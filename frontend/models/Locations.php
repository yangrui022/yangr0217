<?php
namespace frontend\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;


class Locations extends ActiveRecord{


    //建立省与市1：1
    public function getCitys(){

        return $this->hasMany(self::className(),['parent_id'=>'id']);

    }


    public static function getRegion($parentId=0)
    {
        $result = static::find()->where(['parent_id'=>$parentId])->asArray()->all();
        return ArrayHelper::map($result, 'id', 'name');
    }
    public static function  getArea($id){

        $area=Locations::findOne($id);
        return $area->name;

    }
}