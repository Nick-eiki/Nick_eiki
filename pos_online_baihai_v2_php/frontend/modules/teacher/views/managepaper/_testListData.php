<div class="test_list" id="srchResult">
    <?php foreach($data as $v):?>
        <ul>
            <?php $detailsUrl=$v->getType?"organize-details":"view-test"?>
            <li><h4><a href="<?php echo url('teacher/managepaper/'.$detailsUrl, array('testId' => $v->id))?>"><?php echo $v->testName?></a></h4></li>
            <li>试卷名称：<span><?php echo $v->paperName?></span></li>
            <li>考试时间：<span><?php echo $v->testTime?></span></li>
        </ul>
    <?php endforeach;?>
    <?php echo empty($data)?'没有数据':''?>
        <?php
         echo \frontend\components\CLinkPagerExt::widget( array(
               'pagination'=>$pages,
                'maxButtonCount' => 5
            )
        );
        ?>
</div>