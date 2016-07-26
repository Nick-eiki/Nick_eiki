<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/11
 * Time: 11:52
 */

/*
49  209	题型显示	1	单选题	1
50	209	题型显示	2	多选题	1
51	209	题型显示	3	填空题	1
52	209	题型显示	4	问答题	1
53	209	题型显示	5	应用题	1
96	209	题型显示	7	阅读理解	1
95	209	题型显示	6	完形填空	1
*/

if (!isset($no)) {
    $no = '';
}
/** @var common\models\sanhai\ShTestquestion $item */
if(!empty($item)){
?>


<div class="sUI_pannel quest_title">
    <div class="pannel_l"><h5>
            <b><?php echo $homeworkData->getQuestionNo($item->id) ?></b><?= \common\helper\QuestionInfoHelper::getQuestionTypename($item->tqtid) ?>
        </h5></div>
    <!--<div class="pannel_r"><span><a href="javascript:;" class="btn bg_green icoBtn_explain  explainBtn"><i></i>讲解</a> </span></div>-->
</div>
<div class="pd25">
    <div class="Q_title">
        <p><?php echo \frontend\components\helper\StringHelper::htmlPurifier($item->content); ?></p>
    </div>

    <?php if ($item->getQuestionShowType() == 1 || $item->getQuestionShowType() == 2) { ?>
        <div class="Q_cont">
            <?php
            if ($item->answerOption != '' && $item->answerOption != null) {
                echo getHomeworkQuestionContent($item);
            }
            ?>
        </div>
    <?php } ?>

    <?php if ($item->getQuestionShowType() == 3 || $item->getQuestionShowType() == 4 || $item->getQuestionShowType() == 5) {
    } ?>
    <?php if ($item->getQuestionShowType() == 9) { ?>
        <div class="Q_cont">
            <?php
            echo $item->getJudgeQuestionContent();
            ?>
        </div>
    <?php } ?>
</div>
<?php }?>
