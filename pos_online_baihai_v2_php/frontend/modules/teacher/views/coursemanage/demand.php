<?php
/**
 * Created by PhpStorm.
 * User: unizk
 * Date: 14-10-30
 * Time: 下午4:22
 */
/* @var $this yii\web\View */  $this->title="精品课程";
?>
<div class="currentRight grid_16 push_2 on_denand">
    <div class="noticeH clearfix">
        <h3 class="h3L">点播课程</h3>
        <div class="fr">
            <a href="<?php echo url('/teacher/courseManage/upload-demand-video')?>" class="B_btn120 uploadVideoBtn">上传视频</a>
        </div>
    </div>
    <hr>
		<?php echo $this->render('_demand_list', array('model'=>$model, 'pages'=>$pages));?>
</div>
