<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/3/19
 * Time: 17:50
 */
use frontend\components\helper\ImagePathHelper;

?>

<div class="c_manage_main " id="course_list">
	<ul class="c_manage_List">
		<?php foreach ($model as $v) { ?>
			<li class="clearfix pr">
				<?php if(empty($v->url)){ ?>
					<img src="<?php echo publicResources();?>/images/video.png" alt="">
				<?php }else{ ?>
					<img src="<?php echo ImagePathHelper::getPicUrl($v->url);  ?>" alt=""/>
				<?php } ?>
				<p class="push_detail">
					<?php //班级
					echo $v->className;
					?>
					<?php
					//科目
					echo $v->subjectName;
					?>
					<?php
					//教师
					echo $v->teacherName;
					?>
					<?php
					//开始时间和结束时间
					echo $v->beginTime;
					echo " ~ ";
					echo $v->finishTime;
					?></p>
				<a href="<?php echo url('teacher/coursemanage/course-details', array('courseId' => $v->courseID)) ?>"
				   target="_blank" class="title_p"><?php echo $v->courseName; ?>

					<em>
						<?php
						$nowTime = strtotime(date("Y-m-d H:i:s", time()));
						$finishTime = strtotime($v->finishTime);
						$startTime = strtotime($v->beginTime);
						if($startTime>=$nowTime+600) {
							echo '（未开始）';
						}elseif($nowTime+600>$startTime && $nowTime<$startTime){
							echo '（即将开课）';
						}elseif($startTime<=$nowTime && $nowTime<=$finishTime){
							echo "（进行中）";
						}elseif($finishTime<=$nowTime){
							echo "（已完结）";
						}
						?>
					</em>
				</a>
				<?php if(!empty($v->courseBrief)){ ?>
				<p>简介：<?php
					if (mb_strlen($v->courseBrief) > 100) {
						echo mb_substr(strip_tags($v->courseBrief), 0, 147) . '......';
					} else {
						echo strip_tags($v->courseBrief);
					}
					?></p>
				<?php } ?>
				<div class="check_div">
					<a class="a_button bg_blue_l" href="#" target="_blank" style="color: inherit" >通知学生</a>
					<?php if($nowTime+600>$startTime && $nowTime<=$finishTime && $v->creatorID == user()->id){ ?>
						<a class="a_button bg_blue_l" href="<?php echo url('video/video') ?>" target="_blank" style="color: inherit" >去上课</a>
					<?php }elseif($finishTime<=$nowTime){?>
						<a class="a_button bg_blue_l" href="<?php echo url('teacher/coursemanage/course-details', array('courseId' => $v->courseID)) ?>" target="_blank" style="color: inherit" >查看</a>
					<?php } ?>
				</div>
			</li>
		<?php } ?>
	</ul>
		<?php
		 echo \frontend\components\CLinkPagerExt::widget( array(
				'pagination' => $pages,
				'updateId' => '#course_list',
				'maxButtonCount' => 10
			)
		);
		?>
</div>

