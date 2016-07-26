<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/4/21
 * Time: 9:42
 */
use frontend\components\CHtmlExt;
use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\CapacityModel;
use frontend\models\dicmodels\DegreeModel;
use frontend\models\dicmodels\EditionModel;
use frontend\models\dicmodels\FromModel;
use frontend\models\dicmodels\GradeModel;
use frontend\models\dicmodels\ItemTypeModel;
use frontend\models\dicmodels\QuesLevelModel;
use frontend\models\dicmodels\SubjectModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */  $this->title='题目录入';
$backend_asset = publicResources_new();
;
$this->registerJsFile($backend_asset . '/js/My97DatePicker/WdatePicker.js'.RESOURCES_VER, [ 'position'=>\yii\web\View::POS_HEAD] );
$this->registerJsFile($backend_asset . "/js/pubjs.js".RESOURCES_VER);
$this->registerJsFile($backend_asset . "/js/ztree/jquery.ztree.all-3.5.min.js".RESOURCES_VER);
$this->registerCssFile($backend_asset . "/css/questionInput.css".RESOURCES_VER);
$this->registerCssFile("@web/ueditor/themes/default/css/ueditor.min.css".RESOURCES_VER);
$this->registerJsFile("@web/ueditor/ueditor.config.little.js".RESOURCES_VER);
$this->registerJsFile("@web/ueditor/ueditor.all.min.js".RESOURCES_VER);
?>
	<!--知识树-->
<script>
	$(function(){
        formValidationIni('#form_id');
		 var ue = UE.getEditor('container');
		$('.p_reservation').live('click',function(){
			if ($('#form_id').validationEngine('validate')) {
				var url="<?php echo url('/student/wrongtopic/save-question-head')?>";
				var questionID = $("#topid").val();
				var questionPrice=$("#questionPrice").val();
				var provience=$("#provience").val();
				var city=$("#city").val();
				var county=$("#county").val();
				var gradeID=$("#gradeID").val();
				var subjectID=$("#subjectID").val();
				var versionID=$("#versionID").val();
				var source=$("#bj").val();//考试分类
				var year=$("#date1").val();// 年份
				var from =$('#source').val();//来源
				var nandu=$("#nandu").val(); // 难度
				var queslevel=$("#queslevel").val();// 题目等级
				var capacity =$("#capacity").val();//掌握程度
				var tags=$("#tags").val();
				var tqtid=$("#type_0").val();

				var name=$("#container").val();
				if(tags==""){
					popBox.alertBox('自定义标签不能为空！');
					return false;
				}
				if(tqtid==""){
					popBox.alertBox('请选择题型！');
					return false;
				}
				if (name == "") {
					popBox.alertBox('题目不能为空！');
					return false;
				}

				$.post(url,{'questionID':questionID,'questionPrice':questionPrice,'provience':provience,'city':city,'county':county,'gradeID':gradeID,'subjectID':subjectID,'versionID':versionID,'source':source,'year':year,'from':from,'nandu':nandu,'queslevel':queslevel,'capacity':capacity,'tags':tags,'tqtid':tqtid,'kid':'','name':name},
					function(msg){
						location.href="<?php echo url('/student/wrongtopic/save-ques-content')?>"+"?question="+msg;
					})
			}
		})
	})
</script>

<div class="grid_19 main_r">
	<div class="main_cont">
		<div class="title">
			<h4>录入题干</h4>
		</div>
		<br>
		<?php $form =\yii\widgets\ActiveForm::begin( [
			'enableClientScript' => false,
			'id' => 'form_id',
        ])?>
		<ul class="box_data_list">
			<input type="hidden" value="<?php echo $data->id;?>" id="topid" />
			<input type="hidden" value="<?php echo $data->questionPrice ?>" class="input_text text" id="questionPrice" />
			<li class="clearfix">
				<label class="species">适用地区:</label>
				<div class="box_select">
					<?php
					echo CHtmlExt:: dropDownListAjax("provience", $data->provience, ArrayHelper::map(AreaHelper::getProvinceList(), 'AreaID', 'AreaName'), array(
						"defaultValue" => false, "prompt" => "请选择",
						'ajax' => [
							'url' => Url::to('/ajax/get-area'),
							'data' => ['id' => new \yii\web\JsExpression('this.value')],
							'success' => 'function(html){jQuery("#city").html(html).change();}'
						],
						"id" => "provience",
					));
					?>
					<label></label>
					<?php
					echo CHtmlExt:: dropDownListAjax("city", $data->city, ArrayHelper::map(AreaHelper::getCityList( $data->provience), 'AreaID', 'AreaName'),array(
						"defaultValue" => false, "prompt" => "请选择",
						'ajax' => array(
							'url' => Url::to('/ajax/get-area'),
							'data' => array('id' => new \yii\web\JsExpression('this.value')),
							'success' => 'function(html){jQuery("#county").html(html).change();}'
						),
						"id" => "city",

					));
					?>
					<label></label>
					<?php
					echo CHtmlExt:: dropDownListAjax("county", $data->country, ArrayHelper::map(AreaHelper::getRegionList($data->city), 'AreaID', 'AreaName'), array(
						"defaultValue" => false, "prompt" => "请选择",
						"id" => "county",

					));
					?>
					<label></label>
				</div>
			</li>
			<li class="clearfix">
				<label class="species"><i>*</i>适合年级:</label>
				<div class="box_select">
					<?php
					echo CHtmlExt:: dropDownListAjax("grade",$data->gradeid, GradeModel::model()->getListData(),array(
						"defaultValue" => false, "prompt" => "请选择",
						'data-validation-engine' => 'validate[required]',
                        "data-prompt-position"=>"inline",
                        "data-prompt-target"=>"gradeError",
						'ajax' => array(
							'url' => Url::to('/ajax/get-item-for-grade'),
							'data' => array('id' => new \yii\web\JsExpression('this.value')),
							'success' => 'function(html){jQuery("#subjectID").html(html).change();}'
						),
						"id" => "gradeID",

					));
					?>
                <span id="gradeError" class="errorTxt"></span>
				</div>
			</li>
			<li class="clearfix">
				<label class="species"><i>*</i>科目:</label>
				<div class="box_select">
					<?php
					echo CHtmlExt:: dropDownListAjax('item', $data->subjectid, ArrayHelper::map(SubjectModel::getSubByGrade($data->gradeid), 'secondCode', 'secondCodeValue'),
						array(
                            "data-prompt-position"=>"inline",
                            "data-prompt-target"=>"subjectError",
							'ajax' => array(
								'url' => Url::to('/ajax/get-topic-type'),
								'data' => ['subject'=>new JsExpression('this.value'),'grade'=> new JsExpression('jQuery("#gradeID").val()')],
								'success' => 'function(html){jQuery("#type_0").html(html).change();jQuery("#treeval").html("");jQuery("#treeli").html("")}'
							),
							"defaultValue" => false, "prompt" => "请选择 ",
							'data-validation-engine' => 'validate[required]',
							'id'=>"subjectID",
						));
					?>
                    <span id="subjectError" class="errorTxt"></span>
				</div>
			</li>
			<li class="clearfix">
				<label class="species">版本:</label>
				<div class="box_select">
					<?php
					echo CHtmlExt:: dropDownListAjax('banben',  $data->versionid,
						EditionModel::model()->getListData(),
						array(
							"defaultValue" => false, "prompt" => "请选择 ",
							'id'=>'versionID',
						));
					?>
				</div>
			</li>
			<li class="clearfix">
				<label class="species">考试分类:</label>
				<div class="box_select">
					<?php
					echo CHtmlExt:: dropDownListAjax('provenance', $data->provenance,
						FromModel::model()->getListData(),
						array(
							"defaultValue" => false, "prompt" => "请选择 ",
							'id'=> 'bj',
						));
					?>
				</div>
			</li>
			<li class="clearfix">
				<label class="species">年份:</label>
				<div class="box_select">
					<input  type="text"class="Wdate" value="<?php echo $data->year ?>" id="date1" onclick="WdatePicker({dateFmt:'yyyy',minDate:'1900',maxDate:'2099'})" readonly />
				</div>
			</li>
			<li class="clearfix">
				<label class="species">来源:</label>
				<div class="box_select">
					<input type="text" class="text" value="<?php echo $data->quesFrom ?>" id="source">
				</div>
			</li>
			<li class="clearfix">
				<label class="species">题目等级:</label>
				<div class="box_select">
					<?php
					echo CHtmlExt:: dropDownListAjax('queslevel',  $data->quesLevel,
						QuesLevelModel::model()->getListData(),
						array(
							"defaultValue" => false, "prompt" => "请选择 ",
							'id'=> 'queslevel',
						));
					?>
				</div>
			</li>
			<li class="clearfix">
				<label class="species">掌握程度:</label>
				<div class="box_select">
					<?php
					echo CHtmlExt:: dropDownListAjax('capacity', $data->capacity,
						CapacityModel::model()->getListData(),
						array(
							"defaultValue" => false, "prompt" => "请选择 ",
							'id'=> 'capacity',
						));
					?>
				</div>
			</li>
			<li class="clearfix">
				<label class="species"><i>*</i>难易程度:</label>
				<div class="box_select">
					<?php
					echo CHtmlExt:: dropDownListAjax('norm',  $data->complexity,
						DegreeModel::model()->getListData(),
						array(
                            "data-prompt-position"=>"inline",
                            "data-prompt-target"=>"complexityError",
							"defaultValue" => false, "prompt" => "请选择 ",
							'data-validation-engine' => 'validate[required]',
							'id'=>'nandu',
						));
					?>
                    <span id="complexityError" class="errorTxt"></span>
				</div>
			</li>
			<li class="clearfix">
				<label class="species"><i>*</i>自定义标签:</label>
				<div class="word_box">
					<input type="text" value="<?php echo $data->Tags ?>" class="input_box text" style="width:270px" id="tags">
					<span class="altTxt">(多个标签用“,”隔开)</span> </div>
			</li>
		</ul>
		<div class="itemsBox">
			<div id="questionBox_0">
				<ul class="box_data_list">
					<li class="clearfix">
						<label class="species"><i>*</i>题型:</label>
						<div class="box_select">
							<?php
							echo CHtmlExt:: dropDownListAjax('qType', $data->tqtid, ArrayHelper::map(ItemTypeModel::model(),'secondCode','secondCodeValue'),
								array(
                                    "data-prompt-position"=>"inline",
                                    "data-prompt-target"=>"qTypeError",
									"defaultValue" => false, "prompt" => "请选择 ",
									'data-validation-engine' => 'validate[required]',
									'id'=>"type_0",
									'class'=>"xuanzhe02",
								));
							?>
                            <span id="qTypeError" class="errorTxt"></span>
						</div>
					</li>
					<li class="clearfix">
						<label class="species"><i>*</i>题目:</label>
						<div class="word_box clearfix" style="width:650px">
							<textarea id="container" style="width:600px; height:100px;"><?php echo $data->content?></textarea>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<hr>
		<?php \yii\widgets\ActiveForm::end(); ?>
		<div class="row submit_BtnBar">
			<div class="formL"></div>
			<div class="formR">
				<button type="button" class="bg_blue nextBtn p_reservation">下一步</button>
			</div>
		</div>


	</div>
</div>


