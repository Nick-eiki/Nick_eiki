<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-15
 * Time: 上午11:16
 */
use frontend\components\helper\AreaHelper;
use frontend\components\helper\ImagePathHelper;
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */  $this->title="上传试卷";
?>
<div class="currentRight grid_16 push_2 up_work_details">
    <div class="noticeH clearfix noticeB">
        <h3><?php echo $result->homeworkName ?></h3>


    </div>
    <hr>
    <div class="work_detais_cent">
        <h4><?php echo $result->homeworkName ?></h4>
        <ul class="detais_list">
            <li>
                <span>地区：<i><?php echo AreaHelper::getAreaName($result->provience) . "&nbsp" . AreaHelper::getAreaName($result->city) . "&nbsp" . AreaHelper::getAreaName($result->country) ?>
                    </i></span><span>年级：<i>一年级</i></span><span>科目：<i><?php echo $result->subjectname ?></i></span><span>版本：<i><?php echo $result->versionname ?></i></span>
            </li>
            <li><span>知识点：<i><?php $knowledge=new KnowledgePointModel();  echo $knowledge::findKnowledgeStr($result->knowledgeId) ?></i></span></li>
            <li><span>试卷简介：<i><?php echo $result->homeworkDescribe ?></i></span></li>
        </ul>
        <div class="details_b" id="details_b">
            <div class="species clearfix">
                <p>试卷内容：</p>

                <div class="species_Right">
                    <ul class="minute minute70 clearfix ">
                        <?php foreach (ImagePathHelper::getPicUrlArray($result->imageUrls) as $k => $v) {
                            if ($k == 0) {
                                ?>
                                <li>
                                <span>
                                	<img alt="" src="<?php echo publicResources() . $v ?>">
                                    <em><a href="<?php echo url('student/managetask/upload-preview',array('homeworkID'=>app()->request->getQueryParam('homeworkID')))?>">试卷预览</a></em>
                                </span>
                                    <i></i>
                                </li>
                            <?php } else { ?>
                                <li>
                                    <span><img alt="" src="<?php echo publicResources() . $v ?>"></span>
                                    <i></i>
                                </li>
                            <?php
                            }
                        } ?>
                    </ul>
                </div>

            </div>
            <hr class="hr_sp">
            <div class="exa">
                <dl class="exa_list">
                    <dt>
                    <p class="paper_btn">
                        <!--当已经上传一些东西时，上传按钮的文字会变成继续上传!-->
                        <?php if (!$answerList->isUploadAnswer) { ?>
                            <button type="button" class="mini_btn exa_btn up_popo">上传答案</button>
                        <?php } elseif ($answerList->isUploadAnswer && !$answerList->isCheck) { ?>
                            <button type="button" class="mini_btn exa_btn update">修改答案</button>
                        <?php } ?>
                    </p>

                    </dt>
                    <dd>
                        <ul class="anlist clearfix" style="height:50px; overflow:hidden;">
                            <?php if($answerList->isUploadAnswer==1){foreach ($answerList->homeworkAnswerImages as $k => $v) {
                                 if($k<8){?>
                                <li><img src="<?php echo publicResources() . $v->imageUrl ?>" alt=""></li>
                            <?php }elseif($k==8){?>
                                     <li class="more more_js" title="点击这里查看更多"><img
                                             src="<?php echo publicResources() ?>/images/more.png" alt=""></li>
                                     <li><img src="<?php echo publicResources().$v->imageUrl ?>" alt=""></li>
                                 <?php }elseif($k>8){?>
                                     <li><img src="<?php echo publicResources().$v->imageUrl ?>" alt=""></li>
                                 <?php } } } ?>
                        </ul>


                    </dd>
                       <?php if($answerList->isUploadAnswer==1){  ?>
                        <dd class="answer">
                            <p class="clearfix"><strong>我的答案<i>(<?php echo  $answerList->isCheck==0?"未批改":"已批阅"?>)</i></strong></p>
                            <ul class="ul_list clearfix" style="height:50px; overflow:hidden;">
                                <?php if($answerList->isUploadAnswer==1){ foreach($answerList->homeworkCheckInfoS as $key=>$value){ if($key<5){?>
                                <li class=""><img src="<?php echo publicResources().$value->imageUrl ?>" alt=""></li>
                               <?php }elseif($key==5){?>
                                    <li class=""><img src="<?php echo publicResources().$value->imageUrl ?>" alt=""></li>
                                <li class="more more_js"><img src="<?php echo publicResources() ?>/images/more.png"
                                                              alt=""></li>
                                    <?php }elseif($key>5){?>
                                    <li class=""><img src="<?php echo publicResources().$value->imageUrl ?>" alt=""></li>
                            <?php  } } }?>
                            </ul>
                            <p>
                                <?php if($answerList->isCheck){?>
                                    <a href="<?php echo url('student/managetask/view-correct',array('homeworkAnswerID'=>$answerList->homeworkAnswerID,'homeworkID'=>app()->request->getQueryParam('homeworkID')))?>" class="exa_btn mini_btn">查看批改</a>
                                <?php }?>
                            </p>
                        </dd>
                    <?php }?>

                    <?php if(isset($answerList->otherHomeworkAnswerID)&&!empty($answerList->otherHomeworkAnswerID)){?>
                        <dd class="answer">
                            <p class="clearfix"><strong><?php echo $answerList->otherStudentName?>的答案<i>(<?php echo  $answerList->otherIsCheck?"已批改":"未批改" ?>)</i></strong></p>
                            <ul class="ul_list clearfix" style="height:50px; overflow:hidden;">
                                <?php foreach($answerList->otherHomeworkAnswerInfo as $k=>$v){ if($k<7){?>
                                    <li class=""><img src="<?php echo publicResources().$v->imageUrl?>" alt=""></li>
                                <?php }elseif($k==7){?>
                                    <li class=""><img src="<?php echo publicResources().$v->imageUrl?>" alt=""></li>

                                    <li class="more more_js"><img src="<?php echo publicResources()?>/images/more.png" alt=""></li>
                                <?php }elseif($k>7){?>
                                    <li class=""><img src="<?php echo publicResources().$v->imageUrl?>" alt=""></li>

                                <?php } }?>
                            </ul>
                            <p>
                                <?php if($answerList->otherIsCheck){?>
                                    <a href="<?php echo url('student/managetask/view-correct',array('homeworkAnswerID'=>$answerList->otherHomeworkAnswerID,'homeworkID'=>app()->request->getQueryParam('homeworkID')))?>" class="exa_btn mini_btn">查看批改</a>
                                <?php }else{?>
                                    <a href="<?php echo url('student/managetask/correct-paper',array('homeworkAnswerID'=>$answerList->otherHomeworkAnswerID,'homeworkID'=>app()->request->getQueryParam('homeworkID')))?>" class="exa_btn mini_btn">我要批改</a>

                                <?php }?>
                            </p>
                        </dd>
                    <?php }?>
                </dl>


            </div>


        </div>
    </div>


</div>
<!--上传试卷-->
<div id="up-manage" class=" popBox up-manage hide">

</div>
<script>

    $(function () {

        /*删除按钮*/
        $('.minute li i').live('click', function () {
            $(this).parent().remove();
        });

//选择老师
//    上传答案
        $('.up_popo').click(function () {
            $.post("<?php echo url('student/managetask/upload-answer-content')?>", {homeworkID:<?php echo app()->request->getQueryParam("homeworkID")?>}, function (result) {
                $("#up-manage").html(result);
            });
            /*上传试卷*/
            $('#up-manage').dialog({
                autoOpen: false,
                width: 600,
                title: "上传答案",
                modal: true,
                resizable: false,
                buttons: [
                    {
                        text: "确定",

                        click: function () {
                            if ($('#mySchoolPop .text').val() == 1) {
                                $(this).dialog("close");
                            }
                            else {
                                var homeworkID = "<?php echo app()->request->getQueryParam('homeworkID')?>";
                                var imgArray = [];
                                var imgInput = $(this).find(".url");
                                $.each(imgInput, function (n, index) {
                                    imgArray.push($(index).val());
                                });
                                var image = imgArray.join(",");
                                $.post("<?php echo url('student/managetask/upload-answer')?>", {homeworkID: homeworkID, image: image}, function (result) {
                                    if (result.code == 1) {

                                        popBox.alertBox(result.message);
                                        location.reload();
                                    } else {
                                        popBox.alertBox(result.message);
                                    }
                                })
                            }

                        }
                    },
                    {
                        text: "取消",

                        click: function () {
                            $(this).dialog("close");
                        }
                    }

                ]
            });
            $("#up-manage").dialog("open");
            //event.preventDefault();
            return false;
        });
//        修改答案
        $('.update').click(function () {
            $.post("<?php echo url('student/managetask/upload-answer-content')?>", {homeworkID:<?php echo app()->request->getQueryParam("homeworkID")?>}, function (result) {
                $("#up-manage").html(result);
            });
            /*上传试卷*/
            $('#up-manage').dialog({
                autoOpen: false,
                width: 600,
                title: "修改答案",
                modal: true,
                resizable: false,
                buttons: [
                    {
                        text: "确定",

                        click: function () {
                            if ($('#mySchoolPop .text').val() == 1) {
                                $(this).dialog("close");
                            }
                            else {
                                var homeworkID = "<?php echo app()->request->getQueryParam('homeworkID')?>";
                                var imgArray = [];
                                var imgInput = $(this).find(".url");
                                $.each(imgInput, function (n, index) {
                                    imgArray.push($(index).val());
                                });
                                var image = imgArray.join(",");
                                $.post("<?php echo url('student/managetask/upload-answer')?>", {homeworkID: homeworkID, image: image}, function (result) {
                                    if (result.code == 1) {

                                        popBox.alertBox(result.message);
                                        location.reload();
                                    } else {
                                        popBox.alertBox(result.message);
                                    }
                                })
                            }

                        }
                    },
                    {
                        text: "取消",

                        click: function () {
                            $(this).dialog("close");
                        }
                    }

                ]
            });
            $("#up-manage").dialog("open");
            //event.preventDefault();
            return false;
        });

        /*删除添加的试卷*/
        $('.up_img_js li').live('mouseover', function () {
            $(this).children('i').show();
        });
        $('.up_img_js li').live('mouseout', function () {
            $(this).children('i').hide();
        });
        $('.up_img_js li i').live('click', function () {
            $(this).parent().remove();
        });

//更多图片
        $('.more_js').toggle(function () {
                $(this).parent('ul').css('height', 'auto')
            },
            function () {
                $(this).parent('ul').css('height', '50px')
            }

        )

    })
</script>
