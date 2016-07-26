<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2015/12/11
 * Time: 17:42
 */
use frontend\components\helper\ImagePathHelper;
use frontend\components\helper\ViewHelper;

?>
<?php
if(empty($favoritesResult)){
	ViewHelper::emptyView();
}else{
foreach($favoritesResult as $favorites){ ?>
	<dl class="clearfix">
		<dt class="fl"><img width="57px" src="<?php echo ImagePathHelper::getFilePic($favorites->url);?>" alt=""></dt>
		<dd class="">
			<h4><a target="_blank" href="<?= url('teacher/prepare/view-doc', ['id' => $favorites->favoriteId]); ?>"><?=$favorites->headLine; echo '123';?></a></h4>
		</dd>
		<dd style="margin-top: -12px;"> <span class="lesson_plan btn"><?php echo $this->render('_type_view', ['item' => $favorites, 'type' => '1']); ?></span>
			<span class="time gray_d"><?= $favorites->createTime;?></span>
		</dd>
	</dl>
<?php }} ?>