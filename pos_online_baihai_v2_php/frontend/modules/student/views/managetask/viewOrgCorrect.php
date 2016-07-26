<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-26
 * Time: 下午2:21
 */
use common\models\pos\SeHomeworkTeacher;
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title="查看批改";
?>
<div class="grid_19 main_r">
	<div class="main_cont test_class_overall_appraisal">
		<div class="title"> <a href="javascript:;" class="txtBtn backBtn" onclick="window.history.go(-1)"></a>
			<h4>查看批改</h4>
			<div class="title_r">
                <?php /* @var  SeHomeworkTeacher  $homeworkData */?>
				<span>交作业截至时间:<?php echo date('Y-m-d',\common\helper\DateTimeHelper::timestampDiv1000($homeworkData->getHomeworkRel()->select('deadlineTime')->one()->deadlineTime));?></span>
			</div>
		</div>
		<div class="correctPaper">
			<h5><?php echo Html::encode($homeworkData->name);?></h5>
			<ul class="up_details_list">
				<li class="clearfix">
					<p>科目：<span><?php echo \frontend\models\dicmodels\SubjectModel::model()->getSubjectName($homeworkData->subjectId);?></span></p>
					<p>版本：<span><?php echo \frontend\models\dicmodels\EditionModel::model()->getEditionName($homeworkData->version);?></span></p>
				</li>
                <?php if(!empty($homeworkData->chapterId)){ ?>
				<li class="clearfix">
					<p>章节：<span><?php echo \frontend\models\dicmodels\ChapterInfoModel::findChapterStr($homeworkData->chapterId)?></span></p>
				</li>
                <?php }?>
                <?php
                if(!empty($homeworkData->homeworkDescribe)){
                ?>
                <li class="clearfix">
					<p>作业简介：<span><?php echo $homeworkData->homeworkDescribe;?></span></p>
				</li>
                <?php } ?>
			</ul>
			<div class="slidClip"></div>
			<div class="correctPaperSlide">
				<div class="testPaperWrap mc pr">
					<ul class="testPaperSlideList slid">
						<?php foreach ($answerResult as $v) {
							?>
							<li   name="<?=$v->picID ?>"><img id="<?=$v->picID ?>" src="<?php echo publicResources() . $v->answerUrl ?>" width="830"  alt=""/>
								<?php $checkInfoList = json_decode($v->checkJson);
								if (!empty($checkInfoList->checkInfoList)) {
									foreach ($checkInfoList->checkInfoList as $value) { ?>

										<div class="tips" style="<?php echo $value->style ?>">
                                            <div class="tipsMark">
                                                <?php if(isset($value->answerRight)){ switch ($value->answerRight) {
                                                    case '-1':
                                                        ?>
                                                        <i class="tipsProblem"></i>
                                                        <?php
                                                        break;
                                                    case '0':
                                                        ?>
                                                        <i class="tipsWrong"></i>
                                                        <?php  break;
                                                    case '1':
                                                        ?>
                                                        <i class="tipsCorrect"></i>
                                                        <?php        break;
                                                    case '2':
                                                        break;
                                                }  }?>
                                            </div>
											<span class="scoreTxt"><?php echo $value->comments ?></span>
											<br>

										</div>
									<?php }
								} ?>
							</li>
						<?php } ?>
					</ul>
					<a href="javascript:;" id="prevBtn" class="correctPaperPrev">上一页</a>
					<a href="javascript:;" id="nextBtn" class="correctPaperNext">下一页</a>
				</div>
				<div class="sliderBtnBar"></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function() {
		imgArr=[<?php
                            foreach($answerResult as $val){
                                echo '"'. $val->answerUrl.'",';
                            }

         ?>];

		$('.correctPaperSlide').testpaperSlider({ClipArr:imgArr,img_id:window.location.hash.replace("#","")});
	})
</script>