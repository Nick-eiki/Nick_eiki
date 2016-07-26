<?php
/**
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2016/7/16
 * Time: 10:40
 */
?>
<h4>学生信息</h4>
<div>
    <lable for="stu_ID_again">学号：</lable>
    <input id="stu_ID_again" name="stu_ID_again" type="text"/><br>
    <lable for="stu_name_again"><i class="req">*</i>姓名：</lable>
    <input id="stu_name_again" name="stu_name_again" type="text" class="read_only user_select_" value="<?php echo $userDetails->trueName?>"/><br>
    <lable for="stu_mol"><i class="req read_only">*</i>手机号：</lable>
    <input id="stu_mol_again" name="stu_mol" type="text" class="user_select_" value="<?=$userDetails->bindphone?>"/><br>
    <lable>性别：</lable>
    <input type="radio" name="sex" id="male_again" class="read_only" value='0' <?php if($userDetails->sex==0){
        echo "checked='checked'";
    }?>/>&nbsp;男
    <input type="radio" name="sex" id="female_again" value="1" class="read_only" <?php
     if($userDetails->sex){
         echo "checked='checked'";
     }
    ?>/>&nbsp;女<br>
    <lable>帐号：</lable>
    <input type="text" class="read_only user_select_" id="phoneReg" value="<?=$userDetails->phoneReg?>" />

</div>
<?php if($userDetails->phone!=null){?>
<h4>学生家长信息</h4>
<div id="parent_message">
    <lable for="parent_ID">家长姓名：</lable>
    <input id="parent_ID" name="parent_ID" type="text" value="<?=$userDetails->parentsName?>"  data-validation-engine="validate[maxSize[20]]"/><br>
    <lable for="parent_mol"><i class="req">*</i>手机号：</lable>
    <input id="parent_mol" name="parent_mol" type="text" class="user_select_" value="<?=$userDetails->phone?>"/><br>
</div>
<?php }?>
<div class="btn_class">
    <button class="alter btn" id="return_up" type="button">上一步</button>
    <button class="btn" id="submit_update" type="button">确定</button>
</div>
<script>
    $('#edit_user_info_form_again').validationEngine();
</script>