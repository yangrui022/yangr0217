<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use yii\data\Pagination;


class ArticleController extends PublicController
{
    //文章列表
    public function actionIndex()
    {

        $query=Article::find();
        $total=$query->count();
        $page=new Pagination([
                'totalCount'=>$total,
                'defaultPageSize'=>2
            ]
        );
        //从下标0开始偏移3条 3条数据

        $articles=$query->offset($page->offset)->limit($page->limit)->all();
//        $detail=ArticleDetail::find()->all();
//        $articles=Article::find()->all();
        return $this->render('index',['articles'=>$articles,'page'=>$page]);

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
            $detail->load($requerst->post());

            if ($model->validate() && $detail->validate()) {

                $model->create_time=time();
                $model->save();
//            var_dump($model);exit;
//                $detail->content=$model->content;
                $detail->article_id=$model->id;
                $detail->save();
                \Yii::$app->session->setFlash('success', '文章添加成功');
                return $this->redirect(['article/index']);
            }else{

                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add', ['model' => $model,'category'=>$category,'detail'=>$detail]);

    }
    //文章修改
    public function actionEdit($id){
        $category=ArticleCategory::find()->all();
        $detail=ArticleDetail::findOne($id);
        $model=Article::findOne($id);
        $requerst = \Yii::$app->request;

        if ($requerst->isPost) {
            //加载数据
            $model->load($requerst->post());
            $detail->load($requerst->post());
            //验证数据是否正确
            if ($model->validate() && $detail->validate()) {

                //保持数据
                $model->save();
//                323231
                //3545aa63

//                $detail->content=$model->content;
//                $detail->article_id=$model->id;
                $detail->save();
                \Yii::$app->session->setFlash('success', '文章修改成功');
                return $this->redirect(['article/index']);
            }
        }
        //加载视图 并分配数据
        return $this->render('add', ['model' => $model,'category'=>$category,'detail'=>$detail]);
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
//        $article_id=Article::findOne($id)->id;

       $detail=ArticleDetail::findOne($id);


        return $this->render('view',['detail'=>$detail,'article'=>$article]);
    }


    public function actions()
    {
        return [

            'ueditor' => [
                'class' => 'crazyfd\ueditor\Upload',
                'config'=>[
                    'uploadDir'=>date('Y/m/d')
                ]

            ],
        ];
    }
}
