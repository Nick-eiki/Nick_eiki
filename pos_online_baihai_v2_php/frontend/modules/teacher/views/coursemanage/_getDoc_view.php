<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-11-5
 * Time: 上午11:53
 */
?>
<ul>
    <?php foreach($model as $key=>$item):  ?>
        <li handout ='<?php echo $item->id;?>'>讲义名称：<?php echo $item->name;?></li>
    <?php endforeach ?>
    <?php if(empty($model)):  ?>
        没有数据,请公文包中新建讲义，<a href="<?php echo url('/teacher/briefcase/briefcase-list'); ?>" target="_blank">点击这里，上传讲义</a>
    <?php endif; ?>

</ul>
    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(
           'pagination'=>$pages,
            'updateId' => '#updatehandout',
            'maxButtonCount' => 4
        )
    );
    ?>
