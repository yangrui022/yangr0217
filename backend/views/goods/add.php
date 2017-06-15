<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'market_price');
echo $form->field($model,'shop_price');
echo $form->field($model,'brand_id')->dropDownList(\yii\helpers\ArrayHelper::map($brand,'id','name'),['prompt'=>'选择品牌']);
echo $form->field($model,'status',['inline'=>'ture'])->radioList([1=>'正常',0=>'隐藏']);
echo $form->field($model,'is_on_sale',['inline'=>'ture'])->radioList([1=>'上架',0=>'下架']);
echo $form->field($model,'stock');
echo $form->field($model,'goods_category_id')->hiddenInput();
echo '<ul id="treeDemo" class="ztree"></ul>';
echo $form->field($model,'imgFile')->fileInput();
echo \yii\helpers\Html::img($model->logo,['height'=>80]);
echo $form->field($model,'sort');
echo $form->field($intro, 'intro')->widget(\crazyfd\ueditor\Ueditor::className());
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$zNodes = \yii\helpers\Json::encode(\backend\models\GoodsCategory::find()->asArray()->all());
$js = new \yii\web\JsExpression(
    <<<JS
var zTreeObj;
    // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
    var setting = {
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "parent_id",
                rootPId: 0
            }
        },
        callback: {
		    onClick: function(event, treeId, treeNode) {
                //console.log(treeNode.id);
                //将选中节点的id赋值给表单parent_id
                $("#goods-goods_category_id").val(treeNode.id);
            }
	    }
    };
    // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
    var zNodes = {$zNodes};
    
    zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    zTreeObj.expandAll(true);//展开所有节点
    //获取当前节点的父节点（根据id查找）
    var node = zTreeObj.getNodeByParam("id", $("#goods-goods_category_id").val(), null);
    zTreeObj.selectNode(node);//选中当前节点的父节点
    
JS

);

$this->registerJs($js);
?>