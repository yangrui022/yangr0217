<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods_photo".
 *
 * @property integer $goods_id
 * @property string $goods_photos
 */
class GoodsPhoto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id'], 'integer'],
            [['goods_photos'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => 'Goods ID',
            'goods_photos' => '商品相册',
        ];
    }
}
