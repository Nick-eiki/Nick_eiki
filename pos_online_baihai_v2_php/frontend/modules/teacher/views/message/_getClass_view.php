<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-24
 * Time: 上午10:57
 */
?>
<div class="impBox">
    <table class="stu_table">
        <colgroup>
            <col style="width:58px">
            <col style="width:145px">
            <col style="width:89px">
            <col style="width:136px">
        </colgroup>
        <thead>
        <tr>
            <th class="th_checkbox"><input type="checkbox" class="checkbox checkAll"></th>
            <th>学号</th>
            <th>姓名</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
       <?php foreach($loadModel as $key=>$val){ ?>
        <tr>
            <td  class="th_checkbox"><input type="checkbox" class="checkbox" value="<?php echo $val->userID; ?>"></td>
            <td><?php echo $val->stuID;?></td>
            <td><?php echo $val->memName;?></td>
            <td></td>
        </tr>
       <?php } ?>
      </tbody>
    </table>
</div>