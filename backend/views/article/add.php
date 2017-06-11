<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'sort');
echo $form->field($model,'article_category_id')->dropDownList(\yii\helpers\ArrayHelper::map($category,'id','name'),['prompt'=>'选择分类']);

echo $form->field($model,'status')->radioList([1=>'正常',0=>'隐藏']);
echo $form->field($detail, 'content')->widget(\crazyfd\ueditor\Ueditor::className(),[]);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
