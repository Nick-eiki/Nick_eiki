<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2015/7/27
 * Time: 16:56
 */
$publicResources = Yii::$app->request->baseUrl;
$this->registerJsFile($publicResources . '/pub/js/My97DatePicker/WdatePicker.js');
use yii\helpers\Html;

?>
<script type="text/javascript">
	$(function(){
		$('#plan').click(function(){
			var courseName = $('#notice_name').val();
			var onStaff = $('.radio-staff input[name="tea_type"]:checked');

		})
	})
</script>
<div class="popCont">
	<div class="new_tch_group">
		<form id="form_id" >
			<div class="form_list">
				<div class="row clearfix">
					<div class="formL">
						<label>课程名称：</label>
					</div>
					<div class="formR">
						<input id="notice_name" type="text" class="text" value="" data-errormessage-value-missing="名称不能为空！" data-prompt-position="inline" data-prompt-target="titleError"  data-validation-engine="validate[required]">
						<span id="titleError" class="errorTxt" style="border: none;margin-left: 0px"></span>
					</div>
				</div>
				<div class="row">
					<div class="formL">
						<label>主讲人员:</label>
					</div>
					<div class="formR personal">
            <div style="width: 300px">
                        <?php echo \yii\helpers\Html::radioList("speakers","",$teacherList,["itemOptions"=>["data-validation-engine"=>"validate[required]"]])?>
<!--                        <span id="speakersError" class="errorTxt" style="margin-left: 0px"></span>-->
                    </div>
                    </div>
				</div>
				<div class="row">
					<div class="formL">
						<label>参与人员:</label>
					</div>
					<div class="formR personal">
                        <div style="width: 300px">
                        <?php
                        echo Html::checkBoxList("joiner","",$teacherList,["itemOptions"=>["data-validation-engine"=>"validate[required]"]]);
                        ?>
                        <span id="joinerError" class="errorTxt" style="border: none;margin-left: 0px"></span>
					</div>
                        </div>

				</div>
				<div class="row">
					<div class="formL">
						<label>听课时间:</label>
					</div>
					<div class="formR">
						<input type="text" class="text" id="listenTime" name="ExamForm[examTime]"
						       onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',maxDate:'2099'})"
						       data-validation-engine="validate[required] validate[minSize[2]] validate[maxSize[20]]"
						       data-prompt-position="inline" data-prompt-target="listenTimeError">
                        <span id="listenTimeError" class="errorTxt" style="border: none;margin-left: 0px"></span>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>