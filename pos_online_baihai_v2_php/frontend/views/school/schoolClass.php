<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/4/28
 * Time: 11:43
 */
use frontend\models\dicmodels\SchoolGradeModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title="班级列表页";

?>
<script>
    $(function(){

        //搜索
        $('#search_button').click(function () {
            $searchForm = $('#form1');
            $.get($searchForm.attr('action'), $searchForm.serialize(), function (html) {
                $('#srchResult').html(html);
            });
        });

    })
</script>

<!--主体-->
<div class="main_cont">
    <div class="title">
        <h4>校内班级</h4>
    </div>
    <div class="scholl_class_cont">
        <?php $form =\yii\widgets\ActiveForm::begin( array(
            'enableClientScript' => false,
            'id' => 'form1'
        )) ?>
        <div class="scholl_class_contTop">
            <div class="class_contList clearfix">
                <!--<label>学段：</label>
                --><?php
/*                echo Html::dropDownList('schoolLevel',
                    '',
                    SchoolLevelModel::model()->getListData(),
                    array('id' => 'schoolLevel', 'prompt' => '请选择'));
                */?>
                <!--<label>年级：</label>
                --><?php
/*                echo Html::dropDownList('gradeID',
                    '',
                    ArrayHelper::map(GradeModel::model()->getList(), 'gradeId', 'gradeName'),
                    array(
                        'id' => 'gradeID',
                        "prompt" => "请选择",
                    ));
                */?>

                <label>年级：</label>
                <?php
                echo Html::dropDownList('gradeID',
                    '',
                    ArrayHelper::map(SchoolGradeModel::model(loginUser()->getModel(false)->schoolID)->getList(), 'gradeId', 'gradeName'),
                    array(
                        'id' => 'gradeID',
                        "prompt" => "请选择",
                    ));
                ?>

                <input type="text" class="text" id="sclName" name="classname" style="width:300px" value="" placeholder="请输入班级名称搜索...">
                <button type="button" class="big_searchBtn" id="search_button">搜索</button>

            </div>
        </div>
        <?php \yii\widgets\ActiveForm::end() ?>
        <div id="srchResult">
            <?php echo $this->render('_schoolClass_all', array('model' => $model, 'schoolId' => $schoolId, 'pages' => $pages,)); ?>
        </div>

    </div>
</div>

<!--主体end-->

<!--弹框开始（处理申请）-->
<div id="receiveForJs" class="popBox popBox_hand hide" title="手拉手班级">
    <!--完成答题-->
    <div class="impBox">
        <h6 id="receive_area" id="receive_area">北京市 海淀区 人大附中</h6>
        <a href="javascript:;" class="a_button w180 btn40" id="receive_class">本年度最佳荣誉班级</a>
        <p class="font14">申请成为本班的手拉手班级，是否通过申请？</p>
        <div class="radio">
            <input type="radio"  class="hide" id="raido1" name="response_code" value="1" >
            <label for="raido1" class="radioLabel ">通过审核</label>
            <input type="radio"  class="hide" id="raido2" name="response_code" value="0" checked="checked">
            <label for="raido2" class="radioLabel radioLabel_ac">驳回审核</label>
        </div>
        <div class="textareaBox">
            <textarea class="textarea response_reason"></textarea>
        </div>
    </div>
</div>


<!--手拉手班级申请-->
<div id="applyForJs" class="popBox popBox_hand hide" title="手拉手班级申请">
    <!--完成答题-->
    <div class="impBox">
        <h6 class="font16" >申请成为 <span id="apply_area">北京市 海淀区&nbsp;&nbsp;&nbsp;&nbsp;人大附中</span></h6>
        <p style="text-align:center" class="font16" id="apply_class">班级名称</p>
        <div class="font16" style="color:#777;">的手拉手班级吗？</div>
    </div>
</div>