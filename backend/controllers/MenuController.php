<?php

namespace backend\controllers;

use backend\models\Menu;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class MenuController extends  Controller
{
    public function actionIndex()
    {
        $menus=Menu::find()->orderBy(['label'=>SORT_DESC])->all();
        return $this->render('index',['menus'=>$menus]);
    }


    //添加菜单
    public function actionAdd(){

        $model=new Menu();

        if($model->load(\Yii::$app->request->post()) && $model->validate()){

            if(!$model->parent_id){
                $model->parent_id=0;
            }
            $model->save();
            \Yii::$app->session->setFlash('success','添加成功！');
            return $this->redirect(['menu/index']);
        }


        //找到一级菜单


        $data=Menu::find()->where(['parent_id'=>0])->asArray()->all();

        return $this->render('add',['model'=>$model,'data'=>$data]);
    }

public function actionEdit($id){

    $model=Menu::findOne($id);

    if($model->load(\Yii::$app->request->post()) && $model->validate()){

        if(!$model->parent_id){
            $model->parent_id=0;
        }
        $model->save();
        \Yii::$app->session->setFlash('success','修改成功！');
        return $this->redirect(['menu/index']);
    }


    //找到一级菜单
    $data=Menu::find()->where(['parent_id'=>0])->asArray()->all();

    return $this->render('add',['model'=>$model,'data'=>$data]);

}
//删除菜单
public function actionDel($id){

    Menu::findOne($id)->delete();
    \Yii::$app->session->setFlash('danger','删除成功！');
    return $this->redirect(['menu/index']);
}

}
