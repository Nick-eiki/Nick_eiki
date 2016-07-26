<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/11/14
 * Time: 18:17
 */
/* @var $this yii\web\View */  $this->title="资讯详情";
?>

    <script>
        $(function(){
            $('h3.Signature i').editPlus();
//搜索按钮切换
            $('.terrace_btn_js span').bind('click',function(){
                $(this).addClass('s_btn').siblings('span').removeClass('s_btn');
            })
        })
    </script>
<script type="text/javascript">
    $(function(){

        $('.textareaBox_pro .sendBtn_js').live('click',function(){//发送按钮

            var teVal=$(this).parent('p').siblings('.JS_textarea2').val();
            if(teVal.length>100)
            {
                popBox.errorBox("文字已超出!");
            }
            else
            {
                $('.popBox, .mask').fadeOut(500);
                $('.popBox, .mask').remove();
            }
        });

        //生成列表
        //发表评论
        $("#comment_btn").live("click",function(){
            var informationId = $(this).attr('informationID');
			var comment = $('#comment_content' + informationId).val();
            var informationName = $(this).attr('informationName');
            if(comment == '')
            {
                popBox.errorBox("内容不能为空!");
                return false;
            }
            else
            {
                $.post('<?php echo url('ku/information/reply-information');?>',{comment:comment,informationId:informationId,informationName:informationName},function(data){
                    if(data.success){
                        location.reload();
                    }else{
                        popBox.alertBox(data.message);
                    }
                });
                $(this).parent().parent('.pop_up_js').hide();
            }
        });
//对评论进行回复
        $("#reply_comment").live("click",function(){
            var commentId = $(this).attr('commentId');
            var commentUserId = $(this).attr('commentUserId');
            var targetUserId =  $(this).attr('targetUserID');
			var replyContent = $('#reply_content' + commentId).val();
            if(replyContent == '')
            {
                popBox.errorBox("内容不能为空!");
                return false;
            }
            else
            {
                $.post('<?php echo url('ku/information/replay-comment');?>',{commentId:commentId,replyContent:replyContent,targetUserId:targetUserId},function(data){
                    if(data.success){
                        location.reload();
                    }else{
                        popBox.alertBox(data.message);
                    }
                });
                $(this).parent().parent('.pop_up_js').hide();
            }
        });
        //对回复进行回复
        $("#preply_add").live("click",function(){
            var preplayId = $(this).attr('preplayId');
            var commentId = $(this).attr('commentId');
            var targetUers = $(this).attr('targetUers');
			var replayContent = $('#preply_content' + preplayId).val();
            if(replayContent == '')
            {
                popBox.errorBox("内容不能为空!");
                return false;
            }
            else
            {
                $.post('<?php echo url('ku/information/p-replay');?>',{preplayId:preplayId,commentId:commentId,targetUers:targetUers,replayContent:replayContent},function(data){
                    if(data.success){
                        location.reload();
                    }else{
                        popBox.alertBox(data.message);
                    }
                });
                $(this).parent().parent('.pop_up_js').hide();
            }
        });
        // 删除创建出来的列表
        //删除评论
        $('.name.na_del.comment_del').live('click',function(){
            var commentId = $(this).attr('commentId');
            $.post('<?php echo url('ku/information/delete-comment');?>',{commentId:commentId},function(data){
                if(data.success){
					$('#srchResult').html(data);
                }else{
                    popBox.alertBox(data.message);
                }
            });
            $(this).parents('li').remove();
        });

        //删除回复
        $('.replay_del').live('click',function(){
            var replayId = $(this).attr('replayId');
            $.post('<?php echo url('ku/information/delete-replay');?>',{replayId:replayId},function(data){
                if(data.success){
					$('#srchResult').html(data);
                }else{
                    popBox.alertBox(data.message);
                }
            });
            $(this).parents('li').remove();
        });


        //单击小文字回复显示输入框
        $('.reply_btn').live('mousedown', function () {
            $('.textareaBox').hide();
            $(this).parents('.btn_parent').next('.textareaBox').show();
            return false;
        });

        $(document).bind("mousedown",function(e){
            var target=$(e.target);
            if(target.closest(".pop").length == 0){
                $(".pop").hide();
            }
            $('textarea:hidden').val('');
        })

    });


    //举报评论
    $(".report_comment").live('click', function(){
        var commentId = $(this).attr('commentId');

        $.post('<?php echo url('ku/information/report-comment')?>',{commentId:commentId},
            function (data){
                if(data.success){
					popBox.alertBox(data.message);
                }else{
                    popBox.alertBox(data.message);
                }
            }
        )
    });
    //举报回复

    $(".report_reply").live('click', function(){
        var replayId = $(this).attr('replayId');
        alert('举报成功');
        $.post('<?php echo url('ku/information/report-replay')?>',{replayId:replayId},
            function (data){
                if(data.success){
					popBox.alertBox(data.message);
                }else{
                    popBox.alertBox(data.message);
                }
            }
        )

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
<div class="replace">
        <div class="crumbs grid_24">
            <a href="#">首页</a>&gt;&gt;<a href="#">资讯详情</a>
        </div>
        <div class="class_c grid_24 clearfix tch">

            <div class="centLeft  grid_17" >
                <div class="notice information">
                    <div class="noticeH noticeB clearfix">
                        <h3 class="h3L">资讯详情</h3>
                        <div class="new_not fr">
                            <?php foreach ($model as $v) { ?>
                            <?php if(loginUser()->isTeacher()){ ?>
                            <a href="<?php echo url('/ku/information/add-information') ?>" class="new_bj B_btn120">发布信息</a>
                            <?php } ?>
                        </div>
                    </div>
                    <hr>

                    <div class="text_article">
                        <h4><?php echo $v->informationTitle; ?></h4>
                        <p><?php echo $v->publishTime; ?></p>
                        <div class="text_article_cent">
                            <?php echo $v->informationContent; ?>

                        </div>
                        <hr class="hr_d">
                        <div class="paet clearfix">
                            <span class="sl">
                    <?php
                    if ($nextPage->informationListSize == 0) {
                        ?>
                        <a style="color: #DDDDDD">上一篇：无</a>
                    <?php
                    } else {
                        foreach ($nextPage->informationList as $nextVal) {
                            ?>
                            <a href="<?php echo url('/ku/information/information-detail', array('informationID' => $nextVal->informationID)); ?>">上一篇：<?php echo $nextVal->informationTitle; ?></a>
                        <?php
                        }
                    } ?>
                </span>
                <span class="sr">
                    <?php
                    if ($upPage->informationListSize == 0) {
                        ?>
                        <a style="color: #DDDDDD">下一篇：无</a>
                    <?php
                    } else {
                        foreach ($upPage->informationList as $upVal) {
                            ?>
                            <a href="<?php echo url('/ku/information/information-detail', array('informationID' => $upVal->informationID)); ?>">下一篇：<?php echo $upVal->informationTitle; ?></a>
                        <?php }
                    } ?>
                </span>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="discuss">
                        <h3>大家评论</h3>
                        <hr class="hr_d">
                        <div class="course_l ">
							<div class="course_B" >
                            <div class="textareaBox ">
                                <textarea id="comment_content<?php echo $v->informationID; ?>"  class="textarea checkTextarea" ></textarea>
								<span class="placeholder">对咨询 “<?php echo $v->informationTitle; ?>” 评论</span>
								<div class="btnArea">
									<span class="addFace"><i class="addFaceBtn"></i>表情</span>
									<em class="txtCount">可以输入 <b class="num">140</b> 字</em>
                                    <button type="button" id="comment_btn" class="sendBtn" informationName = "<?php echo $v->informationTitle; ?>" informationID="<?php echo $v->informationID; ?>">回复</button>
                                </div>

                            </div>
                            <hr>
                            <!--评论列表-->
                            <?php echo $this->render('_information_comment_view', array('data' => $data, 'pages' => $pages)) ?>
                        </div>
							</div>
                    </div>
                </div>
            </div>
			<div class="centRight">
				<div class="centRightT">
					<a href="classHandsin.html" class=" outAdd_btn B_btn120">设置手拉手班级</a> </div>
				<div class="centRightT clearfix">
					<p class="title titleLeft"> <span>手拉手班级</span><i></i> </p>
					<hr>
					<dl class="list_dl clearfix">
						<dt><img src="<?php echo publicResources();?>/images/pic.png" alt="" width="90" height="90"></dt>
						<dd>
							<h3>177班</h3>
						</dd>
						<dd><span>学校：</span>北京人大附中</dd>

						<dd><span>成员：</span>30名学生</dd>
					</dl>
				</div>
				<div class="centRightT">

					<ul class="class_list clearfix">
						<li><a href="#"><img src="<?php echo publicResources();?>/images/user_s.jpg" alt="" title="北京"></a></li>
						<li><a href="#"><img src="<?php echo publicResources();?>/images/user_s.jpg" alt="" title="北京"></a></li>
						<li><a href="#"><img src="<?php echo publicResources();?>/images/user_s.jpg" alt="" title="北京"></a></li>
					</ul>
				</div>
				<div class="centRightT">
					<h3 class="clearfix">推荐视频</h3>
					<hr>
					<h4>资料名称资料名称资料名称资料名称......</h4>
					<dl class="y_list">
						<dt><a href="#"><img src="<?php echo publicResources();?>/images/teacher_m.jpg"></a></dt>
						<dd>
							<span>简介：</span>简介简介简介简介简介简介简介简介简介简介简介简介简介简介简介
						</dd>

					</dl>
					<ul class="info_list">
						<li><a href="#">资料名称资料名称料名称资料名称料名称资料名称资料名称资料名称</a></li>
						<li><a href="#">资料名称资料名称料名称资料名称料名称资料名称资料名称资料名称</a></li>
						<li><a href="#">资料名称资料名称料名称资料名称料名称资料名称资料名称资料名称</a></li>
						<li><a href="#">资料名称资料名称料名称资料名称料名称资料名称资料名称资料名称</a></li>
					</ul>
				</div>
			</div>
        </div>
</div>
<!--主体内容结束-->
