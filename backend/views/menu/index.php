<table class="table table-bordered">
    <thead>
    <tr>
        <th>菜单名称</th>
        <th>排序</th>
        <th>一级菜单</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($menus as $menu):?>
        <tr>
            <td><?=$menu->label?></td>
            <td><?=$menu->sort?></td>
           <td><?=$menu->parent_id?$menu->parent->label:'一级分类'?></td>
            <td>

                <?php
                if(Yii::$app->user->can('menu/edit')){
                echo \yii\bootstrap\Html::a('修改',['menu/edit','id'=>$menu->id],['class'=>'btn btn-default  glyphicon glyphicon-edit']);
                }
                if(Yii::$app->user->can('menu/del')){
                    echo  \yii\bootstrap\Html::a('删除',['menu/del','id'=>$menu->id],['class'=>'btn btn-default  glyphicon glyphicon-trash']);
                }
                ?>


        </tr>
    <?php endforeach;?>
    </tbody>
</table>


<?php
if(Yii::$app->user->can('menu/add')){
    echo  \yii\bootstrap\Html::a('添加',['menu/add','id'=>$menu->id],['class'=>'btn btn-defaul glyphicon glyphicon-plus']);
}


/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
$this->registerJsFile('//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
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