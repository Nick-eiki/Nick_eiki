<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/4/17
 * Time: 12:06
 */
/* @var $this yii\web\View */
/* @var $this yii\web\View */  /* @var $this yii\web\View */  $this->title="在线答题";
$this->registerCssFile(publicResources_new() . '/js/fancyBox/jquery.fancybox.css'.RESOURCES_VER);
$this->registerJsFile(publicResources_new() . "/js/fancyBox/jquery.fancybox.js".RESOURCES_VER,[ 'position'=>\yii\web\View::POS_HEAD] );

?>



<!--主体-->
<div class="grid_19 main_r">
	<div class="main_cont online_answer">
		<div class="title">
			<h4>在线答题</h4>
			<div class="title_r">
				<span>组卷人：<?php echo loginUser()->getUserInfo($testResult->creator)->getTrueName(); ?>
					时间：<?php echo date("Y-m-d", strtotime($testResult->uploadTime)); ?></span>
			</div>
		</div>
		<div class="work_detais_cent">
			<h4><?php echo $testResult->examName; ?></h4>
			<ul class="ul_list">
				<li><span>1、</span>考察知识点：
					<?php
					if (!empty($kcidName)) {
						foreach ($kcidName as $v) {
							echo $v['name'] . '&nbsp;&nbsp;';
						}
					} else {
						echo '&nbsp;';
					}
					?>
				</li>
				<li><span>2、</span>本试卷共包含<?php echo  $testResult->questionListSize; ?>道题目，其中
					<?php $array = array();
					foreach ($testResult->qeustionTypeNumList as $v) {
						array_push($array, $v->questiontypename . $v->cnum . "道");

					}
					echo implode($array, ",");

					?></li>
				<li><span>3、</span>各题目分值情况：
					<?php
					$k = 1;
					$sum = 0;
					foreach ($testResult->questionScoreList as $v) { ?>
						<?php echo $k++; ?>--<?php if(empty($v->quesScore)){ echo '0分'; }else{ echo $v->quesScore . '分，'; }
						$score = $sum += $v->quesScore;
					}
					?>
					共计<?php echo $score; ?>分
				</li>
				<!--						<li><span>4、</span>答题时间控制在 40 分钟内，其中选择题必须在线回答，填空题与应用题提交手写答案</li>-->
			</ul>
			<!--					<div class="btn_online">-->
			<!--						<!--<button type="button" class="bg_blue btn_line" id="btn_line_js">开始答题</button>-->
			<!--						<div class="time-item">-->
			<!--							<!-- <span id="day_show">0天</span>-->
			<!--							<strong id="hour_show">0时</strong>-->
			<!--							<strong id="minute_show">0分</strong>-->
			<!--							<strong id="second_show">0秒</strong> </div>-->
			<!--					</div>-->
			<div class="testPaperView">
				<div class="paperArea">
					<?php foreach ($testResult->questionList as $key => $item) { ?>
						<?php echo $this->render('//publicView/paper/_new_item_answer_type', array('item' => $item)); ?>
					<?php } ?>
				</div>

				<div class="btnD">
					<button type="button" class=" bg_blue btn btn_js " id="finish">完成答题</button>
				</div>
			</div>

		</div>
	</div>
</div>
<!--主体end-->

<!--答题弹窗-->
<!--注意---后台自己去判断是否答题完毕在显示相应的的提示-->
<div id="dati" class="popBox dati hide" title="完成答题">
	<!--完成答题-->
	<div class="impBox">
		<p style="margin-top:20px; text-align:center; margin-bottom:30px;">您确认提交考卷吗？</p>

		<p class="hide">您的答题情况：本试卷共计 <i class="b">5</i> 道小题，您共完成 <i class="r">2</i> 道题</p>
	</div>
</div>

<script>

	$(function () {

		/*删除按钮*/
		$('.minute li i').live('click', function () {
			$(this).parent().remove();
		});

//完成答题

		$('.btn_js').click(function () {
			/*上传试卷*/
			$('#dati').dialog({
				autoOpen: false,
				width: 600,
				modal: true,
				resizable: false,
				buttons: [
					{
						text: "确定",

						click: function () {
							if ($('#mySchoolPop .text').val() == 1) {
								$(this).dialog("close");
							}
							else {
								var bigArray = [];
								$(".paper").each(function (index, el) {
									var bigTitleID = $(el).find(".bigTitleID").val();
									if ($(el).find(".bigType").val() == "5" || $(el).find(".bigType").val() == "6" || $(el).find(".bigType").val() == "7") {
										var bigTitle = [];

										var bigTitleID = $(el).find(".bigTitleID").val();
										var smallTitleObj = [];
										//获取图片答案路径并且转成字符串
										smallImageArray = [];
										$(el).find(".addImage").find("dd").each(function (n, e) {
											smallImageArray.push($(e).find("img").attr("src"));
										});
										var smallImage = smallImageArray.join(",");
										var smallTitle = {"questionId": $(el).find(".bigTitleID").val(), answerUrl: smallImage};
										bigTitle.push(smallTitle);

										$(el).find(".middleTitle").each(function (indexx, ell) {
											var middleTitleID = $(ell).find(".middleTitleID").val();
											if ($(ell).find(".smallTitle").length > 0) {
												var smallTitleObj = [];
												$(ell).find(".smallTitle").each(function (indexxx, elll) {
//                    获取图片答案路径并且转成字符串
													smallImageArray = [];
													$(elll).find(".addImage").find("dd").each(function (n, e) {
														smallImageArray.push($(e).find("img").attr("src"));
													});
													var smallImage = smallImageArray.join(",");
													var smallTitle = {
														"questionId": $(elll).find(".smallTitleID").val(),
														answerUrl: smallImage
													};
													smallTitleObj.push(smallTitle);
												});

												var middleTitle = {
													"questionId": middleTitleID,
													"detail": smallTitleObj
												};
											}
											if ($(ell).find(".type").val() == 1) {
												var middleAnswer = $(ell).find("[type=radio]:checked").val();
												var middleTitleID = $(ell).find(".middleTitleID").val();
												var middleTitle = {
													"questionId": middleTitleID,
													"answerOption": middleAnswer
												};
											}
											if ($(ell).find(".type").val() == 2) {
//                    获取复选框内容
												var middleAnswerArray = [];
												$(ell).find("[type=checkbox]").each(function (n, e) {
													if ($(e).attr("checked") == "checked") {
														middleAnswerArray.push($(e).val());
													}
												});
												var middleAnswer = middleAnswerArray.join(",");

												var middleTitle = {
													"questionId": middleTitleID,
													"answerOption": middleAnswer
												};
											}
											if ($(ell).find(".smallTitle").length < 1 && $(ell).find(".type").val() != 1 && $(ell).find(".type").val() != 2) {
												middleImageArray = [];
												$(ell).find(".addImage").find("dd").each(function (n, e) {
													middleImageArray.push($(e).find("img").attr("src"));
												});
												var middleImage = middleImageArray.join(",");
												var middleTitle = {
													"questionId": middleTitleID,
													"answerUrl": middleImage
												};
											}
											bigTitle.push(middleTitle);
										})

									}
									if ($(el).find(".bigType").val() == "1") {
										var bigTitle = [];
										var bigAnswer = $(el).find("[type=radio]:checked").val();
										var bigTitleID = $(el).find(".bigTitleID").val();
										var bigTitleObj = {"questionId": bigTitleID, "answerOption": bigAnswer};
										bigTitle.push(bigTitleObj);
									}
									if ($(el).find(".bigType").val() == "2") {
										var bigAnswerArray = [];
										$(el).find("[type=checkbox]").each(function (n, e) {
											if ($(e).attr("checked") == "checked") {
												bigAnswerArray.push($(e).val());
											}
										});
										var bigAnswer = bigAnswerArray.join(",");
										var bigTitle = [];
										var bigTitleObj = {"questionId": bigTitleID, "answerOption": bigAnswer};
										bigTitle.push(bigTitleObj);
									}
									if ($(el).find(".bigType").val() == "4") {
										var bigTitle = [];

//                    获取图片答案路径并且转成字符串
										bigImageArray = [];
										$(el).find(".addImage").find("dd").each(function (n, e) {
											bigImageArray.push($(e).find("img").attr("src"));
										});
										var bigImage = bigImageArray.join(",");
										var bigTitleObj = {"questionId": bigTitleID, "answerUrl": bigImage};
										bigTitle.push(bigTitleObj);

									}
									if ($(el).find(".bigType").val() == "3") {
										if ($(el).find(".middleTitle").length > 0) {
											var bigTitle = [];
											$(el).find(".middleTitle").each(function (n, e) {
												var middleTitleID = $(e).find(".middleTitleID").val();
												var middleImageArray = [];
												$(e).find(".addImage").find("dd").each(function (nn, ee) {
													middleImageArray.push($(ee).find("img").attr("src"));
												});
												var middleImage = middleImageArray.join(",");
												bigTitle.push({"questionId": middleTitleID, "answerUrl": middleImage});
											})
										} else {
											var middleImageArray = [];
											var bigTitle = [];
											$(el).find(".addImage").find("dd").each(function (n, e) {
												middleImageArray.push($(e).find("img").attr("src"));
											});
											var middleImage = middleImageArray.join(",");
											var bigTitleObj = {"questionId": bigTitleID, "answerUrl": middleImage};
											bigTitle.push(bigTitleObj);
										}
									}

									if ($(el).find(".bigType").val() == "8") {
										var bigTitle = [];
										var bigTitleID = $(el).find(".bigTitleID").val();
										var smallTitleObj = [];
										//获取图片答案路径并且转成字符串
										smallImageArray = [];
										$(el).find(".addImage").find("dd").each(function (n, e) {
											smallImageArray.push($(e).find("img").attr("src"));
										});
										var smallImage = smallImageArray.join(",");
										var smallTitle = {"questionId": $(el).find(".bigTitleID").val(), answerUrl: smallImage};
										bigTitle.push(smallTitle);
									}

									var bigTitleAnswer = {"mainQusId": bigTitleID, "detail": bigTitle};
									bigArray.push(bigTitleAnswer);

								});
								var titleObj = {"answers": bigArray};
								var examSubID = "<?php echo app()->request->getQueryParam('examSubID')?>";
								$.post("<?php echo url('student/exam/finish-upload')?>", {
									"answerList": JSON.stringify(titleObj),
									"examSubID": examSubID
								}, function (result) {
									if (result.code == "0") {
										popBox.errorBox(result.message);
									} else {
										popBox.successBox(result.message);
										window.history.go(-1);
									}
								})
							}

						}
					},
					{
						text: "取消",

						click: function () {
							$(this).dialog("close");
						}
					}

				]
			});
			$("#dati").dialog("open");
			//event.preventDefault();
			return false;
		});


		/*删除添加的试卷*/
		$('.up_img_js li').live('mouseover', function () {
			$(this).children('i').show();
		});
		$('.up_img_js li').live('mouseout', function () {
			$(this).children('i').hide();
		});
		$('.up_img_js li i').live('click', function () {
			$(this).parent().remove();
		});

//更多图片
		$('.more_js').toggle(function () {
				$(this).parent('ul').css('height', 'auto')
			},
			function () {
				$(this).parent('ul').css('height', '50px')
			}
		)

	})
</script>
<script type="text/javascript">
	$(function () {
		//显示选中的值--单选
		var radio = $(".alternative");
		var arr_d = [];
		radio.live('click', function () {
			$(this).parents('li').siblings('.stu_answer').text('');
			if (this.checked == true) {
				arr_d.push(arr_d);
				var text = $(this).siblings('i').text();
				$(this).parents('li').siblings('.stu_answer').append('<em>' + text + '</em>');
			}
		});

		//显示选中的值--多选
		var ck = $(".checkbox");
		var arr_select = [];
		ck.live('click', function () {
			//显示放答案的标签
			$(this).parents('li').next('.stu_answer').show();
			//先清空掉
			$(this).parents('li').siblings('.stu_answer').children('em').text('');
			//$(this).parents('dd').siblings('.stu_answer').text('');

			var text = $(this).siblings('i').text();
			if (this.checked == true) {
				//arr.push(text);

				//如果数组里面没有找到text   就让他插入一个
				if (arr_select.indexOf(text) == -1) {
					arr_select.push(text);
				}
			}
			else {
				//否则的话 就删掉你现在取消掉的这个东西
				if (!('indexOf' in Array.prototype)) {
					Array.prototype.indexOf = function (find, i /*opt*/) {
						if (i === undefined) i = 0;
						if (i < 0) i += this.length;
						if (i < 0) i = 0;
						for (var n = this.length; i < n; i++)
							if (i in this && this[i] === find)
								return i;
						return -1;
					};
				}
				arr_select.splice(arr_select.indexOf(text), 1);
			}
			document.title = arr_select;
			//插入数组里面的东西
			for (var i = 0; i < arr_select.length; i++) {
				$(this).parents('li').siblings('.stu_answer').append('<em>' + arr_select[i] + '</em>')
			}
		});

		//显示选中的值--完形单选
		var radio = $('.alternative');
		var arr_d = [];
		radio.live('click', function () {
			$(this).parents('.an_ul').next('.answer').text('');

			if (this.checked == true) {
				arr_d.push(arr_d);
				var text = $(this).siblings('i').text();
				$(this).parents('.an_ul').next('.answer').append('<em>您的答案是：' + text + '</em>');
			}
		});

		/*移入显示删除按钮*/
		$('.minute li').live('mouseover mouseout', function (event) {

			if (event.type == 'mouseover') {
				$(this).children('i').show();
			} else {
				$(this).children('i').hide();
			}
		});
		/*删除按钮*/
		$('.minute li i').live('click', function () {
			$(this).parent().remove();
		})

	})
</script>
<script type="text/javascript">
	var intDiff = parseInt(10);//倒计时总秒数量
	function timer(intDiff) {
		window.setInterval(function () {
			var day_show = $('#day_show');//天
			var hours = $('#hour_show');//小时
			var minutes = $('#minute_show');//分钟
			var seconds = $('#second_show');//秒
			var day = 0,
				hour = 0,
				minute = 0,
				second = 0;//时间默认值
			if (intDiff > 0) {
				//day = Math.floor(intDiff / (60 * 60 * 24));
				hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
				minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
				second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
			}
			if (minute <= 9) minute = '0' + minute;
			if (second <= 9) second = '0' + second;
			if (second == 0) {
				alert('不好意思，时间到');
			}
			//day_show.html(day+"天");
			hours.html('<s id="h"></s>' + hour + '时');
			minutes.html('<s></s>' + minute + '分');
			seconds.html('<s></s>' + second + '秒');
			intDiff--;
		}, 1000);


	}
	$(function () {
		$('#btn_line_js').bind('click', function () {
			$(this).css('background', '#ccc');
			timer(intDiff);
		});
		$("#finish").click(function () {
			return;
			var bigArray = [];
			$(".paper").each(function (index, el) {
				var bigTitleID = $(el).find(".bigTitleID").val();
				if ($(el).find(".bigType").val() == "5" || $(el).find(".bigType").val() == "6" || $(el).find(".bigType").val() == "7") {
					var bigTitle = [];

					var bigTitleID = $(el).find(".bigTitleID").val();
					var smallTitleObj = [];
					//获取图片答案路径并且转成字符串
					smallImageArray = [];
					$(el).find(".addImage").find("dd").each(function (n, e) {
						smallImageArray.push($(e).find("img").attr("src"));
					});
					var smallImage = smallImageArray.join(",");
					var smallTitle = {"questionId": $(el).find(".bigTitleID").val(), answerUrl: smallImage};
					bigTitle.push(smallTitle);

					$(el).find(".middleTitle").each(function (indexx, ell) {
						var middleTitleID = $(ell).find(".middleTitleID").val();
						if ($(ell).find(".smallTitle").length > 0) {
							var smallTitleObj = [];
							$(ell).find(".smallTitle").each(function (indexxx, elll) {
//                    获取图片答案路径并且转成字符串
								smallImageArray = [];
								$(elll).find(".addImage").find("dd").each(function (n, e) {
									smallImageArray.push($(e).find("img").attr("src"));
								});
								var smallImage = smallImageArray.join(",");
								var smallTitle = {
									"questionId": $(elll).find(".smallTitleID").val(),
									answerUrl: smallImage
								};
								smallTitleObj.push(smallTitle);
							});

							var middleTitle = {"questionId": middleTitleID, "detail": smallTitleObj};
						}
						if ($(ell).find(".type").val() == 1) {
							var middleAnswer = $(ell).find("[type=radio]:checked").val();
							var middleTitleID = $(ell).find(".middleTitleID").val();
							var middleTitle = {"questionId": middleTitleID, "answerOption": middleAnswer};
						}
						if ($(ell).find(".type").val() == 2) {
//                    获取复选框内容
							var middleAnswerArray = [];
							$(ell).find("[type=checkbox]").each(function (n, e) {
								if ($(e).attr("checked") == "checked") {
									middleAnswerArray.push($(e).val());
								}
							});
							var middleAnswer = middleAnswerArray.join(",");

							var middleTitle = {"questionId": middleTitleID, "answerOption": middleAnswer};
						}
						if ($(ell).find(".smallTitle").length < 1 && $(ell).find(".type").val() != 1 && $(ell).find(".type").val() != 2) {
							middleImageArray = [];
							$(ell).find(".addImage").find("dd").each(function (n, e) {
								middleImageArray.push($(e).find("img").attr("src"));
							});
							var middleImage = middleImageArray.join(",");
							var middleTitle = {"questionId": middleTitleID, "answerUrl": middleImage};
						}
						bigTitle.push(middleTitle);
					})

				}
				if ($(el).find(".bigType").val() == "1") {
					var bigTitle = [];
					var bigAnswer = $(el).find("[type=radio]:checked").val();
					var bigTitleID = $(el).find(".bigTitleID").val();
					var bigTitleObj = {"questionId": bigTitleID, "answerOption": bigAnswer};
					bigTitle.push(bigTitleObj);
				}
				if ($(el).find(".bigType").val() == "2") {
					var bigAnswerArray = [];
					$(el).find("[type=checkbox]").each(function (n, e) {
						if ($(e).attr("checked") == "checked") {
							bigAnswerArray.push($(e).val());
						}
					});
					var bigAnswer = bigAnswerArray.join(",");
					var bigTitle = [];
					var bigTitleObj = {"questionId": bigTitleID, "answerOption": bigAnswer};
					bigTitle.push(bigTitleObj);
				}
				if ($(el).find(".bigType").val() == "4") {
					var bigTitle = [];

//                    获取图片答案路径并且转成字符串
					bigImageArray = [];
					$(el).find(".addImage").find("dd").each(function (n, e) {
						bigImageArray.push($(e).find("img").attr("src"));
					});
					var bigImage = bigImageArray.join(",");
					var bigTitleObj = {"questionId": bigTitleID, "answerUrl": bigImage};
					bigTitle.push(bigTitleObj);

				}
				if ($(el).find(".bigType").val() == "3") {
					if ($(el).find(".middleTitle").length > 0) {
						var bigTitle = [];
						$(el).find(".middleTitle").each(function (n, e) {
							var middleTitleID = $(e).find(".middleTitleID").val();
							var middleImageArray = [];
							$(e).find(".addImage").find("dd").each(function (nn, ee) {
								middleImageArray.push($(ee).find("img").attr("src"));
							});
							var middleImage = middleImageArray.join(",");
							bigTitle.push({"questionId": middleTitleID, "answerUrl": middleImage});
						})
					} else {
						var middleImageArray = [];
						var bigTitle = [];
						$(el).find(".addImage").find("dd").each(function (n, e) {
							middleImageArray.push($(e).find("img").attr("src"));
						});
						var middleImage = middleImageArray.join(",");
						var bigTitleObj = {"questionId": bigTitleID, "answerUrl": middleImage};
						bigTitle.push(bigTitleObj);
					}
				}

				if ($(el).find(".bigType").val() == "8") {
					var bigTitle = [];
					var bigTitleID = $(el).find(".bigTitleID").val();
					var smallTitleObj = [];
					//获取图片答案路径并且转成字符串
					smallImageArray = [];
					$(el).find(".addImage").find("dd").each(function (n, e) {
						smallImageArray.push($(e).find("img").attr("src"));
					});
					var smallImage = smallImageArray.join(",");
					var smallTitle = {"questionId": $(el).find(".bigTitleID").val(), answerUrl: smallImage};
					bigTitle.push(smallTitle);
				}

				var bigTitleAnswer = {"mainQusId": bigTitleID, "detail": bigTitle};
				bigArray.push(bigTitleAnswer);
			});
			var titleObj = {"answers": bigArray};
			var examSubID = "<?php echo app()->request->getQueryParam('examSubID')?>";
			$.post("<?php echo url('student/exam/finish-upload')?>", {
				"answerList": JSON.stringify(titleObj),
				"examSubID": examSubID
			}, function (result) {
				if (result.code == "0") {
					popBox.errorBox(result.message);
				} else {
					popBox.successBox(result.message);
					location.href = "<?php echo url('student/exam/manage')?>";
				}
			})

		});
		$('.addPicUl li i').live('click', function () {
			$(this).parent('li').remove();

		});

	});
</script>
