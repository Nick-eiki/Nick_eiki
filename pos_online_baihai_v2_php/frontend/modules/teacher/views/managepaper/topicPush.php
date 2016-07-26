<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title="题目管理";


$backend_asset = publicResources_new();
$this->registerJsFile($backend_asset . '/js/My97DatePicker/WdatePicker.js', [ 'position'=>\yii\web\View::POS_HEAD] );
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER, [ 'position'=>\yii\web\View::POS_HEAD] );
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js', [ 'position'=>\yii\web\View::POS_HEAD] );
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER, [ 'position'=>\yii\web\View::POS_HEAD] );


?>

<!--主体内容开始-->
<div class="grid_19 main_r">
    <div class="notice main_cont test titlePush">
        <div class="title">
            <h4>题目推送</h4>
            <div class="title_r">
                <a href="<?php echo url("teacher/managepaper/add-paper") ?>" class="a_button w120 btn40 new_examination bg_green">新建题目组</a> </div>
        </div>

       <div id="questionTeam">
            <?php echo $this->render('_topicpush_list', array('model' => $model, 'pages' => $pages));?>
       </div>
    </div>
</div>



<!--主体内容结束-->

<!--题目推送--------------------->
<div id="titlePush_Box" class="popBox hide titlePush_Box" title="题目推送">
    <form id="form_id">
        <ul class="form_list">
            <li class="row">
                <input type="hidden" id="questionTeamID" name="TopicPushForm[questionTeamID]" value="">
                <div class="formL fl">
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

