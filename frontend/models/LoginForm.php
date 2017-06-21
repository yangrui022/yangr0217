<?php
namespace frontend\models;

use yii\base\Model;

class LoginForm extends Model{
    public $username;
    public $password;
    public $rememberMe;
    public $code;


    public function rules()
    {
        return [
            [['username','password','code'], 'required'],
            ['username','validateUsername'],
            ['rememberMe','boolean'],

        ];
    }
    public function attributeLabels()
    {
        return [

            'username'=>'用户名',
            'password'=>'密码',
            'rememberMe'=>'记住我',
            'code'=>'验证码'
        ];
    }
//登录的时候验证用户名和密码
public function validateUsername(){

        //先获取用户信息
    $member=Member::findOne(['username'=>$this->username]);
    //判断用户名是否存在；
    if($member){
        //账号存在后验证密码
        if(!\Yii::$app->security->validatePassword($this->password,$member->password_hash)){
            $this->addError('password','密码或用户不正确！');

        }
        $duration=$this->rememberMe?7*24*3600:0;
        \Yii::$app->user->login($member,$duration);
        return true;
    }else{

        $this->addError('username','密码或用户不正确！');
        }
        return false;

    }

}