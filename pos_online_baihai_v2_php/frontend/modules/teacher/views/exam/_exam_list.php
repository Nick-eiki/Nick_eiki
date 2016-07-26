<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-10-17
 * Time: 下午3:11
 */
use frontend\components\helper\PinYinHelper;
use frontend\components\helper\StringHelper;
use frontend\components\helper\ViewHelper;

?>
<ul class="itemList">
    <?php  if(!empty($examResult->data->examList)){foreach ($examResult->data->examList as $v) { ?>
        <li class="<?=$v->isSendMsg==1?'sended':''?>">
            <div class="title item_title noBorder">
                <h4>
                    <a href="<?= url('teacher/exam/over-all-appraise', array('examID' => $v->examID,"classID"=>app()->request->getParam("classid"))) ?>"><?php echo $v->examName ?></a>
                </h4>

                <div class="title_r">
                    <span class="gray"><?= $v->examTime ?></span>
                    <span><a href="javascript:;" class="txtBtn gray_d <?=empty($v->examSubList)?'dis_viewDetail':'viewDetail'?>">查看详情<i></i></a></span>
                    <?php if ($v->isSendMsg == 0) { ?>
                        <span><a href="javascript:;" examID="<?php echo $v->examID ?>"
                                 class="txtBtn gray_d setupCls"><?php echo !$v->isSetSub ? '设置' : '修改' ?>科目与时间
                            </a></span>
                    <?php } ?>
                    <?php if ($v->isSetSub == 1) { ?>
                        <?php if ($v->isSendMsg == 0) { ?>
                            <button type="button" examID="<?php echo $v->examID ?>" class="send bg_blue ">发送通知</button>
                        <?php } else { ?>
                            <button type="button " class=" bg_blue">已发送</button>
                        <?php }
                    } else { ?>
                        <button type="button " class=" bg_blue unSetSub">发送通知</button>
                    <?php } ?>
                </div>
            </div>
            <div class="testListWrap">
                <?php if(!empty($v->examSubList)){?>
                <i class="arrow"></i>
                <div class="objClip">
                    <?php foreach($v->examSubList as $value){?>
                    <em class="<?php echo PinYinHelper::firstChineseToPin($value->subjectName) ?>"><?=StringHelper::cutStr($value->subjectName,1,"")?></em>

            <?php }?>
                </div>
            <?php }?>

            <ul class="clearfix testList">

                <?php foreach ($v->examSubList as $value) { ?>
                    <li class="<?php echo PinYinHelper::firstChineseToPin($value->subjectName) ?>"><i></i>
                        <h5><?php echo $value->teacherName ?></h5>

                        <p>
                            <a class="bg_blue_l omega btn w80 btn30"
                               href="<?= url('teacher/exam/subject-details', array('examSubID' => $value->examSubID,"classID"=>app()->request->getParam("classid"))).'#upload' ?>">原始试卷</a>
                            <a class="bg_blue_l omega btn w80 btn30"
                               href="<?= url('teacher/exam/subject-details', array('examSubID' => $value->examSubID,"classID"=>app()->request->getParam("classid"))).'#score'  ?>">在线判卷</a>
                            <a class="bg_blue_l omega btn w80 btn30"
                               href="<?= url('teacher/exam/subject-details', array('examSubID' => $value->examSubID,"classID"=>app()->request->getParam("classid"))).'#score' ?>">学生成绩</a>
                            <a class="bg_blue_l omega btn w80 btn30"
                               href="<?php echo url('teacher/exam/subject-details', array('examSubID' => $value->examSubID,"classID"=>app()->request->getParam("classid"))).'#subjectEvaluate' ?>">科目总评</a>
                        </p>
                    </li>
                <?php } ?>
            </ul>
                </div>
            <div class="clsSetupBox hide">

            </div>
        </li>
    <?php } }else{
        ViewHelper::emptyView();
    }?>
</ul>
<?php
 echo \frontend\components\CLinkPagerExt::widget( array(
        'updateId' => '.examList',
       'pagination'=>$pages,
        'maxButtonCount' => 5,
        'showjump'=>true,
    )
);
?>
<script>
    $(function () {
        ajaxreload = function () {
            $.ajax({
                'url': '<?php echo  app()->request->getUrl() ?>', 'cache': false, 'success': function (html) {
                    $('.examList').html(html)
                }
            });
        };
        //显示"设置科目与时间"
        $('.setupCls').click(function(){
            $this = $(this);
            examID = $(this).attr("examID");
            var pa=$(this).parents('li');
            $.post("<?=url('teacher/exam/get-sub-pop')?>", {examID: examID}, function (result) {
                pa.children(".clsSetupBox ").html(result);
                $('.test .testListWrap').show().removeClass('showTestList');
                $('.test .clsSetupBox').hide();
                pa.children('.testListWrap').hide();
                pa.find('.clsSetupBox').show();
                pa.find('.cancelAddObjBtn').click(function(){
                    pa.find('.clsSetupBox').hide();
                    pa.children('.testListWrap').show();
                });
            });
//            var html='<i class="arrow"></i> test test'+num;

        });
        /*添加考试分数*/
        $('.clsSetupBox .testObjList li').click(function () {
            var pa = $(this).parents('.form_list');
            var id = $(this).attr('id');
            if (!$(this).hasClass('ac')) {
                var name = $(this).text();
                var Score = $(this).attr('data-score');
                pa.find('.objScore').show();
                pa.find('.objScoreList').append('<li data-id="' + id + '">' + name + ' <input type="text" class="text w30" value="' + Score + '"></li>');
            }
            else {
                $('.objScoreList li[data-id=' + id + ']').remove();
                if ($('.objScoreList li').size() == 0) $('.objScore').hide();
            }
        });
//        发送通知
        $(".send").click(function () {
            var examID = $(this).attr("examID");
            $.post("<?php echo url('ajaxteacher/send-message')?>", {
                objectId: examID,
                messageType: "507402"
            }, function (result) {
                if (result.code == 1) {
                    popBox.successBox(result.message);
                    ajaxreload();
                }
                else {
                    popBox.errorBox(result.message)
                }
            })
        });
        $(".unSetSub").click(function () {
            popBox.errorBox("请设置科目");
        });
        //查看详情
        $('.viewDetail').click(function(){
            var pa=$(this).parents('li');
            pa.siblings('li').find('.testListWrap').show().removeClass('showTestList');
            pa.find('.testListWrap').show().toggleClass('showTestList');
            pa.siblings('li').find('.clsSetupBox').hide();
            pa.find('.clsSetupBox').hide();
        });
    })
</script>

