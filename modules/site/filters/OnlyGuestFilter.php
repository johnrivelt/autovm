<?php

namespace app\modules\site\filters;

use Yii;
use yii\base\ActionFilter;

class OnlyGuestFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->controller->redirect(['/site/user/index']);
        }

        return parent::beforeAction($action);
    }
}