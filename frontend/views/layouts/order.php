<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

//加载静态资源管理器，注册静态资源到当前布局文件
\frontend\assets\CartAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<!-- 顶部导航 start -->
<div class="topnav">
    <div class="topnav_bd w990 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>
                <li><?php if(Yii::$app->user->isGuest){
                        echo '您好，欢迎来到京西!'.'['.Html::a('登录',['user/login']).']';
                        echo '['.Html::a('免费注册',['user/register']).']';
                    }else{
                        echo '['.Html::a('注销('.Yii::$app->user->identity->username.')',['user/logout']).']';
                    }
                    ?>  </li>
                <li class="line">|</li>
                <li>我的订单</li>
                <li class="line">|</li>
                <li>客户服务</li>

            </ul>
        </div>
    </div>
</div>
<!-- 顶部导航 end -->

<div style="clear:both;"></div>

<!-- 页面头部 start -->
<!-- 头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><?=Html::img('@web/images/logo.png')?></a></h2>
        <?php $class=Yii::$app->request->pathInfo?>
        <div  class="flow fr <?=$class=='order/order-info.html'?'flow2':'' ?><?=$class=='order/success.html'?'flow3':'' ?>">
            <ul>
                <li>1.我的购物车</li>
                <li class="<?=$class=='order/order-info.html'?'cur':'' ?>">2.填写核对订单信息</li>
                <li class="<?=$class=='order/success.html'?'cur':'' ?>">3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>


<?=$content?>


<div style="clear:both;"></div>
<!-- 底部版权 start -->
<div class="footer w1210 bc mt10">
    <p class="links">
        <a href="">关于我们</a> |
        <a href="">联系我们</a> |
        <a href="">人才招聘</a> |
        <a href="">商家入驻</a> |
        <a href="">千寻网</a> |
        <a href="">奢侈品网</a> |
        <a href="">广告服务</a> |
        <a href="">移动终端</a> |
        <a href="">友情链接</a> |
        <a href="">销售联盟</a> |
        <a href="">京西论坛</a>
    </p>
    <p class="copyright">
        © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号
    </p>
    <p class="auth">
        <a href=""><?=Html::img('@web/images/xin.png')?></a>
        <a href=""><?=Html::img('@web/images/kexin.jpg')?></a>
        <a href=""><?=Html::img('@web/images/police.jpg')?></a>
        <a href=""><?=Html::img('@web/images/beian.gif')?></a>
    </p>
</div>

<!-- 底部版权 end -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
