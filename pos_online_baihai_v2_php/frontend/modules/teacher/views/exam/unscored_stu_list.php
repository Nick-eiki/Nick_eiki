<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 2015/5/7
 * Time: 14:40
 */
?>
<div class="popCont">
    <form class="form_id">
        <table class="test_score" cellpadding="0" cellspacing="0">
            <input type="hidden" class="examSubID" value="<?=$examSubID?>"/>
            <thead>
            <tr>
                <th>学号</th>
                <th>姓名</th>
                <th>录入成绩</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($examResult as $v){?>
            <tr class="unScoredStudentList">
                <td><em><?=$v->stuID?></em></td>
                <td><em><?=$v->trueName?></em></td>
                <input type="hidden" class="studentID" value="<?=$v->studentID?>">
                <td><input type="text" class="text stuSubScore" ></td>
            </tr>
            <?php }?>

            </tbody>
        </table>
    </form>
</div>
<div class="popBtnArea">
    <a href="javascript:" class="okBtn w120 a_button">确定</a>
    <a href="javascript:" class="cancelBtn w120 a_button">取消</a>
</div>
<script>
    fullScore=<?=$fullScore?>;
    $(".okBtn").click(function(){
        var _this=$('.test_score input:text');
//        判断是否至少录入了一个学生成绩
        isHaveScore=false;
        _this.each(function(index,el){
            if($(el).val()!=""){
                isHaveScore=true;

            }
        });
        if(isHaveScore==false){
            return isHaveScore;
        }
        pass=true;
        _this.each(function(index,el){
                if (isNaN($(el).val())) {
                    popBox.errorBox("请填写数字");
                    pass = false;

                }
                else if (parseInt($(el).val()) > parseInt(fullScore)) {
                    popBox.errorBox('请小于' + fullScore);
                    pass = false;

                }
                else if ($(el).val() < 0) {
                    popBox.errorBox('请大于0');
                    pass = false;

                }
        });
        if(pass==false){
            return pass;
        }
        var objArray=[];
        $(".unScoredStudentList").each(function(index,el){
            studentID=$(el).find(".studentID").val();
            stuSubScore=$(el).find(".stuSubScore").val();
            if(stuSubScore!="") {
                var obj = {"studentID": studentID, "stuSubScore": stuSubScore};
                objArray.push(obj);
            }
        });
        examSubID=$(".examSubID").val();
        dataArray={"data":objArray};
        $.post("<?=url('teacher/exam/log-stus-score')?>",{"scoreList":JSON.stringify(dataArray),"examSubID":examSubID},function(result){
            popBox.successBox(result.message);
            location.reload();
        })
    })
</script>