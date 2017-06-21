<table class="cate table table-hover table-bordered ">
    <tr>
        <th>ID</th>
        <th>分类名称</th>
        <th width="15%">分类简介</th>
        <th >上级分类id</th>
        <th width="20%">操作</th>


    </tr>
    <?php foreach ($goods_categories as $goods_categorie):?>
        <tr data-tree="<?=$goods_categorie->tree?>" data-lft="<?=$goods_categorie->lft?>" data-rgt="<?=$goods_categorie->rgt?>">
            <td><?=$goods_categorie->id?></td>
            <td><?=str_repeat('--',$goods_categorie->depth).$goods_categorie->name?>
            <span class="toggle_cate glyphicon glyphicon-triangle-bottom" style="float: right"></span>
            </td>
            <td><?=$goods_categorie->intro?></td>
            <td><?=$goods_categorie->parent_id==0?'顶级分类':$goods_categorie->parent->name?></td>

            <td>
                <?php
                if(Yii::$app->user->can('goods-category/edit')){
                    echo  \yii\bootstrap\Html::a('',['goods-category/edit','id'=>$goods_categorie->id],['class'=>'btn btn-default  glyphicon glyphicon-edit']);
                }
                if(Yii::$app->user->can('goods-category/del')){
                    echo  \yii\bootstrap\Html::a('',['goods-category/del','id'=>$goods_categorie->id],['class'=>'btn btn-default  glyphicon glyphicon-trash']);
                }
                ?>




            </td>
        </tr>
    <?php endforeach;?>
</table>

<div>
    <a href="<?=\yii\helpers\Url::to(['goods-category/add'])?>" class="btn btn-info">添加</a>
</div>

<?php
$js=<<<JS
    $(".toggle_cate").click(function(){
        //查找当前分类的子孙分类（根据房钱的tee lft rgt）
        var tr=$(this).closest('tr');
        var tree=parseInt(tr.attr('data-tree'));
        var lft=parseInt(tr.attr('data-lft'));
        var rgt=parseInt(tr.attr('data-rgt'));
        //显示还是隐藏
        var show = $(this).hasClass('glyphicon-triangle-top')
        //切换图片
        $(this).toggleClass('glyphicon-triangle-top');
        $(this).toggleClass('glyphicon-triangle-bottom');
        $(".cate tr").each(function (){
            if(parseInt($(this).attr('data-tree'))==tree && parseInt($(this).attr('data-lft'))>lft && parseInt($(this).attr('data-rgt'))<rgt
            ){
                // console.log(this);
                show?$(this).fadeIn():$(this).fadeOut();
            }
        });
    });
JS;
$this->registerJs($js);

