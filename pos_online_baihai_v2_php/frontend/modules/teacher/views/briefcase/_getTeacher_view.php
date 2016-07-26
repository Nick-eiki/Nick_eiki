<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-11-4
 * Time: 下午3:11
 */
?>
<ul>
    <?php foreach($model as  $key=>$item){?>
        <li teacherId="<?php echo $item->teacherID;?>"><img src="<?php echo publicResources().$item->headImgUrl;?>" width="40" height="40" alt=""/><?php echo $item->teacherName;?></li>
    <?php } ?>

</ul>
    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(
           'pagination'=>$pages,
            'updateId' => '#updateTeacher',
            'htmlOptions' => array('class' => 'page minipage'),
            'maxButtonCount' => 2
        )
    );
    ?>