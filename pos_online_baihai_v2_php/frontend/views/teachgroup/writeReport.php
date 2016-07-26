<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2015/7/27
 * Time: 15:25
 */
use frontend\components\CHtmlExt;
use frontend\widgets\ueditor\MiniUEditor;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->registerCssFile("@web/ueditor/themes/default/css/ueditor.min.css");
$this->registerJsFile("@web/ueditor/ueditor.config.little.js");
$this->registerJsFile("@web/ueditor/ueditor.all.min.js");

$this->title="写报告";
?>

	<script>
		$(function() {
			formValidationIni('#write');
			$('#report_sub').click(function(){
				var reportTitle = $('#report_title').val();
				var reportContent = UE.getEditor('report_content').getContent();
				if(reportTitle == ''){
					popBox.errorBox('请填写名称！');
					return false;
				}
				if(reportContent == ''){
					popBox.errorBox('请填写内容！');
					return false;
				}
				return true;
			})
		});
	</script>

		<div class="grid_16 alpha omega main_l">
			<div class="main_cont  listen_comment">
				<div class="title">
                    <a href="<?= url::to(['teachgroup/listen-lessons','groupId'=>app()->request->getQueryParam('groupId')]) ?>"
                       class="txtBtn backBtn"></a>
					<h4>写报告</h4>
				</div>
				<div class="date_sc">
					<?php $form = ActiveForm::begin( array('enableClientScript' => false, 'id'=>'write' )) ?>
					<dl class="report_cont">
						<dt><span>* </span>&nbsp;名称</dt>
						<dd>
							<input id="report_title" type="text" class="input_txt" name="report_title" value="<?php  echo $model->reportTitle?>" data-errormessage-value-missing="名称不能为空！" data-prompt-position="inline" data-prompt-target="titleError"  data-validation-engine="validate[required]">
							<span id="titleError" class="errorTxt" style="border: none; margin-left: 0px; left: inherit"></span>
							<?php echo CHtmlExt::validationEngineError($model, 'reportTitle','report_title') ?>
						</dd>
					</dl>
					<dl class="report_cont">
						<dt><span>* </span>&nbsp;内容</dt>
						<dd>
							<?php
							echo MiniUEditor::widget(
								[
									'id' => 'report_content',
									'name' => 'report_content',
									'model'=>$model,
									'attribute'=>'reportContent',

									'UEDITOR_CONFIG' => [
										'initialFrameHeight' => '338',
										'initialFrameWidth' => '',
									],

								]);
							?>
						</dd>
						<?php echo CHtmlExt::validationEngineError($model, 'reportContent','report_content') ?>
					</dl>
					<dl class="report_cont">
						<dt>&nbsp;
						</dt>
						<dd>
							<button type="submit" id="report_sub" class="btn btn40 bg_blue w140">确定</button>
						</dd>
					</dl>
					<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>

<!--主体end-->
