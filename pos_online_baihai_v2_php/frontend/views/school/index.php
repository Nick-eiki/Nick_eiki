<?php

use frontend\models\dicmodels\SchoolLevelModel;
use frontend\services\pos\pos_SchoolSloganService;

/* @var $this yii\web\View */

$this->registerCssFile(publicResources_new() . '/css/school_home.css'.RESOURCES_VER);
$schoolModel=$this->params['schoolModel'];

$schoolId = $schoolModel->schoolID;
$this->blocks['bodyclass'] = "school_home";
//学校签名
$obj = new pos_SchoolSloganService();
$slogan = $obj->searchSchoolSlogan($schoolId);
$school_slogan = isset($slogan) ? $slogan->schoolSlogan : '';


/* @var $this yii\web\View */  $this->title="学校-主页-" . $schoolModel->schoolName;

?>



<?php $this->beginBlock('head_html_ext'); ?>
<div class="BG_cover blur"></div>
<div class="mask"></div>
<?php $this->endBlock('head_html_ext') ?>

<?php $this->beginBlock('head_html'); ?>
<script>
    $(function () {
        $('.mark').click(function () {
            $(this).toggleClass('chked')
        })
    })
</script>
<?php $this->endBlock('head_html') ?>

<div class="cont24">
    <div class="main grid_20 push_2">
        <div class="main_top">
            <h2><?= $schoolModel->schoolName ?></h2>

            <h3><?= $school_slogan ?></h3>

            <p>
                <span>学部：<?= implode('&nbsp;&nbsp;', SchoolLevelModel::model()->departmentNameArr($schoolModel->department)) ?></span>
                <?php if (isset($pointLineSearchList) && isset($pointLineSearchList->pointLinelist) && !empty($pointLineSearchList->pointLinelist)) {
                    $pointLine = $pointLineSearchList->pointLinelist[0]; ?>
                    <span>分数线：<?= $pointLine->year ?>年 — <?= $pointLine->admissionLine ?>分</span>
                <?php } ?>
            </p>
        </div>
        <ul class="objList">
             <li class="objA"><i></i><a href="<?= url('school/publicity', array('schoolId' => $schoolId)); ?>">学校公示<b></b></a>
            <!--            </li>-->
            <li class="objB"><i></i><a href="<?= url('school/answer-questions', array('schoolId' => $schoolId)); ?>">校内答疑<b></b></a>
            </li>
            <!--            <li class="objC"><i></i><a-->
            <!--                    href="-->
            <?php //echo  url('school/teachinglist', array('schoolId' => $schoolId)) ?><!--">教研组,资料<b></b></a></li>-->
            <li class="objD"><i></i><a href="<?= url('school/teacher', array('schoolId' => $schoolId)) ?>">师生,班级<b></b></a>
            </li>
        </ul>


    </div>
</div>
