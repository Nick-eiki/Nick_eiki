<?php
/**
 * Created by unizk.
 * User: ysd
 * Date: 2015/4/7
 * Time: 17:48
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title="注册";
?>
<script>
    $(function () {


        $('#form_id').validationEngine({
            'maxErrorsPerField': 1,
            'onFieldSuccess': function (field) {
                $(field).nextAll('.errorTxt').empty().addClass('validationOK')
            },
            'onFieldFailure': function (field) {
                $(field).nextAll('.errorTxt').removeClass('validationOK')
            }
        });

        /*选择身份*/
        $('.identity button').click(function () {
            $(this).addClass('ac').siblings().removeClass('ac');
            $('#userType').val($(this).attr('datatype'));

            if ($('.identity button:last').is('[class$="ac"]'))$('.parentPhone').show();
            else $('.parentPhone').hide();
        })

    })
</script>

<!--主体部分-->
<div class="cont24">
    <div class="grid_19 push_3">
        <h1>注册班海账号</h1>

        <div class="formArea">
            <?php /* @var  $this CActiveForm */
            $form =\yii\widgets\ActiveForm::begin( array(
                //'enableClientScript' => false,
                'id' => 'form_id'
            )) ?>
            <div class="form_list">
                <div class="row">
                    <div class="formL">
                        <label></label>
                    </div>
                    <div class="formR">
                        <p class="bg_blue_l_gray gray_d font12 attention"><i></i> 所有项目都为必填项,请认真填写</p>
                    </div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label>手机号</label>
                    </div>
                    <div class="formR">
                        <input type="text" id="<?= Html::getInputId($model, 'username') ?>"
                               name="<?php echo Html::getInputName($model, 'username') ?>"
                               value="<?php echo $model->username; ?>"
                               data-validation-engine="validate[required,custom[phoneNumber],ajax[ajaxPhoneNumber]]"
                               class="text" data-prompt-position="inline"
                               data-errormessage-value-missing="请输入正确手机号！" data-prompt-target="phoneError">
                        <span id="phoneError" class="errorTxt"></span>
                        <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'username') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label>密码</label>
                    </div>
                    <div class="formR">
                        <input id="pwd" name="<?php echo Html::getInputName($model, 'passwd') ?>" type="password"
                               onchange=" if($('#repasswd').val()!=''){$('#repasswd').validationEngine('validate');}"
                               class="text" data-prompt-position="inline" data-errormessage-value-missing="密码不能为空！"
                               data-validation-engine="validate[required,minSize[6],maxSize[20]]"
                               data-prompt-target="pwd01Error">
                        <span id="pwd01Error"
                              class="errorTxt"></span><?php echo frontend\components\CHtmlExt::validationEngineError($model, 'passwd', 'pwd') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label>确认密码</label>
                    </div>
                    <div class="formR">
                        <input id="repasswd"  type="password" name="<?php echo Html::getInputName($model, 'repasswd') ?>"
                               id="<?php echo Html::getInputId($model, 'repasswd') ?>"
                               class="text" data-errormessage-value-missing="确认密码不能为空！"
                               data-validation-engine="validate[required,minSize[6],maxSize[20],equals[pwd]]"
                               data-prompt-position="inline"
                               data-prompt-target="pwd02Error">
                        <span id="pwd02Error"
                              class="errorTxt"></span><?php echo frontend\components\CHtmlExt::validationEngineError($model, 'repasswd', 'password') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label>身份</label>
                    </div>
                    <div class="formR identity">
                        <button type="button" datatype="1"
                                class="btn40 bg_blue_l_gray gray_d w150 <?= ($model->type == 1) ? 'ac' : '' ?>"> 我是老师
                        </button><button type="button" datatype="0"
                                class="btn40 bg_blue_l_gray gray_d w150 <?= ($model->type == 0) ? 'ac' : '' ?>"> 我是学生
                        </button>

                        <input id="userType" type="hidden" value="1"
                               data-prompt-position="inline"
                               data-prompt-target="userType_prompt"
                               name="<?php echo Html::getInputName($model, 'type') ?>">
                        <span id="userType_prompt" class="errorTxt"></span>
                    </div><?= frontend\components\CHtmlExt::validationEngineError($model, 'type', 'userType') ?>
                </div>
                <div class="row">
                    <div class="formL">
                        <label>姓名</label>
                    </div>
                    <div class="formR">
                        <input type="text" name="<?php echo Html::getInputName($model, 'trueName') ?>"
                               value="<?php echo $model->trueName; ?>"
                               id="<?php echo Html::getInputId($model, 'trueName') ?>"
                               class="text" data-prompt-position="inline" data-errormessage-value-missing="姓名不能为空！"
                               data-validation-engine="validate[required,maxSize[30]]"
                               data-prompt-target="nameError">
                        <span id="nameError"
                              class="errorTxt"></span><?php echo frontend\components\CHtmlExt::validationEngineError($model, 'trueName') ?>
                    </div>
                </div>

                <div class="row parentPhone <?= ($model->type == 0) ? '' : 'hide' ?>">
                    <div class="formL">
                        <label>家长手机号</label>
                    </div>
                    <div class="formR">
                        <input type="text" name="<?php echo Html::getInputName($model, 'mobile') ?>"
                               value="<?php echo $model->mobile; ?>"
                               id="<?php echo Html::getInputId($model, 'mobile') ?>"
                               class="text" data-prompt-position="inline" data-errormessage-value-missing="家长手机号不能为空！"
                               data-validation-engine="validate[required,custom[phoneNumber]"
                               data-prompt-target="parentphoneError">
                        <span id="parentphoneError"
                              class="errorTxt"></span><?php echo frontend\components\CHtmlExt::validationEngineError($model, 'mobile') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label></label>
                    </div>
                    <div class="formR submitBtnBar">
                        <button type="submit" class="btn40 bg_green submitBtn">立即注册</button>
                        <p class="gray_d font12" style=" padding-top:5px">点击“立即注册”，即表示您同意并愿意遵守班海 <a class="gray_hd"
                                                                                                    href="#">服务协议</a>
                        </p>
                    </div>
                </div>
            </div>
            <?php \yii\widgets\ActiveForm::end(); ?>
        </div>
    </div>
</div>
<!--主体end-->