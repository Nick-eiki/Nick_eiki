<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-11-13
 * Time: 上午11:48
 */
/* @var $this yii\web\View */  $this->title='教师-收藏列表--讲义';

?>

<!--主体内容开始-->

    <div class="currentRight grid_16 push_2 collection_div">
        <h3>我收藏的讲义</h3>
        <hr/>
        <ul class="collect_list clearfix">
            <?php foreach($model as $key=>$item){
                ?>
                <li><img src="<?php echo publicResources();?>/images/iocPic2.png" alt="语文视频图片" />
                    <h4>[<i>讲义</i>]<a href="<?php echo url('teacher/collection/lesson-plan-detail',array('id'=>$item->favoriteId));?>"><?php echo cut_str($item->headLine,20);?></a></h4>
                    <p>简介：<?php echo cut_str($item->brief,30);?></p>
                    <a class="a_button bg_blue_l" href="<?php echo url('teacher/collection/lesson-plan-detail',array('id'=>$item->favoriteId));?>"> 预览</a>
                    <button class="bg_orenge collectBtn" type="button" collectID="<?php echo $item->collectID;?>">取消收藏</button>
                </li>
            <?php  } ?>

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
        $.post("<?php echo url('teacher/collection/del-collection')?>",{id:id},function(data){
            if(data.success){
                location.reload();
            }else{
                popBox.alertBox(data.message);
            }
        });
    })
</script>


