<?php

/**
 * @var BaseAuthController $this
 */
use frontend\components\helper\LetterHelper;
use frontend\components\helper\StringHelper;
use yii\helpers\Html;

/**
 * Created by PhpStorm.
 * User: yang
 * Date: 14-12-12
 * Time: 下午4:14
 */

/*
49  209	题型显示	1	单选题	1
50	209	题型显示	2	多选题	1
51	209	题型显示	3	填空题	1
52	209	题型显示	4	问答题	1
53	209	题型显示	5	应用题	1
96	209	题型显示	7	阅读理解	1
95	209	题型显示	6	完形填空	1
*/

if (!isset($no)) {
    $no = '';
}
?>
<?php if ($item->showTypeId == 1 || $item->showTypeId == 2) { ?>
    <p>小题<?php echo $no ?>: <?php echo StringHelper::htmlPurifier($item->content) ?></p>
    <div class="checkArea">
        <?php
        $result = json_decode($item->answerOption);
        $result=$result==null?array():$result;
         try {
            $select = (from($result)->select(function ($v) {
                if( isset($v->id) && isset( $v->content)){
	                return '<em>'.LetterHelper::getLetter($v->id) . '</em>&nbsp;<p>' . StringHelper::htmlPurifier($v->content).'</p>';
                }
                return '';
            }, '$k')->toArray());
            if ($item->showTypeId == 1) {
				echo Html::radioList('item[' . $item->id . ']', '', $select, array('separator' => '','class'=>'radio','encode'=>false));
			} else {
				echo Html::checkboxList('item[' . $item->id . ']', '', $select, array('separator' => '','class'=>'checkbox','encode'=>false));
            }
        }catch(Exception $e){

        }
        ?>
    </div>
<?php } ?>

<?php if ($item->showTypeId == 3) { ?>
    <p>小题<?php echo $no ?>: <?php echo StringHelper::htmlPurifier($item->content) ?></p>
    <div class="checkArea">
        <?php if (empty($item->childQues)) { ?>
            <p><label></label><input type="text" class="text" name="item[<?php $item->id ?>]" title=""/></p>
        <?php } else {
            foreach ($item->childQues as $key => $i) {
                ?>
                <p><label><?php echo $key+1 ?>、<?php echo  StringHelper::htmlPurifier($i->content) ?> </label><input type="text" class="text" name="item[<?php $i->id ?>]"/></p>
            <?php }
        }
        ?>
    </div>
<?php } ?>

<?php if ($item->showTypeId == 4) { ?>
    <p>小题<?php echo $no ?>: <?php echo StringHelper::htmlPurifier($item->content) ?></p>
    <div class="checkArea">
        <label>回答:</label><?php echo  Html::textArea('item[' . $item->id . ']') ?>
    </div>
<?php } ?>

