<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-16
 * Time: 上午11:53
 */
?>
<form id="form_editId">
    <ul class="form_list">
        <li>
            <div class="formL">
                <label><i></i>素材包名称:<input type="hidden" id="dataBagId" value="<?php echo $id;?>"></label>
            </div>
            <div class="formR">
                <input id="edit_name" type="text" class="text" value="<?php echo $model->Name;?>" data-validation-engine="validate[required,maxSize[30]]">
            </div>
        </li>
        <li>
            <div class="formL">
                <label><i></i>素材包权限:</label>
            </div>
            <div class="formR">
                <input type="radio" value="<?php echo $model->stuLimit;?>" name="stuLimit[stuLimit]" id="stu" data-validation-engine="validate[minCheckbox[1]]"> <label>所有人不可见</label>&nbsp;&nbsp;
<!--                <input type="radio" value="--><?php //echo $model->groupMemberLimit;?><!--" name="stuLimit[stuLimit]" id="group" data-validation-engine="validate[minCheckbox[1]]"><label>教研组可见</label>&nbsp;&nbsp;-->
                <input type="radio" value="<?php echo $model->departmentMemLimit;?>" name="stuLimit[stuLimit]" id="department" data-validation-engine="validate[minCheckbox[1]]"> <label>所有人可见</label>
                <br>
            </div>
        </li>
    </ul>
</form>
<script type="text/javascript">
    $(function(){
      var stuLimit=0;
      var group = 0;
      var department=$('#department').val();
        if(department==1){
            $("#department").attr("checked","checked");
        }else{
            $("#stu").attr("checked","checked");
        }
    });
    $('#form_editId').validationEngine({
        promptPosition:"centerRight",
        maxErrorsPerField:1,
        showOneMessage:true,
        addSuccessCssClassToField:'ok'
    })

</script>