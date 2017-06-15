<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property integer $goods_category_id
 * @property string $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property integer $stock
 * @property integer $is_on_sale
 * @property integer $status
 * @property integer $sort
 * @property integer $create_time
 */
class Goods extends \yii\db\ActiveRecord
{

    //创建商品与品牌一对一的关系
    public function getBrand(){

        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }
    public function getCategory(){

        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);
    }


    public static $Options=[0=>'回收站',1=>'正常'];
    public $imgFile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','goods_category_id','brand_id','market_price','shop_price'],'required','message'=>'{attribute}不能为空'],
            [['goods_category_id', 'stock', 'is_on_sale', 'status', 'sort'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name'], 'string', 'max' => 50],
            [['sn'], 'string', 'max' => 20],
            [['logo', 'brand_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '货号',
            'imgFile' => '商品LOGO',
            'goods_category_id' => '商品分类',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否上架',
            'status' => '状态',
            'sort' => '排序',


        ];
    }
/* 商品和相册关系 1对多
*/
    public function getPhotos()
    {
        return $this->hasMany(GoodsPhoto::className(),['goods_id'=>'id']);
    }

    /*
     * 获取商品详情
     */
    public function getGoodsIntro()
    {
        return $this->hasOne(GoodsIntro::className(),['goods_id'=>'id']);
    }

}
