<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-25
 * Time: 上午10:24
 */
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */  /* @var $this yii\web\View */  $this->title="答题结果";
?>

<!--	<script type="text/javascript">-->
<!--		//完成答题-->
<!--		$(function(){-->
<!--			$('.completeJS').click(function(){-->
<!--				/*上传试卷*/-->
<!--			})-->
<!---->
<!---->
<!--			$('.modify').dialog({-->
<!--				autoOpen: false,-->
<!--				width:600,-->
<!--				modal: true,-->
<!--				resizable:false,-->
<!--				buttons: [-->
<!--					{-->
<!--						text: "马上批改",-->
<!---->
<!--						click: function() {-->
<!--							window.open("../stu-mistake-correctPaper.html")-->
<!---->
<!--						}-->
<!--					},-->
<!--					{-->
<!--						text: "取消",-->
<!---->
<!--						click: function() {-->
<!--							$( this ).dialog( "close" );-->
<!--							var name= $('.name').text();-->
<!--							$('.title').show();-->
<!--							var name_i =$('.i_name_js').text(name);-->
<!---->
<!---->
<!--						}-->
<!--					}-->
<!---->
<!--				]-->
<!--			});-->
<!--			$( ".modify" ).dialog( "open" );-->
<!--			//event.preventDefault();-->
<!--			return false;-->
<!--		})-->
<!--	</script>-->
<script>
	//        $('.openAnswerBtn').click(function () {
	//            $(this).parents('.paper').children('.answerArea').toggle();
	//        });
	$('.openAnswerBtn').toggle(function () {

		$(this).parents('.btnArea').siblings('.answerArea').show();
	} ,function(){

		$(this).parents('.btnArea').siblings('.answerArea').hide();

	});
	$(function(){
		$('.completeJS').click(function(){
			/*上传试卷*/
		});


		$('.modify').dialog({
			autoOpen: false,
			width:600,
			modal: true,
			resizable:false,
			buttons: [
				{
					text: "马上批改",

					click: function() {
						window.open("<?php echo url('student/managepaper/correct-org-paper',array('otherTestAnswerID'=>$testResult->otherTestAnswerID))?>")

					}
				},
				{
					text: "取消",

					click: function() {
						$( this ).dialog( "close" );
						var name= $('.name').text();
						$('.title').show();
						var name_i =$('.i_name_js').text(name);


					}
				}

			]
		});
		if("<?php echo  $testResult->otherTestAnswerID ?>"!=""&&"<?php echo $testResult->otherIsCheck?>"=="0"){
			$( ".modify" ).dialog( "open" );
		}
		//event.preventDefault();
		return false;
	})
</script>

<!--主体-->


<div class="grid_19 main_r">
<div class="main_cont online_answer testPaperView">
<div class="title">
	<h4>在线答题</h4>
	<div class="title_r"><span>组卷人：<?=loginUser()->getUserInfo($testResult->creator)->getTrueName()?> 时间：<?=$testResult->examTime?></span></div>
</div>

<div class="finish_t" style="position:relative;">
	<h4>
		<?php
//	 	echo AreaHelper::getAreaName($testResult->provience)."&nbsp". AreaHelper::getAreaName($testResult->city)."&nbsp".AreaHelper::getAreaName($testResult->country)."&nbsp".$testResult->gradename."&nbsp".$testResult->subjectname."&nbsp".$testResult->versionname
     echo $testResult->name;
        ?>
	</h4>
	<span class="z">自主测评/非自主测评试卷</span>
	<!--这里的div显示要判断是否 未批改a的文字是前去批改，批改完之后a的文字是查看批改链接也随之变成 stu_test_look_over.html-->
	<?php if($testResult->otherTestAnswerID!=""){?>
	<div class="title" style="">
		您收到一份
		<i class="i_name i_name_js"><?php echo $testResult->otherStudentName?></i>的答案，
		<?php if($testResult->otherIsCheck==0){ ?>
		<a href="<?php echo url('student/managepaper/correct-org-paper',array('testAnswerID'=>$testResult->otherTestAnswerID))?>">去批改</a>
		<?php }else{?>
		<a href="<?php echo url('student/managepaper/view-org-correct',array('testAnswerID'=>$testResult->otherTestAnswerID))?>">查看批改</a>
		<?php } ?>
		？</div>
	<?php } ?>
	<ul class="ul_list">
		<li><span>1、</span>考察知识点：<?php echo KnowledgePointModel::findKnowledgeStr($testResult->knowledgeId)?></li>
		<li><span>2、</span>本试卷共包含<?php echo $testResult->questionListSize?>道题目，其中
			<?php
			$array=array();
			foreach($testResult->qeustionTypeNumList as $v){
				array_push($array,$v->questiontypename.$v->cnum."道");
			}
			echo implode($array,",")
			?>
		</li>
		<li><span>3、</span>各题目分值情况：<?php
			$sum = 0;
			foreach($testResult->questionScoreList as $v){
				echo $v->id.'--'.(empty($v->quesScore)?'0':$v->quesScore).'分，';
				$score = $sum += $v->quesScore;
			}
			?>
			共计<?php echo $score; ?>分</li>
<!--		<li><span>4、</span>答题时间控制在 40 分钟内，其中选择题必须在线回答，填空题与应用题提交手写答案</li>-->
		<hr>
		<!--注：当教师填写了试卷总评则显示，否则不显示-->
        <?php if(!empty($evaluateResult->summary)){?>
		<li class="overall_comm_list"style="margin-top:20px;">
			<h3>科目总评</h3>

			<div class="overall_appraisal_r" >
				<ul class="form_list no_padding_form_list overall_comm">
					<li class="row">
						<div class="formL">
							<label for="name">班级：</label>
						</div>
						<div class="formR">
							<div><?php echo $testResult->className; ?></div>
						</div>
					</li>
					<li class="row">
						<div class="formL">
							<label for="name">最高-最低分：</label>
						</div>
						<div class="formR">
							<span class="score">
								最高分：<em><?php echo intval($maxAndMin->MaxScore)?>分</em>
							</span>
							<span class="score">
								最低分：<em><?php echo intval($maxAndMin->MinScore)?>分</em>
							</span>
						</div>
					</li>
					<li class="row">
						<div class="formL">
							<label for="name">分数段：</label>
						</div>
						<div class="formR pointarea">
							<?php foreach($sectionResult->socreList as $v){ ?>
							<span class="scorex">
								<?php echo $v->bottomlimit."-".$v->toplimit."&nbsp"."共".$v->num."人"?>
							</span>
							<?php } ?>
						</div>
					</li>
					<li class="row">
						<div class="formL">
							<label for="name">试卷难点：</label>
						</div>
						<div class="formR" >

							<ul class="labelList">
								<?php
									$knowledgeArray=explode(",",$evaluateResult->knowledgePoint);
									foreach($knowledgeArray as $v){
										if(!empty($v)){
								?>
								<li><?php echo KnowledgePointModel::getNamebyId($v);?></li>
								<?php } } ?>
							</ul>

						</div>
					</li>
					<li class="row" style="margin-bottom:0px">
						<div class="formL">
							<label for="name">学习规划：</label>
						</div>
						<div class="formR">
							<div class="content"> <?php echo $evaluateResult->summary; ?></div>
						</div>
					</li>
				</ul>

			</div>

		</li>
        <?php }?>
	</ul>
	<!--

	注：当教师批改了试卷，则显示成绩，否则不显示
	-->
	<span class="fraction">
		<?php echo $testResult->isCheck?$testResult->stuSubScore:"未批改" ?>
	</span>
	<!--这里显示的是停止答题的时间-->
</div>
	<?php foreach ($testResult->questionList as $key => $item) {
		echo $this->render('//publicView/onlineTest/_recombinationItemProblem', array('item' => $item,"testAnswerID"=>$testResult->testAnswerID));
	} ?>
	<!--翻页-->

		<?php
		 echo \frontend\components\CLinkPagerExt::widget( array(
				'pagination' => $pages,
				'maxButtonCount' => 5
			)
		);
		?>

	<!--阅卷完成-->
	<div class="popBox modify clearfix" title="批改">
		<h5>您收到一份<a href="javascript:" class="name"><?php echo $testResult->otherStudentName?></a>的答案，请问是否批改？</h5>
	</div>

</div>






</div>

<!--主体end-->

<!--答题弹窗-->
<!--注意---后台自己去判断是否答题完毕在显示相应的的提示-->
<div id="dati" class="popBox dati hide" title="完成答题">
	<!--完成答题-->
	<div class="impBox">
		<p>您的题目已全部回答完毕，是否提交？</p>
		<p class="hide">您的答题情况：本试卷共计 <i class="b">5</i> 道小题，您共完成 <i class="r">2</i> 道题</p>
	</div>
</div>
<script>
    $('.openAnswerBtn').click(function(){
        $(this).children('i').toggleClass('clospae');
        $(this).parents('.paper').find('.answerArea').toggle();
    })
</script>

