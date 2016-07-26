<?php

/* @var $this yii\web\View */  $this->title="课程详情";
$this->registerJsFile(publicResources() . '/js/My97DatePicker/WdatePicker.js', [ 'position'=>\yii\web\View::POS_HEAD] );

$backend_asset = publicResources();
;
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/ztree/jquery.ztree.all-3.5.min.js');

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
            $.post('<?php echo url('teacher/coursemanage/reply-information');?>', {
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

        $.post('<?php echo url('teacher/coursemanage/report-comment')?>', {commentId: commentId},
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
        $.post('<?php echo url('teacher/coursemanage/delete-comment');?>', {commentId: commentId}, function (data) {
            if (data.success) {
				$("#srchResult").html(data);
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
        var commentType = 50402;
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
                commentType: commentType
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
        var commentType = 50402;
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
                commentType: commentType
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
				$("#srchResult").html(data);
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
					popBox.alertBox(data.message);
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
    });

    //添加课时----使用讲义弹框
    $('.addDocBtn').live('click', function () {

        var doc = "";
        var _this = $(this);

        var gId = $(this).attr('gId');
        var subId = $(this).attr('subId');
        var materials = $(this).attr('materials');
        $.post("<?php echo url('teacher/coursemanage/get-handouts')?>",{gId:gId,subId:subId,materials:materials}, function (data) {
            $('#updatehandout').html(data);
            $("#DocBox").dialog("open");
            $('#DocBox ul li').removeClass('ac').live('click', function () {
                $(this).addClass('ac').siblings().removeClass('ac');
                doc = $(this).clone();
            })
        });
        $('#DocBox').dialog({
            autoOpen: false,
            width: 500,
            modal: true,
            resizable: false,
            buttons: [
                {
                    text: "确定",
                    click: function () {
                        _this.siblings('.DocList').empty().append(doc);
                        _this.text('修改讲义');
                        _this.nextUntil('.addHour').val(doc.attr('handout'));
                        $(this).dialog('close');
                    }
                },
                {
                    text: "取消",
                    click: function () {
                        $(this).dialog('close');
                    }
                }
            ]
        });
    });
    //上传讲义
    $('.ui-button').live('click', function () {
        var handoutId = $('#DocList').val();
        var courseId = $('#DocList').attr('courseId');
        $.post('<?php echo url('teacher/coursemanage/upload-handouts')?>', {handoutId: handoutId, courseId: courseId},
            function (data) {
                if (data.success) {
                    location.reload();
                } else {
                    popBox.alertBox(data.message);
                }
            }
        )
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

    //收藏讲义
    $('.favorite').live('click', function () {
        var favoriteId = $(this).attr('favoriteId');
        $.post('<?php echo url('teacher/coursemanage/collect')?>', {favoriteId: favoriteId},
            function (data) {
                if (data.success) {
                    location.reload();
                } else {
                    popBox.alertBox(data.message);
                }
            }
        )
    });

    //取消收藏讲义
    $('.undo_collect').live('click', function () {
        var collect = $(this).attr('collect');
        $.post('<?php echo url('teacher/coursemanage/undo-collect')?>', {collect: collect},
            function (data) {
                if (data.success) {
                    location.reload();
                } else {
                    popBox.alertBox(data.message);
                }
            }
        )
    })


</script>
<style>
    .pointer {
        course: pointer
    }
</style>
<!--主体内容开始-->
<div class="currentRight grid_16 push_2 course_deta">
<div class="noticeH clearfix noticeB">
    <h3 class="h3L">课程详情</h3>
</div>
<hr>
<div class="course_deta_title">
<div class="title clearfix">
    <h4><?php echo $model->courseName; ?></h4>
                    <span>
                    <?php if($model->creatorID == user()->id){?>
                    	<a  href="<?php echo url('/video/video')?>" class="a_button bg_blue_l btn_bj" target="_blank">去上课</a>
                    	<a href="<?php echo url('teacher/coursemanage/update-course',array('courseId'=>$model->courseID))?>" class="a_button bg_blue_l btn_bj">修改课程设置</a>
                        <?php }else{
                            echo '';
                        }?>
                    </span>

    <div class="btn_box">
        <!--上传完成按钮变成修改讲义-->
        <?php
        if (empty($handoutName)) {
            ?>
            <ul class="DocList fl clickDoc" style="color: red"></ul>
            <button type="button" id="handoutID" gId='<?php echo $model->gradeID;?>' subId="<?php echo $model->subjectID?>" materials="<?php echo $model->versionID; ?>" class="fl bg_green_l addDocBtn">使用讲义</button>'
        <?php
        } else { ?>
                <ul class="DocList fl" style="color: red">
                    <li>
                        <a style="color: red" href="<?php echo url('teacher/coursemanage/handout-details', array('handoutId' => $handoutName->id)) ?>">讲义名称：<?php echo $handoutName->name ?></a>
                    </li>
                </ul>
                <?php if($model->creatorID == user()->id){ ?>
                    <button type="button" style="margin-left: 5px;" id="handoutID" gId='<?php echo $model->gradeID;?>' subId="<?php echo $model->subjectID?>" materials="<?php echo $model->versionID; ?>" class="fl bg_red_l addDocBtn">修改讲义</button>
                <?php }elseif($model->isCollected == 0 && $model->creatorID !== user()->id){ ?>
                    <button type="button" style="margin-left: 5px;" class="bg_orenge favorite" favoriteId="<?php echo $model->handoutID; ?>">
                        收藏讲义
                    </button>
                <?php } elseif ($model->isCollected == 1) { ?>
                    <button type="button" style="margin-left: 5px;" class="bg_gray undo_collect"
                            collect="<?php echo $model->collectID; ?>">取消收藏
                    </button>
                <?php } ?>
            <?php } ?>
        <input id="DocList" value="" courseId="<?php echo $model->courseID; ?>"
               connectID="<?php echo $model->connectID; ?>" name="<?php echo Html::getInputName($model, 'handoutID') ?>"
               type="hidden"/>
    </div>
</div>
<ul class="course_list">
    <li>
        <span>教师：</span>
        <p><?php echo $model->teacherName; ?></p>
    </li>
    <li><span>上课时间：</span>
        <p><?php echo $model->beginTime; ?></p>
    </li>
    <li><span>结束时间：</span>
        <p><?php echo $model->finishTime; ?></p>
    </li>
    <?php if ($model->connectID == 0) { ?>
        <li><span>知识点：</span>

            <p>
                <?php foreach ($kcidName as $v) { ?>
                    <?php echo $v['name'] ?>
                <?php } ?>
            </p></li>
    <?php } elseif ($model->connectID == 1) { ?>
        <li><span>章节名称：</span>

            <p>
                <?php foreach ($kcidName as $v) { ?>
                    <?php echo $v['name'] ?>
                <?php } ?>
            </p></li>
    <?php } ?>
    <li><span>课程介绍：</span>

        <p>
            <?php if (!empty($model->courseBrief)) { ?>
                <?php echo strip_tags($model->courseBrief); ?>
            <?php } else {
                echo '无';
            } ?>
        </p>
    </li>
</ul>
<h5>课程评论</h5>

<div class="course_l ">
	<div class="course_B" >
    <div class="textareaBox ">
        <textarea id="comment_content<?php echo $model->courseID; ?>" class="textarea checkTextarea"></textarea>
		<span class="placeholder">对课程 “<?php echo $model->courseName; ?>” 评论</span>
		<div class="btnArea">
            <span class="addFace expression_comment"><i class="addFaceBtn"></i>表情</span>
			<em class="txtCount">可以输入 <b class="num">140</b> 字</em>
            <button type="button" id="comment_btn" class="sendBtn" informationName="<?php echo $model->courseName; ?>"
                    informationID="<?php echo $model->courseID; ?>">回复
            </button>
        </div>
    </div>

    <hr>

    <!--评论列表-->
	<div  id="srchResult" class="course_deta_list">
		<?php echo $this->render('_comment_list', array( 'commentList' => $commentList, 'pages' => $pages)); ?>
	</div>
</div>
	</div>
</div>

</div>

<!--主体内容结束-->
<div id="DocBox" class="popBox DocBox hide" title="选择讲义">
    <div id="updatehandout">

    </div>
</div>
