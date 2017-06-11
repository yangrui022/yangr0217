<table class="table table-hover table-bordered ">
    <tr>
        <th>ID</th>
        <th>分类名称</th>
        <th width="15%">分类简介</th>
        <th >上级分类id</th>
        <th width="20%">操作</th>


    </tr>
    <?php foreach ($goods_categories as $goods_categorie):?>
        <tr>
            <td><?=$goods_categorie->id?></td>
            <td><?=$goods_categorie->name?></td>
            <td><?=$goods_categorie->intro?></td>
            <td><?=$goods_categorie->parent_id?></td>

            <td><a href='<?=\yii\helpers\Url::to(['goods-category/delete','id'=>$goods_categorie->id])?>' class="btn btn-danger btn-sm ">删除</a>
                <a href='<?=\yii\helpers\Url::to(['goods-category/edit','id'=>$goods_categorie->id])?>' class="btn btn-info  btn-sm ">修改</a>


            </td>
        </tr>
    <?php endforeach;?>
</table>

<div>
    <?=\yii\widgets\LinkPager::widget(['pagination'=>$page,'nextPageLabel'=>'下一页',
        'prevPageLabel'=>'上一页','firstPageLabel'=>'首页','lastPageLabel'=>'末页']);?>
</div>
<div>
    <a href="<?=\yii\helpers\Url::to(['goods-category/add'])?>" class="btn btn-info">添加</a>
</div>


