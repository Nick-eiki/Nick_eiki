<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-15
 * Time: 上午11:02
 */
use frontend\models\dicmodels\GradeModel;
use yii\helpers\ArrayHelper;

?>
<div class="impBox">
    <form id="updateTeachingPlan">
        <ul class="form_list">
            <li>
                <div class="formL">
                    <label><i></i>教学计划名称：</label>
                </div>
                <div class="formR">
                    <input type="text" class="text" id="planName" value="<?php echo $teachingSearch->planName; ?>" data-validation-engine="validate[required]">
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>使用年级：</label>
                </div>
                <div class="formR">
                    <?php
                    echo Html::dropDownList('gradeID',
                        $teachingSearch->gradeID,
                        ArrayHelper::map( GradeModel::model()->getList(),'gradeId','gradeName'),
                        array('id'=>'gradeID','onchange'=>'change()'));
                    ?>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>描述：</label>
                </div>
                <div class="formR">
                    <textarea   id="brief" data-validation-engine="validate[required]"><?php echo $teachingSearch->brief;?></textarea>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>教学计划：</label>
                </div>
                <div class="formR">
                    <div class="fl" style="font-size:12px; color:#999999;">
                        <p class="addPic btn  btnpop">
                            <a href="javascript:" class="id_btn" style="color:#FFF;">上传计划</a>
                            <input id="ytedit" type="hidden" value="" name="XUploadForm[file]">
                            <input id="edit" class="file" name="XUploadForm[file]" type="file">
                        </p>


                        <em style="margin-left:10px;">文件格式限定为doc,docx或pdf</em>
                    </div>
                    <input type="hidden"  id="editurl"  value="<?php echo $teachingSearch->url;?>"/>
                    <ul class="add_del addPicUl"  id="editimg">

                        <?php
                        $url = explode(",",  $teachingSearch->url);
                        foreach($url as $v){?>
                            <li class="fl"><img src="<?php echo publicResources(). $v; ?>"><i></i></li>
                        <?php       }?>

                    </ul>

                </div>
                <input type="hidden" id="plan" value="<?php echo $id;?>">
            </li>

        </ul>
    </form>
</div>
<script>
    /**删除添加的图片*/
    $('.addPicUl li i').live('click',function(){
        $(this).parent().remove();
    });
    jQuery('#edit').fileupload({'done':editDone,'url':'/upload/doc','autoUpload':true,'formData':{},'dataType':'json','maxNumberOfFiles':1});
    $('#updateTeachingPlan').validationEngine({
        promptPosition:"centerRight",
        maxErrorsPerField:1,
        showOneMessage:true,
        addSuccessCssClassToField:'ok'
    })
</script>