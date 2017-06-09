<?php

namespace backend\controllers;

use backend\models\ArticleCategory;

class CategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $categorys=ArticleCategory::find()->all();
        return $this->render('index',['categorys'=>$categorys]);
    }


    public function actionAdd()
    {

        $model = new ArticleCategory();
        $requerst = \Yii::$app->request;

        if ($requerst->isPost) {
            //加载数据
            $model->load($requerst->post());


            if ($model->validate()) {

                $model->save(false);
                \Yii::$app->session->setFlash('success', '文章分类添加成功');
                return $this->redirect(['category/index']);
            }
        }
        return $this->render('add', ['model' => $model]);

    }
    //修改文章分类
    public function actionEdit($id){
        $model=ArticleCategory::findOne($id);
        $requerst = \Yii::$app->request;

        if ($requerst->isPost) {
            //加载数据
            $model->load($requerst->post());


            if ($model->validate()) {

                $model->save(false);
                \Yii::$app->session->setFlash('success', '文章添加成功');
                return $this->redirect(['category/index']);
            }
        }
        return $this->render('add', ['model' => $model]);

    }

    public function actionDelete($id){
        //获取当前删除对象
        $model=ArticleCategory::findOne($id);

        $model->status=-1;
        $model->save();
        return $this->redirect(['category/index']);

    }

}
