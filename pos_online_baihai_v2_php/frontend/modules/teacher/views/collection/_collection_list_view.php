<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-21
 * Time: 上午9:51
 */
?>
<div class="bookmark_list">
    <?php foreach($model as $key=>$item){
        ?>
        <dl class="docBagDetail_list clearfix">
            <dt class="pic"><img src="<?php echo publicResources();?>/images/iocPic2.png" alt="" /></dt>
            <dd><h4><?php if($item->favoriteType==1){?>
                        <a href="teacher-data-details.html"><em>
                                [教案]</em><?php echo $item->headLine;?></a>
                    <?php   }elseif($item->favoriteType==2){?>
                        <a href="teacher-data-up-video.html"><em>[视频]</em><?php echo $item->headLine;?></a>
                    <?php   }elseif($item->favoriteType==3){?>

                        <a href="teacher-data-details.html"><em>
                                [讲义]</em><?php echo $item->headLine;?></a>
                    <?php  } ?>
                </h4></dd>
            <dd><i>简介：</i><?php echo cut_str($item->brief,100);?></dd>
            <dd> <?php if($item->favoriteType==2){?>
                    <a href="<?php echo url('teacher/collection/video-detail',array('id'=>$item->favoriteId));?>" class="look_btn mini_btn">观看</a>
                <?php  }else{?>
                    <a href="<?php echo url('teacher/collection/lesson-plan-detail',array('id'=>$item->favoriteId));?>" class="viewBtn mini_btn">预览</a>
                <?php  } ?>
                <a class="cancel" style="display: inline-block; line-height: 30px;" collectID="<?php echo $item->collectID;?>">取消收藏</a>
            </dd>
        </dl>
    <?php  }?>
</div>




    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(
           'pagination'=>$pages,
            'updateId'=>'#collection',
            'maxButtonCount' => 5
        )
    );
    ?>
