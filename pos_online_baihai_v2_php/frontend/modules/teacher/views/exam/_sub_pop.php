<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-4-25
 * Time: 下午12:22
 */
?>
<i class="arrow"></i>


<div class="form_list">
    <div class="row">
        <div class="formL">
            <label>考试科目</label>
        </div>
        <div class="formR">
            <ul class="multi_resultList testObjList">
            </ul>
        </div>
    </div>
    <div class="row objScore hide">
        <div class="formL">
            <label>科目满分</label>
        </div>
        <div class="formR">
            <ul class="objScoreList">
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="formL">
            <label>考试时间</label>
        </div>
        <div class="formR">
            <input type="text" class="text w150" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',maxDate:'2099'})" value="<?=$examTime ?>" />
            <button type="button" class="bg_blue_l w100 setSubject">确定</button>
            <input type="hidden" value="<?php echo $examID ?>">
            <button class="bg_blue_l w100 cancelAddObjBtn" type="button">取消</button>
        </div>
    </div>
</div>
<script>
    var obj =<?=$allSub?>;
    var save_arr =<?=$curSub?>;
    for (var i = 0; i < obj.length; i++) {
        $('.clsSetupBox .testObjList').append('<li id="' + obj[i].id + '" data-score="' + obj[i].subScore + '">' + obj[i].subjectName + '</li>')
    }

    for (var i = 0; i < save_arr.length; i++) {
        $('.clsSetupBox .testObjList li').each(function (index, element) {
            if ($(this).text() == save_arr[i].subjectName) {
                $(this).addClass('sel_ac').removeClass('ac');
            }
        });
        $(".clsSetupBox .objScore").show();
        if(save_arr[i].isHaveScore==1) {
            $('.clsSetupBox .objScoreList').append('<li data-id="' + save_arr[i].id + '">' + save_arr[i].subjectName + ' <input type="text" class="text w30" disabled value="' + save_arr[i].subScore + '"></li>');
        }else{
            $('.clsSetupBox .objScoreList').append('<li data-id="' + save_arr[i].id + '">' + save_arr[i].subjectName + ' <input type="text" class="text w30"  value="' + save_arr[i].subScore + '"></li>');
        }
    }
    /*添加考试分数*/
    $('.clsSetupBox .testObjList li').click(function () {
        var pa = $(this).parents('.form_list');
        var id = $(this).attr('id');
        if (!$(this).hasClass('ac') && !$(this).hasClass('sel_ac')) {
            var name = $(this).text();
            var Score = $(this).attr('data-score');
            pa.find('.objScore').show();
            pa.find('.objScoreList').append('<li data-id="' + id + '">' + name + ' <input type="text" class="text w30" value="' + Score + '"></li>');
        }
        else if (!$(this).hasClass('ac') && $(this).hasClass('sel_ac')) {
            return false
        }
        else {
            $('.objScoreList li[data-id=' + id + ']').remove();
            if ($('.objScoreList li').size() == 0) $('.objScore').hide();
        }
    });

    //添加完成
    $('.addObjOKBtn').click(function () {
        var pa = $(this).parents('.clsSetupBox');
        pa.hide().find('.testObjList li').each(function (index, element) {
            if ($(this).hasClass('ac')) {
                $(this).addClass('sel_ac').removeClass('ac');
            }
        });

    });
    //        设置科目和时间
    $(".setSubject").click(function () {

        subjectArray = [];
        var isHaveScore = true;
        subjectList = $(this).parents(".form_list").find(".objScoreList").has("li");
        if (subjectList.length > 0) {
            $(this).parents(".form_list").find(".objScoreList").find("li").each(function (index, el) {
                subjecID = $(el).attr("data-id");
                score = $(el).find("input").val();
                if (score == "") {
                    popBox.errorBox("分数不能为空");
                    isHaveScore = false;
                    return false;
                }else if(isNaN(score)){
                    popBox.errorBox("请填写数字");
                    isHaveScore = false;
                }else if(score<=0){
                    popBox.errorBox("分数要大于0");
                    isHaveScore = false;
                }
                subjectArray.push({"subjectID": subjecID, "subScore": parseInt(score)})
            });
        } else {
            popBox.errorBox("请选择科目");
            return false;
        }
        if (isHaveScore == false) {
            return isHaveScore;
        }
        subjectListArray = {"data": subjectArray};
        examTime = $(this).prev("input").val();
        if (examTime == "") {
            popBox.errorBox("考试时间不能为空");
            return false;
        }
        examID = $(this).next("input").val();
        $.post("<?php echo url('teacher/exam/master-set-sub')?>", {
            "examID": examID,
            "examTime": examTime,
            "subjectList": JSON.stringify(subjectListArray)
        }, function (result) {
            popBox.successBox(result.message);
            ajaxreload();
        })

    })

</script>