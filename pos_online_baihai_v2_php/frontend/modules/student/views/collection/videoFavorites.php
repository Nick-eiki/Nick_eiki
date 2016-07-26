<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-11-13
 * Time: 上午11:36
 */
/* @var $this yii\web\View */  /* @var $this yii\web\View */  $this->title="学生-收藏列表--视频";
?>

<!--主体内容开始-->

    <div class="currentRight grid_16 push_2 collection_div">
        <h3>我收藏的视频</h3>
        <hr/>
        <ul class="collect_list clearfix">
            <?php foreach($model as $key=>$item){
                ?>
                <li class="vo_list"><img src="<?php echo publicResources();?>/images/video.png" alt="语文视频图片" class="data_vo_img"/>
                    <h4>[<i>视频</i>]<a href="<?php echo url('student/collection/video-detail',array('id'=>$item->favoriteId))?>"><?php echo $item->headLine;?></a></h4>
                    <p>简介：<?php echo cut_str($item->brief,100);?></p>
                    <a class="a_button bg_blue playBtn" href="<?php echo url('student/collection/video-detail',array('id'=>$item->favoriteId))?>">观看</a>
                    <button class="bg_gray collectBtn" type="button" collectID="<?php echo $item->collectID;?>">取消收藏</button>
                </li>
           <?php }?>


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
        $.post("<?php echo url("student/collection/delcollection")?>", {collectID: collectID}, function (data) {
            if (data.success) {
                location.reload();
            }else{
                popBox.alertBox(data.message);
            }
        });
    })
</script>

