<?php

namespace backend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $photo
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $create_time
 * @property integer $updated_time
 * @property integer $last_login_time
 * @property integer $last_login_ip
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $imgFile;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }
    //定义场景常量

    const SCENARIO_ADD ='add';
//定义场景字段
//    public function scenarios()
//    {
//        $scenarios=parent::scenarios();
//        $scenarios[self::SCENARIO_ADD]=['username','imgFile','password_hash','code','email'];
//        $scenarios[self::SCENARIO_EDIT]=['code','username','imgFile','sex','password_hash','email'];
//        return $scenarios;
//    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password_hash','required','on'=>self::SCENARIO_ADD],
            ['password_hash','string','length'=>[6,10],'tooShort'=>'密码不够六位','on'=>self::SCENARIO_ADD],
            [['username', 'email'], 'required','message'=>'{attribute}不能为空'],
            [['status', 'create_time', 'updated_time', 'last_login_time'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique','message'=>'{attribute}已被注册'],
            [['email'], 'unique','message'=>'{attribute}已被注册'],
            [['password_reset_token'], 'unique'],
            ['imgFile','file','extensions'=>['jpg','png','gif']],
            ['imgFile','file','skipOnEmpty'=>false,'on'=>self::SCENARIO_ADD]];
            //添加不能跳过

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'imgFile'=>'选择一个头像',
            'password_hash' => '密码',
            'password_reset_token' => 'Password Reset Token',
            'email' => '邮箱',
            'status' => '状态',
            'create_time' => '创建时间',
            'updated_time' => '更新时间',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录ip',
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method.
        //通过id获取账号
        return self::findOne(['id'=>$id]);

    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        // TODO: Implement getId() method.
        //获取当前账号的id
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {

        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {

        return $this->auth_key===$authKey;
    }
    public function generteAuthKey(){

        $this->auth_key==Yii::$app->security->generateRandomString();

        $this->save();
    }



    public static  function findByUsername($username){

        return static::findOne(['username'=>$username]);
    }


}
