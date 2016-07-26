<?php
/**
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2016/7/13
 * Time: 18:51
 */
?>
<tbody>
<tr id="table_header">
    <td class="short"></td>
    <td class="short">姓名</td>
    <td class="short">性别</td>
    <td class="long">手机号</td>
    <td class="long">账号</td>
    <td class="short">学科</td>
</tr>
<?php foreach($userResult as $v){?>
<tr>
    <td class="short"><input type="radio" value="<?php echo $v->userID?>" name="find_name"></td>
    <td class="short"><?php echo $v->trueName?></td>
    <td class="short"><?php echo $v->sex?'女':'男'?></td>
    <td class="long"><?php echo $v->bindphone?></td>
    <td class="long"><?php echo $v->phoneReg?></td>
    <td class="short"><?php echo \frontend\models\dicmodels\SubjectModel::model()->getSubjectName($v->subjectID)?></td>
</tr>
<?php }?>
</tbody>