<?php
namespace frontend\controllers;


use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsIntro;
use frontend\models\Address;

use frontend\models\Locations;

use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

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

    public function actionList($id){
        //找到所有品牌
        $brands=Brand::find()->all();

        //找到对应分类的下的商品
        $query = Goods::find()->where(['goods_category_id'=>$id]);
        $total = $query->count();
        $page = new Pagination([
                'totalCount' => $total,
                'defaultPageSize' => 6
            ]
        );

        $goods = $query->offset($page->offset)->limit($page->limit)->all();


        return $this->render('list',['goods'=>$goods,'page'=>$page,'brands'=>$brands]);
    }

}