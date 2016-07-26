<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/20
 * Time: 15:35
 */
use yii\helpers\Html;

$this->title='教研课题——写报告';
$this->registerJsFile("@web/ueditor/ueditor.config.little.js");
$this->registerJsFile("@web/ueditor/ueditor.all.min.js");
?>

<div class="main_cont  listen_comment">
    <div class="title">
        <h4>
            写报告</h4>
    </div>
    <?php /* @var  $this CActiveForm */
    $form =\yii\widgets\ActiveForm::begin( array(
        //'enableClientScript' => false,
        'id' => 'form_id'
    )) ?>
    <div class="date_sc">
        <dl class="report_cont">
            <dt>
                <span>* </span>
                &nbsp;名称</dt>
            <dd>
                <input type="text" name="<?= Html::getInputName($courseReport,'reportTitle') ?>" value="<?= Html::encode($courseReport->reportTitle) ?>" class="input_txt" data-errormessage-value-missing="名称不能为空！" data-prompt-position="inline" data-prompt-target="contentError"  data-validation-engine="validate[required,maxSize[50]]"/>
                <span id="contentError" class="errorTxt" style="border: none;margin-left: 323px"></span>
            </dd>
        </dl>
        <dl class="report_cont">
            <dt>
                <span>* </span>
                &nbsp;内容</dt>
            <dd>
                <textarea id="report_content" name="<?= Html::getInputName($courseReport,'reportContent') ?>" style="height: 338px"><?= Html::encode($courseReport->reportContent) ?></textarea>
            </dd>
        </dl>
        <dl class="report_cont">
            <dt>&nbsp;
            </dt>

            <dd>
                <input type="hidden" name="courseId" value="<?= $courseId; ?>"/>
                <button type="button" id="sub" class="btn btn40 bg_blue w140">
                    确定</button>
                <!--<button type="button" class="btn btn40 bg_blue_l w140 cancel_btn">
                    取消</button>
                    -->
            </dd>
        </dl>
    </div>
    <?php \yii\widgets\ActiveForm::end(); ?>
</div>
<script>
    $(function() {
        //ueEditor配置
        UE.getEditor("report_content");
    });
    //验证内容是否为空
    $("#sub").click(function(){
        var length = $("#report_content").val().length;
        if($("#report_content").val() == '') {
            popBox.errorBox('内容不能为空！');
            return false;
        }else if(length>5000){
            popBox.errorBox("超过最大字数5000！");
            return false;
        }
        $("#form_id").submit();

    })
</script>