<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-5-3
 * Time: 上午10:17
 */
?>
<ul class="resultList clearfix">
    <?php foreach($examResult->data->examList as $v){?>
        <li examID="<?=$v->examID?>"><?=$v->examName?></li>
    <?php }?>
</ul>

    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(

            'updateId' => '.examList',
           'pagination'=>$pages,
            'maxButtonCount' => 5
        )
    );
    ?>
<script>
//    搜索按钮搜索考试
   $(".searchBtn ").click(function(){
       classID="<?=app()->request->getParam('classID')?>";
       examName=$("#searchText").val();
       $.post("<?=url('teacher/count/get-exam-list')?>",{classID:classID,examName:examName},function(result){
           $(".examList").html(result);
       });
   })
</script>
