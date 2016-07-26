<?php
/**
 *
 * @var ManagepaperController $this
 */
use frontend\components\CHtmlExt;
use frontend\models\dicmodels\EditionModel;
use frontend\models\dicmodels\GradeModel;
use frontend\models\dicmodels\SubjectModel;
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title='上传试卷';
$backend_asset = publicResources_new();
;
$this->registerJsFile($backend_asset . '/js/echarts/echarts-all.js');
?>
<link rel="stylesheet" href="">
<script>
	$(function () {
		var zNodes = [];
		$('.addPointBtn').click(function () {
			var subjectId = $("#<?php echo  Html::getInputId($model,'subjectID') ?>").val();
			if(subjectId ==""){
				popBox.alertBox('科目不能为空！');
				return false;
			}
			var grade = $("#paperform-gradeid").val();
			if(grade ==""){
				popBox.alertBox('年级不能为空！');
				return false;
			}
			var url = "/ajaxteacher/get-knowledge";
			$this = $(this);
			$.post(url, {'subjectID': subjectId, 'grade': grade}, function (msg) {
				if (msg.success) {
					zNodes = msg.data;

				} else {
					zNodes = [];
				}
				popBox.pointTree(zNodes, $this);

			});
		});
		$('#PaperForm_subjectID,#PaperForm_gradeID').change(function(){
			$('.clsTreeBox').empty();
			$('.labelList').empty();
		});

		$('#form1').submit(function(){
			//判断验证是否通过
			if ($(this).validationEngine('validate') == false){
				return false;
			}
			if($('.up_test_list').find('.pic_list').length<1){
				popBox.alertBox('请上传试卷！');
				return false;
			}
		});
	});

</script>


<!--主体-->
<div class="grid_19 main_r">
	<div class="main_cont test justifying">
		<div class="title"> <a href="javascript:" onclick="window.history.go(-1);" class="txtBtn backBtn"></a>
			<h4>上传试卷</h4>
		</div>
		<br>
		<div class="form_list ">
			<?php $form =\yii\widgets\ActiveForm::begin( array(
				'enableClientScript' => false,
				'id' => 'form1'
			))?>
			<div class="row">
				<div class="formL">
					<label><i>*</i>试卷名称</label>
				</div>
				<div class="formR" style="position: relative">
					<input type="text" class="text" style="width:518px"
						   data-prompt-target="department_prompt"
						   id="<?php echo Html::getInputId($model, 'paperName')?>"
						   name="<?php echo Html::getInputName($model, 'paperName')?>"
						   data-validation-engine="validate[required,maxSize[30]]"
						   data-prompt-position = "inline"
						/>
					<span id="department_prompt" style="left: 610px" class="errorTxt" ></span>
					<?php echo frontend\components\CHtmlExt::validationEngineError($model, 'paperName') ?>
					<span class="gray">(30字以内)</span>
				</div>
			</div>
			<div class="row">
				<div class="formL">
					<label><i>*</i>年级</label>
				</div>
				<div class="formR">
					<?php echo CHtmlExt::dropDownListAjax(Html::getInputName($model, 'gradeID'), $model->gradeID, GradeModel::model()->getListData(), array(
						'prompt' => '请选择', 'data-validation-engine' => 'validate[required]',
						'id' => Html::getInputId($model, 'gradeID'),
						'ajax' => [
							'url' => Yii::$app->urlManager->createUrl('ajax/get-item-for-grade'),
							'data' => ['id' => new \yii\web\JsExpression('this.value')],
							'success' => 'function(html){jQuery("#' . Html::getInputId($model, 'subjectID') . '").html(html).change();}'
                        ],

					))?>
					<?php echo frontend\components\CHtmlExt::validationEngineError($model, 'gradeID') ?>
				</div>
			</div>
			<div class="row">
				<div class="formL">
					<label><i>*</i>科目</label>
				</div>
				<div class="formR">
					<?php echo CHtmlExt::dropDownListAjax(Html::getInputName($model, 'subjectID'), $model->subjectID, SubjectModel::model()->getListData(), array(
						'prompt' => '请选择', 'data-validation-engine' => 'validate[required]',
						'id' => Html::getInputId($model, 'subjectID'),
                        'ajax' => [
                            'url' => Yii::$app->urlManager->createUrl('ajax/get-version'),
                            'data' => ['subject' => new \yii\web\JsExpression('this.value')],
                            'success' => 'function(html){jQuery("#' . Html::getInputId($model, "versionID") . '").html(html).change();}'
                        ]
					))?>
					<?php echo frontend\components\CHtmlExt::validationEngineError($model, 'subjectID') ?>
				</div>
			</div>
			<div class="row">
				<div class="formL">
					<label><i>*</i>教材版本</label>
				</div>
				<div class="formR">
					<?php echo CHtmlExt::dropDownListAjax(Html::getInputName($model, 'versionID'), $model->versionID, EditionModel::model()->getListData(), array(
						'prompt' => '请选择',
						'data-validation-engine' => 'validate[required]',
						'id' => Html::getInputId($model, 'versionID')
					))?>

					<?php echo frontend\components\CHtmlExt::validationEngineError($model, 'versionID') ?>
				</div>
			</div>
			<div class="row">
				<div class="formL">
					<label>涉及知识点</label>
				</div>
				<div class="formR">
					<div id="tree_0" class="treeParent">
						<?php echo  frontend\widgets\extree\XTree::widget([
							'model' => $model,
							'attribute' => 'knowledgePoint',
							'options' => [
								'htmlOptions' => []
                            ]]);?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="formL">
					<label><i>*</i>上传试卷</label>
				</div>
				<div class="formR">
					<div class="imgFile">
						<ul class="up_test_list clearfix">

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
<!--								<input class="paperRoute" name="--><?php //echo Html::getInputName($model, 'paperRoute')?><!--" type="hidden"/>-->
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="formL">
					<label>试卷介绍：</label>
				</div>
				<div class="formR" style="position: relative">
					<?php
					////富文本模式   留底以备下次修改~

//					echo \frontend\widgets\ueditor\MiniUEditor::widget(
//						array(
//							'id' => 'editor',
//							'model' => $model,
//							'attribute' => 'summary',
//							'UEDITOR_CONFIG' => array(
//								'initialFrameHeight' => '200',
//								'initialFrameWidth' => '600',
//							),
//						));
//					?>

					<textarea name="<?php echo Html::getInputName($model, 'summary') ?>"
					          data-validation-engine="validate[maxSize[300]]"
					          data-prompt-target="describe_prompt"
					          data-prompt-position="inline"
					          style="width: 700px;;"
					          ></textarea>
					<span id="describe_prompt" class="errorTxt" style="left: 570px;top: 80px;"></span>
				</div>
			</div>
			<div class="row">
				<div class="formL">
					<label></label>
				</div>
				<div class="formR submitBtnBar">
					<button type="submit" class="bg_blue btn40" onclick="return checkKnowledge();">确定</button>

				</div>
			</div>
			<?php \yii\widgets\ActiveForm::end()?>
		</div>

	</div>
</div>
<script>
	done = function(e, data) {
		$.each(data.result, function (index, file) {
			if(file.error){
				popBox.errorBox(file.error);
				return ;
			}
			$('<li class="pic_list"><input type="hidden" class="imgUrl" name="picurls[]" value="' + file.url + '" /> <img src="' + file.url + '" alt=""><span class="delBtn"></span></li>').insertBefore(".more");
		});
	};

	$('#form1').validationEngine({
		validateNonVisibleFields:true,
		promptPosition:"centerRight",
		maxErrorsPerField:1,
		showOneMessage:true,
		addSuccessCssClassToField:'ok'
	});

</script>
