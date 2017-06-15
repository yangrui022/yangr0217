<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_photo`.
 */
class m170612_111942_create_goods_photo_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_photo', [
            'goods_id' => $this->primaryKey(),
            'goods_photos'=>$this->string(255)->comment('商品相册'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_photo');
    }
}
