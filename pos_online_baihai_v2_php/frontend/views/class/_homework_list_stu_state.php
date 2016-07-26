<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2016/1/13
 * Time: 14:26
 */
use common\helper\DateTimeHelper;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<?php
$isTop = true;
/** @var common\models\pos\SeHomeworkRel[] $newHomeworkGVal */
foreach ($newHomeworkGVal as $key => $val) {
	$isUploadAnswer = $val->getHomeworkAnswerInfo()->where(['studentID' => user()->id, 'isUploadAnswer' => '1'])->exists();
	//作业详情
	$homeworkInfo = $val->getHomeWorkTeacher()->one();

	//查询已答数
	$answer = $val->homeworkAnswerInfoCountCache();
	if (!empty($homeworkInfo)) { ?>
		<li class="clearfix <?php if ($isTop) { echo 'work_notop'; $isTop = false; } ?>">
			<em class="num"><?php echo date('d', DateTimeHelper::timestampDiv1000($val->createTime)); ?></em>

			<div class="work_left">
				<div class="work_top clearfix">
									<span class="work_img <?php if ($homeworkInfo->getType == 0) {
										echo 'picture';
									} elseif ($homeworkInfo->getType == 1) {
										echo 'word';
									} ?>"></span>

					<div class="work_info">
						<p class="work_title" title="<?php echo Html::encode($homeworkInfo->name); ?>">
							<?php echo Html::encode($homeworkInfo->name); ?>
						</p>

						<p>
							截止：<?php echo date('Y年m月d日', DateTimeHelper::timestampDiv1000($val->deadlineTime)); ?>
						</p>
					</div>
				</div>
				<div class="work_bottom clearfix">
					<ul class="progress">
						<li class="clearfix">
							<span>提交：</span>

							<div class="progress_outer">
								<div class="progress_inner" style="width: <?php
								if (isset($studentMember) && $studentMember != 0) {
									echo ($answer / $studentMember) * 100;
								} else {
									echo '0';
								}
								?>%"></div>
							</div>
							<label><?php echo $answer . "/" . $studentMember; ?></label>
						</li>
					</ul>
				</div>
			</div>

							<span class="<?php if ($isUploadAnswer) {
								echo "complete_ico";
							} elseif (!$isUploadAnswer) {
								echo "not_complete_ico";
							} ?>"></span>

			<div class="work_right">
				<img src="<?= url('qrcode/zy/' . $val->id) ?>" class="qr" alt="" width="102"
				     height="102">

				<div class="oper">
                    <?php

                    if (!$isUploadAnswer) {
                        ?>
                        <a href="<?php echo Url::to(['/classes/managetask/details', 'classId'=>$classId ,'relId' => $val->id]);?>"
                           relId="<?= $val->id ?>"
                           class="btn bg_white btn40 icoBtn_book bookBtn"><i></i>写作业</a>
                        <a href="javascript:;" class="btn bg_white btn40 icoBtn_result resultBtn btn_disable"><i></i>看结果</a>
                    <?php } elseif ($isUploadAnswer) {
                        ?>
                        <a href="javascript:;"
                           class="btn bg_white btn40 icoBtn_book bookBtn btn_disable"><i></i>写作业</a>
                        <a href="<?php echo Url::to(['/classes/managetask/details', 'classId'=>$classId ,'relId' => $val->id]);?>"
                           class="btn bg_white btn40 icoBtn_result resultBtn"><i></i>看结果</a>
                    <?php } ?>


				</div>
			</div>
		</li>
	<?php
	}
} ?>
