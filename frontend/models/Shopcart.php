<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "shopcart".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property integer $amount
 * @property integer $member_id
 */
class Shopcart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shopcart';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'amount', 'member_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => '商品id',
            'amount' => '商品数量',
            'member_id' => '用户id',
        ];
    }

//    public function beforeSave($insert)
//    {
//        $member_id=Yii::$app->user->getId();
//        $amount=self::findOne(['member_id'=>$member_id]);
//        $this->member_id=$member_id;
//          $this->amount+=$amount;
//
//
//    }
}
