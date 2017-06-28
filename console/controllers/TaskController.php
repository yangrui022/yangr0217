<?php
namespace console\controllers;

use backend\models\Goods;
use frontend\models\Order;
use yii\console\Controller;

class  TaskController extends Controller{


    public function actionClean(){
  set_time_limit(0);
    while (1){


        //找到超时，未支付的超过一小时的订单
        $models=Order::find()->where(['stuats'=>1])->andWhere(['<','create_time',time()-3600])->all();
        foreach ($models as $model){

//            $model->stuats=0;
//            $model->sava();
//            foreach ($model->goods as $goods){
//
//                Goods::updateAllCounters(['stock'=>$goods->amount],'id='.$goods->goods_id);
//            }


            echo 'ID'.$model->id.'clean';
            sleep(1);

        }
    }
    }
}