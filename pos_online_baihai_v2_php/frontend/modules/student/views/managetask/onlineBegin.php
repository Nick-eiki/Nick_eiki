<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-11-15
 * Time: 上午11:31
 */
use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */  $this->title="开始答题";
?>
<div class="currentRight grid_17 push_1 hear">
    <div class="notice">
        <div class="noticeH clearfix noticeB">
            <h3 class="h3L">日常作业</h3>
        </div>
        <hr>
        <div class="unanswer_main">
            <h4>课程名称课程名称课程名称课程名称课程名称课程名课程名称课程</h4>
            <ul class="unanswer_list">
                <li><span><em>地区：</em> <?php echo AreaHelper::getAreaName($homeworkResult->provience)."&nbsp".AreaHelper::getAreaName($homeworkResult->city)."&nbsp".AreaHelper::getAreaName($homeworkResult->country)?></span> <span><em>年级：</em><?php echo GradeModel::model()->getGradeName($homeworkResult->gradeId)?></span> <span><em>科目：</em><?php echo SubjectModel::model()->getSubjectName($homeworkResult->subjectId)?></span> <span><em>版本：</em><?php echo EditionModel::model()->getEditionName($homeworkResult->version)?></span></li>
                <li><em>知识点：</em><?php echo KnowledgePointModel::findKnowledgeStr($homeworkResult->knowledgeId)?></li>
                <li><em>试卷简介：</em> <?php echo $homeworkResult->homeworkDescribe?>
                   </li>
            </ul>
            <p class="conserve clearBoth">
                <a href="<?php echo url('student/managetask/online-answering',array('homeworkID'=>app()->request->getQueryParam('homeworkID')))?>" class="B_btn110" style="display:inline-block;">开始答题</a>
            </p>
        </div>
    </div>
</div>