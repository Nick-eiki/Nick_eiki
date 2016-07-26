<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-12-8
 * Time: 下午2:11
 */
?>
<div class="docPack pr">
    <ul class="docBagDetail_list clearfix">
        <?php foreach($model as $key=>$item){

            ?>
            <li class="pr"><img src="<?php echo publicResources().$item->url ?>" alt="上传视频图片" />
                        <a href="<?php echo url('teacher/briefcase/detail',array('id'=>$item->infoId))?>" class="paper_name"><?php echo $item->name;?></a>
                    <p>简介： <?php echo $item->brief;?></p>
                    <p>上传时间：<?php echo $item->uploadTime;?></p>
                    <p> <a class="a_button bg_green viewBtn" href="<?php echo url('teacher/briefcase/detail',array('id'=>$item->infoId));?>" >预览</a></p>


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