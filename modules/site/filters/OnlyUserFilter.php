<?php

namespace app\modules\site\filters;

use Yii;
use yii\base\ActionFilter;

class OnlyUserFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->controller->goHome();
        }
        
        return parent::beforeAction($action);
    }
}