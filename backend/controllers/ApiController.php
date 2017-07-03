<?php
namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\EditPwdForm;
use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\LoginForm;
use frontend\models\Member;
use frontend\models\Order;
use frontend\models\Shopcart;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Request;
use yii\web\Response;
use yii\web\UploadedFile;

class ApiController extends Controller{

    public $enableCsrfValidation=false;

    public function init()
    {
        \Yii::$app->response->format=Response::FORMAT_JSON;
        parent::init();
    }

//    public function actionGetGoodsByBrand(){
//
//        if($brand_id=\Yii::$app->request->get('brand_id')){
//
//            $goods=Goods::find()->where(['brand_id'=>$brand_id])->all();
//            return Json::encode(['status'=>1,'msg'=>'','data'=>$goods]);
//        }
//
//        return Json::encode(['status'=>-1,'msg'=>'参数不正确']);
//    }

//注册
    public function actionUserRegister(){
        $request=new Request();
        if($request->isPost){
            $member=new Member();
            $member->username=$request->post('username');
            $member->password=$request->post('password');

            $member->email=$request->post('email');
            $member->tel=$request->post('tel');
            if($member->validate()){
                $member->save();
                //返回json
                return ['stuatus'=>1,'msg'=>'','data'=>$member->toArray()];

            }
            return ['stuatus'=>-1,'msg'=>$member->getErrors()];
        }
        return ['stuatus'=>-1,'msg'=>'请使用post提交！'];

    }

    //用户登录
    public function actionUserLogin(){
        $request=\Yii::$app->request;
        if($request->isPost){

            $member=Member::findOne(['username'=>$request->post('username')]);
            if($member && \Yii::$app->security->validatePassword($request->post('password'),$member->password_hash)){
                \Yii::$app->user->login($member);
                return ['status'=>1,'msg'=>'登录成功','data'=>$member];

            }
            return ['status'=>-1,'msg'=>'登录失败'];
        }
        return ['status'=>-1,'msg'=>'请求方式错误'];
    }
        //修改密码
    public function actionUserEditPassword(){
        $request=\Yii::$app->request;
        //判断用户是否登录
        if(!\Yii::$app->user->isGuest){
            //判断原密码是否正确
            $member =Member::findOne(['id'=>\Yii::$app->user->id]);
            if(\Yii::$app->security->validatePassword($request->post('old_password'),$member->password)){
                $member->password_hash=\Yii::$app->security->generatePasswordHash($request->post('password'));
//                if($member->validate()){
                $member->save();
                return ['status'=>1,'msg'=>'修改密码成功'];
//                }
//                return ['status'=>-1,'msg'=>'修改失败','data'=>$member->getErrors()];
            }
            return ['status'=>-1,'msg'=>'旧密码错误'];
        }
        return ['status'=>-1,'msg'=>'没有登录请登录'];
    }
//获取用户登录信息
    public function actionUserInfo(){
        //判断用户是否登录
        if(!\Yii::$app->user->isGuest){
            $member =Member::findOne(['id'=>\Yii::$app->user->id]);
            return ['status'=>1,'msg'=>'用户登录状态','data'=>$member];
        }
        return ['status'=>-1,'msg'=>'没有登录请登录'];
    }

//添加用户地址
public function actionUserAddress(){

    $request=\Yii::$app->request;
    if(!\Yii::$app->user->isGuest){
        $member_id =\Yii::$app->user->id;
        if($request->isPost){
            $address= new Address();
            $address->member_id=$member_id;
            $address->name=$request->post('name');
            $address->province=$request->post('province');
            $address->city=$request->post('city');
            $address->area=$request->post('district');
            $address->detail=$request->post('detail');
            $address->tel=$request->post('tel');
            if($address->validate()){
                $address->save();
                return ['status'=>1,'msg'=>'添加地址成功','data'=>$address];
            }
            return ['status'=>-1,'msg'=>'添加地址失败','data'=>$address->getErrors()];
        }
        return ['status'=>-1,'msg'=>'提交方式错误'];
    }
    return ['status'=>-1,'msg'=>'没有登录请登录'];
}
//删除地址
    public function actionAddressDel(){
        if($id=\Yii::$app->request->get('id')){
            $address=Address::find()->where(['id'=>$id])->one();
            $address->delete();
            return ['status'=>1,'msg'=>'删除成功','data'=>$address];
        }
        return ['status'=>'-1','msg'=>'参数不正确'];

    }
//地址列表
public function actionAddressList(){

    $request=\Yii::$app->request;
    if(!\Yii::$app->user->isGuest) {
        $member_id = \Yii::$app->user->id;


        $address = Address::find()->where(['user_id' => $member_id])->all();
        return ['status' => 1, 'msg' => '', 'data' => $address];
    }

        return ['status'=>-1,'msg'=>'没有登录请登录'];
    }
    //获取所有商品分类
    public function actionCategoryList(){
        $query=GoodsCategory::find();


            //总条数
        $total=$query->count();
        //每页显示条数
        $per_page=\Yii::$app->request->get('per_page',5);
        //当前显示第几页

        $page=\Yii::$app->request->get('page',1);
        $page<1?1:$page;
        $categorys=$query->offset($per_page*($page-1))->limit($per_page)->asArray()->all();
//            $goods=Goods::find()->where(['brand_id'=>$brand_id])->asArray()->all();
        return ['status'=>1,'msg'=>'','data'=>[
            'total'=>$total,
            'per_page'=>$per_page,
            'page'=>$page,
            'categorys'=>$categorys

        ]];



    }
    //获取分类下的所有子分类
    public function actionChildrenByCategory()
    {

        if ($cate_id = \Yii::$app->request->get('cate_id')) {

            $child_cates = GoodsCategory::find()->where(['parent_id' => $cate_id])->all();
            return ['status' => 1, 'msg' => '', 'data' => $child_cates];
        }
        return ['status'=>-1,'msg'=>'请使用get方式提交'];
    }
    //获取某分类的父分类
    public function actionParentByCategory(){
        if($cate_id=\Yii::$app->request->get('cate_id')){
            $parent_cate=GoodsCategory::find()->where(['parent_id'=>$cate_id])->all();
            return ['status' => 1, 'msg' => '', 'data' => $parent_cate];
        }
        return ['status'=>-1,'msg'=>'请使用get方式提交'];

    }
//获取分类下的所有商品（1-3级）
public function actionGoodsByCategory(){

    $request=\Yii::$app->request;
    if($request->isGet){
        $cid=$request->get('id');
        $parent=GoodsCategory::findOne(['parent_id'=>$cid]);
            $cates=GoodsCategory::find()->where(['tree'=>$parent->tree])->andWhere('lft>'.$parent->lft)->andWhere('rgt<'.$parent->rgt)->all();

        $cids=[];
        foreach ($cates as $cate){
            $cids[]=$cate->id;
        }
        $goods=[];
        foreach ($cids as $id){
            //一个分类有多个商品所以这里查询所有的商品丢到数组去
            $goods[]=Goods::findAll(['goods_category_id'=>$id]);
        }
//        var_dump($cids);die;
        return ['status'=>1,'msg'=>'查询成功','data'=>$goods];
    }
    return ['status'=>-1,'msg'=>'提交方式错误'];
}

//获取某品牌下的所有商品
    public function actionGoodsByBrand(){





        if($brand_id=\Yii::$app->request->get('brand_id')){
//            var_dump($brand_id);die;
            $query=Goods::find()->where(['brand_id'=>$brand_id]);

            //总条数
            $total=$query->count();
            //每页显示条数
            $per_page=\Yii::$app->request->get('per_page',2);
            //当前显示第几页
            $page=\Yii::$app->request->get('page',1);
            $goods=$query->offset($per_page*($page-1))->limit($per_page)->asArray()->all();
//            $goods=Goods::find()->where(['brand_id'=>$brand_id])->asArray()->all();
            return ['status'=>1,'msg'=>'','data'=>[
                'total'=>$total,
                'per_page'=>$per_page,
                'page'=>$page,
                'goods'=>$goods

                ]];
        }
        return ['status'=>'-1','msg'=>'参数不正确'];
    }

    //获取文章分类
    public function actionArticleCate(){
        $article=ArticleCategory::find()->all();
        return ['status'=>1,'msg'=>'查询成功','data'=>$article];
    }
    //获取某分类下的所有文章
    public function actionArticleByCate(){
        if($cate_id=\Yii::$app->request->get('cate_id')){
            $articles=Article::find()->where(['article_category_id'=>$cate_id])->asArray()->all();
            return ['status'=>1,'msg'=>'','data'=>$articles];

        }
        return ['status'=>-1,'msg'=>'请使用get方式提交'];
    }
    //获取某文章所属分类
    public function actionCateByArticle(){
        if($art_id=\Yii::$app->request->get('art_id')){
            $article=Article::findOne(['id'=>$art_id]);
           $cates= $article->category;
            return ['status'=>1,'msg'=>'','data'=>$cates];
        }
        return ['status'=>-1,'msg'=>'请使用get方式提交'];

    }
//添加商品到购物车
       
    public function actionCartAddGoods(){
            //未登录
            $goods_id=\Yii::$app->request->post('goods_id');
            $amount=\Yii::$app->request->post('amount');
            $goods = Goods::findOne(['id'=>$goods_id]);
            if($goods == null){
                return ['status'=>-1,'msg'=>'没有此商品'];
            }
            if(\Yii::$app->user->isGuest){
                //缓存
                //获取response里面的cookie
                $cookies=\Yii::$app->request->cookies;
                $cookie=$cookies->get('cart');
                if($cookie == null){
                    $cart=[];
                }else{
                    $cart = unserialize($cookie->value);;
                }
                $cookiess=\Yii::$app->response->cookies;
                //如果不存在这个建名就创建
                if(key_exists($goods->id,$cart)){
                    $cart[$goods_id] += $amount;
                }else{
                    $cart[$goods_id] = $amount;
                }
                $cookie=new Cookie([
                    'name'=>'cart',
                    'value'=>serialize($cart)
                ]);
                $cookiess->add($cookie);
                return ['status'=>1,'msg'=>'存入cookie成功','data'=>$cart];
            }else{
                //登录
                $model=new Shopcart();
                $member_id=\Yii::$app->user->id;
                $cart = Shopcart::find()->where(['member_id' => $member_id])->andWhere(['goods_id'=>$goods_id])->one();
                if(\Yii::$app->request->isPost){
                    if($cart){
                        $cart->amount  +=$amount;
                        $cart->save();
                        return ['status'=>1,'msg'=>'累加成功','data'=>$cart];
                    }else{
                        $model->goods_id=$goods_id;
                        $model->amount=$amount;
                        $model->member_id=$member_id;
                        $model->save(false);
                        return ['status'=>1,'msg'=>'添加成功','data'=>$model];
                    }

                }
                return ['status'=>-1,'msg'=>'提交方式不正确'];
            }
        }

        //验证码
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>3,
                'maxLength'=>3,
            ],
        ];
        //http://admin.yii2shop.com/api/captcha 显示验证码
        //http://admin.yii2shop.com/api/captcha.html?refresh=1 获取新验证码图片地址
        //http://admin.yii2shop.com/api/captcha.html?v=59573cbe28c58 新验证码图片地址
    }
    //文件上传
    public function actionUpload(){
        //实例化文件上传对象
        $file=UploadedFile::getInstanceByName('img');
        if($file){
            //获取文件路径
            $fileName='/upload/'.uniqid().'.'.$file->extension;
           $res= $file->saveAs($fileName,false);
           if($res){
               return ['status'=>1,'msg'=>'文件上传成功！','data'=>$fileName];
           }
            return ['status'=>1,'msg'=>'文件上传失败！','data'=>$file->error];
        }
        return ['status'=>-1,'msg'=>'没有上传文件！','data'=>''];
    }


        //修改某商品的数量&&删除某件商品
    public function actionEditAmount(){

        $good_id=\Yii::$app->request->post('goods_id');
        $amount=\Yii::$app->request->post('amount');
//        var_dump($good_id,$amount);exit;
        $goods=Goods::findOne(['id'=>$good_id]);
        if($goods==null){
            return ['status'=>-1,'msg'=>'没有此商品'];
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
            return ['status'=>1,'msg'=>'存入cookie成功','data'=>$cart];
        }else{

            //用户已经登录，获取登录用户id
            $member_id=\Yii::$app->user->getId();

            //操作数据库
            $cart=Shopcart::findOne(['member_id'=>$member_id,'goods_id'=>$good_id]);
            if(\Yii::$app->request->isPost){

                if($amount==0){
                    Shopcart::findOne(['goods_id'=>$good_id])->delete();
                    return ['status'=>-1,'msg'=>'删除成功','data'=>$cart];
                }
                $cart->amount=$amount;
                $cart->save();
                return ['status'=>1,'msg'=>'累加成功','data'=>$cart];

            }


            return ['status'=>-1,'msg'=>'提交方式不正确'];
        }

    }

    //清除购物车
    public function actionDelCart(){
        $query=Shopcart::find();
        $total=$query->count();

       Shopcart::deleteAll();
        return ['status'=>1,'msg'=>'清除购物车成功！','data'=>$total];

    }

    //获取购物车的商品
    public function actionGoodsByCart(){

        $good_id=\Yii::$app->request->post('goods_id');
        $goods=Goods::findOne(['id'=>$good_id]);
        if($goods==null){
            return ['status'=>-1,'msg'=>'没有此商品'];
        }

        //判断用户是否登录
        if(\Yii::$app->user->isGuest) {
            //未登录就获取cookie的商品
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
            return ['status' => -1, 'msg' => 'cookie中的购物信息', 'data' => $models];
        }else{

            //登录就获取当前用户的购物商品+cookie中的商品
            //如果登录了获取缓存数据，同步到数据库


        }


    }

    //订单
    //获取支付方式
    public function actionGetPayment(){
        //订单的操作都需要用户登录
        if(\Yii::$app->user->isGuest){

            return ['status'=>-1,'msg'=>'请先登录！'];
        }
        //登录后,获取用户id,获取订单信息
        $member_id=\Yii::$app->user->id;
        $orders=Order::find()->where(['member_id'=>$member_id])->all();

        foreach ($orders as $order){

          $payment=  $order->payment_name;
        }
        return ['status'=>-1,'msg'=>'','data'=>$payment];

    }

    //获取支送货方式
    public function actionGetDelivery(){
        //订单的操作都需要用户登录
        if(\Yii::$app->user->isGuest){

            return ['status'=>-1,'msg'=>'请先登录！'];
        }
        //登录后,获取用户id,获取订单信息
        $member_id=\Yii::$app->user->id;
        $orders=Order::find()->where(['member_id'=>$member_id])->all();

        foreach ($orders as $order){

            $delivery=  $order->delivery_name;
        }
        return ['status'=>-1,'msg'=>'','data'=>$delivery];

    }
    //提交订单
    public function actionPutOrder(){

        if(\Yii::$app->user->isGuest){

            return ['status'=>-1,'msg'=>'请先登录！'];
        }


    }

    //获取用户订单列表
    public function actionOrderList(){
        if(\Yii::$app->user->isGuest){

            return ['status'=>-1,'msg'=>'请先登录！'];
        }

        //登录后,获取用户id,获取订单信息
        $member_id=\Yii::$app->user->id;
        $orders=Order::find()->where(['member_id'=>$member_id])->all();


        return ['status'=>-1,'msg'=>'','data'=>$orders];

    }
}

