<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-4-14
 * Time: 下午4:23
 */
use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */  $this->title="试卷预览";
?>

<div class="grid_19 main_r">
    <div class="main_cont">
        <div class="title"><a href="javascript:" onclick="window.history.go(-1);" class="txtBtn backBtn"></a>
            <h4>试卷预览</h4>

            <div class="title_r"><span>组卷人：<?= loginUser()->getUserInfo($result->creator)->getTrueName(); ?></span>
                <span>组卷时间：<?= $result->uploadTime ?></span></div>
        </div>
        <div class="itemCont">
            <h4><?= $result->name ?></h4>

            <div class="subTitle tc">
                <?= AreaHelper::getAreaName($result->provience) . "&nbsp;" . AreaHelper::getAreaName($result->city) . "&nbsp;" . AreaHelper::getAreaName($result->country) . "&nbsp;" . $result->gradename . "&nbsp;" . $result->versionname ?>
            </div>
            <div class="testPaperInfo">
                <?php if(!empty($result->knowledgeId)){?>
                <p>1.考察知识点:<?= KnowledgePointModel::findKnowledgeStr($result->knowledgeId) ?></p>
                     <?php }?>
                <p>2.本试卷包含<?= $result->questionListSize ?>道题,其中
                    <?php
                    $questionArray = array();
                    foreach ($result->qeustionTypeNumList as $v) {
                        array_push($questionArray, $v->questiontypename . $v->cnum . "道");
                    }
                    echo implode(",", $questionArray);
                    ?>
                </p>

                <p>3.各题分值情况:
                    <?php foreach ($result->questionScoreList as $v) {
                        echo $v->id . "--" . (empty($v->quesScore) ? 0 : $v->quesScore) . "分" . "&nbsp;";
                    }
                    ?></p>
            </div>
        </div>
        <br>
        <a href="<?php echo url('teacher/makepaper/word',array('id'=>app()->request->getParam('paperID','')));?>">下载</a>
        <div class="testPaperView pr">

            <div class="paperArea">
                <?php foreach ($result->questionList as $key => $item) {
                    echo $this->render('//publicView/paper/_recombinationItemPreview', array('item' => $item));
                } ?>

            </div>
        </div>
    </div>
</div>
<script>
    $('.openAnswerBtn').click(function () {
        $(this).children('i').toggleClass('clospae');
        $(this).parents('.paper').find('.answerArea').toggle();
    });
    $(".backBtn").click(function () {
        window.history.go(-1);
    })
</script>
