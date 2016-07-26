<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-25
 * Time: 上午10:31
 */
use frontend\components\helper\AreaHelper;
use frontend\components\helper\ImagePathHelper;
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */  $this->title="上传试卷详细";
?>
<div class="currentRight grid_16 push_2 up_work_details">
    <div class="noticeH clearfix noticeB">
        <h3><?php echo $testResult->name ?></h3>


    </div>
    <hr>
    <div class="work_detais_cent">
        <h4><?php echo $testResult->name ?></h4>
        <ul class="detais_list">
            <li>
                <span>地区：<i><?php echo AreaHelper::getAreaName($testResult->provience) . "&nbsp" . AreaHelper::getAreaName($testResult->city) . "&nbsp" . AreaHelper::getAreaName($testResult->country) ?></i></span><span>年级：<i><?php echo $testResult->gradename ?></i></span><span>科目：<i><?php echo $testResult->subjectname ?></i></span><span>版本：<i><?php echo $testResult->versionname ?></i></span>
            </li>
            <li><span>知识点：<i><?php echo KnowledgePointModel::findKnowledgeStr($testResult->knowledgeId) ?></i></span>
            </li>
            <li><span>科目总评：<i><?php echo $evaluateResult->summary ?></i></span></li>
            <li><span>试卷简介：<i><?php echo $testResult->paperDescribe ?></i></span></li>
        </ul>
        <div class="details_b" id="details_b">
            <div class="species clearfix">
                <p>试卷内容：</p>

                <div class="species_Right">
                    <ul class="minute minute70 clearfix ">
                        <?php foreach (ImagePathHelper::getPicUrlArray($testResult->imageUrls) as $key => $value) { ?>
                            <?php if ($key == 0) { ?>
                                <li>
                                <span>
                                	<img alt="" src="<?php echo publicResources() . $value ?>">
                                    <em><a href="<?php echo url('student/managepaper/upload-preview', array('examID' => app()->request->getQueryParam('examID'))) ?>">试卷预览</a></em>
                                </span>
                                    <i></i>
                                </li>
                            <?php } else { ?>
                                <li>
                                    <span><img alt="" src="<?php echo publicResources() . $value ?>"></span>
                                    <i></i>
                                </li>
                            <?php }
                        } ?>
                    </ul>
                </div>

            </div>
            <hr class="hr_sp">
            <div class="exa">
                <?php $loadOrDateAnswer = $answerResult->isUploadAnswer ? "修改答案" : "上传答案" ?>
                <dl class="exa_list">
                    <dt>
                    <p class="paper_btn">
                        <!--当已经上传一些东西时，上传按钮的文字会变成继续上传!-->
                        <?php if(!$answerResult->isCheck){?>
                        <button type="button" class="bg_green exa_btn up_popo"><?php echo $loadOrDateAnswer ?></button>
                       <?php }?>
                    </p>

                    </dt>
                    <dd>
                        <ul class="anlist clearfix" style="height:50px; overflow:hidden;">
                            <?php if($answerResult->isUploadAnswer){ foreach ($answerResult->testCheckInfoS as $key => $value) {
                                if ($key < 8) { ?>
                                    <li><img src="<?php echo publicResources() . $value->imageUrl ?>" alt=""></li>
                                <?php } elseif ($key == 8) { ?>
                                    <li class="more more_js" title="点击这里查看更多"><img
                                            src="<?php echo publicResources() ?>/images/more.png" alt=""></li>
                                    <li><img src="<?php echo publicResources() . $value->imageUrl ?>" alt=""></li>
                                <?php } elseif ($key > 8) { ?>
                                    <li><img src="<?php echo publicResources() . $value->imageUrl ?>" alt=""></li>
                                <?php }
                            }  } ?>
                        </ul>
                    </dd>
                    <?php if ($answerResult->isCheck) { ?>
                        <dd class="answer">
                            <p class="clearfix"><strong>我的答案<i>(已批阅)</i></strong><span>总成绩：<?php echo $answerResult->testScore?></span></p>
                            <ul class="ul_list clearfix" style="height:50px; overflow:hidden;">
                                <?php foreach ($answerResult->testCheckInfoS as $k => $v) {
                                    if ($k < 8) { ?>
                                        <li class=""><img src="<?php echo publicResources() . $v->imageUrl ?>" alt="">
                                        </li>
                                    <?php } elseif ($k == 8) { ?>
                                        <li class="more more_js"><img
                                                src="<?php echo publicResources() ?>/images/more.png" alt=""></li>
                                        <li class=""><img src="<?php echo publicResources() . $v->imageUrl ?>" alt="">
                                        </li>

                                    <?php } elseif ($k > 8) { ?>
                                        <li class=""><img src="<?php echo publicResources() . $v->imageUrl ?>" alt="">
                                        </li>

                                    <?php }
                                } ?>
                            </ul>
                            <p>
                                <a href="<?php echo url('student/managepaper/view-correct', array('examID' => app()->request->getQueryParam('examID'))) ?>"
                                   class="a_button bg_blue exa_btn">查看批改</a></p>
                        </dd>
                    <?php } ?>
                    <?php if(isset($answerResult->otherTestAnswerID)&&!empty($answerResult->otherTestAnswerID)){?>
                    <dd class="answer">
                        <p class="clearfix"><strong><?php echo $answerResult->otherStudentName?>的答案<i>(<?php echo  $answerResult->otherIsCheck?"已批改":"未批改" ?>)</i></strong></p>
                        <ul class="ul_list clearfix" style="height:50px; overflow:hidden;">
                         <?php foreach($answerResult->otherTestAnswerInfo as $k=>$v){ if($k<7){?>
                            <li class=""><img src="<?php echo publicResources().$v->imageUrl?>" alt=""></li>
                         <?php }elseif($k==7){?>
                             <li class=""><img src="<?php echo publicResources().$v->imageUrl?>" alt=""></li>

                             <li class="more more_js"><img src="<?php echo publicResources()?>/images/more.png" alt=""></li>
                        <?php }elseif($k>7){?>
                             <li class=""><img src="<?php echo publicResources().$v->imageUrl?>" alt=""></li>

                         <?php } }?>
                        </ul>
                        <p>
                            <?php if($answerResult->otherIsCheck){?>
                            <a href="<?php echo url('student/managepaper/view-correct',array('testAnswerID'=>$answerResult->otherTestAnswerID))?>" class="a_button bg_blue exa_btn">查看批改</a>
                        <?php }else{?>
                                <a href="<?php echo url('student/managepaper/correct-paper',array('testAnswerID'=>$answerResult->otherTestAnswerID))?>" class="a_button bg_blue exa_btn">我要批改</a>

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
<div id="up-manage" class=" popBox up-manage hide" title="上传试卷">

</div>
<script>

    $(function () {

        /*删除按钮*/
        $('.minute li i').live('click', function () {
            $(this).parent().remove();
        });

//选择老师

        $('.up_popo').click(function () {
            /*上传或者修改答案*/
            var examID = "<?php echo app()->request->getQueryParam('examID')?>";
            $.post("<?php echo url('student/managepaper/upload-answer-content')?>", {examID: examID}, function (result) {
                $("#up-manage").html(result);
            });
            $('#up-manage').dialog({
                autoOpen: false,
                width: 600,
                title: "<?php echo $loadOrDateAnswer ?>",
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
                                var imageArray = [];
                                $(".url").each(function (index, el) {
                                    var imageJson = {"imageUrl": $(el).val()};
                                    imageArray.push(imageJson);
                                });
                                var imageUrl = {"imageUrls": imageArray};
                                $.post("<?php echo url('student/managepaper/upload-paper')?>", {imageUrl: JSON.stringify(imageUrl), examID: "<?php echo app()->request->getQueryParam('examID')?>"}, function (result) {
                                    if (result.code) {
                                        popBox.successBox(result.message);
                                        location.reload();
                                    }
                                    else {
                                        popBox.errorBox(result.message);
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