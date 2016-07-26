<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-1-12
 * Time: 下午4:23
 */
use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */  $this->title="作业预览";
?>
<div class="currentRight grid_13">
    <div class="item c_testPaper_view">
        <div class="itemTitle">
            <h3><i class="icon30"></i>作业预览</h3>
            <div class="fr t_my_work">
                <span>组卷人:<?php ?></span>&nbsp;&nbsp;&nbsp;<span>时间:<?php echo $result->uploadTime?></span>&nbsp;&nbsp;&nbsp;

            </div>
        </div>
        <hr>
        <br>
        <div class="itemCont">
            <h5 class="tc Qtitle"><?php echo $result->subTitle?></h5>
            <div class="subTitle tc"><?php echo AreaHelper::getAreaName($result->provience)."&nbsp".AreaHelper::getAreaName($result->city)."&nbsp".AreaHelper::getAreaName($result->country)."&nbsp".$result->gradename."&nbsp".$result->subjectname."&nbsp".$result->versionname?></div>
            <p>1.<?php echo KnowledgePointModel::findKnowledgeStr($result->knowledgeId)?></p>
            <p>2.本试卷包含<?php echo $result->questionListSize?>道题,其中<?php $questionArray=array();
                foreach($result->qeustionTypeNumList as $v){
                    array_push($questionArray,$v->questiontypename.$v->cnum."道");
                }
                echo implode(",",$questionArray);
                ?>
            </p>
            <p>3.各题分值情况</p>
        </div>
        <div class="testPaperView pr">
            <div class="paperArea">

                <?php foreach ($result->questionList as $key => $item) {
                    echo $this->render('_itemPreview', array('item' => $item));
                } ?>


            </div>


        </div>
    </div>

</div>
<script>
    $(function(){


        $('.openAnswerBtn').toggle(function(){
            (this).parents('.paper').children('.answerArea').show();
        },function(){
            (this).parents('.paper').children('.answerArea').hide();
        })


    })
</script>