<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-20
 * Time: 下午2:17
 */
/* @var $this yii\web\View */  /* @var $this yii\web\View */  $this->title="学生设置-收藏列表";
?>
<div class="currentRight grid_16 push_2 collection_div">
    <h3>我收藏的讲义/我收藏的视频</h3>
    <hr/>
    <ul class="collect_list clearfix">
        <?php foreach($model as $key=>$item){
               if($item->favoriteType==3){?>

                           <li class="vo_list"><img src="<?php echo publicResources();?>/images/video.png" alt="语文视频图片" class="data_vo_img"/>
                               <h4>[<i>视频</i>]<?php echo $item->headLine;?></h4>
                               <p>简介：<?php echo cut_str($item->brief,60);?></p>
                               <a class="a_button btn20 bg_blue playBtn" href="<?php echo url('student/collection/video-detail',array('id'=>$item->favoriteId));?>">观看</a>
                                <button class="btn20 bg_gray collectBtn" type="button" collectID="<?php echo $item->collectID;?>">取消收藏</button>
                           </li>
             <?php  }else{?>
                   <li><img src="<?php echo publicResources();?>/images/iocPic2.png" alt="语文视频图片" />
                       <h4>[<i>讲义</i>]<a href="<?php echo url('student/collection/detail',array('id'=>$item->favoriteId))?>"><?php echo $item->headLine;?></a></h4>
                       <p>简介:<?php echo cut_str($item->brief,60);?></p>
                            <a class="a_button btn20 bg_blue lookBtn" href="<?php echo url('student/collection/detail',array('id'=>$item->favoriteId));?>"> 预览</a>
                           <button class="btn20 bg_gray collectBtn" type="button" collectID="<?php echo $item->collectID;?>">取消收藏</button>
                   </li>
      <?php     }    } ?>

    </ul>

      <?php
         echo \frontend\components\CLinkPagerExt::widget( array(
                'pagination' => $pages,
                'maxButtonCount' => 5
            )
        );
        ?>
</div>
<!--主体内容结束-->
<script type="text/javascript">
$('.collectBtn').bind('click',function(){
    $this = $(this);
    var collectID=$this.attr("collectID");
    $.post("<?php echo url("student/collection/del-collection")?>", {collectID: collectID}, function (data) {
        if (data.success) {
                location.reload();
        }else{
            popBox.alertBox(data.message);
        }
    });
})
</script>