<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-20
 * Time: 下午2:02
 */
/* @var $this yii\web\View */  $this->title="教师设置-收藏列表";
?>
<div class="centLeft bookmark">
    <div class="item Ta_fav clearfix">
        <h4 class="fl">Ta的收藏</h4>
        <div class="bookmarknav_R fr">
            <label>资料类型：</label>
            <?php
            echo Html:: dropDownList('type','', array('1'=>'教案', '2'=>'讲义', '3'=>'视频'),
                array("prompt" => "请选择")
            );
            ?>
        </div>
    </div>
    <hr>
    <div id="collection">
     <?php  echo $this->render('_collection_list_view', array('model'=>$model,'pages'=>$pages));?>
    </div>

</div>
<div class="centRight">
    <div class="item Ta_teacher">
        <h4>Ta的老师</h4>
        <a class="more" href="#">更多</a>
        <ul class="teacherList">
            <li>
                <img src="<?php echo publicResources();?>/images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="<?php echo publicResources();?>/images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="<?php echo publicResources();?>/images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="<?php echo publicResources();?>/images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="<?php echo publicResources();?>/images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="<?php echo publicResources();?>/images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="<?php echo publicResources();?>/images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="<?php echo publicResources();?>/images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="<?php echo publicResources();?>/images/user_m.jpg">
                张三丰
            </li>
        </ul>

    </div>
</div>
<!--主体内容结束-->

<script type="text/javascript">
    $('.cancel').bind('click',function(){
        $this = $(this);
        var id=$this.attr('collectID');
        $.post("<?php echo url('teacher/collection/del-collection')?>",{id:id},function(data){
            if(data.success){
                location.reload();
            }else{
                popBox.alertBox(data.message);
            }
        });
    });
    $(function(){
    $('#type').change(function(){
        var type = $('#type').val();
        $.post('<?php echo app()->request->url;?>',{type:type},function(data){
            $('#collection').html(data);
        })
    });
    })

</script>