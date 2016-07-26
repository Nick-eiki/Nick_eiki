<?php
/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 2015/6/24
 * Time: 14:59
 */
use frontend\components\helper\ViewHelper;
use frontend\components\WebDataKey;
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = '教师个人中心';

$publicResources = Yii::$app->request->baseUrl;
$this->registerJsFile($publicResources . '/pub/js/My97DatePicker/WdatePicker.js');
$classInfo = loginUser()->getClassInfo();
$classid = loginUser()->classInfo[0]->classID;
/** var $this CController */
?>
<script>
	$(function () {

		/*弹窗初始化*/
		$('.popBox').dialog({
			autoOpen: false,
			width: 720,
			modal: true,
			resizable: false,
			close: function () {
				$(this).dialog("close")
			}
		});

		//快速回复
		$('.fast').live('click', function () {
			var data = $(this).attr("data-value");
			var arrinfo = data.split('|');
			var id = arrinfo[0];
			var name = arrinfo[1];
			var mailBoxId = $(this).attr("data-content-id");
			popBox.private_new_msg([{'id': id, 'name': name}], function () {
				var messageContent = $.trim($('.private_msg_Box textarea').val());
				if (messageContent == "") {
					popBox.errorBox("内容不能为空!");
					return false;
				}
				if (messageContent.length > 300) {
					popBox.errorBox("文字已超出!");
					return false;
				}
				var url = '<?= url("messagebox/send-message")?>';
				var userId = $('.popCont .sel').val();
				$.post(url, {userId: userId, messageContent: messageContent, mailBoxId: mailBoxId}, function (result) {
					if (result.success == true) {
						$('.private_msg_Box').remove();
						location.reload();
					}
				});
			}, true);

			return false;
		});

		$('.widget_select ul li a').click(function () {
			var classid = $(this).attr('data-classid');
			var url = '<?=url('/teacher/setting/homework'); ?>';
			var more_url = '<?= url('/teacher/resources/collect-work-manage');?>' + '?classid=' + classid;
			$.post(url, {classid: classid}, function (data) {
				$('#taskView').html(data);
				$('#moreHomework').attr('href', more_url);
			});
		});

		//私信
		$('.letter_btn').click(function () {
			$.post('<?php echo url('teacher/setting/letter')?>', {}, function (data) {
				$("#letter_div").html(data)
			})
		})

	})

</script>


<div class="grid_19 main_r">
	<div class="main_cont personal_center">
		<div class="title">
			<h4>个人中心</h4>

			<div class="title_r"><a href="<?= url('teacher/setting/set-head-pic'); ?>" class="btn bg_green btn40 w120">个人信息设置</a>
			</div>
		</div>
		<ul class="personal_top">
			<li class="Notice pushBtnJs">
				<a href="<?= url('teacher/message/msg-contact?show=sendwin'); ?>"><i></i>发布通知</a>
			</li>
			<li class="Upload">
				<a href="<?= url('teacher/prepare/upload-files') ?>"><i></i>上传文件</a>
			</li>
			<li class="question">
				<a href="<?= url('teacher/answer/add-question') ?>"><i></i>我要提问</a>
			</li>
		</ul>

		<!-- 我的消息 -->
		<div class="myMsg">
			<div class="title">
				<h4 class="font16">我的消息</h4>

				<div class="title_r"><a href="<?= url('teacher/message/msg-contact'); ?>"
				                        class="gray_d underline">查看更多</a></div>
			</div>
			<div class="tab">

				<ul class="tabList clearfix" style="margin-left: -35px;">
					<li><a href="javascript:;"
					       class="sysMsg <?php if (isset($msgResult->sysMsg) && $msgResult->sysMsg > 99) {
						       echo 'over99';
					       } ?>"><i></i><b><?= $msgResult->sysMsg; ?></b>系统消息</a></li>
				</ul>
				<div class="tabCont">
					<div class="tabItem ">
						<i class="arrow" style="left:25px ;"></i>
						<ul class="myMsg_notice">
							<?php
							if(empty($sysResult)) {
								ViewHelper::emptyView();
							}else{
								foreach ($sysResult as $sys) { ?>
									<li>
										<div class="title">
											<h4><?= Html::encode($sys->messageContent) ?></h4>

											<div class="title_r"> <?= $sys->sentTime; ?></div>
										</div>
										<div class="title ">
											<h4 class="font14 notice_h4">发件人：<?= $sys->sentName ?></h4>
											<?php if($sys->messageType != 507009){ ?>
											<div class="title_r notice_r">
												<a href="<?php echo url('teacher/message/is-read', array('messageID' => $sys->messageID, 'messageType' => $sys->messageType, 'objectID' => $sys->objectID)) ?>" class="btn bg_blue btn30 w120">
													<?php
													if ($sys->messageType == 507003) {
														echo '前去批改';
													} elseif ($sys->messageType == 507004) {
														echo '前去批改';
													} elseif ($sys->messageType == 507403) {
														echo '查看答题情况';
													} elseif ($sys->messageType == 507404) {
														echo '去完善';
													} elseif ($sys->messageType == 507005) {
														echo '点击查看';
													} ?>
												</a>
											</div>
											<?php } ?>
										</div>
									</li>
							<?php }} ?>
						</ul>
					</div>
					<div class="tabItem hide myMsg_letter clearfix">
						<i class="arrow" style="left: 200px;"></i>
						<i class="arrow" style="left: 200px;"></i>

						<div id="letter_div" class="msg_mine"></div>
					</div>
				</div>
			</div>
		</div>
		<!-- 我的消息  end-->
		<!-- 我的备课 -->
		<div class="myClasses">
			<div class="title">
				<h4 class="font16">我的备课</h4>

				<div class="title_r"><a href="<?= url('teacher/prepare'); ?>" class="gray_d underline">查看更多</a></div>
			</div>
			<ul>
				<li>
					<div class="myFile">
						<h4>我的文件</h4>
					</div>

					<?php
					if ($this->beginCache(WebDataKey::WEB_TEACHER_PERSONAL_CENTER_MY_FILES_CACHE_KEY . "_" . user()->id, ['duration' => 600])) {
						echo $this->render('_my_files', ['fileResult' => $fileResult]);
						$this->endCache();
					}
					?>
				</li>
				<li>
					<div class="myFile">
						<h4>我的收藏</h4>
					</div>
					<?php
					if ($this->beginCache(WebDataKey::WEB_TEACHER_PERSONAL_CENTER_MY_FAVORITE_CACHE_KEY . "_" . user()->id, ['duration' => 600])) {
						echo $this->render('_my_favorite', ['favoritesResult' => $favoritesResult]);
						$this->endCache();
					}
					?>
				</li>
			</ul>
		</div>
		<!-- 我的备课 end -->

		<!-- 我的作业 -->
		<div class="myMsg">
			<div class="title pr">
				<h4 class="font16">我的作业</h4>

				<div class="title_r">
					<a id="moreHomework" href="<?= url('/teacher/resources/collect-work-manage'); ?>" class="gray_d underline">查看更多</a>
				</div>
				<div class="widget_select">

				</div>
			</div>

			<div class="sup_box myHomework">
				<div id="taskView">
					<?php
					if($this->beginCache(WebDataKey::WEB_TEACHER_PERSONAL_CENTER_MY_HOMEWORK_CACHE_KEY . "_" . user()->id, ['duration' => 120])){
						echo $this->render('_task_view', array('homeworkList' => $homeworkList));
						$this->endCache();
					}
					?>
				</div>
			</div>
		</div>
		<!-- 我的作业 end -->

		<!-- 我的答疑 -->
		<div class="myQuestion">
			<div class="title">
				<h4 class="font16">我的答疑</h4>

				<div class="title_r"><a href="<?= url('/teacher/answer/answer-questions'); ?>" class="gray_d underline">查看更多</a>
				</div>
			</div>
			<div class="answer_questions">
				<div class="make_testpaper">
					<?php echo $this->render('//publicView/answer/_answer_list', array('modelList' => $answerResult, 'pages' => $pages)); ?>
				</div>
			</div>
		</div>
		<!-- 我的答疑 end -->

	</div>
</div>

<!--创建作业弹窗-->
<div class="popBox hide choose_classes" title="选择班级" id="choose_classes">
	<div id="classContent">

	</div>
	<div class="popBtnArea">
		<button type="button" class="okBtnHomework">确定</button>
		<button type="button" class="cancelBtn">取消</button>
	</div>
</div>
