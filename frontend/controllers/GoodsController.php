<?php
namespace frontend\controllers;


use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsIntro;
use backend\models\GoodsPhoto;
use Behat\Gherkin\Exception\NodeException;
use Codeception\Exception\ElementNotFound;
use frontend\models\Address;

use frontend\models\Locations;

use frontend\models\Shopcart;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class GoodsController extends Controller{

    public $layout='address';
    public function actionAddress(){

        $model=new Address();

//        var_dump(\Yii::$app->request->post());exit;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $stus=Address::find()->where(['user_id'=>\Yii::$app->user->getId()])->all();
            if($model->stutas){
                foreach ($stus  as $stu){
                    $stu->stutas=0;
                    $stu->save();
                }

            }
            $model->user_id=\Yii::$app->user->getId();
            $model->create_at=time();
            $model->update_at=time();
            $model->save();
            return $this->redirect(['index/index']);

        }


        return $this->render('address',['model'=>$model]);
    }
//修改收货地址
    public function actionEdit($id){


        $model=Address::findOne($id);
        if($model->user_id!=\Yii::$app->user->getId()){
            return $this->redirect(['user/login']);
        }

        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $stus=Address::find()->where(['user_id'=>\Yii::$app->user->getId()])->all();
            if($model->stutas) {
                foreach ($stus as $stu) {
                    $stu->stutas = 0;
                    $stu->save();
                }
            }
            $model->update_at=time();
            $model->save();
            return $this->redirect(['goods/address']);

        }


        return $this->render('address',['model'=>$model]);
    }


    //删除收货地址
    public function actionDel($id){
        $model=Address::findOne($id);
        if($model->user_id!=\Yii::$app->user->getId()){
            return $this->redirect(['user/login']);
        }
        $model->delete();
        return $this->redirect(['goods/address']);

    }
    //设置为默认地址
    public function actionStuta($id){


//        if($model->user_id!=\Yii::$app->user->getId()){
//            return $this->redirect(['user/login']);
//        }
        $models=Address::find()->all();

        foreach ($models  as $model){
            $model->stutas=0;
            $model->save();
        }
                $model=Address::findOne($id);
        $model->stutas=1;
        $model->save();
        return $this->redirect(['goods/address']);
    }
    public function actions()
    {
        $actions=parent::actions();
        $actions['get-region']=[
            'class'=>\chenkby\region\RegionAction::className(),
            'model'=>Locations::className()
        ];
        return $actions;
    }

    public function actionDetail($id){
        //找到当前商品

        $this->layout='goods';


        $good=Goods::findOne($id);
        $imgs=GoodsPhoto::find()->where(['goods_id'=>$id])->all();


        //商品详情

        $view=GoodsIntro::findOne(['goods_id'=>$id]);


        return $this->render('view',['view'=>$view,'good'=>$good,'imgs'=>$imgs]);
    }


    public function actionList($id){
        $this->layout='list';
        //找到所有品牌
        $brands=Brand::find()->all();
        //找到当前分类id对应的父级分类;
        $cate=GoodsCategory::findOne($id);

        $children=$cate->children;

        if($children){
            $child_id=[];
            foreach ($children as $child){
                $category=$child->children;
                if($category){
                    foreach ($category as $cate){
                        $child_id[]=  $cate->id;
                    }

                }
                $child_id[]=  $child->id;
            }
            $query=Goods::find()->where(['goods_category_id'=>$child_id]);
        }else{

            $query = Goods::find()->where(['goods_category_id'=>$id]);
        }

        $tree_id=$cate->tree;
        $category=GoodsCategory::findone(['tree'=>$tree_id,'parent_id'=>0]);
        $total = $query->count();
        $page = new Pagination([
                'totalCount' => $total,
                'defaultPageSize' => 6
            ]
        );
        $goods = $query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('list',['goods'=>$goods,'brands'=>$brands,'category'=>$category,'page'=>$page]);
    }


    //添加购物车
    public function actionAddCart(){
        //接受参数
        $good_id=\Yii::$app->request->post('goods_id');
        $amount=\Yii::$app->request->post('amount');
//        var_dump($good_id,$amount);exit;
        $goods=Goods::findOne(['id'=>$good_id]);
        if($goods==null){
            throw new NotFoundHttpException('商品没用找到！');
        }
        //判断用户是否登录
        if(\Yii::$app->user->isGuest){

            //现获取cookie中的数据
           $cookies= \Yii::$app->request->cookies;
            $cookie=$cookies->get('cart');

            if($cookie==null){
                //cookie中没有商品
                $cart=[];
            }else{
                $cart=unserialize($cookie->value);

            }
            //将数据保存在cookie中
            $cookies=\Yii::$app->response->cookies;
            //判断购物车中是否存在商品,存在累加
            if(key_exists($goods->id,$cart)){
               $cart[$good_id]+=$amount;
            }else{
            //不存在就直接加入
                $cart[$good_id]=$amount;
            }
            $cookie=new Cookie([
                'name'=>'cart',
                'value'=>serialize($cart),


            ]);
           $cookies->add($cookie);

        }else{

           // 实例化购物车数据库

            $member_id=\Yii::$app->user->getId();
                $cart=Shopcart::findOne(['member_id'=>$member_id,'goods_id'=>$good_id]);

                if($cart){
//                    echo 1;exit;
                    $cart->amount+=$amount;
                    $cart->save();

                }else{
//                    echo 2;exit;
                    $model=new Shopcart();
                    $model->goods_id=$good_id;
                    $model->member_id=$member_id;
                    $model->amount=$amount;

//                var_dump($member_id);exit;
                    $model->save();
                }




        }
        return $this->redirect(['goods/cart']);
//        return $this->render('cart');
    }


    public function actionCart(){
        $this->layout='cart';
        //获取cookie中的数据
        if(\Yii::$app->user->isGuest) {
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if ($cookie == null) {
                //cookie中没有商品
                $cart = [];
            } else {
                $cart = unserialize($cookie->value);

            }
            $models = [];
            foreach ($cart as $good_id => $amount) {

                $goods = Goods::findOne(['id' => $good_id])->attributes;
                $goods['amount'] = $amount;
                $models[] = $goods;

            }
            return $this->render('cart',['models'=>$models]);

        }else{
            //如果登录了获取缓存数据，同步到数据库
            $member_id=\Yii::$app->user->getId();
            //获取缓存的数据
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');

            if ($cookie == null) {
                //cookie中没有商品
                $cart = [];
            } else {
                $cart = unserialize($cookie->value);

            }
           foreach ($cart as  $good_id => $amount){
               $goods = Goods::findOne(['id' => $good_id])->attributes;
               $goods['amount'] = $amount;

            //实例化购物车对象，判断数据表是否有同样的商品
               $cartshop=Shopcart::findOne(['goods_id'=>$goods['id'],'member_id'=>$member_id]);
               if($cartshop){
//                   echo 1;exit;
                   $cartshop->amount+=$amount;
                   $cartshop->save();

               }else{

                   $model=new Shopcart();
                   $model->goods_id=$good_id;
                   $model->member_id=$member_id;
                   $model->amount=$amount;
                   $model->save();
               }

           }
            $cookies = \Yii::$app->response->cookies;
            $cookies->get('cart');

            $cookies->remove('cart');
            $models=[];
            $carts=Shopcart::find()->where(['member_id'=>\Yii::$app->user->getId()])->all();
//            var_dump($carts);exit;
            foreach ($carts as $cart){
//                var_dump($cart->goods_id);exit;
                $goods=Goods::findOne(['id'=>$cart->goods_id])->attributes;
//                var_dump($goods);exit;
                $goods['amount']=$cart->amount;
                $models[] = $goods;

            }
            return $this->render('cart',['models'=>$models]);
        }


//
//        $models[]=$goods;


        return $this->render('cart',['models'=>$models]);

    }

    public function actionUpdateCart(){
        //接受参数
        $good_id=\Yii::$app->request->post('goods_id');
        $amount=\Yii::$app->request->post('amount');
//        var_dump($good_id,$amount);exit;
        $goods=Goods::findOne(['id'=>$good_id]);
        if($goods==null){
            throw new NotFoundHttpException('商品没用找到！');
        }
        //判断用户是否登录
        if(\Yii::$app->user->isGuest){

            //现获取cookie中的数据
            $cookies= \Yii::$app->request->cookies;
            $cookie=$cookies->get('cart');

            if($cookie==null){
                //cookie中没有商品
                $cart=[];
            }else{
                $cart=unserialize($cookie->value);

            }
            //将数据保存在cookie中
            $cookies=\Yii::$app->response->cookies;
            //修改时，根据数量存在与否,进行删除
            if($amount){
                $cart[$good_id] = $amount;
            }else{
                if(key_exists($goods['id'],$cart)) unset($cart[$good_id]);
            }

            $cookie=new Cookie([
                'name'=>'cart',
                'value'=>serialize($cart)

            ]);
            $cookies->add($cookie);

        }else{
            //用户已经登录，获取登录用户id
            $member_id=\Yii::$app->user->getId();

            //操作数据库
            $cart=Shopcart::findOne(['member_id'=>$member_id,'goods_id'=>$good_id]);
            if($amount==0){
               Shopcart::findOne(['goods_id'=>$good_id])->delete();
            }
            $cart->amount=$amount;
            $cart->save();



        }

    }
}