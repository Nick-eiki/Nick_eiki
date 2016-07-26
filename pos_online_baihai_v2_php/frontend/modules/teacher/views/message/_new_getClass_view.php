<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-22
 * Time: 下午6:16
 */
?>
<div class="popCont">
    <div class="new_tch_group">
        <h5>选择学生</h5>
        <hr>
        <ul class="multi_resultList sut_list clearfix" id="multi_resultList">
            <?php foreach($loadModel as $val){  ?>
               <li data_user="<?php echo $val->userID;?>"><?php echo  $val->stuID.'&nbsp;'.$val->memName;?></li>
         <?php    }?>
            </ul>
    </div>
</div>
<div class="popBtnArea">
    <button type="button" class="okBtn" id="okBtn">确定</button>
    <button type="button" class="cancelBtn BtnCancel">取消</button>
</div>

