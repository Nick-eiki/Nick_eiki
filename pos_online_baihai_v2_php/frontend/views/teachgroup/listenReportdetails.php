<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2015/7/27
 * Time: 14:47
 */
use frontend\components\helper\StringHelper;
use frontend\components\WebDataCache;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var  common\models\pos\SeGroupLecturePlanReport $detailsModel */
/** @var  common\models\pos\SeGroupLecturePlanReport $up */
/** @var  common\models\pos\SeGroupLecturePlanReport $next */
/** @var int $groupId */

$this->title="报告详情";
?>
		<div class="grid_16 alpha omega main_l">
			<div class="main_cont report_details">

				<div class="title">
                    <a href="<?php
                    echo url::to(['listen-lessons','groupId'=>$groupId])?>" class="txtBtn backBtn"></a>
					<h4>报告详情</h4>
				</div>
				<div>
					<div class="grade">

                        <h4><?php
                            echo Html::encode($detailsModel->reportTitle); ?></h4>
						<p>作者：<?php echo WebDataCache::getTrueName($detailsModel->userID);; ?></p>
						<div class="article">
							<p><?php echo StringHelper::htmlPurifier($detailsModel->reportContent); ?></p>

						</div>
					</div>
					<div class="flip">
						<div class="title noBorder">
							<span> 上一篇：
							<?php if(empty($up)){ ?>
								没有了
							<?php }else{ ?>
								 <a href="<?php echo Url::to(['listen-report-details','groupId'=>$groupId,'lecturePlanReportId'=>$up->lecturePlanReportId,'lecturePlanID'=>$detailsModel->lecturePlanID])?>"><?php echo $up->reportTitle; ?></a>
							<?php } ?>
								</span>
							<div class="title_r"> 下一篇：
								<?php if(empty($next)){?>
									没有了
								<?php }else{ ?>
									<a href="<?php echo Url::to(['listen-report-details','groupId'=>$groupId,'lecturePlanReportId'=>$next->lecturePlanReportId,'lecturePlanID'=>$detailsModel->lecturePlanID])?>"><?php echo $next->reportTitle; ?></a>
								<?php } ?>

							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
<!--主体end-->
<div id="popBox" class="popBox popBox_hand hide" title="手拉手班级申请">
	<!--完成答题-->
	<div class="impBox">
		<h6 class="font16">申请成为 <span>北京市 海淀区&nbsp;&nbsp;&nbsp;&nbsp;人大附中</span></h6>
		<p style="text-align:center" class="font16">班级名称</p>
		<div class="font16" style="color:#777;">的手拉手班级吗？</div>

	</div>

	<div class="popBtnArea">
		<button type="button" class="okBtn">申请</button>
		<button type="button" class="cancelBtn">取消</button>
	</div>

</div>
