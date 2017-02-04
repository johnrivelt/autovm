<?php

namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use app\extensions\Api;

use app\models\Ip;
use app\models\Vps;
use app\models\VpsIp;
use app\modules\api\filters\Auth;
use app\modules\api\components\Status;

class VpsController extends Controller
{
    public function behaviors()
    {
        return [
            Auth::className(),
        ];
    }
    
    public function actionInfo()
    {
        $vps = Vps::findOne(Yii::$app->request->post('id'));
        
        if (!$vps) {
            return ['ok' => false, 'status' => Status::NOT_FOUND];
        }
        
        return [
            'ok' => true,
            'id' => $vps->id,
            'ip' => $vps->ip->ip,
        ];
    }
    
    public function actionCreate()
    {
	
	require Yii::getAlias('@app/extensions/jdf.php');	

        $request = Yii::$app->request;
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $ip = Ip::find()->leftJoin('vps_ip', 'vps_ip.ip_id = ip.id')
                ->andWhere('vps_ip.id IS NULL')
                ->andWhere(['ip.server_id' => $request->post('serverId')])
                ->isPublic();
            
            if ($ipId = $request->post('ipId')) {
                $ip->andWhere(['ip.id' => $ipId]);   
            }
            
            $ip = $ip->one();
            
            if (!$ip) {
                throw new \Exception('Cannot found ip');
            }
            
            $vps = new Vps;
            
            $vps->user_id = $request->post('userId');
            $vps->server_id = $request->post('serverId');
            $vps->datastore_id = $request->post('datastoreId');
            $vps->os_id = $request->post('osId');
            $vps->password = $request->post('password');
            
            if ($planId = $request->post('planId')) {
                $vps->plan_id = $planId;   
                $vps->plan_type = VpsPlansTypeDefault;
            } else {
		$vps->plan_type = VpsPlansTypeCustom;
                $vps->vps_ram = $request->post('vpsRam');
                $vps->vps_cpu_mhz = $request->post('vpsCpuMhz');
                $vps->vps_cpu_core = $request->post('vpsCpuCore');
                $vps->vps_hard = $request->post('vpsHard');
                $vps->vps_band_width = $request->post('vpsBandwidth');
            }

	    $vps->reset_at = date('j');
            
            if (!$vps->save(false)) {
                throw new \Exception('Cannot save vps');
            }
            
            $vpsIp = new VpsIp;
            
            $vpsIp->ip_id = $ip->id;
            $vpsIp->vps_id = $vps->id;
            
            if (!$vpsIp->save(false)) {
                throw new \Exception('Cannot save vps ip');
            }
            
            $transaction->commit();
            
            return [
                'ok' => true,
                'id' => $vps->id,
                'ip' => $ip->ip,
            ];
            
        } catch (\Exception $e) {
            $transaction->rollBack();
            
            return ['ok' => false, 'e' => $e->getMessage(), 'status' => Status::ERROR_SYSTEM];
        }
    }
    
    public function actionResetBandwidth()
    {
        $vps = Vps::findOne(Yii::$app->request->post('id'));
        
        if (!$vps) {
            return ['ok' => false, 'status' => Status::NOT_FOUND];
        }
        
        $save = Bandwidth::updateAll(['status' => Bandwidth::STATUS_INACTIVE], 'vps_id = :id', [':id' => $vps->id]);
        
        if (!$save) {
            return ['ok' => false, 'status' => Status::ERROR_SYSTEM];
        }
        
        return ['ok' => true];
    }
    
    public function actionActive()
    {
        $vps = Vps::findOne(Yii::$app->request->post('id'));
        
        if (!$vps) {
            return ['ok' => false, 'status' => Status::NOT_FOUND];
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $vps->status = Vps::STATUS_ACTIVE;
            
            if (!$vps->save(false)) {
                throw new \Exception('Cannot save vps');
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
            
            if (!$result) {
                throw new \Exception('Cannot start vps');
            }
            
            $transaction->commit();
            
            return ['ok' => true];
            
        } catch (Exception $e) {
            $transaction->rollBack();
            
            return ['ok' => false, 'e' => $e->getMessage(), 'status' => Status::ERROR_SYSTEM];
        }
    }
    
    public function actionInactive()
    {
        $vps = Vps::findOne(Yii::$app->request->post('id'));
        
        if (!$vps) {
            return ['ok' => false, 'status' => Status::NOT_FOUND];
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $vps->status = Vps::STATUS_INACTIVE;
            
            if (!$vps->save(false)) {
                throw new \Exception('Cannot save vps');
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
            
            if (!$result) {
                throw new \Exception('Cannot stop vps');
            }
            
            $transaction->commit();
            
            return ['ok' => true];
            
        } catch (Exception $e) {
            $transaction->rollBack();
            
            return ['ok' => false, 'e' => $e->getMessage(), 'status' => Status::ERROR_SYSTEM];
        }
    }

    public function actionDelete()
    {
        $vps = Vps::findOne(Yii::$app->request->post('id'));
        
        if (!$vps) {
            return ['ok' => false, 'status' => Status::NOT_FOUND];
        }
        
#        $data = [
#            'ip' => $vps->ip->getAttributes(),
#            'vps' => $vps->getAttributes(),
#            'server' => $vps->server->getAttributes(),
#        ];
#
#        $api = new Api;
#        $api->setUrl(Yii::$app->setting->api_url);
#        $api->setData($data);
#
#        $result = $api->request(Api::ACTION_DELETE);
#
#        if (!$result) {
#            return ['ok' => false, 'status' => Status::ERROR_SYSTEM];
#        }
        
        $vps->delete();
        
        return ['ok' => true];
    }
}
