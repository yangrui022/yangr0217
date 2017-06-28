<?php
namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Order;
use frontend\models\OrderInfo;
use frontend\models\Shopcart;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use yii\db\Exception;
use yii\web\Controller;

class OrderController extends Controller{

    public $layout='order';
    public static $order_info=[

        ['delivery_id'=>1,'delivery_name'=>'普通送货上门','delivery_price'=>20.00,'delivery_info'=>'每张订单不满499.00元,运费20.00元, 订单4...'],
        ['delivery_id'=>2,'delivery_name'=>'特快专递','delivery_price'=>40.00,'delivery_info'=>'每张订单不满499.00元,运费40.00元, 订单4...'],
        ['delivery_id'=>3,'delivery_name'=>'平邮','delivery_price'=>10.00,'delivery_info'=>'每张订单不满499.00元,运费15.00元, 订单4...'],
    ];
    public static $order_payment=[
        ['payment_id'=>1,'payment_name'=>'货到付款','payment_info'=>'送货上门后再收款，支持现金、POS机刷卡、支票支付'],
        ['payment_id'=>2,'payment_name'=>'在线支付','payment_info'=>'即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
        ['payment_id'=>3,'payment_name'=>'上门自取','payment_info'=>'自提时付款，支持现金、POS刷卡、支票支付'],
        ['payment_id'=>4,'payment_name'=>'邮局汇款','payment_info'=>'通过快钱平台收款 汇款后1-3个工作日到账']

    ];
    public function actionOrderInfo(){
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['user/login']);

        }
            $infos=self::$order_info;
            $payments=self::$order_payment;
            //获取当前登录用户的收货地址
        //获取登录用户id
        $user_id=\Yii::$app->user->getId();

        $addresses=Address::find()->where(['user_id'=>$user_id])->all();

        //获取购物车的信息
        $models=[];
        $carts=Shopcart::find()->where(['member_id'=>$user_id])->all();
        foreach ($carts as $cart){
            $goods=Goods::findOne(['id'=>$cart->goods_id])->attributes;

            $goods['amount']=$cart->amount;
            $models[] = $goods;

        }




       return $this->render('order_info',['infos'=>$infos,'addresses'=>$addresses,'payments'=>$payments,'models'=>$models]);
    }

    public function actionOrderCg(){
        $member_id=\Yii::$app->user->getId();





        //获取地址id,配送id,支付id


        $address_id=\Yii::$app->request->post('address');
        $delivery_id=\Yii::$app->request->post('delivery');
        $payment_id=\Yii::$app->request->post('payment');

        $total=\Yii::$app->request->post('total');
        $address=Address::findOne(['id'=>$address_id]);
        $deliverys=self::$order_info;
        $payments=self::$order_payment;

        //实例化订单表单
        $order=new Order();

        $order->member_id=$member_id;
        $order->name=$address->name;
        $order->province=\frontend\models\Locations::getArea($address->province);
        $order->city=\frontend\models\Locations::getArea($address->city);
        $order->area=\frontend\models\Locations::getArea($address->district);
        $order->address=$address->address;
        $order->tel=$address->tel;
        $order->delivery_id=$delivery_id;
        $order->delivery_name=$deliverys[$delivery_id-1]['delivery_name'];
        $order->delivery_price=$deliverys[$delivery_id-1]['delivery_price'];
        $order->payment_id=$payment_id;
        $order->payment_name=$payments[$payment_id-1]['payment_name'];
        $order->total=$total;
        $order->stuats=1;
        $order->trade_no=date('Ymd').rand(1000,9999);
        $order->create_time=time();
      //保存数据之前，开启事务
       $transaction= \Yii::$app->db->beginTransaction();
        try{

            $order->save();


            $carts=Shopcart::find()->where(['member_id'=>$member_id])->all();

            foreach ($carts as $cart){

                //实例化
                $goods=Goods::findOne(['id'=>$cart->goods_id,'status'=>1]);
                if($goods==null){
                    throw  new Exception($goods->name.'已售完');
                }
                if($goods->stock<$cart->amount){
                    throw  new Exception($goods->name.'库存不足！');
                }

                $order_info=new OrderInfo();
                $order_info->order_id=$order->id;
                $order_info->goods_id=$goods->id;
                $order_info->goods_name=$goods->name;
                $order_info->logo=$goods->logo;
                $price= $order_info->price=$goods->shop_price;
                $amount=$order_info->amount=$cart->amount;
                $order_info->total=$price*$amount;
                $order_info->save();
                //减去库存
                $goods->stock-=$cart->amount;
                $goods->save();



            }

            Shopcart::deleteAll(['member_id'=>$member_id]);


            //提交
            $transaction->commit();
            return 'success';

        }catch (Exception $e){


            $transaction->rollBack();

            return 'false';

        }













    }

    public function actionSuccess(){

       return $this->render('success');
    }

    public function actionMyorder(){

        $this->layout='address';
        $member_id=\Yii::$app->user->id;
        //获取该用户的所有订单信息
        $orders=Order::find()->where(['member_id'=>$member_id])->all();

    foreach ($orders as $order){

       $goods=OrderInfo::findOne(['order_id'=>$order->id]);


    }

        //获取商品信息

      return  $this->render('myorder',['orders'=>$orders,'goods'=>$goods]);
    }


}