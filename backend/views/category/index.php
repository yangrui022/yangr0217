<table class="table table-hover table-bordered ">
    <tr>
        <th>ID</th>
        <th>文章分类名</th>
        <th>文章简介</th>
        <th>文章排序</th>
        <th>状态</th>
        <th>操作</th>


    </tr>
    <?php foreach ($categorys as $category):?>
        <tr>
            <td><?=$category->id?></td>
            <td><?=$category->name?></td>
            <td><?=$category->intro?></td>
            <td><?=$category->sort?></td>
            <td><?=\backend\models\ArticleCategory::$categoryOptions[$category->status]?></td>
            <td>
                <?php
                if(Yii::$app->user->can('category/edit')){
                    echo  \yii\bootstrap\Html::a('',['category/edit','id'=>$category->id],['class'=>'btn btn-default  glyphicon glyphicon-edit']);
                }

                if(Yii::$app->user->can('category/del')){
                    echo  \yii\bootstrap\Html::a('',['category/del','id'=>$category->id],['class'=>'btn btn-default  glyphicon  glyphicon-trash']);
                }

                ?>


        </tr>
    <?php endforeach;?>
</table>


<div>
    <a href="<?=\yii\helpers\Url::to(['category/add'])?>" class="btn btn-info">添加</a>
</div>


