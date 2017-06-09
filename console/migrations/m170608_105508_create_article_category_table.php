<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m170608_105508_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),
//            name varchar﴾50﴿ 名称
            'name' => $this->string(50)->comment('分类名称'),
//            intro text 简介
            'intro' => $this->text()->comment('分类简介'),
//            sort int﴾11﴿ 排序
            'sort' => $this-> integer(11)->comment('分类排序'),
//            status int﴾2﴿ 状态﴾‐1删除 0隐藏 1正常﴿
            'status' => $this->integer(11)->comment('分类状态'),
//            is_help int﴾1﴿ 类型
            'is_help' => $this->integer(1)->comment('分类类型'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_category');
    }
}
