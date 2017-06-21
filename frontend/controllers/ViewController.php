<?php
namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsIntro;
use yii\web\Controller;

class ViewController extends  Controller{


    public $layout='goods';
    public function actionDetail($id){
        //找到当前商品
        $good=Goods::findOne($id);

        //商品详情

            $view=GoodsIntro::findOne(['goods_id'=>$id]);


        return $this->render('view',['view'=>$view,'good'=>$good]);
    }

}