<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-8
 * Time: 上午10:27
 */
use frontend\components\CHtmlExt;
use frontend\models\dicmodels\LoadSubjectModel;
use frontend\models\dicmodels\LoadTextbookVersionModel;
use frontend\models\dicmodels\SchoolLevelModel;
use frontend\models\dicmodels\TeachingResearchDutyModel;
use frontend\models\TeacherClassForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */  $this->title='老师完善信息';
$teacherClassCount = count($teacherClassList);
$backend_asset = publicResources_new();
$this->registerJsFile($backend_asset . '/js/selSchool.js'.RESOURCES_VER,['position'=>View::POS_END]);

?>
<script>
    $(function () {
        $subjectId = $('#<?= Html::getInputId($model, "subjectID") ?>');
        $department = $('#<?= Html::getInputId($model, "department") ?>');

        $schoolid = $('#<?= Html::getInputId($model, 'schoolId') ?>');
        $schoolName=$('#selectSchoolText');
        $schoolid.change(function(){
            $('.clearinput').val('');
        });
        $department.change(function(){
            $schoolid.val('').change();
            $schoolName.val('');
        });

        $('.popBox').dialog({
            autoOpen: false,
            width: 670,
            modal: true,
            resizable: false,
            close: function () {
                $(this).dialog("close")
            }
        });

        /*学校列表 地区列表 班级列表 教研组列表鼠标hover--------------*/
        $('.resultList li,.mySchoolBox .selectAreaBox dl dd,.gradeList dd').live('click', function () {
            $(this).addClass('ac').siblings().removeClass('ac');
        });

        /*学校pop-----------------------------------------*/
        $("#selectSchoolText").click(function(){
            schoolDialogs(1,'#selectSchoolText','#teacheruserform-schoolid','#teacheruserform-department');
        });



        /*教研组pop-------------------------------------------*/


        $('#sel_tch_group_input').click(function () {
            if (!$schoolName.validationEngine('validate') || !$subjectId.validationEngine('validate')  || $.trim($schoolid.val()).length==0  || $.trim($subjectId.val()).length==0   ) {
                return;
            }
            $.post('<?php echo url('register/new-search-teaching-group') ?>', {
                    schoolId: $schoolid.val(),
                    department: $department.val(),
                    subjectID: $subjectId.val()
                },
                function ($data) {
                    $('#updateGroup').html($data);
                    $('.creat_tch_groupBox').dialog('open');
                }
            )

        });

        $('.creat_tch_group').live('click', function () {
            $(this).hide();
            $('.tch_group_List').hide();
            $('.new_tch_group').show();
            $('.creat_tch_groupBox h5').text('创建教研组');
        });

        $('.creat_tch_groupBox .okBtn').live('click', function () {
            var $winGroup = $(this).parents('.popBox');
            if ($('.new_tch_group').is(':visible')) {
                if ($('#TeacherUserForm').validationEngine('validate')) {
                    $addTeachinggroupForm = $('#addGroupInfo');
                    $.post($addTeachinggroupForm.attr('action'), $addTeachinggroupForm.serialize(), function (data) {
                        if (data.success == true) {
                            $('#teachergroupform-groupid').val(data.data.split("|")[0]);
                            $('#sel_tch_group_input').val(data.data.split("|")[1]).blur();
                            $winGroup.dialog("close");
                        }
                        else {
                            popBox.alertBox(data.message);
                        }
                    });
                }
                return false;
            } else {
                $('#sel_tch_group_input').val($('.popBox .tch_group_List .ac').text()).blur();

                $('#teachergroupform-groupid').val($('.popBox .tch_group_List .ac').attr('id'));
            }


            $winGroup.dialog("close");
        });

        /*班级pop---------------------------------------*/


        (function () {
            var target = "";
            $('.sel_class_input').live('click', function () {
                //              $.trim($schoolid.val())

                if (!$schoolid.validationEngine('validate') || $.trim($schoolid.val()).length==0 ) {
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
                        return false;
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
            });

//
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

        /*关闭弹钮*/
        $('.popBox .cancelBtn').live('click', function () {
            $(this).parents('.popBox').dialog("close");
        });


    })
</script>
<script type="text/html">


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
                                        'data-errormessage-value-missing' => "学段不能为空",
                                        'ajax' => [
                                            'url' => Url::to('/ajax/get-subject'),
                                            'data' => ['schoolLevel' => new \yii\web\JsExpression('this.value')],
                                            'success' => 'function(html){jQuery("#' . Html::getInputId($model, "subjectID") . '").html(html).change();}'
                                        ]

                                    ]
                                );
                                ?>
                                <span id="department_prompt" class="errorTxt"></span>
                            </span>

                    </div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label>学科</label>
                    </div>
                    <div class="formR">
                            <span class="selectWrap big_sel">
                                <?=
                                frontend\components\CHtmlExt::activeDropDownListCustomize($model, "subjectID",  ArrayHelper::map(LoadSubjectModel::model()->getData($model->department,1), 'secondCode', 'secondCodeValue'),
                                    [
                                        'data-validation-engine' => 'validate[required]',
                                        "prompt" => "请选择",
                                        "id" => Html::getInputId($model, "subjectID"),
                                        'data-prompt-target' => "subject_prompt",
                                        'data-prompt-position' => "inline",
                                        'data-errormessage-value-missing' => "学科不能为空",
                                        'ajax' => [
                                            'url' => \Yii::$app->urlManager->createUrl('/ajax/get-versions'),
                                            'data' => ['subject' => new  JsExpression('this.value'),'department'=> new JsExpression( 'jQuery("#'.Html::getInputId($model, "department").'").val()')],
                                            'success' => 'function(html){jQuery("#' . Html::getInputId($model, "textbookVersion") . '").html(html).change();}'
                                        ]
                                    ]
                                );
                                ?>
                                <span id="subject_prompt" class="errorTxt"></span>
                            </span>

                    </div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label>教材版本</label>
                    </div>
                    <div class="formR">
                            <span class="selectWrap big_sel">

	                            <?php echo frontend\components\CHtmlExt::dropDownListCustomize(Html::getInputName($model, "textbookVersion"), $model->textbookVersion, LoadTextbookVersionModel::model($model->subjectID, null, $model->department)->getListData(),
		                            [
			                            'data-validation-engine' => 'validate[required]',
			                            "prompt" => "请选择",
			                            "id" => Html::getInputId($model, "textbookVersion"),
			                            'data-prompt-target' => "textbookVersion_prompt",
			                            'data-prompt-position' => "inline",
			                            'data-errormessage-value-missing' => "教材版本不能为空",
		                            ]
	                            );
	                            ?>
                                <span id="textbookVersion_prompt" class="errorTxt"></span>
                            </span>

                    </div>
                </div>

                <div class="row">
                    <div class="formL">
                        <label>选择学校</label>
                    </div>
                    <div class="formR">
                        <?php echo CHtmlExt::activeHiddenInput($model,'schoolId',[ 'data-validation-engine' => "validate[required]",
                            'data-errormessage-value-missing' => "学校不能为空",
                            'data-prompt-target' => "schoolName_prompt",
                            'data-prompt-position' => "inline"]); ?>
                        <?php echo CHtmlExt::activeTextInput($model, 'schoolName',["class" => "text", "id" => 'selectSchoolText',
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
                        <label>任教班级</label>
                    </div>
                    <div class="formR">
                        <ul id="work_cls_list" class="work_cls_list">
                                <?php $tcItem = array_shift($teacherClassList); ?>
                            <?php echo $this->render("//register/_new_reg_teacherClassView", ["tcItem" => $tcItem, 'key' => 0]) ?>
                        <?php
                        foreach ($teacherClassList as $key => $tcItem) {
                            ?>
                            <?php echo $this->render("//register/_new_reg_teacherClassView", ["tcItem" => $tcItem, 'key' => $key + 1]) ?>
                        <?php } ?>

                        </ul>
                        <button type="button" class="btn36 bg_blue_l w130 addClsBtn" id="addClass"> 添加任教班级</button>
                    </div>
                </div>
                <hr style="width:370px;margin:0 auto 20px; position:relative;left:-30px;border-top:1px dashed #ddd; border-bottom:1px dashed #fff">
                <div class="row">
                    <div class="formL">
                        <label>教研组</label>
                    </div>
                    <div class="formR">
                        <ul class="work_cls_list">
                            <li>

                                <?php echo CHtmlExt::activeHiddenInput($teacherGroup,'groupID',['class' => 'clearinput']); ?>
                                <?php echo CHtmlExt::activeTextInput($teacherGroup, "groupName",["class" => "text w120 clearinput", "id" => "sel_tch_group_input",
                                    'data-validation-engine' => "validate[required]",
                                    'data-errormessage-value-missing' => "教研组不能为空",
                                    'data-prompt-position' => "inline",
                                    'data-prompt-target' => "groupName_prompt",
                                    "readOnly" => 'readOnly',
                                ]) ?>
                                <span id="groupName_prompt" class="errorTxt"></span>
                            		<span class="selectWrap big_sel" style="width:161px">
                                        <?php echo CHtmlExt::dropDownListCustomize(Html::getInputName($teacherGroup, "identity"), $teacherGroup->identity, TeachingResearchDutyModel::model()->getListData(),
                                            array("defaultValue" => true)
                                        ); ?>
                                    </span>
                            </li>
                        </ul>
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

<!--弹窗pop----------------------------------------------------->
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

<!--教研组-->
<div class="popBox creat_tch_groupBox hide" title="我所在的教研组">
    <div id="updateGroup">
    </div>
</div>

<script type="text/javascript">
    /*添加任教班级---------------*/
    function checkSingle() {
        var pa = '#work_cls_list';
        if ($(pa + ' li').size() < 2) $(pa + ' li:first .delBtn').hide();
        else $(pa + ' li:first .delBtn').show();
    }
    var teacherClassCount =  <?php echo $teacherClassCount; ?>;

    var apde_html = <?php $out=  $this->render("//register/_new_reg_teacherClassView", ["tcItem" => new  TeacherClassForm(), 'key' => "#temp#"],true); echo   json_encode($out); ?>;
    /*删除行*/
    $('.work_cls_list li .delBtn').live('click', function () {
        $(this).parent().remove();
        checkSingle();
    });
    $('#addClass').bind('click', function () {
        $('#work_cls_list').append(apde_html.replace(/#temp#/g, ++teacherClassCount));
        checkSingle();
    });



</script>
<?php if (Yii::$app->getSession()->hasFlash('error')) { ?>
    <script type="text/javascript">
        $(function(){
            popBox.errorBox(' <?php  echo    Yii::$app->session->getFlash('error'); ?>');
        })

    </script>
<?php } ?>

