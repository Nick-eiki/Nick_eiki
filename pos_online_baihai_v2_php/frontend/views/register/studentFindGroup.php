<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-7
 * Time: 下午5:59
 */
use frontend\components\CHtmlExt;
use frontend\models\dicmodels\ClassDutyModel;
use frontend\models\dicmodels\SchoolLevelModel;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */  $this->title='学生完善信息';
$backend_asset = publicResources_new();
$this->registerJsFile($backend_asset . '/js/selSchool.js'.RESOURCES_VER,['position'=>View::POS_END]);
?>

<script>
    $(function () {
        $department = $('#<?= Html::getInputId($model, "department") ?>');
        $schoolid = $('#<?= Html::getInputId($model, 'schoolId') ?>');
        $classesId = $('#<?= Html::getInputId($model, 'classId') ?>');

        $schoolName = $('#selectSchoolText');
        $schoolid.change(function () {
            $('.clearinput').val('');
        });
        $department.change(function () {
            $schoolid.val('').change();
            $schoolName.val('');
        });

        /*学校列表 地区列表 班级列表 教研组列表鼠标hover--------------*/
        $('.resultList li,.mySchoolBox .selectAreaBox dl dd,.gradeList dd').live('click', function () {
            $(this).addClass('ac').siblings().removeClass('ac');
        });


        /*弹窗*/
        $('.popBox').dialog({
            autoOpen: false,
            width: 670,
            modal: true,
            resizable: false,
            close: function () {
                $(this).dialog("close")
            }
        });


        /*学校pop-----------------------------------------*/
        $("#selectSchoolText").click(function(){
            schoolDialogs(1,'#selectSchoolText','#<?= Html::getInputId($model, 'schoolId') ?>','#<?= Html::getInputId($model, "department") ?>');
        });

        /*班级pop---------------------------------------*/
        (function () {
            var target = "";
            $('.sel_class_input').live('click', function () {


                if (!$schoolid.validationEngine('validate')) {
                    return;
                }
                target = $(this);
                $.post('<?php echo url('register/new-search-classes') ?>', {
                        schoolId: $schoolid.val(),
                        department: $department.val()
                    },
                    function ($data) {
                        $('#updateClass').html($data);
                        $('.creat_classBox').dialog('open');
                    }
                );
                $('.creat_classBox .okBtn').die('click').live('click', function () {

                    var $window = $(this).parents('.popBox');
                    if ($('.myNewClass').is(':visible')) {
                        var joinYear = $("#joinYear").val();
                        var classNumber= $("#classNumber").val();
                        if(joinYear =='' || classNumber ==''){
                            popBox.errorBox('班级编号或班不能为空');
                            return false;
                        }
                        if ($('#addClassInfo').validationEngine('validate')) {
                            $addClassInfo = $('#addClassInfo');
                            $.post($addClassInfo.attr('action'), $addClassInfo.serialize(), function (data) {
                                if (data.success) {
                                    data.data.split('|')[0];
                                    target.siblings('input:hidden').val(data.data.split('|')[0]);
                                    target.val(data.data.split('|')[1]).blur().change();
                                    $window.dialog("close");
                                } else {

                                    popBox.alertBox(data.message);
                                }
                            });
                        }

                    } else {
                        target.val($('.creat_classBox .myclassList .ac').text()).blur();
                        target.siblings('input:hidden').val($('.creat_classBox .myclassList .ac').attr('id'));
                        $window.dialog("close");
                    }
                    return false;
                });
            });
        })();


        $('.gradeList dd').live('click', function () {
            var gradeID = $(this).attr('id');
            $('.creat_classBox .crumbList').show().children('span').remove();
            $('.creat_classBox .crumbList a').before('<span class="ac">' + $(this).text() + '</span>');

            $.post("<?php echo url('register/new-search-classes-info')?>", {
                schoolId: $schoolid.val(),
                department: $department.val(), gradeID: gradeID
            }, function (data) {
                $('.gradeList').hide();
                $('.creat_classBox h5').text('班级列表');
                $("#newSearchClassesInfo").html(data);
                $('.creat_classBox .myclassList').show();
            })
        });

        $('.creat_classBox .back_selectArea').live('click', function () {
            $('.creat_classBox .crumbList').hide().children('span').remove();
            $('.gradeList').show();
            $('.creat_classBox .myclassList').hide();
        });

        $('.creatNewClass').live('click', function () {
            $(this).hide();
            $('.gradeList,.crumbListWrap,.myclassList').hide();
            $('.myNewClass').show();
            $('.creat_classBox h5').text('创建新班级')
        });

        /*学号*/
        $('#stu_num_input').live('click', function () {
            var _this = $(this);
            $.post('<?php echo url('register/search-numbers') ?>', {classId: $classesId.val()}, function (data) {
                if (data.success) {
                    $('#updateMember').html(data.data);
                    $('.stu_numberBox').dialog('open');
                }
                return false;
            });

            $('.stu_numberBox .okBtn').live('click', function () {
                _this.val($('.stu_numberBox .stu_numberList .ac').text());
                _this.siblings('input:hidden').val($('.stu_numberBox .stu_numberList .ac').attr('data_classmem'));
                $(this).parents('.popBox').dialog("close");
            })
        });


        /*关闭弹钮-----------------------------------------*/
        $('.popBox .cancelBtn').live('click',function () {
            $(this).parents('.popBox').dialog("close");
        });


    })

</script>

<!--主体部分-->
<div class="cont24">
    <div class="grid_19 push_3">
        <div class="formArea">
            <?php

            $form = ActiveForm::begin( array(
                'id' => 'form_id',
            )) ?>
            <div class="form_list">
                <div class="row">
                    <div class="formL">
                        <label></label>
                    </div>
                    <div class="formR">
                        <p class="bg_blue_l_gray gray_d font12 attention"><i></i> 所有项目都为必填项,请按顺序认真填写</p>
                    </div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label>学段</label>
                    </div>
                    <div class="formR">
                            <span class="selectWrap big_sel">
                            	<?php echo frontend\components\CHtmlExt::activeDropDownListCustomize($model, "department",  SchoolLevelModel::model()->getListData(),
                                    [
                                        'data-validation-engine' => 'validate[required]',
                                        "defaultValue" => true,
                                        "prompt" => "请选择",
                                        'data-prompt-target' => "department_prompt",
                                        'data-prompt-position' => "inline",
                                        'data-errormessage-value-missing' => "学段不能为空"


                                    ]
                                );
                                ?>
                                <span id="department_prompt" class="errorTxt"></span>
                            </span>

                    </div>

                </div>
                <div class="row">
                    <div class="formL">
                        <label>选择学校</label>
                    </div>
                    <div class="formR">
                        <?php echo CHtmlExt::activeHiddenInput($model, 'schoolId'); ?>
                        <?php echo CHtmlExt::activeTextInput($model, 'schoolName', ["class" => "text", "id" => 'selectSchoolText',
                            "readOnly" => 'readOnly',
                            'data-validation-engine' => "validate[required]",
                            'data-errormessage-value-missing' => "学校不能为空",
                            'data-prompt-target' => "schoolName_prompt",
                            'data-prompt-position' => "inline",
                        ]) ?>
                        <span id="schoolName_prompt" class="errorTxt"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label>选择班级</label>
                    </div>
                    <div class="formR">
                        <?php echo CHtmlExt::activeHiddenInput($model, 'classId', ['class' => 'clearinput']); ?>
                        <?php echo CHtmlExt::activeTextInput($model, 'className', ["class" => "text sel_class_input clearinput",
                            "readOnly" => 'readOnly',
                            'data-validation-engine' => "validate[required]",
                            'data-errormessage-value-missing' => "班级不能为空",
                            'data-prompt-position' => "inline",
                            'data-prompt-target' => "className_prompt"
                        ]) ?>
                        <span id="className_prompt" class="errorTxt"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label>班内职务</label>
                    </div>
                    <div class="formR">
                            <span class="selectWrap big_sel">
                            	    <?php echo CHtmlExt::dropDownListCustomize(Html::getInputName($model, 'job'), $model->job?:20107, ClassDutyModel::model()->getList(),
                                        [
                                        'data-validation-engine' => "validate[required]",
                                        'class' => 'clearinput',
                                        "defaultValue" => true,
                                        'data-errormessage-value-missing' => "班内职务不能为空",
                                        'data-prompt-position' => "inline",
                                        'data-prompt-target' => "job_prompt"
                                    ]) ?>
                                <span id="job_prompt" class="errorTxt"></span>
                            </span>

                    </div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label>学号</label>
                    </div>
                    <div class="formR">
                        <?php echo CHtmlExt::activeHiddenInput($model, 'classMemID', ['class' => 'classMeminput']); ?>
                        <?php echo CHtmlExt::activeTextInput($model, 'stuID', ["class" => "text", "id" => "stu_num_input", 'data-validation-engine' => "validate[required,maxSize[11]]", 'data-errormessage-value-missing' => "学号不能为空",
                            'data-prompt-position' => "inline",
                            'data-prompt-target' => "stuID_prompt"]); ?>
                        <span id="stuID_prompt" class="errorTxt"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label></label>
                    </div>
                    <div class="formR submitBtnBar">
                        <button type="submit" class="btn40 bg_green submitBtn">完　成</button>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<!--主体end-->

<!--弹框pop----------------------------------------------------------->
<!--选择学校-->
<div class="popBox mySchoolBox" title="我所在的学校">
    <div id="updateSchool">
    </div>
</div>

<!--班级-->
<div class="popBox creat_classBox hide" title="我所在的班级">
    <div id="updateClass">
    </div>
</div>

<!--学号-->
<div class="popBox stu_numberBox hide" title="核对学号">
    <div id="updateMember">
    </div>

</div>
<?php if (Yii::$app->getSession()->hasFlash('error')) { ?>
    <script type="text/javascript">
        $(function(){
            popBox.errorBox(' <?php  echo    Yii::$app->session->getFlash('error'); ?>');
        })

    </script>
<?php } ?>
