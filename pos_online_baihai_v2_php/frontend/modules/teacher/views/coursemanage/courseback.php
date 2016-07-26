<?php
/**
 * Created by unizk.
 * User: ysd
 * Date: 14-10-30
 * Time: 下午5:01
 */
/* @var $this yii\web\View */  $this->title="课程管理-家长会回放";
?>
<div class="currentRight grid_16 push_2 course_playback">

    <div class="noticeH clearfix">
        <h3 class="h3L">视频名称</h3>
    </div>
    <hr>
    <div class="diary_text">
        <h4><?php echo $modelList->meetingName;?></h4>
        <p class="class_time"><em><?php echo $modelList->beginTime;?></em>至<em><?php echo $modelList->finishTime;?></em></p>
        <p>会议议题： <?php echo $modelList->meetingDetail;?>
        <div class="video_mix">
            视频播放器插件
        </div>
    </div>



</div>