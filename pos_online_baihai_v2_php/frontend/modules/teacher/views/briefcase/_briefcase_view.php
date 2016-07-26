<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-17
 * Time: 下午2:35
 */
?>
<form id="updatebriefcase">
    <ul class="form_list">
        <li>
            <div class="formL">
                <label><i></i>公文包名称:<input type="hidden" id="briefcaseId" value="<?php echo $id;?>"></label>
            </div>
            <div class="formR">
                <input id="edit_name" type="text" class="text" value="<?php echo $model->Name;?>" data-validation-engine="validate[required,maxSize[30]]">(最多可输入30个字)
            </div>
        </li>
        <li>
            <div class="formL">
                <label><i></i>公文袋权限:</label>
            </div>
            <div class="formR">
                <input type="radio" value="<?php echo $model->stuLimit;?>" name="stuLimit[stuLimit]" id="stu" data-validation-engine="validate[minCheckbox[1]]"> <label>所有人不可见</label>&nbsp;&nbsp;

                <input type="radio" value="<?php echo $model->departmentMemLimit;?>" name="stuLimit[stuLimit]" id="department" data-validation-engine="validate[minCheckbox[1]]"> <label>所有人可见</label>
                <br>
            </div>
        </li>
    </ul>
</form>
<script type="text/javascript">
    $(function(){
        var stuLimit=0;
        var group =0;
        var department=$('#department').val();
        if(department==1){
            $("#department").attr("checked","checked");
        }else{
            $("#stu").attr("checked","checked");
        }

    });
    $('#updatebriefcase').validationEngine({
        promptPosition:"centerRight",
        maxErrorsPerField:1,
        showOneMessage:true,
        addSuccessCssClassToField:'ok'
    })

</script>