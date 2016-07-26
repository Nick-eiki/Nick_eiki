<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/11
 * Time: 10:49
 */
use frontend\components\helper\ImagePathHelper;
use frontend\components\WebDataCache;

/* @var $this \yii\web\View */
/** @var common\models\search\Es_testQuestion $item */


?>
<?php
    $showType = $item->getQuestionShowType();
    $isMaster = $item->getQuestionChildCache();

    ?>
    <div class="pd25">
        <?php if ($showType == 1 || $showType == 2) { ?>
            <div class="Q_title">
                <p><?php echo $item->content ?></p>
            </div>

            <?php
            if (!empty($isMaster)) {
                echo $this->render('//publicView/topic_preview/_haschild_item_answer', ['childList' => $isMaster, 'mainId' => $item->id]);
            } else {
                ?>
                <div class="Q_cont">
                    <?php
                    if ($item->answerOption != '' && $item->answerOption != null) {
                        echo getHomeworkQuestionContent($item);
                    }
                    ?>
                </div>
            <?php } ?>
        <?php } ?>

        <?php if ($showType == 3 || $showType == 4 || $showType == 5 || $showType == 6 || $showType == 7) { ?>
            <div class="Q_title">
                <p><?php echo $item->content ?></p>
            </div>
            <?php $isMaster = $item->getQuestionChildCache();
            echo $this->render('//publicView/topic_preview/_haschild_item_answer', ['childList' => $isMaster, 'mainId' => $item->id]);
            ?>
        <?php } ?>

        <?php if ($showType == 8) { ?>
            <p><?php
                $imgArr = ImagePathHelper::getPicUrlArray($item->content);
                foreach ($imgArr as $imgVal) {
                    echo '<img src="' . $imgVal . '" width="874">';
                }
                ?></p>
        <?php } ?>

        <?php if ($showType == 9) { ?>
            <div class="Q_title">
                <p><?php echo $item->content ?></p>
            </div>
            <?php
            $isMaster = $item->getQuestionChildCache();
            if (!empty($isMaster)) {
                echo $this->render('//publicView/topic_preview/_haschild_item_answer', ['childList' => $isMaster, 'mainId' => $item->id]);
            } else {
                ?>
                <div class="Q_cont">
                    <?php
                    echo $item->getJudgeQuestionContent();
                    ?>
                </div>
            <?php } ?>
        <?php } ?>

        <div class="sUI_pannel btnArea">
            <button type="button" class="bg_white icoBtn_open show_aswerBtn">查看答案解析 <i></i></button>
        </div>
        <div class="A_cont">
            <p><em>答案：</em><?php echo getNewAnswerContent($item); ?></p>
            <?php if (WebDataCache::getShowTypeID($item->tqtid) != 8) { ?>

                <?php if (!empty($item->analytical) && trim(strip_tags($item->analytical)) != '略') { ?>
                    <p><em>解析：</em> <?= $item->analytical; ?>
                <?php } ?></p>
            <?php } ?>
        </div>
    </div>


