<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-11-5
 * Time: 上午11:53
 */
?>
<ul>
    <?php foreach($model as $key=>$item){?>
        <li handout ='<?php echo $item->ID;?>'>讲义名称<?php echo $item->name;?></li>
    <?php } ?>

</ul>

    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(
           'pagination'=>$pages,
            'updateId' => '#updatehandout',
            'htmlOptions' => array('class' => 'page minipage'),
            'maxButtonCount' => 2
        )
    );
    ?>

