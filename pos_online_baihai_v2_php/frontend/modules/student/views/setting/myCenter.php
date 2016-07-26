<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2015/6/24
 * Time: 14:00
 */
use frontend\components\helper\ViewHelper;
use frontend\models\dicmodels\SubjectModel;

/* @var $this yii\web\View */  $this->title='个人中心';
$this->registerCssFile(publicResources_new() . '/js/fancyBox/jquery.fancybox.css'.RESOURCES_VER);
$this->registerJsFile(publicResources_new() . "/js/fancyBox/jquery.fancybox.js".RESOURCES_VER, [ 'position'=>\yii\web\View::POS_HEAD] );
?>
	<script type="text/javascript">
		$(function() {

			$(".fancybox").fancybox();
			/*删除图标显示和隐藏*/
			$('.notice_list li').live('mouseover', function() {
				$(this).children('.crossDelBtn').removeClass('hide');
				$(this).children('.notice_send_btn').addClass('bg_blue_d');
				$(this).addClass('bg_gray_ll');
			});
			$('.notice_list li').live('mouseout', function() {
				$(this).children('.crossDelBtn').addClass('hide');
				$(this).children('.notice_send_btn').removeClass('bg_blue_d');
				$(this).removeClass('bg_gray_ll');
			});
			/*弹窗初始化*/
			$('.popBox').dialog({
				autoOpen: false,
				width: 720,
				modal: true,
				resizable: false,
				close: function() {
					$(this).dialog("close")
				}
			});


			//系统消息
			$('.notes_btn').click(function(){
				$.post('<?php echo url('student/setting/my-center-notice')?>',{

				}, function (data) {
					$("#notice_div").html(data)
				})
			});

			//系统消息
			$('.sys_msg_btn').click(function(){
				$.post('<?php echo url('student/setting/my-center-sys-msg')?>',{

				}, function (data) {
					$("#sys_msg").html(data)
				})
			});

			//私信
			$('.letter_btn').click(function(){
				$.post('<?php echo url('student/setting/letter')?>',{

				}, function (data) {
					$("#letter_div").html(data)
				})
			});
			//快速回复
			$('.fast').live('click',function () {
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
							$.post('<?php echo url('student/setting/letter')?>',{ }, function (data) {
								$("#letter_div").html(data)
							})
						}
					});
				},true);

				return false;
			});

			//我要提问
			$('.my_quiz').click(function(){
				$('#my_question').dialog({
					autoOpen: false,
					width:400,
					modal: true,
					resizable:false,
					buttons: [
						{
							text: "前去检索",

							click: function() {
								if($('#mySchoolPop .text').val()==1){
									$( this ).dialog( "close" );
								}
								else{
									location.href = '<?php echo url('/terrace/answer/answer-questions-list');?>';
								}
							}
						},
						{
							text: "我要提问",

							click: function() {
								if($('#mySchoolPop .text').val()==1){
									$( this ).dialog( "close" );
								}
								else{
									if('<?php echo loginUser()->isStudent();?>'){
										location.href = '<?php echo url('student/answer/add-question',array('studentId'=>app()->request->getQueryParam('studentId','')));?>';
									}else if('<?php echo loginUser()->isTeacher();?>'){
										location.href = '<?php echo url('teacher/answer/add-question',array('teacherId'=>app()->request->getQueryParam('teacherId','')))?>';
									}
								}
							}
						}
					]
				});
				$( "#my_question" ).dialog( "open" );
				return false;
			});
			$('.red_btn_js2').die().live('click', function () {
				var aqid = $(this).attr('val');
				var answer = $(".textarea_js" + aqid).val();
				if (answer == '') {
					popBox.errorBox("内容不能为空!");
					return false;
				}
				if (answer.length > 1001) {
					popBox.alertBox('超过1000字数限制，请重新编辑！');
					return false;
				}
				else {

					$.post('<?php echo url('answer/result-question');?>', {answer: answer, aqid: aqid}, function (data) {
						if (data.success) {
							popBox.successBox('回答成功');
							$.post('<?php echo url('answer/answer-detail');?>', {aqid: aqid}, function (datas) {
								$('.answer_detail' + aqid).html(datas);
							});
							$(this).parents('.test_BoxBtn').siblings('.answerBigBox ').children('.answerW').show();
							$(this).parents('.test_BoxBtn').siblings('.answerBigBox ').children('.answerM').hide();
						} else {
							popBox.alertBox(data.message);
						}
					});
					$(this).parent().parent('.pop_up_js').hide();
				}
			});

			/*增加同问的数字*/
			$('.q_add').die().live('click', function () {
				var aqid = $(this).attr('val');
				var creatorid = $(this).attr('user');
				var userid = "<?php echo user()->id;?>";
				if (creatorid == userid) {
					return false;
				} else {
					var aqid = $(this).attr('val');
					$.post('<?php echo url('answer/aame-question');?>', {aqid: aqid}, function (data) {
						if (data.success) {
							$.post('<?php echo url('answer/answer-detail');?>', {aqid: aqid}, function (datas) {
								$('.answer_detail' + aqid).html(datas);
							});
						} else {
							popBox.alertBox(data.message);
						}
					})
				}
			});

			/*点击采用变成已采用*/
			$('.adopt_btn').die().live('click', function () {

				$(this).removeClass('put');
				$(this).text('最佳答案');

				var aqid = $(this).attr('u');
				var resultid = $(this).attr('val');
				$.post('<?php echo url('answer/use-the-answer');?>', {resultid: resultid}, function (data) {
					if (data.success) {
						$.post('<?php echo url('answer/answer-detail');?>', {aqid: aqid}, function (datas) {
							$('.answer_detail' + aqid).html(datas);
						});
					}
				})
			});

			$('.reply').die().live('click', function () {
				$(this).parents('.test_BoxBtn').siblings('.answerBigBox ').children('.answerM').show();
				$(this).parents('.test_BoxBtn').siblings('.answerBigBox ').children('.answerW').hide();
			});

			$('.quiz').die().live('one', 'click', function () {
				var oText = $(this).children('em');
				var num = oText.text();
				num++;
				oText.text('').append(num);
				//$(this).children('i').
			});

			$('.area_closeJs').die().live('click', function () {
				$(this).parents('.answerM').hide();
				$(this).parents('.answerM').reset();
			});

			//我的作业 科目标签
			$('.task_sel li').bind('click',function(){
				var type= $(this).attr('type');
				$.get('<?= url('/student/setting/work-manage');?>',{type:type},function(data){
					$('#task_list').html(data);
				})
			});
			//错题集 科目标签
			$('.wrong_sel li').bind('click',function(){
				var type= $(this).attr('type');
				$.get('<?= url('/student/setting/wro-top-for-item');?>',{type:type},function(data){
					$('#wrong_list').html(data);
				})
			})
		})
	</script>

		<div class="grid_19 main_r">
			<div class="main_cont personal_center">
				<div class="title">
					<h4>个人中心</h4>
					<div class="title_r">
						<a href="<?php echo url('student/setting/set-head-pic')?>" class="btn bg_green btn40 w120">个人信息设置</a>
					</div>
				</div>
<!--				<ul class="personal_top">-->
<!--					<li class="Photograph" style="margin-left: 235px;"><a href="--><?php //echo url('student/wrongtopic/take-photo-topic')?><!--"><i></i>拍照录题</a> </li>-->
<!--					<li class="question" style="margin-left: 200px;"><a href="javascript:;" class="my_quiz"><i></i>我要提问</a> </li>-->
<!--				</ul>-->
				<!-- 我的消息 -->
				<div class="myMsg">
					<div class="title">
						<h4 class="font16">我的消息</h4>
						<div class="title_r">
							<a href="<?php echo url('student/message/notice')?>" class="gray_d underline">查看更多</a>
						</div>
					</div>

					<div class="tab">
						<ul class="tabList clearfix" style="margin-left: -35px;">
							<li>
								<a href="javascript:;" class="notes <?php if($resultNum->notice>99){echo 'over99';}?> notes_btn"><i></i><b><?php echo $resultNum->notice; ?></b>通知</a>
							</li>
							<li>
								<a href="javascript:;" class="sysMsg <?php if($resultNum->sysMsg>99){echo 'over99';}?> sys_msg_btn"><i></i><b><?php echo $resultNum->sysMsg; ?></b>系统消息</a>
							</li>
							<li>
<!--								<a href="javascript:;" class="letter --><?php //if($resultNum->priMsg>99){echo 'over99';}?><!-- letter_btn"><i></i><b>--><?php //echo $resultNum->priMsg; ?><!--</b>私信</a>-->
							</li>
						</ul>
						<div class="tabCont">
<!--							 通知-->
							<div class="tabItem">
								<i class="arrow" style="left: 25px;"></i>
								<div id="notice_div">
								<?php echo $this->render('_my_center_notice',['noticeResult'=>$noticeResult]);?>
								</div>
							</div>
<!--							 系统消息 -->
							<div class="tabItem hide">
								<i class="arrow" style="left:170px ;"></i>
								<div id="sys_msg"></div>
							</div>
						</div>
					</div>
				</div>
				<!-- 我的消息  end-->
				<!-- 我的作业 -->
				<div class="myMsg">
					<div class="title" style="position: relative;">
						<h4 class="font16">我的作业</h4>

						<div class="title_r">
							<a href="<?php echo url('student/managetask/work-manage',['classid'=>$classId])?>" class="gray_d underline">查看更多</a>
						</div>
						<div class="widget_select task_sel">
							<?php
							$subject = SubjectModel::getSubjectByDepartmentCache(loginUser()->getModel(false)->department,1);
							?>
							<h6><span>所有</span><i></i></h6>
							<ul>
								<?php foreach($subject as $val){ ?>
								<li type="<?=$val->secondCode; ?>"><a href="javascript:;"><?=$val->secondCodeValue; ?></a></li>
								<?php } ?>
							</ul>
						</div>
					</div>
					<div id="task_list">
					<?php
					if($this->beginCache(\frontend\components\WebDataKey::WEB_STUDENT_MY_CENTER_MY_HOMEWORK_CACHE_KEY . '_' . user()->id, ['duration'=>600])) {
						echo $this->render('_my_center_task', ["taskResult" => $taskResult, 'studentMember' => $studentMember,]);
						$this->endCache();
					} ?>
					</div>
				</div>
				<!-- 我的作业 end -->

				<!-- 我的答疑 -->
				<div class="myQuestion">
					<div class="title">
						<h4 class="font16">我的答疑</h4>
						<div class="title_r">
							<a href="<?php echo url('student/answer/answer-questions')?>" class="gray_d underline">查看更多</a>
						</div>
					</div>

					<div class="answer_questions">
					<div class="make_testpaper">
						<?php
						if(empty($answerResult)) {
							ViewHelper::emptyView();
						}
						echo $this->render('//publicView/answer/_answer_list', ['modelList' => $answerResult, 'pages' => $pages, 'val' => $val]);
					?>
					</div>
					</div>
				</div>
				<!-- 我的答疑 end -->

				<!-- 我的错题集 -->
				<div class="myQuestion">
					<div class="title"  style="position: relative;">
						<h4 class="font16">我的错题集</h4>
						<div class="title_r">
							<a href="<?php echo url('student/wrongtopic/manage')?>" class="gray_d underline">查看更多</a>
						</div>
						<div class="widget_select wrong_sel" style="margin-left: 10px;">
							<h6><span>所有</span><i></i></h6>
							<ul>
								<?php foreach($subject as $val){ ?>
									<li type="<?=$val->secondCode; ?>"><a href="javascript:;"><?=$val->secondCodeValue; ?></a></li>
								<?php } ?>
							</ul>
						</div>
					</div>
					<div id="wrong_list">
                        <?php echo $this->render('//publicView/wrong/_wrong_question_list',['wrongQuestion'=>$testQuestion,'pages' => $pages])?>
					</div>
				</div>
				<!-- 我的错题集 end -->

			</div>
		</div>
		<!--主体end-->

<!--新增加我要提问弹窗-->
<div id="my_question" class="my_question popoBox hide " title="答疑管理">
	<div class="impBox">
		<form>
			<div class="answer_text" style="text-align:center; line-height: 55px;">
				请先看一下是否已有相同问题
			</div>

		</form>
	</div>
</div>