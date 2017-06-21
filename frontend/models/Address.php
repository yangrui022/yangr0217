<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Address extends ActiveRecord{

    public function rules(){
        return [
            [['name','tel','address','city','province','district'],'required'],
            ['default','safe'],
            ['stutas','safe'],
            [['tel'],'integer'],
        ];
    }
    public function attributeLabels(){
        return [
            'name'=>'收货人 :',
            'tel'=>'手机号 :',
            'area'=>'所在地区 :',
            'address'=>'详细地址 :',
            'stutas'=>'设置为默认地址 :',
        ];
    }
    public function getProvinces(){
        return $this->hasOne(Locations::className(),['id'=>'province']);
    }
    public function getCitys(){
        return $this->hasOne(Locations::className(),['id'=>'city']);
    }
    public function getDistricts(){
        return $this->hasOne(Locations::className(),['id'=>'district']);
    }
}
