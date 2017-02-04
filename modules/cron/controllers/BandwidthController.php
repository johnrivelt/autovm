<?php

namespace app\modules\cron\controllers;

use Yii;
use yii\web\Controller;

use app\models\Vps;
use app\models\VpsAction;
use app\models\Plan;
use app\models\Server;
use app\extensions\Api;
use app\models\Bandwidth;

class BandwidthController extends Controller
{
    public function actionIndex()
    {
        $servers = Server::find()->all();
        
        foreach ($servers as $server) {
            
            $data = [
                'server' => $server->getAttributes(),
            ];

            $api = new Api;
	    $api->setUrl(Yii::$app->setting->api_url);
	    $api->setData($data);
			
	    $result = $api->request(Api::ACTION_BANDWIDTH);
            
            if (!$result) {
                continue;
            }
                
            $virtualServers = @json_decode($result->data);
         
            if ($virtualServers) {
            
                foreach ($virtualServers as $ip => $data) {

                    $vps = Vps::findByIp($ip);

                    if ($vps) {

	 	        echo $vps->id;
		        echo '<br>';

                        $plan = Plan::find()->where(['id'=>$vps->plan_id])->one() ;

                        $bw = ($plan ? $plan->band_width : $vps->vps_band_width);

                        $lastBandwidth = Bandwidth::find()->where(['vps_id' => $vps->id])->active()->orderBy('id DESC')->one();
                        $lastUsed = ($lastBandwidth ? $lastBandwidth->used : 0);
                        
                        $bandwidth = new Bandwidth;
                        $bandwidth->vps_id = $vps->id;

                        $bandwidth->used = $data->bandwidth;
                        
                        if ($data->bandwidth >= $lastUsed) {
                            $bandwidth->pure_used = ($data->bandwidth - $lastUsed);
                        } else {
                            $bandwidth->pure_used = $data->bandwidth;
                        }

                        if ($data->bandwidth < $lastUsed) {
				$bandwidth->used += $lastUsed;
			}

                        $bandwidth->save(false);

			if (($lastUsed/1024/1024) >= $bw) {
                               
		   	    $data = [
				'ip' => $vps->ip->getAttributes(),
				'vps' => $vps->getAttributes(),
			 	'server' => $server->getAttributes(),
			    ];

			    $api->setData($data);
                         
                            $result = $api->request(Api::ACTION_STOP);
                            
                            if ($result) {
                                $vps->status = Vps::STATUS_INACTIVE;
                                $vps->save(false);
                                
                                $action = new VpsAction;
                                $action->vps_id = $vps->id;
                                $action->action = VpsAction::ACTION_STOP;
                                $action->save(false);
                            }
                        }
                    }
                } 
            }
        }
    }
}
