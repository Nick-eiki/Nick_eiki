<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 4/28/2016
 * Time: 10:32 AM
 * 布置作业详情页面
 */
use common\helper\DateTimeHelper;
use frontend\components\WebDataCache;
use yii\helpers\Url;

?>

<?php
/** @var common\models\pos\SeHomeworkRel $item */
foreach($homeworkRelList as $item) {
	//作业详情

	$homeworkInfo = $item->getHomeWorkTeacher()->one();
	//查询已答数
	$answer = $item->homeworkAnswerInfoCountCache();
	//查询批改数
	$isCorrections = $item->isCheckedStudentCountCache();
	//查询班级学生总数
	$studentMember = WebDataCache::getClassStudentMember($item->classID);


	?>
<div class="monthbox">
	<div class="floatLayer">
<!--		<a href="javascript:;" class="edit"><i></i>编辑</a>-->
		<a href="javascript:;" class="delete is_delete" rel="<?=$item->id; ?>" hmwid="<?php echo $item->homeworkId; ?>" ><i></i>删除</a>
	</div>
	<ul class="worklist">
		<li class="clearfix" relId="<?=$item->id?>">
			<div class="work_left">
				<div class="work_top clearfix">
					<span class="work_img <?php if($homeworkInfo->getType == 0){ echo 'picture'; }elseif($homeworkInfo->getType == 1){ echo 'word'; }?>"></span>
					<div class="work_info">
						<p class="work_title"><?php echo WebDataCache::getClassesName($item->classID) ?></p>
						<p>截止：<span><?=date("Y-m-d",DateTimeHelper::timestampDiv1000($item->deadlineTime)) ?></span></p>
					</div>
				</div>
				<div class="work_bottom clearfix">
					<ul class="progress">
						<li class="clearfix">
							<span>完成人数：</span>
							<div class="progress_outer">
								<div class="progress_inner" style="width: <?php
								if(isset($studentMember) && $studentMember!=0){
									echo ($answer/$studentMember)*100;
								}else{
									echo '0';
								}
								?>%"></div>
							</div>
							<label><?php echo $answer."/".$studentMember; ?></label>
						</li>
						<li class="clearfix">
							<span>批改进度：</span>
							<div class="progress_outer">
								<div class="progress_inner" style="width: <?php
								if(isset($answer) && $answer!=0){
									echo ($isCorrections/$answer)*100;
								}else{
									echo '0';
								}
								?>%"></div>
							</div>
							<label><?php echo $isCorrections."/".$answer; ?></label>
						</li>
					</ul>
				</div>
			</div>
			<div class="work_right">
				<div class="oper">
					<?php
					$nowDatetime = time();
					?>
					<?php if($nowDatetime > strtotime(date('Y-m-d 23:59:59', DateTimeHelper::timestampDiv1000($item->deadlineTime)))){?>
						<a href="javascript:;" class="btn bg_white btn40 icoBtn_book bookBtn btn_disable"><i></i>已截止</a>
					<?php }else{ if($item->isSendMsgStudent==0){?>
						<a href="javascript:;" class="btn bg_white btn40 icoBtn_book bookBtn urge"><i></i>催作业</a>
					<?php }else{?>
						<a href="javascript:;" class="btn bg_white btn40 icoBtn_book bookBtn  btn_disable "><i></i>催作业</a>
					<?php    } }?>
					<a href="<?php echo Url::to(['/class/work-detail', 'classId'=>$item->classID, 'classhworkid'=>$item->id])?>" class="btn bg_white btn40 icoBtn_gou gouBtn"><i></i>去批改</a>
					<?php if($homeworkInfo->getType == 0){ ?>
						<a href="javascript:;" class="btn bg_white btn40 icoBtn_book bookBtn btn_disable"><i></i>看结果</a>
					<?php }else{?>
						<a href="<?php echo Url::to(['/workstatistical/work-statistical-all','relId'=>$item->id,'classId'=>$item->classID])?>" class="btn bg_white btn40 icoBtn_result resultBtn"><i></i>看结果</a>
					<?php } ?>
				</div>
			</div>
		</li>
	</ul>
</div>
	<?php } ?>

