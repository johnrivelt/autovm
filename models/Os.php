<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "os".
 *
 * @property string $id
 * @property string $name
 * @property string $type
 * @property string $operation_system
 * @property string $username
 * @property string $password
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Vps[] $vps
 */
class Os extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'os';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name', 'type', 'username', 'password', 'operation_system'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
			'username' => Yii::t('app', 'Username'),
			'password' => Yii::t('app', 'Password'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'operation_system' => Yii::t('app', 'Operation System'),

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVps()
    {
        return $this->hasMany(Vps::className(), ['os_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\queries\OsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\OsQuery(get_called_class());
    }

    public static function getOperationSystem()
    {
     	$list =  array(
                'windows 2003 32 bit',
                'windows 2008 32 bit',
                'windows 2008 64 bit',
                'windows 2012 64 bit',
                'windows 2016 64 bit',
                'ubuntu 16.04 32 bit',
                'ubuntu 16.04 64 bit',
                'centos 7 64 bit',
                'centos 6.8 32 bit',
                'centos 6.8 64 bit',                
                'debian 8.5 32 bit',
                'debian 8.5 64 bit'
            );

	return array_combine($list, $list);
    }
    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['name', 'type', 'username', 'password'],
        ];
    }
        
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
}
