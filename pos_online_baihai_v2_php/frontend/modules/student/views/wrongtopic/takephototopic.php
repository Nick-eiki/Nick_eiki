<?php
use frontend\components\CHtmlExt;
use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\DegreeModel;
use frontend\models\dicmodels\EditionModel;
use frontend\models\dicmodels\GradeModel;
use frontend\models\dicmodels\SubjectModel;
use frontend\widgets\xupload\XUploadSimple;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title='拍照录题';
?>
<script>
	$(function(){

		$('#upWork').validationEngine({
			validateNonVisibleFields:true,
			promptPosition:"centerRight",
			maxErrorsPerField:1,
			showOneMessage:true,
			addSuccessCssClassToField:'ok'
		});

		$('.confirm').click(function(){
			$('#fileupload').val();
		});

		$('#upWork').submit(function() {
			//判断验证是否通过
			if ($(this).validationEngine('validate') == false) {
				return false;
			}
			if($('.img_list').find('.pic_list').length<1){
				popBox.alertBox('请上传试卷！');
				return false;
			}
		});

	})
</script>
<!--主体-->

<div class="grid_19 main_r">
	<div class="main_cont justifying">
		<div class="title"> <a href="javascript:window.history.go(-1)" class="txtBtn backBtn"></a>
			<h4>拍照录题</h4>
		</div>
		<br>
		<?=Html::beginForm(['take-photo-topic'],'post',['id'=>'upWork']);?>
		<div class="form_list ">
			<div class="row">
				<div class="formL">
					<label>适用地区</label>
				</div>
				<div class="formR">
					<?php
					echo CHtmlExt::dropDownListAjax(Html::getInputName($model, "provience"), $model->provience, ArrayHelper::map(AreaHelper::getProvinceList(), 'AreaID', 'AreaName'),
						array(
							"defaultValue" => false, "prompt" => "请选择",
							'ajax' => array(
								'url' => Yii::$app->urlManager->createUrl('ajax/get-area'),
								'data' => array('id' => new \yii\web\JsExpression('this.value')),
								'success' => 'function(html){jQuery("#' . Html::getInputId($model, "city") . '").html(html).change();}'
							),
							"id" => Html::getInputId($model, "provience"),

						));
					?>
					<label>省</label>
					<?php
					echo CHtmlExt::dropDownListAjax(Html::getInputName($model, "city"), $model->city, ArrayHelper::map(AreaHelper::getCityList($model->provience), 'AreaID', 'AreaName'), array(
						"defaultValue" => false, "prompt" => "请选择", "id" => Html::getInputId($model, "city"),
						'ajax' => array(
							'url' => Yii::$app->urlManager->createUrl('ajax/get-area'),
							'data' => array('id' => new \yii\web\JsExpression('this.value')),
							'success' => 'function(html){jQuery("#' . Html::getInputId($model, "country") . '").html(html).change();}'
						),
					));
					?>
					<label>市</label>
					<?php
					echo CHtmlExt::dropDownListAjax(Html::getInputName($model, "country"), $model->country, ArrayHelper::map(AreaHelper::getRegionList($model->city), 'AreaID', 'AreaName'), array(
						"defaultValue" => false, "prompt" => "请选择", "id" => Html::getInputId($model, "country"),
						'data-prompt-target' => "diqu_prompt",
						'data-prompt-position' => "inline"
					));
					?>
					<label>区</label>
					<span id="diqu_prompt"></span>
				</div>
			</div>
			<div class="row">
				<div class="formL">
					<label><i>*</i>适用年级</label>
				</div>
				<div class="formR">
					<?php echo CHtmlExt::dropDownListAjax(Html::getInputName($model, 'gradeid'), $model->gradeid, GradeModel::model()->getListData(), array(
						'prompt' => '请选择',
						'data-validation-engine' => 'validate[required]',
						'data-prompt-target' => "gradeError",
						'data-prompt-position' => "inline",
						'id' => Html::getInputId($model, 'gradeid'),
						'ajax' => array(
                            'url' => Yii::$app->urlManager->createUrl('ajax/get-item-for-grade'),
                            'data' => array('id' => new \yii\web\JsExpression('this.value')),
                            'success' => 'function(html){jQuery("#' . Html::getInputId($model, 'subjectid') . '").html(html).change();}'
                        ),
					))?>
					<?php echo CHtmlExt::validationEngineError($model, 'gradeid') ?><span id="gradeError" class="errorTxt" style="left:120px"></span>
				</div>
			</div>
			<div class="row">
				<div class="formL">
					<label><i>*</i>科目</label>
				</div>

				<div class="formR">
					<?php echo CHtmlExt::dropDownListAjax(Html::getInputName($model, 'subjectid'), $model->subjectid, SubjectModel::model()->getListData(), array(
						'prompt' => '请选择', 'data-validation-engine' => 'validate[required]',
						'data-prompt-target' => "subjectError",
						'data-prompt-position' => "inline",
						'id' => Html::getInputId($model, 'subjectid')
					))?>
					<?php echo CHtmlExt::validationEngineError($model, 'subjectid') ?><span id="subjectError" class="errorTxt" style="left:120px"></span>
				</div>
			</div>
			<div class="row">
				<div class="formL">
					<label><i>*</i>版本</label>
				</div>
				<div class="formR">
					<?php echo CHtmlExt::dropDownListAjax(Html::getInputName($model, 'versionid'), $model->versionid, EditionModel::model()->getListData(), array(
						'prompt' => '请选择',
						'data-validation-engine' => 'validate[required]',
						'data-prompt-target' => "versionError",
						'data-prompt-position' => "inline",
						'id' => Html::getInputId($model, 'versionid')
					))?>
					<?php echo frontend\components\CHtmlExt::validationEngineError($model, 'versionid') ?><span id="versionError" class="errorTxt" style="left:120px"></span>
				</div>
			</div>
			<div class="row">
				<div class="formL">
					<label><i>*</i>难易程度</label>
				</div>
				<div class="formR">
					<?php echo CHtmlExt::dropDownListAjax(Html::getInputName($model, 'complexity'), $model->complexity, DegreeModel::model()->getListData(), array(
						'prompt' => '请选择',
						'data-validation-engine' => 'validate[required]',
						'data-prompt-target' => "complexityError",
						'data-prompt-position' => "inline",
						'id' => Html::getInputId($model, 'complexity')
					))?>
					<?php echo CHtmlExt::validationEngineError($model, 'complexity') ?><span id="complexityError" class="errorTxt" style="left:120px"></span>
				</div>
			</div>
			<div class="row">
				<div class="formL">
					<label><i>*</i>题目详情</label>
				</div>
				<div class="formR">
					<div class="imgFile">
						<ul class="up_test_list clearfix img_list">
							<li class="more">
								<?php
								$t1 = new frontend\widgets\xupload\models\XUploadForm;
								/** @var $this BaseController */
								echo  XUploadSimple::widget( array(
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

					</div>
				</div>
			</div>
			<div class="row">
				<div class="formL">
					<label>答案与解析</label>
				</div>
				<div class="formR">
					<div class="imgFile">
						<ul class="up_test_list clearfix ">
							<li class="more">
								<?php
								$t1 = new frontend\widgets\xupload\models\XUploadForm;
								/** @var $this BaseController */
								echo  XUploadSimple::widget( array(
									'url' => Yii::$app->urlManager->createUrl("upload/pic"),
									'model' => $t1,
									'attribute' => 'file',
									'autoUpload' => true,
									'multiple' => true,
									'options' => array(
										'acceptFileTypes' => new \yii\web\JsExpression('/(\.|\/)(jpg|png|jpeg)$/i'),
										"done" => new \yii\web\JsExpression('doneTwo'),
										"processfail" => new \yii\web\JsExpression('function (e, data) {var index = data.index,file = data.files[index]; if (file.error) {alert(file.error);}}')
									),
									'htmlOptions' => array(
										'id' => 'imgupload',
									)
								));
								?>
							</li>
						</ul>

					</div>
				</div>
			</div>
			<div class="row">
				<div class="formL">
					<label></label>
				</div>
				<div class="formR submitBtnBar">
					<button type="submit" class="bg_blue btn40 confirm">保存题目</button>
				</div>
			</div>
		</div>
		<?=Html::endForm();?>
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
			$('<li class="pic_list"><input type="hidden" id="picurls" name="picurls[]" value="' + file.url + '" /> <img src="' + file.url + '" alt=""><span class="delBtn"></span></li>').insertBefore( $(e.target).parent());

		});
	};

	doneTwo = function(e, data) {

		$.each(data.result, function (index, file) {
			k++;
			if(file.error){
				popBox.errorBox(file.error);
				return ;
			}
			$('<li><input type="hidden" id="imgurls" name="imgurls[]" value="' + file.url + '" /> <img src="' + file.url + '" alt=""><span class="delBtn"></span></li>').insertBefore( $(e.target).parent());

		});
	};
	$('#upWork').validationEngine({
		validateNonVisibleFields:true,
		promptPosition:"centerRight",
		maxErrorsPerField:1,
		showOneMessage:true,
		addSuccessCssClassToField:'ok'
	});

</script>