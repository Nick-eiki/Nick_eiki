<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-11-13
 * Time: 下午3:13
 */
/* @var $this yii\web\View */  $this->title='教师-收藏列表--视频';
?>

<!--主体内容开始-->

    <div class="currentRight grid_16 push_2 collection_div">
        <h3>我收藏的视频</h3>
        <hr/>
        <ul class="collect_list clearfix">
            <?php foreach($model as $key=>$item){?>
                <li>
                    <img src="<?php echo publicResources();?>/images/video.png" alt="语文视频图片" class="data_vo_img"/>
                    <h4>[<i>视频</i>]<a href="teacher_data_detail1.html"><?php echo $item->headLine;?></a></h4>
                    <p>简介：<?php echo $item->brief;?></p>
                    <a class="a_button bg_blue_l" href="<?php echo url('teacher/collection/video-detail',array('id'=>$item->favoriteId));?>">观看</a><button class="bg_gray collectBtn" type="button" collectID="<?php echo $item->collectID;?>">取消收藏</button>
                    
                </li>
           <?php } ?>
        </ul>
            <?php
             echo \frontend\components\CLinkPagerExt::widget( array(
                   'pagination'=>$pages,
//                    'updateId'=>'#collection',
                    'maxButtonCount' => 5
                )
            );
            ?>
    </div>

<!--主体内容结束-->
<script type="text/javascript">
    $('.collectBtn').bind('click',function(){
        $this = $(this);
        var id=$this.attr('collectID');
        $.post("<?php echo url('teacher/collection/delCollection')?>",{id:id},function(data){
            if(data.success){
                location.reload();
            }else{
                popBox.alertBox(data.message);
            }
        });
    })
</script>