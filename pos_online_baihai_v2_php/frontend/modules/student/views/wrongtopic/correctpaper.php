<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/5/2
 * Time: 11:28
 */
use frontend\components\helper\AreaHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */  $this->title="错题本-判卷";
?>

<script>
	$(function(){
//判卷
		$('.correctPaperSlide').testpaperSlider();

		var tpArg={on:false,bg:"#fff",fontSize:14,testPaperSize:0,page:0,tipsTxt:'',tipsMark:'<div class="tipsMark"></div>'};
		$('.setBg,.setFont').click(function(){//打开弹出框
			$(this).children('div').show();
			return false;
		});
		$('.setBg div a').click(function(){//设定背景
			$(this).parent().hide();
			$('.setBg').css('background-color',$(this).css('background-color'));
			tpArg.bg=$(this).css('background-color');
			return false;
		});
		$('.setFont div a').click(function(){//设定字号
			$(this).parent().hide();
			$('.setFont').css("background-image","none").children('.tit').text($(this).text());
			tpArg.fontSize=$(this).text();
			return false;
		});

		$('.slideControlPanel .ok').click(function () {
			var acs=$('.slideControlPanel .ac').size();
			if($('.comment .text').val()!="" || acs>0){
				$(this).addClass('ac');
				tpArg.tipsTxt='<span class="commentTxt">'+$('.comment .text').val()+'</span>';
				tpArg.on=true;
			}
			else{
				$(this).removeClass('ac');
				popBox.errorBox('请填写"评语" 或者 选择"判卷标签"');
			}
		});

		$('.slideControlPanel .mark').click(function(){
			$(this).addClass('ac').siblings('.mark').removeClass('ac');
		});

//正确
		$('.slideControlPanel .correct').click(function(){
			tpArg.tipsMark='<div class="tipsMark"><i class="tipsCorrect" data-value="1"></i></div>';
		});
//半对
		$('.slideControlPanel .problem').click(function(){
			tpArg.tipsMark='<div class="tipsMark"><i class="tipsProblem" data-value="-1"></i></div>';
		});
//错误
		$('.slideControlPanel .wrong').click(function(){
			tpArg.tipsMark='<div class="tipsMark"><i class="tipsWrong" data-value="0"></i></div>';
		});


		$('.testPaperSlideList li').click(function(ev){//添加tips
			if(tpArg.on==false){
				popBox.errorBox('填写评语 或 判卷标签,点击[确定]');
			}
			var tipLeft=ev.clientX-$(this).offset().left+$(document).scrollLeft();
			var tipTop=ev.clientY-$(this).offset().top+$(document).scrollTop();
			if(tpArg.on==true && tpArg.tipsTxt!=""){
				$('.slideControlPanel .mark,.slideControlPanel .ok').removeClass('ac');
				var html='<div class="tips" style="top:'+tipTop+'px;left:'+tipLeft+'px; background:'+tpArg.bg+';font-size:'+tpArg.fontSize+'px">'+tpArg.tipsMark+tpArg.tipsTxt+'<span class="removeBtn hide">×</span></div>';
				$(this).append(html);
				tpArg.tipsMark='<div class="tipsMark"></div>';

				savePage();
				var TipsJson = {
					id: null,
					pid: null,
					left: tipLeft,
					top: tipTop,
					background: tpArg.bg,
					fontSize: tpArg.fontSize
				};
				//alert("添加结束");
				var TipsJson={id:null,pid:null,left:tipLeft,top:tipTop,background:tpArg.bg,fontSize:tpArg.fontSize };
				var timer;
				$('.tips').hover(
					function(){
						var _this=$(this);
						clearTimeout(timer);
						timer=setTimeout(function(){_this.children('.removeBtn').fadeIn()},800)
					},
					function(){
						var _this=$(this);
						clearTimeout(timer);
						_this.children('.removeBtn').fadeOut()
					}
				);

				$( ".tips" ).draggable({ containment: 'parent',stop:function(){
					savePage();
					//alert("拖拽结束后");
				}});

				$('.tips .removeBtn').click(function(){
					$(this).parent().remove();
					savePage();
					//alert("删除后")
				});
				$('.comment .text').val("").placeholder({'top':"5px",'value':"评语"});
				tpArg.on=false;
			}
		});
		/*阅卷完成popBox*/

		//考试名称修改
		$('.popBox').dialog({
			autoOpen:false,
			width:480,
			modal: true,
			resizable:false,
			close:function(){$(this).dialog("close")}
		});
		$( ".correctEndBtn" ).click(function(){
			$( ".correctEndBox" ).dialog( "open" );
			return false;
		});

		$(".finish").click(function(){
			var url = "<?php echo Url::to(['/student/wrongtopic/add-answer-pic'])?>";
			var test= $(".current").children(".picid").val();
			var scoreJson = [];
			$(".current").children(".tips").each(function(index,el) {
				var scoreTxt=$(el).find(".commentTxt").html();
				var scoreVal=$(el).find(".scoreVal").html();
				var style = $(el).attr('style');
				var answerRight = $(el).find("i").attr("data-value");
				if(answerRight==''|| answerRight==undefined){
					answerRight ='2';
				}
				var sonObj={'style':style,'score':scoreVal,'comments':scoreTxt,"answerRight":answerRight};
				scoreJson.push(sonObj);
			});
			$.post(url,{"answer":JSON.stringify(scoreJson),"pid":test},function(msg){
				if(msg.success==true){
					//alert("已保存");
				}
			})
		});

		$("#score").keyup(function(){
			var tmptxt=$(this).val();
			$(this).val(tmptxt.replace(/\D|^0/g,''));
		}).bind("paste",function(){
			var tmptxt=$(this).val();
			$(this).val(tmptxt.replace(/\D|^0/g,''));
		}).css("ime-mode", "disabled");

		imgArr=[<?php foreach($piclist as $topicVal){ if(!empty($topicVal->picList)){ foreach ($topicVal->picList as $val){ ?> "<?php echo $val->picUrl; ?>",<?php } } } ?>];
		$('.correctPaperSlide').testpaperSlider({ClipArr:imgArr});

		//截止刷新
		window.onbeforeunload=function () {
			event.returnValue = "重新加载页面将不能修改此前所做的批改!";
		};

		$('.okBtn').live('click',function(){
			var url = "<?php echo Url::to(['/student/wrongtopic/check-question-answer-rec']); ?>";
			var recsq = $("#rec").val();
			var score = $("#score").val();
			var tid = $("#tid").val();
			var sid = $("#sid").val();
			if (isNaN(score) || score>10){
				popBox.alertBox("分数不能超过10分！");
				return false;
			}
			$.post(url,{'recseq':recsq,'score':score},function(msg){
				if(msg.success == true){
					$('.correctEndBox').dialog( "close" );//alert('批改成功');
					if(score == 10){
						popBox.confirmBox('您是否愿意从错题本<em style="font-size:18px; color:#900">移除</em>此题?',
							function(){
								var url2 = "<?php echo Url::to(['/student/wrongtopic/remove-topic'])?>";
								$.post(url2,{'subjectId':tid},function(msg){
									if(msg.success == true){
										window.onbeforeunload=null;
										location.href="<?php echo url('/student/wrongtopic/wro-top-for-item')?>"+"?item="+sid;
									}
								})
							},
							function(){
								window.onbeforeunload=null;
								location.href="<?php echo url('/student/wrongtopic/wrong-detail')?>"+"?study="+tid;
							}
						)
					}else{
						window.onbeforeunload=null;
						location.href="<?php echo url('/student/wrongtopic/wrong-detail')?>"+"?study="+tid;
					}
				}else{
					$('.correctEndBox').dialog( "close" );  alert('批改失败');
				}

			})
		})
	});

	tc = null;
	function savePage() {
		clearTimeout(tc);
		tc = setTimeout(function () {
			var scoreJson = [];
			$('li.current div.tips').each(function (index, el) {
				var comments = $(el).find(".commentTxt").html();
				var scoreVal=$(el).find(".scoreVal").html();
				var style = $(el).attr("style");
				var answerRight = $(el).find("i").attr("data-value");
				if(answerRight==''|| answerRight==undefined){
					answerRight ='2';
				}
				var sonObj={'style':style,'score':scoreVal,'comments':comments,"answerRight":answerRight};
				scoreJson.push(sonObj);
			});
			var test= $(".current").children(".picid").val();
			$.post("<?php echo url('student/wrongtopic/add-answer-pic')?>", {

				"answer":JSON.stringify(scoreJson),"pid":test
			}, function (data) {
				if (!data.success) {
					popBox.alertBox(data.message);
				}
			})
		}, 300);
	}
</script>

<!--top_end-->
<!--主体-->

<div class="grid_19 main_r">
	<div class="main_cont test_class_overall_appraisal">
		<?php foreach($topic as $topicVal){ ?>
			<input type="hidden" value="<?php echo $topicVal->id;?>" id="tid">
			<input type="hidden" value="<?php echo $topicVal->subjectid;?>" id="sid">
			<input type="hidden" value="<?php echo $recseq;?>" id="rec" >
			<div class="title"> <a class="txtBtn backBtn" onclick="window.history.go(-1);"></a>
				<h4><?php echo $topicVal->questiontypename; ?></h4>
				<div class="title_r">
					<div class="pageCount"></div>
				</div>
			</div>
			<div class="correctPaper">
				<h5><?php echo $topicVal->operaterName?>的《<?php echo $topicVal->questiontypename; ?>》</h5>
				<ul class="up_details_list">
					<li class="clearfix">
						<p>地区：<span><?php echo AreaHelper::getAreaName($topicVal->provience).'&nbsp;&nbsp;'.AreaHelper::getAreaName($topicVal->city).'&nbsp;&nbsp'.AreaHelper::getAreaName($topicVal->country)?></span></p>
						<p>年级：<span><?php echo $topicVal->gradename; ?></span></p>
						<p>科目：<span><?php echo $topicVal->subjectname; ?></span></p>
						<p>版本：<span><?php echo $topicVal->versionname; ?></span></p>
					</li>
<!--					<li class="clearfix">-->
<!--						<p>知识点：<span>-->
<!--									--><?php
//									if(isset($topicVal->Kid)){
//										foreach(KnowledgePointModel::findKnowledge($topicVal->Kid) as $key=>$item){
//											echo $item->name;
//										}  } ?>
<!--							</span></p>-->
<!--					</li>-->
<!--					<li class="clearfix">-->
<!--						<p>作业简介：<span> </span></p>-->
<!--					</li>-->
				</ul>
				<div class="slidClip"></div>
				<div class="correctPaperSlide">
					<div class="testPaperWrap mc pr">
						<ul class="testPaperSlideList slid">
							<?php foreach($piclist as $key=>$val){ ?>
								<input type="hidden" value="<?php echo $key+1?>" class="key" >
								<?php if(!empty($val->picList)){
									foreach($val->picList as $v) {
										echo '<li><input type="hidden" value="' . $v->picId . '" class="picid" ><img src="' . $v->picUrl . '" width="830" height="870"  alt="" />';
										if ($v->checkJson) {
											$style = json_decode($v->checkJson);
											foreach ($style->checkInfoList as $vl) {  ?>
<!--												echo '<div class="tips" style="' . $vl->style . '"> <span class="scoreTxt">' . $vl->comments . '</span></div>';-->
												<div class="tips" style="<?php echo $vl->style; ?>">
													<div class="tipsMark">
														<?php switch ($vl->answerRight) {
															case '-1':
																?>
																<i class="tipsProblem" data-value="-1"></i>
																<?php
																break;
															case '0':
																?>
																<i class="tipsWrong" data-value="0"></i>
																<?php  break;
															case '1':
																?>
																<i class="tipsCorrect" data-value="1"></i>
																<?php        break;
															case '2':
																break;
														}?>
													</div>
													<span class="commentTxt"><?php echo $vl->comments ?></span>
													<br>

												</div>
											<?php }
										}
										echo '</li>';
									}
								}
							}?>
						</ul>
						<a href="javascript:;" id="prevBtn" class="correctPaperPrev">上一页</a> <a href="javascript:;" id="nextBtn" class="correctPaperNext">下一页</a>
					</div>
				</div>
				<?php if($status == 1){?>
					<div class="slideControlPanel">
						<div class="setBg" title="设定背景">
							<div class="pop"> <span></span> <a class="red"></a> <a class="pink"></a> <a class="yellow"></a> <a class="blue"></a> <a class="green"></a> </div>
						</div>
						<div class="setFont"><span class="tit"></span>
							<div class="pop"> <span></span> <a>12</a> <a>14</a> <a>16</a> </div>
						</div>
						<div class="mySelect correctSelect tc gray_d">点拨</div>
						<div class="comment">
							<input class="text" type="text" style="padding:3px 0 !important">
						</div>
						<div class="score hide" >
							<b>得分</b>
							<input class="text" type="text" style=" padding:3px 0 !important">
						</div>
						<div title="正确" class="mark correct">正确</div>
						<div title="半对" class="mark problem">半对</div>
						<div title="错误" class="mark wrong">错误</div>
						<div class="ok">确定</div>
					</div>
					<br>
					<div class="tc bottomBtnBar"><button type="button" style="height:46px; width:312px" class="bg_green correctEndBtn finish">批改完成</button></div>
				<?php } ?>

			</div>
		<?php } ?>
	</div>
</div>

<!--主体end-->

<!--弹框-->
<div id="dati" class="popBox correctEndBox" title="完成答题">
	<!--完成答题-->
	<div class="popCont" style="padding-left:100px">
		<p class="">您已完成本题答案的批阅</p>
		<br>
		<p>得分 <input type="text" id="score" class="text w50"> 分&nbsp;&nbsp;&nbsp;&nbsp;满分 10 分</p>
		<br>

	</div>
	<div class="popBtnArea">
		<button type="button" class="okBtn">确定</button>
		<button type="button" class="cancelBtn">取消</button>
	</div>
</div>
