<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-24
 * Time: 上午10:57
 */
?>
<div class="popCont">
    <div class="new_tch_group">
        <h5>选择学生</h5>
        <hr>
        <ul class="multi_resultList sut_list clearfix" id="multi_resultList">
            <?php foreach($loadModel as $key=>$val){ ?>
                <li style="width: 170px;" data_user="<?php echo $val->userID;?>"><?php echo  $val->stuID.'&nbsp;'.$val->memName;?></li>
            <?php }?>
        </ul>
    </div>
</div>