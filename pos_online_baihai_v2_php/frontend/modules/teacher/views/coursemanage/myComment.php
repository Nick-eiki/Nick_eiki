<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/12/9
 * Time: 16:46
 */
use frontend\components\helper\FaceHelper;
use frontend\components\WebDataCache;

/* @var $this yii\web\View */  $this->title="我发表的评论";
$this->registerJsFile(publicResources() . '/js/My97DatePicker/WdatePicker.js', [ 'position'=>\yii\web\View::POS_HEAD] );

$backend_asset = publicResources();
;
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/ztree/jquery.ztree.all-3.5.min.js');

?>

<script type="text/javascript">
	$(function() {

		$('.textareaBox_pro .sendBtn_js').live('click', function () {//发送按钮
			var teVal=$(this).parent('p').siblings('.JS_textarea2').val();
			if (teVal.length > 200) {
				popBox.errorBox("文字已超出!");
			}
			else {
				$('.popBox, .mask').fadeOut(500);
				$('.popBox, .mask').remove();
			}
		});

		//举报评论
		$(".report_comment").live('click', function () {
			var commentId = $(this).attr('commentId');

			$.post('<?php echo url('teacher/coursemanage/report-comment')?>', {commentId: commentId},
				function (data) {
					if (data.success) {
						location.reload();
					} else {
						popBox.alertBox(data.message);
					}
				}
			)
		});

		//删除评论
		$('.name.na_del.comment_del').live('click', function () {
			var commentId = $(this).attr('commentId');
			$.post('<?php echo url('teacher/coursemanage/delete-comment');?>', {commentId: commentId}, function (data) {
				if (data.success) {
					location.reload();
				} else {
					popBox.alertBox(data.message);
				}
			});
			$(this).parents('li').remove();
		});
		//对评论进行回复
		$("#reply_comment").live("click", function () {
			var commentId = $(this).attr('commentId');
			var commentUserId = $(this).attr('commentUserId');
			var targetUserId = $(this).attr('targetUserID');
			var commentType = $(this).attr('commentType');
			var replyContent = $('#reply_content' + commentId).val();
			if (replyContent == '') {
				popBox.errorBox("内容不能为空!");
				return false;
			}
			else {
				$.post('<?php echo url('teacher/coursemanage/replay-comment');?>', {
					commentId: commentId,
					replyContent: replyContent,
					targetUserId: targetUserId,
					commentType:commentType
				}, function (data) {
					if (data.success) {
						location.reload();
					} else {
						popBox.alertBox(data.message);
					}
				});
				$(this).parent().parent('.pop_up_js').hide();
			}
		});
		//对回复进行回复
		$("#preply_add").live("click", function () {
			var preplayId = $(this).attr('preplayId');
			var commentId = $(this).attr('commentId');
			var targetUers = $(this).attr('targetUers');
			var commentType = $("#reply_comment").attr('commentType');
			var replayContent = $('#preply_content' + preplayId).val();
			if (replayContent == '') {
				popBox.errorBox("内容不能为空!");
				return false;
			}
			else {
				$.post('<?php echo url('teacher/coursemanage/p-replay');?>', {
					preplayId: preplayId,
					commentId: commentId,
					targetUers: targetUers,
					replayContent: replayContent,
					commentType:commentType
				}, function (data) {
					if (data.success) {
						location.reload();
					} else {
						popBox.alertBox(data.message);
					}
				});
				$(this).parent().parent('.pop_up_js').hide();
			}
		});
		// 删除创建出来的列表

		//删除回复
		$('.replay_del').live('click', function () {
			var replayId = $(this).attr('replayId');
			$.post('<?php echo url('teacher/coursemanage/delete-replay');?>', {replayId: replayId}, function (data) {
				if (data.success) {
					location.reload();
				} else {
					popBox.alertBox(data.message);
				}
			});
			$(this).parents('li').remove();
		});

		//举报回复
		$(".report_reply").live('click', function () {
			var replayId = $(this).attr('replayId');
			$.post('<?php echo url('teacher/coursemanage/report-replay')?>', {replayId: replayId},
				function (data) {

					if (data.success) {
						location.reload();
					} else {
						popBox.alertBox(data.message);
					}
				}
			)

		});


		// 删除创建出来的列表
		$('.na_del').live('click', function () {
			$(this).parents('li').remove();
		});

		//单击小文字回复显示输入框
		$('.reply_btn').live('mousedown', function () {
			$('.textareaBox').hide();
			$(this).parents('.btn_parent').next('.textareaBox').show();
			return false;
		});

		$(document).bind("mousedown", function (e) {
			var target = $(e.target);
			if (target.closest(".pop").length == 0) {
				$(".pop").hide();
			}
			$('textarea:hidden').val('');
		})
	})

</script>

<script type="text/javascript">
	$(document).ready(function(){
		$('.textareaBox_pro .JS_textarea2').each(function(index, element) {
			$(this).charCount({
				allowed: 140,
				warning: 10,
				counterText: '剩余字数: '
			});
		});
	});
</script>
<!--主体内容开始-->
<div class="currentRight  currentRight_new  grid_16 push_2">
	<div class="notice information">
		<div class="noticeH noticeB clearfix">
			<h3 class="h3L">我发表的评论</h3>

		</div>
		<hr>

		<div class="discuss">

			<div class="course_l ">
				<div class="course_B" >
					<!--评论列表-->
					<div  class="course_deta_list">
						<?php foreach ($commentList as $replyVal) { ?>
						<ul class="course_list">
							<li>
								<span class="c_title_h"><img src="<?php echo publicResources() . user()->getUserInfo($replyVal->commentUserID)->getFaceIcon();?> " alt="" data-type="header" onerror="userDefImg(this);" height="40" width="40"></span>

								<p class="p_name">
									<em class="name">
										<?php
										if ($replyVal->commentUserID == user()->id) {
											echo "我";
										} else {
											echo $replyVal->commentUserName;
										}
										?>
									</em>对<em
										class="name"><?php echo $replyVal->informationName; ?></em>的回复</p>

								<p class="content_expression" content="<?php echo $replyVal->commentContent; ?>">
									<?php echo FaceHelper::ReplaceFaceUrl($replyVal->commentContent); ?>
								</p>

								<p class="btn_parent">
									<span><?php echo $replyVal->commentTime; ?> </span>
									<a href="javascript:;" class="name report_comment"
									   commentId="<?php echo $replyVal->commentID; ?>">举报</a>
                                                <span class="span_link">
                                                    <a href="javascript:;" class="name reply_btn">回复</a>
													<?php
													if (user()->id == $replyVal->commentUserID) {
														?>
														<a href="javascript:" class="name na_del comment_del"
														   commentId="<?php echo $replyVal->commentID; ?>">删除</a>
													<?php } ?>
                                                </span>
								</p>

								<!--对评论回复-->
								<div class=" textareaBox hide pop">
									<textarea id="reply_content<?php echo $replyVal->commentID?>" class="textarea checkTextarea"></textarea>

									<div class="btnArea">
										<span class="addFace expression_reply"><i class="addFaceBtn"></i>表情</span>
										<em class="txtCount">可以输入 <b class="num">140</b> 字</em>
										<button type="button" id="reply_comment" class="btn20 bg_gray sendBtn_js"
												commentId="<?php echo $replyVal->commentID; ?>"
												targetUserID='<?php echo $replyVal->commentUserID; ?>'
												commentUserId="<?php echo $replyVal->commentUserID; ?>"
												commentType="<?php echo $replyVal->commentType; ?>">回复
										</button>
									</div>
								</div>
								<hr>
							</li>
							<!--回复列表-->
							<?php foreach ($replyVal->subReplays as $commentVal) { ?>

								<li class="counter_style">
									<span class="c_title_h"><img src="<?php echo publicResources() . WebDataCache::getFaceIcon($commentVal->replayUserID);?>" alt="" data-type="header" height="40" width="40"></span>

									<p class="p_name">
										<em class="name">
											<?php
											if ($commentVal->replayUserID == user()->id) {
												echo "我";
											} else {
												echo $commentVal->replayUserName;
											}
											?>
										</em>对<em class="name">
											<?php
											if ($commentVal->replayTargetUserID == user()->id) {
												echo "我";
											} else {
												echo $commentVal->replayTargetUserName;
											}
											?>
										</em>回复：
									</p>

									<p><?php echo FaceHelper::ReplaceFaceUrl($commentVal->replayContent); ?></p>

									<p class="btn_parent">
										<span><?php echo $commentVal->replayTime ?></span>
										<a href="javascript:;" class="name report_reply"
										   replayId="<?php echo $commentVal->replayID; ?>">举报</a>
                                                    <span class="span_link">
                                                        <a href="javascript:;" class="name reply_btn">回复</a>
														<?php if ($commentVal->replayUserID == user()->id) { ?>
															<a href="javascript:" class="name na_del replay_del"
															   replayId="<?php echo $commentVal->replayID; ?>">删除</a>
														<?php } ?>
                                                    </span>
									</p>

									<div class="textareaBox hide pop">
										<textarea id="preply_content<?php echo $commentVal->replayID; ?>" class="textarea checkTextarea"></textarea>

										<div class="btnArea">
											<span class="addFace expression_preply"><i class="addFaceBtn"></i>表情</span>
											<em class="txtCount">可以输入 <b class="num">140</b> 字</em>
											<button type="button" id="preply_add"
													class="btn20 bg_gray sendBtn_js  preply_add"
													preplayId="<?php echo $commentVal->replayID; ?>"
													targetUers="<?php echo $commentVal->replayUserID; ?>"
													commentId="<?php echo $replyVal->commentID; ?>">回复
											</button>
										</div>
									</div>
									<hr>
								</li>
								<?php }?>
							</ul>
							<?php } ?>
					</div>

						<?php
						 echo \frontend\components\CLinkPagerExt::widget( array(
								'pages' => $pages,
								//'updateId' => '#srchResult',
								'maxButtonCount' => 5
							)
						);
						?>
				</div>

			</div>
		</div>
	</div>


	<!--主体内容结束-->


	<!--弹出框pop--------------------->


