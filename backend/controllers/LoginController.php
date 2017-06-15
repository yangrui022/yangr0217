<?php
namespace backend\controllers;

use backend\models\LoginForm;
use backend\models\User;
use yii\web\Controller;
use yii\web\Cookie;

Class LoginController extends Controller{

    public function actionIndex()
    {
        if(!\Yii::$app->user->isGuest){
            return $this->goHome();
        }

        $model = new LoginForm();


        if ($model->load(\Yii::$app->request->post()) && $model->login()) {

           if ($model->validate()) {
//
                $user=User::findOne(['username'=>$model->username]);
                $user->last_login_ip=\Yii::$app->request->userIP;
                //保存登录时间
                $user->last_login_time=time();
//                var_dump($user->last_login_ip);exit;
                $user->save(false);
                \Yii::$app->session->setFlash('success','登录成功');
            return $this->redirect(['user/index']);
//
        }
        }
        return $this->render('index',['model'=>$model]);
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['login/index']);
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,
                'maxLength'=>5
            ],
        ];
    }
}