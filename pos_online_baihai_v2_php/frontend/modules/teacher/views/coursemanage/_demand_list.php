<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/3/19
 * Time: 18:02
 */
?>
<div class="docPack pr" id="demand_list">
	<?php  foreach($model as $val){ ?>
		<dl class="docBagDetail_list clearfix">
			<dt><img src="<?php echo publicResources();?>/images/video.png" alt="" /></dt>
			<dd>
				<h4>
					<a href="<?php  echo url('/teacher/coursemanage/demand-details',array('courseID'=>$val->courseID)) ?>">
						<em>[<?php echo $val->typeName; ?>]</em>
						<?php echo $val->courseName?>
						<?php if($val->isShare == 0){?>
							<em style="color: #FF0000">（未分享）</em>
						<?php }elseif($val->isShare == 1){ ?>
							<em style="color: #66cccc">（已分享）</em>
						<?php } ?>
					</a>
				</h4>
			</dd>
			<dd>
				<?php if(isset($val->courseBrief)){ ?>
				<i>简介：</i><?php  echo mb_substr(strip_tags($val->courseBrief), 0, 48, 'utf-8'); ?>
				<?php } ?>
			</dd>
			<dd><dd><a href="<?php  echo url('/teacher/coursemanage/demand-details',array('courseID'=>$val->courseID)) ?>" class="a_button bg_blue w50 look_btn">去观看</a></dd></dd>
		</dl>
	<?php   } ?>
		<?php
		 echo \frontend\components\CLinkPagerExt::widget( array(
				'pagination' => $pages,
				'updateId' => '#demand_list',
				'maxButtonCount' => 10
			)
		);
		?>
</div>