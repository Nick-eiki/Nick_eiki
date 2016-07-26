<?php

/**
 * @var BaseAuthController $this
 */

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
    <p>小题<?php echo $homeworkData->getQuestionNo($item->id) ?>
        : <?php echo \frontend\components\helper\StringHelper::htmlPurifier($item->content); ?></p>
    <?php if ($item->getQuestionShowType() == 1 || $item->getQuestionShowType() == 2) { ?>
        <div class="checkArea">
            <?php
            if($item->answerOption!=''&&$item->answerOption!=null) {
                echo getHomeworkChildQuestionOption($item, $mainId);
            }
            ?>
        </div>
    <?php } ?>
    <?php if ($item->getQuestionShowType() == 3||$item->getQuestionShowType() == 4||$item->getQuestionShowType() == 5) { ?>
    <?php } ?>
    <?php if ($item->getQuestionShowType() == 9) { ?>
        <div class="checkArea">

        </div>
    <?php } ?>

</div>


