<?php
/**
 * Created by PhpStorm.
 * User: bbb
 * Date: 2015/7/28
 * Time: 16:22
 */
?>
<ul class="myMsg_notice">
    <?php foreach($courseReport as $k => $v) : ?>
            <li>
                <div class="title noBorder">
                    <h4 style="text-indent: 0px;"><a href="<?= url('teachgroup/report-details',array('groupId'=>$groupId,'courseReportId'=>$v->courseReportId,'courseId'=>$courseId))?>"><?php echo \frontend\components\CHtmlExt::encode($v->reportTitle); ?></a></h4>
                    <div class="title_r">
                        <span><?php echo date("Y-m-d H:i",($v->updateTime)/1000)?></span>
                    </div>
                </div>
                <p>作者：<?php echo \frontend\components\WebDataCache::getTrueName($v->userID) ?></p>
                <p>报告内容：<?php echo cut_str(strip_tags($v->reportContent),50); ?></p>
            </li>
    <?php endforeach; ?>
</ul>
<?php

echo \frontend\components\CLinkPagerExt::widget( [
        'pagination'=>$pages,
        'updateId' => '#topicdetailPage',
        'maxButtonCount' => 5
    ]
);
?>