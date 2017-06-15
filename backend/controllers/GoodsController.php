<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsDayCount;

use backend\models\GoodsIntro;

use backend\models\GoodsPhoto;
use backend\models\GoodSearchForm;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\UploadedFile;
use xj\uploadify\UploadAction;

class GoodsController extends PublicController
{
    public function actionIndex()
    {
        $model =new GoodSearchForm();
        $query = Goods::find();

        /*if($keyword = \Yii::$app->request->get('keyword')){
            $query->andWhere(['like','name',$keyword]);
        }
        if($sn = \Yii::$app->request->get('sn')){
            $query->andWhere(['like','sn',$sn]);
        }*/

        //接收表单提交的查询参数
        $model->search($query);







        $total = $query->count();
        $page = new Pagination([
                'totalCount' => $total,
                'defaultPageSize' => 3
            ]
        );
        //从下标0开始偏移3条 3条数据

        $goods = $query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index', ['goods' => $goods, 'page' => $page,'model'=>$model]);
        //加载视图 传输数据
    }
    public function actionAdd()
    {


//        var_dump($count->count);exit;
//        var_dump($count->count);exit;
        $intro = new GoodsIntro();
        //添加商品图片时候，保存到相册中
        $photo=new GoodsPhoto();

        $brand = Brand::find()->all();
        $model = new Goods();
        $request = new Request();
        if ($request->isPost) {
            //加载数据
            $model->load($request->post());
            $intro->load($request->post());
            $photo->load($request->post());

            //现获取当前时间20160612
            $day = date('Ymd');
            $count = GoodsDayCount::findOne(['day' => $day]);
//            var_dump($count);exit;
            //找到count表中是否添加了今天的商品

            if (empty($count)) {
                $count = new GoodsDayCount();
                $count->day = $day;
            $count->count = 1;
                $count->save();
        } else {

            $count->count += 1;

                $count->save();
        }


            //在验证之前实例化文件上传对象
            $model->imgFile = UploadedFile::getInstance($model, 'imgFile');
            //判断是否验证成功
            if ($model->validate() && $intro->validate()&& $photo->validate()) {
                //判断如果上传图片
                if ($model->imgFile) {
                    //判断是否上传文件
                    ////保存文件 获取文件名  extension 扩展名
                    $fileName = '/images/goods/' . uniqid() . '.' . $model->imgFile->extension;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false);
                    $model->logo = $fileName;
                }
                $model->create_time = time();
                $model->sn = $day . str_pad($count->count, 4, 0, STR_PAD_LEFT);
//              var_dump($model->sn);exit;
                $model->save(false);
                $intro->goods_id = $model->id;
                $intro->save();

                $photo->goods_id=$model->id;

                $photo->goods_photos=$model->logo;

                $photo->save(false);

                \Yii::$app->session->setFlash('success', '商品添加成功');
                return $this->redirect(['goods/index']);
            }

        }

        return $this->render('add', ['model' => $model, 'brand' => $brand, 'intro' => $intro]);
    }


    public function actionEdit($id)
    {

        $intro = GoodsIntro::findOne($id);
        $brand = Brand::find()->all();
        $model = Goods::findOne($id);
        $request = new Request();
        if ($request->isPost) {
            //加载数据
            $model->load($request->post());
            $intro->load($request->post());
            //现获取当前时间20160612

            //在验证之前实例化文件上传对象
            $model->imgFile = UploadedFile::getInstance($model, 'imgFile');
            //判断是否验证成功
            if ($model->validate() && $intro->validate()) {
                //判断如果上传图片
                if ($model->imgFile) {
                    //判断是否上传文件
                    ////保存文件 获取文件名  extension 扩展名
                    $fileName = '/images/goods/' . uniqid() . '.' . $model->imgFile->extension;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false);
                    $model->logo = $fileName;
                }


                $model->save(false);
                $intro->save();

                \Yii::$app->session->setFlash('success', '商品信息修改成功');
                return $this->redirect(['goods/index']);
            }

        }

        return $this->render('add', ['model' => $model, 'brand' => $brand, 'intro' => $intro]);

    }
    public function actionView($id){
        $goods=Goods::findOne($id);
        $view=GoodsIntro::findOne(['goods_id'=>$id]);

      return $this->render('view',['goods'=>$goods,'view'=>$view]);

    }
    /*
   * 商品相册
   */
    public function actionPhoto($id)
    {
        $goods = Goods::findOne(['id'=>$id]);
        if($goods == null){
            throw new NotFoundHttpException('商品不存在');
        }


        return $this->render('photo',['goods'=>$goods]);

    }

    /*
     * AJAX删除图片
     */
    public function actionDelPhoto(){
        $id = \Yii::$app->request->post('id');
        $model = GoodsPhoto::findOne(['id'=>$id]);
        if($model && $model->delete()){
            return 'success';
        }else{
            return 'fail';
        }

    }


//    //查看商品详情
//    public function actionView($id)
//    {
//        $goods = Goods::findOne($id);
//
//        $view = GoodsIntro::findOne($id);
//
//        return $this->render('view', ['view' => $view, 'goods' => $goods]);
//    }



    public function actions() {
        return [

            'ueditor' => [
                'class' => 'crazyfd\ueditor\Upload',
                'config'=>[
                    'uploadDir'=>date('Y/m/d')
                ]

            ],

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
//                'format' => function (UploadAction $action) {
//                    $fileext = $action->uploadfile->getExtension();
//                    $filename = sha1_file($action->uploadfile->tempName);
//                    return "{$filename}.{$fileext}";
//                },
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
                    //图片上传成功的同时，将图片和商品关联起来
                    $model = new GoodsPhoto();
                    $model->goods_id = \Yii::$app->request->post('goods_id');
                    $model->goods_photos = $action->getWebUrl();
                    $model->save();
                    $action->output['fileUrl'] = $model->goods_photos;
                },
            ],
        ];
    }


}
