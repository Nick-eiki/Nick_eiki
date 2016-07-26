<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2015/6/12
 * Time: 16:30
 */
/* @var $this yii\web\View */
$this->title='消息发送历史';
?>

<script>
	$(function () {
		/*升序降序箭头切换*/
		//全选
		$('#chkAll').newCheckAll($('.unfinished_list input:checkbox'));
		$('#chkAll_s').newCheckAll($('.score_list input:checkbox'));
		$('.rattanCent_h  h4 em').toggle(function () {
			$('.tabCont .bear_children').hide();
			$(this).parents('.rattanCent_h').children('.bear_children').show();
		}, function () {
			$('.tabCont .bear_children').hide();
			$(this).parents('.rattanCent_h').children('.bear_children').hide();
		});
		$('.centH3 em').toggle(function () {
			$('.tabCont .rattanCent_h').hide();
			$(this).parents('.rattanCent_t').children('.rattanCent_h').show();
		}, function () {
			$('.tabCont .rattanCent_h').hide();
			$(this).parents('.rattanCent_t').children('.rattanCent_h').hide();
		});

		$('.show_list').click(function () {
			var everyday = $(this).find('option:selected').val();

			<?php $week = date('w');?>
			var beginDate = "<?php echo date('Y-m-d', strtotime('+'. 1-$week.'days')); ?>";
			var endDate = "<?php echo date('Y-m-d', strtotime('+'. 7-$week.'days')); ?>";
			var classId = "<?php echo app()->request->getParam('classId')?>";
			$.post('<?php echo url('teacher/count/get-message-day-list')?>', {
					beginDate: beginDate,
					endDate: endDate,
					everyday: everyday,
					classId:classId

				},
				function (data) {
					$("#message").html(data)
				}
			)
		});

		$('.date_sel').change(function () {
			var everyday = $(this).find('option:selected').val();
			//每天
			if (everyday == 1) {
				<?php $week = date('w');?>
				var beginDate = "<?php echo date('Y-m-d', strtotime('+'. 1-$week.'days')); ?>";
				var endDate = "<?php echo date('Y-m-d', strtotime('+'. 7-$week.'days')); ?>";
				var classId = "<?php echo app()->request->getParam('classId')?>";
				$.post('<?php echo url('teacher/count/get-message-day-list')?>', {
						beginDate: beginDate,
						endDate: endDate,
						everyday: everyday,
						classId:classId
					},
					function (data) {
						$("#message").html(data)
					}
				)
			}
			//本周
			if (everyday == 2) {
				<?php $week = date('w');?>
				var beginDate = "<?php echo date('Y-m-d', strtotime('+'. 1-$week.'days')); ?>";
				var endDate = "<?php echo date('Y-m-d', strtotime('+'. 7-$week.'days')); ?>";
				var classId = "<?php echo app()->request->getParam('classId')?>";
				$.post('<?php echo url('teacher/count/get-message-week-list')?>', {
						beginDate: beginDate,
						endDate: endDate,
						everyday: everyday,
						classId:classId
					},
					function (data) {
						$("#message").html(data)
					}
				)
			}
		});

		$('.last_show_list').click(function () {
			var everyday = $(this).find('option:selected').val();

			<?php $week = date('w');?>
			var beginDate = "<?php echo date('Y-m-d', strtotime('+'. 1-$week.'days')); ?>";
			var endDate = "<?php echo date('Y-m-d', strtotime('+'. 7-$week.'days')); ?>";
			var classId = "<?php echo app()->request->getParam('classId')?>";
			$.post('<?php echo url('teacher/count/get-message-last-day-list')?>', {
					beginDate: beginDate,
					endDate: endDate,
					everyday: everyday,
					classId:classId
				},
				function (data) {
					$("#last_message").html(data)
				}
			)
		});

		$('.last_date_sel').change(function () {
			var everyday = $(this).find('option:selected').val();
			//每天
			if (everyday == 1) {
				<?php $week = date('w');?>
				var beginDate = "<?php echo date('Y-m-d', strtotime('+'. 1-$week.'days')); ?>";
				var endDate = "<?php echo date('Y-m-d', strtotime('+'. 7-$week.'days')); ?>";
				var classId = "<?php echo app()->request->getParam('classId')?>";
				$.post('<?php echo url('teacher/count/get-message-lastday-list')?>', {
						beginDate: beginDate,
						endDate: endDate,
						everyday: everyday,
						classId:classId
					},
					function (data) {
						$("#last_message").html(data)
					}
				)
			}
			//本周
			if (everyday == 2) {
				<?php $week = date('w');?>
				var beginDate = "<?php echo date('Y-m-d', strtotime('+'. 1-$week.'days')); ?>";
				var endDate = "<?php echo date('Y-m-d', strtotime('+'. 7-$week.'days')); ?>";
				var classId = "<?php echo app()->request->getParam('classId')?>";
				$.post('<?php echo url('teacher/count/get-message-last-week-list')?>', {
						beginDate: beginDate,
						endDate: endDate,
						everyday: everyday,
						classId:classId
					},
					function (data) {
						$("#last_message").html(data)
					}
				)
			}
		})


	})
</script>

<!--主体-->
<div class="grid_19 main_r">
	<div class="main_cont rattanBox">
		<div class="title">
			<a onclick="window.history.go(-1)" class="txtBtn backBtn"></a>
			<h4 id="testName">消息发送历史</h4>
		</div>
		<div class="rattancent">
			<div class="tab ">
				<div class="tabCont">
					<div class="tabItem homeworkResult" style="padding:0px 20px 20px">
						<div class="rattancent_list">
							<!--展示-->

							<div class="rattancent_list">
								<!--展示-->
								<div class="rattanCent_t">
									<!--											<h3 class="clearfix centH3"><span class="fl">2014-2015学年上学期期末考试</span><em class="fr">展开<b style="margin-left: 5px;"></b></em></h3>-->
									<div class="rattanCent_h ">
										<?php $week = date('w'); ?>
										<h4 class="clearfix"><span
												class="fl"><?php echo $begin . ' - ' . $end; ?></span><em
												class="fr show_list">查看详情<b style="margin-left: 5px;"></b></em></h4>
										<!--隐藏-->
										<div class="rattanCbox bear_children hide">
											<div class="answerBigBox">
												<div class="answerBox">
													<em class="arrow" style="right:12px; top:-9px;"></em>

													<div class="answerBox_list">
														<div class="rattancent_cue clearfix date_sel">
															<span class="fl rattancent_l">作业未完成次数统计(单位：次)</span>
															<select class="fr">
																<option value="1">每天</option>
																<option value="2">本周</option>
															</select>
															</span>
														</div>
														<div id="message">

														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="rattancent_list">
								<!--展示-->

								<div class="rattancent_list">
									<!--展示-->
									<div class="rattanCent_t">
										<!--											<h3 class="clearfix centH3"><span class="fl">2014-2015学年上学期期末考试</span><em class="fr">展开<b style="margin-left: 5px;"></b></em></h3>-->
										<div class="rattanCent_h ">
											<h4 class="clearfix"><span
													class="fl"><?php echo $lastBegin . ' - ' . $lastEnd; ?></span><em
													class="fr last_show_list">查看详情<b style="margin-left: 5px;"></b></em>
											</h4>
											<!--隐藏-->
											<div class="rattanCbox bear_children hide">
												<div class="answerBigBox">
													<div class="answerBox">
														<em class="arrow" style="right:12px; top:-9px;"></em>

														<div class="answerBox_list">
															<div class="rattancent_cue clearfix last_date_sel">
																<span class="fl rattancent_l">作业未完成次数统计(单位：次)</span>
																<select class="fr">
																	<option value="1">每天</option>
																	<option value="2">本周</option>
																</select>
																</span>
															</div>
															<div id="last_message"></div>

														</div>
													</div>
												</div>
												<!--隐藏end-->
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div></div>
    </div>
