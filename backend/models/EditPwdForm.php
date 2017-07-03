<?php
namespace backend\models;

use frontend\models\Member;
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

        ];
    }
    public function rules()
    {
        return [

            [[ 'old_password', 'new_password', 're_password'], 'required'],


        ];
    }


public function getPassword($id){
        $member=Member::findOne(['id'=>$id]);

        if($member){
            //如果用户存在
            if(\Yii::$app->security->validatePassword($this->old_password,$member->password)){

                $this->addError('old_password','旧密码不正确');


            }


        }


}

}