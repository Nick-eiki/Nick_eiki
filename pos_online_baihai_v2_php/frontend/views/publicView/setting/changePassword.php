<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2015/6/2
 * Time: 16:44
 */

use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title='修改密码';
$backend_asset = publicResources_new();

$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js');
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);

?>
<?php if (Yii::$app->getSession()->hasFlash('success')) { ?>
	<script type="text/javascript">
		$(function(){
			popBox.successBox('<?php echo Yii::$app->getSession()->getFlash('success'); ?>');
		})
	</script>
<?php } ?>
<!--top_end-->
<div class="grid_19 main_r">
	<div class="main_cont userSetup change_pwd">
		<div class="title">
			<a href="javascript:;" onclick="window.history.go(-1)" class="txtBtn backBtn"></a>
			<h4>个人信息设置</h4>
		</div>
		<div class="tab">
			<?php echo $this->render('//publicView/setting/_set_href'); ?>

			<div class="tabCont">
				<?php $form =\yii\widgets\ActiveForm::begin( array(
					'enableClientScript' => false,
				))?>
				<div class="form_list">
					<div class="row">
						<div class="formL">
							<label>旧密码：</label>
						</div>
						<div class="formR">
							<input type="password"
							       class="text w310"
							       id="<?php echo Html::getInputId($model, 'oldpasswd') ?>"
							       name="<?php echo  Html::getInputName($model,'oldpasswd')?>"
							       data-validation-engine="validate[required,minSize[6],maxSize[20]]"
							       data-prompt-position="inline"
							       data-prompt-target="oldError"
								/>
							<?php echo frontend\components\CHtmlExt::validationEngineError($model, 'oldpasswd') ?>
							<span class="errorTxt" id="oldError" style="left:330px"></span>
						</div>
					</div>
					<div class="row">
						<div class="formL">
							<label>新密码：</label>
						</div>
						<div class="formR">
							<input type="password"
							       class="text w310"
							       name="<?php echo  Html::getInputName($model,'passwd')?>"
							       id="<?php echo Html::getInputId($model, 'passwd') ?>"
							       data-validation-engine="validate[required,minSize[6],maxSize[20]]"
							       data-prompt-position="inline"
							       data-prompt-target="newError"/>
							<?php echo frontend\components\CHtmlExt::validationEngineError($model, 'passwd') ?>
							<span class="errorTxt" id="newError" style="left:330px"></span>
						</div>
					</div>
					<div class="row">
						<div class="formL">
							<label>确认密码：</label>
						</div>
						<div class="formR">
							<input type="password"
							       class="text w310"
							       name="<?php echo  Html::getInputName($model,'repasswd')?>"
							       data-validation-engine="validate[required,equals[<?php echo Html::getInputId($model, 'passwd') ?>]]"
							       data-prompt-position="inline"
							       data-prompt-target="affirmError"/>
							<span class="errorTxt" id="affirmError" style="left:330px"></span>
						</div>
					</div>
					<div class="row">
						<div class="formL">
							<label></label>
						</div>
						<div class="formR">
							<button type="submit" class="bg_blue btn40 w140">保存</button>
						</div>
					</div>
				</div>
				<?php \yii\widgets\ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>
