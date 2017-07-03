<?php
namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\components\SphinxClient;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class IndexController extends  Controller{


    public $layout='index';
    public function actionIndex()
    {




        return $this->render('index');
    }

        public function actionTest(){
        $cl = new SphinxClient();
        $cl->SetServer ( '127.0.0.1', 9312);
//$cl->SetServer ( '10.6.0.6', 9312);
//$cl->SetServer ( '10.6.0.22', 9312);
//$cl->SetServer ( '10.8.8.2', 9312);
        $cl->SetConnectTimeout ( 10 );
        $cl->SetArrayResult ( true );
// $cl->SetMatchMode ( SPH_MATCH_ANY);
        $cl->SetMatchMode ( SPH_MATCH_ALL);
        $cl->SetLimits(0, 1000);
        $info = '男士体恤';//需要搜索的词
        $res = $cl->Query($info, 'goods');//shopstore_search
//print_r($cl);
        var_dump($res);
    }
}

