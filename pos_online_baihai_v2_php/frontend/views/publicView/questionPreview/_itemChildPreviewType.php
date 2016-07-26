<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-2-8
 * Time: 上午11:45
 */
use frontend\components\helper\LetterHelper;
use yii\helpers\Html;

if (!isset($no)) {
    $no = '';
}
?>
<?php if ($item->showTypeId == 1 || $item->showTypeId == 2) { ?>
    <p>小题<?php echo $no ?>: <?php echo $item->content ?></p>
    <div class="checkArea">
        <?php
        $result = json_decode($item->answerOption); 
        $select = (from($result)->select(function ($v) {
            return LetterHelper::getLetter($v->id) . '&nbsp;' . $v->content;
        }, '$k')->toArray());
//        if ($item->showTypeId == 1) {
//            echo Html::radioList('item[' . $item->questionId . ']', '', $select, array('separator' => '','class'=>'radio','encode'=>false));
//        } else {
//            echo Html::checkboxList('item[' . $item->questionId . ']', '', $select, array('separator' => '','encode'=>false));
//        }
        ?>
    </div>
<?php } ?>

<?php if ($item->showTypeId == 3) { ?>
    <p>小题<?php echo $no ?>: <?php echo $item->content ?></p>
    <div class="checkArea">
        <?php if (empty($item->childQues)) { ?>
            <p><label></label><input type="text" class="text" name="item[<?php $item->questionId ?>]" title=""/></p>
        <?php } else {
            foreach ($item->childQues as $key => $i) {
                ?>
                <p><label><?php echo $key+1 ?>、<?php echo  $i->content ?> </label><input type="text" class="text" name="item[<?php $i->questionId ?>]"/></p>
            <?php }
        }
        ?>
    </div>
<?php } ?>

<?php if ($item->showTypeId == 4) { ?>
    <p>小题<?php echo $no ?>: <?php echo $item->content ?></p>
    <div class="checkArea">
        <label>回答:</label><?php echo  Html::textArea('item[' . $item->questionId . ']') ?>
    </div>
<?php } ?>