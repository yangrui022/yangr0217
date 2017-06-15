<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_intro`.
 */
class m170612_075458_create_goods_intro_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_intro', [
            'goods_id' => $this->integer(),
            'intro'=>$this->text()->comment('商品详情'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_intro');
    }
}
