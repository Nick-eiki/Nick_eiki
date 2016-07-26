<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/11/20
 * Time: 14:17
 */
?>
<div id="srchResult">
    <?php foreach ($data as $replyVal) {  ?>


        <ul class="course_deta_list">
            <li>
                <span class="c_title_h"><img src="../images/user_s.jpg" alt=""></span>
                <p class="p_name"><em class="name"><?php echo $replyVal->commentUserName; ?></em>针对<em class="name"><?php echo $replyVal->commenTitletName; ?></em>发表（好评/评论）：</p>
                <p><?php echo $replyVal->commentContent; ?></p>
                <p class="btn_parent">
                    <span><?php echo $replyVal->commentTime;?> </span>
                    <a href="javascript:" class="name report_comment" commentId = "<?php echo $replyVal->commentID; ?>">举报</a>

                    <span class="span_link"><a href="javascript:" class="name reply_btn">回复</a><a href="javascript:;" class="name na_del" commentId = "<?php echo $replyVal->commentID; ?>">删除</a></span>
                </p>
                <div class="textareaBox_pro textareaBox hide popo">
                    <textarea class="textarea_val<?php echo $replyVal->commentID; ?> " style="width:600px"></textarea>
                    <p class="exp">
                        <span class="expression"><i></i>表情</span>
                        <span class="counter">还可以输入<em class="JS_num2">140</em>字</span>
                        <button type="button" class="sendBtn_js" commentId = "<?php echo $replyVal->commentID; ?>">回复</button>
                    </p>
                </div>
                <hr>
            </li>
        </ul>

        <?php foreach ($replyVal->subReplays as $commentVal) { ?>
        <ul class="course_deta_list course_deta_list2">
            <li>
                <span class="c_title_h"><img src="../images/user_s.jpg" alt=""></span>
                <p class="p_name">
                    <em class="name">孙武龙</em>针对<em class="name">追击问题</em>发表（好评/评论）：
                </p>
                <p><?php echo $commentVal->replayContent?></p>
                <p class="btn_parent">
                    <span><?php echo $commentVal->replayTime?></span>
                    <a href="javascript:" class="name">举报</a>
                    <span class="span_link"><a href="javascript:;" class="name reply_btn">回复</a><a href="javascript:;" class="name na_del">删除</a></span>
                </p>
                <div class="textareaBox_pro textareaBox hide popo">
                    <textarea class="JS_textarea2 textarea_val " style="width:600px"></textarea>
                    <p class="exp"><span class="expression"><i></i>表情</span><span class="counter">还可以输入<em class="JS_num2">140</em>字</span><button type="button" class="sendBtn_js">回复</button></p>

                </div>
                <hr>
            </li>
        </ul>
        <?php } ?>

    <?php } ?>


    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(
           'pagination'=>$pages,
            'updateId' => '#srchResult',
            'maxButtonCount' => 5
        )
    );
    ?>

</div>

<script>
    $(".sendBtn.sendBtn_js.mini_btn").live("click",function(){
        var commentId = $(this).attr('commentId');
        var replayContent = $('.text.text_display.JS_textarea2.textarea_val' + commentId).val();
        if(replayContent == '')
        {
            popBox.errorBox("内容不能为空!!!!");
            return false;
        }
        else
        {
            $.post('<?php echo url('teacher/information/replay-comment');?>',{commentId:commentId,replayContent:replayContent},function(data){
                if(data.success){
                    location.reload();
                }else{
                    popBox.alertBox(data.message);
                }
            });
            $(this).parent().parent('.pop_up_js').hide();
        }
    });
    $(".report_comment").live('click', function(){
        var commentId = $(this).attr('commentId');
        $.post('<?php echo url('teacher/information/report-comment')?>',{commentId:commentId},
            function (data){
                if(data.success){
                    location.reload();
                }else{
                    popBox.alertBox(data.message);
                }
            }
        )
    })
</script>



