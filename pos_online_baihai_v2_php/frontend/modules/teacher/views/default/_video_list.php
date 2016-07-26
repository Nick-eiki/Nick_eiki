<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/3/19
 * Time: 17:19
 */
use frontend\models\dicmodels\QueryHasFavoriteModel;

?>
<div id="srchResult">
<ul class="collect_list clearfix">
	<?php  foreach($model as $val){ ?>
		<li class="vo_list">
		<img src="<?php echo publicResources();?>/images/video.png" alt="" class="data_vo_img" />

		<h4> <a href="<?php echo url('/teacher/default/demand-details',array('teacherId' => $teacherId,'courseID'=>$val->courseID)) ?>">
				<em>[<?php echo $val->typeName; ?>]</em>
				<?php  echo $val->courseName?>
			</a></h4>
		<p><i>简介：</i><?php  echo mb_substr(strip_tags($val->courseBrief), 0, 80, 'utf-8'); ?></p>
		<a class="a_button bg_blue playBtn" href="<?php echo url('/teacher/default/demand-details',array('teacherId' => $teacherId,'courseID'=>$val->courseID)) ?>">观看</a>
		<?php $queryModel= QueryHasFavoriteModel::queryHasFavorite($val->courseID,'3',user()->id );
		?>
		<?php
		if($teacherId !=user()->id){
			if($val->isCollected == 0){

				?>
				<button class="collectBtn"  action="1" collectID="<?php echo  $val->courseID;?>" typeId="3">收藏</button>
			<?php } else { ?>
				<button class="collectBtn"  action="0" collectID="<?php echo  $queryModel->collectID;?>">取消收藏</button>
			<?php } ?>
			</li>
		<?php }} ?>
</ul>

	<?php
	 echo \frontend\components\CLinkPagerExt::widget( array(
			'pages' => $pages,
            'updateId' => '#srchResult',
			'maxButtonCount' => 10
		)
	);
	?>
</div>