<?php
/**
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2016/1/12
 * Time: 16:36
 */
use common\models\pos\SeHomeworkAnswerQuestionAll;
use frontend\components\WebDataCache;
use yii\helpers\Url;

$this->title='批改作业';
$this->blocks['requireModule']='app/classes/tch_hmwk_ele';
?>
<div class="main col1200 clearfix tch_homework_ele"  id="requireModule" rel="app/classes/tch_hmwk_ele">
<div class="container homework_title">
    <span class="return_btn"><a id="addmemor_btn" class="btn bg_gray icoBtn_back" href="<?=url::to(['/class/work-detail','classId'=>$classId,'classhworkid'=>$oneAnswerResult->relId])?>"><i></i>返回</a></span>
    <h4><?php echo $homeworkResult->name ?></h4>
</div>

<div class="aside col230 alpha">
    <div id="userList" class="userList">
        <?php
        /* @var  SeHomeworkAnswerInfo[] $homeworkAnswerResult */
        foreach ($homeworkAnswerResult as $k => $v) {
        $answerAllQuery = SeHomeworkAnswerQuestionAll::find()->where(['homeworkAnswerID' => $v->homeworkAnswerID])->andWhere(['>','correctResult',0]);
        $subjectArray=[];
        foreach($answerAllQuery->all() as $v1){
            $questionID=$v1->questionID;
            if(\common\helper\QuestionInfoHelper::Info($questionID)->isMajorQuestionCache()){
                array_push($subjectArray,$questionID);
            }
        }
        ?>
        <dl class="<?= $v->homeworkAnswerID == $homeworkAnswerID ? 'cur' : '' ?>"  homeworkAnswerID="<?= $v->homeworkAnswerID ?>"
            studentID="<?= $v->studentID ?>">
            <a href="<?= url::to(['/class/correct-org-hom', 'classId'=>$classId,'homeworkAnswerID'=>$v->homeworkAnswerID]) ?>">
                <dt><img onerror="userDefImg(this);" width='50px' height='50px'
                         src="<?= WebDataCache::getFaceIcon($v->studentID) ?>"></dt>
            <dd>
                <h5><?= WebDataCache::getTrueName($v->studentID) ?></h5>
                <em class="approved">已批:<span><?= count($subjectArray) ?></span>/<b><?= count($questionArray) ?></b></em>
            </dd>
                </a>
        </dl>
        <?php }?>
    </div>
</div>
<div class="container col940 omega">
    <div class="pd25">
        <div class="cor_questions">
            <ul id="q_list" style="width: 1428px;">
                <?php foreach ($questionArray as $k => $v) {
                    $answerAllResult = SeHomeworkAnswerQuestionAll::find()->where(['homeworkAnswerID' => $oneAnswerResult->homeworkAnswerID, 'questionID' => $v])->one();
                    if ($answerAllResult != null) {
                        $correctResult = $answerAllResult->correctResult;
                    } else {
                        $correctResult = 0;
                    }
                    switch ($correctResult) {
                        case 0:
                            $pic = '';
                            break;
                        case 1:
                            $pic = 'wrong_btn';
                            break;
                        case 2:
                            $pic = 'half_btn';
                            break;
                        case 3:
                            $pic = 'check_btn';
                            break;
                        default:
                            $pic='';
                    }
                    if ($k == 0) {
                        ?>
                        <li class="act" questionID="<?= $v ?>">题<?= $homeworkResult->getQuestionNo($v) ?><i
                                class="<?= $pic ?>"></i></li>
                    <?php } else { ?>
                        <li questionID="<?= $v ?>">题<?= $homeworkResult->getQuestionNo($v) ?><i class="<?= $pic ?>"></i>
                        </li>
                    <?php }
                } ?>

            </ul>
        </div>

        <div class="work_btnmark">
            <div id="slide" class="slide slide820">
                <?php if(!empty($answerImageArray)){?>
                <div class="slidePaperWrap mc">
                    <ul id="slidePaperList" class="slidePaperList ">
                        <?php foreach ($answerImageArray as $v) { ?>
                            <li><img src="<?= $v ?>" alt=""/><span></span></li>
                        <?php } ?>
                    </ul>
                    <a href="javascript:;" id="prevBtn" class="slidePaperPrev">上一页</a> <a href="javascript:;" id="nextBtn" class="slidePaperNext">下一页</a>
                </div>
                <?php }else{?>
                    <div class="empty">
                        <img src="/pub/images/unAnswered.png">
                    </div>
                <?php }?>
                <div class="sliderBtnBar hide"></div>
                <div class="slidClip">
                    <span onselectstart="return false" class="ClipPrevBtn">prev</span>
                    <div class="slidClipWrap">
                        <div class="slidClipArea">
                            <?php if(!empty($answerImageArray)){ foreach($answerImageArray as $v){?>
                                <a class="">
                                    <img src="<?=publicResources().$v?>">
                                </a>
                            <?php  } }else{?>
<!--                                <a class="">-->
<!--                                    <img src="/pub/images/unAnswered.png">-->
<!--                                </a>-->
                            <?php }?>

                        </div>
                    </div>
                    <span onselectstart="return false" class="ClipNextBtn">next</span>

                </div>
            </div>

            <div class="original">
                <span class="btn_txt">原题</span>
                <div class="exhibition">
                    <i class="arrow_v_r"></i>
                    <div class="testPaperView">
                        <div class="paper"><!--多个小题-->

                            <h5>题目1:</h5>
                            <h6>【2013年】 高考 多个小题</h6>
                            <p>题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分</p>
                            <ul class="sub_Q_List">
                                <li><span>小题1:</span>小题内容小题内容小题内容小题内容小题内容小题内容小题内容小题内容小题内容</li>
                                <li><span>小题2:</span>小题内容小题内容小题内容小题内容小题内容小题内容小题内容小题内容小题内容</li>
                                <li><span>小题3:</span>小题内容小题内容小题内容小题内容小题内容小题内容小题内容小题内容小题内容</li>
                            </ul>
                            <dl class="userUploadAnswerList clearfix">
                                <dt>您上传的答案:</dt>
                                <dd>1. <img src="../../images/answer.png" alt=""></dd>
                                <dd>2. <img src="../../images/answer.png" alt=""></dd>
                                <dd>3. <img src="../../images/answer.png" alt=""></dd>
                            </dl>
                            <div class="btnArea clearfix">
                                <span class="openAnswerBtn fl">答案与解析<i></i></span>
                                <span class="r_btnArea fr">难度:<em>5</em>&nbsp;&nbsp;&nbsp;录入:admin</span>
                            </div>
                            <div class="answerArea">
                                <p><em>答案:</em><span>1.答案</span><span>2.答案</span><span>3.答案</span></p>
                                <p><em>解析:</em>解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="cor_btn">
                <a href="javascript:;" class="check"></a>
                <a href="javascript:;" class="half"></a>
                <a href="javascript:;" class="wrong"></a>

            </div>
        </div>
    </div>
</div>

</div>