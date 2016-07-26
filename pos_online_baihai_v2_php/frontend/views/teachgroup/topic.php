<?php
/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 2015/7/20
 * Time: 11:41
 */
use frontend\components\CHtmlExt;
use frontend\models\dicmodels\GradeModel;
use frontend\widgets\xupload\models\XUploadForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

$this->title='教研课题';
?>
<style>
    .i_red{color:red;}

</style>
<script type="text/javascript">
    //初始化弹窗
    $(function() {
        $('.popBox').dialog({
            autoOpen: false,
            width: 720,
            modal: true,
            resizable: false,
            close: function() { $(this).dialog("close") }
        });

        /*添加课题弹窗*/
        $('#BtnTopic').click(function() {
            $("#popBox1").dialog("open");
            return false;
        });

        //添加上传文件删除操作
        $("#fileList .close_btn").live('click',function(){
            $(this).parent().remove();
            $('#file').remove();
        });

        //修改上传文件删除操作
        $("#fileList2 .close_btn").live('click',function(){
            $(this).parent().remove();
            $('#file').remove();
        });

        //通过年级搜索
        $('#ChangeGradeID').change(function(){

            var gradeId = $(this).val();
            $.get('',{gradeId:gradeId},function(data){
                $("#topicPage").html(data);
            });

        });

    });
</script>
<script>
    $(function(){
        $('.members').each(function(index, element) {
            var _this=$(this);
            var img_size=_this.children('img').size();
            if(img_size>10)	_this.children('.moreUserBtn').show();
            _this.children('img:nth-child(n+12)').addClass('hide');
        });
        $('.moreUserBtn').toggle(
            function(){
                $(this).text('收起').siblings('.hide').show();
            },
            function(){
                $(this).text('更多').siblings('.hide').hide();
            }
        )
    });

</script>


<div class="main_cont">
    <div class="title">
        <h4>教研课题</h4>
        <div class="title_r">
            <?php echo CHtmlExt::dropDownListAjax('gradeId', '', GradeModel::model()->getListData(), array(
                'prompt' => '全部年级',
                'defaultValue' => true,
                'id' => 'ChangeGradeID',
            ))?>
            <button type="button" id="BtnTopic" class="btn btn40 bg_green">添加课题</button>
        </div>
    </div>
    <div id="topicPage">
        <?php echo $this->render('_topic_list',array('groupId'=>$groupId,'pages'=>$pages,'course'=>$course));?>
    </div>

</div>

<!--添加课题弹出层-->
<div id="popBox1" class="popBox popBox_hand popBox_topic hide" title="添加课题">
    <form id="form_id" action="">
        <div class="popCont">
            <div class="new_tch_group">
                <form>
                    <div class="form_list">
                        <div class="row clearfix">
                            <div class="formL">
                                <label><i class="i_red">*</i>课题名称：</label>
                            </div>
                            <div class="formR">
                                <input id="notice_name" name="<?= Html::getInputName($seGroupCourseModel,'courseName') ?>" type="text" class="text"
                                       data-prompt-target="nameError"
                                       data-prompt-position="inline"
                                       data-validation-engine="validate[required,maxSize[30]]">
                                <span class="altTxt">(30字以内)</span>
                                <span id="nameError" class="errorTxt"  style="left:380px"></span>
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
                                        'id' => 'gradeID',
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
                                    <input type="checkbox"    value="<?=$val->teacherID; ?>" />
                                    <label class="stuZ" for="ch3"><?= \frontend\components\WebDataCache::getTrueName($val->teacherID)?></label>
                                <?php }?>
                                <input type="hidden" name="teacherID" value="" id="teacherID"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="formL">
                                <label><i class="i_red">*</i>课题要求:</label>
                            </div>
                            <div class="formR topic_fr"> <a class="bg_green btn50 w180 iconBtn a_button" style="position:relative; overflow: hidden">
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
                                            'id' => 'fileupload',
                                        )
                                    ]);
                                    ?>
                                    上传文件</a>
                                <div class="prompt_cn">文字格式限定为doc,docx或pdf&nbsp;,&nbsp;大小不超过2M</div>
                                <div class="file-list" id="fileList">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="formL">
                                <label><i class="i_red">*</i>课题描述：</label>
                            </div>
                            <textarea class="add_txt" name="<?= Html::getInputName($seGroupCourseModel,'brief') ?>" id="topicDes"></textarea>
                            <input type="hidden" value="<?= app()->request->getQueryParam('groupId');?>" id="groupId_add" name="groupId"/>
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
</div>
<!--添加课题弹出层end-->

<!--修改课题弹出层-->
<div id="popBox2" class="popBox popBox_hand popBox_topic hide" title="修改课题">

</div>
<!--添加课题弹出层end-->

<script type="text/javascript">

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
            $('#file').remove();
            $('<input type="hidden" id="file" name="file[]" value="' + url + '" />').insertBefore( $(e.target).parent());
            $("#fileList .add_pto").remove();
            $("#fileList").append('<div class="add_pto clearfix"><span class="close close_btn">x</span><i class="ico-doc"></i><h6 class="cth">'+ name +'</h6></div>');
        });
    };


    //点击确定操作
    $('#popBox1 .okBtn').click(function() {

        var topic_des = $('#topicDes').val();
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
            $('#teacherID').val(value);
        }

        var file = $('#file').val();
        if(file == '' || typeof($('#file').val()) == "undefined"){
            popBox.errorBox('请上传文件');
            return false;
        }
        if ($('#form_id').validationEngine('validate')) {

            $form_id = $('#form_id');
            $.post('<?= Url::to(['/teachgroup/add-topic'])?>', $form_id.serialize(),function(data){
                if(data.success){
                    popBox.successBox(data.message);
                    $("#popBox1").dialog("close");
                    location.reload();
                    return false;
                }else{
                    popBox.errorBox(data.message);
                }
            });
        }


    });
</script>