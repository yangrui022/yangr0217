<table class="table table-hover table-bordered ">
    <tr>
        <th>ID</th>
        <th>头像</th>
        <th>用户名</th>
        <th>邮箱</th>
        <th>状态</th>
        <th>注册时间</th>
        <th>更新时间</th>
        <th>最后登录时间</th>
        <th>最后登录ip</th>
        <th>操作</th>


    </tr>
    <?php foreach ($users as $user):?>
        <tr>
            <td><?=$user->id?></td>
            <td><?=\yii\bootstrap\Html::img(Yii::getAlias('@web').$user->photo,['width'=>'60'])?></td>
            <td><?=$user->username?></td>
            <td><?=$user->email?></td>
            <td><?=$user->status?'在线':'离线'?></td>
            <td><?=date('Y-m-d H:i:s',$user->create_time)?></td>
            <td><?=date('Y-m-d H:i:s',$user->updated_time)?></td>
            <td><?=date('Y-m-d H:i:s',$user->last_login_time)?></td>
            <td><?=$user->last_login_ip?></td>


            <td><a href='<?=\yii\helpers\Url::to(['user/delete','id'=>$user->id])?>' class="btn btn-danger glyphicon glyphicon-trash"></a>
                <a href='<?=\yii\helpers\Url::to(['user/edit','id'=>$user->id])?>' class="btn btn-info ">修改</a>
                <a href='<?=\yii\helpers\Url::to(['user/pwd','id'=>$user->id])?>' class="btn btn-success ">修改密码</a>
            </td>
        </tr>
    <?php endforeach;?>
</table>

<div>
    <a href="<?=\yii\helpers\Url::to(['user/add'])?>" class="btn btn-info">添加</a>
</div>


