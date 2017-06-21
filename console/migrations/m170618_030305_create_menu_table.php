<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170618_030305_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'label'=>$this->string(255)->comment('菜单名称'),
            'url'=>$this->string(255)->comment('路由'),
            'parent_id'=>$this->integer()->comment('一级菜单'),
            'sort'=>$this->integer()->comment('排序'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
