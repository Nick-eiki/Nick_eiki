<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/13
 * Time: 14:36
 */
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\EditionModel;
use frontend\models\dicmodels\KnowledgePointModel;
use yii\helpers\Html;

/** @var $this yii\web\View */
$this->title = '作业答题';
$this->blocks['requireModule']='app/classes/stu_hmwk_do_homework_paper';
?>
<div class="main col1200 clearfix stu_do_homwork" id="requireModule" rel="<?php echo publicResources_new2().'/js/app/classes/stu_hmwk_do_homework_paper.js';?>">
    <div class="container homework_title">
        <a href="javascript:history.back(-1);" class="btn bg_gray icoBtn_back return_btn"><i></i>返回</a>
        <h4 title="<?= Html::encode($homeworkData->name) ?>"><?= Html::encode($homeworkData->name) ?></h4>
    </div>
    <div class="container homwork_info">
        <div class="pd25">
            <p><em>版本：</em><?php echo EditionModel::model()->getEditionName($homeworkData->version) ?></p>

            <?php if (!empty($homeworkData->chapterId)) {
                $chapterInfo = ChapterInfoModel::findChapter($homeworkData->chapterId);
                ?>
                <p><em>章节：</em><?php foreach ($chapterInfo as $val) {
                        echo $val->chaptername;
                    } ?></p>
            <?php } elseif (!empty($homeworkData->knowledgeId)) { ?>
                <p>
                    <em>章节：</em><?php echo KnowledgePointModel::findKnowledgeStr($homeworkData->knowledgeId); ?>
                </p>
            <?php } ?>

            <?php if(isset($homeworkData->difficulty) && $homeworkData->difficulty>=0){?>
            <p><em>难度：</em><b class="<?php if ($homeworkData->difficulty == 0) {
                    echo "";
                } elseif ($homeworkData->difficulty == 1) {
                    echo "mid";
                } elseif ($homeworkData->difficulty == 2) {
                    echo "hard";
                } ?>"></b></p>
            <?php }?>
            <?php if(!empty($homeworkData->homeworkDescribe)){?>
            <p><em>简介：</em><?php echo Html::encode($homeworkData->homeworkDescribe); ?></p>
            <?php }?>
            <?php echo $this->render("//publicView/classes/_teacher_homework_rel_audio",[ 'homeworkRelAudio' => $homeworkRelAudio]); ?>
        </div>
    </div>

    <!-- 答题卡-->
    <div id="answer_card" class="container answer_card">
        <div class="answer_card_border">
            <h4 class="cont_title"><i class="t_ico_answer_card"></i>作业答题卡</h4>
            <a id="open_cardBtn" href="javascript:;" class="open_cardBtn">展开<i></i></a>
            <div class="answer_card_cont">
                <div class="pd25" style="padding-bottom: 45px;">
                    <div class="answer_paper">
                        <div class="sUI_pannel sub_title">
                            <div class="pannel_l">我的答案
                                    <?php if($isUploadedAnswer&&$isCheck !=1):?>
                                        <span>未批改完，请在批改完毕后查看</span>
                                    <?php elseif($isUploadedAnswer&&$isCheck ==1):?>
                                        <span>已批改</span>
                                <?php endif;?>
                            </div>

                            <?php if($isUploadedAnswer&&$isCheck ==1):
                                    $result = "";
                                    $correctLevel = $answerInfo->correctLevel;
                                    if ($correctLevel == 1) {
                                        $result =  '<strong class="bad">差</strong><b>（作业做得有点差，必须努力哟）</b>';
                                    } elseif ($correctLevel == 2) {
                                        $result =  '<strong class="mid">中</strong><b>（作业做得还可以，下次努力哟）</b>';
                                    } elseif ($correctLevel == 3) {
                                        $result =  '<strong class="good">良</strong><b>（作业做得不错，继续努力哟）</b>';
                                    } elseif ($correctLevel == 4) {
                                        $result =  '<strong class="best">优</strong><b>（作业做得非常好，记得保持哟）</b>';
                                    }
                                ?>
                            <div class="pannel_r"><span> <?php echo $result;?></span></div>
                            <?php endif;?>
                        </div>

                        <div class="upImgFile" style="margin-top: 20px">
                            <ul class="clearfix">

                        <!--如果没有上传作业，显示删除按钮class-->
                                <?php $delBtnClass="";if(!$isUploadedAnswer){$delBtnClass = "delBtn";}?>
                                <?php if (!empty($answerImageArray)) {
                                    foreach ($answerImageArray as $v) { ?>
                                        <li><img src="<?= $v; ?>" alt=""><span class="<?php echo $delBtnClass;?>"></span></li>
                                    <?php }
                                } ?>
                        <!--如果没有上传作业，显示添加按钮-->
                                <?php if(!$isUploadedAnswer):?>
                                    <li class="addResult disabled">
                                        <a href="javascript:;" class="uploadFileBtn">
                                            <i></i>
                                            还可以添加<span>20</span>张图片
                                            <?php
                                            $t1 = new frontend\widgets\xupload\models\XUploadForm;
                                            /** @var $this BaseController */
                                            echo  \frontend\widgets\xupload\XUploadRequire::widget( array(
                                                'url' => Yii::$app->urlManager->createUrl("upload/pic"),
                                                'model' => $t1,
                                                'attribute' => 'file',
                                                'autoUpload' => true,
                                                'multiple' => true,
                                                'options' => array(
                                                    'acceptFileTypes' => new \yii\web\JsExpression('/(\.|\/)(jpg|png|jpeg)$/i'),
                                                    'maxFileSize' => 4*1024*1024,
                                                    "done" => new \yii\web\JsExpression('done'),
                                                    "processfail" => new \yii\web\JsExpression('function (e, data) {var index = data.index,file = data.files[index]; if (file.error) {alert(file.error);}}')
                                                ),
                                                'htmlOptions' => array(
                                                    'id' => 'fileupload',
                                                )
                                            ));
                                            ?>


<!--                                            <input id="ytt1" type="hidden" value="" name="XUploadForm[file]">-->
<!--                                            <input id="t1" class=" file" name="XUploadForm[file]" type="file">-->
                                        </a>
                                    </li>
                                <?php endif;?>
                            </ul>
                        <!--如果没有上传作业，显示按钮-->
                            <?php if(!$isUploadedAnswer):?>
                                <div class="popBtnArea tc">
                                    <button type="button" class="btn40 bg_blue okBtn" style="width: 120px">交作业</button>
                                </div>
                            <?php endif;?>
                        </div>
                        <?php
                        //批改语音 如果没有上传作业，则不显示
                        if(!empty($isUploadedAnswer)) {
                            $hworkAnCorrectAudio = $answerInfo->getHomeworkAnswerCorrectAudio()->all();
                            echo $this->render("//publicView/classes/_teacher_homework_answer_correct_audio", ["hworkAnCorrectAudio" => $hworkAnCorrectAudio]);
                        }?>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- 作业区-->
    <div class="container testpaperArea">
        <h4 class="cont_title"><i class="t_ico_file"></i>作业内容</h4>
        <div class="pd25">
            <div id="slide" class="slide">
                <div class="slidePaperWrap mc">
                    <ul id="slidePaperList" class="slidePaperList ">
                        <?php $imageArray = $homeworkData->homeworkImages;
                        foreach ($imageArray as $v) {
                            ?>
                            <li><img data-type='header' onerror="userDefImg(this);" src="<?= publicResources() . $v->url ?>" alt=""/><span></span></li>
                        <?php } ?>
                    </ul>
                    <a href="javascript:;" id="prevBtn" class="slidePaperPrev">上一页</a> <a href="javascript:;" id="nextBtn" class="slidePaperNext">下一页</a> </div>
                <div class="sliderBtnBar hide"></div>
                <div class="slidClip">
                    <span onselectstart="return false" class="ClipPrevBtn">prev</span>
                    <div class="slidClipWrap">
                        <div class="slidClipArea">
                            <?php $imageArray = $homeworkData->homeworkImages;
                            foreach ($imageArray as $v) {
                                ?>
                            <a><img data-type='header' onerror="userDefImg(this);" src="<?= publicResources() . $v->url ?>" alt=""/></a>
                            <?php } ?>
                        </div>
                    </div>
                    <span onselectstart="return false" class="ClipNextBtn">next</span>

                </div>
            </div>

        </div>


    </div>


</div>


<script>
    $(".popBtnArea .okBtn").click(function () {
        imageArray = [];
        $(".upImgFile").find("img").each(function (index, el) {
            img = $(el).attr("src");
            imageArray.push(img);
        });
        if (imageArray.length != 0) {
            relId = "<?=app()->request->getQueryParam('relId')?>";
            $.post("<?=url('classes/managetask/upload-answer')?>", {
                relId: relId,
                image: imageArray
            }, function (result) {
                require(['popBox'],function(popBox){

                    if(result.success){
                        popBox.successBox(result.message);
                        setTimeout(function(){
                            location.reload();
                        },2000);

                    }
                });
            })
        }else {
            require(['popBox'],function(popBox){
                popBox.errorBox("请添加作业答案");
            });
        }
    });


    done = function (e, data) {
        $.each(data.result, function (index, file) {
            if (file.error) {
                require(['popBox'],function(popBox){
                    popBox.errorBox(file.error);
                });
                return;
            }

            var liSize=$('.upImgFile').find('li').size();
            $('.uploadFileBtn').find('span').html(20-liSize);
            if(liSize == 20){
                $('.addResult').hide();
            }
            if(liSize>=21){
                require(['popBox'],function(popBox){
                    popBox.errorBox('最多传20张图片');
                });
                return false;
            }

            $('<li><img src="'+ file.url+'" alt=""><span class="delBtn"></span></li>').insertBefore(".addResult");

        });
    };

</script>