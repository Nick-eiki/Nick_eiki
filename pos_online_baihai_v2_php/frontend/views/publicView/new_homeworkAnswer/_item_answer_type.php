<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/13
 * Time: 18:45
 */
use common\models\sanhai\ShTestquestion;
use frontend\components\helper\ImagePathHelper;
use frontend\components\WebDataCache;

if(!isset($objectiveAnswer)){
    $objectiveAnswer=null;
}
/** @var ShTestquestion $item */
$showType = $item->getQuestionShowType();
$isMaster = $item->getQuestionChildCache();
?>
<div id="<?= $item->id ?>" class="quest">
    <?php if (empty($isMaster)) { ?>
        <div class="sUI_pannel quest_title">
            <div class="pannel_l"><h5>
                    <b><?php echo $homeworkData->getQuestionNo($item->id) ?></b><?= \common\helper\QuestionInfoHelper::getQuestionTypename($item->tqtid) ?>
                </h5></div>
        </div>
    <?php } ?>
    <div class="pd25">
        <?php if ($showType == 1 || $showType == 2) { ?>
            <div class="Q_title">
                <p><?php echo $item->content ?></p>
            </div>
            <?php
            if (!empty($isMaster)) {
                    echo $this->render('//publicView/new_homeworkAnswer/_haschild_item', ['childList' => $isMaster, 'mainId' => $item->id, 'isAnswered'=>$isAnswered ,'homeworkData' => $homeworkData ,'objectiveAnswer' => $objectiveAnswer]);
            } else {
                ?>
                <div class="Q_cont">
                    <?php
                    if ($item->answerOption != '' && $item->answerOption != null) {
                        echo getHomeworkQuestionContent($item);
                    }
                    ?>
                </div>
                <div class="checkBar">
                    <?php
                            if($isAnswered){
                                 echo getHomeworkQuestionOptionAnswer($item , $objectiveAnswer);
                            }else{
                                echo getHomeworkQuestionOption($item);
                            }
                    ?>
                </div>
            <?php } ?>
        <?php } ?>

        <?php if ($showType == 3 || $showType == 4 || $showType == 5 || $showType == 6 || $showType == 7) { ?>
            <div class="Q_title">
                <p><?php echo $item->content ?></p>
            </div>
            <?php
            if (!empty($isMaster)) {
                echo $this->render('//publicView/new_homeworkAnswer/_haschild_item', ['childList' => $isMaster, 'mainId' => $item->id, 'isAnswered'=>$isAnswered , 'homeworkData' => $homeworkData,'objectiveAnswer' => $objectiveAnswer]);
            }else{?>
            <div class="checkBar">
                <div class="sbj_prompt"><i></i>主观题请在答题卡中上传图片作答</div>
            </div>
            <?php }?>
        <?php } ?>

        <?php if ($showType == 8) { ?>
            <p><?php
                $imgArr = ImagePathHelper::getPicUrlArray($item->content);
                foreach ($imgArr as $imgVal) {
                    echo '<img src="' . $imgVal . '" width="874">';
                }
                ?>
            </p>
        <?php } ?>

        <?php if ($showType == 9) { ?>
            <div class="Q_title">
                <p><?php echo $item->content ?></p>
            </div>

            <?php
            $isMaster = $item->getQuestionChildCache();
            if (!empty($isMaster)) {
                    echo $this->render('//publicView/new_homeworkAnswer/_haschild_item', ['childList' => $isMaster, 'mainId' => $item->id, 'isAnswered'=>$isAnswered ,'homeworkData' => $homeworkData ,'objectiveAnswer' => $objectiveAnswer]);
            }else{ ?>
                <div class="Q_cont">
                    <div class="checkBar">
                    <?php
                    if($isAnswered){
                        echo $item->getJudgeQuestionOptionAnswer($objectiveAnswer);
                    }else{
                        echo $item->getJudgeQuestionOption();
                    }
                    ?>
                    </div>
                </div>
            <?php }?>
        <?php } ?>

        <?php if (!empty($isAnswered)) { ?>
            <div class="sUI_pannel btnArea">
                <button type="button" class="bg_white icoBtn_open show_aswerBtn">查看答案解析 <i></i></button>
            </div>
            <div class="A_cont">
                <p><em>答案：</em><?php echo getNewAnswerContent($item); ?></p>
                <?php if (WebDataCache::getShowTypeID($item->tqtid) != 8) { ?>
                    <p><em>解析：</em><?php if(empty($item->analytical)){echo '略';}else{echo $item->analytical;} ?></p>
                <?php } ?>
            </div>
        <?php } ?>

    </div>
</div>
