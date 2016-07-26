<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 2015/7/29
 * Time: 10:52
 */
use yii\helpers\Html;

/** @var common\models\pos\SeGroupLecturePlan $lectureResult */
?>
<div class="popCont">
    <div class="new_tch_group">
        <form id="update_form_id" >
            <div class="form_list">
                <div class="row clearfix">
                    <div class="formL">
                        <label>课程名称：</label>
                    </div>
                    <div class="formR">
                        <input id="update_notice_name" type="text" class="text" value="<?=Html::encode($lectureResult->title)?>" data-errormessage-value-missing="名称不能为空！" data-prompt-position="inline" data-prompt-target="updateTitleError"  data-validation-engine="validate[required]">
                        <span id="updateTitleError" class="errorTxt" style="border: none;margin-left: 0px"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label>主讲人员:</label>
                    </div>
                    <div class="formR personal">
                        <div style="width: 300px">
                        <?php echo Html::radioList("updateSpeakers",$lectureResult->teacherID,$teacherList,["itemOptions"=>["data-validation-engine"=>"validate[required]"]])?>
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
                        echo Html::checkBoxList("updateJoiner",$joinArray,$teacherList,["itemOptions"=>["data-validation-engine"=>"validate[required]"]]);
                        ?>
                            </div>
                    </div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label>听课时间:</label>
                    </div>
                    <div class="formR">
                        <input type="text" class="text" id="updateListenTime"
                               onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',maxDate:'2099'})"
                               data-validation-engine="validate[required] validate[minSize[2]] validate[maxSize[20]]"
                               data-prompt-position="inline" data-prompt-target="updateListenTimeError"
                               value="<?=date('Y-m-d h:i',strtotime($lectureResult->joinTime))?>"
                            >
                        <span id="updateListenTimeError" class="errorTxt" style="border: none;margin-left: 0px"></span>
                        <input type="hidden" id="lecturePlanID" value="<?=$lectureResult->lecturePlanID?>">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        formValidationIni('#update_form_id');
    })
</script>