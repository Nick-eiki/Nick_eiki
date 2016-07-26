<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/4/21
 * Time: 17:51
 */

use frontend\components\CLinkPagerExt;
use frontend\components\helper\ImagePathHelper;
use frontend\components\helper\LetterHelper;
use frontend\components\helper\StringHelper;
use frontend\components\helper\ViewHelper;
use frontend\components\WebDataCache;
use frontend\models\dicmodels\DegreeModel;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<script>
	$(function(){
		$('.openAnswerBtn').unbind('click').click(function(){
		$(this).children('i').toggleClass('close');
		$(this).parents('.paper').find('.answerArea').toggle();
		});

		$(".del").click(function(){
			var subId = $(this).children(".subid").val();
			var url ="<?php echo Url::to(['/student/wrongtopic/remove-topic']); ?>";
			if(confirm("确定要移除此题吗？")){
				$.post(url,{'subjectId':subId},function(msg){
					if(msg.success == true){
						popBox.successBox('移除成功');
						location.reload(true);
					}else{
						popBox.errorBox('移除失败');
					}
				});
			}
		});
		$('.sele').hide();
		$('.Determine').hide();

		$('.Determine').live('mouseover',function(){
			$(this).css('color','#F00')
		});
		$('.Determine').live('mouseout',function(){
			$(this).css('color','#000')
		});

		$('.editDifficult').live('click',function(){
			$(this).siblings('em').hide();
			$(this).siblings('.sele').show();
			$(this).siblings('a').show();
		});
		$('.Determine').live('click',function(){
			var $this = $(this);
			var url = "<?php echo Url::to(['/student/wrongtopic/modify-complexity'])?>";
			var tid= $(this).prev('.topid').val();
			var val =$(this).siblings('.sele').val();
			if(val == '') return false;
			$.post(url,{'tid':tid,'val':val},function(msg){
				if(msg.success){
					$this.siblings('.ezy').html(msg.data);
				}else{
					popBox.errorBox(msg.message);
				}
			});
			$(this).hide();
			$(this).siblings('.sele').hide();
			$(this).siblings('em').show();
		})
	})
</script>

<div class="testPaperView pr">
	<div class="paperArea">
		<?php if(empty($model->list)): ?>
			<?php ViewHelper::emptyView(); ?>
		<?php endif;?>
		<?php foreach($model->list as $tnum=>$val): ?>
			<div class="paper"><!--选择题-->

						<span class="viewDetailLink">
							<a href="<?= Url::to(['wrongtopic/wrong-detail','study'=>$val->id]); ?>" class="">查看答题记录</a>
							<?php if($val->showTypeId == 8){?>
								<a href="<?= Url::to(['wrongtopic/wrong-detail','study'=>$val->id]) ?>" class="">重做</a>
							<?php }else{?>
								<a href="<?= Url::to(['wrongtopic/re-answer','t'=>$val->id]) ?>" class="">重做</a>
							<?php } ?>

						</span>
				<?php if($val->answerRight == 1){?>
					<span class="del" title="移出错题本"> <input type="hidden" value="<?php echo $val->id ?>" class="subid"></span>
				<?php }?>
				<h5>题目<?php echo $tnum+1 ?>:</h5>
				<h6>
					<?php
					if(!empty($val->year)){
						$str = "【".$val->year."年】 ";
					}else{
						$str = '';
					}
					echo $str.Html::encode($val->quesFrom)."&nbsp;&nbsp;".StringHelper::htmlPurifier($val->questiontypename);?>
				</h6>

				<?php if($val->showTypeId == 8){ $imgUrl = ImagePathHelper::getPicUrlArray($val->content); ?>

					<ul class="paper_title clearfix">
						<?php foreach($imgUrl as $imgVal){?>
						<li><img src="<?php echo $imgVal?>" alt=""></li>
						<?php } ?>
					</ul>
				<?php }else{ ?>
				<p><?php echo StringHelper::htmlPurifier($val->content); ?></p>
				<?php } ?>

				<?php if(!empty($val->childQues)){
					echo " <ul class='sub_Q_List'>";
					foreach($val->childQues as $num=>$vals){
						if($val->showTypeId != 3){
							echo "<li><span>小题".($num+1).":</span>".$vals->content."<br>";
						}
						if($vals->showTypeId == 1 || $vals->showTypeId == 2){
							if(!empty($val->answerOption)) {
								foreach (json_decode($vals->answerOption) as $k => $v) {
									echo "<label>" . LetterHelper::getLetter($k) . ". " . preg_replace("/<(?!\/?IMG)[^<>]*>/is", "", StringHelper::htmlPurifier($v->content)) . "</label>&nbsp; &nbsp; &nbsp; &nbsp;";
								}
							}
						}
						echo "</li>";
					}
					echo "</ul>";
				}
				if($val->showTypeId == 1 || $val->showTypeId == 2){
					if(!empty($val->answerOption)){
						foreach(json_decode($val->answerOption) as $k=>$v){
							echo "<label>". LetterHelper::getLetter($k).". ".preg_replace("/<(?!\/?IMG)[^<>]*>/is","",StringHelper::htmlPurifier($v->content))."</label>&nbsp; &nbsp; &nbsp; &nbsp;";
						}
					}
				}?>

				<div class="btnArea clearfix"> <span class="openAnswerBtn fl">查看答案与解析<i class="open"></i></span>
                    <span class="r_btnArea fr">难度：<em class="ezy" ><?php echo DegreeModel::model()->getDegreeName($val->complexity)?></em>
						<?php
						echo Html::dropDownList('norm',  '',
							DegreeModel::model()->getListData(),
							array(
								"defaultValue" => false, "prompt" => "请选择 ",
								'data-validation-engine' => 'validate[required]',
								'class'=>'sele',
								'style'=>"display: none;"
							));
						?>
						<input type="hidden" value="<?php echo $val->id ?>" class="topid">
                        <a href="javascript:;" class="Determine" style="display: none;">确定</a>
                    <i class="editDifficult"></i>

                        &nbsp;&nbsp;&nbsp;录入:<?php echo WebDataCache::getTrueName($val->operater)?></span> </div>
				<div class="answerArea hide">
					<?php if(!empty($val->childQues)){
						echo "<p><em>答案：</em>";
						if($val->showTypeId == 3){
							foreach($val->childQues as $a=>$b) {
								echo " <span>".$b->answerContent . "</span>";
							}
						}else{
							foreach($val->childQues as $dnum=>$dvals){
								if($dvals->showTypeId == 1 || $dvals->showTypeId == 2){
									$CanswerContent = explode(",",$dvals->answerContent);
									echo "<span>小题".($dnum+1)."：";
									foreach($CanswerContent as $vb1){
										echo LetterHelper::getLetter($vb1)." &nbsp;&nbsp;";
									}
									echo "</span>";
								}else{

									if(!empty($dvals->childQues)){
										echo "<p>小题" . ($dnum + 1)."：";
										foreach($dvals->childQues as $a=>$b) {
											echo " <span>".$b->answerContent . "</span>";
										}
										echo "</p>";
									}else{
										if($dvals->showTypeId == 1){
											echo "<p>小题".($dnum+1)."：  <span>".LetterHelper::getLetter(StringHelper::htmlPurifier($dvals->answerContent))."   </span></p>";
										}elseif($dvals->showTypeId == 2){
											$answerContentv = explode(",",$dvals->answerContent);
											echo "<p>小题".($dnum+1)."：  <span>";
											foreach($answerContentv as $vbb){
												echo LetterHelper::getLetter($vbb);
											}
											echo "   </span></p>";
										}else{
											echo "<p>小题".($dnum+1)."：  <span>".StringHelper::htmlPurifier($dvals->answerContent)."   </span></p>";
										}

									}
								}

							}
						}

						echo "</p>";
						echo "<p><em>解析:</em>".StringHelper::htmlPurifier($val->analytical)."</p>";
					}else if($val->showTypeId == 1 || $val->showTypeId == 2){
						echo "<p><em>答案:</em><span>";
						$answerContent = explode(",",$val->answerContent);
						foreach($answerContent as $vbb){
							//echo LetterHelper::getLetter($vbb)."&nbsp;&nbsp";

                            if($vbb==='0' || $vbb === '1' || $vbb ==='2' || $vbb ==='3'){
                                echo LetterHelper::getLetter($vbb)."&nbsp;&nbsp;";
                            }else{
                                echo $vbb;
                            }
						}
						echo "</span></p>";
						echo "<p><em>解析:</em>".StringHelper::htmlPurifier($val->analytical)."</p>";
					}elseif($val->showTypeId == 8 ){
						echo "<ul class='paper_title clearfix'>";
						//教师上传的图片作业，做错的时候用的答案与解析
						if($val->answerContent != null){
							$imgUrl1 = ImagePathHelper::getPicUrlArray($val->answerContent);
							foreach($imgUrl1 as $imgVal1){
								echo '<li><img src="'.$imgVal1.'" alt=""></li>';
							}
						}
						//学生自己上传的图片题，做错的时候用的答案与解析
						if($val->analytical != null){
							$imgUrl2 = ImagePathHelper::getPicUrlArray($val->analytical);
							foreach($imgUrl2 as $imgVal2){
								echo '<li><img src="'.$imgVal2.'" alt=""></li>';
							}
						}
						echo "</ul>";

					}else{
						echo "<p><em>答案:</em><span>".$val->answerContent."</span></p>";
						echo "<p><em>解析:</em>".StringHelper::htmlPurifier($val->analytical)."</p>";
					}

					?>

				</div>
			</div>  <hr>
		<?php endforeach; ?>

	</div>
</div>
	<?php
	 echo CLinkPagerExt::widget( array(
			'pagination' => $pages,
			 'updateId' => '#wrong_list',
			'maxButtonCount' => 10
		)
	);
	?>