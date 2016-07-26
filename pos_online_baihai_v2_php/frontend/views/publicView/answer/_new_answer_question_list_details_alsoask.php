<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2015/12/29
 * Time: 16:49
 */
use frontend\components\helper\ImagePathHelper;
use frontend\components\WebDataCache;

/** @var common\models\pos\SeAnswerQuestion $val */
$alsoAsk = $val->getSameQuestionResult()->all();
?>
<span class="askerList AlsoAsk_head<?php echo $val->aqID?> head_card">
	 <?php foreach($alsoAsk as $samelist) { ?>
				 <b creatorID="<?=$samelist->sameQueUserId?>">
					 <img class="icon_card" data-type="header" onerror="userDefImg(this);"  width="40px" height="40px" src="<?php echo ImagePathHelper::imgThumbnail(WebDataCache::getFaceIcon($samelist->sameQueUserId),70,70);?>" alt="" creatorID="<?=$samelist->sameQueUserId?>" source="0">
				 </b>
	 <?php } ?>
</span>
