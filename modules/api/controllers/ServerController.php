<?php

namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;

use app\models\Ip;
use app\modules\api\filters\Auth;
use app\modules\api\components\Status;

class ServerController extends Controller
{
    public function behaviors()
    {
        return [
            Auth::className(),
        ];
    }
    
    public function actionIp()
    {
        $serverId = Yii::$app->request->post('serverId');
        
        $ips = Ip::find()->where(['server_id' => $serverId])->all();
        
        return [
            'ok' => true, 
            'ips' => ArrayHelper::map($ips, 'id', 'ip'),
        ];
    }
}