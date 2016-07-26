<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/4/23
 * Time: 15:20
 */
use frontend\components\helper\ImagePathHelper;
use frontend\components\helper\LetterHelper;
use frontend\components\helper\StringHelper;
use frontend\models\dicmodels\DegreeModel;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */  $this->title="错题详情";
//49  209	题型显示	1	单选题	1
//50	209	题型显示	2	多选题	1
//51	209	题型显示	3	填空题	1
//52	209	题型显示	4	问答题	1
//53	209	题型显示	5	应用题	1
//96	209	题型显示	7	阅读理解	1
//95	209	题型显示	6	完形填空	1

?>
<script type="text/javascript">
		$(function() {
			$('.openAnswerBtn').unbind('click').click(function(){
				$(this).children('i').toggleClass('close');
				$(this).parents('.paper').find('.answerArea').toggle();
			});

			$('.reAnswerBtn').click(function(){
				var sid = $("#subid").val();
				location.href="<?php echo url('/student/wrongtopic/re-answer')?>"+"?t="+sid;
			});
			$('.prevPage').click(function(){
				var sid = $("#subid").val();
				var item = $("#item").val();
				location.href="<?php echo url('/student/wrongtopic/wrong-detail')?>"+"?t="+sid+"&sub="+item+"&p=1";
				<?php if(!empty($prev)){
					if($prev->cnt == 0){
						echo 'popBox.errorBox("没有了~！");';
					}
				}?>
			});
			$('.nextPage').click(function(){
				var sid = $("#subid").val();
				var item = $("#item").val();
				location.href="<?php echo url('/student/wrongtopic/wrong-detail')?>"+"?t="+sid+"&sub="+item+"&n=1";
				<?php if(!empty($next)){
					if($next->cnt == 0){
						echo 'popBox.errorBox("没有了~！");';
					}
				}?>
			})
		})
	</script>
<script>
	$(function(){

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
			var url= "<?php echo Url::to(['/student/wrongtopic/modify-complexity']);?>";
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
		});
<?php foreach($topic->list as $topicVal){
			if($topicVal->showTypeId == 8){
		?>
		//题目
		imgArr =  [<?php foreach(ImagePathHelper::getPicUrlArray($topicVal->content) as $val){?> "<?php echo $val; ?>",<?php } ?>];
		//解析
		imgArr2 = [<?php foreach(ImagePathHelper::getPicUrlArray($topicVal->analytical) as $val2){?> "<?php echo $val2; ?>",<?php } ?>];
		//
		$('#topicSlide').testpaperSlider({ClipArr:imgArr,slidClip:"#topicClip",sliderBtnBar:"#topicSliderBtnBar",next:"#topicNextBtn",prev:"#topicPrevBtn"});

		$('#analyzeAreaSlide').testpaperSlider({ClipArr:imgArr2,slidClip:"#analyzeClip",sliderBtnBar:"#analyzeSliderBtnBar",next:"#analyzeNextBtn",prev:"#analyzePrevBtn"});
<!--	--><?php }} ?>
		//切换题目和解析
		$('.show_topicArea').click(function(){
			$('.topicArea').show();
			$('.analyzeArea').hide();
		});
		$('.show_analyzeArea').click(function(){
			$('.analyzeArea').show();
			$('.topicArea').hide();
		});

		$('#re_anwser_btn').click(function(){
			$('#topicArea,.topic_detal').show();
			$('.stateBar').hide();
			$('.analyzeArea,.re_anwser_hide').hide();
			$('.topicArea .show_analyzeArea').hide();
		})

	})

</script>
<!--主体-->

<div class="grid_19 main_r">
	<div class="main_cont mistake_detail">
		<div class="title">
			<?php foreach($topic->list as $subjectName){?>
			<a href="javascript:;" onclick="window.history.go(-1)" class="txtBtn backBtn"></a>
			<h4><?php echo $subjectName->subjectname.'错题集'; ?></h4><!--名称后天调用-->
			<?php } ?>
			<div class="title_r">
				<div class="problem_r_list">
					<h5>增加题目<i></i></h5>
					<ul class="hot" style="display:none;">
						<li><a class="t_ico" href="javascript:;">增加题目<i></i></a></li>
						<li class="list this "><a class="" href="<?php echo url('student/wrongtopic/wrong-enter')?>">录入新题</a></li>
						<li class="list"><a class="" href="<?php echo url('/student/wrongtopic/take-photo-topic');?>">上传新题</a></li>
					</ul>
				</div>
			</div>
		</div>
		<!--电子版   多个小题-->
		<div class="testPaperView">
		<input type="hidden" id="subid" value="<?php echo $subId;?>">

		<?php foreach($topic->list as $val){
			if($val->showTypeId == 8){ ?>
				<input type="hidden" name="topicType" id="t_type" value="<?php echo $val->showTypeId ?>" >
				<div class="topicArea">
					<div class="correctPaper">
						<h5><?php echo $val->provenanceName." ".$val->questiontypename;?></h5>
						<div id="topicSlide" class="correctPaperSlide">
							<div class="testPaperWrap mc pr">
								<ul class="testPaperSlideList slid">
									<?php
										$topicImg=ImagePathHelper::getPicUrlArray($val->content);
										foreach($topicImg as $topicVal){
									?>
									<li><img src="<?php echo $topicVal;?>" width="830" height="" alt=""/></li>
									<?php } ?>
								</ul>
								<a href="javascript:;" id="topicPrevBtn" class="correctPaperPrev">上一页</a>
								<a href="javascript:;" id="topicNextBtn" class="correctPaperNext">下一页</a>
							</div>
							<div id="topicSliderBtnBar" class="sliderBtnBar"></div>
						</div>
						<br>
						<div class="clip_switch"><em class="show_topicArea ac">题目(共<?php echo count($topicImg) ?>张)</em><em class=" show_analyzeArea">解析</em></div>
						<div id="topicClip" class="slidClip"></div>
					</div>
				</div>
				<div class="analyzeArea hide">
					<div class="correctPaper">
						<h5><?php echo $val->provenanceName." ".$val->questiontypename;?></h5>
						<div id="analyzeAreaSlide" class="correctPaperSlide">
							<div class="testPaperWrap mc pr">
								<ul class="testPaperSlideList slid">
									<?php
									$analysisImg=ImagePathHelper::getPicUrlArray($val->analytical);
									foreach($analysisImg as $analysisVal){ ?>
									<li><img src="<?php echo $analysisVal; ?>" width="830" height="" alt=""/></li>
									<?php } ?>
								</ul>
								<a href="javascript:;" id="analyzePrevBtn" class="correctPaperPrev">上一页</a>
								<a href="javascript:;" id="analyzeNextBtn" class="correctPaperNext">下一页</a>
							</div>
							<div  id="analyzeSliderBtnBar" class="sliderBtnBar"></div>
						</div>
						<br>
						<div class="clip_switch"><em class="show_topicArea">题目</em><em class="show_analyzeArea ac">解析(共<?php echo count($analysisImg)?>张)</em></div>
						<div id="analyzeClip" class="slidClip"></div>
					</div>
				</div>

		<hr class="dashde">
		<br>
		<?php
			}
			if($val->showTypeId ==8 || $val->showTypeId == 1 || $val->showTypeId == 2){ ?>
		<div class="recent_Q"><!--最近答题-->
		<input type="hidden" value="<?php echo $val->subjectid;?>" id="item">
		<?php if($val->showTypeId == 1 || $val->showTypeId == 2){?>
		<div class="paperArea">
		<div class="paper">
		<h5>题目:</h5>
		<span class="r_btnArea fr">难度：<em class="ezy" ><?php echo DegreeModel::model()->getDegreeName($val->complexity) ?></em>
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
			</span>
			<h6><span>
					<?php
					if(!empty($val->year)){
						echo "【".$val->year."年】";
					}
					echo $val->provenanceName."&nbsp;&nbsp".$val->questiontypename;?></span>
				<span class="source">来源：<?php echo $val->wrongQuesfromTxt; ?></span>
			</h6>
		<p><?php echo $val->content ?></p>
			<div class="checkArea">
			<input type="hidden" value="<?php echo $val->subjectid;?>" id="item">
			<?php
				if($val->showTypeId == 1 ){
					if(!empty($val->answerOption)){
						foreach(json_decode($val->answerOption) as $k=>$v){
							echo "<label  class='radio'><em>". LetterHelper::getLetter($k).".</em>  ".$v->content."</label>&nbsp;&nbsp;";
						}
					}
				}
				if($val->showTypeId == 2 ){
					if(!empty($val->answerOption)) {
						foreach (json_decode($val->answerOption) as $k => $v) {
							echo "<label  class='radio'><em>" . LetterHelper::getLetter($k) . ".</em>  " . $v->content . "</label>&nbsp;&nbsp;";
						}
					}
				}
				?>
				</div>
				<div class="btnArea clearfix">
					<span class="openAnswerBtn fl">查看答案与解析<i class="open"></i></span>
				</div>
				<div class="answerArea hide">
					<p><em>答案:</em><span><?php
                            foreach($val->answerContent as $answer_val){
                                if($answer_val==='0' || $answer_val === '1' || $answer_val ==='2' || $answer_val ==='3'){
                                    echo LetterHelper::getLetter($answer_val)."&nbsp;&nbsp;";
                                }else{
                                    echo $answer_val;
                                }
                            }

                            ?></span></p>
					<p><em>解析:</em><?php echo StringHelper::htmlPurifier($val->analytical) ?></p>
				</div>

		</div>
		</div>
		<?php }else{ ?>

			<div class="title item_title noBorder">
				<h4><?=$val->subjectname?>错题集</h4>

			</div>
			<?php } ?>
			<div class="stateBar">
				<?php  if($val->showTypeId == 8){?>
					<button id="re_anwser_btn" type="button"  class="btn60 bg_green w160">重答此题</button>
				<?php }else{?>
					<button type="button" class="reAnswerBtn btn60 bg_green w160">重答此题</button>
				<?php }?>
				<span class="re_anwser_hide">共回答<?php echo $seltopanswernum;?>次, 其中答对:<span class="green"><?php echo $selrightanswernum ?></span>次, 答错:<span class="orenge"><?php echo $errorAnswerNum ?></span>次</span>
			</div>
					<div class="re_anwser_hide">
						<br>
						<?php

						if($val->showTypeId == 8){
							if(!empty($val->userAnswers)){
								 foreach($val->userAnswers as $s=>$answerVal){
									 if($s<=2){
						?>
						<span>第<?php echo $answerVal->recSeq?>次答案：</span>
						<?php if($answerVal->ischecked == 0){?>
						<div class="imgFile my_answer"><!--电子答案-->
							<div class="title item_title noBorder">
								<h4 class="gray_d font12"><?php echo date("Y-m-d H:i",strtotime($answerVal->answerTime))?></h4><!--名称后天调用-->
								<a href="<?=Url::to(['wrongtopic/correct-paper']) ?>?t=<?php echo $val->id ?>&c=<?php echo $s;?>&a=1" style="margin:10px 0 0 20px" class="fl a_button bg_blue_l w80">去批改</a>
							</div>
							<ul class="up_test_list clearfix">
								<?php foreach($answerVal->picList as $key=>$ansVal1){ ?>
								<li><img src="<?php echo $ansVal1->picUrl; ?>" alt="">答案第<?php echo $key+1;?>页</li>
								<?php } ?>
							</ul>
						</div>
						<?php } if($answerVal->ischecked == 1){?>
						<div class="imgFile my_answer"><!--电子答案-->
							<div class="title item_title noBorder">
								<h4 class="gray_d font12"><?php echo date("Y-m-d H:i",strtotime($answerVal->answerTime))?></h4><!--名称后天调用-->
								<a href="<?=Url::to(['wrongtopic/correct-paper']) ?>?t=<?php echo $val->id ?>&c=<?php echo $s;?>&a=0" style="margin:10px 0 0 20px" class="fl a_button bg_blue_l w80">查看批改</a>
							</div>
							<ul class="up_test_list clearfix">
								<?php foreach($answerVal->picList as $key=>$ansVal2){ ?>
									<li><img src="<?php echo $ansVal2->picUrl; ?>" alt="">批改后第<?php echo $key+1;?>页</li>
								<?php } ?>
							</ul>
						</div>
						<?php } ?>
						<hr>
						<?php }} ?>
						<?php }}elseif( $val->showTypeId ==1 || $val->showTypeId ==2){ ?>
						<ul class="my_answer question_list clearfix"><!--电子答案-->
							<?php foreach($val->userAnswers as $selectVal){?>
								<?php if($selectVal->answerRight==1){?>
							<li class="answers">
								<em class="gray_d"><?php echo date("Y-m-d H:i",strtotime($selectVal->answerTime));?></em>
								<span class="Q_error"><?php echo LetterHelper::getLetter($selectVal->userAnswerOption); ?> </span>
							</li>
								<?php }else{?>
							<li class="answers">
								<em class="gray_d"><?php echo date("Y-m-d H:i",strtotime($selectVal->answerTime));?></em>
								<span class="Q_correct"><?php echo LetterHelper::getLetter($selectVal->userAnswerOption); ?> </span>
							</li>
								<?php }} ?>
						</ul>
						<?php } ?>
					</div>
					<div class="imgFile topic_detal hide">
						<h6>上传答案</h6>
						<ul class="up_test_list clearfix"  id="img_list">
							<li class="more">
								<?php
								$t1 = new frontend\widgets\xupload\models\XUploadForm;
								/** @var $this BaseController */
								echo  \frontend\widgets\xupload\XUploadSimple::widget( array(
									'url' => Yii::$app->urlManager->createUrl("upload/pic"),
									'model' => $t1,
									'attribute' => 'file',
									'autoUpload' => true,
									'multiple' => true,
									'options' => array(
										'acceptFileTypes' => new \yii\web\JsExpression('/(\.|\/)(jpg|png|jpeg)$/i'),
										"done" => new \yii\web\JsExpression('done'),
										"processfail" => new \yii\web\JsExpression('function (e, data) {var index = data.index,file = data.files[index]; if (file.error) {alert(file.error);}}')
									),
									'htmlOptions' => array(
										'id' => 'fileupload',
									)
								));
								?>
							</li>
						</ul>
						<div class="btnD tc">
							<button class=" bg_blue btn btn_js w120" id="pic_btn">完成答题</button>
						</div>
					</div>
		<br>

			<?php }else{ ?>

			<div class="paperArea">
			<div class="paper">
			<h5>题目:</h5>
			<span class="r_btnArea fr">难度：<em class="ezy" ><?php  echo DegreeModel::model()->getDegreeName($val->complexity)?></em>
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
			</span>
			<h6><span>
					<?php
				if(!empty($val->year)){
					echo "【".$val->year."年】";
				}
				echo $val->provenanceName."&nbsp;&nbsp".$val->questiontypename;?></span>
				<span class="source">来源：<?php echo $val->wrongQuesfromTxt; ?></span>
			</h6>

			<p><?php echo StringHelper::htmlPurifier($val->content) ?></p>
				<div class="checkArea">
				<input type="hidden" value="<?php echo $val->subjectid;?>" id="item">
			<?php if($val->showTypeId == 1 || $val->showTypeId == 2){
				if($val->showTypeId == 1 ){
					foreach(json_decode($val->answerOption) as $k=>$v){
						echo "<label  class='radio'><em>". LetterHelper::getLetter($k).".</em>  ".StringHelper::htmlPurifier($v->content)."</label>&nbsp;&nbsp;";
					}
				}
				if($val->showTypeId == 2 ){
					foreach(json_decode($val->answerOption) as $k=>$v){
						echo "<label  class='radio'><em>". LetterHelper::getLetter($k).".</em>  ".StringHelper::htmlPurifier($v->content)."</label>&nbsp;&nbsp;";
					}
				}
				?>
				</div>
				<dl class="userAnswerList clearfix">
					<dt>您的答案:</dt>
					<dd>
						<?php if(!empty($answer)) {
							echo LetterHelper::getLetter($answer->answerOption)."&nbsp;&nbsp;";
						}?>
					</dd>
				</dl>
				<div class="btnArea clearfix">
					<span class="openAnswerBtn fl">查看答案与解析<i class="open"></i></span>
				</div>
				<div class="answerArea hide">
					<p><em>答案:</em><span><?php foreach($val->answerContent as $answer_val){   echo LetterHelper::getLetter($answer_val)."&nbsp;&nbsp;";}?></span></p>
					<p><em>解析:</em><?php echo StringHelper::htmlPurifier($val->analytical) ?></p>
				</div>
				<ul class="form_list answerSum">
					<li>
						<div class="formL">
							<label>答题统计:</label>
						</div>
						<div class="formR answerCount">

							共回答<?php echo $seltopanswernum;?>次　　答对:<strong><?php echo $selrightanswernum ?></strong>次　　　答错:<em><?php echo ($seltopanswernum-$selrightanswernum)?></em>次

						</div>
					</li>
					<li>
						<div class="formL">
							<label>最近一次答题:</label>
						</div>
						<div class="formR testClass">
							<!--选择题-->
							<ul class="sel_list clearfix ">
								<?php foreach($history as $ic){
									$stuanswer = explode(",",$ic[0]->userAnswerOption);
									if($ic[0]->answerRight == 1){ ?>
										<li> <em class="correct">
												<?php foreach($stuanswer as $zh){ echo LetterHelper::getLetter($zh);}?>
												<i></i></em> <span><?php echo substr($ic[0]->answerTime,0,10)?>
                                            </span> </li>
									<?php }else{ ?>
										<li> <em class="error">
												<?php foreach($stuanswer as $zh){ echo LetterHelper::getLetter($zh);}?>
												<i></i></em> <span><?php echo substr($ic[0]->answerTime,0,10)?>
                                            </span> </li>
									<?php } } ?>
							</ul>
						</div>
					</li>
				</ul>
			<?php } else{ if(!empty($val->childQues)){ ?>   <!--多个小题 -->
				<ul class="sub_Q_List">
					<?php foreach($val->childQues as $chilkey=>$chilval){  $i=1;?>
						<li> <span>小题 <?php echo ($chilkey+1); ?>:</span><?php echo StringHelper::htmlPurifier($chilval->content) ?></li>
					<?php } ?>
				</ul>
				<dl class="userAnswerList clearfix"><dt>您上传的答案:</dt>
					<dd>
						<?php if(!empty($answer)){ ?>
							<?php if(!empty($answer->childQuesAnswer)){

								foreach($answer->childQuesAnswer as $cvk =>$cvs){
									if(!empty($cvs->picList)){
										echo  ($cvk+1).".";
										foreach($cvs->picList as $cvs2){
											echo "<img style='margin-left:10px;'src='".$cvs2->picUrl."' height='48' width='50' alt=''/>";
										}
										echo "&nbsp;&nbsp;";
									}else{
										echo  ($cvk+1).".";
										$arr = explode(',',$cvs->answerOption);
										foreach($arr as $arrVal){
										echo  LetterHelper::getLetter($arrVal)."&nbsp;&nbsp;";
										}
									}

								}
							}?>
						<?php }?>
					</dd>
				</dl>

			<?php }else{ // 单个答案  ?>
				<dl class="userAnswerList clearfix">
					<dt>您上传的答案:</dt>
					<dd class="addImage">

						<?php
						if(!empty($answer)){
							foreach($answer->picList as $img) {
								echo "<img src='" . $img->picUrl . "' height='48' width='50' alt=''>";
							}
						}

						?>

					</dd>
				</dl>
			<?php } ?>
				<div class="btnArea clearfix">
					<span class="openAnswerBtn fl">查看答案与解析
						<i class="open"></i>
					</span>

				</div>
				<div class="answerArea hide">
					<em>答案:</em>

					<?php
					foreach($val->childQues as $chilkey2=>$chilval2) {
						$i = 1;
						if ($chilval2->showTypeId == 1 || $chilval2->showTypeId == 2){
							$cAnswerContent = explode(",",$chilval2->answerContent);
							echo "<span>小题" . ($chilkey2 + 1) . ".";
							foreach($cAnswerContent as $vb1){
								echo LetterHelper::getLetter($vb1)." &nbsp;&nbsp;";
							}
							echo "</span>";

						} elseif(!empty($chilval2->childQues)){
							echo "<span>小题" . ($chilkey2 + 1).".";
							foreach($chilval2->childQues as $a=>$b) {
								echo " <span>".StringHelper::htmlPurifier($b->answerContent) . "</span>";
							}
							echo "&nbsp;&nbsp;";
						}else {
							echo "<span>小题" . ($chilkey2 + 1) . "." . StringHelper::htmlPurifier($chilval2->answerContent) . "&nbsp;&nbsp;</span>";
						}
					}
					if($val->showTypeId == 3){
						echo "<span>".StringHelper::htmlPurifier($val->content)."</span>";
					}
					?>
					<p><em>解析:</em><?php echo StringHelper::htmlPurifier($val->analytical) ?></p>
				</div>
				<div class="formR testClass">
					<h6>历史答案：</h6>
					<ul class="multi_list">
						<?php foreach($history as $s=>$z){ ?>
							<?php if(!empty($z)){ ?>
							<span style="float: left">第<?php echo $z[0]->recSeq?>次答案：</span>
							<?php } ?>
							<li class="clearfix">
								<dl>
									<?php foreach($z as $m=>$n){
										if(!empty($n->picList)){
											$check =$n->ischecked;
										}
										$time =  substr($n->answerTime,0,10);
										?>
										<dd>
											<?php if(empty($n->picList)) {
												$hisAnswer = explode(",", $n->userAnswerOption);
												if ($n->answerRight == 1) {
													?>

	                                                <?php echo $m+1 ?>
													<span class="exactness">
                                                            <?php foreach ($hisAnswer as $val2) {
																echo LetterHelper::getLetter($val2);
															} ?>  <i></i>
                                                    </span>

												<?php } else { ?>
<!--
<!--													--><?php echo $m+1 ?>
													<span  class="wrong">
                                                            <?php foreach ($hisAnswer as $val2) {
																echo LetterHelper::getLetter($val2);
															} ?>
														<i></i>
                                                    </span>
												<?php }}else{ ?>
												<?php echo $m+1 ?>
												<?php foreach($n->picList as $g) {
													echo "<img src='" . $g->picUrl . "' height='48' width='50' alt=''>";
												}}?>
										</dd>
									<?php }?>
								</dl>
								<?php if(isset($check)){?>
									<?php if($check == 0){ ?>
										<p><a href="<?=Url::to(['wrongtopic/correct-paper']) ?>?t=<?php echo $val->id ?>&c=<?php echo $s;?>&a=1">去批改</a> <span class="time"><?php echo $time ?></span></p>
									<?php }else{?>
										<p><a href="<?=Url::to(['wrongtopic/correct-paper']) ?>?t=<?php echo $val->id ?>&c=<?php echo $s;?>&a=0"">查看批改</a> <span class="time"><?php echo $time ?></span></p>
									<?php } ?>
								<?php }?>
							</li>

						<?php }?>
					</ul>
				</div>
			<?php }?>

			<hr>
			</div>
			</div>
			<div class="btnD tc">
				<button class="reAnswerBtn bg_blue btn btn_js w120">重答此题</button>
			</div>
			<br>
				<hr class="dashde">
		<?php }} ?>
		</div>
			<div class="submitBtnBar">
				<button type="button" class="bg_blue btn40 w120 prevPage">上一题</button>
				<button type="button" class="bg_blue btn40 w120 nextPage">下一题</button>
			</div>
		</div>
	</div>
</div>
<script>
k=0;
done = function(e, data) {

$.each(data.result, function (index, file) {
k++;
if(file.error){
popBox.errorBox(file.error);
return ;
}
$('<li img_url="' + file.url + '"><input type="hidden" class="picurls" name="ImgUrl[]" value="' + file.url + '" /><img src="' + file.url + '" alt="">第一页<span class="delBtn"></span></li>').insertBefore( $(e.target).parent());

});
};

	$(function(){
		$('#pic_btn').live('click', function(){

			var subId = $('#subid').val();
			var topicType = $("#t_type").val();
			var imgArray = [];

			$(this).parent('.btnD').prev("#img_list").find(".picurls").each(function (n,index){
				imgArray.push($(index).val());
			});
			var ImgUrl = imgArray.join(",");
			if (imgArray == "") {
				popBox.errorBox("请上传答案！");
				return false;
			}

			$.post('<?php echo url("/student/wrongtopic/img-reset");?>',{

				t:subId,
				subId:subId,
				topicType:topicType,
				ImgUrl:ImgUrl
			},function (data){
				if (data.success) {
					location.reload();
				}
			})
		})
	})
</script>


