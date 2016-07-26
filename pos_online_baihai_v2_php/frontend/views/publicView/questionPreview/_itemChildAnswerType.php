<?php

/**
 * @var BaseAuthController $this
 */
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Created by PhpStorm.
 * User: yang
 * Date: 14-12-12
 * Time: 下午4:14
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
?>
<div class="middleTitle">
    <input type="hidden" class="middleTitleID" name="answer[<?php echo $mainId ?>][item][<?php echo $item->id ?>]"
           value=""/>
    <?php if ($item->getQuestionShowType() == 1 || $item->getQuestionShowType() == 2) { ?>

        <p>小题<?php echo $homeworkData->getQuestionNo($item->id) ?>
            : <?php echo \frontend\components\helper\StringHelper::htmlPurifier($item->content); ?></p>
        <div class="checkArea">
            <?php
            echo getHomeworkChildQuestionOption($item, $mainId);
            ?>
        </div>
    <?php } ?>

    <?php if ($item->getQuestionShowType() == 3) { ?>
        <p>小题<?php echo $homeworkData->getQuestionNo($item->id) ?>: <?php echo $item->content ?></p>
    <?php } ?>

    <?php if ($item->getQuestionShowType() == 4) { ?>
        <p>小题<?php echo $homeworkData->getQuestionNo($item->id) ?>: <?php echo $item->content ?></p>
    <?php } ?>
    <?php if ($item->getQuestionShowType() == 9) { ?>
        <p><?php echo $item->content ?></p>
        <div class="checkArea">
            <?php
            $op_list = array(
                '0' => array('id' => '0', 'content' => '错'),
                '1' => array('id' => '1', 'content' => '对')
            );
            echo Html::radioList("answer[$item->id]", '', ArrayHelper::map($op_list, 'id', 'content'),
                ['class' => "radio alternative", 'qid' => $item->id, 'tpid' => $item->getQuestionShowType(), 'separator' => '&nbsp;', 'encode' => false]);
            ?>
        </div>
    <?php } ?>

</div>


