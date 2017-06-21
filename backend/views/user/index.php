<table class="table table-hover table-bordered ">
    <tr>
        <th>ID</th>
        <th>头像</th>
        <th>用户名</th>
        <th>所属角色</th>
        <th>邮箱</th>
        <th>状态</th>
        <th>注册时间</th>
        <th>更新时间</th>
        <th>最后登录时间</th>
        <th>最后登录ip</th>
        <th width="20%">操作</th>


    </tr>
    <?php foreach ($users as $user):?>
        <tr>
            <td><?=$user->id?></td>
            <td><?=\yii\bootstrap\Html::img(Yii::getAlias('@web').$user->photo,['width'=>'60'])?></td>
            <td><?=$user->username?></td>
            <td><?php foreach (Yii::$app->authManager->getRolesByUser($user->id) as $role){
                    echo $role->name;

                }?></td>
            <td><?=$user->email?></td>
            <td><?=$user->status?'在线':'离线'?></td>
            <td><?=date('Y-m-d H:i:s',$user->create_time)?></td>
            <td><?=date('Y-m-d H:i:s',$user->updated_time)?></td>
            <td><?=date('Y-m-d H:i:s',$user->last_login_time)?></td>
            <td><?=$user->last_login_ip?></td>


            <td>
            <?php
            if(Yii::$app->user->can('user/edit')){
                echo  \yii\bootstrap\Html::a('',['user/edit','id'=>$user->id],['class'=>'btn btn-default  glyphicon glyphicon-edit']);
            }
            if(Yii::$app->user->can('user/edit')){
                echo  \yii\bootstrap\Html::a('修改密码',['user/add','id'=>$user->id],['class'=>'btn btn-default  glyphicon glyphicon-wrench']);
            }
            if(Yii::$app->user->can('user/edit')){
                echo  \yii\bootstrap\Html::a('',['user/del','id'=>$user->id],['class'=>'btn btn-default  glyphicon glyphicon-trash']);
            }
            ?>


            </td>
        </tr>
    <?php endforeach;?>
</table>

<div>
<?php
if(Yii::$app->user->can('user/add')){
    echo  \yii\bootstrap\Html::a('注册',['user/add','id'=>$user->id],['class'=>'btn btn-default  glyphicon ']);
}
?>

</div>


