<?php
namespace backend\models;

use yii\base\Model;


Class EditPwdForm extends Model{

    public $old_password;
    public $re_password;
    public $new_password;
    public $code;

    public function attributeLabels()
    {
        return [
            'old_password'=>'旧密码',
            'new_password'=>'新密码',
            're_password'=>'确认新密码',
            'code'=>'验证码',
        ];
    }
    public function rules()
    {
        return [

            [['code', 'old_password', 'new_password', 're_password'], 'required'],


        ];
    }




}