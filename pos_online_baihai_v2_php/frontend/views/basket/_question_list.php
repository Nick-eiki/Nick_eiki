<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2016/2/24
 * Time: 14:44
 */
use common\models\search\Es_testQuestion;
use frontend\components\helper\ImagePathHelper;
use frontend\components\WebDataCache;

foreach ($questionCartQuestion as $key=>$item) {
	$esQuestionData = Es_testQuestion::find()->where(["id"=>$item->questionId])->one();
	/** @var common\models\search\Es_testQuestion $esQuestionData */
	$showType = $esQuestionData->getQuestionShowType();
	$isMaster = $esQuestionData->getQuestionChildCache();
?>
<div class="quest join_basket">
	<div class="sUI_pannel quest_title">
		<div class="pannel_l">
			<b><?php echo $key+1?></b>
		</div>
		<div class="pannel_r" style="margin-right: 25px">
           <span><a href="javascript:;" class="move_up_btn"  cartQuestionId="<?php echo $item->cartQuestionId?>"><i></i> 上移</a></span>
            <span><a href="javascript:;" class="move_down_btn"  cartQuestionId="<?php echo $item->cartQuestionId?>"><i></i> 下移</a></span>
			<span><a href="javascript:;" class="del_btn del_question"  cartQuestionId="<?php echo $item->cartQuestionId?>" orderNumber="<?=$item->orderNumber?>"><i></i>删除</a></span>
		</div>
	</div>
	<div class="pd25" cartQuestionId="<?php echo $item->cartQuestionId?>" >
			<?php if ($showType == 1 || $showType == 2) { ?>
				<div class="Q_title">
					<p><?php echo $esQuestionData->content ?></p>
				</div>

				<?php
				if (!empty($isMaster)) {
					echo $this->render('//publicView/basket/_haschild_item_answer', ['childList' => $isMaster, 'mainId' => $esQuestionData->id]);
				} else {
					?>
					<div class="Q_cont">
						<?php
						if ($esQuestionData->answerOption != '' && $esQuestionData->answerOption != null) {
							echo getHomeworkQuestionContent($esQuestionData);
						}
						?>
					</div>
				<?php } ?>
			<?php } ?>

			<?php if ($showType == 3 || $showType == 4 || $showType == 5 || $showType == 6 || $showType == 7) { ?>
				<div class="Q_title">
					<p><?php echo $esQuestionData->content ?></p>
				</div>
				<?php $isMaster = $esQuestionData->getQuestionChildCache();
				echo $this->render('//publicView/basket/_haschild_item_answer', ['childList' => $isMaster, 'mainId' => $esQuestionData->id]);
				?>
			<?php } ?>

			<?php if ($showType == 8) { ?>
				<p><?php
					$imgArr = ImagePathHelper::getPicUrlArray($esQuestionData->content);
					foreach ($imgArr as $imgVal) {
						echo '<img src="' . $imgVal . '" width="874">';
					}
					?></p>
			<?php } ?>

			<?php if ($showType == 9) { ?>
				<div class="Q_title">
					<p><?php echo $esQuestionData->content ?></p>
				</div>
				<?php
				$isMaster = $esQuestionData->getQuestionChildCache();
				if (!empty($isMaster)) {
					echo $this->render('//publicView/basket/_haschild_item_answer', ['childList' => $isMaster, 'mainId' => $esQuestionData->id]);
				} else {
					?>
					<div class="Q_cont">
						<?php
						echo $esQuestionData->getJudgeQuestionContent();
						?>
					</div>
				<?php } ?>
			<?php } ?>

		<div class="sUI_pannel btnArea">
			<button type="button" class="bg_white icoBtn_open show_aswerBtn">查看答案解析 <i></i></button>
		</div>
		<div class="A_cont">
			<div class="answerBar">
				<h6>答案:</h6>
				<p><?php echo getNewAnswerContent($esQuestionData); ?></p>
			</div>
	<?php if (WebDataCache::getShowTypeID($esQuestionData->tqtid) != 8) { ?>
			<div class="analyzeBar">
				<h6>解析：</h6>
				<p><?php if (empty($esQuestionData->analytical)) {
						echo '略';
					} else {
						echo $esQuestionData->analytical;
					} ?></p>
			</div>
	<?php } ?>
		</div>


	</div>

</div>

<?php } ?>