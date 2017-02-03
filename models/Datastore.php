<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "datastore".
 *
 * @property string $id
 * @property string $server_id
 * @property string $value
 * @property string $space
 * @property string $is_default
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Server $server
 * @property Vps[] $vps
 */
class Datastore extends \yii\db\ActiveRecord
{
    const IS_DEFAULT = 1;
    const IS_NOT_DEFAULT = 2;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'datastore';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['server_id', 'value', 'space', 'is_default'], 'required'],
            [['server_id', 'space', 'is_default', 'created_at', 'updated_at'], 'integer'],
            [['value'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'server_id' => Yii::t('app', 'Server ID'),
            'value' => Yii::t('app', 'Value'),
            'space' => Yii::t('app', 'Space'),
            'is_default' => Yii::t('app', 'Is Default'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServer()
    {
        return $this->hasOne(Server::className(), ['id' => 'server_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVps()
    {
        return $this->hasMany(Vps::className(), ['datastore_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\queries\DatastoreQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\DatastoreQuery(get_called_class());
    }
    
    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['server_id', 'value', 'space', 'is_default'],
        ];
    }
    
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    public static function getDefaultYesNo()
    {
        return [
            self::IS_DEFAULT => Yii::t('app', 'Yes'),
            self::IS_NOT_DEFAULT => Yii::t('app', 'No'),
        ];
    }
}
