
<?php
/**
 *@var $this yii\web\view
 */
?>
<!-- 主体部分 start -->
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>

    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <?php foreach ($addresses as $address):?>


                <p>
                    <input type="radio" value="<?=$address->id?>" <?=$address->stutas==1?'checked':''?> name="address_id"/><?=$address->name.'&emsp;'.$address->tel.'&emsp;'.\frontend\models\Locations::getArea($address->province).'&emsp;'.\frontend\models\Locations::getArea($address->city).'&emsp;'.\frontend\models\Locations::getArea($address->district)?></p>

            <?php endforeach;?>
            </div>


        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>


            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($infos as $k=>$info):
                    ?>
                    <tr class="<?=$k==0?'cur':''?>">
                        <td>
                            <input type="radio"value="<?=$info['delivery_id'] ?>" <?=$k==0?'checked':''?> name="delivery" class="style" /><?=$info['delivery_name']?>

                        </td>
                        <td class="money">￥<span><?=$info['delivery_price']?></span></td>
                        <td><?=$info['delivery_info']?></td>
                    </tr>
                    <?php endforeach;?>

                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>


            <div class="pay_select">
                <table>
                    <?php foreach ($payments as $k1=>$payment):?>
                    <tr class="<?=$k1==0?'cur':''?>">
                        <td class="col1"><input type="radio" value="<?=$payment['payment_id']?>"   name="pay" /><?=$payment['payment_name']?></td>
                        <td class="col2"><?=$payment['payment_info']?></td>
                    </tr>
                    <?php endforeach;?>

                </table>

            </div>
        </div>
        <!-- 支付方式  end-->

        <!-- 发票信息 start-->
        <div class="receipt none">
            <h3>发票信息 </h3>


            <div class="receipt_select ">
                <form action="">
                    <ul>
                        <li>
                            <label for="">发票抬头：</label>
                            <input type="radio" name="type" checked="checked" class="personal" />个人
                            <input type="radio" name="type" class="company"/>单位
                            <input type="text" class="txt company_input" disabled="disabled" />
                        </li>
                        <li>
                            <label for="">发票内容：</label>
                            <input type="radio" name="content" checked="checked" />明细
                            <input type="radio" name="content" />办公用品
                            <input type="radio" name="content" />体育休闲
                            <input type="radio" name="content" />耗材
                        </li>
                    </ul>
                </form>

            </div>
        </div>
        <!-- 发票信息 end-->

        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <?php
                    foreach ($models as $model):
                    ?>
                    <td class="col1"><a href=""><?=\yii\helpers\Html::img('http://admin.yii2shop.com/'.$model['logo'])?></a>  <strong><a href=""><?=$model['name']?></a></strong></td>
                    <td class="col3">￥<?=$model['shop_price']?></td>
                    <td class="col4"> <?=$model['amount']?></td>
                    <td class="col5"><span>￥<?=$model['shop_price']*$model['amount']?></span></td>
                </tr>
                <?php endforeach;?>

                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span><?=count($models)?>件商品，总商品金额：</span>
                                <?php
                                $sum=0;
                                foreach ($models as $model){
                                    $sum+=$model['shop_price']*$model['amount'];
                                }?>
                                <em >￥<span class="shop"><?=$sum?></span></em>

                            </li>

                            <li>
                                <span>运费：</span>

                                <em >￥<span class="yf">20</span></em>

                            </li>
                            <li>
                                <span>应付总额：</span>
                                <em>￥<span class="totle"><?=$sum+20?></span></em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>

    <div class="fillin_ft">

        <a href="javascript:void(0)" class="cg_btn"><span>提交订单</span></a>


        <p>应付总额：<strong>￥<span  class="totle"><?=20+$sum?></span>.00元</strong></p>

    </div>
</div>
<!-- 主体部分 end -->

<div style="clear:both;"></div>
<?php
$url=\yii\helpers\Url::to(['order/order-cg']);
$token = Yii::$app->request->csrfToken;
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
//绑定配送方式监听事件
$(function() {
  
        
  $('.style').change(function() {
             
         //获取当当前选中的配送金额
      var money=$(this).closest('tr').find('.money span').html();
     //将获取到的配送金额加入
        var a= $('.yf').html(money)
       
        //获取到商品的金额
        var shop=$('.shop').html();
       
        //找到总金额
        var tot=money*1+shop*1
        //将总金额放入
        $('.totle').html(tot);
           
        

  });

//给提交订单绑定事件
$('.cg_btn').click(function() {
  
    //获取到选择地址id
    var address=$('.address_info input:checked').val();
   
    //获取配送方式
    var delivery=$('.delivery input:checked').val();
    //获取支付方式
      var payment=$('.pay_select input:checked').val();
      var total=$('.totle').html();
      
           
       
      // console.debug(pay_select)
      
      //发送ajax 请求到后台
      $.post('$url',{address:address,delivery:delivery,payment:payment,total:total,"_csrf-frontend":"$token"} ,function(data) {
          
            if(data=='success'){
                window.location.href="http://www.yii2shop.com/order/success.html";
            }else {
                alert('库存不足！');
                window.location.href="http://www.yii2shop.com/goods/cart.html";
            }
      });
    
});
  

  
});


JS


));

