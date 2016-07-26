<?php
/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 2015/7/20
 * Time: 11:57
 */
use yii\helpers\Url;

$this->title='教研课题详情';
?>

<div class="main_cont">

    <div class="title">
        <a href="javascript:history.back(-1)" class="txtBtn backBtn"></a>
        <h4>课题详情</h4>
        <div class="title_r">
            <a href="<?= Url::to(['/teachgroup/topic-report','groupId'=>$groupId,'courseId'=>$courseId]);?>" class="btn white btn40 w90 bg_blue">写报告</a>
            <a href="<?= $course->url;?>" class="btn white btn40 w90 bg_blue">下载</a>
        </div>
    </div>
    <div>
        <div class="title item_title noBorder">
            <h4><?= $gradeName; ?>：<?= \frontend\components\CHtmlExt::encode($course->courseName); ?></h4>
        </div>
        <div class="form_list no_padding_form_list">
            <div class="row">
                <div class="formL">
                    <label>课题成员：<?php foreach($courseMembers as $courseMember) : ?><span><?php echo \frontend\components\WebDataCache::getTrueName($courseMember->teacherID); ?>　</span><?php endforeach; ?>
                    </label>

                </div>

            </div>
            <div class="row">
                <div class="formL">
                    <span>课题描述：<?= \frontend\components\CHtmlExt::encode($course->brief); ?></span>
                </div>
            </div>

        </div>

        <div class="report">
            <div class="title">
                <h4 style="text-indent: 0px;">教研报告</h4>
                <div class="title_r">
                    <span>共<?= $pages->totalCount; ?>篇</span>
                </div>
            </div>
            <div id="topicdetailPage">
            <?php echo $this->render('_topicdetail_list',array('groupId'=>$groupId,'pages'=>$pages,'courseReport'=>$courseReport,'courseId'=>$courseId));?>
                </div>
        </div>

    </div>
</div>