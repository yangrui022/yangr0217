<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170619_095101_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(60)->comment('收货人'),
            'address'=>$this->string(255)->comment('收货地址'),
            'tel'=>$this->integer(11)->comment('手机号码'),

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
