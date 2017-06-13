<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Request;
use yii\web\NotFoundHttpException;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()

    {

        $goods_categories=GoodsCategory::find()->orderBy('tree,lft')->all();
//        $total=$query->count();
//        $page=new Pagination([
//                'totalCount'=>$total,
//                'defaultPageSize'=>3
//            ]
//        );
//        //从下标0开始偏移3条 3条数据
//
//        $goods_categories=$query->offset($page->offset)->limit($page->limit)->all();


        return $this->render('index',['goods_categories'=>$goods_categories]);
    }

    //添加商品分类
    public function actionAdd(){

        $model=new GoodsCategory();
        $request=new Request();
        if($model->load($request->post()) && $model->validate()){

            //判断是否是添加一级分类（parent_id是否为0）
            if($model->parent_id){
                //添加非一级分类
                $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);//获取上一级分类
                $model->prependTo($parent);//添加到上一级分类下面
            }else{

                //添加一级分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','添加分类成功');
            return $this->redirect(['goods-category/index']);
        }
        $categories = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }

    //修改商品分类
    public function actionEdit($id){

        $model = GoodsCategory::findOne(['id'=>$id]);
        $parent_id=$model->parent_id;
        if($model==null){
            throw new NotFoundHttpException('分类不存在');
        }
        $request=new Request();
        if($model->load($request->post()) && $model->validate()){

            //判断是否是添加一级分类（parent_id是否为0）
            if($model->parent_id){
                //添加非一级分类
                $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);//获取上一级分类
                $model->prependTo($parent);//添加到上一级分类下面
            }else{
                //添加一级分类
                //判断父id是否发生变化
                if($model->parent_id==$parent_id && $model->parent_id==0){
                    $model->save();

                }else{
                    $model->makeRoot();
                }

            }
            \Yii::$app->session->setFlash('success','修改分类成功');
            return $this->redirect(['goods-category/index']);
        }

        $categories = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }
    //删除分类，分类下有子孙分类不能删除，只能删除没有子孙分类的分类；
    public function actionDelete($id){
      $model=  GoodsCategory::findOne(['parent_id'=>$id]);

     if(!empty($model)){
         \Yii::$app->session->setFlash('danger','该分类下有子分类不能删除');
         return $this->redirect(['goods-category/index']);
     }
        GoodsCategory::findOne($id)->delete();
        return $this->redirect(['goods-category/index']);
        }






    //测试
    public function actionTest(){
            //创建一级分类
//        $jydq=new GoodsCategory();
//        $jydq->name='家用电器';
//        $jydq->parent_id=0;
//        $jydq->makeRoot();
//        var_dump($jydq);

        //创建二级分类
//        $parent = GoodsCategory::findOne(['id'=>1]);
//        $xjd = new GoodsCategory();
//        $xjd->name = '小家电';
//        $xjd->parent_id = $parent->id;
//        $xjd->prependTo($parent);
//        echo '操作成功';
    }
    public function actionZtree(){


        //找到所有分类
        $categories=GoodsCategory::find()->asArray()->all();
        return $this->renderPartial('ztree',['categories'=>$categories]);

    }
}

