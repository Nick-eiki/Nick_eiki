<?php
/**
 * Created by PhpStorm.
 * User: gaoli_000
 * Date: 2015/6/24
 * Time: 17:29
 */
use frontend\components\helper\ViewHelper;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<ul class="myMsg_notice">
	<?php if (empty($result->list)) {
		ViewHelper::emptyView();
	} else {
		foreach ($result->list as $val) {
			?>

			<li>
				<div class="title">
					<h4 class="<?php if ($val->isRead == '0') {
						echo 'no_see';
					} ?>"><?php echo Html::encode($val->messageContent); ?></h4>

					<div class="title_r">
						<span class="gray_d"><?php echo date('Y-m-d H:i', strtotime($val->sentTime)); ?></span>
					</div>
				</div>
				<div class="title ">
					<h4 class="font14 notice_h4">发件人：<?php echo $val->sentName; ?></h4>

					<div class="title_r notice_r">
						<?php if($val->messageType == 507001){ ?>
							<a  href="<?php echo Url::to(['/classes/managetask/details', 'classId'=>$classId ,'relId' => $val->objectID]);?>" class="btn bg_blue btn30 w120">前去完成</a>

						<?php }?>
					</div>
				</div>
			</li>
		<?php }
	} ?>
</ul>