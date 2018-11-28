<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuario".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $access_token
 * @property string $auth_key
 */
class Usuario extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
	public $password_repeat;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
    	return 'usuario';
    }

    public function beforeSave($insert)
    {
    	if(!parent::beforeSave($insert)){
    		return false;
    	}

    	if($insert){
    		$this->access_token = Yii::$app->security->generateRandomString(82);
    		$this->auth_key = Yii::$app->security->generateRandomString(45);
    	}

    	if(isset($this->dirtyAttributes['password'])){
    		$this->password=sha1($this->password);
    	}

    	return true;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
    	return [
    		[['username', 'password', 'password_repeat'], 'required'],
    		[['username', 'password', 'auth_key'], 'string', 'max' => 45],
    		[['access_token'], 'string', 'max' => 82],
    		[['username'], 'unique'],
    		[['password_repeat'], 'compare', 'compareAttribute' => 'password'],
    		//[['password'], 'compare', 'compareAttribute' => 'password_repeat']
    	];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
    	return [
    		'id' => Yii::t('app', 'ID'),
    		'username' => Yii::t('app', 'Username'),
    		'password' => Yii::t('app', 'Password'),
    		'access_token' => Yii::t('app', 'Access Token'),
    		'auth_key' => Yii::t('app', 'Auth Key'),
    		'password_repeat' => Yii::t('app', 'Password Repeat'),
    	];
    }

    public static function findIdentity($id)
    {
    	return self::find()->where(['id'=>$id])->one();
        //return self::findOne($id);
    }
    
    public static function findIdentityByAccessToken($token, $type = null)
    {
    	return self::findOne(['access_token' => $token]);
    }
    
    public function getId()
    {
    	return $this->idusuario;   
    }
    
    public function getAuthKey()
    {
    	return $this->auth_key;  
    }
    
    public function validateAuthKey($authKey)
    {
    	return ($this->getAuthKey() === $authKey);
    }
    
    public function findByUsername($username)
    {
    	return self::findOne(['username' => $username]);
    }

    public function validatePassword($password){
    	return ($this->password === sha1($password));
    }
}
