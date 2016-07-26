<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-11-13
 * Time: 上午11:36
 */
/* @var $this yii\web\View */  /* @var $this yii\web\View */  $this->title="学生-收藏列表--讲义";
?>

<!--主体内容开始-->

    <div class="currentRight grid_16 push_2 collection_div">
        <h3>我收藏的讲义</h3>
        <hr/>
        <ul class="collect_list clearfix">
            <?php foreach($model as $key=>$item){?>
                <li><img src="<?php echo publicResources();?>/images/iocPic2.png" alt="语文视频图片" />
                    <h4>[<i>讲义</i>]<a href="<?php echo url('student/collection/detail',array('id'=>$item->favoriteId))?>"><?php echo $item->headLine;?></a></h4>
                    <p>简介：<?php echo cut_str($item->brief,100);?></p>
                    <a  class="a_button bg_blue"  href="<?php echo url('student/collection/detail',array('id'=>$item->favoriteId));?>">预览</a>
                    <button class="bg_orenge collectBtn" style="padding-left: 5px;width: 65px;" type="button" collectID="<?php echo $item->collectID;?>">取消收藏</button>
                </li>
         <?php    } ?>

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
