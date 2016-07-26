<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-14
 * Time: 下午2:11
 */
?>
<ul class="teachingPlan_list clearfix">
    <?php foreach($teachingList as $key=>$item){?>
        <li><a href="<?php echo url('teacher/teachingplan/details',array('id'=>$item->teachingPlanID))?>">[<i><?php echo $item->gradeName?></i>]<?php echo cut_str($item->planName,26);?>.</a>

            <p>课题描述：<?php echo cut_str($item->brief,150);?></p>
            <button class="changeBtn changeForJs" type="button" teaching="<?php echo $item->teachingPlanID;?>">修改计划</button>
        </li>
    <?php } ?>
</ul>

    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(
           'pagination'=>$pages,
            'updateId' => '#teachingPlan',
            'maxButtonCount' => 5
        )
    );
    ?>
