<?php
/**
 * Created by PhpStorm.
 * User: unizk
 * Date: 14-11-15
 * Time: 下午4:55
 */

use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\KnowledgePointModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title="题目组详情";
$this->registerJsFile(publicResources_new() . '/js/My97DatePicker/WdatePicker.js', [ 'position'=>\yii\web\View::POS_HEAD] );

$backend_asset = publicResources_new();
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);


?>
<script>
    $(function () {
        $('#titlePush_Box').dialog({
            autoOpen: false,
            width: 700,
            modal: true,
            resizable: false,
            buttons: [
                {
                    text: "确定",
                    click: function () {
                        var classID = $('#class').val();
                        var studentNum = $("#student-num").val();
                        if (classID == "") {
                            popBox.alertBox('班级不能为空！');
                            return false;
                        } else if(studentNum == ''){
                            popBox.alertBox('请选择学生！');
                            return false;
                        }
                        if ($('#form_id').validationEngine('validate')) {
                            var option = $("#student-num").val();
                            if (option == 'single') {
                                var num_stu = $(".stu_sel_list.clearfix li").text();
                                if (num_stu == '') {
                                    popBox.alertBox('请选择指定人！');
                                    return false;
                                } else {
                                    $form_id = $('#form_id');
                                    $.post("<?php echo url('teacher/managepaper/title-push')?>", $form_id.serialize(), function (data) {
                                        if (data.success) {
                                            window.location.href = "<?php  echo \yii\helpers\Url::to(["topic-push"])?>";
                                        }else{
                                            popBox.alertBox(data.message);
                                        }
                                    })
                                }
                            }

                            if (option == 'all') {

                                $form_id = $('#form_id');
                                $.post("<?php echo url('teacher/managepaper/title-push')?>", $form_id.serialize(), function (data) {
                                    if (data.success) {
                                        window.location.href = "<?php  echo \yii\helpers\Url::to(["topic-push"])?>";
                                    } else {
                                        popBox.alertBox(data.message);
                                    }
                                })
                            }
                        }
                        $(this).dialog("close");
                    }
                },
                {
                    text: "取消",
                    click: function () {
                        $(this).dialog("close");
                    }
                }

            ]
        });

        $('.pushBtnJs').click(function () {
            var questionTeamID = $(this).val();
            $('#questionTeamID').val(questionTeamID);
            $("#titlePush_Box").dialog("open");
            var studentNum = $("#student-num").val();
            if(studentNum == 'single') {
                $('.selectForJs').show();
            }else{
                $('.selectForJs').hide();
            }
            return false;
        });

        //添加指定人到列表
        //$('.stu_sel_list').height('0');
        function add_student() {
            var _selLi = $('#multi_resultList .ac');
            var html = '';
            for(var i=0, _len=_selLi.length; i<_len; i++){
                var _curEl = _selLi.eq(i);
                html += '<li data_user="' + _curEl.attr('data_user') + '" class="multiLi" ><p>'+ _selLi.eq(i).html()  +'</p><span class="delBtn"><input type="hidden" name="receiver[]" value="' +_curEl.attr('data_user') + '"></span></li>';
            }
            $('#choose_stu_list').html(html);
        }

        //删除已选学生
        $('.stu_sel_list li i').live('click', function () {
            $(this).parent().remove();
        });

        $('#stuListBox').dialog({
            autoOpen: false,
            width: 500,
            modal: true,
            resizable: false,
            buttons: [
                {
                    text: "确定",
                    click: function () {
                        add_student();
                        $(this).dialog("close");
                    }
                },
                {
                    text: "取消",
                    click: function () {
                        $(this).dialog("close");
                    }
                }

            ]
        });

        //指定人按钮的显示/隐藏切换
        $("#student-num").change(function () {
            var stunum = $(this).val();
            if (stunum == 'single') {
                $('.selectForJs').show();
                // $('.stu_more').show();
                // $('.stu_more').text('展开全部');
            } else {
                $('.stu_sel_list.clearfix').children().remove();
                if ($("#student-num").val() != '部分学生') {
                    $('.stu_more').hide();
                }
                $('.selectForJs').hide();
            }
        });


        //点击指定人按钮查询学生列表
        $('.selectForJs').click(function () {
            var classID = $('#class').val();
            var studentNum = $("#student-num").val();
            if (classID == "") {
                popBox.alertBox('班级不能为空！');
                return false;
            } else if(studentNum == ''){
                popBox.alertBox('请选择学生！');
                return false;
            } else{

                $.post('<?php echo url('teacher/managepaper/get-class');?>', {classId: classID}, function (data) {
                    $('#stuListBox').html(data);
                    var selList=$('#choose_stu_list li');
                    var _len = selList.length;
                    if(_len>0){
                        $('#stuListBox li').removeClass('ac');
                        for(var i=0; i<_len; i++){
                            var _userId = selList.eq(i).attr('data_user');
                            $( '#stuListBox li[data_user="' + _userId + '"]').addClass('ac');
                        }
                    }
                    $("#stuListBox").dialog("open");

                    return false;
                });
            }
        });

        //更多
        $('.more').toggle(function(){
            $(this).siblings('.content').css('height','auto');
            $(this).text('收起')
        },function(){
            $(this).siblings('.content').css('height','24px');
            $(this).text('更多')
        });

        //更多
        $('.txtOverCont').openOverCont({width:780});

    });


</script>

<div class="grid_19 main_r">
    <div class="notice main_cont test titlePush">
    <div class="title">
        <a href="<?=url('/teacher/managepaper/topic-push'); ?>" class="txtBtn backBtn"></a>
        <h4>练一练详情</h4>
        <div class="fr">
            <span>组织时间:<?php echo $model->createTime;?></span>
            <?php if(isset($model->isNoted) && $model->isNoted == 1){?>
            <a href="<?= url('/teacher/managepaper/topic-push-result',array('questionTeamID'=>app()->request->getParam('questionTeamID')));?>" class="a_button w120 bg_blue btn40">查看统计结果</a>
            <?php }?>
        </div>
    </div>
    <hr>
    <div class="form_list no_padding_form_list type">
	    <h4 style="text-align: center; font-size: 18px;"><?=Html::encode($model->questionTeamName);?></h4>
        <div class="row ">
            <div class="formL">
                <label>条件：</label>
            </div>
            <div class="formR schTxtArea">
                <p><span><?php echo AreaHelper::getAreaName($model->provience);?></span><span><?php echo AreaHelper::getAreaName($model->city);?></span><span><?php echo $model->gradename;?></span><span><?php echo $model->subjectname;?></span></p>
            </div>
        </div>
        <div class="row ">
            <div class="formL">
                <label>考察知识点：</label>
            </div>
            <div class="formR schTxtArea" style="width: auto">
                <p><?php
                    if(isset($model->connetID)){
                        foreach(KnowledgePointModel::findKnowledge($model->connetID) as $key=>$item){
                            echo '<span>'.$item->name.'</span>';
                        }  } ?></p>
            </div>
        </div>
        <div class="row ">
            <?php if(isset($model->labelName) && !empty($model->labelName)){?>
            <div class="formL">
                <label>自定义标签：</label>
            </div>
            <?php }?>
            <div class="formR schTxtArea" style="width: auto">
                <p><?php
                    if(isset($model->labelName) && !empty($model->labelName)){
                        $arr = explode("，",$model->labelName);
                        foreach($arr as $val){
                            echo '<span>'.Html::encode($val).'</span>';
                        }
                    }

                    ?></p>
            </div>
       </div>

    </div>
    <br>
    <h4>共有题目<?php echo $model->countSize;?>道题如下:</h4>
    <div id="detailPaperList">

        <?php echo $this->render('_detailpaper_list',array('model'=>$model,'pages'=>$pages));?>
    </div>
    <p class="tc bottomBtnBar">
        <?php if(isset($model->countSize) && $model->countSize > 0){?>
        <button type="button" class="bg_green pushBtnJs" value="<?php echo $model->questionTeamID;?>">推送本组</button>
        <?php }?>
    </p>
</div>
</div>
<!--主体内容结束-->

<!--题目推送--------------------->
<div id="titlePush_Box" class="popBox hide titlePush_Box" title="题目推送">
    <form id="form_id">
        <ul class="form_list">
            <li class="row">
                <input type="hidden" id="questionTeamID" name="TopicPushForm[questionTeamID]" value="">
                <div class="formL">
                    <label><i></i>接收人：</label>
                </div>
                <div class="formR Push_R fl">
                    <?php echo  Html::dropDownList('class',isset($pages->params['classId'])?$pages->params['classId']:'', ArrayHelper::map(loginUser()->getClassInfo(),'classID','className')  ,
                        ['class'=>"select_tab","prompt" => "请选择",'id'=>'class']); ?>
                    <?php
                    echo Html::dropDownList('student-num',
                        '',array('single'=>'部分学生','all'=>'全部学生'),["prompt" => "请选择","class"=>"contact_select",'id'=>'student-num']);
                    ?>
                    <button type="button" class="selectForJs  bg_green btn40 w100">选择学生</button>
                    <ul class="stu_sel_list clearfix" id="choose_stu_list">

                    </ul>
                    <!--                    <a href="#" class="stu_more">展开全部</a>-->
                    <p class="checkbox_p">
                        <input type="hidden" class="checkbox" name="TopicPushForm[isMessage]" value="0">
                        <!--<label>短信通知家长</label>-->
                    </p>
                    <!--<textarea name="TopicPushForm[message]"></textarea>-->
                    <input type="hidden" name="TopicPushForm[message]" value=""/>
                </div>
            </li>
        </ul>
        <form>
</div>

<!--学生名单-->
<div id="stuListBox" class=" popBox stuListBox hide" title="学生名单">

</div>

