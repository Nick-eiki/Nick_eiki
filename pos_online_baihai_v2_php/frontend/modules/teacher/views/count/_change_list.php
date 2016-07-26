<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-5-3
 * Time: 上午10:52
 */
?>
<div  style="text-align: right">学科及总成绩排名</div>
<table ellpadding="0" cellspacing="0">

<thead>

<tr>
    <th>学号</th>
    <th>姓名</th>
    <?php foreach($examResult->listHead as $v){?>
        <?php if($subjectID==$v->subjectId){?>
            <th><?=$v->subjectName?><em class="<?=$ascDesc==1?'em_ico up':'em_ico down'?>" subjectID="<?=$v->subjectId?>" ></em></th>
            <?php }else{?>
    <th><?=$v->subjectName?><em class="em_ico " subjectID="<?=$v->subjectId?>" ></em></th>
    <?php  } }?>
    <?php if($subjectID=="all"||$subjectID==null){?>
    <th>成绩<em class="<?=$ascDesc==1?'em_ico up':'em_ico down'?> " subjectID="all"></em></th>
    <?php }else{?>
        <th>成绩<em class="em_ico" subjectID="all"></em></th>
    <?php }?>
    <?php if($subjectID=="ranking"){?>
    <th>排名变化<em  class="<?=$ascDesc==1?'em_ico up':'em_ico down'?>" subjectID="ranking"></em></th>
    <?php }else{?>
        <th>排名变化<em  class="em_ico" subjectID="ranking"></em></th>
    <?php }?>
</tr>
</thead>
<tbody>
<?php foreach($examResult->list as $key=>$value){?>
<tr>
    <td>

        <label ><?=$value->stuID?></label>
    </td>
    <td><?=$value->userName?></td>
    <?php $subjectArray=(array)$value;
      $size=count($subjectArray);
    $lev=0;
    foreach ($subjectArray as $k => $v) {
        $lev++;
    if ($lev > 4 && $lev < $size) {
        ?>
        <td><?=$subjectArray["$k"]?></td>
  <?php } }
    ?>
    <td><?=$value->totalRank?></td>
    <td><?=$value->rankChange>0?'+'.$value->rankChange:$value->rankChange?></td>
</tr>
<?php }?>
</tbody>
    </table>
<script>
    /*升序降序箭头切换*/
    /*$('.em_ico').toggle(function () {
        $(this).parents('th').siblings().children('.em_ico').removeClass('up');
        $(this).parents('th').siblings().children('.em_ico').removeClass('down');
        $(this).addClass('up');
        $(this).removeClass('down');
    }, function () {
        $(this).addClass('down');
        $(this).removeClass('up');
    });*/


//    $('.em_ico').live('click',function(){
//        var _this=$(this);
//        if(_this.hasClass("up")){
//            $(this).removeClass('up');
//            $(this).addClass('down');
//        }else{
//            $(this).removeClass('down');
//            $(this).addClass('up');
//        }
////       $(this).addClass('up')
//
//    })







    $(".score_list .em_ico").click(function(){
        subjuectID=$(this).attr("subjectId");
        if($(this).hasClass("up")){
            $(this).removeClass('up');
            $(this).addClass('down');

            ascDesc=2;
        }else{
            $(this).removeClass('down');
            $(this).addClass('up');
            ascDesc=1;
        }
        examID="<?=$examID?>";
        $.post("<?=url('teacher/count/get-change-list')?>",{
            subjectID:subjuectID,
            ascDesc:ascDesc,
            examID:examID
        },function(result){
            $(".score_list").html(result);
        });
    })

</script>