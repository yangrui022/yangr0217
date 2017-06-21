<?php

$form = \yii\bootstrap\ActiveForm::begin([
    'method' => 'get',
    //get方式提交,需要显式指定action
    'action'=>\yii\helpers\Url::to(['goods/index']),
    'options'=>['class'=>'form-inline']
]);
echo $form->field($model,'name')->textInput(['placeholder'=>'商品名'])->label(false);
echo $form->field($model,'sn')->textInput(['placeholder'=>'货号'])->label(false);
echo $form->field($model,'minPrice')->textInput(['placeholder'=>'￥'])->label(false);
echo $form->field($model,'maxPrice')->textInput(['placeholder'=>'￥'])->label('-');
echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();

?>



<table class="table table-bordered">
    </div>
    <tr>
        <th>ID</th>
        <th></th>
        <th>货号</th>
        <th>商品LOGO</th>
        <th>市场价格</th>
        <th>商品价格</th>
        <th>商品品牌</th>
        <th>商品分类</th>
        <th>是否上架</th>
        <th>商品库存</th>
        <th>商品状态</th>
        <th>商品排序</th>
        <th>创建时间</th>
        <th width="200px">操作</th>


    </tr>
    <?php foreach ($goods as $good):?>
        <tr>
            <td><?=$good->id?></td>
            <td><?=$good->name?></td>
            <td><?=$good->sn?></td>
            <td><?=\yii\bootstrap\Html::img(Yii::getAlias('@web').$good->logo,['width'=>100,'class'=>'img-thumbnail'])?></td>
            <td><?=$good->market_price?></td>
            <td><?=$good->shop_price?></td>
            <td><?=$good->brand->name?></td>
            <td><?=$good->category->name?></td>
            <td><?=$good->is_on_sale?'上架':'下架'?></td>
            <td><?=$good->stock?></td>
            <td><?=\backend\models\Goods::$Options[$good->status]?></td>
            <td><?=$good->sort?></td>
            <td><?= date('Ymd H:i:s',$good->create_time)?></td>
            <td>
                <?php
                if(Yii::$app->user->can('goods/edit')){
                    echo  \yii\bootstrap\Html::a('',['goods/edit','id'=>$good->id],['class'=>'btn btn-default  glyphicon glyphicon-edit']);
                }

                if(Yii::$app->user->can('goods/detail')){
                    echo  \yii\bootstrap\Html::a('',['goods/view','id'=>$good->id],['class'=>'btn btn-default  glyphicon  glyphicon-search']);
                }
                if(Yii::$app->user->can('goods/del')){
                    echo  \yii\bootstrap\Html::a('',['goods/del','id'=>$good->id],['class'=>'btn btn-default  glyphicon  glyphicon-trash']);
                }
                if(Yii::$app->user->can('goods/photo')){
                    echo  \yii\bootstrap\Html::a('',['goods/photo','id'=>$good->id],['class'=>'btn btn-default  glyphicon  glyphicon-picture']);
                }
                ?>



            </td>
        </tr>
    <?php endforeach;?>
</table>

<div>
    <?=\yii\widgets\LinkPager::widget(['pagination'=>$page,'nextPageLabel'=>'下一页',
        'prevPageLabel'=>'上一页','firstPageLabel'=>'首页','lastPageLabel'=>'末页']);?>
</div>
<?php
if(Yii::$app->user->can('goods/add')){
echo  \yii\bootstrap\Html::a('添加商品',['goods/add'],['class'=>'btn btn-default  glyphicon glyphicon-shopping-cart']);
}





