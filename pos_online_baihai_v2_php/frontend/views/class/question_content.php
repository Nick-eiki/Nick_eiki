<?php
/**
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2015/11/20
 * Time: 11:59
 */
use frontend\components\WebDataCache;

?>
<i class="v_r_arrow"></i>
<div class="testPaperView">
<div class="paper">
<?php echo $this->render('//publicView/questionPreview/_itemPreviewType', array('item' => $questionResult)); ?>
<div class="answerArea ">
    <p><em>答案:</em>
        <span><?php echo $this->render('//publicView/questionPreview/_itemProblemAnswer', array('item' => $questionResult)); ?></span>
    </p>
    <?php if(WebDataCache::getShowTypeID($questionResult->tqtid)!= 8){?>
        <p><em>解析:</em>
            <?php echo $questionResult->analytical; ?>
        </p>
    <?php } ?>
</div>
    </div>
</div>
