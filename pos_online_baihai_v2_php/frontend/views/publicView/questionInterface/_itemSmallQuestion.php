<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/11
 * Time: 11:52
 */
/* @var $this yii\web\View */
/* @var $item common\models\sanhai\ShTestquestion*/
/*
49  209	题型显示	1	单选题	1
50	209	题型显示	2	多选题	1
51	209	题型显示	3	填空题	1
52	209	题型显示	4	问答题	1
53	209	题型显示	5	应用题	1
96	209	题型显示	7	阅读理解	1
95	209	题型显示	6	完形填空	1
*/

use common\helper\StringHelper;

if (!isset($no)) {
    $no = '';
}
?>

<div class="pd25 small">
    <div class="Q_title">
        <p><?php echo \frontend\components\helper\StringHelper::htmlPurifier(StringHelper::replacePath($item->content)); ?></p>
    </div>
    <?php if ($item->showType== 1 || $item->showType== 2) { ?>
        <div class="Q_cont">
            <?php
            if ($item->answerOption != '' && $item->answerOption != null) {
                echo StringHelper::replacePath(getHomeworkQuestionContent($item));
            }
            ?>
        </div>
    <?php } ?>

    <?php if ($item->showType== 3 || $item->showType== 4 || $item->showType== 5) {
    } ?>
    <?php if ($item->showType == 9) { ?>
        <div class="Q_cont">
            <?php
            echo $item->getJudgeQuestionContent();
            ?>
        </div>
    <?php } ?>
</div>

