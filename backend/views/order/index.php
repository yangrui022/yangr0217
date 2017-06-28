<table class="table table-hover  ">
    <tr>
        <th>ID</th>
        <th>用户</th>
        <th>收货人</th>
        <th>收货地址</th>
        <th>电话号码</th>
        <th>送货方式</th>
        <th>付款方式</th>
        <th>总计</th>
        <th>操作</th>


    </tr>
    <?php foreach ($orders as $order):?>
        <tr>
            <td><?=$order->id?></td>
            <td><?=$order->member->username?></td>
            <td><?=$order->name?></td>
            <td><?=$order->province.$order->city.$order->area.$order->address?></td>
            <td><?=$order->tel?></td>
            <td><?=$order->delivery_name?></td>
            <td><?=$order->payment_name?></td>
            <td><?=$order->total?></td>
            <td>
                <?php
                if(Yii::$app->user->can('order/stuats')){
                  echo  $order->stuats==0? \yii\bootstrap\Html::a('待发货',['order/stuats','id'=>$order->id],['class'=>'btn btn-default ']):'已发货';
                }


                if(Yii::$app->user->can('order/del')){

                    echo $order->stuats==2? \yii\bootstrap\Html::a('',['order/del','id'=>$order->id],['class'=>'btn btn-default  glyphicon ']):'';
                }

                ?>


        </tr>
    <?php endforeach;?>
</table>



