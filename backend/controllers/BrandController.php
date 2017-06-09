<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/8
 * Time: 16:09
 */
namespace backend\controllers;

use backend\models\Brand;

use crazyfd\qiniu\Qiniu;
use xj\uploadify\UploadAction;

use yii\data\Pagination;
use yii\web\Controller;



class BrandController extends Controller
{
    public function actionIndex()
    {
        //分页
        //获取所有品牌
//        $query=Brand::find()->all();
        $query=Brand::find();
        $total=$query->count();
        $page=new Pagination([
                'totalCount'=>$total,
                'defaultPageSize'=>3
            ]
        );
        //从下标0开始偏移3条 3条数据

        $brands=$query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['brands' => $brands,'page'=>$page]);
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
//            $model->imgFile = UploadedFile::getInstance($model, 'imgFile');

            if ($model->validate()) {


                //判断是否上传文件
//                if ($model->imgFile) {

//                    //保存文件 获取文件名  extension 扩展名
//                    $fileName = '/images/brand/' . uniqid() . '.' . $model->imgFile->extension;
//                    $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false);
//                    $model->logo = $fileName;
//                }

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
//            $model->imgFile = UploadedFile::getInstance($model, 'imgFile');

            if ($model->validate()) {


                //判断是否上传文件
//                if ($model->imgFile) {

//                    //保存文件 获取文件名  extension 扩展名
//                    $fileName = '/images/brand/' . uniqid() . '.' . $model->imgFile->extension;
//                    $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false);
//                    $model->logo = $fileName;
//                }

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

    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                /*'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png','gif'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $imgUrl=$action->getWebUrl();


                    $ak = 'OzVRCOc5q9bCo17jINHpEumKDpoM0P02nqn9vrA-';
                    $sk = 'T_555p4kIN_Y8Kz4jMN9dKmvzuh7-LO_2J9BM10m';
                    $domain = 'http://or9rald82.bkt.clouddn.com/';
                    $bucket = 'yangrui';

                    $qiniu = new Qiniu($ak, $sk,$domain, $bucket);
                    //要上传的文件


                    $qiniu->uploadFile(\Yii::getAlias('@webroot').$imgUrl,$imgUrl);

                   $url=$qiniu->getLink($imgUrl);
                    $action->output['fileUrl'] = $url;
//
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }



}
