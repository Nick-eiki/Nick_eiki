<?php
/**
 *
 * @var RegisterController $this
 * @var #P#M#C\ClassInfoService.searchClassInfoByPage.classList|? $pageList
 * @var \Pagination $pages
 */
?>

<!--<ul class="classesList clearfix">-->
<!--    --><?php //foreach ($memberList as $item) { ?>
<!--        <li>-->
<!--            <label>-->
<!--                <input class="radio" type="radio" name="stuNum" value="--><?php //echo  $item->stuID ?><!--">-->
<!--                --><?php //echo $item->memName ?><!-- <em>--><?php //echo $item->stuID ?><!--</em></label>-->
<!--        </li>-->
<!--    --><?php //} ?>
<!--</ul>-->

<div class="subTitleBar">
    <h5>选择学号</h5>
</div>
<div class="popCont">
    <ul class="resultList stu_numberList clearfix">
        <?php foreach ($memberList as $item) { ?>
            <li id="<?php echo $item->stuID ?>" data_classMem="<?php echo $item->classMemID;?>"><?php echo $item->stuID ?></li>
        <?php } ?>

    </ul>
</div>
<div class="popBtnArea">
    <button type="button" class="okBtn">确定</button>
    <button type="button" class="cancelBtn">取消</button>
</div>