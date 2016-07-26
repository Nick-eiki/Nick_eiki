<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2015/8/4
 * Time: 14:10
 */

use common\models\pos\SeSameQuestion;
use frontend\components\WebDataCache;

$come = SeSameQuestion::find()->where(['aqID'=>$val->aqID])->all();
?>
<em class="comeFrom fl">来自：</em>
<ul class="answer_testList fl">
	<li>
        <div class="picture_listl" creatorID="<?=$val->creatorID?>">
		<img data-type="header" onerror="userDefImg(this);"  width="30px" height="30px" src="<?php echo publicResources() . WebDataCache::getFaceIcon($val->creatorID);?>" alt="" title="<?php  echo $val->creatorName; ?>">
	    </div>

	</li>
    <?php foreach($come as $samelist) { ?>
    <li>
        <div class="picture_listl" creatorID="<?=$samelist->sameQueUserId?>">
            <span><img data-type="header" onerror="userDefImg(this);"  width="30px" height="30px" src="<?php echo publicResources() . WebDataCache::getFaceIcon($samelist->sameQueUserId);?>" alt="" title="<?php echo WebDataCache::getTrueName($samelist->sameQueUserId);?>"></span>

        </div>
    </li>
    <?php } ?>

</ul>