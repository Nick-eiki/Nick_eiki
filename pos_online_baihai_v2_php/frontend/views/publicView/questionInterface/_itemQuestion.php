<?php
/**
 * Created by PhpStorm.
 * User: aaa
 * Date: 2016/1/11
 * Time: 19:02
 */
/* @var $this yii\web\View */
/* @var $questionModel common\models\sanhai\ShTestquestion*/
use common\helper\StringHelper;
use frontend\components\helper\ImagePathHelper;

$showType = $questionModel->getQuestionShowType();
?>
<div class="pd25 big">
    <?php if ($showType == 1 || $showType == 2) { ?>
        <div class="Q_title">
            <p><?php echo StringHelper::replacePath($questionModel->content);?></p>
        </div>
        <?php if (!empty($smallQuestion)) {
            echo $this->render('//publicView/questionInterface/_itemListQuestion', ['smallQuestion' => $smallQuestion]);
        } else { ?>
            <div class="Q_cont">
                <?php
                if ($questionModel->answerOption != '' && $questionModel->answerOption != null) {
                    echo StringHelper::replacePath(getHomeworkQuestionContent($questionModel));
                }
                ?>
            </div>
        <?php } ?>
    <?php } ?>

    <?php if ($showType == 3 || $showType == 4 || $showType == 5 || $showType == 6 || $showType == 7) { ?>
        <div class="Q_title">
            <p><?php echo StringHelper::replacePath($questionModel->content); ?></p>
        </div>
        <?php if (!empty($smallQuestion)) {echo $this->render('//publicView/questionInterface/_itemListQuestion', ['smallQuestion' => $smallQuestion]);}?>
    <?php } ?>

    <?php if ($showType == 8) { ?>
        <p><?php
            $imgArr = ImagePathHelper::getPicUrlArray(StringHelper::replacePath($questionModel->content));
            foreach ($imgArr as $imgVal) {
                echo '<img src="' . $imgVal . '" width="874">';
            }
            ?></p>
    <?php } ?>

    <?php if ($showType == 9) { ?>
        <div class="Q_title">
            <p><?php echo StringHelper::replacePath($questionModel->content) ?></p>
        </div>
        <?php
        if (!empty($smallQuestion)) {
            echo $this->render('//publicView/questionInterface/_itemListQuestion', ['smallQuestion' => $smallQuestion]);
        } else {
            ?>
            <div class="Q_cont">
                <?php
                echo $questionModel->getJudgeQuestionContent();
                ?>
            </div>
        <?php } ?>
    <?php } ?>
    <div class="A_cont">
        <p><em>答案：</em><?php echo StringHelper::replacePath(getNewAnswerContent($questionModel)); ?></p>
        <?php if ($showType != 8) { ?>
            <p><em>解析：</em>
                <?php if (empty($questionModel->analytical)) {
                    echo '略';
                } else {
                    echo StringHelper::replacePath($questionModel->analytical);
                } ?>
            </p>
        <?php } ?>
    </div>
</div>

