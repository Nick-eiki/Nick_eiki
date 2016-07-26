<?php
/** @var $teacherClassList TeacherClassForm[] */
use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\EditionModel;
use frontend\models\dicmodels\SchoolLevelModel;
use frontend\models\dicmodels\SubjectModel;
use frontend\models\dicmodels\TeachingResearchDutyModel;
use frontend\models\IdentityModel;
use frontend\models\TeacherClassForm;
use frontend\models\TeacherGroupForm;
use yii\helpers\Html;

/** @var $teacherGroupList TeacherGroupForm[] */
/* @var $this yii\web\View */  $this->title="基本信息";

$this->registerJsFile($publicResources . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($publicResources . '/js/jquery.validationEngine-zh_CN.js');
$this->registerJsFile($publicResources . '/js/register.js');
$teacherClassCount = count($teacherClassList);
$teacherGroupCount = count($teacherGroupList);



?>
<div class="currentRight grid_22  cp_email_cl">
<?php  /** @var $form CActiveForm */
$form =\yii\widgets\ActiveForm::begin( array(
    'enableClientScript' => false,
))?>
<ul class="form_list organization">
    <li>
        <div class="formL">
            <label><i></i>姓名：</label>
        </div>
        <div class="formR">
            <input type="text" class="text" value="<?php echo $model->trueName ?>"
                   name="<?php echo Html::getInputName($model, 'trueName') ?>"
                   data-validation-engine="validate[required]"
                >
            <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'trueName') ?>
        </div>
    </li>
    <li>
        <div class="formL">
            <label><i></i>手机号：</label>
        </div>
        <div class="formR"><input type="text" class="text" value="<?php echo $model->phone ?>"
                                  name="<?php echo Html::getInputName($model, 'phone') ?>"
                                  data-validation-engine="validate[required,custom[phoneNumber]]">
        </div>
    </li>
    <li>
        <div class="formL">
            <label class="label">所在地区：</label>
        </div>
        <div class="formR">
            <?php
            echo AreaHelper::getAreaName($model->provience);
            ?>
            <?php
            echo AreaHelper::getAreaName($model->city);
            ?>
            <?php
            echo AreaHelper::getAreaName($model->county);
            ?>
        </div>
    </li>
    <li>
        <div class="formL">
            <label class="label">选择学校：</label>
        </div>
        <div class="formR">
            <?php echo $form->hiddenField($model, 'schoolId'); ?>
            <?php echo $model->schoolName; ?>&nbsp;<span class="gray_l txtBtn modifyStageBtn" show="学校">修改</span>
        </div>
    </li>
    <li>
        <div class="formL">
            <label class="label">学段：</label>
        </div>
        <div class="formR">
            <?php echo  $form->hiddenField($model, 'department'); ?>
            <?php echo  SchoolLevelModel::model()->getSchoolLevelhName($model->department) ?>&nbsp;<span class="gray_l txtBtn modifyStageBtn" show="学段">修改</span>
        </div>
    </li>
    <li>
        <div class="formL">
            <label class="label"><i></i>教材版本：</label>
        </div>
        <div class="formR">
            <?php echo $form->dropDownList($model, 'textbookVersion', EditionModel::model()->getListData(),
                [
                    'data-validation-engine' => 'validate[required]', "defaultValue" => false,
                    "prompt" => "请选择",
                    'data-prompt-target' => "textbookVersion_prompt",
                    'data-prompt-position' => "inline",
                    'data-errormessage-value-missing' => "学段不能为空",
                ]
            );
            ?>
            <span id="textbookVersion_prompt"></span>
        </div>
    </li>

    <li>
        <div class="formL">
            <label class="label"><i></i>任教班级：</label>
        </div>
        <div class="formR" id="classesInput">
            <div class="input_list">
                <?php $tcItem = array_shift($teacherClassList); ?>
                <?php echo $form->hiddenField($tcItem, '[0]classID'); ?>
                <?php echo $form->textField($tcItem, '[0]className', ["class" => "text text_defined",
                    'data-validation-engine' => "validate[required]",
                    "onfocus"=>'this.blur()',
                    'data-errormessage-value-missing' => "班级不能为空",
                ]) ?>

                <?php  echo $form->dropDownList($tcItem, '[0]identity', IdentityModel::getTeacherIdentity(),
                    array(
                        "defaultValue" => false, "prompt" => "请选择",
                    ));?>
                <?php  echo $form->dropDownList($tcItem, '[0]subjectNumber', SubjectModel::model()->getListData(),
                    array(
                        "defaultValue" => false, "prompt" => "请选择",
                    ));?>
                <a href="javascript:" id="addClass" class="apde_btn apde_js"><i>添加</i>任教班级</a></div>
            <?php
            foreach ($teacherClassList as $key => $tcItem) {
                ?>
                <?php echo $this->render("//register/_reg_teacherClassView", ["tcItem" => $tcItem, 'key' => $key + 1]) ?>
            <?php } ?>
        </div>
    </li>
    <hr>
    <li>
        <div class="formL">
            <label class="label"><i></i>教研组：</label>
        </div>
        <?php $tcgItem = array_shift($teacherGroupList);
        ?>
        <div class="formR" id="groupInput">
            <div class="input_list">
                <?php echo $form->hiddenField($tcgItem, "[0]groupID"); ?>
                <?php echo $form->textField($tcgItem, "[0]groupName", ["class" => "text text_defined text_l text_t",
                    "onfocus"=>'this.blur()',
                ]) ?>
                <?php  echo $form->dropDownList($tcgItem, "[0]identity", TeachingResearchDutyModel::model()->getListData(),
                    array(
                        "defaultValue" => false, "prompt" => "请选择",
                    ));?>
                <a href="javascript:" id="addGroup" class="apde_btn apde_btn_c k_js"><i>添加</i>教研组</a>

            </div>

            <?php /** @var $this Controller */
            foreach ($teacherGroupList as $key => $tcgItem): ?>
                <?php echo $this->render("//register/_reg_teacherGroupView", ["tcgItem" => $tcgItem, 'key' => $key + 1]) ?>
            <?php endforeach ?>
        </div>

    </li>
    <hr>

    <li>
        <div class="formL">
            <label class="label"></label>
        </div>
        <div class="formR">
            <button type="submit" class="bg_red_d w120">确&nbsp;&nbsp;定</button>
        </div>
    </li>
</ul>
<?php \yii\widgets\ActiveForm::end(); ?>
</div>

<!--弹出框pop--------------------------------------------------------------->
<!--我就职的学校--->
<div id="mySchoolPop" class="popBox mySchoolPop hide" title="我就职的学校">
    <div id="updateSchool">
    </div>
</div>

<!--我就读的班--------------------->
<div id="myClassesPop" class="popBox myClassesPop hide" title="就读教的班级">
    <div id="updateClass">
    </div>
</div>
<!--我就职的教研组--->
<div id="myGroupPop" class="popBox myGroupPop hide" title="我就职的教研组">
    <div id="updateGroup">
    </div>
</div>

<script>
var $department = $('#TeacherUserForm_department');
var $schoolId = $('#TeacherUserForm_schoolId');
var $schoolName = $('#TeacherUserForm_schoolName');
var $classesId = $('#TeacherUserForm_classId');
var $classesName = $('#TeacherUserForm_className');
$department.change(function () {
    $schoolId.val('');
    $schoolName.val('');
    $schoolName.change();
});

$schoolName.change(function () {
    $classesId.val('');
    $classesName.val('');
    $classesName.change();
});

$classesName.change(function () {
});




$(function () {
    //我就任的教研组
    $('#groupInput .text_defined').live('click', function () {
        var $text = $(this);
        var $hidden = $text.prevAll('input:hidden');

        $('.groupList li').click(function () {
            $(this).addClass('ac').siblings().removeClass('ac');
        });
        $('#myGroupPop').dialog({
            autoOpen: false,
            width: 500,
            modal: true,
            resizable: false, open: function () {
                $.post('<?php echo url('register/search-teaching-group') ?>', {
                        schoolId: $schoolId.val(),
                        department: $department.val()
                    },
                    function ($data) {
                        $('#updateGroup').html($data);
                    }
                )
            },

            buttons: [
                {
                    text: "确定",
                    click: function () {
                        var $this = $(this);
                        $addTeachinggroupForm = $('#addGroupInfo');
                        if ($.trim($addTeachinggroupForm.find(':text').val()) != '') {
                            $.post($addTeachinggroupForm.attr('action'), $addTeachinggroupForm.serialize(), function (data) {
                                if (data.success == true) {
                                    $hidden.val(data.data.split("|")[0]);
                                    $text.val(data.data.split("|")[1]);
                                }
                                else {
                                    popBox.alertBox("添加失败");
                                }
                            })
                        } else {
                            var select = $('li.ac', $this);
                            $hidden.val(select.attr('value'));
                            $text.val(select.text()).change();
                            $this.dialog("close");
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

        $("#myGroupPop").dialog("open");
        return false;
    });
    $('#classesInput .text_defined').live('click', function () {
        var $text = $(this);
        var $hidden = $text.prevAll('input:hidden');

        //我就读的班

        $('#myClassesPop').dialog({
            autoOpen: false,
            width: 500,
            modal: true,
            resizable: false,
            open: function () {
                $.post('<?php echo url('register/search-classes') ?>', {
                        schoolId: $schoolId.val(),
                        department: $department.val()
                    },
                    function ($data) {
                        $('#updateClass').html($data);
                    }
                )
            },
            buttons: [
                {
                    text: "确定",
                    click: function () {
                        var $this = $(this);

                        if ($.trim($('#addClassInfo .text').val()) != '') {
                            $addClassInfo = $('#addClassInfo');
                            $.post($addClassInfo.attr('action'), $addClassInfo.serialize(), function (data) {
                                if (data.success) {

                                    $hidden.val(data.data.split('|')[0]);
                                    $text.val(data.data.split('|')[1]).change();

                                } else {
                                    popBox.alertBox(data.message);
                                }
                                $this.dialog("close");
                            });

                        }
                        else {

                            var select = $('li.ac', $this);
                            $hidden.val(select.attr('value'));
                            $text.val(select.text()).change();
                            $this.dialog("close");
                        }

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

        $("#myClassesPop").dialog("open");
        return false;
    });
    //我就任的部门
    $('#deptInput .text').live('click', function () {

        $("#myDeptPop").dialog("open");
        return false;
    });
});
$(function () {
    $('.addClassesBtn').click(function () {
        $('.newClassesList').show();
    });
    $('.addSchoolBtn').live('click', function () {
        $('.newSchoolList').show();
    });
    $('.addGroupBtn').live('click', function () {
        $('.newGroupList').show();
    });
    $('.addDeptBtn').live('click', function () {
        $('.newDeptList').show();
    })
});


</script>

<script type="text/javascript">
    var teacherClassCount =  <?php echo $teacherClassCount; ?>;
    var teacherGroupCount =  <?php echo   $teacherGroupCount; ?>;



    var apde_html = <?php $out=  $this->render("//register/_reg_teacherClassView", ["tcItem" => new  TeacherClassForm(), 'key' => "#temp#"],true); echo   json_encode($out); ?>;
    var apde_html2 = <?php $out= $this->render("//register/_reg_teacherGroupView", ["tcgItem" => new  TeacherGroupForm(), 'key' => "#temp#"],true); echo   json_encode($out); ?>;
    $('.input_p .apde_btn_2').live('click', function () {
        $(this).parent().remove();
    });

    $('#addGroup').bind('click', function () {
        $('#groupInput').append(apde_html2.replace(/#temp#/g, ++teacherGroupCount));
    });
    $('#addClass').bind('click', function () {
        $('#classesInput').append(apde_html.replace(/#temp#/g, ++teacherClassCount));
    });


    $('.modifyStageBtn').click(function(){
        var name=$(this).attr("show");
        popBox.alertBox('<p>变更'+name+'后，您将会脱离原来的班级或教研组，</p><br><p style="font-size:20px;text-align:center; color:#900">确定变更吗?</p><br>',
            function(){
                window.location.href = '<?php echo url('register/del-relationship');?>';
            }
        )
    });

</script>
