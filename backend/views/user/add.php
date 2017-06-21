<?php $form=\yii\bootstrap\ActiveForm::begin()?>

<?=$form->field($model,'username')->textInput()?>
<?=$form->field($model,'email')->textInput()?>

<?php if(!$model->password_hash){
  echo  $form->field($model,'password_hash')->passwordInput();

}?>
<?=$form->field($model,'imgFile')->fileInput()?>
<?=$form->field($model,'roles',['inline'=>true])->checkboxList(\backend\models\User::getRoleOptions())?>
<?=$form->field($model,'status',['inline'=>'ture'])->radioList([1=>'正常',0=>'隐藏'])?>
<?php if($model->photo) echo "<img src='$model->photo' width='100px'/>"?>

<?= \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info'])?>
<?php \yii\bootstrap\ActiveForm::end();?>