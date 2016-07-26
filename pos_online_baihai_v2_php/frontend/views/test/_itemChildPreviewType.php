<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-2-8
 * Time: 上午11:45
 */
use frontend\components\helper\LetterHelper;
use frontend\components\helper\StringHelper;

if (!isset($no)) {
    $no = '';
}
?>
<?php if ($item->showTypeId == 1 || $item->showTypeId == 2) { ?>
    <p>小题<?php echo $no ?>: <?php echo $item->content ?></p>
    <div class="checkArea">
        <?php
        $result = json_decode($item->answerOption);
        $select = (from($result)->each(function ($v) {
            echo    ''.LetterHelper::getLetter($v->id) . '、' . StringHelper::html_strip_tags(['p','br'], $v->content).'          ';
        }, '$k'));
        ?>
    </div>
<?php } ?>

<?php if ($item->showTypeId == 3) { ?>
    <p>小题<?php echo $no ?>: <?php echo $item->content ?></p>
    <div class="checkArea">
        <?php if (empty($item->childQues)) { ?>
            <p><label></label><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></p>
        <?php } else {
            foreach ($item->childQues as $key => $i) {
                ?>
                <p><label><?php echo $key+1 ?>、<?php echo  $i->content ?> </label><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></p>
            <?php }
        }
        ?>
    </div>
<?php } ?>

<?php if ($item->showTypeId == 4) { ?>
    <p>小题<?php echo $no ?>: <?php echo $item->content ?></p>
    <div class="checkArea">
        <label>回答:</label><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
    </div>
<?php } ?>