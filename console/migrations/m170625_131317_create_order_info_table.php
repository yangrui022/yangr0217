<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_info`.
 */
class m170625_131317_create_order_info_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order_info', [
            'id' => $this->primaryKey(),
            'order_id'=>$this->integer()->comment('订单id'),
            'goods_id'=>$this->integer()->comment('商品id'),
            'goods_name'=>$this->string(255)->comment('商品名'),
            'logo'=>$this->string(255)->comment('商品图片'),
            'price'=>$this->decimal()->comment('商品价格'),
            'amount'=>$this->integer()->comment('商品数量'),
            'total'=>$this->decimal()->comment('商品总数'),

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order_info');
    }
}
