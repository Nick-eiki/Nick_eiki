<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/28
 * Time: 11:39
 */
use common\models\pos\SeGroupCourse;
use frontend\components\CHtmlExt;
use frontend\components\WebDataCache;
use frontend\models\dicmodels\GradeModel;
use frontend\widgets\xupload\models\XUploadForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/** @var SeGroupCourse $seGroupCourseModel */

/* @var $this yii\web\View */

?>
<script type="text/javascript">
    $(function(){
        formValidationIni('#form_id2');
    })
</script>
<style>
    .i_red{color:red;}

</style>

<form id="form_id2" action="">
    <div class="popCont">
        <div class="new_tch_group">
            <form>
                <div class="form_list">
                    <div class="row clearfix">
                        <div class="formL">
                            <label><i class="i_red">*</i>课题名称：</label>
                        </div>
                        <div class="formR">
                            <input id="notice_name2" name="<?= Html::getInputName($seGroupCourseModel,'courseName') ?>" type="text" class="text" value="<?= CHtmlExt::encode($seGroupCourseModel->courseName);?>"
                                   data-prompt-target="nameError2"
                                   data-prompt-position="inline"
                                   data-validation-engine="validate[required,maxSize[30]]">
                            <span class="altTxt">(30字以内)</span>
                            <span id="nameError2" class="errorTxt"  style="left:380px"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="formL">
                            <label><i class="i_red">*</i>年级:</label>
                        </div>
                        <div class="formR">
                                <span class="selectWrap big_sel">
                                    <?php echo CHtmlExt::activeDropDownListCustomize($seGroupCourseModel, 'gradeID', GradeModel::model()->getListData(), array(
                                        'empty' => '全部年级',
                                        'id' => 'gradeID2',
                                    ))?>
                                </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="formL">
                            <label><i class="i_red">*</i>课题成员:</label>
                        </div>
                        <div class="formR personal">
                            <?php foreach($member as $val){ ?>
                                <input type="checkbox"    value="<?=$val->teacherID; ?>" <?php if(in_array($val->teacherID ,$courseMember)){echo 'checked';} ?>/>
                                <label class="stuZ" for="ch3"><?= WebDataCache::getTrueName($val->teacherID) ?></label>
                            <?php }?>
                            <input type="hidden" name="teacherID" value="" id="teacherID2"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="formL">
                            <label><i class="i_red">*</i>课题要求:</label>
                        </div>
                        <div class="formR topic_fr">
                                <?php if(isset($seGroupCourseModel->url) && !empty($seGroupCourseModel->url)){ ?>
                                    <input type="hidden" id="file2" name="file[]" value="<?= $seGroupCourseModel->url?>">
                                <?php }?>
                            <a class="bg_green btn50 w180 iconBtn a_button" style="position:relative; overflow: hidden">
                                <?php
                                $t1 = new XUploadForm;
                                echo   \frontend\widgets\xupload\XUploadSimple::widget( [
                                    'url' => Url::to(['/upload/doc']),
                                    'model' => $t1,
                                    'attribute' =>'file',
                                    'autoUpload' => true,
                                    'multiple' => false,
                                    'options' => array(
                                        'acceptFileTypes' =>new JsExpression('/\.(doc|doc?x|pdf)$/i'),
                                        'maxFileSize'=>'2000000',
                                        "done" => new JsExpression("done"),
                                        "processfail" => new JsExpression('function (e, data) {var index = data.index,file = data.files[index]; if (file.error) {alert(file.error);}}')
                                    ),
                                    'htmlOptions' => array(
                                        'id' => 'fileupload2',
                                    )
                                ]);
                                ?>
                                上传文件</a>
                            <div class="prompt_cn">文字格式限定为doc,docx或pdf&nbsp;,&nbsp;大小不超过2M</div>
                            <div class="file-list" id="fileList2">
                                <?php if(isset($seGroupCourseModel->url) && !empty($seGroupCourseModel->url)){ ?>
                                        <div class="add_pto clearfix"><span class="close close_btn">x</span><i class="ico-doc"></i><h6 class="cth"><?= substr($seGroupCourseModel->url,-23) ?></h6></div>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="formL">
                            <label><i class="i_red">*</i>课题描述：</label>
                        </div>
                        <textarea class="add_txt" name="<?= Html::getInputName($seGroupCourseModel,'brief') ?>" id="topicDes2"><?= CHtmlExt::encode($seGroupCourseModel->brief);?></textarea>
                        <input type="hidden" value="<?= $groupId;?>" id="groupId_modify" name="groupId"/>
                        <input type="hidden" value="<?= $courseId?>" name="courseId" />
                    </div>
                </div>

            </form>
        </div>
    </div>
    <div class="popBtnArea">
        <button type="button" class="okBtn"> 确定</button>
        <button type="button" class="cancelBtn"> 取消</button>
    </div>
</form>

<script type="text/javascript">
$(function(){
    url = "";
    name = "";
    done = function (e, data) {
        $.each(data.result, function (index, file) {
            if (file.error) {
                popBox.errorBox(file.error);
                return;
            }
            url = file.url;
            name = file.name;
            popBox.successBox('上传成功');
            $('#file2').remove();
            $('<input type="hidden" id="file2" name="file[]" value="' + url + '" />').insertBefore( $(e.target).parent());
            $("#fileList2 .add_pto").remove();
            $("#fileList2").append('<div class="add_pto clearfix"><span class="close close_btn">x</span><i class="ico-doc"></i><h6 class="cth">'+ name +'</h6></div>');
        });
    };

    //点击确定操作
    $('#popBox2 .okBtn').click(function() {

        var topic_des = $('#topicDes2').val();
        if(topic_des == ""){
            popBox.errorBox('课题描述不能为空');
            return false;
        }else if(topic_des.length > 200){
            popBox.errorBox('课题描述最多200字');
            return false;
        }

        var len = $('.personal input:checkbox:checked');
        if(len.length == 0){
            popBox.errorBox('请选择课题成员');
            return false;
        }else{
            var value = [];
            len.each(function(index){
                value.push($(this).val());
            });
            $('#teacherID2').val(value);
        }

        var file = $('#file2').val();
        if(file == '' || typeof($('#file2').val()) == "undefined"){
            popBox.errorBox('请上传文件');
            return false;
        }
        if ($('#form_id2').validationEngine('validate')) {

            $form_id = $('#form_id2');
            $.post('<?= Url::to(['/teachgroup/modify-topic-one'])?>', $form_id.serialize(),function(data){
                if(data.success){
                    popBox.successBox(data.message);
                    $("#popBox2").dialog("close");
                    location.reload();
                    return false;
                }else{
                    popBox.errorBox(data.message);
                }
            });
        }


    });

})
</script>