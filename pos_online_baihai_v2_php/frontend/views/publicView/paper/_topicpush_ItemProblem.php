<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 14-12-12
 * Time: 下午4:14
 */
?>

<div class="paper">
    <a href="javascript:;" onclick="popBox.errorCorrect_topic(<?= $item->id ?>)" class="error_correction">我要纠错</a>
    <button type="button" id="<?php echo $item->id;?>" pid="Q_<?php echo $item->tqtid ?>"
            class="editBtn addBtn">组卷
    </button>
    <?php echo $this->render('//publicView/paper/_itemProblemType', array('item' => $item)); ?>
    <div class="btnArea clearfix"><span class="openAnswerBtn fl">查看答案与解析<i class="open"></i></span> <span
            class="r_btnArea fr">难度:<em><?php echo $item->complexityText ; ?></em>&nbsp;&nbsp;&nbsp;录入:<?php echo $item->operaterName; ?></span>
    </div>
    <div class="answerArea hide">
        <p><em>答案:</em>
            <span><?php echo $this->render('//publicView/paper/_itemProblemAnswer', array('item' => $item)); ?></span>
        </p>

        <p><em>解析:</em>
            <?php echo $item->analytical; ?>
        </p>
    </div>
</div>