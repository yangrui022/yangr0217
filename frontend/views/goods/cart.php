<?php
/**
 * @var $this \yii\web\View
 */

?>


<!-- 主体部分 start -->
<div class="mycart w990 mt10 bc">
    <h2><span>我的购物车</span></h2>
    <table>
        <thead>
        <tr>

            <th class="col1">商品名称</th>
            <th class="col3">单价</th>
            <th class="col4">数量</th>
            <th class="col5">小计</th>
            <th class="col6">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($models as $model):?>
        <tr>
            <input type="hidden" class="goods_id" value="<?=$model['id']?>"/>
            <td class="col1"><a href=""><?=\yii\helpers\Html::img('http://admin.yii2shop.com/'.$model['logo'])?></a> <strong><?=\yii\helpers\Html::a($model['name'])?></strong></td>
            <td class="col3">￥<span><?=$model['shop_price']?></span></td>
            <td class="col4">
                <a href="javascript:;" class="reduce_num"></a>
                <input type="text" name="amount" value="<?=$model['amount']?>" class="amount"/>
                <a href="javascript:;" class="add_num"></a>
            </td>
            <td class="col5">￥<span><?=$model['amount']*$model['shop_price']?></span></td>
            <td class="col6"><a href="javascript:" class="btn-del">删除</a></td>
        </tr>
      <?php endforeach;?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="6">购物金额总计： <strong>￥ <span id="total">1870.00</span></strong></td>
        </tr>
        </tfoot>
    </table>
    <div class="cart_btn w990 bc mt10">
        <?=\yii\helpers\Html::a('继续购物',['index/index'],['class'=>'continue'])?>
        <a href="" class="checkout">结 算</a>
    </div>
</div>

<?php
$url=\yii\helpers\Url::to(['goods/update-cart']);
$token = Yii::$app->request->csrfToken;
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
//绑定添加或减少的监听事件
    $('.add_num,.reduce_num').click(function(){
            // console.debug(this);
            //找到商品数量
            var amount=$(this).closest('tr').find('.amount').val();
            // console.debug(amount);
            //找到商品id
            var goods_id=$(this).closest('tr').find('.goods_id').val();
            // console.debug(goods_id);
            //发送ajax 请求
            $.post('$url',{'goods_id':goods_id,'amount':amount,"_csrf-frontend":"$token"},function(data){
                console.debug(amount);
              
            });
    });

//删除该商品，
    $('.btn-del').click(function() {
        if(confirm('你确定要删除该商品吗？')){
             var goods_id=$(this).closest('tr').find('.goods_id').val();
             //发送ajax 请求
             $.post('$url',{'goods_id':goods_id,'amount':0,"_csrf-frontend":"$token"})
         $(this).closest('tr').remove();
        }
           
    });


JS


));


