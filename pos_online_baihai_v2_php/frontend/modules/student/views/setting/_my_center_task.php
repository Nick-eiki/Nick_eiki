<?php
/**
 * 作业
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2015/6/25
 * Time: 10:05
 */
use frontend\components\helper\ViewHelper;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="sup_box myHomework">
	<ul class="itemList sup_ul">
		<?php if(empty($taskResult)){
			ViewHelper::emptyView();
		}
		foreach($taskResult as $val ){
			/** @var common\models\pos\SeHomeworkRel $val */
			$homeworkInfo = $val->getHomeWorkTeacher()->one();

			//查询已答数
			$answer = $val->getHomeworkAnswerInfo()->count();
			//查询批改数
			$isCorrections = $val->getHomeworkAnswerInfo()->where(['isCheck'=>1])->count();
//查询学生提交过答案否

			$isAnswer = $val->getHomeworkAnswerInfo()->where(['studentID'=>user()->id])->count();

			?>
		<li class="clearfix sup_li" style="border-bottom: 1px dashed #d7d7d7;">
			<div class="item_title noBorder sup_l fl">
				<h4>
					<a href="<?php echo Url::to(['managetask/details','relId'=>$val->id]) ?>" class="details" homeworkID="<?= $val->homeworkId?>">
						<i class="gray_d" style="display: inline; padding-right: 10px;">
							[ <?php
							if(!empty($homeworkInfo)){
								if($homeworkInfo->getType==0){
									echo '纸质';
								}elseif($homeworkInfo->getType == 1){
									echo '电子';
								}
							}?> ]
						</i>
						<?php if(!empty($homeworkInfo)){ echo Html::encode($homeworkInfo->name);}  ?>
					</a>
				</h4>
				<dl>
					<dd class="clearfix schedule">
						<div class="timeBox">
							<em class="scrollT">已答：未答</em>
							<p class="scrollBar">
								<span class="progress">
									<sub style="top:-5px">
										<b style="width: <?php
										if(isset($studentMember) && $studentMember!=0){
											echo ($answer/$studentMember)*100;
										}else{
											echo '0';
										}
										?>%"></b>
										<em class="scrollR">
											<i><?= $answer; ?>:</i>
											<i><?= $studentMember-$answer; ?></i>
										</em>
									</sub>
								</span>
							</p>
						</div>
					</dd>
				</dl>
			</div>
			<div class="sup_r  fr">
				<div class="sup_box">
					<div>
						<?php if(isset($isAnswer) && $isAnswer == 0){?>
							<a href="<?php echo Url::to(['managetask/details','relId'=>$val->id])?>" class="a_button notice w120 " relId="<?= $val->id?>">写作业</a>
						<?php }elseif (isset($isAnswer) && $isAnswer == 1){?>
							<em class="w100">已答</em>
						<?php }?>
					</div>
				</div>
			</div>
		</li>
		<?php } ?>
	</ul>
</div>