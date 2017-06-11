<table class="table table-hover table-bordered ">
    <tr>
        <th>ID</th>
        <th>文章名称</th>
        <th width="15%">文章简介</th>
        <th >文章所属分类</th>
        <th>排序</th>
        <th>状态</th>
        <th>创建时间</th>
        <th width="20%">操作</th>


    </tr>
    <?php foreach ($articles as $article):?>
        <tr>
            <td><?=$article->id?></td>
            <td><?=$article->name?></td>
            <td><?=mb_substr($article->intro,0,50)?></td>
            <td><?=$article->category->name?></td>
            <td><?=$article->sort?></td>
            <td><?=\backend\models\Brand::$statuOptions[$article->status]?></td>

            <td><?=date('Y-m-d H:i:s',$article->create_time)?></td>
            <td><a href='<?=\yii\helpers\Url::to(['article/delete','id'=>$article->id])?>' class="btn btn-danger btn-sm ">删除</a>
                <a href='<?=\yii\helpers\Url::to(['article/edit','id'=>$article->id])?>' class="btn btn-info  btn-sm ">修改</a>
                <a href='<?=\yii\helpers\Url::to(['article/detail','id'=>$article->id])?>' class="btn btn-success btn-sm ">查看文章详情</a>

            </td>
        </tr>
    <?php endforeach;?>
</table>
<div>
    <?=\yii\widgets\LinkPager::widget(['pagination'=>$page,'nextPageLabel'=>'下一页',
        'prevPageLabel'=>'上一页','firstPageLabel'=>'首页','lastPageLabel'=>'末页']);?>
</div>

<div>
    <a href="<?=\yii\helpers\Url::to(['article/add'])?>" class="btn btn-info">添加</a>
</div>


