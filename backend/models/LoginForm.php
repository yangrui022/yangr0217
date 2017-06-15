<?php
namespace backend\models;

use yii\base\Model;

Class LoginForm extends Model{

    public $username;
    public $password_hash;
    public $time;
    public $code;
    public $flag;
    private $_user=false;

    public function attributeLabels()
    {
        return [
            'password_hash'=>'密码',
            'username'=>'用户名',
            'code'=>'验证码',
            'flag'=>'记住密码',


        ];
    }
    public function rules()
    {
        return [
            [['username','password_hash','code'],'required','message'=>'{attribute}不能为空'],
            ['code','captcha','captchaAction'=>'login/captcha','message'=>'验证码错误！'],
            //添加自定义验证方法
            ['username','validateUsername'],
            ['flag','boolean']

        ];

    }


        public function validateUsername(){
            //获取用户信息
            $name= User::findOne(['username'=>$this->username]);
            $old=\Yii::$app->security->generatePasswordHash($this->password_hash);
            //判断用户信息是否存在
            if($name){//存在
                //用户存在 验证密码
                if(!\Yii::$app->security->validatePassword($this->password_hash,$old)){
                    $this->addError('password_hash','密码不正确');
                }else{
                    //账号秘密正确，登录
                    \Yii::$app->user->login($name);
                }
            }else{//不存在时
                $this->addError('username','账号不正确');
            }
        }

        public function login(){

            if($this->validate()){
                if($this->flag){
                    return \Yii::$app->user->login($this->getUser(),$this->flag?3600*24*30:0);
                }

            }
        return false;

        }

    public function getUser(){

            if($this->_user===false){
                    $this->_user=User::findByUsername($this->username);
            }
        return $this->_user;
    }

}
