<table class="table table-hover table-bordered ">
    <tr>
        <th>ID</th>
        <th>品牌名称</th>
        <th>品牌简介</th>
        <th>品牌LOGO</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>


    </tr>
    <?php foreach ($brands as $brand):?>
        <tr>
            <td><?=$brand->id?></td>
            <td><?=$brand->name?></td>
            <td><?=$brand->intro?></td>
            <td><?=\yii\bootstrap\Html::img($brand->logo,['width'=>150])?></td>
            <td><?=$brand->sort?></td>
            <td><?=\backend\models\Brand::$statuOptions[$brand->status]?></td>
            <td><a href='<?=\yii\helpers\Url::to(['brand/delete','id'=>$brand->id])?>' class="btn btn-danger ">删除</a>
                <a href='<?=\yii\helpers\Url::to(['brand/edit','id'=>$brand->id])?>' class="btn btn-info ">修改</a></td>
        </tr>
    <?php endforeach;?>
</table>


<div>
    <a href="<?=\yii\helpers\Url::to(['brand/add'])?>" class="btn btn-info">添加</a>
</div>


