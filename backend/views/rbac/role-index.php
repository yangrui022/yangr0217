<table class="table table-bordered">
    <tr>
        <th>角色名称</th>
        <th>角色描述</th>
        <th>角色权限</th>
        <th>操作</th>
    </tr>
    <?php foreach ($roles as $role):?>
        <tr>
            <td><?=$role->name?></td>
            <td><?=$role->description?></td>
            <td><?php
                foreach (Yii::$app->authManager->getPermissionsByRole($role->name) as $permission){
                    echo $permission->description;
                    echo '&nbsp;';
                }
                ?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['rbac/edit-role','name'=>$role->name],['class'=>'btn btn-warning btn-sm'])?>
            <?=\yii\bootstrap\Html::a('删除',['rbac/del-role','name'=>$role->name],['class'=>'btn btn-danger btn-sm'])?></td>
        </tr>
    <?php endforeach;?>
</table>
<a href="<?=\yii\helpers\Url::to(['rbac/add-role'])?>" class="btn btn-info">添加</a>
<a href="<?=\yii\helpers\Url::to(['rbac/permission-index'])?>" class="btn btn-info">权限列表</a>