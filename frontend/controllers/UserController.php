<?php

namespace frontend\controllers;


use frontend\models\Lactions;

use frontend\models\LoginForm;
use frontend\models\Member;


use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;

class UserController extends \yii\web\Controller
{
    public $layout='login';
    public function actionRegister()
    {

        $model=new Member(['scenario'=>Member::SCENARIO_REGISTER]);
        //加载数据和验证数据
//        var_dump(\Yii::$app->request->post());exit;

        if($model->load(\Yii::$app->request->post()) && $model->validate()){

            $model->save(false);
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
           return $this->redirect(['index/index']);
        }

       return $this->render('login',['model'=>$model]);
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['user/login']);
    }

    //发送短信验证码测试
    public function actionSend(){

        // 配置信息
        $config = [
            'app_key'    => '24480029',
            'app_secret' => '1441e980e19368768db330d4b1ff157e',
            // 'sandbox'    => true,  // 是否为沙箱环境，默认false
        ];


// 使用方法一
        $client = new Client(new App($config));
        $req    = new AlibabaAliqinFcSmsNumSend;
        $code=rand(10000,99999);
        $req->setRecNum('15608058683')
            ->setSmsParam([
                'username'=>'杨睿',
                'code' => $code,
            ])
            ->setSmsFreeSignName('杨睿')
            ->setSmsTemplateCode('SMS_71530128');

        $resp = $client->execute($req);
        var_dump($code);
    }


    public function actionSendsms(){

        //判断电话号码是否正确
        $data=\Yii::$app->request->post();
//        var_dump($data);exit;
        if(!preg_match('/^1[34578]\d{9}$/',$data['tel'])){
            echo '电话号码不正确！';
            exit;
        }

        $code=rand(10000,99999);


//        $result = \Yii::$app->sms->setNum($data['tel'])->setParam(['code' => $code,'username'=>$data['username']])->send();
        $result=1;
        if($result){

            \Yii::$app->cache->set('tel_'.$data['tel'],$code,5*60);

            echo 'success'.$code;
        }else{
            echo '发送失败';
        }


    }
}
