<table class="table table-bordered">
    <thead>
        <tr>
            <th>权限名称</th>
            <th>权限描述</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($permissions as $permission):?>

            <tr>
                <td><?=$permission->name?></td>
                <td><?=$permission->description?></td>
                <td><?=\yii\bootstrap\Html::a('修改',['rbac/edit-permission','name'=>$permission->name],['class'=>'btn btn-warning btn-sm'])?>
                <?=\yii\bootstrap\Html::a('删除',['rbac/del-permission','name'=>$permission->name],['class'=>'btn btn-danger btn-sm'])?></td>
            </tr>

    <?php endforeach;?>
    </tbody>
</table>
<a href="<?=\yii\helpers\Url::to(['rbac/add-permission'])?>" class="btn btn-info">添加</a>
<?php
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('/css/jquery.dataTables.min.css');
$this->registerJsFile('/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs('$(".table").DataTable({
    "oLanguage" : {
        "sLengthMenu": "每页显示 _MENU_ 条记录",
        "sZeroRecords": "抱歉， 没有找到",
        "sInfo": "从 _START_ 到 _END_ /共 _TOTAL_ 条数据",
        "sInfoEmpty": "没有数据",
        "sInfoFiltered": "(从 _MAX_ 条数据中检索)",
        "sZeroRecords": "没有检索到数据",
         "sSearch": "搜索:",
        "oPaginate": {
        "sFirst": "首页",
        "sPrevious": "前一页",
        "sNext": "后一页",
        "sLast": "尾页"
        }

    }

});');