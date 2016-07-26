<?php foreach($model as $key=>$item){

    ?>
    <?php if($studentId ==user()->id){ ?>
    <li>
        <img style="width: 70px;height: 70px;" src="<?php echo publicResources().$item->url; ?>">
        <h5>
            <?php if($item->favoriteType==1){?>
                <a href="<?php echo url('student/collection/detail',array('id'=>$item->favoriteId));?>"><em>
                        [教案]</em><?php echo $item->headLine;?></a>
            <?php   }elseif($item->favoriteType==2){?>
                <a href="<?php echo url('student/collection/detail',array('id'=>$item->favoriteId));?>"><em>[讲义]</em><?php echo $item->headLine;?></a>
            <?php   }elseif($item->favoriteType==3){?>

                <a href="<?php echo url('student/collection/video-detail',array('id'=>$item->favoriteId));?>"><em>
                        [视频]</em><?php echo $item->headLine;?></a>
            <?php  } ?>
        </h5>
        <h6>简介:</h6>
        <p>
            <?php echo cut_str($item->brief,60); ?>
        </p>
        <p>
            <?php if($item->favoriteType ==3){ ?>
                <a class="a_button bg_blue" href="<?php echo url('student/collection/video-detail',array('id'=>$item->favoriteId));?>">观看</a>
            <?php    }else{ ?>
                <a class="a_button bg_gree" href="<?php echo url('student/collection/detail',array('id'=>$item->favoriteId));?>">预览</a>
            <?php      }?>
            <button type="button" collectID="<?php echo $item->collectID;?>" class="bg_gray collectBtn delCollectBtn"> 取消收藏</button>
        </p>
    </li>
<?php    }else{ ?>
            <li>
                <img style="width: 70px;height: 70px;" src="<?php echo publicResources() . $item->url; ?>">
                <h5><a href="<?php echo url('student/default/detail', array('id' => $item->favoriteId,'studentId'=>$studentId)); ?>"><em>
                            [视频]</em><?php echo $item->headLine; ?></a>
                </h5>
                <h6>简介:</h6>

                <p><?php echo cut_str($item->brief,60); ?></p>

                <p>
                    <a  class="a_button bg_blue" href="<?php echo url('student/default/video-detail', array('id' => $item->favoriteId,'studentId'=>$studentId)); ?>">观看</a>
                    <?php
                    if ($item->isCollected == 1) {
                        ?>
                        <button class="bg_gray collectBtn" type="button" collectID="<?php echo $item->favoriteId; ?>" typeId="<?php echo $item->favoriteType; ?>" action="0"> 取消收藏</button>
                    <?php } else { ?>
                        <button class="bg_orenge collectBtn" type="button" collectID="<?php echo $item->favoriteId; ?>" action="1" typeId="<?php echo $item->favoriteType; ?>">收藏</button>
                    <?php } ?>
                </p>
            </li>
 <?php   } } ?>






























