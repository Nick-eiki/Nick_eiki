<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-10-31
 * Time: 上午11:59
 */
use frontend\components\helper\LetterHelper;
use frontend\models\dicmodels\KnowledgePointModel;
use frontend\services\pos\pos_ExamService;
use yii\helpers\Html;

?>
<div class="testItem clearfix">
<img class="testpaperClip" src="<?= publicResources_new().'/images/online.png' ?>">
<?php if ($item->isCheck) { ?>
    <h5>
        成绩：<?= $item->stuSubScore ?>
    </h5>
    <p class="gray_d">本班最高分:<?= intval($item->maxScore) ?>&nbsp;&nbsp;&nbsp;本班最低: <?= intval($item->minScore) ?></p>
<?php } else { ?>
    <h5>
        <?= $item->isUploadAnswer ? '等待成绩！' : '快来上传你的答卷吧！' ?>
    </h5>
    <p class="gray_d"></p>
<?php } ?>

<div class="toolsBtnBar">
    <button type="button" class="bg_blue_l total_comtBtn">科目总评</button>
    <?php if (!$item->isUploadAnswer) { ?>
        <a href="<?= url('student/exam/on-line-answers',array('examSubID'=>$item->examSubID))?>" class="a_button w70 bg_blue_l">在线答题</a>
    <?php } else { ?>
        <a  href="<?=url('student/exam/online-answered',array('examSubID'=>$item->examSubID))?>" class="a_button w70 bg_blue_l viewtestPaperBtn">查看答卷</a>
    <?php } ?>
</div>
<div class="<?=$item->isUploadAnswer?'my_answer ':'my_answer hide'?>">
    <h6><strong>我的答卷</strong> <span class="gray"></span></h6>
   <?php $examServer=new pos_ExamService();
       $examResult=$examServer->queryTestAnswerQuestion("","",$item->testAnswerID);
   ?>
    <?php if($item->isUploadAnswer){
        ?>
    <div class="digitalFile">
        <?php if(!empty($examResult->objQuestionAnswerList)){?>
        <h6><strong>客观题答案</strong></h6>
        <p>
            <?php foreach($examResult->objQuestionAnswerList as $k=>$v){?>
            <span class="<?=$v->answerRight?'Q_correct':'Q_error'?>"><?=$v->questionId?>. <?=LetterHelper::getLetter($v->userAnswerOption)?></span>
            <?php }?>
        </p>
        <?php }?>
        <?php if(!empty($examResult->resQueAllPicList)){?>
        <h6><strong>主观题答案</strong></h6>
        <ul class="up_test_list clearfix ">
            <?php foreach($examResult->resQueAllPicList as $key=>$value){ if($key<6){?>
            <li><a href="<?=url('student/exam/view-correct',array('testAnswerID'=>$item->testAnswerID))?>"><img src="<?=publicResources().$value->picUrl?>" alt=""></a></li>
              <?php  } }?>
        </ul>
        <?php }?>
    </div>
    <?php }?>
</div>

<div class="total_comt pop">
    <i class="arrow" style="left:100px"></i>
    <span class="closeBtn"></span>

    <div class="form_list no_padding_form_list">
        <div class="row">
            <div class="formL">
                <label>试卷难点</label>
            </div>
            <div class="formR">
                <?php $knowlst = KnowledgePointModel::findKnowledgeArr($item->subjectEvaluate->knowledgePoint); ?>
                <?php foreach ($knowlst as $k_name): ?>
                    <span><?= $k_name ?></span>
                <?php endforeach ?>
            </div>
        </div>
        <div class="row">
            <div class="formL">
                <label>学习情况</label>
            </div>
            <div class="formR">
                <?= Html::encode($item->subjectEvaluate->summary) ?></div>
        </div>
    </div>

</div>
    </div>