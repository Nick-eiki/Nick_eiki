<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 2015/7/28
 * Time: 17:13
 */
use common\models\pos\SeGroupLecturePlan;
use frontend\components\WebDataCache;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php /** @var SeGroupLecturePlan[] $lessonList */
foreach($lessonList as $v){ ?>
<div class="box1">
    <span class="blue-btn joinTime"><?=date("Y-m",strtotime($v->joinTime))?></span>
    <div class="preview"> <span></span>
        <div class="box2 clearfix">
            <div class="test_left clearfix">
                <div class="titles clearfix title_edt">
                    <h5> [<?=$v->teacherName?>]<?=Html::encode($v->title)?> </h5>
	                <a href="javascript:;;" class="edit_btn" lecturePlanID="<?=$v->lecturePlanID?>"></a>
                    <?php $memberArray=array();
                      foreach($v->groupLecturePlanMember as $value){
                          array_push($memberArray,$value->userID);
                      }
                    if(in_array(user()->id,$memberArray)){
                           $memberResult=\common\models\pos\SeGroupLecturePlanReport::find()->where(["userID"=>user()->id,"lecturePlanID"=>$v->lecturePlanID])->one();
                      if(empty($memberResult)){?>
                          <a href="<?php echo Url::to(['write-report','groupId'=>$groupId,'lecturePlanId'=>$v->lecturePlanID])?>" class="btn btn30 bg_blue_l fill_in_btn ">填写听课报告</a>
                     <?php }

                    }
                    ?>

                </div>
                <div class="lecture_list">
                    <dl class="clearfix">
                        <dt> 听课时间: </dt>
                        <dd><?=date("Y-m-d H:i",strtotime($v->joinTime))?></dd>
                    </dl>
                    <dl class="clearfix">
                        <dt> 听课人: </dt>
                        <dd>
                        <?php

                        foreach($v->groupLecturePlanMember as $value){
                            echo $value->userName."&nbsp;&nbsp;";
                        }
                        ?></dd>
                    </dl>
                    <?php if(!empty($v->groupLecturePlanReport)){?>
                    <dl class="clearfix">
                        <dt class="reports"> 听课报告: </dt>
                        <dd>
                            <ul class="reports_con">
                                <?php
                                   foreach($v->groupLecturePlanReport as $value){?>
                                <li> <a href="<?=url::to(['teachgroup/listen-report-details','groupId'=>app()->request->getQueryParam('groupId'),'lecturePlanReportId'=>$value->lecturePlanReportId,'lecturePlanID'=>$v->lecturePlanID])?>" title="<?=WebDataCache::getTrueName($value->userID)?>" class="cur"><?=Html::encode($value->reportTitle)?> </a> </li>
                                   <?php }
                                ?>
                            </ul>
                        </dd>
                    </dl>
                        <?php if(count($v->groupLecturePlanReport)>4){?>
                            <div class="expand_com"> <span class="open_inner"> <a>展开报告</a><i></i></span> </div>
                        <?php }?>

                    <?php }?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }?>
<?php if ($pages->getPageCount() > $pages->getPage() + 1) { ?>
<span class="blue-btn more-btn" onclick="return getLessonList(<?php echo $pages->getPage() + 2 ?>,<?=app()->request->getQueryParam('groupId')?>,<?=app()->request->getQueryParam('type',0)?>);">更多</span>
<?php }?>
<script>
    var getLessonList=function(page,groupId,type){
        $.get("<?=url('teachgroup/get-lessons-page')?>",{page:page,groupId:groupId,type:type},function(result){
            $(".more-btn").replaceWith(result);
        })
    };
    $(function(){
        var boxList=$(".box1");
        var timeArray=[];
         boxList.each(function(index,el){
             var obj=$(el).find(".joinTime").html();
             if(timeArray.indexOf(obj)>-1){
             $(el).find(".joinTime").hide();
             }else{
                 timeArray.push(obj);
             }

         });
        $(".box1 .titles h5").next("a").unbind("click");
        /*编辑弹窗*/
        $(".box1 .titles h5").next("a").click(function() {
            var groupId=<?=app()->request->getQueryParam("groupId")?>;
            var lecturePlanID=$(this).attr("lecturePlanID");
            $.post("<?=url('teachgroup/get-listen-details')?>",{groupId:groupId,lecturePlanID:lecturePlanID},function(result){
                $("#popBox2").html(result);

            });
            $("#popBox2").dialog("open");

            return false;
        });
        //听课评课
        $(".date_sc .expand_com").click(function() {
            var reports_con=$(this).prev().find(".reports_con");
            if (!$(this).hasClass("expand_com_close")) {
                reports_con.height("auto");
                $(this).addClass("expand_com_close").find("a").text("收起报告");

            } else {
                $(this).removeClass("expand_com_close").find("a").text("展开报告");
                reports_con.height(104);
            }
        });

    })

</script>
