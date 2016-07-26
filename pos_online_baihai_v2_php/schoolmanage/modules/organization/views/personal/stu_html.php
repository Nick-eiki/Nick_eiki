<?php
/**
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2016/7/16
 * Time: 16:23
 */
?>
<h4>学生信息</h4>
<div>
    <lable for="stu_ID_again">学号：</lable>
    <input  id="stu_ID_again" name="stu_ID_again" type="text"/><br>
    <lable for="stu_name_again"><i class="req">*</i>姓名：</lable>
    <input id="stu_name_again" name="stu_name_again" type="text" class="read_only user_select_"
           data-validation-engine="validate[required,minSize[2],maxSize[20]]"
           data-errormessage-value-missing="用户名不能为空"
           value="<?=$trueName?>"
        /><br>
    <lable for="stu_mol"><i class="req read_only">*</i>手机号：</lable>
    <input id="stu_mol_again" name="stu_mol" type="text" class="user_select_"
           data-validation-engine= "validate[required,custom[phone]]"
           data-errormessage-value-missing="手机号不能为空"
           value="<?=$bindphone?>"
        /><br>
    <lable>性别：</lable>
    <input type="radio" name="sex" id="male_again" class="read_only" value="0"/>&nbsp;男
    <input type="radio" name="sex" id="female_again" class="read_only" value="1"/>&nbsp;女<br>
    <lable><i class="req">*</i>帐号：</lable>
    <input type="text"  class="read_only user_select_"
           data-validation-engine="validate[required,minSize[2],maxSize[20]]"
           data-errormessage-value-missing="账号不能为空"
           id="phoneReg"
           value="<?=$phoneReg?>" disabled="disabled" style="border: 0px;background:#ffffff"
        />
    <ul>
        <li class="posit">提示:&nbsp;</li>
        <li>
            1.新创建的学生帐号的帐号为:<?=$phoneReg?>。<br>
            2.新创建的学生帐号的初始密码为:123456。
        </li>
    </ul>
</div>

<div class="btn_class">
    <button class="alter btn" id="return_up" type="button">上一步</button>
    <button class="btn" id="submit_new" type="button">确定</button>
</div>
<script>
    $('#edit_user_info_form_again').validationEngine();
</script>