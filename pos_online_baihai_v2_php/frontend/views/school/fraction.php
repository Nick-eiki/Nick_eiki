<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/4/27
 * Time: 12:23
 */
//$cs = Yii::$app->clientScript;
use frontend\components\CHtmlExt;
use yii\helpers\Html;

$this->registerJsFile(publicResources_new() . "/js/jquery.validationEngine-zh_CN.js");
$this->registerJsFile(publicResources_new() . "/js/jquery.validationEngine.min.js");
$this->registerJsFile(publicResources_new() . "/js/register.js".RESOURCES_VER);
/* @var $this yii\web\View */  $this->title="学校--录取分数线";
?>

<script>
	$(function(){
		<?php if($teacherLine ==false){ ?>
			$('i').hide();
		<?php } ?>

		/*弹窗初始化*/
		$('.popBox').dialog({
			autoOpen: false,
			width:460,
			modal: true,
			resizable:false,
			close:function(){ $(this).dialog("close") }

		});
		/*添加弹窗*/
		(function(){
			var score=[];
			var tds="";
			var end_score={};
			//编辑
			$('.addBtnJs').click(function(){
				$("#addscoreBox").dialog( "open" );
				return false;
			});
			$("#add").click(function(){
				if ($('#add_form_id').validationEngine('validate')) {
					var schoolId = '<?php echo $schoolId; ?>';
					var year = $("#year_add").val();
					var admission = $("#admission").val();
					var choiceSchool = $("#choiceSchool").val();
					var accommodation = $("#accommodation").val();
					$.post("<?php echo url("school/add-point-line")?>", {
						'year': year,
						'admission': admission,
						'choiceSchool': choiceSchool,
						'accommodation': accommodation,
						'schoolId': schoolId
					}, function (data) {
						if (data.success) {
							popBox.successBox(data.message);
							location.reload();
						} else {
							popBox.errorBox(data.message);
						}
					});
				}
			});
			//编辑弹窗

			$('.updateBtnJs').live('click',function(){
				var plId = $(this).parent('td').prev('.plId').attr('pl');
				var pa=$(this).parents('tr');
				tds=pa.children('td').not('td:last');
				score=[];
				tds.each(function() {
					score.push($(this).text());
				});
				$('.updatescoreBox .entry_score').val(score[1]);
				$('.updatescoreBox .sel_school_score').val(score[2]);
				$('.updatescoreBox .live_score').val(score[3]);
				$('.updatescoreBox .selectWrap select').val(score[0]);
				$('.updatescoreBox .pl_id').val(plId);
				$( "#updatescoreBox" ).dialog( "open" );
				return false;
			});
			$("#update").click(function(){

				if ($('#update_form_id').validationEngine('validate')) {
					var plId = $("#plId").val();
					var year = $("#year_update").val();
					var admission = $("#admission_update").val();
					var choiceSchool = $("#choiceSchool_update").val();
					var accommodation = $("#accommodation_update").val();
					$.post("<?php echo url("school/update-score")?>",
						{
							'plId':plId,
							'year': year,
							'admission': admission,
							'choiceSchool': choiceSchool,
							'accommodation': accommodation
						}, function (data) {
							if (data.success) {
								popBox.successBox(data.message);
								location.reload();
							} else {
								popBox.errorBox(data.message);
							}
						});
				}
			})
		})();

		$('#year_sel').live('change',function(){
			var sel_year = $(this).val();
			var schoolId = '<?php echo $schoolId; ?>';
			$.post("<?= url('school/fraction',array('schoolId'=>$schoolId))?>",
				{
					schoolId:schoolId,
					"sel_year":sel_year
				},function (data){
					$('#fraction_list').html(data);
			})
		})

	})

</script>


<!--主体-->

<div class="main_cont cut_score">
	<div class="title">
		<h4>历年录取分数线</h4>
		<div class="title_r">
			年份:
					<span class="selectWrap big_sel" style="width:100px"> <i></i> <em></em>
						<?php
						echo CHtmlExt::dropDownListCustomize('year_sel',
							'',
							getClassYears(),array(
								"id" => 'year_sel',
								"defaultValue" => false,
								"prompt" => "请选择",
								)
						);
						?>
                </span>
			<?php if ($teacherLine) { ?>
				<button type="button" class="btn40 bg_green addBtnJs">添加</button>
			<?php } ?>
		</div>
	</div>

	<div class="cut_score_cont">
		<div class="cut_score_item fraction_list" id="fraction_list">
			<?php echo $this->render('_fraction_list_view',array('arr'=>$arr, 'teacherLine'=>$teacherLine));?>
		</div>
	</div>
</div>

<!--主体end-->
<!--添加分数线-->
<div id="addscoreBox" class="popBox hide pushNotice addscoreBox" title="添加分数线">
	<div class="popCont">
		<div class="new_tch_group">
			<form id="add_form_id">
				<div class="form_list">

					<div class="row">
						<div class="formL">
							<label><i>*</i>年份：</label>
						</div>
						<div class="formR" class="formR" style="position: relative;">
                            <span class="selectWrap big_sel">
                           	<i></i>
                           	<em>请选择</em>
								<?php
								echo Html::dropDownList('year_add',
									'',
									getClassYears(),array(
                                        "id" => 'year_add',
										"defaultValue" => false,
										"prompt" => "请选择",
										'data-validation-engine'=>"validate[required]",
										'data-prompt-target' => "select_year",
										'data-prompt-position' => "inline",
										'data-errormessage-value-missing' => "请选择年份！"
									));
								?>
                            </span>
							<span id="select_year" style="left:130px; top:13px"  class="errorTxt"></span>
						</div>
					</div>
					<div class="row">
						<div class="formL">
							<label><i>*</i>录取分数线：</label>
						</div>
						<div class="formR" style="position: relative;">
							<input
								type="text" id="admission"
								class="text entry_score"
								style="width:121px;"
								data-validation-engine="validate[required,custom[number,max[999],min[1]]]"
								data-prompt-target = "enroll"
								data-prompt-position ="inline"
								data-errormessage-value-missing ="分数线不能为空"
								>
							<span id="enroll" style="left:130px; top:13px" class="errorTxt"></span>
						</div>
					</div>
					<div class="row">
						<div class="formL">
							<label>择校分数线：</label>
						</div>
						<div class="formR" style="position: relative;">

							<input
								type="text"
							    style="width:121px;"
							    id="choiceSchool"
							    class="text sel_school_score"
							    data-prompt-target = "choose_school"
							    data-prompt-position ="inline"
							    data-validation-engine="validate[custom[number,max[999],min[0]]]"
								>
							<span id="choose_school" style="left:130px; top:13px" class="errorTxt"></span>
						</div>
					</div>
					<div class="row">
						<div class="formL">
							<label>住宿分数线：</label>
						</div>
						<div class="formR" style="position: relative;">
							<input
								type="text"
								style="width:121px;"
								id="accommodation"
								class="text live_score"
								data-prompt-target = "stay"
								data-prompt-position ="inline"
								data-validation-engine="validate[custom[number,max[999],min[0]]]"
								>
							<span id="stay" style="left:130px; top:13px" class="errorTxt"></span>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="popBtnArea">
		<button type="button" class="okBtn" id="add">确定</button>
		<button type="button" class="cancelBtn">取消</button>
	</div>
</div>

<div id="updatescoreBox" class="popBox hide pushNotice addscoreBox updatescoreBox" title="编辑分数线">
	<div class="popCont">
		<div class="new_tch_group">
			<form id="update_form_id">
				<div class="form_list">

					<div class="row">
						<div class="formL">
							<label><i>*</i>年份：</label>
						</div>
						<div class="formR" style="position: relative;">
                            <span class="selectWrap big_sel">
                           	<i></i>
                           	<em>请选择</em>
								<?php
								echo Html::dropDownList(
									'year_update', '',
									getClassYears(),array(
                                        "id" => 'year_update',
 										"defaultValue" => false,
										"prompt" => "请选择",
										'data-validation-engine'=>"validate[required]",
										'data-prompt-target' => "select_year_update",
										'data-prompt-position' => "inline",
										'data-errormessage-value-missing' => "请选择年份！"
									));
								?>
                            </span>
							<span id="select_year_update" style="left:130px; top:13px"  class="errorTxt"></span>
						</div>
					</div>
					<div class="row">
						<div class="formL">
							<label><i>*</i>录取分数线：</label>
						</div>
						<div class="formR" style="position: relative;" >
							<input
								type="text"
								id="admission_update"
								class="text entry_score"
								style="width:121px;"
								data-validation-engine="validate[required,custom[number,max[999],min[1]]]"
								data-prompt-target = "enroll_update"
								data-prompt-position ="inline"
								data-errormessage-value-missing ="分数线不能为空"
								>
							<span id="enroll_update" style="left:130px; top:13px" class="errorTxt"></span>
						</div>
					</div>
					<div class="row">
						<div class="formL">
							<label>择校分数线：</label>
						</div>
						<div class="formR" style="position: relative;">
							<input
								type="text"
								id="choiceSchool_update"
								class="text sel_school_score"
								style="width:121px;"
								data-validation-engine="validate[custom[number,max[999],min[0]]]"
								data-prompt-target = "choose_school_update"
								data-prompt-position ="inline"
								>
							<span id="choose_school_update" style="left:130px; top:13px" class="errorTxt"></span>
						</div>
					</div>
					<div class="row">
						<div class="formL">
							<label>住宿分数线：</label>
						</div>
						<div class="formR" style="position: relative;">
							<input
								type="text"
								id="accommodation_update"
								class="text live_score"
								style="width:121px;"
								data-validation-engine="validate[custom[number,max[999],min[0]]]"
								data-prompt-target = "stay_update"
								data-prompt-position ="inline"
								>
							<span id="stay_update" style="left:130px; top:13px" class="errorTxt"></span>
						</div>
					</div>
				</div>
				<input type="hidden" id="plId" class="pl_id">
			</form>
		</div>
	</div>
	<div class="popBtnArea">
		<button type="button" class="okBtn" id="update">确定</button>
		<button type="button" class="cancelBtn">取消</button>
	</div>
</div>
