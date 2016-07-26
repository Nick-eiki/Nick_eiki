<div class="reg_c_c">
    <h3>欢迎注册</h3>
    <?php
    use yii\helpers\Html;

    $form =\yii\widgets\ActiveForm::begin(array(
        'enableClientScript' => false,
    ))?>
    <ul class="form_list">

        <li >
            <div class="formL"><label for="name"><i></i>邮箱：</label></div>
            <div class="formR">
                <input name="<?php echo Html::getInputName($model, 'email')?>"
                       id="<?php echo Html::getInputId($model, 'email')?>"
                       data-validation-engine="validate[required,custom[email],ajax[ajaxEmailCall]]"
                       class="text" type="text" value="<?php echo $model->email ?>" />
                <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'email')?>
            </div>
        </li>
        <li >
            <div class="formL"><label><i></i>密码：</label></div>
            <div class="formR">
                <input name="<?php echo Html::getInputName($model, 'passwd')?>"
                       id="<?php echo Html::getInputId($model, 'passwd')?>"
                       data-validation-engine="validate[required,minSize[6],maxSize[20]]"
                       data-errormessage-value-missing="密码不能为空"
                       data-errormessage-custom-error="无效的密码"
                       class="text" type="password" />
                <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'passwd')?>
            </div>

        </li>
        <li >
            <div class="formL"><label><i></i>确认密码：</label></div>
            <div class="formR focus">
                <input name="<?php echo Html::getInputName($model, 'repasswd')?>"
                       id="<?php echo Html::getInputId($model, 'repasswd')?>"
                       data-validation-engine="validate[required,equals[<?php echo Html::getInputId($model, 'passwd')?>]]"
                       data-errormessage-value-missing="确认密码不能为空"
                       data-errormessage-pattern-mismatch="密码不一致，请重新输入！"
                       class="text" type="password" />
                <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'repasswd')?>
            </div>

        </li>
        <li>
            <div class="formL"><label><i></i>身份：</label></div>
            <div class="formR change">
                <input type="radio" class="tab" name="<?php echo Html::getInputName($model, 'type') ?>" checked value="0"><label class="role">学生</label>
               <input type="radio" value="1" name="<?php echo Html::getInputName($model, 'type') ?>"> <label class="role">老师</label>
            </div>
        </li>
        <li class="tab_box">
            <ul class="form_list tab_ul" style="display:block">
                <li>
                    <div class="formL" id="name"><label><i></i>学生姓名：</label></div>
                    <div class="formR">
                        <input name="<?php echo Html::getInputName($model, 'trueName')?>"
                               id="<?php echo Html::getInputId($model, 'trueName')?>"
                               data-validation-engine="validate[required]"
                               data-errormessage-value-missing="不能为空"
                               class="text" type="text" value="<?php echo $model->trueName ?>" />
                        <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'trueName')?>
                    </div>
                </li>
                <li>
                    <div class="formL" id="mobile"><label><i></i>家长手机号：</label></div>
                    <div class="formR click_f genearch">
                        <input name="<?php echo Html::getInputName($model, 'mobile')?>"
                               id="<?php echo Html::getInputId($model, 'mobile')?>"
                               data-validation-engine="validate[required,custom[phone]]"
                               data-errormessage-value-missing="手机号不能为空"
                               class="text" type="text" value="<?php echo $model->mobile ?>" />
                        <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'mobile')?>

                    </div>
                </li>
            </ul>
        </li>
        <li>
            <div class="formL"> <label class="label"></label></div>
            <div class="formR">
                <input type="checkbox"   name="<?php echo Html::getInputName($model, 'checkagree')?>" class="check" data-validation-engine="validate[required]"  data-prompt-position="inline" data-errormessage-value-missing="请同意服务协议！" data-prompt-target="agree_prompt" >
                <span class="prompt" id="agree_prompt">我已阅读并同意<a href="register/agreement" target="_blank">服务使用协议</a></span>
            </div>
        </li>
        <li>
            <div class="formL"> <label class="label"></label> </div>
            <div class="formR sub_box">
                <button type="submit" class="submitBtn btn">注&nbsp;&nbsp;册</button>
            </div>

        </li>
    </ul>
    <?php \yii\widgets\ActiveForm::end();?>
</div>


<script type="text/javascript">
    function checkagree(){
        if($("#agree").is(":checked")==false){
            alert("请勾选下面的同意条款");
            return false;
        }
        return true;

    }
    $('.change input:radio').click(function(){
        var changes=$('.change input:[type="radio"]:checked').val();
        if(changes==0){
           $('#name label').html('<i></i>学生姓名');
            $('#mobile label').html('<i></i>家长手机号');
        }
        else{
            $('#name label').html('<i></i>教师姓名:');
            $('#mobile label').html('<i></i>教师手机号');

        }
    })

</script>