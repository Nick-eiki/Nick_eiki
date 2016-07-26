<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 2015/6/12
 * Time: 15:54
 */
/* @var $this yii\web\View */  $this->title="藤条棍";
?>
<div class="grid_19 main_r">
<div class="main_cont bear_children">
<div class="title">
    <h4>藤条棍</h4>
	<div class="title_r rattanR">
		<!--		        <button class="w130 bg_gray btn40">消息发送历史</button>-->
		<a href="<?php echo url('teacher/count/message-history',array('classId'=>app()->request->getParam('classID')))?>" class="a_button w130 bg_gray btn40">消息发送历史</a>
	</div>
</div>
<div class="rattancent">
<div class="tab fl">
<ul class="tabList clearfix">
    <li><a href="javascript:;" class="ac">作业未完成情况汇总</a></li>
    <li><a href="javascript:;">成绩排名</a></li>
</ul>
<div class="tabCont">

<div class="tabItem  homeworkResult">
<!--                    <div style="text-align: right">作业未完成次数统计（单位：次）</div>-->
<!--                    <table class="unfinished_list" cellpadding="0" cellspacing="0" id="homework">-->
<!--                        --><?php //echo $this->render("bear_homework",array("homeworkResult"=>$homeworkResult,"classID"=>$classID,
//                            "subject"=>$subject,
//                            "orderBy"=>$orderBy
//                        ));?>
<!--                    </table>-->
<!--                    <div class="test_achievement_foot">-->
<!--                                  <span class="">-->
<!--										<input id="chkAll" type="checkbox" class="hide">-->
<!--                                        <label for="chkAll" class="chkLabel">全选</label>-->
<!--								  </span>-->
<!--                        <button type="button" class="w120 bg_blue evaluateBtn" id="evaluateBtn">告家长</button>-->
<!--                    </div>-->
<div class="rattancent_list thisWeek">
    <!--展示-->
    <div class="rattanCent_t">


        <div class="rattanCent_h ">
            <h4 class="clearfix"><span class="fl"><?=$thisMonday."至".$today?></span><em class="fr" beginTime="<?=$thisMonday?>" endTime="<?=$today?>">查看详情<b></b></em></h4>
            <!--隐藏-->
            <div class="rattanCbox bear_children hide">
                <div class="answerBigBox">
                    <div class="answerBox">
                        <em class="arrow" style="right:12px; top:-9px;"></em>


                        <div class="answerBox_list">
                            <div class="rattancent_cue clearfix">
                                <span class="fl rattancent_l">作业未完成次数统计(单位：次)</span>

                            </div>
                            <div class="tabox">
                                <table class="unfinished_list tableA" cellpadding="0" cellspacing="0">


                                </table>
                            </div>
<!--                            <div class="test_achievement_foot">-->
<!--                                                          <span class="">-->
<!--                                                                <input id="chkAllA" type="checkbox" class="hide">-->
<!--                                                                <label for="chkAllA" class="chkLabel">全选</label>-->
<!--                                                          </span>-->
<!--                                <button type="button" class="w120 bg_blue evaluateBtn" id="evaluateBtn">告家长</button>-->
<!--                            </div>-->
                        </div>

                    </div>
                </div>
            </div>
            <!--隐藏end-->
        </div>
    </div>
</div>
<div class="rattancent_list lastWeek">
    <!--展示-->
    <div class="rattanCent_t">


        <div class="rattanCent_h ">
            <h4 class="clearfix"><span class="fl"><?=$lastMonday."至".$lastSunday?></span><em class="fr" beginTime="<?=$lastMonday?>" endTime="<?=$lastSunday?>">查看详情<b></b></em></h4>
            <!--隐藏-->
            <div class="rattanCbox bear_children hide">
                <div class="answerBigBox">
                    <div class="answerBox">
                        <em class="arrow" style="right:12px; top:-9px;"></em>


                        <div class="answerBox_list">
                            <div class="rattancent_cue clearfix">
                                <span class="fl rattancent_l">作业未完成次数统计(单位：次)</span>

                            </div>
                            <div class="tabox">
                                <table class="unfinished_list tableB" cellpadding="0" cellspacing="0">

                                </table>
                            </div>
                            <div class="test_achievement_foot">
                                                          <span class="">
                                                                <input id="chkAllB" type="checkbox" class="hide">
                                                                <label for="chkAllB" class="chkLabel">全选</label>
                                                          </span>
                                <button type="button" class="w120 bg_blue evaluateBtn disableBtn" id="evaluateBtn">告家长</button>
                                <span>如果家长未注册，则发送失败</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!--隐藏end-->
        </div>
    </div>
</div>
</div>


<div class="tabItem  examResult hide">
    <p>考试名称<input type="text" class="text" style="margin-left:13px" id="textJS"></p>
    <div class="score_list" >


    </div>
    <!--                    <div class="test_achievement_foot">-->
    <!--                                  <span class="">-->
    <!--										<input id="chkAll_s" type="checkbox" class="hide">-->
    <!--                                        <label for="chkAll_s" class="chkLabel">全选</label>-->
    <!--								  </span>-->
    <!--                        <button type="button" class="w120 bg_blue evaluateBtn" id="evaluateBtn">告家长</button>-->
    <!--                    </div>-->

</div>


</div>
</div>
</div>
</div>
</div>
<!--选择考试-->
<div id="choosePaper" class="popBox hide pushNotice addscoreBox" title="选择考试">
    <div class="popCont">
        <div class="new_choosePaper">
            <div class="title">
                <h4 class="font14" style="line-height:54px">考试名称</h4>
                <div class="title_r clearfix" style="top:12px;">
                    <input  type="text" class="text fl" id="searchText" style="width:216px; height:12px;">
                    <button type="button" class="hideText TextBtn  searchBtn fl">搜索</button>
                </div>
            </div>
            <div  class="examList">

            </div>
        </div>
    </div>
    <div class="popBtnArea">
        <button type="button" class="okBtn">确定</button>
        <button type="button" class="cancelBtn">取消</button>
    </div>
</div>

<script>
    $(function () {
        /*升序降序箭头切换*/
        $('.em_ico').toggle(function () {
            $(this).parents('th').siblings().children('.em_ico').removeClass('up');
            $(this).parents('th').siblings().children('.em_ico').removeClass('down');
            $(this).addClass('up');
            $(this).removeClass('down');
        }, function () {
            $(this).addClass('down');
            $(this).removeClass('up');
        });
        $('.thisWeek  h4 em').toggle(function(){
            var beginTime=$(this).attr("beginTime");
            var endTime=$(this).attr("endTime");
            var classID="<?=app()->request->getParam('classID')?>";
            var url="<?=url('teacher/count/get-homw-list')?>";
            $.post(url,{beginTime:beginTime,endTime:endTime,classID:classID},function(result){
                $(".thisWeek").find(".unfinished_list").html(result);
                $(".lastWeek").find(".unfinished_list").html("");
            });
            $('.tabCont .bear_children').hide();
            $(this).parents('.rattanCent_h').children('.bear_children').show();
        },function(){
            $('.tabCont .bear_children').hide();
            $(this).parents('.rattanCent_h').children('.bear_children').hide();
        });
        $('.lastWeek  h4 em').toggle(function(){
            var beginTime=$(this).attr("beginTime");
            var endTime=$(this).attr("endTime");
            var classID="<?=app()->request->getParam('classID')?>";
            var url="<?=url('teacher/count/get-homw-list')?>";
            $.post(url,{beginTime:beginTime,endTime:endTime,classID:classID},function(result){
                $(".lastWeek").find(".unfinished_list").html(result);
                $(".thisWeek").find(".unfinished_list").html("");
            });
            $('.tabCont .bear_children').hide();
            $(this).parents('.rattanCent_h').children('.bear_children').show();
        },function(){
            $('.tabCont .bear_children').hide();
            $(this).parents('.rattanCent_h').children('.bear_children').hide();
        });

        /*弹框*/
        $('.popBox').dialog({
            autoOpen: false,
            width: 680,
            modal: true,
            resizable: false,
            close: function () {
                $(this).dialog("close")
            }
        });
        /*编辑弹窗*/
        (function () {

            var text_val = "";

            $('#textJS').click(function () {
                classID="<?=app()->request->getParam('classID')?>";
                $.post("<?=url('teacher/count/get-exam-list')?>",{classID:classID},function(result){
                    $(".examList").html(result);
                });
                $('#choosePaper .resultList li').each(function () {
                    if ($(this).text() == $('#textJS').val()) {
                        $(this).siblings().removeClass('ac');
                        $(this).addClass('ac');
                    }
                });
                $(".popBox").dialog("open");
                return false;
            });


            $('#choosePaper .okBtn').click(function () {
                examID=$(".examList").find(".ac").attr("examID");
                $.post("<?=url('teacher/count/get-change-list')?>",{examID:examID},function(result){
                    $(".score_list").html(result);
                });
                $('#choosePaper .resultList li').each(function () {
                    if ($(this).hasClass("ac")) {
                        text_val = $(this).text();
                    }
                });
                $('#textJS').val(text_val);
                $(".popBox").dialog("close");

            })


        })();
//    告家长
        $("#evaluateBtn").click(function(){
            if(!$("#evaluateBtn").hasClass("disableBtn")) {
                var obj = $(".tableB .chkLabel_ac");
                var studentArray = [];
                obj.each(function (index, el) {
                    var studentID = $(el).attr("studentID");
                    studentArray.push(studentID);
                });
                var studentList = studentArray.join(",");
                $.post("<?=url('teacher/count/send-msg-to-parents')?>", {students: studentList}, function (result) {

                    popBox.successBox(result.message);
                    if(result.success){
                        location.reload();
                    }
                })
            }
        })

    })
</script>