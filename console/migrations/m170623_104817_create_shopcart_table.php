<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shopcart`.
 */
class m170623_104817_create_shopcart_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('shopcart', [
            'id' => $this->primaryKey(),
            'goods_id'=>$this->integer()->comment('商品id'),
            'amount'=>$this->integer()->comment('商品数量'),
            'member_id'=>$this->integer()->comment('用户id'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('shopcart');
    }
}
