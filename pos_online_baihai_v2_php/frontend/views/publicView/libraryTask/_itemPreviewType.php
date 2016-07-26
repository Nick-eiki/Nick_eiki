<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-1-13
 * Time: 下午5:08
 */
use frontend\components\helper\ImagePathHelper;
use frontend\components\helper\ViewHelper;

$showType= $item->getQuestionShowType();
?>


<?php

if ($showType==null) {
   ViewHelper::emptyView();
}?>
<h5>题 <?php echo $homeworkData->getQuestionNo($item->id) ?></h5>
<h6>
    <?php if(!empty($item->year)){?>
        【<?php echo $item->year ?>
        年】
    <?php }?>
    <?php if(isset($item->provenanceName)){echo $item->provenanceName;} ?>  <?php if(isset($item->questiontypename)){echo $item->questiontypename;} ?></h6>
<?php if ($showType== 1 || $showType== 2) { ?>

    <p><?php echo $item->content ?></p>
    <?php $isMaster = $item->getQuestionChildCache();
    if (!empty($isMaster)) {
        echo $this->render('//publicView/libraryTask/_haschild_item_answer', ['childList' => $isMaster, 'mainId' => $item->id, 'homeworkData' => $homeworkData]);
    } else {
        ?>
        <div class="checkArea">
            <?php
            if($item->answerOption!=''&&$item->answerOption!=null) {
                echo getHomeworkMainQuestionOption($item);
            }
            ?>
        </div>
    <?php } ?>
<?php } ?>

<?php if ($showType == 3 || $showType == 4  || $showType == 5 || $showType == 6 || $showType == 7) { ?>
    <p><?php echo $item->content ?></p>
    <ul class="sub_Q_List">
        <li>
            <?php $isMaster = $item->getQuestionChildCache();
            echo $this->render('//publicView/libraryTask/_haschild_item_answer', ['childList' => $isMaster, 'mainId' => $item->id, 'homeworkData' => $homeworkData]);
            ?>
        </li>
    </ul>
<?php } ?>

<?php if ($showType== 8) { ?>
	<p><?php
		$imgArr = ImagePathHelper::getPicUrlArray($item->content);
		foreach($imgArr as $imgVal){
			echo '<img src="'.$imgVal.'" width="874">';
		}
		?></p>
<?php } ?>
<?php  if ($showType== 9) { ?>
    <p><?php echo $item->content ?></p>
    <?php
    $isMaster = $item->getQuestionChildCache();
    if (!empty($isMaster)) {
        echo $this->render('//publicView/libraryTask/_haschild_item_answer', ['childList' => $isMaster, 'mainId' => $item->id,'homeworkData' => $homeworkData]);
    }else{

    }?>
<?php } ?>

