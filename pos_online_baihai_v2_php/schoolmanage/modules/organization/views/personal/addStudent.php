<?php
/**
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2016/7/15
 * Time: 14:58
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title='添加学生';
$this->registerCssFile(publicResources_new() . '/css/school_add_stu.css');
$this->registerCssFile(publicResources_new().'/css/school_testMag.css');
$this->registerJsFile(publicResources_new() . '/js/require.js', ['position' => \yii\web\View::POS_HEAD, 'data-main' => publicResources_new() . '/js/app/school/app_add_stu.js']);
?>
<div class="col1200 clearfix sch_mag_person sch_mag_teacher" id="requireModule" rel="app/sch_mag/sch_mag_teacher">
    <input type="hidden" id="classID" value="<?php echo $classID?>"/>
    <div class="mag_title">
        <a href="<?=Url::to(['/organization/personal/manage-list','classId'=>$classID])?>" class="btn btn30 icoBtn_back gobackBtn"><i></i>返回</a>
        <h4>单个添加学生</h4>
    </div>
    <div id="form_add_stu" class="container">
        <?php $form = ActiveForm::begin(array(
            'enableClientScript' => false,
            'id' => "edit_user_info_form",
            'method' => 'post'
        )) ?>
        <div class="add_stu">
            <lable for="stu_ID">学号：</lable>
            <input id="stu_ID" name="stu_ID" type="text"/><br>
            <lable for="stu_name"><i class="req">*</i>姓名：</lable>
            <input id="stu_name" name="stu_name" type="text"   class="input_txt  "
                   data-validation-engine="validate[required,minSize[2],maxSize[20]]"
                   data-errormessage-value-missing="用户名不能为空"
                /><br>
            <lable for="stu_mol"><i class="req">*</i>手机号：</lable>
            <input id="stu_mol" name="stu_mol" type="text"  class="input_txt  "
                   data-validation-engine= "validate[required,custom[phoneNumber]]"
                   data-errormessage-value-missing="手机号不能为空"
                /><br>
            <lable>性别：</lable>
            <input type="radio" name="sex_again" id="male" value="0" checked="checked"/>男
            <input type="radio" name="sex_again" id="female"  value="1"/>女
            <div id="verification">
            <button class="btn" id="proof" type="button">校验</button>
                </div>
        </div>
        <div class="add_stu_verification">



        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <?php $form = ActiveForm::begin(array(
        'enableClientScript' => false,
        'id' => "edit_user_info_form_again",
        'method' => 'post'
    )) ?>
    <div id="form_add_stu_1">



    </div>
    <?php ActiveForm::end(); ?>
</div>
