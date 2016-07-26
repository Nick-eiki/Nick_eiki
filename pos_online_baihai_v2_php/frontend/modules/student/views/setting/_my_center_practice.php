<?php
/**
 * 练一练
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2015/6/25
 * Time: 11:13
 */
use frontend\components\helper\ViewHelper;
use frontend\models\dicmodels\KnowledgePointModel;
use yii\helpers\Html;

?>
<div class="title">
	<h4 class="font16">练一练</h4>
	<div class="title_r">
		<a href="<?php echo url('student/managepaper/topic-push')?>" class="gray_d underline">查看更多</a>
	</div>
</div>

<div class="titlePush">
	<div class="titlePush titlePush_main">
		<ul class="titlePush_List">
			<?php if(empty($practiceResult)){
				ViewHelper::emptyView();
			}
			foreach($practiceResult as $val){
			?>
			<li class="pr clearfix">
				<h5>[ <?php echo $val->gradename ?> <?php echo $val->subjectname;?> ] <a href="<?php
					if($val->isAnswered == 0){
						echo url('student/managepaper/start-answer',array('questionTeamID'=>$val->questionTeamID,'notesID'=>$val->notesID));
					}else if($val->isAnswered == 1){
						echo url('student/managepaper/finish-answer',array('questionTeamID'=>$val->questionTeamID,'notesID'=>$val->notesID));
					}
					?>" class="title_p"><?php echo Html::encode($val->questionTeamName);?></a>
					<?php if($val->isAnswered == '1'){?>
						<em class="gray_d">回答完毕</em>
					<?php }else{ ?>
						<em class="orenge">未回答</em>
					<?php   }?></h5>
				<p>知识点：<?php
					$res = KnowledgePointModel::findKnowledge( $val->connetID);
					foreach($res as $value){
						echo '<em style="padding-right:26px">'.$value->name.'</em>';
					}
					?>
				</p>

				<div class="clearfix titlePush_more">
					<div class="content fl gray_d">
						<em class="fl">推送记录：</em>
						<div class="txtOverCont fl">
							<div class="cont fl">
								<b><?php echo date('Y-m-d H:i', strtotime($val->createTime)); ?></b>
							</div>
						</div>
					</div>
				</div>

				<div>
					<?php if($val->isAnswered == '0'){?>
						<a class="a_button notice w120"
						   href="<?php echo url("student/managepaper/start-answer",array('questionTeamID'=>$val->questionTeamID,'notesID'=>$val->notesID));?>">答题</a>
					<?php }?>
				</div>
			</li>
			<?php } ?>
		</ul>
	</div>
</div>