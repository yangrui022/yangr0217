<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/8
 * Time: 16:09
 */
namespace backend\controllers;

use backend\models\Brand;

use yii\web\Controller;
use yii\web\UploadedFile;


class BrandController extends Controller
{
    public function actionIndex()
    {
        //分页
        //获取所有品牌
        $brands=Brand::find()->all();
//        $query=Brand::find();
//        $total=$query->all();
//        $page=new Pagination([
//                'totalCount'=>$total,
//                'defaultPageSize'=>2
//            ]
//        );
//        //从下标0开始偏移2条 2条数据
//        $brands=$query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['brands' => $brands]);
        //加载视图 传输数据

    }

    //添加
    public function actionAdd()
    {

        $model = new Brand();
        $requerst = \Yii::$app->request;

        if ($requerst->isPost) {
            //加载数据
            $model->load($requerst->post());
            //验证之前实例化上传文件对象
            $model->imgFile = UploadedFile::getInstance($model, 'imgFile');

            if ($model->validate()) {


                //判断是否上传文件
                if ($model->imgFile) {

                    //保存文件 获取文件名  extension 扩展名
                    $fileName = '/images/brand/' . uniqid() . '.' . $model->imgFile->extension;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false);
                    $model->logo = $fileName;
                }

                $model->save(false);
                \Yii::$app->session->setFlash('success', '品牌添加成功');
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add', ['model' => $model]);

    }

    //修改品牌
    public function actionEdit($id){
        $model=Brand::findOne($id);
        $requerst = \Yii::$app->request;

        if ($requerst->isPost) {
            //加载数据
            $model->load($requerst->post());
            //验证之前实例化上传文件对象
            $model->imgFile = UploadedFile::getInstance($model, 'imgFile');

            if ($model->validate()) {


                //判断是否上传文件
                if ($model->imgFile) {

                    //保存文件 获取文件名  extension 扩展名
                    $fileName = '/images/brand/' . uniqid() . '.' . $model->imgFile->extension;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false);
                    $model->logo = $fileName;
                }

                $model->save(false);\Yii::$app->session->setFlash('success', '品牌添加成功');
                \Yii::$app->session->setFlash('success', '品牌修改成功');
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add', ['model' => $model]);
    }
        //删除 改变状态
    public function actionDelete($id){
        //获取当前删除对象
        $model=Brand::findOne($id);

        $model->status=-1;
        $model->save();
        return $this->redirect(['brand/index']);

    }
}
