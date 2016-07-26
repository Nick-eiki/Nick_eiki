<?php
/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 2015/6/1
 * Time: 15:13
 */

use common\models\pos\SeHomeworkAnswerImage;
use common\models\sanhai\ShTestquestion;
use frontend\components\helper\ImagePathHelper;
use frontend\components\helper\LetterHelper;
use frontend\components\helper\StringHelper;
use frontend\components\WebDataCache;

/** @var common\models\pos\SeHomeworkAnswerInfo $item */
$answerInfoImg = $item->getHomeworkAnswerDetailImage()->all();
$answerInfoImgCount = count($answerInfoImg);
/** @var common\models\pos\SeHomeworkAnswerQuestionAll $v */
$answerQuestionAll = $item->getHomeworkAnswerQuestionAll()->all();
$answerImg = SeHomeworkAnswerImage::find()->where(['homeworkAnswerId' => $item->homeworkAnswerID])->all();
$isCountAnswerImg = count($answerImg);
//判断有没有主观题
$hasMajor=false;
foreach($answerQuestionAll as $v){
    $teQuestionModel=ShTestquestion::find()->where(['id'=>$v->questionID])->one();
    if($teQuestionModel && $teQuestionModel->isMajorQuestionCache() ){
        $hasMajor=true;
        break;
    }
}
?>

<div class="stu_item">
	<div class="title item_title noBorder">
		<h4>
			<a href="#" class="blue_l_l" title="<?php echo WebDataCache::getTrueName($item->studentID); ?>">
				<?php echo StringHelper::cutStr(WebDataCache::getTrueName($item->studentID), 4); ?>
			</a>
			<?php if ($answerInfoImgCount != '0') {
				echo "的答案--共" . $answerInfoImgCount . "页&nbsp;&nbsp;";
			} ?>&nbsp;&nbsp;
			<span class="gray">已批改</span>
		</h4>

		<div class="title_r">
			<?php if ($item->getType == 0) { ?>
				<a href="<?= url('teacher/managetask/new-pic-correct', ['homeworkAnswerID' => $homeworkAnswerID]) ?>"
				   class=" btn bg_blue_l btn30 w80">查看批改</a>
			<?php } elseif ($item->getType == 1&&$hasMajor) { ?>
				<a href="<?= url('teacher/managetask/new-org-correct', ['homeworkAnswerID' => $homeworkAnswerID]) ?>"
				   class=" btn bg_blue_l btn30 w80">查看批改</a>
			<?php } ?>

		</div>
	</div>
	<div class="questionArea">
		<div class="my_answer">
			<?php if ($item->getType == 1) { ?>
				<div class="digitalFile">
					<h6>客观题答案</h6>

					<p>
						<?php
						foreach ($answerQuestionAll as $k => $v) {
							$testQuestion = $v->getShTestquestion()->select('tqtid,id')->one();
							$isMajorQuerstion = $testQuestion->isMajorQuestionCache();
							$getQuestionShowType = $testQuestion->getQuestionShowType();

							if (!$isMajorQuerstion) { ?>

								<?php
								if ($v->ischecked == 1) {
									if ($getQuestionShowType==9) { ?>

										<span class="correct">
											<?php echo $homeWorkTeacher->getQuestionNo($testQuestion->id) . '.';
											echo LetterHelper::rightOrWrong($v->answerOption)?>
										</span>
									<?php
									} else {

										if ($v->correctResult == 3) {
											?>
											<span class="correct">
										    <?php echo $homeWorkTeacher->getQuestionNo($testQuestion->id) . '.';
										    echo LetterHelper::getLetter($v->answerOption); ?>
												<i class="right"></i>
									    </span>
										<?php } elseif ($v->correctResult == 2) { ?>
											<span class="correct">
										    <?php echo $homeWorkTeacher->getQuestionNo($testQuestion->id). '.';
										    echo LetterHelper::getLetter($v->answerOption); ?>
												<i class="half"></i>
									    </span>
										<?php } elseif ($v->correctResult == 1) { ?>
											<span class="correct">
										    <?php echo $homeWorkTeacher->getQuestionNo($testQuestion->id). '.';
										    echo $v->answerOption==''?'未答':LetterHelper::getLetter($v->answerOption); ?>
												<i class="wrong"></i>
									    </span>
										<?php }
									} ?>
								<?php } else { ?>
									<span class="correct">
									    <?php echo $homeWorkTeacher->getQuestionNo($testQuestion->id) . '.';
									    echo LetterHelper::getLetter($v->answerOption); ?>
								    </span>

								<?php }
							}
						} ?>
					</p>
                    <?php if($hasMajor){
                    ?>
					<h6>主观题答案</h6>
             <?php }?>
					<p>
						<?php
                        if($hasMajor&&$isCountAnswerImg==0){
                            echo '该学生未答主观题';
                        }
						foreach ($answerQuestionAll as $k => $v) {
							$testQuestion = $v->getShTestquestion()->select(['tqtid'])->one();
							$isMajorQuerstion = $testQuestion->isMajorQuestionCache();
							if ($isMajorQuerstion) { ?>
								<?php if ($v->ischecked == 1) {
									if ($v->correctResult == 3) {
										?>
										<span class="correct">
										    <?php echo $k + 1 . '.';
										    echo LetterHelper::getLetter($v->answerOption); ?>
											<i class="right"></i>
									    </span>
									<?php } elseif ($v->correctResult == 2) { ?>
										<span class="correct">
										    <?php echo $k + 1 . '.';
										    echo LetterHelper::getLetter($v->answerOption); ?>
											<i class="half"></i>
									    </span>
									<?php } elseif ($v->correctResult == 1) { ?>
										<span class="correct">
										    <?php echo $k + 1 . '.';
										    echo LetterHelper::getLetter($v->answerOption); ?>
											<i class="wrong"></i>
									    </span>
									<?php } ?>
								<?php } ?>
							<?php }
						} ?>
					</p>
					<ul class="up_test_list clearfix ">
						<?php
						foreach ($answerImg as $val) { ?>
							<li>
								<a href="<?= url('teacher/managetask/new-org-correct', ['homeworkAnswerID' => $homeworkAnswerID, '#' => $val->answerImageId]) ?>">
									<img src="<?php echo ImagePathHelper::getPicUrl($val->url); ?>" alt="">
								</a>
							</li>
						<?php } ?>
						<?php
						?>
					</ul>
				</div>

			<?php } else { ?>
				<div class="digitalFile">

					<h6>答案</h6>
					<ul class="up_test_list clearfix ">
						<?php foreach ($answerInfoImg as $val) {
							?>
							<li>
								<a href="<?= url('teacher/managetask/new-pic-correct', ['homeworkAnswerID' => $homeworkAnswerID, '#' => $val->tID]) ?>">
									<img src="<?php echo ImagePathHelper::getPicUrl($val->imageUrl); ?>" alt="">
								</a>
							</li>
						<?php } ?>
					</ul>
				</div>
			<?php } ?>

		</div>
	</div>
</div>
