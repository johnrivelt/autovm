<?php

namespace app\modules\admin\filters;

use Yii;
use yii\base\ActionFilter;

class OnlyAdminFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->getIsAdmin()) {
            Yii::$app->controller->goHome();
        }
        
        return parent::beforeAction($action);
    }
}