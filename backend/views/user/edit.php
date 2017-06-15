
<?php $form=\yii\bootstrap\ActiveForm::begin()?>


<?=$form->field($model,'old_password')->passwordInput()?>
<?=$form->field($model,'new_password')->passwordInput()?>
<?=$form->field($model,'re_password')->passwordInput()?>


<?=$form->field($model,'code')->widget(\yii\captcha\Captcha::className(),[
    'captchaAction'=>'login/captcha',
    'template'=>'<div class="row"><div class="col-lg-2">{input}</div><div class="col-lg-1">{image}</div></div>'

])?>
<?= \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info'])?>
<?php \yii\bootstrap\ActiveForm::end();?>
