<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/1/29
 * Time: 10:15
 */
/* @var $this yii\web\View */  $this->title="精品详情";
?>



<!--主体内容开始-->
	<div class="clearfix grid_24 bg_white">
    <div class="centLeft">
        <div class="crumbs noticeB">
			<a href="<?php echo url('teacher/default/index',array('teacherId'=>$model->teacherID))?>">教师主页</a>
			>>
			<a href="#"><?php  echo $model->courseName; ?></a>
		</div>
        <div class="notice ">
            <div class="plan_l details">

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
                                        <span style="width: 80px;"><?php echo $v->chaptername; ?></span>
                                    <?php } ?>
                                </li>
                                <li><em><a href="#"><?php echo $valNum->teachMaterialName?></a></em><i></i></li>
                            </ul>

                        <div class="video_mix">
							<video  controls="controls ">
								<source src="<?php echo $valNum->videoUrl;?>" >
								<video>
                        </div>
						<?php } ?>
                    </div>
                </div>


            </div>

        </div>
    </div>
    <div class="centRight">
        <div class="item Ta_teacher">
            <h4>Ta的老师</h4>
            <a class="more" href="#">更多</a>
            <ul class="teacherList">
                <li>
                    <img src="<?php echo publicResources();?>/images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="<?php echo publicResources();?>/images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="<?php echo publicResources();?>/images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="<?php echo publicResources();?>/images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="<?php echo publicResources();?>/images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="<?php echo publicResources();?>/images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="<?php echo publicResources();?>/images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="<?php echo publicResources();?>/images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="<?php echo publicResources();?>/images/user_m.jpg">
                    张三丰
                </li>
            </ul>


        </div>
    </div>
	</div>