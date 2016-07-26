<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-4-16
 * Time: 上午11:08
 */
?>
<ul class="resultList clearfix ">

    <?php foreach ($paperResult->list as $v) { ?>
        <li paperID="<?php echo $v->paperId ?>"><?php echo $v->name ?></li>
    <?php } ?>
</ul>
<input type="hidden" class="paperID">


    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(
            'updateId' => '.'.$pages->params["replace"],
           'pagination'=>$pages,
            'maxButtonCount' => 5
        )
    );
    ?>

