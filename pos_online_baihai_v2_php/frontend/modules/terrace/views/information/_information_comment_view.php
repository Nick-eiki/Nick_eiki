<!--评论列表-->
<div id="srchResult"  class="course_deta_list">
	<?php use frontend\components\helper\FaceHelper;
	use frontend\components\WebDataCache;

	foreach ($data as $replyVal) {  ?>
		<ul class="course_list">
			<li>
				<span class="c_title_h"><img src="<?php echo publicResources() . WebDataCache::getFaceIcon($replyVal->commentUserID);?>" alt="" data-type="header" onerror="userDefImg(this);"  height="40" width="40"></span>

				<p class="p_name">
					<em class="name"><?php echo $replyVal->commentUserName; ?></em>
					针对
					<em class="name"><?php echo $replyVal->commenTitletName; ?></em>
					发表（好评/评论）：
				</p>
				<p><?php echo FaceHelper::ReplaceFaceUrl($replyVal->commentContent); ?></p>

				<p class="btn_parent">
					<span><?php echo $replyVal->commentTime; ?> </span>
					<a href="javascript:" class="name report_comment"
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
				<div class="textareaBox hide pop">
					<textarea  id="reply_content<?php echo $replyVal->commentID?>" class="textarea checkTextarea"></textarea>
					<div class="btnArea">
						<span class="addFace expression_reply"><i class="addFaceBtn"></i>表情</span>

						<button type="button" id="reply_comment" class="sendBtn2"
								commentId="<?php echo $replyVal->commentID; ?>"
								targetUserID='<?php echo $replyVal->commentUserID; ?>'
								commentUserId="<?php echo $replyVal->commentUserID; ?>">回复
						</button>
					</div>
				</div>
				<hr>
			</li>

			<!--评论回复列表-->
			<?php foreach ($replyVal->subReplays as $commentVal) { ?>

				<li class="counter_style">
					<span class="c_title_h"><img src="<?php echo publicResources() . WebDataCache::getFaceIcon($commentVal->replayUserID);?>" alt="" data-type="header" onerror="userDefImg(this);"  height="40" width="40"></span>

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
															<a href="javascript:;" class="name na_del replay_del"
															   replayId="<?php echo $commentVal->replayID; ?>">删除</a>
														<?php } ?>
                                                    </span>
					</p>

					<div class=" textareaBox hide pop">
						<textarea id="preply_content<?php echo $commentVal->replayID; ?>" class="textarea checkTextarea"></textarea>
						<div class="btnArea">
							<span class="addFace expression_preply"><i class="addFaceBtn"></i>表情</span>
							<button type="button" id="preply_add"
									class="sendBtn2"
									preplayId="<?php echo $commentVal->replayID; ?>"
									targetUers="<?php echo $commentVal->replayUserID; ?>"
									commentId="<?php echo $replyVal->commentID; ?>">回复
							</button>
						</div>

					</div>
					<hr>
				</li>

			<?php
			}?>
		</ul>
	<?php
	} ?>

		<?php
		 echo \frontend\components\CLinkPagerExt::widget( array(
				'pagination' => $pages,
				'updateId' => '#srchResult',
				'maxButtonCount' => 3
			)
		);
		?>

</div>