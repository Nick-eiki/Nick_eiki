<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-10-31
 * Time: 上午11:59
 */
use frontend\components\helper\StringHelper;
use frontend\models\dicmodels\KnowledgePointModel;
use yii\helpers\Html;

?>
<div class="testItem clearfix">
    <img class="testpaperClip" src="<?= publicResources_new() . '/images/photo.png' ?>">

    <?php if ($item->stuSubScore) { ?>
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
        <a class="bg_blue_l a_button w80"
           href="<?= url('student/exam/upload-preview', array('examSubID' => $item->examSubID)) ?>">查看原试卷</a>


        <?php if ($item->isCheck == 1) { ?>
            <a class="bg_blue_l a_button w80"
               href="<?= url('student/exam/view-correct', array('testAnswerID' => $item->testAnswerID)) ?>">查看批改</a>
        <?php
        } else {
            if ($item->isUploadAnswer == 0) {
                ?>
                <button type="button" class="bg_blue_l upload_test_Btn">上传答卷</button>
            <?php } else {
                if ($item->isCheck == 0) { ?>
                    <button type="button" class="bg_blue_l topModify_Btn">修改答卷</button>
                <?php
                }
            }
        } ?>
    </div>
    <div class="<?= $item->isUploadAnswer ? 'my_answer' : 'my_answer hide' ?>">
        <h6><strong>我的答卷 </strong><span
                class="gray"><?= $item->isUploadAnswer ? ($item->isCheck == 0 ? "未批改" : ($item->isCheck == 1 ? "已批改" : "批改中")) : "未上传" ?></span>
        </h6>

        <div class="imgFile ">
            <ul class="up_test_list clearfix">
                <?php  $imgArr = StringHelper::splitNoEMPTY($item->imageUrls);
                foreach ($imgArr as $key => $img) {
                    ?>
                    <li data-img-url="<?= $img ?>"><img src="<?= $img ?>" alt=""><b>答案第<?= $key + 1 ?>页</b><span
                            class="delBtn"></span></li>
                <?php } ?>
                <li class="more">
                    <?php
                    $t1 = new frontend\widgets\xupload\models\XUploadForm;
                    /** @var $this BaseController */
                    echo  \frontend\widgets\xupload\XUploadSimple::widget( array(
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
                            'class' => 'more',
                        )
                    ));
                    ?>
                </li>
            </ul>
            <button type="button" class="btn40 bg_blue w120 finishBtn"
                    onclick="return savepage(this,<?= $item->examSubID ?>);return false; ">保存
            </button>
        </div>
        <!--    <div class="digitalFile">-->
        <!--                    <h6>客观题答案</h6>-->
        <!--                    <p class="answers"><span class="Q_correct">1. A</span><span class="correct">2. B</span><span class="error">3. A</span><span class="Q_error">1. A</span></p>-->
        <!--                    <h6>主观题答案</h6>-->
        <!--        <ul class="up_test_list clearfix ">-->
        <!--            --><?php //$imageArray=explode(",",$item->imageUrls);foreach($imageArray as $v){ ?>
        <!--            <li><img src="--><? //=publicResources().$v?><!--" alt=""></li>-->
        <!--           --><?php //}?>
        <!--        </ul>-->
        <!--    </div>-->
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
<script>
    isUploadAnswer = "<?=$item->isUploadAnswer?>";
    if (isUploadAnswer) {
        $(".digitalFile").show();
    }
</script>
