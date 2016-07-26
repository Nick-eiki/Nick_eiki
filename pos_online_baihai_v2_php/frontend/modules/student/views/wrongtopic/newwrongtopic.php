<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/4/21
 * Time: 15:23
 */
use frontend\models\dicmodels\SubjectModel;

/* @var $this yii\web\View */  $this->title="单科错题列表";
$this->registerJsFile(publicResources_new() . '/js/My97DatePicker/WdatePicker.js'.RESOURCES_VER, [ 'position'=>\yii\web\View::POS_HEAD] );

$backend_asset = publicResources_new();
;
$this->registerJsFile($backend_asset . "/js/jquery.validationEngine-zh_CN.js".RESOURCES_VER);
$this->registerJsFile($backend_asset . "/js/jquery.validationEngine.min.js".RESOURCES_VER);
$this->registerJsFile($backend_asset . "/js/register.js".RESOURCES_VER);
$subject=new SubjectModel();
?>

<div class="grid_19 main_r">
    <div class="main_cont mistake_detail">
        <div class="title">
            <a href="<?php echo url('/student/wrongtopic/manage')?>" class="txtBtn backBtn"></a>
            <h4><?php if(!empty($wrongSubject)){ echo $subject->getSubjectName($wrongSubject->subjectId);}?>错题集</h4>
        </div>
        <div class="schResult">
            <?php echo $this->render('//publicView/wrong/_wrong_question_list',['wrongQuestion'=>$wrongQuestion,'pages' => $pages])?>
        </div>
    </div>
</div>