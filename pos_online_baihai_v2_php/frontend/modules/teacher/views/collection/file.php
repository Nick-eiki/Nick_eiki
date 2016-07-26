<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-11-13
 * Time: 上午11:48
 */
/* @var $this yii\web\View */  $this->title='教师-收藏列表--文档';

?>

<!--主体内容开始-->

<div class="currentRight grid_16 push_2 collection_div">
    <h3>我收藏的文档</h3>
    <hr/>
    <ul class="collect_list clearfix">
        <?php foreach($model as $key=>$item){
            ?>
            <li><img src="<?php echo publicResources();?>/images/iocPic2.png" alt="语文视频图片" />
                <h4>[<i>文档</i>]<a href="<?php echo url('teacher/collection/lesson-plan-detail',array('id'=>$item->favoriteId));?>"><?php echo $item->headLine;?></a></h4>
                <p>简介：<?php echo $item->brief;?></p>
                <button class="lookBtn" type="button"><a href="<?php echo url('teacher/collection/lesson-plan-detail',array('id'=>$item->favoriteId));?>">预览</a></button>
                <button class="collectBtn" type="button" collectID="<?php echo $item->collectID;?>">取消收藏</button>
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