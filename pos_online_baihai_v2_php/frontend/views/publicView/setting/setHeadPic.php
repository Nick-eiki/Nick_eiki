<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2015/5/29
 * Time: 16:41
 */
/* @var $this yii\web\View */  $this->title='修改头像';
$backend_asset = publicResources_new();
$this->registerCssFile($backend_asset . "/js/Jcrop/css/jquery.Jcrop.css".RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/Jcrop/js/jquery.Jcrop.min.js'.RESOURCES_VER);


?>
<script type="text/javascript" xmlns="http://www.w3.org/1999/html">

	$(function () {
		//    上传图片完成后的处理
		done = function (e, data) {
			$.each(data.result, function (index, file) {
				if (file.error) {
					popBox.alertBox(file.error);
					return;
				}
				img = file;
				//给隐藏表单赋值
				$("#xuwanting").attr("src", file.url);
				$('.faceIcon').val(file.url);
				setTimeout(function(){ popBox.uploadPic()},300);
			})
		};

		$(".save").click(function () {
			var url = '<?php echo url("ajax/image-pic")?>';
			var x = $('#jcrop_x1').val();
			var y = $('#jcrop_y1').val();
			var width = $('#jcrop_w').val();
			var height = $('#jcrop_h').val();
			//没有裁剪
			if (x == 0) {
				x = 0;
			}
			if (y == 0) {
				y = 0;
			}
			if (width == 0) {
				width = 500;
			}
			if (height == 0) {
				height = 500;
			}
			$.post(url, {name: img.url, x: x, y: y, width: width, height: height}, function (data) {
				if (data.success) {
					popBox.successBox("修改成功");
					location.reload();
				}
				else {
					popBox.errorBox("修改失败");
				}
			});
		});


	});


</script>
<!--主体-->
<div class="grid_19 main_r">
	<div class="main_cont userSetup upload_Pic">
		<div class="title">
			<a href="javascript:;" onclick="window.history.go(-1)" class="txtBtn backBtn"></a>
			<h4>个人信息设置</h4>
		</div>
		<div class="tab">
			<?php echo $this->render('//publicView/setting/_set_href'); ?>
			<div id="preview-pane" class="tabCont clearfix ">
				<div class="instructions">
					如果你还没有设置自己的头像，系统会显示为默认头像。<br>为了使其他用户更方便快捷的找到你，班海强烈建议您上传一张新照片作为您的个人头像。
				</div>
				<div class="picEditBar fl">
					<div id="uploadBtn" class="fileinput-button bg_green btn50 a_button popBox uploadPic"
					     style="width:352px; border-radius:3px" title="上传图片">
						<span class=" uploading">
					        <span class="id_btn Continue">选择文件</span>
							<?php
							$t1 = new frontend\widgets\xupload\models\XUploadForm;
							/** @var $this BaseController */
							echo  \frontend\widgets\xupload\XUploadSimple::widget( array(
								'url' => \Yii::$app->urlManager->createUrl("upload/header"),
								'model' => $t1,
								'attribute' => 'file',
								'autoUpload' => true,
								'multiple' => false,
								'options' => array(
									'acceptFileTypes' => new \yii\web\JsExpression('/(\.|\/)(gif|jpe?g|png)$/i'),
									'maxFileSize' => 2000000,
									"done" => new \yii\web\JsExpression('done'),
									"processfail" => new \yii\web\JsExpression('function (e, data) {var index = data.index,file = data.files[index]; if (file.error) {alert(file.error);}}')
								),
								'htmlOptions' => array(
									'id' => 't1',
								)
							));
							?>
					    </span>
					</div>

					<div class="default_pic">
						<div class="imgBar">
							<img  id="xuwanting"
							     src="<?php echo publicResources_new();?>/images/head_pic.png">
						</div>
						<input class="faceIcon" type="hidden"/>
						<input type="hidden" id="jcrop_x1">
						<input type="hidden" id="jcrop_y1">
						<input type="hidden" id="jcrop_x2">
						<input type="hidden" id="jcrop_y2">
						<input type="hidden" id="jcrop_w">
						<input type="hidden" id="jcrop_h">

					</div>
					<div class="btnBar tc">

						<button type="button" class="bg_blue btn40 w140 save" id="save">确定</button>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<button type="button" onclick="location.reload()" class="bg_blue_l btn40 w140">取消</button>

					</div>
				</div>
				<div class="pic_clipBar preview-container fl">
					<h5>头像效果预览</h5>

					<div id="preview_box230" class="clip_img clip_img230">
						<img id="crop_preview230"  title="230*230像素" height="230" width="230"
						     src="<?php echo publicResources_new();?>/images/head_pic_230.png">
					</div>
					<div id="preview_box70" class="clip_img clip_img70">
						<img id="crop_preview70"  title="70*70像素" height="70" width="70"
						     src="<?php echo publicResources_new();?>/images/head_pic_70.png">
					</div>
					<div id="preview_box50" class="clip_img clip_img50">
						<img id="crop_preview50"  title="50*50像素" height="50" width="50"
						     src="<?php echo publicResources_new();?>/images/head_pic_50.png">
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

