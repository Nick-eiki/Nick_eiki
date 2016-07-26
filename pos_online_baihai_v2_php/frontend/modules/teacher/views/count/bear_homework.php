<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-4-29
 * Time: 下午4:49
 */
?>
<thead>
<tr>
    <th width="143" >学号</th>
    <th width="143">姓名</th>
    <?php foreach ($homeworkResult->listHead as $v) { if($v->subjectId==$subject){?>
        <th width="143"><?= $v->subjectName ?><em class="<?=$orderBy==1?'em_ico up':'em_ico down'?> " subjectID="<?= $v->subjectId ?>"></em></th>
        <?php }else{?>
        <th><?= $v->subjectName ?><em class="em_ico " subjectID="<?= $v->subjectId ?>"></em></th>
    <?php }?>
    <?php } if($subject==""){ ?>
    <th width="143">总次数<em class="<?=$orderBy==1?'em_ico up':'em_ico down'?> " subjectID="all"></em></th>
    <?php }else{?>
        <th width="143">总次数<em class="em_ico " subjectID="all"></em></th>
    <?php }?>
</tr>
</thead>
<tbody >
<?php $i=1;foreach ($homeworkResult->list as $v) {
    $i++;
    ?>
    <tr height="40">
        <td  width="143">
                    <span>
                        <?php if($endTime==date("Y-m-d",time())) {
                            echo $v->stuID;
                        }else{?>
                            <input id="<?=$i?>" type="checkbox" class="hide">
                            <label for="<?=$i?>" class="chkLabel " studentID="<?=$v->userID?>"><?= $v->stuID ?></label>
                        <?php }?>

                    </span>

        </td>
        <td  width="143"><?= $v->userName ?></td>
        <?php $subjectArray = (array)$v;
        $size = count((array)$v); ?>
        <?php $lev = 0;
        foreach ($subjectArray as $key => $value) {
            $lev++;
            if ($lev > 3 && $lev < $size) {
                ?>
                <td  width="143"><?= $subjectArray["$key"] ?></td>
            <?php }
        } ?>
        <td  width="143"><?= $v->sumCnt ?></td>
    </tr>
<?php } ?>
</tbody>
<script>
    $(".homeworkResult .em_ico").click(function () {
        classID="<?=$classID?>";
        $this=$(this);
        $(this).hasClass("down");
        if ($(this).hasClass("up")) {
            $(this).removeClass('up');
            $(this).addClass('down');
            orderBy = 2;
        } else {
            $(this).removeClass('down');
            $(this).addClass('up');
            orderBy = 1;
        }
        subjectID = $(this).attr("subjectID");
        beginTime=$(this).parents(".rattancent_list").find(".fr").attr("beginTime");
        endTime=$(this).parents(".rattancent_list").find(".fr").attr("endTime");
        $.post("<?=url('teacher/count/get-homw-list')?>", {classID:classID,subjectID: subjectID, orderBy: orderBy,beginTime:beginTime,endTime:endTime}, function (result) {
            $this.parents(".rattancent_list").find(".unfinished_list").html(result);

        })
    });
    //全选
    $('#chkAllA').newCheckAll($('.tableA input:checkbox'));
    $('#chkAllB').newCheckAll($('.tableB input:checkbox'));
    $('#chkAllB').click(function () {//全选按钮判断"评价他们按钮"
        if ($(this).attr('checked') == 'checked') {
            $('#evaluateBtn').removeClass('disableBtn');
        }
        else {
            $('#evaluateBtn').addClass('disableBtn');
//            $('#evaluateBtn').unbind('click');
        }
    });
    $('.tableB input:checkbox').click(function () {//单选按钮判断"评价他们按钮"
        var chked = $('.tableB input:checkbox[checked="checked"]').not('[disabled]').size();
        if ($(this).attr('checked') == 'checked' || chked > 0) {
            $('#evaluateBtn').removeClass('disableBtn');
        }
        else {
            $('#evaluateBtn').addClass('disableBtn');
//            $('#evaluateBtn').unbind('click');
        }
    });
</script>
