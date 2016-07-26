<?php
/**
 * Created by Unizk.
 * User: ysd
 * Date: 14-10-30
 * Time: 下午3:55
 */
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title="课程管理-家长会管理";
$backend_asset = publicResources();
$publicResources = Yii::$app->request->baseUrl;
;
$this->registerJsFile($publicResources . '/js/My97DatePicker/WdatePicker.js');
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/register.js');
?>
<script>
    $(function(){
        //开设家长会
        $('#open_parentMeeting').dialog({
            autoOpen: false,
            width:650,
            modal: true,
            resizable:false,
            buttons: [
                {
                    text: "确定",
                    click: function() {
                        if ($('#createmeeting').validationEngine('validate')) {

                            //判断起始日期与结束日期大小
                                var endtime = $('#add_end_time').val();
                                var starttime = $('#add_start_time').val();
                                var start = new Date(starttime.replace("-", "/").replace("-", "/"));
                                var end = new Date(endtime.replace("-", "/").replace("-", "/"));
                                if (end < start) {
                                    alert('结束日期不能小于开始日期！');
                                    return false;
                                }
                                else {
                                    //创建家长会
                                    $createmeeting = $('#createmeeting');
                                    $.post($createmeeting.attr('action'), $createmeeting.serialize(),
                                        function(data){
                                            if(data.success){
                                                location.reload();
                                            }else{
                                                popBox.alertBox(data.message);
                                            }
                                        });
                                    $( this ).dialog( "close" );
                                }

                        }
                    }
                },
                {
                    text: "取消",
                    click: function() {
                        $( this ).dialog( "close" );
                    }
                }
            ]
        });
        $('.parentMeeting .openMeetingBtnJs').click(function(){
            var classname = $('#class option:selected').text();
            if(classname !== "请选择"){
                $( "#open_parentMeeting" ).dialog( "open" );
                $('.meeting_class.open').text(classname);
                var classid = $('.select_tab option:selected').val();
                $('#hidden').val(classid);
            }else{
                popBox.alertBox('请选择班级！');
                return false;
            }
        });
        //修改家长会
        $('#edit_parentMeeting').dialog({
            autoOpen: false,
            width:650,
            modal: true,
            resizable:false,
            buttons: [
                {
                    text: "确定",
                    click: function() {
                        if ($('#modifymeeting').validationEngine('validate')) {

                            //判断起始日期与结束日期大小
                            var endtime = $('#modify_end_time').val();
                            var starttime = $('#modify_start_time').val();
                            var start = new Date(starttime.replace("-", "/").replace("-", "/"));
                            var end = new Date(endtime.replace("-", "/").replace("-", "/"));
                            if (end < start) {
                                alert('结束日期不能小于开始日期！');
                                return false;
                            }
                            else {
                                //修改家长会
                                $modifymeeting = $('#modifymeeting');
                                $.post($modifymeeting.attr('action'), $modifymeeting.serialize(),
                                    function(data){
                                        if(data.success){
                                            location.reload();
                                        }else{
                                            popBox.alertBox(data.message);
                                        }
                                    });
                                $( this ).dialog( "close" );
                            }


                        }
                    }
                },
                {
                    text: "取消",
                    click: function() {
                        $( this ).dialog( "close" );
                    }
                }
            ]
        });
        $('.parentMeeting_main .editMeetingBtnJs').click(function(){
            $( "#edit_parentMeeting" ).dialog( "open" );
            var meeid = $(this).attr('val');
            var url = "<?php echo  url('/teacher/coursemanage/searchInt-par-meeting-by-i-d')?>";
            $.post(url, {meeid: meeid}, function (result) {
                $("#classid").val(result.data.data.classID);
                $("#meeid").val(result.data.data.meeID);
                $(".text.name.edit").val(result.data.data.meetingName);
                $(".time_text.edit1").val(result.data.data.beginTime);
                $(".time_text.edit2").val(result.data.data.finishTime);
                $(".content.edit").val(result.data.data.meetingDetail);
                $('.meeting_class.edit').text(result.data.data.className);
            })
        });
        //班级变化，列表自动刷新
        $('.select_tab').change(function(){
            var classid = $('#class').val();
            var url='<?php echo url("teacher/coursemanage/parentmeeting") ?>'+'?classId='+classid;
            location.href=url;
        });



    });
</script>
<!--主体内容开始-->
<div class="currentRight grid_16 push_2 hear">
    <div class="notice parentMeeting">
        <div class="noticeH clearfix noticeB">
            <h3 class="h3L">家长会管理</h3>
            <div class="fr">
                <em>我的班级：</em>
                <?php echo  Html::dropDownList('class',isset($pages->params['classId'])?$pages->params['classId']:'', ArrayHelper::map(loginUser()->getClassInfo(),'classID','className')  ,
                    ['class'=>"select_tab","prompt" => "请选择"]); ?>
                <button type="button" class="B_btn120 new_examination openMeetingBtnJs">开设家长会</button> </div>
        </div>
        <hr>
        <div class="parentMeeting_main">
            <ul class="parentMeeting_List">
                <?php  foreach($modelList as $val){?>
                    <li class="pr">
                        <h4>会议名字：<?php echo $val->meetingName;?>
                            <?php
                            $time = time();
                            $meeid = $val->meeID;
                            $defTime = (strtotime($val->beginTime)-$time)/60;
                            if($time < strtotime($val->beginTime)){
                                echo '<em>（ <i>未开始</i> ）</em>';
                                if($defTime > 10){
                                    echo '<i class="edit_icon editMeetingBtnJs" val="'.$meeid.'"></i>';
                                }
                            }elseif($time > strtotime($val->finishTime)){
                                echo '<em>（ <i>已完结</i> ）</em>';
                            }else{
                                echo '<em>（ <i>正在进行中</i> ）</em>';
                            }
                            ?></h4>
                        <p>会议议题：<?php echo $val->meetingDetail;?></p>
                        <p>会议时间：<i><?php echo $val->beginTime;?></i>  至  <i><?php echo $val->finishTime;?></i></p>
                        <?php
                        $time = time();
                        if($time < strtotime($val->beginTime)){
                        }elseif($time > strtotime($val->finishTime)){
                            $url = url('teacher/coursemanage/courseback',array('meetingid'=>$val->meeID));
                            echo '<a href="'.$url.'" class="a_button bg_blue_l">回放</a>';
                        }else{
                            echo '<a href="'.url('/video/video').'" class="a_button bg_blue_l">去开会</a>';
                        }
                        ?>
                    </li>
                <?php }?>
            </ul>
        </div>
            <?php
             echo \frontend\components\CLinkPagerExt::widget( array(
                   'pagination'=>$pages,
                    //                'updateId' => '#teaching',
                    'maxButtonCount' => 3
                )
            );
            ?>
    </div>

</div>
<!--主体内容结束-->
<!--开设家长会--------------------->
<div id="open_parentMeeting" class=" popBox hide open_parentMeeting" title="开设家长会">
    <?php
    /** @var $form CActiveForm */
    echo  Html::beginForm('/teacher/Coursemanage/Createparentmeeting','post',['id'=>'createmeeting']);
    ?>
    <ul class="form_list add">
        <li>
            <div class="formL">
                <label>班级：</label>
            </div>
            <div class="formR">
                <em class="meeting_class open"></em>
                <input type="hidden" id="hidden" name="<?php echo Html::getInputName($model, 'classid')?>">
            </div>
        </li>
        <li>
            <div class="formL">
                <label><i></i>家长会名称：</label>
            </div>
            <div class="formR">

                <input type="text" data-validation-engine="validate[required,maxSize[30]]" data-errormessage-value-missing="家长会名称不能为空" class="text name add"  name="<?php echo Html::getInputName($model, 'meetingname') ?>">
            </div>
        </li>
        <li>
            <div class="formL">
                <label><i></i>会议时间：</label>
            </div>
            <div class="formR">
                <input type="text" style="font-size:12px;width: 145px" id="add_start_time" class="time_text add1" data-validation-engine="validate[required]" data-errormessage-value-missing="开始时间不能为空" name="<?php echo Html::getInputName($model, 'time1') ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd H:m:s',minDate:'<?php echo date('Y-m-d h:i:s',time())?>'});">
                <em class="link_text">至</em>
                <input type="text" style="font-size:12px; width: 145px" id="add_end_time" class="time_text add2" data-validation-engine="validate[required]" data-errormessage-value-missing="结束时间不能为空" name="<?php echo Html::getInputName($model, 'time2') ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd H:m:s',minDate:'<?php echo date('Y-m-d h:i:s',time())?>'});">
            </div>
        </li>
        <li>
            <div class="formL">
                <label><i></i>会议议题：</label>
            </div>
            <div class="formR">
                <textarea class="content add" data-validation-engine="validate[required]" data-errormessage-value-missing="会议议题不能为空" name="<?php echo Html::getInputName($model, 'content') ?>"></textarea>
            </div>
        </li>
    </ul>
    <?php echo  Html::endForm();?>
</div>
<!--编辑家长会--------------------->
<div id="edit_parentMeeting" class=" popBox hide edit_parentMeeting" title="编辑家长会">
    <?php
    /** @var $form CActiveForm */
    echo  Html::beginForm('/teacher/Coursemanage/ModifyIntParMeeting','post',['id'=>'modifymeeting']);
    ?>
        <ul class="form_list">
            <li>
                <div class="formL">
                    <label>班级：</label>
                </div>
                <div class="formR">
                    <em class="meeting_class edit"></em>
                    <input type="hidden" id="classid"  name="<?php echo Html::getInputName($models, 'classid')?>">
                    <input type="hidden" id="meeid"  name="<?php echo Html::getInputName($models, 'meeid')?>">
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>家长会名称：</label>
                </div>
                <div class="formR">
                    <input type="text" class="text name edit" data-validation-engine="validate[required,maxSize[30]]" data-errormessage-value-missing="家长会名称不能为空" id="nameedit" name="<?php echo Html::getInputName($models, 'meetingname') ?>">
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>会议时间：</label>
                </div>
                <div class="formR">
                    <input type="text" style="font-size:12px; width: 145px" id="modify_start_time" class="time_text edit1" data-validation-engine="validate[required]" data-errormessage-value-missing="开始时间不能为空" name="<?php echo Html::getInputName($models, 'time1') ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd H:m:s',minDate:'<?php echo date('Y-m-d h:i:s',time())?>'});">
                    <em class="link_text">至</em>
                    <input type="text" style="font-size:12px; width: 145px" class="time_text edit2" id="modify_end_time" data-validation-engine="validate[required]" data-errormessage-value-missing="结束时间不能为空" name="<?php echo Html::getInputName($models, 'time2') ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd H:m:s',minDate:'<?php echo date('Y-m-d h:i:s',time())?>'});">
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>会议议题：</label>
                </div>
                <div class="formR">
                    <textarea class="content edit"  data-validation-engine="validate[required]" data-errormessage-value-missing="会议议题不能为空" name="<?php echo Html::getInputName($models, 'content') ?>"></textarea>
                </div>
            </li>
        </ul>
    <?php echo  Html::endForm();?>
</div>






