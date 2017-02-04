<?php

namespace app\modules\site\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

use app\models\Os;
use app\models\Vps;
use app\extensions\Api;
use app\models\VpsAction;
use app\models\Bandwidth;
use app\models\Datastore;
use app\filters\LicenseFilter;
use app\modules\site\filters\OnlyUserFilter;

class VpsController extends Controller
{
    public function behaviors()
    {
        return [
            OnlyUserFilter::className(),
        ];
    }
    
    public function actionIndex($id)
    {        
        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $id])->active()->one();
        $used_bandwidth = Bandwidth::find()->where(['vps_id'=>$id])->active()->orderBy('id desc')->one();

        $used_bandwidth=isset($used_bandwidth->used)?$used_bandwidth->used:0;
        if (!$vps) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found anything'));
        }
      
        return $this->render('index', [
            'vps' => $vps,
            'used_bandwidth'=>$used_bandwidth
        ]);
    }
    
    public function actionBandwidth()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (!($vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0];
        } 
        
        $times = [];
        
        for ($i=10; $i>=0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $times[$date] = ['date' => $date, 'total' => 0];
        }
        
        $sql = "SELECT SUM(pure_used) as total, FROM_UNIXTIME(created_at, '%Y-%m-%d') date FROM bandwidth WHERE vps_id = :id AND status = :status GROUP BY date";
        $result = Yii::$app->db->createCommand($sql);
        $result->bindValue(':id', $vpsId);
        $result->bindValue(':status', Bandwidth::STATUS_ACTIVE);
        $result = $result->queryAll(); 
       
        foreach ($result as $data) {
            if (isset($times[$data['date']])) {
                $times[$data['date']]['total'] = floor($data['total'] / 1024); // kb
            }
        }
        
        return array_values($times);
    }
    
    public function actionSelectOs()
    {
        $id = Yii::$app->request->post('vpsId');
        
        
        $operationSystems = Os::find()->all();

        return $this->renderAjax('select-os', [
            'operationSystems' => $operationSystems,
            'vpsId' => $id,
        ]);
    }

    public function actionInstall()
    {
        Yii::$app->session->set('password',  Yii::$app->request->post('password'));
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (!($password = Yii::$app->request->post('password')) || !($vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0];
        }
        
        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $vpsId])->active()->one();
        
        if (!$vps) {
            return ['status' => 0];
        }
        
        $datastore = Datastore::find()->where(['server_id' => $vps->server->id])->defaultScope()->one();
        
        if (!$datastore) {
            return ['status' => 0];
        }
      
        $os = Os::findOne(Yii::$app->request->post('osId'));
        
        if (!$os) {
            return ['status' => 0];
        }
        
        // validate password
        if (!preg_match('/(?=.*[A-Z]+)(?=.*[a-z]+)(?=.*[0-9]+)/', $password)) {
            return ['status' => 1];
        }
        
        $vps->os_id = $os->id;
        $vps->password = $password;
        
        if (!$vps->save(false)) {
            return ['status' => 0];
        }
        
        // raw password
        $vps->password = $password;
        
        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'os' => $vps->os->getAttributes(),
            'datastore' => $vps->datastore->getAttributes(),
            'defaultDatastore' => $datastore->getAttributes(),
            'server' => $vps->server->getAttributes(),
        ];
        
        if ($vps->plan) {
            $data['plan'] = $vps->plan->getAttributes();   
        }
        
        $api = new Api;
        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);
                
        $result = $api->request(Api::ACTION_INSTALL);
     
        if ($result) {
            $action = new VpsAction;
            $action->vps_id = $vps->id;
            $action->action = VpsAction::ACTION_INSTALL;
            $action->description = $vps->os->name;
            $action->save(false);
             
            return ['status' => Yii::$app->request->post('password')];
        }
        
        return ['status' => 0];
    }

    public function actionStart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (!($vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0];
        }
        
        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $vpsId])->active()->one();
        
        if (!$vps) {
            return ['status' => 0];
        }
        
        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'server' => $vps->server->getAttributes(),
        ];

        $api = new Api;
        
        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);
        
        $result = $api->request(Api::ACTION_START);

        if ($result) {
            $action = new VpsAction;
            $action->vps_id = $vps->id;
            $action->action = VpsAction::ACTION_START;
            $action->save(false);

            return ['status' => 1];
        }
        
        return ['status' => 0];
    }
    
    public function actionStop()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (!($vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0];
        }
        
        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $vpsId])->active()->one();
        
        if (!$vps) {
            return ['status' => 0];
        }
        
        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'server' => $vps->server->getAttributes(),
        ];

        $api = new Api;
        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);
        
        $result = $api->request(Api::ACTION_STOP);

        if ($result) {
            $action = new VpsAction;
            $action->vps_id = $vps->id;
            $action->action = VpsAction::ACTION_STOP;
            $action->save(false);
            
            return ['status' => 1];
        }

        return ['status' => 0];
    }
    
    public function actionRestart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (!($vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0];
        }
        
        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $vpsId])->active()->one();
        
        if (!$vps) {
            return ['status' => 0];
        }
        
        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'server' => $vps->server->getAttributes(),
        ];

        $api = new Api;
        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);
        
        $result = $api->request(Api::ACTION_RESTART);

        if ($result) {
            $action = new VpsAction;
            $action->vps_id = $vps->id;
            $action->action = VpsAction::ACTION_RESTART;
            $action->save(false);
            
            return ['status' => 1];
        }
        
        return ['status' => 0];
    }
    
    public function actionStatus()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (!($vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0];
        }
        
        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $vpsId])->active()->one();
        
        if (!$vps) {
            return ['status' => 0];
        }
        
        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'server' => $vps->server->getAttributes(),
        ];

        $api = new Api;
        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);
        
        $result = $api->request(Api::ACTION_STATUS);
                                
        if (!$result) {
            return ['status' => 0];
        }
        
        if ($result->power == 'on') {
            return ['status' => 1];
        } else {
            return ['status' => 2];
        }
    }
    
    public function actionMonitor()
    {
        if (!($vpsId = Yii::$app->request->post('vpsId'))) {
            return false;
        }
        
        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $vpsId])->active()->one();
        
        if (!$vps) {
            return false;
        }
        
        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'server' => $vps->server->getAttributes(),
        ];

        $api = new Api;
        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);
        
        $result = $api->request(Api::ACTION_MONITOR);
        
        if (!$result) {
            return false;
        }
                
        return $this->renderAjax('monitor', [
            'vps' => $vps,
            'ram' => $result->ram,
            'usedRam' => $result->usedRam,
            'cpu' => $result->cpu,
            'usedCpu' => $result->usedCpu,
            'uptime' => Yii::$app->helper->calcTime($result->uptime),
        ]);
    }
    
    public function actionActionLog()
    {
        if (!($vpsId = Yii::$app->request->post('vpsId'))) {
            return false;
        }
        
        $actions = VpsAction::find()->where(['vps_id' => $vpsId])->orderBy('id DESC');
        
        $count = clone $actions;
        $pages = new Pagination(['totalCount' => $count->count()]);
        $pages->setPageSize(5);
        
        $actions = $actions->offset($pages->offset)->limit($pages->limit)->all();
        
        return $this->renderAjax('action-log', [
            'actions' => $actions,
            'pages' => $pages,
            'vpsId' => $vpsId,
        ]);
    }
}
