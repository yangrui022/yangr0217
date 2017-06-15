<?php $form=\yii\bootstrap\ActiveForm::begin()?>

<?=$form->field($model,'username')->textInput()?>

<?=$form->field($model,'password_hash')->passwordInput()?>
<?=$form->field($model,'flag')->checkbox(['value'=>1])?>
<?=$form->field($model,'code')->widget(\yii\captcha\Captcha::className(),[
    'captchaAction'=>'login/captcha',
    'template'=>'<div class="row"><div class="col-lg-2">{input}</div><div class="col-lg-1">{image}</div></div>'

])?>
<?= \yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-info'])?>
<?php \yii\bootstrap\ActiveForm::end();?><?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/14
 * Time: 15:00
 */