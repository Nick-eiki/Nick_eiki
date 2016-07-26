<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-12-9
 * Time: 上午11:39
 */
?>
<script type="text/javascript">
    $(function(){
        $('.collectBtn').bind('click', function () {
            var $_this = $(this);
            var collectID = $_this.attr('collectID');
            var type =$_this.attr('typeId');
            var action =$_this.attr('action');
            $.post("<?php echo url('student/default/add-collection')?>", {collectID: collectID,type:type,action:action}, function (data) {
                if (data.success) {
                    if(action ==1){
                        $_this.attr('action',0).text('取消收藏');
                    }else{
                        $_this.attr('action',1).text('收藏');
                    }
                } else {
                    popBox.alertBox(data.message);

                }
            });
        });
    })
</script>

<div class=" item Ta_fav">
    <ul class="item_subList">
        <?php foreach($model as $key=>$item){
            ?>
                <li>
                    <img style="width: 70px;height: 70px;" src="<?php echo publicResources().$item->url; ?>">
                    <h5>
                        <a href="<?php echo url('student/default/detail',array('id'=>$item->favoriteId,'studentId'=>$studentId));?>"><em>
                                [视频]</em><?php echo cut_str($item->headLine,12);?></a>
                    </h5>
                    <h6>简介:</h6>
                    <p><?php echo cut_str($item->brief,50); ?></p>
                    <p>
                        <a class="a_button bg_blue" href="<?php echo url('student/default/video-detail',array('id'=>$item->favoriteId,'studentId'=>$studentId));?>">观看</a>
                        <?php
                        if($item->isCollected==1 ){ ?>
                            <button class="bg_gray collectBtn" type="button" action="0" collectID="<?php echo $item->favoriteId;?>" typeId="<?php echo $item->favoriteType;?>"> 取消收藏</button>
                        <?php     }else{ ?>
                            <button type="button" action="1" collectID="<?php echo $item->favoriteId;?>" class="bg_orenge collectBtn" typeId="<?php echo $item->favoriteType;?>" >收藏</button>
                        <?php      }?>

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