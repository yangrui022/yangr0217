<?php
$form=\yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'label');
echo $form->field($model,'url');
echo $form->field($model,'sort');
echo $form->field($model,'parent_id')->dropDownList(\yii\helpers\ArrayHelper::map($data,'id','label'),['prompt'=>'选择一级菜单，不选择就创建一级菜单']);
echo  \yii\bootstrap\Html::submitButton('添加',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();