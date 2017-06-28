<?php

namespace backend\controllers;

use backend\models\Order;

class OrderController extends \yii\web\Controller
{
    public function actionIndex()


    {
        $orders=Order::find()->all();


        return $this->render('index',['orders'=>$orders]);
    }

    public function actionStuats($id){

        $order=Order::findOne($id);

        $order->stuats=1;

        $order->save();

        return $this->redirect('index');
    }
}
