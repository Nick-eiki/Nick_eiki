<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/15
 * Time: 13:27
 */
use common\models\sanhai\ShTestquestion;
use frontend\components\WebDataCache;
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\EditionModel;
use yii\helpers\Html;

/** @var $this yii\web\View */
$this->title = '答题完毕';
$this->blocks['requireModule'] = 'app/classes/stu_hmwk_do_homework';
?>


<div class="main col1200 clearfix stu_do_homwork" id="requireModule" rel="app/classes/stu_hmwk_do_homework">
    <div class="container homework_title">
        <a href="javascript:history.back(-1);" class="btn bg_gray icoBtn_back return_btn"><i></i>返回</a>
        <h4 title="<?= Html::encode($homeworkData->name) ?>"><?= Html::encode($homeworkData->name) ?></h4>
    </div>
    <div class="container homwork_info">
        <div class="pd25">
            <?php if (!empty($homeworkData->version)) { ?><p>
                <em>版本：</em><?= EditionModel::model()->getEditionName($homeworkData->version); ?></p><?php } ?>
            <?php if (!empty($homeworkData->chapterId)) { ?><p>
                <em>章节：</em><?php echo ChapterInfoModel::findChapterStr($homeworkData->chapterId); ?></p><?php } ?>
            <?php if (isset($homeworkData->difficulty) && $homeworkData->difficulty >= 0) { ?><p><em>难度：</em><b
                    class="<?php if ($homeworkData->difficulty == 1) {
                        echo 'mid';
                    } elseif ($homeworkData->difficulty == 2) {
                        echo 'hard';
                    } ?>"></b></p><?php } ?>
            <?php if (!empty($homeworkData->homeworkDescribe)) { ?><p>
                <em>简介：</em><?= Html::encode($homeworkData->homeworkDescribe); ?></p><?php } ?>
            <?php
            //布置语音
            echo $this->render("//publicView/classes/_teacher_homework_rel_audio",[ 'homeworkRelAudio' => $homeworkRelAudio]); ?>
        </div>
    </div>
    <?php if($isAnswered->isCheck){ ?>
    <!--梯队-->
    <div class="container homwork_ladder">
        <div class="portrait <?php if ($teamNum == 1) {
            echo 'one';
        } elseif ($teamNum == 2) {
            echo 'two';
        } elseif ($teamNum == 3) {
            echo 'three';
        } elseif ($teamNum == 4) {
            echo 'four';
        } else {
            echo 'five';
        } ?>">
            <img src="<?= WebDataCache::getFaceIcon(user()->id) ?>" style="vertical-align: middle;" data-type="header"
                 onerror="userDefImg(this);"/>
        </div>
        <img src="<?= publicResources_new2() ?>/images/homwork_ladder.jpg">

        <p>全国共有 <span><?= $finishTotalCount ?></span> 名同学做过这份作业，您的作业成绩排在第 <span><?= $teamNum ?></span> 梯队，在你前面的有
            <span><?= $overCount ?></span> 名同学，继续加油哦！</p>
    </div>
    <?php }?>
    <!-- 答题卡-->
    <div id="answer_card" class="container answer_card">
        <div class="answer_card_border">
            <h4 class="cont_title"><i class="t_ico_answer_card"></i>作业答题卡</h4>
            <a id="open_cardBtn" href="javascript:;" class="open_cardBtn">展开<i></i></a>

            <div class="answer_card_cont">
                <div class="pd25" style="padding-bottom: 45px;">
                    <div class="answer_ele">
                        <div class="sUI_pannel sub_title">
                            <div class="pannel_l"> 客观题</div>
                            <div class="pannel_r"><span><i class="done"></i>已答</span><span><i></i>未答</span><span><i
                                        class="uncheck"></i>未批</span><span><i class="wrong"></i>答错</span><span><i
                                        class="correct"></i>答对</span><span><i class="half"></i>半对</span></div>
                        </div>
                        <div id="ele_list" class="ele_list">
                            <?php
                            if (empty($objective)) {
                                echo '该作业无客观题';
                            } else {
                                foreach ($objective as $key => $obj) {
                                    ?>
                                    <em class="<?php if ($obj->correctResult == 1) {
                                        echo 'wrong';
                                    } elseif ($obj->correctResult == 2) {
                                        echo 'half';
                                    } elseif ($obj->correctResult == 3) {
                                        echo 'correct';
                                    } ?>"><?php echo $key ?></em>
                                <?php }
                            } ?>
                        </div>
                    </div>
                    <div class="answer_paper">
                        <div class="sUI_pannel sub_title">
                            <div class="pannel_l"> 主观题<span><?php if (!empty($subjective)) {
                                        if ($isAnswered->isCheck == 0) {
                                            echo '未批改完，请在批改完毕后查看';
                                        } elseif ($isAnswered->isCheck == 1) {
                                            echo '批改完成';
                                        } elseif ($isAnswered->isCheck == 2) {
                                            echo '批改中';
                                        }
                                    } ?></span></div>
                        </div>
                        <div id="paper_list" class="paper_list">
                            <?php
                            if (empty($subjective)) {
                                echo '该作业无主观题';
                            } else {
                                foreach ($subjective as $key => $sub) {
                                    ?>
                                    <em class="<?php if ($sub->correctResult == 1) {
                                        echo 'wrong';
                                    } elseif ($sub->correctResult == 2) {
                                        echo 'half';
                                    } elseif ($sub->correctResult == 3) {
                                        echo 'correct';
                                    } else {
                                        echo 'clip_img';
                                    } ?>"><?php echo $key ?></em>
                                <?php }
                            } ?>
                        </div>
                        <div class="upImgFile">
                            <ul class="clearfix">
                                <?php
                                $images = $picAnswer;
                                if (isset($images) && !empty($images)) {
                                    foreach ($images as $val) {
                                        ?>
                                        <li>
                                            <img src="<?= $val->url; ?>" alt="">
                                        </li>
                                    <?php }
                                } ?>
                            </ul>
                        </div>
                        <div class="statistic tc" style="margin-bottom:30px;">
                            此次作业正确率
                            <b>
                                <?= sprintf("%.2f", ($homeworkQuestionCorrectCount / count($homeworkQuestionIdResult) * 100)) ?>%
                            </b>
                        </div>
                        <?php echo $this->render("//publicView/classes/_teacher_homework_answer_correct_audio",["hworkAnCorrectAudio"=>$hworkAnCorrectAudio])?>

                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- 作业区-->
    <div class="container no_bg testpaperArea">
        <div class="testPaper">
            <?php
            foreach ($homeworkQuestion as $key => $item) {
                $questionInfo = ShTestquestion::findOne($item->questionId);
                if (empty($questionInfo)) continue;
                echo $this->render('//publicView/new_homeworkAnswer/_item_answer_type',
                    ['item' => $questionInfo, 'number' => $key + 1, 'isAnswered' => $isAnswered, 'homeworkData' => $homeworkData, 'objectiveAnswer' => $objectiveAnswer]);
            }
            ?>

        </div>
    </div>

</div>