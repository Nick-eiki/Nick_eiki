<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-4
 * Time: 下午2:42
 */
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */  $this->title="作业列表";
?>
<div class="currentRight grid_16 push_2 up_work">
    <div class="noticeH clearfix noticeB">
        <h3 class="h3L">作业管理</h3>

            <div class="new_not fr">

            <?php

            echo Html::dropDownList("",app()->request->getParam("classID")
                ,
                ArrayHelper::map($classArray, 'classID', 'className'),
                array(
                    "prompt"=>"请选择",
                    "id" => "classID"
                ));
            ?>

            <?php

            echo Html::dropDownList("",app()->request->getParam('type','')
                ,
               array("我上传的作业","我组织的作业"),
                array(
                    "prompt"=>"请选择",
                    "id" => "type"
                ));
            ?>
              <?php if(app()->request->getParam("classID")!=""){?>
            <a href="<?php echo url('teacher/managetask/fixup-work',array('classID'=>app()->request->getParam('classID'))) ?>" class="B_btn120 btn">布置作业</a>
            <a href="<?php echo url('teacher/managetask/header',array('classID'=>app()->request->getParam('classID'))) ?>" class="B_btn120 btn">组织作业</a>
         <?php }?>
        </div>

    </div>
    <hr>
    <div class="up_work_list">
        <?php foreach($homeworkList as $v){ if($v->getType==1){?>
            <dl class="clearfix">
                <dt><img src="<?php echo publicResources()?>/images/teacher_m.jpg" alt=""></dt>
                <dd>
                    <h4><a href="<?php echo url('teacher/managetask/organize-work-details',array('homeworkID'=>$v->homeworkId,'classID'=>$v->classID))?>">[<?php echo $v->gradename."&nbsp".$v->subjectname?>]</a></h4>
                </dd>
                <dd>
                    <h5><a href="<?php echo url('teacher/managetask/organize-work-details',array('homeworkID'=>$v->homeworkId,'classID'=>$v->classID))?>"><?php echo $v->name?></a></h5>
                </dd>
                <dd>
                    <span>简介：</span><em><?php echo $v->homeworkDescribe?></em>
                </dd>
                <dd class="timer">
                    <em>交作业截至时间：<i><?php echo $v->deadlineTime?></i></em>
                </dd>
                <dd class="c_btn">
                    <?php if(!$v->isSendMsg){?>
                        <a href="javascript:" class="a_button bg_red_l send" homeworkID="<?php echo $v->homeworkId?>" >通知学生</a>
                <?php }else{?>
                        <a href="javascript:;" class="a_button bg_red_l off_notice">通知学生</a>
                    <?php }?>

                </dd>
            </dl>
        <?php }elseif($v->getType==0){?>
            <dl class="clearfix">
                <dt><img src="<?php echo publicResources() ?>/images/teacher_m.jpg" alt=""></dt>
                <dd>
                    <h4><a href="<?php echo url('teacher/managetask/upload-work-details',array('homeworkID'=>$v->homeworkId,'classID'=>app()->request->getParam('classID'))) ?>">[<?php echo $v->gradename."&nbsp".$v->subjectname?>]</a></h4>
                </dd>
                <dd>
                    <h5><a href="<?php echo url('teacher/managetask/upload-work-details',array('homeworkID'=>$v->homeworkId,'classID'=>app()->request->getParam('classID'))) ?>"><?php echo $v->name?></a></h5>
                </dd>
                <dd>
                    <span>简介：</span><em><?php echo $v->homeworkDescribe?></em>
                </dd>
                <dd class="timer">
                    <em>交作业截至时间：<i><?php echo $v->uploadTime?></i></em>

                </dd>
                <dd class="c_btn">
                     <?php if(!$v->isSendMsg){?>
                    <a href="javascript:;" class="a_button bg_red_l send" homeworkID="<?php echo $v->homeworkId?>">通知学生</a>
                      <?php }else{?>
                         <a href="javascript:;" class="a_button bg_red_l off_notice">通知学生</a>
                     <?php }?>
                    <a href="<?php echo url('teacher/managetask/update-work',array('homeworkID'=>$v->homeworkId,'classID'=>$v->classID))?>" class="a_button bg_blue work_btn">重新布置</a>
               </dd>
            </dl>
        <?php } }?>
    </div>
        <?php
         echo \frontend\components\CLinkPagerExt::widget( array(
               'pagination'=>$pages,
                'maxButtonCount' => 5
            )
        );
        ?>


</div>
<script>
    $(function(){
        $("#classID").change(function(){
            var classID=$(this).val();
            var type=$("#type").val();
            if(classID!=""){

                if(type!=""){
            location.href="<?php echo url('teacher/managetask/index')?>"+"/classID/"+classID+"/type/"+type;
                }else{
            location.href="<?php echo url('teacher/managetask/index')?>"+"/classID/"+classID;
                }

              }else{
                if(type!=""){
                    location.href="<?php echo url('teacher/managetask/index')?>"+"/type/"+type;
                }else{
                    location.href="<?php echo url('teacher/managetask/index')?>";
                }
            }
        });
       $("#type").change(function(){
           var type=$(this).val();
           var classID=$("#classID").val();
           if(type!=""){
               if(classID!=""){
           location.href="<?php echo url('teacher/managetask/index')?>"+"/classID/"+classID+"/type/"+type;
               }else{
                   location.href="<?php echo url('teacher/managetask/index')?>"+"/type/"+type;
               }
           }else{
               if(classID!=""){
                   location.href="<?php echo url('teacher/managetask/index')?>"+"/classID/"+classID;
               }else{
                   location.href="<?php echo url('teacher/managetask/index')?>";
               }
           }
       });
        $(".send").click(function(){
            var homeworkID=$(this).attr("homeworkID");
            $.post("<?php echo url('ajaxteacher/send-message')?>",{objectId:homeworkID,messageType:"507001"},function(result){
                        if(result.code){
                            popBox.successBox(result.message);
                            location.reload();
                        }else{
                            popBox.errorBox(result.message);
                        }
            })
        })
    })
</script>