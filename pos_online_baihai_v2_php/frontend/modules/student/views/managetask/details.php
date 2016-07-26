<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-4-18
 * Time: 下午3:45
 */

use frontend\components\helper\AreaHelper;
use frontend\components\helper\LetterHelper;
use frontend\models\dicmodels\ChapterInfoModel;
use yii\helpers\Html;

$this->registerCssFile(publicResources_new() . '/js/fancyBox/jquery.fancybox.css' . RESOURCES_VER);
$this->registerJsFile(publicResources_new() . "/js/fancyBox/jquery.fancybox.js" . RESOURCES_VER, ['position' => \yii\web\View::POS_HEAD]);
/* @var $this yii\web\View */
$this->title = "作业详情";
?>

<div class="grid_19 main_r">
    <div class="main_cont ">
        <div class="title"><a class="txtBtn backBtn" onclick="window.history.go(-1);"></a>
            <h4>作业详情</h4>
        </div>
        <div class="correctPaper">
            <h5><?= $homeworkData->name ?></h5>
            <ul class="up_details_list" style="margin-top:10px;">
	            <?php if(!empty($homeworkData->provience) && !empty($homeworkData->city) && !empty($homeworkData->country) ){?>
                <li class="clearfix">
                    <p>
                        地区：<span><?= AreaHelper::getAreaName($homeworkData->provience) . "&nbsp;" . AreaHelper::getAreaName($homeworkData->city) . "&nbsp;" . AreaHelper::getAreaName($homeworkData->country) ?></span>
                    </p>
                </li>
	            <?php } ?>
                <li class="clearfix">
                    <?php if (!empty($homeworkData->chapterId)) { ?>
                        <p>
                            章节：<span><?= ChapterInfoModel::findChapterStr($homeworkData->chapterId) ?></span>
                        </p>
                    <?php } ?>
                </li>
                <li class="clearfix">
                    <?php if (!empty($homeworkData->homeworkDescribe)) { ?>
                        <p>作业简介：<span><?= Html::encode($homeworkData->homeworkDescribe); ?></span></p>
                    <?php } ?>
                </li>
            </ul>
            <?php if ($homeworkData->getType == 1) { ?>
                <div class="test_class_this tc">
                    <?php if ($isUploadAnswer == 0) { ?>
                        <a href="<?= url('student/managetask/new-online-answering', array('relId' => app()->request->getQueryParam('relId'))) ?>"
                           class="a_button w160 btn50 bg_green " style="margin-right:20px;">开始答题</a>
                    <?php } else { ?>
                        <a href="<?= url('student/managetask/new-online-answered', array('relId' => app()->request->getQueryParam('relId'))) ?>"
                           target="_blank" class=" a_button w160 btn50 bg_green ">作业预览</a>
                    <?php } ?>
                </div>
            <?php } ?>
            <br>

            <div>
                <?php if (!empty($questionResult->objQuestionAnswerList)) {
//                    判断是否有人提交了客观题答案了
                    $isObjAnswered = false;
                    foreach ($questionResult->objQuestionAnswerList as $v) {
                        if ($v->userAnswerOption != null) {
                            $isObjAnswered = true;
                        }
                    }
                    ?>
                    <?php if ($isObjAnswered) { ?>
                        <h6>客观题答案</h6>
                        <p>
                            <?php foreach ($questionResult->objQuestionAnswerList as $k => $v) {
                                if ($v->userAnswerOption != null) { ?>
                                    <span class="<?= $v->answerRight ? 'Q_correct' : 'Q_error' ?>"><?= $k + 1 ?>
                                        .<?= LetterHelper::getLetter($v->userAnswerOption) ?>
                    </span>
                                <?php }
                            } ?>

                        </p>
                    <?php } ?>
                <?php } ?>

                <?php if (!empty($questionResult->resQueAllPicList)) { ?>
                    <h6 class="my_answer">主观题答案</h6>
                    <ul class="up_test_list clearfix ">
                        <?php foreach ($questionResult->resQueAllPicList as $k => $v) {
                            if ($k < 7 && $k != 0) { ?>
                                <li><a><img src="<?= publicResources() . $v->picUrl ?>" alt=""></a></li>
                            <?php } elseif ($k == 0) { ?>
                                <li>
                                    <a href="<?= url('student/managetask/view-correct', array('homeworkAnswerID' => $questionResult->homeworkAnswerID)) ?>"><img
                                            src="<?= publicResources() . $v->picUrl ?>" alt=""></a></li>
                            <?php }
                        } ?>
                    </ul>
                <?php } ?>
            </div>
            <?php if ($homeworkData->getType == 0) { ?>
                <div class="slidClip"></div>
                <div class="correctPaperSlide">
                    <div class="testPaperWrap mc pr">
                        <ul class="testPaperSlideList slid">
                            <?php $imageArray = $homeworkData->homeworkImages;
                            foreach ($imageArray as $v) {
                                ?>
                                <li><img src="<?= publicResources() . $v->url ?>" alt=""/></li>
                            <?php } ?>
                        </ul>
                        <a href="javascript:;" id="prevBtn" class="correctPaperPrev">上一页</a> <a href="javascript:;"
                                                                                                id="nextBtn"
                                                                                                class="correctPaperNext">下一页</a>
                    </div>
                    <div class="sliderBtnBar"></div>
                </div>
            <?php } ?>
        </div>
        <?php if ($homeworkData->getType == 0 && $isUploadAnswer == 0) { ?>
            <div class="test_class_this tc">
                <button type="button" class="w160 btn50 bg_green c_Btn">上传答案</button>
            </div>
        <?php } ?>
        <br>

        <div class="work_checked"><!--已批改-->
            <div class="title item_title hide ">
                <h4>我的答案&nbsp;&nbsp;<span class="gray">
                        <?php
                        if ($isCheck == 2) {
                            echo '批改中';
                        } elseif ($isCheck == 0) {
                            echo '未批改';
                        } elseif ($isCheck == 1) {
                            $correctLevel = $answerInfo->correctLevel;
                            if ($correctLevel == 1) {
                                echo '已批改（差）';
                            } elseif ($correctLevel == 2) {
                                echo '已批改（中）';
                            } elseif ($correctLevel == 3) {
                                echo '已批改（良）';
                            } elseif ($correctLevel == 4) {
                                echo '已批改（优）';
                            }
                        }
                        ?>
                    </span></h4>
            </div>
            <div class="my_answer">
                <div class="imgFile hide">
                    <h6 class="hide">主观题答案</h6>
                    <ul class="up_test_list clearfix uploadPaper ">
                        <?php if (!empty($answerImageArray)) {
                            foreach ($answerImageArray as $v) { ?>
                                <li class="fancyPic">
                                    <a class="fancybox" href="<?= $v; ?>" data-fancybox-group="gallery_133813">
                                        <img src="<?= $v; ?>" data-original="<?= $v; ?>" alt="" style="display: inline;"
                                             class="lazy"></a>
                                </li>
                                <li class="delPic hide">
                                    <img alt="" src="<?= $v ?>">
                                    <span class="delBtn"></span>
                                </li>
                            <?php }
                        } ?>
                        <li class="more">


                            <?php
                            $t1 = new frontend\widgets\xupload\models\XUploadForm;
                            /** @var $this BaseController */
                            echo \frontend\widgets\xupload\XUploadSimple::widget(array(
                                'url' => Yii::$app->urlManager->createUrl("upload/paper"),
                                'model' => $t1,
                                'attribute' => 'file',
                                'autoUpload' => true,
                                'multiple' => true,
                                'options' => array(
                                    "done" => new \yii\web\JsExpression('done')
                                ,
                                ),
                                'htmlOptions' => array(
                                    'id' => 'fileupload',
                                )
                            ));
                            ?>
                        </li>
                    </ul>
                    <?php

                    if ($isCheck == 0) { ?>
                        <button type="button" class="bg_blue w120 btn40 modifyBtn hide">修改答案</button>
                    <?php } ?>

                    <button type="button" class="bg_blue w120 btn40 finishBtn hide">上传完毕</button>
                    <button type="button" class="bg_blue_l w120 btn40 cancelBtn">取 消</button>
                </div>
            </div>
            <br>

            <div class="myCorrect hide">
                <h6>我的批阅</h6>
                <ul class="up_test_list clearfix ">
                    <li><img src="../../images/test_img.png" alt=""></li>
                    <li><img src="../../images/test_img.png" alt=""></li>
                    <li><img src="../../images/test_img.png" alt=""></li>
                    <li><img src="../../images/test_img.png" alt=""></li>
                    <li><img src="../../images/test_img.png" alt=""></li>
                </ul>
            </div>
        </div>
        <!--        --><?php //}?>
    </div>
</div>
<?php $imageArray = $homeworkData->homeworkImages;
$array = array();
foreach ($imageArray as $v) {
    array_push($array, $v->url);
}
?>
<script type="text/javascript">
    $(function () {
        $(".fancybox").fancybox();
        $("img.lazy").lazyload({
            effect: "fadeIn"
        });
        imgArr =<?=json_encode($array)?>;
        $('.correctPaperSlide').testpaperSlider({ClipArr: imgArr});
        $('.test_class_this .c_Btn').click(function () {
            $(this).parent().hide();
            $('.imgFile').show().imgFileUpload();
            $(this).parent('.test_class_this').hide();
            $(".cancelBtn").show();
        });

        $('.finishBtn').click(function () {
            //$(this).hide();
            $('.item_title, .imgFile h6').show();
        });

        $(".finishBtn").click(function () {
            imageArray = [];
            $(".delPic").find("img").each(function (index, el) {
                img = $(el).attr("src");
                imageArray.push(img);
            });
            if (imageArray.length != 0) {
                relId = "<?=app()->request->getQueryParam('relId')?>";
                $.post("<?=url('student/managetask/upload-answer')?>", {
                    relId: relId,
                    image: imageArray
                }, function (result) {
                    popBox.successBox(result.message);
                    location.reload();
                })
            } else {
                popBox.errorBox("请选择图片");
            }
        });
//        取消按钮刷新
        $('.cancelBtn').click(function () {
            location.reload();
//            $('.test_class_this').show();
//            $('.imgFile').hide();
        });
        $(".modifyBtn").click(function () {
            $(".cancelBtn ").show();
            $('.delPic').show();
            $('.fancyPic').hide();
        });
        isUploadAnswer = "<?=$isUploadAnswer?>";
        getType = "<?=$homeworkData->getType?>";
        if (isUploadAnswer && getType == 0) {
//            $(".work_checked .item_title,.imgFile").show();
            $('.imgFile').show().imgFileUpload();
            $('.item_title, .imgFile h6').show();
            $(".cancelBtn").hide();
        }
    });

    done = function (e, data) {
        $.each(data.result, function (index, file) {
            if (file.error) {
                popBox.errorBox(file.error);
                return;
            }
            $('<li class="' + 'delPic' + '"><img src="' + file.url + '" alt=""><span class="delBtn"></span></li>').insertBefore($(e.target).parent());
        });
    };
</script>
