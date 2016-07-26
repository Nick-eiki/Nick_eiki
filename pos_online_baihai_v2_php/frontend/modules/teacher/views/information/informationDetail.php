<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/11/14
 * Time: 18:17
 */
/* @var $this yii\web\View */  $this->title="资讯详情";
?>

<script type="text/javascript">
$(function () {

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

    //发表评论
    $("#comment_btn").live("click", function () {
        var informationId = $(this).attr('informationID');
        var comment = $('#comment_content' + informationId).val();
        var informationName = $(this).attr('informationName');
        if (comment == '') {
            popBox.errorBox("内容不能为空!");
            return false;
        }
        else {
            $.post('<?php echo url('ku/information/reply-information');?>', {
                comment: comment,
                informationId: informationId,
                informationName: informationName
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

    //举报评论
    $(".report_comment").live('click', function () {
        var commentId = $(this).attr('commentId');

        $.post('<?php echo url('ku/information/report-comment')?>', {commentId: commentId},
            function (data) {
                if (data.success) {
					popBox.alertBox(data.message);
                } else {
                    popBox.alertBox(data.message);
                }
            }
        )
    });

    //删除评论
    $('.name.na_del.comment_del').live('click', function () {
        var commentId = $(this).attr('commentId');
        $.post('<?php echo url('ku/information/delete-comment');?>', {commentId: commentId}, function (data) {
            if (data.success) {
				$('#srchResult').html(data);
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

        var replyContent = $('#reply_content' + commentId).val();
        if (replyContent == '') {
            popBox.errorBox("内容不能为空!");
            return false;
        }
        else {
            $.post('<?php echo url('ku/information/replay-comment');?>', {
                commentId: commentId,
                replyContent: replyContent,
                targetUserId: targetUserId,
                commentUserId: commentUserId
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

        var replayContent = $('#preply_content' + preplayId).val();
        if (replayContent == '') {
            popBox.errorBox("内容不能为空!");
            return false;
        }
        else {
            $.post('<?php echo url('ku/information/p-replay');?>', {
                preplayId: preplayId,
                commentId: commentId,
                targetUers: targetUers,
                replayContent: replayContent
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
        $.post('<?php echo url('ku/information/delete-replay');?>', {replayId: replayId}, function (data) {
            if (data.success) {
				$('#srchResult').html(data);
            } else {
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

    $(document).bind("mousedown", function (e) {
        var target = $(e.target);
        if (target.closest(".pop").length == 0) {
            $(".pop").hide();
        }
        $('textarea:hidden').val('');
    })

});

//举报回复
$(".report_reply").live('click', function () {
    var replayId = $(this).attr('replayId');
    alert('举报成功');
    $.post('<?php echo url('ku/information/report-replay')?>', {replayId: replayId},
        function (data) {

            if (data.success) {
				popBox.alertBox(data.message);
            } else {
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
<div class="currentRight grid_16 push_2 make_testpaper">
    <div class="notice information">
        <div class="noticeH noticeB clearfix">
            <h3 class="h3L">资讯详情</h3>

            <div class="new_not fr">

                <a href="<?php echo url('/teacher/information/add-information') ?>" class="new_bj B_btn120">发布信息</a>
            </div>
        </div>
        <hr>
        <?php foreach ($model as $v) { ?>
        <div class="text_article" id="paging">
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
                        <a href="#">上一篇：无</a>
                    <?php
                    } else {
                        foreach ($nextPage->informationList as $nextVal) {
                            ?>
                            <a href="<?php echo url('/teacher/information/information-detail', array('informationID' => $nextVal->informationID)); ?>">上一篇：<?php echo $nextVal->informationTitle; ?></a>
                        <?php
                        }
                    } ?>
                </span>
                <span class="sr">
                    <?php
                    if ($upPage->informationListSize == 0) {
                        ?>
                        <a href="#">下一篇：无</a>
                    <?php
                    } else {
                        foreach ($upPage->informationList as $upVal) {
                            ?>
                            <a href="<?php echo url('/teacher/information/information-detail', array('informationID' => $upVal->informationID)); ?>">下一篇：<?php echo $upVal->informationTitle; ?></a>
                        <?php }
                    } ?>
                </span>
            </div>
        </div>

        <div class="discuss">
            <h3>大家评论</h3>
            <hr class="hr_d">
            <div class="course_l ">
				<div class="course_B" >
                <div class="textareaBox">
                    <textarea  id="comment_content<?php echo $v->informationID; ?>"  class="textarea checkTextarea"></textarea>
					<span class="placeholder">对咨询 “<?php echo $v->informationTitle; ?>” 评论</span>
					<div class="btnArea">
						<span class="addFace"><i class="addFaceBtn"></i>表情</span>
						<em class="txtCount">可以输入 <b class="num">140</b> 字</em>
                        <button type="button" id="comment_btn" class="sendBtn" informationName="<?php echo $v->informationTitle; ?>" informationID="<?php echo $v->informationID; ?>">回复
                        </button>
                    </div>

                </div>
                <hr>

                <?php echo $this->render('_information_comment_view', array('data' => $data, 'pages' => $pages)) ?>

            </div>
				</div>
        </div>
    </div>
    <?php } ?>
</div>
<!--主体内容结束-->
