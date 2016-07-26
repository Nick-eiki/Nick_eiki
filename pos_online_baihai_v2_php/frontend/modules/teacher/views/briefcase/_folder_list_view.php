<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-11-11
 * Time: 上午11:01
 */
use frontend\components\helper\ImagePathHelper;

?>
<div class="docPack pr">
    <ul class="docBagDetail_list clearfix">
        <?php foreach($model as $key=>$item){
            ?>
            <li class="pr"><img src="<?php echo  ImagePathHelper::getPicUrl($item->url) ?>" alt="上传图片" />
                <h4>
                <a href="<?php echo url('teacher/briefcase/material-detail',array('id'=>$item->infoId))?>" class="paper_name"><?php echo $item->name;?></a></h4>
                <p>简介： <?php echo $item->brief;?></p>
                <p>上传时间：<?php echo $item->uploadTime;?></p>
                <p> <a style="display: inline-block; line-height: 30px;" href="<?php echo url('teacher/briefcase/material-detail',array('id'=>$item->infoId));?>" class="viewBtn">预览</a>
                    <a class="a_button bg_blue viewBtn changeBtn" href="<?php echo url('teacher/briefcase/update-material',array('id'=>$id,'infoId'=>$item->infoId));?>">修改</a></p>
            </li>
        <?php }  ?>

    </ul>

        <?php
         echo \frontend\components\CLinkPagerExt::widget( array(
               'pagination'=>$pages,
                'updateId' => '#folder',
                'maxButtonCount' => 5
            )
        );
        ?>

</div>