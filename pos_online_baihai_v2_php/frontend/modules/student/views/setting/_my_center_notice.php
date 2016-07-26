<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2015/6/24
 * Time: 14:55
 * 通知
 */
use frontend\components\helper\StringHelper;
use frontend\components\helper\ViewHelper;
use yii\helpers\Html;

?>
<script>
	$(function(){
	//家校联系通知
	$('.read_mark_btn').click(function(){
		var _this = $(this);
		var messageid = $(this).attr('messageid');
		$.post('<?=url("student/message/only-is-read");?>',{messageid:messageid},function(data){
			if(data.success){
				_this.parent().find('.seebtnJs').css('font-weight','normal');
				_this.remove()
			}else{
				popBox.errorBox(data.message);
			}
		});
	});
	})
</script>
<ul class="myMsg_notice">
	<?php
		if(!empty($noticeResult->list)):
		foreach ($noticeResult->list as $val) {
	    if(isset($val->messageType) && $val->messageType != '507201'){
	?>
	<li>
		<div class="title">
			<h4 class="<?php if($val->isRead == '0'){ echo 'no_see';}?>" style="text-indent: 0px;"><?php echo Html::encode($val->messageTiltle); ?></h4>
			<div class="title_r">
				<span class="gray_d"><?php echo date('Y-m-d H:i', strtotime($val->sentTime)); ?></span>
			</div>
		</div>
		<div class="title ">
			<h4 class="font14 notice_h4" style="text-indent: 0px;">发收人：<?php echo $val->sentName; ?></h4>
			<div class="title_r notice_r">
		    <?php if(isset($val->messageType) && $val->messageType != '507201'){ ?>
				<a href="<?php echo url('student/message/is-read',array('messageID'=>$val->messageID,'messageType'=>$val->messageType,'objectID'=>$val->objectID))?>" class="btn bg_blue btn30 w120">
					<?php if($val->messageType == 507001){?>
						前去完成
					<?php }elseif($val->messageType == 507402){?>
						前去完成
					<?php }elseif($val->messageType == 507202){?>
						查看详情
					<?php }elseif($val->messageType == 507401){?>
						前去完成
					<?php }elseif($val->messageType == 507203){?>
						查看详情
					<?php }elseif($val->messageType == 507204){?>
						查看详情
					<?php }elseif($val->messageType == 507205){?>
						查看排名变化
					<?php }?>
				</a>
			    <?php } ?>
			</div>
		</div>
		<p class="<?php if($val->isRead == '0'){ echo 'no_see';}?>" title="<?=Html::encode($val->messageContent)?>"><?php echo StringHelper::cutStr(Html::encode($val->messageContent),33) ?></p>
		<?php if(!empty($val->url)){ ?>
			<div class="QA_cont_imgBox">
				<?php $img = explode(',',$val->url);
				foreach($img as $v){
					?>
					<a class="fancybox" href="<?php echo publicResources() . $v; ?>" data-fancybox-group="gallery_<?= $val->messageID; ?>">
						<img src="<?php echo publicResources().$v;?>" width="160" height="120" alt=""/>
					</a>
				<?php } ?>
			</div>
		<?php } ?>
	</li>
	<?php }else{ ?>
	<li>
		<div class="title">
			<h4 class="<?php if($val->isRead == '0'){ echo 'no_see';}?>" style="text-indent: 0px;"><?php echo Html::encode($val->messageTiltle); ?></h4>
			<div class="title_r">
				<span class="gray_d"><?php echo date('Y-m-d H:i', strtotime($val->sentTime)); ?></span>
			</div>
		</div>
		<div class="title ">
			<h4 class="font14 notice_h4" style="text-indent: 0px;">发收人：<?php echo $val->sentName; ?></h4>
			<div class="title_r notice_r">
				<em class="crossDelBtn hide" val="<?php echo $val->messageID;?>"></em>
		    <?php if($val->isRead == '0'){ ?>
				<a href="javascript:;" class="btn bg_blue btn30 w120 read_mark_btn" messageid="<?php echo $val->messageID;?>">标记为已读</a>
		    <?php }?>
			</div>
		</div>
		<p>通知内容：<?php echo Html::encode($val->messageContent); ?></p>
		<?php if(!empty($val->url)){ ?>
			<div class="QA_cont_imgBox">
				<?php $img = explode(',',$val->url);
				foreach($img as $v){
					?>
					<a class="fancybox" href="<?php echo publicResources() . $v; ?>" data-fancybox-group="gallery_<?= $val->messageID; ?>">
						<img src="<?php echo publicResources().$v;?>" width="160" height="120" alt=""/>
					</a>
				<?php } ?>
			</div>
		<?php } ?>
	</li>
	<?php } } ?>
	<?php
	else:
		ViewHelper::emptyView();
	endif;
	?>
</ul>