<?php

namespace app\modules\cron\controllers;

use Yii;
use yii\web\Controller;

use app\models\Vps;
use app\models\Bandwidth;

class ResetController extends Controller
{
    public function actionIndex()
    {
        require Yii::getAlias('@app/extensions/jdf.php');
        
        $now = [ date('j') ];
        
        if (date('n') == 2 && date('j') == 28) {
            $now = [28, 29, 30];   
        }
       
        $times = implode(',', $now);
        
        $virtualServers = Vps::find()->where("reset_at IN ($times)")->all();
        
        foreach ($virtualServers as $vps) {
            
            Bandwidth::updateAll(['status' => Bandwidth::STATUS_INACTIVE], 'vps_id = :id', [':id' => $vps->id]);
        }
    }
}
