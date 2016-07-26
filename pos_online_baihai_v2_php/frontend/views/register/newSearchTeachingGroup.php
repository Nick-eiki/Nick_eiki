<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-10
 * Time: 下午2:14
 */
use frontend\models\dicmodels\SubjectModel;
use yii\helpers\Url;

?>
<div class="subTitleBar">
    <h5>选择教研组</h5>
</div>
<div class="popCont">
    <div class="crumbListWrap"> <a href="#" class="txtBtn gray_d creat_tch_group">创建教研组</a>

        <div class="crumbList clearfix">
            <span class="ac"><?php echo SubjectModel::model()->getSubjectName($subjectID); ?></span>
        </div>
    </div>
    <ul class="resultList tch_group_List clearfix">
        <?php
        if(empty($pageList)){ ?>
           <div class="prompt">没有检索到相应的教研组，点击创建新教研组</div>
       <?php  }else{
        foreach ($pageList as $item) { ?>
            <li id="<?php echo $item->groupID; ?>"
                title="<?php echo $item->groupName; ?>"><?php echo $item->groupName; ?></li>
        <?php } } ?>

    </ul>
    <div class="new_tch_group hide">
        <form id="addGroupInfo" action="<?php echo Url::to(['add-teaching-group']) ?>">
            <div class="form_list">
                <div class="row">
                    <div class="formL">
                        <label>教研组名称</label>
                    </div>
                    <div class="formR">
                        <input type="text" class="text" name="groupName" data-validation-engine="validate[required,maxSize[30]]"
                               data-errormessage-value-missing="教研组名称不能为空"
                               data-prompt-target="groupName_prompts" data-prompt-position="inline">
                             <span id="groupName_prompts" class="errorTxt"></span>
                    </div>
                </div>
                <input name="subjectID" type="hidden" value="<?php echo $subjectID; ?>">
                <input name="schoolId" type="hidden" value="<?php echo $pages->params['schoolId'] ?>">
                <input name="department" type="hidden" value="<?php echo $pages->params['department'] ?>">
            </div>
        </form>
    </div>
</div>
<div class="popBtnArea">
    <button type="button" class="okBtn">确定</button>
    <button type="button" class="cancelBtn">取消</button>
</div>
<script type="text/javascript">
    $('#addGroupInfo').validationEngine({'maxErrorsPerField': 1});
</script>