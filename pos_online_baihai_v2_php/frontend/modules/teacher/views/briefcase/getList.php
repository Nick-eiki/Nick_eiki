<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-17
 * Time: 下午4:03
 */
/* @var $this yii\web\View */  $this->title="教师-备课-公文袋-公文袋列表";
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
                $('#briefcase').html(data);
            })
        })
    })
</script>
<!--主体内容开始-->

<div class="currentRight grid_16 push_2">
    <div class="crumbs"><a href="<?php echo url('teacher/message/notice')?>">教师</a> &gt;&gt; <a href="<?php echo url('teacher/briefcase/briefcase-list')?>">公文包</a> &gt;&gt;<a href="#">公文袋</a></div>
    <div class="noticeH clearfix">
        <h3 class="h3L" title="<?php echo $packName;?>" style="cursor: pointer;"><?php echo cut_str($packName,10)?></h3>
        <ul class='tabList' id="type">
            <li  typeId ="1">教案</li>
            <li  typeId ="2">讲义</li>
            <li  typeId ="7">教学计划</li>
        </ul>
        <div class="fr">
            <a href="<?php echo url('teacher/briefcase/upload-information',array('id'=>$id))?>" class="B_btn120 uploadDataBtn">上传资料</a>&nbsp;&nbsp;
        </div>
    </div>
    <hr>

<div id="briefcase">
    <?php echo $this->render('_briefcase_list_view', array('model' => $model, 'pages' => $pages, 'id' => $id)) ?>
</div>
</div>

<!--主体内容结束-->
