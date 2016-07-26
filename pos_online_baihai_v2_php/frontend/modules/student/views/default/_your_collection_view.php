<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-12-9
 * Time: 上午11:38
 */
?>
<div class=" item Ta_fav">
    <ul class="item_subList">
        <?php foreach($model as $key=>$item){
            ?>
                <li>
                    <img style="width: 70px;height: 70px;" src="<?php echo publicResources().$item->url; ?>">
                    <h5>
                        <?php if($item->favoriteType==1){?>
                            <a href="<?php echo url('student/collection/detail',array('id'=>$item->favoriteId));?>"><em>
                                    [教案]</em><?php echo cut_str($item->headLine,12);?></a>
                        <?php   }elseif($item->favoriteType==2){?>
                            <a href="<?php echo url('student/collection/detail',array('id'=>$item->favoriteId));?>"><em>[讲义]</em><?php echo cut_str($item->headLine,12);?></a>
                        <?php   }elseif($item->favoriteType==3){?>

                            <a href="<?php echo url('student/collection/video-detail',array('id'=>$item->favoriteId));?>"><em>
                                    [视频]</em><?php echo cut_str($item->headLine,12);?></a>
                        <?php  } ?>
                    </h5>
                    <h6>简介:</h6>
                    <p><?php echo cut_str($item->brief,50); ?></p>
                    <p>
                        <?php if($item->favoriteType ==3){ ?>
                            <a class="a_button bg_blue" href="<?php echo url('student/collection/video-detail',array('id'=>$item->favoriteId));?>">观看</a>
                        <?php    }else{ ?>
                            <a class="a_button bg_blue" href="<?php echo url('student/collection/detail',array('id'=>$item->favoriteId));?>">预览</a>
                        <?php      }?>

                        <button type="button" collectID="<?php echo $item->collectID;?>" class="bg_gray collectBtn del"> 取消收藏</button>
                    </p>
                </li>


        <?php    }?>
    </ul>

        <?php
         echo \frontend\components\CLinkPagerExt::widget( array(
                'pagination' => $pages,
                'updateId'=>'#collection',
                'maxButtonCount' => 5
            )
        );
        ?>


</div>