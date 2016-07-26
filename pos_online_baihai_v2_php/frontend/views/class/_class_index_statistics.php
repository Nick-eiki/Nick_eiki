<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2015/12/10
 * Time: 13:46
 */
/** @var common\models\pos\SeClass $classModel */
use yii\helpers\Url;

$key = $classModel->classID;

$homeworkMember = $classModel->getCountHomeworkMember();
$deadlineTimeHomework = $classModel->getCountDeadlineTimeHomeworkMember();
$examInfoMem = $classModel->getExamInfoMem();
$finishExamMem = $classModel->getFinishExamMem();
$answerAllCount = $classModel->getAnswerAllCount();
$resolvedAnswer = $classModel->getResolvedAnswer();
$fileCount = $classModel->getFileCount();
$readCount = $classModel->getReadCount();
$readCount = isset($readCount) ? $readCount:0;
?>

<dl class="class_top">
    <dd class="class_book">
        <?php if ($isInClass) { ?>
            <a href="<?php echo Url::to(['class/homework', 'classId' => $classModel->classID]); ?>"><i></i>作业</a>
        <?php } else { ?>
            <a><i></i>作业</a>
        <?php } ?>
    </dd>

    <dt class="statistics">
    <div><p>作业统计</p>：<span><?php echo $homeworkMember; ?></span>份</div>
    <div><p>　已截止</p>：<span><?php echo $deadlineTimeHomework; ?></span>份</div>
    <div><p>　未截止</p>：<span><?php echo $homeworkMember - $deadlineTimeHomework ?></span>份</div>
    </dt>
</dl>
<dl class="class_top">
    <dd class="class_answer">
        <a href="<?php echo Url::to(['class/answer-questions', 'classId' => $classModel->classID]) ?>"><i></i>答疑</a>
    </dd>

    <dt class="statistics">
    <div><p>答疑总计</p>：<span><?php echo $answerAllCount; ?></span>个</div>
    <div><p>　已解决</p>：<span><?php echo $resolvedAnswer; ?></span>个</div>
    <div><p>　未解决</p>：<span><?php echo $answerAllCount - $resolvedAnswer; ?></span>个</div>
    　
    </dt>
</dl>
<dl class="class_top noBorder">
    <dd class="class_file">
        <a href="<?php echo Url::to(['class/class-file', 'classId' => $classModel->classID]) ?>"><i></i>文件</a>
    </dd>
    <dt class="statistics">
    <div><p>文件总计</p>：<span><?php echo $fileCount; ?></span>份</div>
    <div><p>阅读总计</p>：<span><?php echo  $readCount; ?></span>次</div>
    </dt>
</dl>