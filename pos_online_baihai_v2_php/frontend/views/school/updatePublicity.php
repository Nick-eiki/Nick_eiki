﻿﻿<?php
/**
 * Created by PHPstorm
 * User: mahongru
 * Date: 15-7-7
 * Time: 下午18:33
 */

use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title='学校-更新公示';
?>
<script type="text/javascript">

    function back() {
        history.go(-1);
    }
    $('#form_id').validationEngine({
        'maxErrorsPerField': 1,
        'onFieldSuccess': function (field) {
            $(field).nextAll('.errorTxt').empty().addClass('validationOK')
        },
        'onFieldFailure': function (field) {
            $(field).nextAll('.errorTxt').removeClass('validationOK')
        }
    });
</script>
<div class="main_cont school_new_public">
    <div class="title">
        <h4>更新公示</h4>
        <!--<div class="title_r">
            <button type="button" class="btn40 bg_green addBtnJs">添加</button>
        </div>
        -->
    </div>
    <?php /* @var  $this CActiveForm */
    $form =\yii\widgets\ActiveForm::begin( array(
        //'enableClientScript' => false,
        'id' => 'form_id'
    )) ?>
    <div class="form_list">
        <div class="row">
            <div class="formL">
                <label><i>*</i>名称：</label>
            </div>
            <div class="formR">
                <input type="text" name="<?php echo  Html::getInputName($model, 'publicityTitle') ?>"
                       value="<?= Html::encode($model->publicityTitle) ?>" data-errormessage-value-missing="名称不能为空！"
                       data-prompt-position="inline" data-prompt-target="titleError"
                       data-validation-engine="validate[required,maxSize[30]]" class="text" style=" width:550px">
                <span id="titleError" class="errorTxt" style="border: none;margin-left: 280px"></span>
            </div>
        </div>
        <div class="row">
            <div class="formL">
                <label><i>*</i>分类：</label>
            </div>
            <div class="formR">
                    	<span class="selectWrap big_sel" style="width:120px">
                            <i></i>
                           	<em>请选择</em>
                            <?php
                            echo Html:: DropDownList( Html::getInputName($model, "publicityType"), $model->publicityType, array("1" => "校园公告", "2" => "校园生活", "3" => "校园新闻", "4" => "教育综合", "5" => "招生动态", "6" => "荣誉墙",),
                                array(
                                    'data-validation-engine' => 'validate[required]', "defaultValue" => false, "prompt" => "请选择",
                                    'data-prompt-target' => "typeError",
                                    'data-prompt-position' => "inline",
                                    'data-errormessage-value-missing' => "分类不能为空",
                                )); ?>
                            </span>
                <span id="typeError" class="errorTxt" style="border: none;margin-left: -160px"></span>
            </div>
        </div>
        <!--<div class="row">
           <div class="formL">
               <label>投票：</label>
           </div>
           <div class="formR">
               <input id="r01" type="radio" class="hide" name="vote" value="1"><label for="r01" class="radioLabel radioLabel_ac">允许</label>
               <input id="r02" type="radio" class="hide"  name="vote" value="0"><label for="r02" class="radioLabel">禁止</label>
           </div>
       </div>
       -->
        <div class="row">
            <div class="formL">
                <label><i>*</i>内容：</label>
            </div>
            <div class="formR">
                <textarea class="public_textarea"
                          name="<?php echo  Html::getInputName($model, 'publicityContent') ?>"
                          data-errormessage-value-missing="内容不能为空！" data-prompt-position="inline"
                          data-prompt-target="contentError" data-validation-engine="validate[required,maxSize[1000]]"
                          style="width:560px"><?php echo Html::encode($model->publicityContent); ?></textarea>
                <span id="contentError" class="errorTxt" style="border: none;margin-left: 280px"></span>
            </div>
        </div>
        <div class="row">
            <div class="formL">
                <label><!--<i>*</i>-->上传图片：</label>
            </div>
            <div class="formR">
                <a href="javascript:;" class="btn bg_green w120 uploadFileBtn">
                    上传图片
                    <?php
                    $t1 = new frontend\widgets\xupload\models\XUploadForm;
                    /** @var $this BaseController */
                    echo  \frontend\widgets\xupload\XUploadSimple::widget( array(
                        'url' => \Yii::$app->urlManager->createUrl("upload/pic"),
                        'model' => $t1,
                        'attribute' => 'file',
                        'autoUpload' => true,
                        'multiple' => true,
                        'options' => array(
                            'acceptFileTypes' => new \yii\web\JsExpression('/(\.|\/)(jpg|png|jpeg)$/i'),
                            "done" => new \yii\web\JsExpression('done'),
                            "processfail" => new \yii\web\JsExpression('function (e, data) {var index = data.index,file = data.files[index]; if (file.error) {alert(file.error);}}')
                        ),
                        'htmlOptions' => array(
                            'id' => 'fileupload',
                        )
                    ));
                    ?>
                </a>
                <div class="imgFile">
                    <ul class="up_test_list clearfix">
                        <?php foreach ($imageUrl as $v) : ?>
                            <?php if ($v != '') : ?>
                                <li><input type="hidden" name="Model[imgUrl][]" value="<?php echo $v; ?>"/><img
                                        src="<?php echo $v; ?>" alt=""><span class="delBtn"></span></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="formL"><label></label></div>
            <div class="formR submitBtnBar">
                <button type="submit" class=" bg_blue btn40 w120">确定</button>
            </div>
        </div>
    </div>
    <?php \yii\widgets\ActiveForm::end(); ?>
</div>
</div>
</div>
<script>
    done = function (e, data) {
        $.each(data.result, function (index, file) {
            if (file.error) {
                popBox.errorBox(file.error);
                return;
            }
            var size=$(".up_test_list").find("li").size();
            if(size>=6){
                popBox.errorBox("您最多能上传最多6张图片");
                return;
            }
            $(".up_test_list").append(' <li><input type="hidden" name="Model[imgUrl][]" value="' + file.url + '"/><img src="' + file.url + '" alt=""><span class="delBtn"></span></li>');
        });

    };
//    $("#form_id").submit(function(){
//        if($(".up_test_list").find("li").size()==0){
//            popBox.errorBox("请上传图片");
//            return false;
//        }
//    })
</script>
