<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-10-15
 * Time: 下午1:24
 */
/* @var $this yii\web\View */  $this->title="学期总评";
?>
<div class="currentRight grid_16 push_2 term_app">
    <div class="notice manage">
        <div class="noticeH clearfix noticeB">
            <h3 class="h3L">学期总评</h3>
           <?php if($identity){?>
            <div class="new_not fr">
               <?php if($classEvaluate->data->isHaveCEva==0){?>
                <button type="button" class="new_bj btnjs">填写本班总评</button>
                   <?php }else{?><?php ?>
                <button type="button" class="new_bj update">修改本班总评</button>
               <?php    }?>
                <?php }?>
            </div>
        </div>
        <hr>
        <div class="term" style="display:block;">
            <!--此处本班点评内容，只有点击了右上角的本班点评评论后才回显出评论内容。若未点评，点击评论出现评价本班弹出框-->
            <h4><?php echo $minAndMax->data->schoolYear . "年" . $minAndMax->data->semester . $minAndMax->data->examName ?></h4>
            <dl>
                <dt>本班总评：</dt>
                <dd><span class="max title">最高分：<em><?php echo intval($minAndMax->data->MaxandMin[0]->MaxPersonalScore) ?>分</em></span><span
                        class="max">最低分：<em><?php echo intval($minAndMax->data->MaxandMin[0]->MinPersonalScore)?>分</em></span>
                </dd>
                <dd> <?php foreach ($scoreSection->data->socreList as $k => $v) {
                        if ($k == 0) {
                            ?>
                            <span class="fraction title">分数段：<em><?php echo $v->bottomlimit ?>
                                    -<?php echo $v->toplimit ?>分</em><em><?php echo $v->num ?>人</em></span>
                        <?php } else { ?>
                            <span class="fraction"><em><?php echo $v->bottomlimit ?>-<?php echo $v->toplimit ?>
                                    分</em><em><?php echo $v->num ?>人</em></span>
                        <?php
                        }
                    } ?>
                </dd>
                <dd><span class="title">班内学习态度：</span><em> <?php echo $classEvaluate->data->learnSituation ?></em></dd>
                <dd><span class="title">共性问题：</span><em><?php echo $classEvaluate->data->commonPro ?></em></dd>
                <dd><span class="title">改进建议：</span><em><?php echo $classEvaluate->data->improveAdvise ?></em></dd>
            </dl>
        </div>
        <table id="totalTable" width="100%">
            <colgroup>
                <col style="width:30px">
                <col style="width:100px">
                <col style="width:60px">
                <col style="width:60px">
                <col style="width:60px">
                <col style="width:60px">
                <col style="width:60px">
                <col style="width:60px">
                <col style="width:120px">
            </colgroup>
            <thead>
            <tr height="34" bgcolor="#ddd">
                <th><input type="checkbox" id="checkAll"></th>
                <th>学号</th>
                <th>姓名</th>
                <?php foreach ($studentResult->data->examSubList as $v) { ?>
                    <th><?php echo $v->subjectName ?></th>
                <?php } ?>
                <th>总成绩</th>
                <th>本班名次</th>
                <th>操作</th>
                <th>
                    <em class="p_btn hide p_btnjs">批量评价</em>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($studentResult->data->examScoresList as $v) {
                if ($v->isHavePerEvaluate) {
                    ?>

                    <tr>
                        <td><input type="checkbox"></td>
                        <td>123456789</td>
                        <td class="name"><?php echo $v->studentName ?></td>
                        <?php foreach ($v->scoreList as $value) { ?>
                            <td class="cj yuwen"><?php echo empty($value->personalScore) ? 0 : $value->personalScore ?></td>
                        <?php } ?>
                        <td class="zcj"><?php echo $v->totalScore ?></td>
                        <td class="pm"><?php echo $v->ranking ?></td>
                        <?php if($identity){?>
                        <td><span class="write_comt hide"><input type="hidden" value="<?php echo $v->studentID ?>">评价Ta</span><span
                                class="view_comt">查看评价</span>
                        </td>
                        <?php }?>
                        <td></td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>123456789</td>
                        <td class="name"><?php echo $v->studentName ?></td>
                        <?php foreach ($v->scoreList as $value) { ?>
                            <td class="cj yuwen"><?php echo empty($value->personalScore) ? 0 : $value->personalScore ?></td>
                        <?php } ?>
                        <td class="zcj"><?php echo $v->totalScore ?></td>
                        <td class="pm"><?php echo $v->ranking ?></td>
                        <?php if($identity){?>
                        <td><span class="write_comt"><input type="hidden"
                                                            value="<?php echo $v->studentID ?>">评价Ta</span><span
                                class="view_comt hide">查看评价</span></td>
                        <?php }?>
                        <td></td>
                    </tr>
                <?php } ?>
            <?php } ?>

            </tbody>
        </table>
    </div>

</div>
<!--填写本班总评-->
<div id="general" class=" popBox generalBox hide" title="填写本班总评">
    <div class="impBox">
        <form>
            <ul class="form_list">
                <li>
                    <div class="formL">
                        <label>班级：</label>
                    </div>
                    <div class="formR">
                        <div><?php echo $minAndMax->data->className ?></div>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label>考试名称：</label>
                    </div>
                    <div class="formR">
                        <div><?php echo $minAndMax->data->examName ?></div>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label>最高-低分</label>
                    </div>
                    <div class="formR">
                        <div><span class="max">最高分：<i><?php echo $minAndMax->data->MaxandMin[0]->MaxPersonalScore ?>
                                    分</i></span><span
                                class="max">最低分：<i><?php echo $minAndMax->data->MaxandMin[0]->MinPersonalScore ?>
                                    分</i></span></div>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label>分数段：</label>
                    </div>
                    <div class="formR">
                        <div>
                            <?php foreach ($scoreSection->data->socreList as $k => $v) { ?>
                                <span class="max"><?php echo $v->bottomlimit ?>-<?php echo $v->toplimit ?>
                                    <i>共<?php echo $v->num ?>人</i></span>
                            <?php } ?>

                        </div>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label>班内学习状态：</label>
                    </div>
                    <div class="formR">
                        <textarea id="learnSituation"></textarea>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label>共性问题：</label>
                    </div>
                    <div class="formR">
                        <textarea id="commonPro"></textarea>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label>改进建议：</label>
                    </div>
                    <div class="formR">
                        <textarea id="improveAdvise"></textarea>
                    </div>
                </li>
            </ul>
        </form>
    </div>
</div>
<!--修改本班总评-->
<div id="updateGeneral" class="popBox generalBox hide" title="修改本班总评">
    <div class="impBox">
        <form>
            <ul class="form_list">
                <li>
                    <div class="formL">
                        <label>班级：</label>
                    </div>
                    <div class="formR">
                        <div><?php echo $minAndMax->data->className ?></div>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label>考试名称：</label>
                    </div>
                    <div class="formR">
                        <div><?php echo $minAndMax->data->examName ?></div>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label>最高-低分</label>
                    </div>
                    <div class="formR">
                        <div><span class="max">最高分：<i><?php echo $minAndMax->data->MaxandMin[0]->MaxPersonalScore ?>
                                    分</i></span><span
                                class="max">最低分：<i><?php echo $minAndMax->data->MaxandMin[0]->MinPersonalScore ?>
                                    分</i></span></div>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label>分数段：</label>
                    </div>
                    <div class="formR">
                        <div>
                            <?php foreach ($scoreSection->data->socreList as $k => $v) { ?>
                                <span class="max"><?php echo $v->bottomlimit ?>-<?php echo $v->toplimit ?>
                                    <i>共<?php echo $v->num ?>人</i></span>
                            <?php } ?>

                        </div>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label>班内学习状态：</label>
                    </div>
                    <div class="formR">
                        <textarea
                            id="updateLearnSituation"><?php echo $classEvaluate->data->learnSituation ?></textarea>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label>共性问题：</label>
                    </div>
                    <div class="formR">
                        <textarea id="updateCommonPro"><?php echo $classEvaluate->data->commonPro ?></textarea>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label>改进建议：</label>
                    </div>
                    <div class="formR">
                        <textarea id="updateImproveAdvise"><?php echo $classEvaluate->data->improveAdvise ?></textarea>
                    </div>
                </li>
            </ul>
        </form>
    </div>
</div>
<!--单个评价学生-->
<div id="single" class=" popBox generalBox hide" title="评价学生">
    <div class="impBox">
        <form>
            <ul class="form_list">
                <li>
                    <div class="formL">
                        <label>学生：</label>
                    </div>
                    <div class="formR stu_name">

                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label>考试名称：</label>
                    </div>
                    <div class="formR test_name">
                    </div>
                </li>

                <li>
                    <div class="formL">
                        <label>Ta的成绩：</label>
                    </div>
                    <div class="formR">
                        <div class="score">
                        </div>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label>学生名次：</label>
                    </div>
                    <div class="formR ranking">

                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label>评价学生：</label>
                    </div>
                    <div class="formR">
                        <textarea id="singleEvaluate"></textarea>
                    </div>
                </li>

            </ul>
        </form>
    </div>
</div>


<!--批量评价学生-->
<div id="batchBox" class=" popBox batchBox" title="批量评价学生">
    <div class="impBox">
        <form>
            <ul class="form_list">

                <li>
                    <div class="formL">
                        <label>考试名称：</label>
                    </div>
                    <div class="formR test_name">
                    </div>
                </li>


                <li>
                    <div class="formL">
                        <label>学生：</label>
                    </div>
                    <div class="formR">
                        <table>
                            <thead>
                            <th class="tl" width="80">姓名</th>
                            <th class="tr" width="80">总成绩</th>
                            <th class="tr" width="100">名次</th>
                            </thead>
                        </table>
                        <div style=" max-height:150px; *height:150px; width:300px; overflow:auto">
                            <table id="termTable" class="term_tabble">
                                <tbody>
                                <!--<tr height="24">
                                    <td>猪八戒</td>
                                    <td>207分</td>
                                    <td>第1名</td>
                                </tr>
                                <tr height="24">
                                    <td>猪八戒</td>
                                    <td>207分</td>
                                    <td>第1名</td>
                                </tr>-->
                                </tbody>

                            </table>
                        </div>
                    </div>
                </li>
                <li style="margin:10px 0">
                    <div class="formL">
                        <label>评价学生：</label>
                    </div>
                    <div class="formR">
                        <textarea id="batchEvaluate" style="width:300px; height:80px"></textarea>
                    </div>
                </li>

            </ul>
        </form>
    </div>
</div>
<!--评价弹窗-->
<div class="comtBox pop"
     style="width:200px;position:absolute; background:#ddd; z-index:100;border:1px solid #ddd; padding:5px; font-size:12px; line-height:18px">
    内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内内容内容内容
</div>
<script>
$(function () {
    var check_num = 0;//已选择数量  全选/选择2个以上会用到
    var aTr = $('#totalTable tbody tr');
    var aTh = $('#totalTable th');
    var th_num = aTh.size();
    var stu_obj = {
        sn_id: "",//学号
        name: "",//姓名
        yuwen: 0,//语文
        shuxue: 0,//数学
        yingyu: 0,//英语
        zcj: 0, //总成绩
        pm: 0//排名
    };
    $('.test_name').text($('.term h4').text());//弹出框--考试名称

//选择两个以上,显示"批量评价"
    aTr.each(function () {
        var checkbox = $(this).find(':checkbox');
        checkbox.click(function () {
            if ($(this).attr('checked')) {
                check_num++;
            }
            else {
                check_num--
            }
            if (check_num > 1) {
                $('.p_btnjs').show();
            }
            else {
                $('.p_btnjs').hide();
            }
        })
    });

//全选
    $("#checkAll").checkAll($('#totalTable tbody :checkbox'));
    $("#checkAll").click(function () {
        if (this.checked) {
            $('.p_btnjs').show();//显示"批量评价"
            check_num = aTr.length;
        }
        else {
            $('.p_btnjs').hide();//隐藏"批量评价
            check_num = 0;
        }
    });


//填写本班总评
    $('.btnjs').click(function () {
        $('#general').dialog({
            autoOpen: false,
            width: 500,
            modal: true,
            resizable: false,
            buttons: [
                {
                    text: "确定",
                    click: function () {
                        var examID = "<?php echo $examID?>";
                        var learnSituation = $("#learnSituation").val();
                        var commonPro = $("#commonPro").val();
                        var improveAdivise = $("#improveAdvise").val();
                        $.post("<?php echo url('teacher/exam/class-evaluate')?>", {examID: examID, learnSituation: learnSituation, commonPro: commonPro, improveAdvise: improveAdivise}, function (data) {
                            if (data.code) {
                                popBox.successBox(data.message);
                                   location.reload();
                            }
                            else {
                                popBox.errorBox(data.message);
                            }
                        });

                        $(this).dialog("close");
                    }
                },
                {
                    text: "取消",
                    click: function () {
                        $(this).dialog("close");
                    }
                }
            ]
        });
        $("#general").dialog("open");
        return false;
    });
//修改本班总评
    $('.update ').click(function () {
        $('#updateGeneral').dialog({
            autoOpen: false,
            width: 500,
            modal: true,
            resizable: false,
            buttons: [
                {
                    text: "确定",
                    click: function () {
                        var examID = "<?php echo $examID?>";
                        var learnSituation = $("#updateLearnSituation").val();
                        var commonPro = $("#updateCommonPro").val();
                        var improveAdivise = $("#updateImproveAdvise").val();
                        $.post("<?php echo url('teacher/exam/class-evaluate')?>", {examID: examID, learnSituation: learnSituation, commonPro: commonPro, improveAdvise: improveAdivise}, function (data) {
                            if (data.code) {
                                popBox.successBox(data.message);
                                  location.reload();
                            }
                            else {
                                popBox.alertBox(data.message);
                            }
                        });

                        $(this).dialog("close");
                    }
                },
                {
                    text: "取消",
                    click: function () {
                        $(this).dialog("close");
                    }
                }
            ]
        });
        $("#updateGeneral").dialog("open");
        return false;
    });
//单个评价学生(评价Ta/查看评价中的"编辑")
    function edit_comt(btn) {
        var html = "";
        var oTr = btn.parents('tr');
        var aTd = oTr.children('td');
        $('.popBox .stu_name').text(aTd.eq(2).text());//学生姓名;
        for (i = 3; i < th_num - 3; i++) {
            html += aTh.eq(i).text() + ':' + aTd.eq(i).text() + '分&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        $('.popBox .score').empty().append(html);//成绩
        var pm_num = th_num - 3;//排名
        html = aTh.eq(pm_num).text() + ':' + aTd.eq(pm_num).text();
        $('.popBox .ranking').empty().append(html);//排名

        $('#single').dialog({
            autoOpen: false,
            width: 500,
            modal: true,
            title: "评价单个学生",
            resizable: false,
            buttons: [
                {
                    text: "确定",
                    click: function () {
                        var evaluate = $("#singleEvaluate").val();
                        var examID = "<?php echo $examID?>";
                        var studentID = btn.find("input").val();
                        $.post('<?php echo url("teacher/exam/student-evaluate")?>', {studentID: studentID, examID: examID, evaluate: evaluate}, function (data) {
                            if (data.code == 1) {
                                popBox.successBox(data.message);
                                location.reload();
                            }
                            else {
                                popBox.errorBox(data.message);
                            }
                        });
                        $(this).dialog("close");
                    }
                },
                {
                    text: "取消",
                    click: function () {
                        $(this).dialog("close");
                    }
                }
            ]
        });
        $("#single").dialog("open");
        return false;
    }

//    修改学生评价
    function update_comt(btn) {
        var html = "";

        var oTr = btn.parents('tr');
        var aTd = oTr.children('td');
        $('.popBox .stu_name').text(aTd.eq(2).text());//学生姓名;
        for (i = 3; i < th_num - 3; i++) {
            html += aTh.eq(i).text() + ':' + aTd.eq(i).text() + '分&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        $('.popBox .score').empty().append(html);//成绩
        var pm_num = th_num - 3;//排名
        html = aTh.eq(pm_num).text() + ':' + aTd.eq(pm_num).text();
        $('.popBox .ranking').empty().append(html);//排名

        $('#single').dialog({
            autoOpen: false,
            width: 500,
            modal: true,
            title: "修改评价",
            resizable: false,
            buttons: [
                {
                    text: "确定",
                    click: function () {
                        var studentID = btn.prev("span").find("input").val();
                        var evaluate = $("#singleEvaluate").val();
                        var examID = "<?php echo $examID?>";
                        $.post('<?php echo url("teacher/exam/student-evaluate")?>', {studentID: studentID, examID: examID, evaluate: evaluate}, function (data) {
                            if (data.code == 1) {
                                popBox.successBox(data.message);
                                location.reload();
                            }
                            else {
                                popBox.errorBox(data.message);
                            }
                        });
                        $(this).dialog("close");
                    }
                },
                {
                    text: "取消",
                    click: function () {
                        $(this).dialog("close");
                    }
                }
            ]
        });
        $("#single").dialog("open");
        return false;
    }

//点击"评价Ta",单个编辑评价
    $('.write_comt').click(function () {
        edit_comt($(this));
    });


//批量评价
    $('#batchBox').dialog({
        autoOpen: false,
        width: 550,
        modal: true,
        resizable: false,
        buttons: [
            {
                text: "确定",
                click: function () {
                    var evaluate = $("#batchEvaluate").val();
                    var examID = "<?php echo $examID?>";
                    var student = $(this).find("input");
                    var studentArray = [];
                    student.each(function (index, el) {
                        studentArray.push($(el).val());
                    });
                    var studentList = studentArray.join(",");
                    $.post('<?php echo url("teacher/exam/student-evaluate")?>', {studentID: studentList, examID: examID, evaluate: evaluate}, function (data) {
                        if (data.code == 1) {
                            popBox.successBox(data.message);
                            location.reload();
                        }
                        else {
                            popBox.alertBox(data.message);
                        }
                    });
                    $(this).dialog("close");
                }
            },
            {
                text: "取消",
                click: function () {
                    $(this).dialog("close");
                }
            }
        ]
    });
    $('.p_btnjs').click(function () {
        var arr = [];
        var html = "";
        aTr.each(function (index, element) {
            var checkbox = $(this).find(':checkbox');
            if (checkbox.attr('checked')) {
                stu_obj.name = $(this).find('.name').text();
                stu_obj.zcj = $(this).find('.zcj').text();
                stu_obj.pm = $(this).find('.pm').text();
                stu_obj.studentID = $(this).find(".write_comt").find("input").val();

                html += "<tr>";
                html += '<td class="tl" width="80">' + stu_obj.name + '</td>';
                html += '<td class="tr" width="80">' + stu_obj.zcj + '</td>';
                html += '<td class="tr" width="100">' + stu_obj.pm + '</td>';
                html += '<td class="tr" width="100">' + '<input type="hidden" value=' + stu_obj.studentID + '>' + '</td>';
                html += '</tr>';
                arr.push(stu_obj);
            }
        });
        $('#termTable tbody').empty().append(html);
        $("#batchBox").dialog("open");
        return false;
    });


//显示评价
    var ajax_text = "";
    var evaluateContent = "";

    function show_comt(comt_text) {

        $('.view_comt').click(function () {

            var _this = $(this);
            var examID = "<?php echo $examID?>";
            var studentID = _this.prev("span").find("input").val();
            $.post("<?php echo url('teacher/exam/search-evaluate')?>", {examID: examID, studentID: studentID}, function (result) {
                comt_text = result.data;
                evaluateContent = result.data;
                $("#singleEvaluate").val(evaluateContent);
                var top = _this.offset().top + 20;
                var left = _this.offset().left;
                var html = '<span style="text-decoration:underline;color:red; margin-left:10px;cursor:pointer">编辑</span>';
                $('.comtBox').html(comt_text+ html).css({'top': top, 'left': left}).show();
                $('.comtBox span').click(function () {//再次编辑单个评价
                    update_comt(_this);
                })
            });


            return false;
        })

    }

//php调用
    show_comt(ajax_text);
    $('#batchEvaluate').placeholder({"value":"部门名称","color":"#ccc"});
})
</script>