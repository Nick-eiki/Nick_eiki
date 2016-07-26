<?php /* @var $this yii\web\View */  $this->title="教师-教研平台-教研课题"; ?>




<!--主体内容开始-->

    <div class="currentRight grid_16 push_2 tch">
        <div class="crumbs"><a href="#">教研课题</a> >> <a href="#">课题列表</a> >> 课题详情</div>
        <div class="notice tch ">
            <div class="noticeH clearfix noticeB">
                <h3 class="h3L"><?php echo $taskCourse->courseName;?></h3>
                <div class="new_not fr"> <a href="<?php echo url('teacher/diary/add-diary')?>" class="new_bj newBtnJs">提交课题报告</a> </div>
            </div>
            <hr>
            <div class="plan_l details">
                <p><i>课题组成员：</i>
                     <?php foreach($taskCourse->courseMemberList as $key=>$val){ ?>
                         <span id="<?php echo $val->memberID;?>"><?php echo $val->memberName;?></span>
                   <?php  } ?>
                   </p>
                <p><i>课题描述：</i><?php echo $taskCourse->brief;?></p>
                <div>放插件</div>
                <div class="plan_l details_width">
                    <div class="noticeH clearfix">
                        <h4 class="h3L" style="padding-left:10px; border: none">教研报告</h4>
                        <div class="new_not fr"> <a href="javascript:" class="a_rl">共<i><?php echo $total;?></i>篇</a> </div>
                    </div>
                    <hr>
                    <ul class="notice_list topic_list">
                             <?php foreach($dairySearchList as $val){ ?>

                        <li class="clearfix" style="width: 296px;"><a href="<?php echo url('teachinggroup/report',array('id'=>$val->diaryID,'groupId'=>$taskCourse->teachingGroupID));?>"><i>[<?php echo $val->teacherName;?>]</i><?php echo $val->headline;?></a><em><?php echo $val->createTime;?></em></li>
                                 <?php    }?>

                    </ul>
                        <?php
                         echo \frontend\components\CLinkPagerExt::widget( array(
                               'pagination'=>$pages,
                                'maxButtonCount' => 5
                            )
                        );
                        ?>
                </div>
            </div>
        </div>
    </div>

<!--主体内容结束-->


<style type="text/css">
    .fot_z span{ font-size:14px;}
</style>

<!--创建修改新建教学计划--------------------->
<script type="text/javascript">
    $(function(){
        $('.addDel').live('click',function(){
            $(this).parent().remove();

        })

    })

</script>

