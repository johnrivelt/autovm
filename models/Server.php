<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "server".
 *
 * @property string $id
 * @property string $name
 * @property string $ip
 * @property integer $port
 * @property string $username
 * @property string $password
 * @property string $license
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Datastore[] $datastores
 * @property Ip[] $ips
 * @property Vps[] $vps
 */
class Server extends \yii\db\ActiveRecord
{
    public function afterFind()
    {
        $this->password = Yii::$app->security->decryptByPassword(base64_decode($this->password), Yii::$app->params['secret']);
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'server';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip', 'name', 'port', 'username', 'password', 'license'], 'required'],
            [['port', 'created_at', 'updated_at'], 'integer'],
            [['ip'], 'string', 'max' => 45],
			[['license'], 'string', 'max' => 16],
            [['name', 'username', 'password'], 'string', 'max' => 255]
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
            'ip' => Yii::t('app', 'Ip'),
            'port' => Yii::t('app', 'Port'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
			'license' => Yii::t('app', 'License'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDatastores()
    {
        return $this->hasMany(Datastore::className(), ['server_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIps()
    {
        return $this->hasMany(Ip::className(), ['server_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVps()
    {
        return $this->hasMany(Vps::className(), ['server_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\queries\ServerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\ServerQuery(get_called_class());
    }
    
    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['name', 'ip', 'port', 'username', 'password', 'license'],
        ];
    }
    
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    public function beforeSave($insert)
    {
        $this->password = base64_encode(Yii::$app->security->encryptByPassword($this->password, Yii::$app->params['secret']));
        return parent::beforeSave($insert);
    }
}
