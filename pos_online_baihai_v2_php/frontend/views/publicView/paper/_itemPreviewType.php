<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-1-13
 * Time: 下午5:08
 */
use frontend\components\helper\ImagePathHelper;
use frontend\components\helper\LetterHelper;
use frontend\components\helper\ViewHelper;
use yii\helpers\Html;

?>

<?php if ($item->showTypeId ==null) {
   ViewHelper::emptyView();
}?>

<?php if ($item->showTypeId == 1 || $item->showTypeId == 2) { ?>
    <h5>题 <?php echo $item->questionId ?></h5>
    <h6>【<?php echo $item->year ?>
        年】 <?php if(isset($item->provenanceName)){echo $item->provenanceName;} ?>  <?php if(isset($item->questiontypename)){echo $item->questiontypename;} ?></h6>
    <p><?php echo $item->content ?></p>
    <div class="checkArea">
        <?php
        $result = json_decode($item->answerOption);
        $result=$result==null?array():$result;
        $select = (from($result)->select(function ($v) {
	        return '<em>'.LetterHelper::getLetter($v->id) . '</em>&nbsp;<p>' . $v->content.'</p>';
        }, '$k')->toArray());
        if ($item->showTypeId == 1) {
            echo Html::radioList('item[' . $item->questionId . ']', '', $select, array('separator' => '','class'=>'radio','encode'=>false));
        } else {
            echo Html::checkboxList('item[' . $item->questionId . ']', '', $select, array('separator' => '','class'=>'checkbox','encode'=>false));
        }
        ?>
    </div>
<?php } ?>

<?php if ($item->showTypeId == 3) { ?>
    <h5>题 <?php echo $item->questionId ?></h5>
    <h6>【<?php echo $item->year ?>
        年】 <?php if(isset($item->provenanceName)){echo $item->provenanceName;} ?>  <?php if(isset($item->questiontypename)){echo $item->questiontypename;} ?></h6>
    <p><?php echo $item->content ?></p>
    <div class="checkArea">
        <?php if (empty($item->childQues)) { ?>
            <p><label>填空</label><input type="text" class="text" name="item[<?php $item->questionId ?>]" title=""/></p>
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
    <h5>题 <?php echo $item->questionId ?></h5>
    <h6>【<?php echo $item->year ?>
        年】 <?php if(isset($item->provenanceName)){echo $item->provenanceName;} ?>  <?php if(isset($item->questiontypename)){echo $item->questiontypename;} ?></h6>
    <p><?php echo $item->content ?></p>
    <div class="checkArea">
        <label>回答:</label><?php echo  Html::textArea('item[' . $item->questionId . ']') ?>
    </div>
<?php } ?>

<?php if ($item->showTypeId == 5 || $item->showTypeId == 6 || $item->showTypeId == 7) { ?>
    <h5>题 <?php echo $item->questionId ?></h5>
    <h6>【<?php echo $item->year ?>
        年】 <?php if(isset($item->provenanceName)){echo $item->provenanceName;} ?>  <?php if(isset($item->questiontypename)){echo $item->questiontypename;} ?></h6>
    <p><?php echo $item->content ?></p>
    <ul class="sub_Q_List">
        <li>
            <?php
            if (isset($item->childQues)) {
                foreach ($item->childQues as $key => $i) {
                    echo $this->render('//publicView/paper/_itemChildPreviewType', array('item' => $i, 'no' => $key + 1));
                }
            }
            ?>
        </li>
    </ul>
<?php } ?>

<?php if ($item->showTypeId == 8) { ?>
	<h5>题 <?php echo $item->questionId ?></h5>
	<h6>【<?php echo $item->year ?>年】 <?php if(isset($item->provenanceName)){echo $item->provenanceName;} ?>  <?php if(isset($item->questiontypename)){echo $item->questiontypename;} ?></h6>
	<p><?php
		$imgArr = ImagePathHelper::getPicUrlArray($item->content);
		foreach($imgArr as $imgVal){
			echo '<img src="'.$imgVal.'" width="874">';
		}
		?></p>
<?php } ?>

