<?php use frontend\components\helper\ImagePathHelper;

foreach($model as $key=>$item){
        if($teacherId ==user()->id){
    ?>
    <li>
        <img src="<?php echo  ImagePathHelper::getPicUrl($item->url) ?>" style="width: 110px;height: 110px;">
            <?php if($item->favoriteType==1){ ?>
                [教案]<?php echo $item->headLine;?>
            <?php  }elseif($item->favoriteType==2){ ?>
                [讲义]<?php echo $item->headLine;?>
            <?php  }elseif($item->favoriteType==3){ ?>
                [视频]<?php echo $item->headLine;?>
            <?php   }?>
        <h6>简介:<span><?php echo strip_tags(cut_str($item->brief,60));?></span></h6>
        <p></p>
        <p><?php if($item->favoriteType ==3){?>
                <a href="<?php echo url('teacher/default/video-detail',array('id'=>$item->favoriteId,'teacherId'=>$teacherId));?>" class="a_button bg_green preview" >观看</a>
            <?php   }else{ ?>
                <a href="<?php echo url('teacher/default/detail',array('id'=>$item->favoriteId,'teacherId'=>$teacherId));?>" class="a_button bg_green preview">预览</a>
            <?php   }?>
            <button class="a_button bg_gray del" type="button" collectID="<?php echo $item->collectID;?>">取消收藏</button>

        </p>
    </li>
<?php  }else{ ?>
            <li>
            <img src="<?php echo  ImagePathHelper::getPicUrl($item->url) ?>" style="width: 70px;height: 70px;">
            <?php if ($item->favoriteType == 1) { ?>
                [教案]<?php echo $item->headLine; ?>
            <?php } elseif ($item->favoriteType == 2) { ?>
                [讲义]<?php echo $item->headLine; ?>
            <?php } elseif ($item->favoriteType == 3) { ?>
                [视频]<?php echo $item->headLine; ?>
            <?php } ?>
            <h6>简介:<span><?php echo strip_tags(cut_str($item->brief,40)); ?></span></h6>

            <p><?php if ($item->favoriteType == 3) { ?>
                    <a href="<?php echo url('teacher/default/video-detail', array('id' => $item->favoriteId, 'teacherId' => $teacherId)); ?>" class="a_button bg_green preview">
                        观看
                    </a>
                <?php } else { ?>
                    <a href="<?php echo url('teacher/default/detail', array('id' => $item->favoriteId, 'teacherId' => $teacherId)); ?>" class="a_button bg_green preview">
                        预览
                    </a>
                <?php } ?>

                <?php if ($item->isCollected == 1) { ?>
                    <button class="bg_orenge other_collection" type="button" action="0" collectID="<?php echo $item->favoriteId; ?>" typeId="<?php echo $item->favoriteType; ?>">取消收藏
                    </button>
                <?php } else { ?>
                    <button class="bg_orenge other_collection" action="1" type="button" collectID="<?php echo $item->favoriteId; ?>"
                            typeId="<?php echo $item->favoriteType; ?>">收藏
                    </button>
                <?php } ?>

            </p>
        </li>



<?php }  } ?>


<script type="text/javascript">
    $(function(){
        $('.other_collection').bind('click', function () {
            var $_this = $(this);
            var id = $_this.attr('collectID');
            var type =$_this.attr('typeId');
            var action = $_this.attr('action');
            $.post("<?php echo url('teacher/default/add-material')?>", {id: id,type:type,action:action}, function (data) {
                if (data.success) {
                    if (action==1){
                        $_this.attr('action',0).text('取消收藏');
                    }
                    else {
                        $_this.attr('action',1).text('收藏');
                    }
                } else {
                    popBox.alertBox(data.message);

                }
            });
        });
    })

</script>