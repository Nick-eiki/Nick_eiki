<?php
/**
 * 答疑
 * Created by PhpStorm.
 * User: gaoli_000
 * Date: 2015/6/25
 * Time: 11:49
 */
use frontend\components\helper\ImagePathHelper;
use frontend\components\helper\StringHelper;
use frontend\components\helper\ViewHelper;
use frontend\components\WebDataCache;
use frontend\services\pos\pos_AnswerQuestionManagerService;
use yii\helpers\Html;

?>
<script>
	$(function () {

		$(".fancybox").die().fancybox();
		$('.red_btn_js2').die().live('click', function () {
			var aqid = $(this).attr('val');
			var answer = $(".textarea_js" + aqid).val();
			if (answer == '') {
				popBox.errorBox("内容不能为空!");
				return false;
			}
			if (answer.length > 1001) {
				popBox.alertBox('超过1000字数限制，请重新编辑！');
				return false;
			}
			else {

				$.post('<?php echo url('answer/result-question');?>', {answer: answer, aqid: aqid}, function (data) {
					if (data.success) {
						popBox.successBox('回答成功');
						$.post('<?php echo url('answer/answer-detail');?>', {aqid: aqid}, function (datas) {
							$('.answer_detail' + aqid).html(datas);
						});
						$(this).parents('.test_BoxBtn').siblings('.answerBigBox ').children('.answerW').show();
						$(this).parents('.test_BoxBtn').siblings('.answerBigBox ').children('.answerM').hide();
					} else {
						popBox.alertBox(data.message);
					}
				});
				$(this).parent().parent('.pop_up_js').hide();
			}
		});

		/*增加同问的数字*/
		$('.q_add').die().live('click', function () {
			var aqid = $(this).attr('val');
			var creatorid = $(this).attr('user');
			var userid = "<?php echo user()->id;?>";
			if (creatorid == userid) {
				return false;
			} else {
				var aqid = $(this).attr('val');
				$.post('<?php echo url('answer/same-question');?>', {aqid: aqid}, function (data) {
					if (data.success) {
						$.post('<?php echo url('answer/answer-detail');?>', {aqid: aqid}, function (datas) {
							$('.answer_detail' + aqid).html(datas);
						});
					} else {
						popBox.alertBox(data.message);
					}
				})
			}
		});

		/*点击采用变成已采用*/
		$('.adopt_btn').die().live('click', function () {

			$(this).removeClass('put');
			$(this).text('最佳答案');

			var aqid = $(this).attr('u');
			var resultid = $(this).attr('val');
			$.post('<?php echo url('answer/use-the-answer');?>', {resultid: resultid}, function (data) {
				if (data.success) {
					$.post('<?php echo url('answer/answer-detail');?>', {aqid: aqid}, function (datas) {
						$('.answer_detail' + aqid).html(datas);
					});
				}
			})
		});

		$('.reply').die().live('click', function () {
			$(this).parents('.test_BoxBtn').siblings('.answerBigBox ').children('.answerM').show();
			$(this).parents('.test_BoxBtn').siblings('.answerBigBox ').children('.answerW').hide();
		});

		$('.quiz').die().live('one', 'click', function () {
			var oText = $(this).children('em');
			var num = oText.text();
			num++;
			oText.text('').append(num);
			//$(this).children('i').
		});

		$('.area_closeJs').die().live('click', function () {
			$(this).parents('.answerM').hide();
			$(this).parents('.answerM').reset();
		})
	})
</script>
<script>
	$(function(){
		$('.answer').toggle(function(){
			$(this).parents('.test_BoxBtn').siblings('.answerBigBox ').children('.answerW').show();
			$(this).parents('.test_BoxBtn').siblings('.answerBigBox ').children('.answerM').hide();
		},function(){
			$(this).parents('.test_BoxBtn').siblings('.answerBigBox ').children('.answerW').hide();
			$(this).parents('.test_BoxBtn').siblings('.answerBigBox ').children('.answerM').hide();
		})
	})
</script>
<div class="title">
	<h4 class="font16">我的答疑</h4>
	<div class="title_r">
		<a href="<?php echo url('student/answer/answer-questions')?>" class="gray_d underline">查看更多</a>
	</div>
</div>

<div class="make_testpaper">
	<?php if(empty($answerResult)){
		ViewHelper::emptyView();
	}?>
	<?php foreach ($answerResult as $val) { ?>
	<div class="answer_detail<?php echo $val->aqID;?>" style="padding: 34px 0px 0px 25px;">
		<div class="test_tite clearfix">
			<h4 class="fl">问题<?php echo $val->aqID; ?></h4>
			<em class="fr gray_d"><?php echo date("Y-m-d H:i", strtotime($val->createTime)); ?></em>
		</div>

		<div class="testpaperBox">
			<p><?php echo Html::encode($val->aqName); ?></p>
			<p class="gray_d"><?php echo StringHelper::htmlPurifier($val->aqDetail); ?></p>
			<?php
			//分离图片
			$img = ImagePathHelper::getPicUrlArray($val->imgUri);
			foreach($img as $k=>$imgSrc) {
				?>
				<a class="fancybox" href="<?php  echo $imgSrc; ?>" data-fancybox-group="gallery_<?= $val->aqID; ?>"><img width="162" height="122" src="<?php  echo $imgSrc; ?>" alt=""></a>
			<?php  } ?>
		</div>
		<div class="test_BoxBtn clearfix">
			<em class="comeFrom fl">来自：</em>
			<ul class="answer_testList">
				<li>
					<img data-type="header" onerror="userDefImg(this);"  width="30px" height="30px" src="<?php echo publicResources() . WebDataCache::
						getFaceIcon($val->creatorID);?>" alt="" title="<?php echo $val->creatorName; ?>">
					<?php foreach($val->samelist as $samelist){ ?>
						<span><img data-type="header" onerror="userDefImg(this);"  width="30px" height="30px" src="<?php echo publicResources() . WebDataCache::getFaceIcon($samelist->userID);?>" alt="" title="<?php echo $samelist->userName;?>"></span>
					<?php } ?>
				</li>
			</ul>
		</div>
		<div class="test_BoxBtn clearfix">
			<dl class="box_btn clearfix">
				<dd>
					<a href="javascript:;" class="w90 a_button reply bg_blue_l">回答</a>
					<?php if($val->resutltNumber == 0){?>
						<a href="javascript:;" class="w90 a_button bg_blue_l">答案<em>(<?php echo $val->resutltNumber;?></i>)</em></a>
					<?php }else{?>
						<a href="javascript:;" class="w90 a_button answer bg_blue_l">答案<em>(<?php echo $val->resutltNumber;?></i>)</em></a>
					<?php }?>
					<a href="javascript:;" class="w90 a_button bg_blue_l quiz q_add" val="<?php echo $val->aqID;?>" user="<?php echo $val->creatorID;?>">同问(<i id="<?php echo $val->aqID;?>"><?php echo $val->sameQueNumber;?></i>)</a>
					<?php if($val->creatorID == user()->id){?>
						<?php if(loginUser()->isTeacher()){ ?>
							<a href="<?php echo url('teacher/answer/update-question',array('aqId'=>$val->aqID)) ?>" class="w90 a_button bg_blue_l">修改</a>
						<?php }elseif(loginUser()->isStudent()){ ?>
							<a href="<?php echo url('student/answer/update-question',array('aqId'=>$val->aqID)) ?>" class="w90 a_button bg_blue_l">修改</a>
						<?php }} ?>
				</dd>
			</dl>
		</div>
		<div class="answerBigBox ">
			<div class="answerBox answerM hide">
				<em class="arrow" style="left:28px;"></em>
				<div class="editor">
					<textarea style="width: 740px;" class="textarea textarea_js<?php echo $val->aqID;?>"></textarea>
					<p class="BtnBox" style="margin-top: 10px; margin-bottom: 10px;">
						<a href="javascript:;" class="a_button bg_blue w80 red_btn_js2" val="<?php echo $val->aqID;?>">回答</a>
						<a class="a_button bg_blue_l w80 area_closeJs">取消</a>
					</p>
				</div>
			</div>
			<div class="answerBox answerW hide">
				<em class="arrow" style="left:128px;"></em>
				<div class="answerBox_list">
					<ul class="answer_list">
						<?php
						$material = new pos_AnswerQuestionManagerService();
						$modelList = $material->SearchQuestionByID($val->aqID);
						$res = $modelList->data->otherResultList;
						$msg = $modelList->data->useResultList;
						foreach($msg as $msgValue){
							?>
							<li class="clearfix">
								<em class="answer_listL">
									<img data-type="header" onerror="userDefImg(this);"  width="50" height="50" src="<?php echo $msgValue->headImgUrl?>">
								</em>
								<div class="answer_listR fl">
									<div class="answer_a clearfix">
										<em><?php echo Html::encode($msgValue->trueName);?></em><span>最佳答案</span>
										<em class="fr"><?php echo date("Y-m-d H:i", strtotime($msgValue->createTime)); ?></em>
									</div>
									<div class="answer_a clearfix">
										<?php echo Html::encode($msgValue->resultDetail);?>
										<?php
										//分离图片
										$resultImg = ImagePathHelper::getPicUrlArray($msgValue->resultImgUri);
										foreach($resultImg as $k=>$resultImgSrc) {
											?>
											<a class="fancybox" href="<?php  echo $resultImgSrc; ?>" data-fancybox-group="gallery_<?= $msgValue->resultID; ?>"><img width="162" height="122" src="<?php  echo $resultImgSrc; ?>" alt=""></a>
										<?php  } ?>
									</div>
								</div>
							</li>
						<?php }?>

						<?php foreach($res as $value){
							?>
							<li class="clearfix">
								<em class="answer_listL">
									<img data-type="header" onerror="userDefImg(this);"  width="50" height="50" src="<?php echo $value->headImgUrl?>">
								</em>
								<div class="answer_listR fl">
									<div class="answer_a clearfix">
										<em><?php echo Html::encode($value->trueName);?></em>

										<?php if($val->creatorID == user()->id){  if(empty($msg)){ ?>
											<span class="put put_Js adopt_btn" val="<?php echo $value->resultID;?>" u="<?php echo $val->aqID;?>"><a href="javascript:" class="btn_c" >采用</a></span>
										<?php }} ?>

										<em class="fr"><?php echo date("Y-m-d H:i", strtotime($value->createTime)); ?></em>
									</div>
									<div class="answer_a clearfix">
										<?php echo  Html::encode($value->resultDetail);?>
										<?php
										//分离图片
										$rseImg = ImagePathHelper::getPicUrlArray($value->resultImgUri);
										foreach($rseImg as $k=>$resImgSrc) {
											?>
											<a class="fancybox" href="<?php  echo $resImgSrc; ?>" data-fancybox-group="gallery_<?= $value->resultID; ?>"><img width="162" height="122" src="<?php  echo $resImgSrc; ?>" alt=""></a>
										<?php  } ?>
									</div>

								</div>
							</li>
						<?php }?>
					</ul>
				</div>

			</div>

		</div>
	</div>
	<?php } ?>
</div>
