
<?php

/*
* Created by PhpStorm.
 * User: wgl
* Date: 14-11-5
* Time: 下午11:56
*/
/* @var $this yii\web\View */  $this->title="视频详情";
$this->registerJsFile(publicResources_new() . '/js/My97DatePicker/WdatePicker.js', [ 'position'=>\yii\web\View::POS_HEAD] );

$backend_asset = publicResources_new();
;
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);


?>
<script type="text/javascript">
    $(function(){
        $('.blue span').mouseover(function(){
            $('.souPosition_js').show();
        })

    })

</script>

<!--主体内容开始-->
<div class="currentRight grid_16 push_2 course_details">

        <div class="noticeH clearfix">
            <h3 class="h3L"><?php echo $model->courseName; ?></h3>
            <div class="fr">
                <a href="<?php echo url('/teacher/coursemanage/upload-demand-video')?>" class="B_btn120 details_btn">上传视频</a>
            </div>
        </div>
        <hr>

        <div class="diary_text diary_text2">
            <h4><?php echo $model->courseName; ?></h4>
            <ul class="course_list clearfix">
                <li><?php echo $model->subjectName; ?></li>
                <li><?php echo $model->gradeName; ?></li>
                <li><?php echo $model->versionName; ?></li>
                <li class="blue">
                    <span><a class="blue" href="<?php echo url('/school/index',array('schoolId'=>$model->schoolID))?>"><?php echo $model->schoolName ?></a></span>


                </li>
            </ul>
            <h5>讲义介绍：</h5>
            <p style=""><?php echo strip_tags($model->courseBrief); ?></p>
            <h6>课时安排：</h6>

            <div class="video clearfix">
                <?php foreach( $model->courseHourList as $valNum){ ?>
                <span>第<?php  echo $valNum->cNum;?>堂课</span>

                <ul class="clearfix video_li">
                    <li><img src="<?php echo publicResources();?>/images/video.png" alt=""></li>
                </ul>
                <ul class="clearfix video_ul">
                    <li><?php  echo $valNum->cName; ?></li>
                    <li>
                        <?php  foreach($kcidName as $v){  ?>
                        <span style="width: 80px;"><?php echo $v->name; ?></span>
                        <?php } ?>
                    </li>
                    <li><em><a href="#"><?php echo $valNum->teachMaterialName?></a></em><i></i></li>
                </ul>

                <div class="video_mix" style="text-align: center; ">
					<video  controls="controls ">
						<source src="<?php echo $valNum->videoUrl;?>" >
					<video>
                </div>
				<?php } ?>
            </div>

        </div>



</div>

<!--主体内容结束-->

