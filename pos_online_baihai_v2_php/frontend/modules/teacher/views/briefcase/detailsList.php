<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-16
 * Time: 下午3:10
 */
/* @var $this yii\web\View */  $this->title="教师-备课-素材包-素材列表";
?>
<script type="text/javascript">
    $(function(){
//点击选择按钮 加上背景
        $('#type li').css('cursor','pointer');
        $('#type li').bind('click',function(){
            $(this).siblings('li').removeClass('ac');
            $(this).addClass('ac');
            var type= $(this).attr('typeId');
            $.post('<?php echo app()->request->url;?>',{type:type},function(data){
                $('#folder').html(data);
            })
        })
    })
</script>


<!--主体内容开始-->

    <div class="currentRight grid_16 push_2">
        <div class="crumbs"><a href="<?php echo url('teacher/message/notice');?>">教师</a> &gt;&gt; <a href="<?php echo url('teacher/briefcase/data-list');?>">素材库</a> &gt;&gt;<a href="#">素材包列表</a></div>
        <div class="noticeH clearfix">
            <h3 class="h3L" title="<?php echo $packName;?>" style="cursor: pointer;"><?php echo cut_str($packName,10);?></h3>
            <div class="fr">
                <a href="<?php echo url('teacher/briefcase/upload-pack',array('id'=>$id))?>" class="B_btn120 uploadDataBtn">添加素材</a>&nbsp;&nbsp;
            </div>
        </div>
        <hr>
        <div id="folder">
        <?php echo $this->render('_folder_list_view',array('model'=>$model, 'pages' => $pages,'id'=>$id));?>
        </div>
    </div>

<!--主体内容结束-->

