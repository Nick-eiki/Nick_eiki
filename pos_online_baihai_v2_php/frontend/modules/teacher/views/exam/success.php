<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-4-14
 * Time: 下午1:29
 */
 /* @var $this yii\web\View */  $this->title="创建成功";
?>
<div class="grid_19 main_r">
    <div class="main_cont test">
        <div class="title">
            <a href="<?=url('teacher/exam/manage',array('classid'=>app()->request->getParam('classID')))?>" class="txtBtn backBtn"></a>
            <h4>创建考试成功</h4>
        </div>
        <div class="grid_14 push_1 test_success">
            <h2>考试创建成功，您现在可以为考试安排试卷！</h2>
            <!--马上安排试卷”跳转到考试的科目详情页；
点击“暂不安排试卷”跳转到考试列表页-->
            <div class="successText tc">
                <a href="<?=url('teacher/exam/subject-details',array('examSubID'=>app()->request->getParam('examSubID'))).'#upload'?>" class="btn bg_blue w100">马上安排试卷</a>
                <a href="<?php echo url('teacher/exam/manage',array('classid'=>app()->request->getParam('classID')))?>" class="btn bg_blue w100">暂不安排试卷</a>

            </div>
        </div>


    </div>
</div>