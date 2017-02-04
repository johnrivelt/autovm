<?php

namespace app\modules\admin\models\forms;

use Yii;
use yii\base\Model;

class SettingForm extends Model
{
    public $title;
    public $api_url;
    
    public function rules()
    {
        return [
            [['title', 'api_url'], 'required'],
        ];
    }    
    
    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'Title'),
            'api_url' => Yii::t('app', 'Api Url'),
        ];
    }
}