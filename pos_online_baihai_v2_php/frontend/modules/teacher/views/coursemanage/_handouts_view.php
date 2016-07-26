<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/12/4
 * Time: 17:59
 * 课程直播上传讲义专用
 */

?>
<ul>
    <?php foreach($model as $key=>$item){  ?>
        <li handout ='<?php echo $item->id;?>'>
            讲义名称：<?php echo $item->name;?>
        </li>
    <?php } ?>

</ul>
    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(
           'pagination'=>$pages,
            'updateId' => '#updatehandout',
            'maxButtonCount' => 4
        )
    );
    ?>
