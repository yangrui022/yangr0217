<?php

namespace frontend\controllers;


use frontend\models\Lactions;
use frontend\models\Locations;
use frontend\models\LoginForm;
use frontend\models\Member;

class UserController extends \yii\web\Controller
{
    public $layout='login';
    public function actionRegister()
    {

        $model=new Member();
        //加载数据和验证数据
        if($model->load(\Yii::$app->request->post()) && $model->validate()){

            $model->save();
            return $this->redirect(['user/login']);
    }

        return $this->render('register',['model'=>$model]);
    }

    public function actionLogin()
    {
        $model=new LoginForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //找到用户信息
            $member=Member::findOne(['username'=>$model->username]);
            $member->last_login_ip=ip2long(\Yii::$app->request->userIP);
            //保存登录时间
            $member->last_login_time=time();
            $member->save(false);
           return $this->redirect(['user/index']);
        }

       return $this->render('login',['model'=>$model]);
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['order/address']);
    }



    public function actionIndex()
    {


        return $this->render('index');
    }

}
