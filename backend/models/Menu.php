<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $label
 * @property string $url
 * @property integer $parent_id
 * @property integer $sort
 */
class Menu extends \yii\db\ActiveRecord
{
//获取一级菜单
    public function getParent(){

      return $this->hasOne(self::className(),['id'=>'parent_id']);
    }
    //获取子菜单
    public function getChildren()
    {
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'sort'], 'integer'],
            [['label', 'url'], 'string', 'max' => 255],
            [['label','url'],'unique','message'=>'{attribute}已存在']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => '菜单名称',
            'url' => '路由',
            'parent_id' => '一级菜单',
            'sort' => '排序',
        ];
    }
}
