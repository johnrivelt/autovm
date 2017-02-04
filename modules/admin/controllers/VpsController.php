<?php

namespace app\modules\admin\controllers;

use app\models\Ssh;
use app\models\VpsIp;
use app\extensions\Api;
use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

use app\models\Ip;
use app\models\Os;
use app\models\Vps;
use app\models\Plan;
use app\models\Server;
use app\models\Bandwidth;
use app\models\VpsAction;
use app\models\Datastore;
use app\modules\admin\filters\OnlyAdminFilter;
use yii\data\ActiveDataProvider;
use app\models\searchs\searchVps;

class VpsController extends Controller
{
    public function behaviors()
    {
        return [
            OnlyAdminFilter::className(),
        ];
    }
    
    public function actionIndex()
    {
        $searchModel = new searchVps();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
	
	public function actionView($id)
	{
		$vps = Vps::findOne($id);
		
		if (!$vps) {
			throw new NotFoundHttpException(Yii::t('app', 'Not found anything'));
		}
		
		$bandwidth = floor(Bandwidth::find()->where(['vps_id' => $id])->active()->sum('pure_used') / 1024);
        $used_bandwidth = Bandwidth::find()->where(['vps_id'=>$id])->active()->orderBy('id desc')->one();
        $used_bandwidth=isset($used_bandwidth->used)?$used_bandwidth->used:0;
		return $this->render('view', [
			'vps' => $vps,
			'bandwidth' => $bandwidth,
            'used_bandwidth'=>$used_bandwidth
		]);
	}
	
	public function actionResetBandwidth($id)
	{
		$vps = Vps::findOne($id);
		
		if ($vps) {
			Bandwidth::updateAll(['status' => Bandwidth::STATUS_INACTIVE], 'vps_id = :id', [':id' => $vps->id]);
		}
		
		return $this->redirect(['/admin/vps/view', 'id' => $id]);
	}
    
    public function actionCreate($id)
    {
        $model = new Vps();
        $model->user_id = $id;
        if(Yii::$app->request->isPost) {
            //print_r($_POST);exit;
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->plan_type = $_POST['Vps']['plan_type'];
                if($model->plan_type==VpsPlansTypeCustom)
                {
                    $model->vps_ram = isset($_POST['Vps']['vps_ram']) && intval($_POST['Vps']['vps_ram']) ? $_POST['Vps']['vps_ram'] : 0;
                    $model->vps_hard = isset($_POST['Vps']['vps_hard']) ? $_POST['Vps']['vps_hard'] : 0;
                    if($model->vps_hard <21)
                    {
                        $model->addError('vps_hard',Yii::t('app','hard size must be grater than 21'));
                        return $this->sharedRender($model);
                    }
                    $model->vps_cpu_core = $_POST['Vps']['vps_cpu_core'];
                    $model->vps_cpu_mhz = $_POST['Vps']['vps_cpu_mhz'];
                    $model->vps_band_width = $_POST['Vps']['vps_band_width'];
                    $model->plan_id = 0;
                    //var_dump($model);exit;

                }
                else
                {

                }
                $model->os_id=0;
                $model->password='';
                Yii::$app->db->createCommand('set foreign_key_checks=0')->execute();
                if ($model->save()) {
                    //delete recent vps_ip
                    $oldIp = VpsIp::find()->where(['vps_id' => $model->id])->one();
                    if ($oldIp) {
                        $oldIp->delete();
                    }
                    //add new vps_ip
                    $ip = new VpsIp;
                    $ip->vps_id = $model->id;
                    $ip->ip_id = $model->ip_id;
                    $ip->save();
                    Yii::$app->session->addFlash('success', Yii::t('app', 'Your new vps has been created'));
                    return $this->refresh();
                }
                else {
                    var_dump($model->errors);
                    exit;
                }
            } else
            {
                var_dump($model->errors);exit;
            }

        }
        $model->plan_type = VpsPlansTypeDefault;
        return $this->sharedRender($model);
    }
    
    public function actionEdit($id)
    {
        $model = Vps::findOne($id);
        
        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found anything'));
        }
        Yii::$app->db->createCommand('set foreign_key_checks=0')->execute();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->plan_type = $_POST['Vps']['plan_type'];
            if($model->plan_type==VpsPlansTypeCustom)
            {
                $model->vps_ram = $_POST['Vps']['vps_ram'];
                $model->vps_hard = $_POST['Vps']['vps_hard'];
                $model->vps_cpu_core = $_POST['Vps']['vps_cpu_core'];
                $model->vps_cpu_mhz = $_POST['Vps']['vps_cpu_mhz'];
                $model->vps_band_width = $_POST['Vps']['vps_band_width'];
                $model->plan_id = 0;
                //var_dump($model);exit;

            }
            if ($model->save()) {
                //delete recent vps_ip
                $oldIp = VpsIp::find()->where(['vps_id' => $model->id])->one();
                if ($oldIp) {
                    $oldIp->delete();
                }
                //add new vps_ip
                $ip = new VpsIp;
                $ip->vps_id = $model->id;
                $ip->ip_id = $model->ip_id;
                $ip->save();
                Yii::$app->session->addFlash('success', Yii::t('app', 'Vps has been edited'));
				//turn off vps
                if($model->status == Vps::STATUS_INACTIVE)
                {
                    $data = [
                        'ip' => $model->ip->getAttributes(),
                        'vps' => $model->getAttributes(),
                        'server' => $model->server->getAttributes(),
                    ];
                    
                    $api = new Api();
                    $api->setUrl(Yii::$app->setting->api_url);
                    $api->setData($data);
                    $result = $api->request(Api::ACTION_STOP);
                    if ($result) {
                        $action = new VpsAction;
                        $action->vps_id = $model->id;
                        $action->action = VpsAction::ACTION_STOP;
                        $action->save(false);
                    }
                    
                }
                else 
                {
                    $data = [
                        'ip' => $model->ip->getAttributes(),
                        'vps' => $model->getAttributes(),
                        'server' => $model->server->getAttributes(),
                    ];
                    
                    $api = new Api();
                    $api->setUrl(Yii::$app->setting->api_url);
                    $api->setData($data);
                    $result = $api->request(Api::ACTION_START);
                    if ($result) {
                        $action = new VpsAction;
                        $action->vps_id = $model->id;
                        $action->action = VpsAction::ACTION_START;
                        $action->save(false);
                    }
               }
                return $this->refresh();
            }
        } 
        
        return $this->sharedRender($model);
    }
    
    public function actionLog($id)
    {
        $vps = Vps::findOne($id);
        
        if (!$vps) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found anything'));
        }
        
        $logs = VpsAction::find()->orderBy('id DESC');
        
        $count = clone $logs;
        $pages = new Pagination(['totalCount' => $count->count()]);
        $pages->setPageSize(10);
        
        $logs = $logs->offset($pages->offset)->limit($pages->limit)->all();
        
        return $this->render('log', [
            'vps' => $vps,
            'logs' => $logs,
            'pages' => $pages,
        ]);
    }
    
    public function actionDatastores()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $id = Yii::$app->request->post('id'); 
        
        $datastores = Datastore::find()->where(['server_id' => $id])->all();
        
        return ArrayHelper::map($datastores, 'id', 'value');
    }
    
    public function actionIps()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $id = Yii::$app->request->post('id'); 
        
        $ips = Ip::find()->leftJoin('vps_ip', 'vps_ip.ip_id = ip.id')
            ->andWhere('vps_ip.id IS NULL')
            ->andWhere(['ip.server_id' => $id])
            ->all();
            
        return ArrayHelper::map($ips, 'id', 'ip');
    }
    
    public function actionDelete()
    {
        if (($data = Yii::$app->request->post('data')) && is_array($data)) {
            foreach ($data as $id) {
                Vps::findOne($id)->delete();
            }
        }
        
        return $this->redirect(Yii::$app->request->referrer);
    }
    
    protected function sharedRender($model)
    {
        return $this->render($model->isNewRecord ? 'create' : 'edit', [
            'model' => $model,
            'plans' => Plan::find()->all(),
            'servers' => Server::find()->all(),
            'operationSystems' => Os::find()->all(),
        ]);
    }
    public function actionInformations()
    {
        $serverid=$_GET['serverId'];
        $ip=$_GET['ip'];
        $datastore=$_GET['datastore'];

        $server = Server::findOne($serverid);
        if(!$server) {
            exit(json_encode(array('status'=>'could not find server')));
        }

        $datastore = Datastore::findOne($datastore);
        if(!$datastore) {
            exit(json_encode(array('status'=>'could not find datastore')));
        }

        $ssh = new Ssh($server->ip,$server->username,$server->password,$server->port);
        if(!$ssh) {
            exit(json_encode(array('status'=>'failed connect/login to server')));
        }

        $ip = Ip::findOne($ip);

        $allVms = $ssh->exec('vim-cmd vmsvc/getallvms');
        $pathData = Yii::app()->helper->findVmId($ip->ip, $allVms);
        if(!isset($pathData[3])) {
            exit(json_encode(array('status'=>'failed to get datastore path')));
        }

        $vmxPath = '/vmfs/volumes/datastore'.$datastore->value.'/'.$pathData[3].'.vmx';
        $vmxData = $ssh->exec("cat '{$vmxPath}'");
        preg_match_all('/(numvcpus|memsize|sched\.cpu\.max)\s+\=\s+\"(.*?)\".*/i',$vmxData,$informations);

        if(!isset($informations[1]))
        {
            exit(json_encode(array('status'=>'failed to get informations')));
        }

        $_informations = array('numvcpus'=>0, 'sched.cpu.max'=>0, 'memSize'=>0);
        foreach($informations[1] as $index => $name)
        {
            $_informations[$name] = $informations[2][$index];
        }

        echo json_encode(array('status'=>'success','ram'=>$_informations['memSize'],'cpu'=>$_informations['sched.cpu.max'],'cpuCores'=>$_informations['numvcpus']));
    }
}
