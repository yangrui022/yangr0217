<?php
use yii\helpers\Html;
?>
<!-- 登录主体部分start -->
<div class="login w990 bc mt10 regist">
    <div class="login_hd">
        <h2>用户注册</h2>
        <b></b>
    </div>
    <div class="login_bd">
        <div class="login_form fl">
        <?php
        $form = \yii\widgets\ActiveForm::begin(
            ['fieldConfig'=>[
                'options'=>[
                    'tag'=>'li',
                ],
                'errorOptions'=>[
                    'tag'=>'p'
                ]
            ]]
        );
        echo '<ul>';
        echo $form->field($model,'username')->textInput(['class'=>'txt']);//用户名

        echo $form->field($model,'password')->passwordInput(['class'=>'txt']);//密码
        echo $form->field($model,'re_password')->passwordInput(['class'=>'txt']);//确认密码
        echo $form->field($model,'email')->textInput(['class'=>'txt']);//邮箱
        echo $form->field($model,'tel')->textInput(['class'=>'txt']);//电话
        $button =  Html::button('发送验证码',['id'=>'send_sms_button']);
        echo $form->field($model,'smsCode',['options'=>['class'=>'checkcode'],'template'=>"{label}\n{input}$button\n{hint}\n{error}"])->textInput(['class'=>'txt']);
        echo $form->field($model,'code',['options'=>['class'=>'checkcode']])->widget(\yii\captcha\Captcha::className(),['template'=>'{input}{image}']);

        echo '<li>
                        <label for="">&nbsp;</label>
                        <input type="submit" value="" class="login_btn">
                    </li>';
        echo '</ul>';
        \yii\widgets\ActiveForm::end();
        ?>


        </div>

        <div class="mobile fl">
            <h3>手机快速注册</h3>
            <p>中国大陆手机用户，编辑短信 “<strong>XX</strong>”发送到：</p>
            <p><strong>1069099988</strong></p>
        </div>

    </div>
</div>
<!-- 登录主体部分end -->
<?php
/* @var $this \yii\web\View
 */
$url=\yii\helpers\Url::to(['user/sendsms']);
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
        $('#send_sms_button').click(function(){
            
            
               //当发送验证码被点击的时候
          //现获取到手机号码和用户名
          var tel=$('#member-tel').val();
          var usernmae=$('#member-username').val();
          //发送ajax请求
          $.post('$url',{'tel':tel,'username':usernmae},function(data) {
            if(data == 'success'){
                console.log('短信发送成功');
                
            }else{
                console.log(data);
            }
         
            
         })
            //启用输入框
            // $('#member-smscode').prop('disabled',false);
            //
            // var time=30;
            // var interval = setInterval(function(){
				// time--;
				// if(time<=0){
				// 	clearInterval(interval);
				// 	var html = '获取验证码';
				// 	$('#send_sms_button').prop('disabled',false);
				// } else{
				// 	var html = time + ' 秒后再次获取';
				// 	$('#send_sms_button').prop('disabled',true);
				// }
				//
				// $('#send_sms_button').text(html);
            // },1000);
		
        
         
          
       
        
        });
		
	


JS




));
