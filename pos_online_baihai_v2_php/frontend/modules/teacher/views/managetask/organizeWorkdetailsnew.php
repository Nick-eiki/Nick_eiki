<?php
/**
 * Created by unizk.
 * User: ysd
 * Date: 2015/4/17
 * Time: 14:52
 */
use frontend\components\WebDataCache;
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\EditionModel;
use frontend\models\dicmodels\SubjectModel;
use yii\helpers\Url;

/* @var $this yii\web\View */  $this->title='组织作业详情页';
?>

<div class="grid_19 main_r">


    <div class="main_cont online_answer testPaperView">
        <div class="title">
	        <a onclick="window.history.go(-1)" class="txtBtn backBtn"></a>
            <h4>作业预览</h4>

        </div>
        <div class="correctPaper clearfix">
            <h5><?=$homeworkData->name; ?></h5>
            <ul class="up_details_list">
                <li class="clearfix">

                    <p>科目：<span><?=SubjectModel::model()->getSubjectName($homeworkData->subjectId)?></span></p>
                    <p>版本：<span><?=EditionModel::model()->getEditionName($homeworkData->version); ?></span></p>
                </li>
                <?php if($homeworkData->id!==''&& $homeworkData->chapterId !==null){ ?>
                    <li class="clearfix">
                        <p>章节：<span><?php echo ChapterInfoModel::findChapterStr($homeworkData->chapterId);?></span></p>
                    </li>
                <?php }?>
                <?php if($homeworkData->homeworkDescribe !==''){ ?>
                    <li class="clearfix">
                        <p>作业内容：<span><?=strip_tags($homeworkData->homeworkDescribe); ?></span></p>
                    </li>
                <?php  }?>

            </ul>
        </div>
        <?php
        foreach($homeworkResult as $item){
            if(!empty($item)){
            ?>

            <div class="paper" data-content-id="<?= $item->id?>">

                <?php echo $this->render('//publicView/libraryTask/_itemPreviewType', array('item' => $item, 'homeworkData' => $homeworkData)); ?>
                <div class="btnArea clearfix"><span class="openAnswerBtn fl">查看答案与解析<i class="open"></i></span> <span
                        class="r_btnArea fr">难度:<em><?php  echo WebDataCache::getDictionaryName($item->complexity)?></em>&nbsp;&nbsp;&nbsp;录入:平台</span>
                </div>
                <div class="answerArea hide">
                    <p><em>答案:</em>
                        <span><?php echo getNewAnswerContent($item); ?></span>
                    </p>
                    <?php if(WebDataCache::getShowTypeID($item->tqtid)!= 8){?>
                        <p><em>解析:</em>
                            <?php echo $item->analytical; ?>
                        </p>
                    <?php } ?>
                </div>
            </div>
            <hr>
        <?php }}?>
    </div>

</div>

<!--主体end-->
<script type="text/javascript">
    //查看答案与解析
    $('.openAnswerBtn.fl').click(function(){
        $(this).children('i').toggleClass('close');
        $(this).parents('.paper').find('.answerArea').toggle();
    })
</script>