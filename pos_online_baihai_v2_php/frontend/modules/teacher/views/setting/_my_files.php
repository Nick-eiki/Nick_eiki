<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2015/12/11
 * Time: 17:28
 */
use frontend\components\helper\ImagePathHelper;
use frontend\components\helper\ViewHelper;

?>
<?php
if(empty($fileResult)){
	ViewHelper::emptyView();
}else{
	foreach($fileResult as $file){?>
		<dl class="clearfix">
			<dt class="fl"><img width="57px" src="<?php echo ImagePathHelper::getFilePic($file->url);?>" alt=""></dt>
			<dd class="">
				<h4><a target="_blank" href="<?= url('teacher/prepare/view-doc', ['id' => $file->id]); ?>" title="<?=$file->name;?>"><?php echo cut_str($file->name, 13); ?></a></h4>
			</dd>
			<dd style="margin-top: -12px;"> <span class="lesson_plan btn"><?php echo $this->render('_type_view', ['item' => $file, 'type' => '']); ?></span> <span class="time gray_d"><?= $file->createTime?></span></dd>
		</dl>
	<?php }} ?>