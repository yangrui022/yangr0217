<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use yii\web\Request;

class ArticleController extends \yii\web\Controller
{
    //文章列表
    public function actionIndex()
    {
        $articles=Article::find()->all();
        return $this->render('index',['articles'=>$articles]);

    }

    public function actionAdd()
    {
        //获取所有分类
        $category=ArticleCategory::find()->all();
        $model = new Article();
        $detail=new ArticleDetail();
        $requerst = \Yii::$app->request;

        if ($requerst->isPost) {
            //加载数据
            $model->load($requerst->post());


            if ($model->validate()) {

                $model->create_time=time();
                $model->save(false);
                $detail->content=$model->intro;
                $detail->article_id=$model->id;
                $detail->save();
                \Yii::$app->session->setFlash('success', '文章添加成功');
                return $this->redirect(['article/index']);
            }
        }
        return $this->render('add', ['model' => $model,'category'=>$category]);

    }
    //文章修改
    public function actionEdit($id){
        $category=ArticleCategory::find()->all();
        $detail=new ArticleDetail();
        $model=Article::findOne($id);
        $requerst = \Yii::$app->request;

        if ($requerst->isPost) {
            //加载数据
            $model->load($requerst->post());

            //验证数据是否正确
            if ($model->validate()) {
            //保持数据
                $model->save(false);

                $detail->content=$model->intro;
                $detail->article_id=$model->id;
                $detail->save();
                \Yii::$app->session->setFlash('success', '文章修改成功');
                return $this->redirect(['article/index']);
            }
        }
        //加载视图 并分配数据
        return $this->render('add', ['model' => $model,'category'=>$category]);
    }

    //删除
    public function actionDelete($id){
        //获取当前删除对象
        $model=Article::findOne($id);

        $model->status=-1;
        $model->save();
        return $this->redirect(['article/index']);

    }
    //查看文章详情
    public function actionDetail($id){
        $article=Article::findOne($id);
        $article_id=Article::findOne($id)->id;
       $detail=ArticleDetail::findOne($article_id);

//var_dump($detail);exit;
        return $this->render('view',['detail'=>$detail,'article'=>$article]);
    }
    public function actionContent($id){
        $article_id=Article::findOne($id)->id;

        $model=new ArticleDetail();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());

            if($model->validate()){
                $model->article_id=$article_id;
                $model->save();
                return $this->redirect(['article/index']);
            }
        }

        return $this->render('edcont',['model'=>$model]);
    }
}
