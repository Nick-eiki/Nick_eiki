<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2015/8/4
 * Time: 15:31
 */
use frontend\components\helper\AnswerHelper;

//答案数
$replyNumber = AnswerHelper::ReplyNumber($val->aqID);
//同问数
$alsoAsk = AnswerHelper::AlsoAsk($val->aqID);
?>

	<span><a href="javascript:;" class="btn reply bg_blue_l">回答</a></span>
	<span>
		<a href="javascript:;" class="btn answer bg_blue_l" aqid="<?php echo $val->aqID; ?>">
		答案
		<em>(<b><?php echo $replyNumber; ?></b>)</em>
		</a>
	</span>
	<span>
		<a href="javascript:;" class="btn bg_blue_l quiz quiz_btn_add" val="<?php echo $val->aqID; ?>"
	   user="<?php echo $val->creatorID; ?>" uuser="<?php echo user()->id; ?>">
		同问(<em id="same<?php echo $val->aqID; ?>" val="<?php echo $alsoAsk; ?>"><?php echo $alsoAsk; ?></em>)
		</a>
	</span>
<?php if ($val->creatorID == user()->id) { ?>
	<?php if (loginUser()->isTeacher()) { ?>
		<span><a href="<?php echo url('teacher/answer/update-question', array('aqId' => $val->aqID)) ?>"
		         class="btn bg_blue_l">修改</a></span>
	<?php } elseif (loginUser()->isStudent()) { ?>
		<span><a href="<?php echo url('student/answer/update-question', array('aqId' => $val->aqID)) ?>"
		         class="btn bg_blue_l">修改</a></span>
	<?php }
} ?>