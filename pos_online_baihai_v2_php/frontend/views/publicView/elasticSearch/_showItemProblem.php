<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 14-12-12
 * Time: 下午4:14
 */
use frontend\components\helper\StringHelper;
use frontend\components\WebDataCache;

?>
<div class="testPaperView pr"><!--选择题-->
    <div class="paperArea">
        <div class="paper">
            <div class="paper_r">
                <a href="javascript:;" onclick="popBox.errorCorrect_topic(<?= $item->id ?>)" class="error_correction">我要纠错</a>
                <?php if ($item->isCollected == 0) { ?>
                    <a href="javascript:;" class="page_fav" data-id="<?= $item->id ?>"><i></i>收藏</a>
                <?php } elseif ($item->isCollected == 1) { ?>
                    <a href="javascript:;" class="page_cancel_fav" data-id="<?= $item->id ?>"><i></i>取消收藏</a>
                <?php } ?>
            </div>

            <?php echo $this->render('//publicView/elasticSearch/_itemProblemType', array('item' => $item)); ?>
            <div class="btnArea clearfix"><span class="openAnswerBtn fl">查看答案与解析<i
                        class="open"></i></span> <span
                    class="r_btnArea fr">难度:<em><?php echo WebDataCache::getDictionaryName($item->complexity) ?></em>&nbsp;&nbsp;&nbsp;</span>
            </div>
            <div class="answerArea hide">
                <p><em>答案:</em>
                    <span><?php echo $this->render('//publicView/elasticSearch/_itemProblemAnswer', array('item' => $item)); ?></span>
                </p>

                <p><em>解析:</em>
                    <?php echo StringHelper::htmlPurifier($item->analytical); ?>
                </p>
            </div>
        </div>
    </div>
</div>