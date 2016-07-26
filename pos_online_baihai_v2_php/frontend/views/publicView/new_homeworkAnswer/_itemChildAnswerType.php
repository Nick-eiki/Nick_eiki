<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/14
 * Time: 15:11
 */

?>
<div id="<?= $item->id ?>" class="quest sub_quest">
    <div class="sUI_pannel quest_title">
        <div class="pannel_l"><h5>
                <b><?php echo $homeworkData->getQuestionNo($item->id) ?></b><?= \common\helper\QuestionInfoHelper::getQuestionTypename($item->tqtid) ?>
            </h5></div>
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
            <div class="checkBar">
                <?php
                if ($isAnswered) {
                    if(isset($objectiveAnswer)){
                        echo getHomeworkQuestionOptionAnswer($item, $objectiveAnswer);
                    }

                } else {
                    echo getHomeworkQuestionOption($item);
                }
                ?>
            </div>
        <?php } ?>

        <?php if ($item->getQuestionShowType() == 3 || $item->getQuestionShowType() == 4 || $item->getQuestionShowType() == 5) {?>
            <div class="checkBar">
                <div class="sbj_prompt"><i></i>主观题请在答题卡中上传图片作答</div>
            </div>
        <?php } ?>

        <?php if ($item->getQuestionShowType() == 9) { ?>
            <div class="Q_cont">
                <?php
                if ($isAnswered) {
                    if(isset($objectiveAnswer)){
                        echo $item->getJudgeQuestionOptionAnswer($objectiveAnswer);
                    }

                } else {
                    echo $item->getJudgeQuestionOption();
                }
                ?>
            </div>
        <?php } ?>

    </div>
</div>


