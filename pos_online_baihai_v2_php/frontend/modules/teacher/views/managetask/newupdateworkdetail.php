<?php
/**
 * Created by PhpStorm.
 * User: unizk
 * Date: 2015/4/16
 * Time: 18:20
 */
use common\models\pos\SeHomeworkTeacher;
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\EditionModel;
use frontend\models\dicmodels\SubjectModel;

/* @var $this yii\web\View */  $this->title='上传作业详情';
/** @var  SeHomeworkTeacher $homeworkDetails */
$images= $homeworkDetails->getHomeworkImages()->select('url')->asArray()->column();
?>
<script type="text/javascript">
    $(function() {
        imgArr=<?php echo   json_encode($images);
         ?>;
        $('.correctPaperSlide').testpaperSlider({ClipArr:imgArr});
    })
</script>

<div class="grid_19 main_r">
    <div class="main_cont test_class_overall_appraisal">
        <div class="title">
	        <a onclick="window.history.go(-1)" class="txtBtn backBtn"></a>
            <h4> 作业预览</h4>
            <?php if(!$isAssignStu){?>
            <div class="title_r"><a href="<?= \yii\helpers\Url::to(['/teacher/managetask/new-update-work','homeworkid'=>$homeworkId])?>" class="btn40 w120 bg_green a_button">修改作业</a>
            </div>
            <?php }?>
        </div>
        <div class="correctPaper">
            <h5><?=$homeworkDetails->name; ?></h5>
            <ul class="up_details_list">
                <li class="clearfix">

                    <p>科目：<span><?=SubjectModel::model()->getSubjectName($homeworkDetails->subjectId)?></span></p>
                    <p>版本：<span><?=EditionModel::model()->getEditionName($homeworkDetails->version); ?></span></p>
                </li>
                <?php if($homeworkDetails->id!==''&& $homeworkDetails->chapterId !==null){ ?>
                    <li class="clearfix">
                        <p>章节：<span><?php echo ChapterInfoModel::findChapterStr($homeworkDetails->chapterId);?></span></p>
                    </li>
                <?php }?>
             <?php if($homeworkDetails->homeworkDescribe !==''){ ?>
                 <li class="clearfix">
                     <p>作业内容：<span><?=strip_tags($homeworkDetails->homeworkDescribe); ?></span></p>
                 </li>
            <?php  }?>

            </ul>
            <?php if(!empty($images)){?>
            <div class="slidClip"></div>
            <div class="correctPaperSlide">
                <div class="testPaperWrap mc pr">
                    <ul class="testPaperSlideList slid">
                        <?php

                            foreach($images as $val){
                                echo '<li><img src="'.$val.'" width="830"   alt=""/></li>';
                            }

                        ?>

                    </ul>
                    <a href="javascript:;" id="prevBtn" class="correctPaperPrev">上一页</a> <a href="javascript:;" id="nextBtn" class="correctPaperNext">下一页</a> </div>
                <div class="sliderBtnBar"></div>
            </div>
            <?php }else{
              \frontend\components\helper\ViewHelper::emptyView();
            }?>
        </div>
    </div>
</div>