<?php

/**
 * @var BaseAuthController $this
 */
use frontend\components\helper\LetterHelper;
use frontend\components\helper\StringHelper;
use frontend\components\WebDataCache;

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
<?php
if (WebDataCache::getShowTypeID($item->tqtid) == 1 || WebDataCache::getShowTypeID($item->tqtid) == 2) { ?>
    <p>小题<?php echo $no ?>: <?php echo StringHelper::htmlPurifier($item->content) ?></p>
    <div class="checkArea">
        <?php
        $result = json_decode($item->answerOption);
        $result=$result==null?array():$result;
        try {
            $select = (from($result)->select(function ($v) {
                if( isset($v->id) && isset( $v->content)){
                    return '<label><em>'.LetterHelper::getLetter($v->id) . '</em>&nbsp;<p>' . StringHelper::htmlPurifier($v->content).'</p></label>';
                }
                return '';
            }, '$k')->toArray());
            foreach($select as $v1)
            {
                echo  $v1;
            }

        }catch(Exception $e){

        }
        ?>
    </div>
<?php } ?>

<?php if (WebDataCache::getShowTypeID($item->tqtid) == 3) { ?>
    <p>小题<?php echo $no ?>: <?php echo StringHelper::htmlPurifier($item->content) ?></p>
    <div class="checkArea">
        <?php if (empty($item->childQues)) { ?>
        <?php } else {
            foreach ($item->childQues as $key => $i) {
                ?>
                <p><label><?php echo $key+1 ?>、<?php echo  StringHelper::htmlPurifier($i->content) ?> </label></p>
            <?php }
        }
        ?>
    </div>
<?php } ?>

<?php if (WebDataCache::getShowTypeID($item->tqtid) == 4||WebDataCache::getShowTypeID($item->tqtid) == 9) { ?>
    <p>小题<?php echo $no ?>: <?php echo StringHelper::htmlPurifier($item->content) ?></p>
<?php } ?>

