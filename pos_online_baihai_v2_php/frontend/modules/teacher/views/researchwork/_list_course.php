<ul class="teachingPlan_list clearfix">
    <?php foreach($taskCourseList as $key=>$item){ ?>
        <li> <a href="<?php echo url('teacher/researchwork/details',array('id'=>$item->courseID)); ?>">[<i><?php echo $item->gradeName;?></i>]<?php echo cut_str($item->courseName,26);?></a>
            <p>课题描述：<?php echo cut_str($item->brief,85)?></p>

        </li>
   <?php  }?>
</ul>

    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(
           'pagination'=>$pages,
            'updateId' => '#update',
            'maxButtonCount' => 2
        )
    );
    ?>