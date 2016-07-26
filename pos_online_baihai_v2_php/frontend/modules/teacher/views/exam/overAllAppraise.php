<?php
/**
 * Created by  wangchunlei
 * User: Administrator
 * Date: 15-4-14
 * Time: 下午8:51
 */
use yii\helpers\Html;
use yii\web\View;

$publicResources = Yii::$app->request->baseUrl;
$this->registerJsFile(publicResources_new() . '/js/echarts/echarts.js',array("position"=>View::POS_HEAD));
/* @var $this yii\web\View */  $this->title="班级总评";
?>
<div class="grid_19 main_r">
    <div class="main_cont test_class_overall_appraisal">
        <div class="title">
            <a href="<?= url('teacher/exam/manage', array('classid' => $minAndMax->classID)) ?>"
               class="txtBtn backBtn"></a>
            <h4><?= $minAndMax->examName ?></h4>

            <div class="title_r">
                考试时间:<?= $examResult->examTime ?>
            </div>
        </div>
        <ul class="itemList">
            <li>
                <div class="title item_title noBorder test_achievement">
                    <h4>成绩列表</h4>

                    <div class="course">
                        <a href="javascript:;" class="cour_btn hotWord">录入成绩<i></i></a>

                        <div class="course_box hotWordList hide">
                            <i class="arrow course_box_arrow"></i>
                            <dl class="clearfix">
                                <?php foreach ($studentResult->examSubList as $v) { ?>
                                    <dd examSubID="<?= $v->examSubID ?>"><?= $v->subjectName ?></dd>
                                <?php } ?>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="test_achievement_table">
                    <div class="test_table_wrap">
                        <table class="test_table" cellpadding="0" cellspacing="0">
                            <thead>
                            <tr>
                                <th>学号</th>
                                <th>姓名</th>
                                <?php foreach ($studentResult->examSubList as $v) { ?>
                                    <th><?php echo $v->subjectName ?></th>
                                <?php } ?>
                                <th>总成绩</th>
                                <th>本班名次</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($studentResult->examScoresList as $v) { ?>
                                <tr studentID="<?= $v->studentID ?>">
                                    <td class="id">
                                        <?php if (!$v->isHavePerEvaluate) { ?>
                                            <input type="checkbox" class="hide" id="<?= $v->studentID ?>">
                                            <label class="chkLabel" for="<?= $v->studentID ?>"
                                                   studentID="<?= $v->studentID ?>"><?=$v->stuID?></label>
                                        <?php } else { ?>
                                            <input id="<?= $v->studentID ?>" type="checkbox" class="hide chked" checked
                                                   disabled>
                                            <label class="chkLabel chkedDisable" for=<?= $v->studentID ?>><?=$v->stuID?></label>
                                        <?php } ?>
                                    </td>

                                    <td class="name">
                                        <em><?= $v->studentName ?></em>
                                    </td>
                                    <?php foreach ($v->scoreList as $value) { ?>
                                        <td>
                                            <em><?= $value->stuSubScore ?></em>
                                        </td>
                                    <?php } ?>


                                    <td>
                                        <em><?= $v->totalScore ?></em>
                                    </td>

                                    <td>
                                        <em><?= $v->ranking ?></em>
                                    </td>

                                    <td>
                                        <?php if (!$v->isHavePerEvaluate) { ?>
                                            <em class="appraise_popo" studentID="<?= $v->studentID ?>"><i></i>评价</em>
                                        <?php } else { ?>
                                            <em class="appraise_a "><i class="complete"></i>已评价<i
                                                    class="open_my_evaluate"></i></em>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>

                        </table>
                    </div>
                    <div class="test_achievement_foot">

                                    	<span class="">
                                        	<input type="checkbox" class="hide" id="chkAll">
                                            <label class="chkLabel chkAll" for="chkAll">全选</label>
                                        </span>
                        <?php if ($isAllHavePerEvaluate == false) { ?>
                            <button type="button" class="w120  bg_blue evaluateBtn disableBtn" id="evaluateBtn">评价他们
                            </button>
                        <?php   } else { ?>
                            <button type="button" class="w120  bg_blue evaluateBtn disableBtn allEvaluated">评价他们
                            </button>
                        <?php } ?>
                        <?php if ($isSendAll) { ?>
                            <button type="button" class="w120 bg_blue senAllScore">发送成绩与评价</button>
                        <?php } else { ?>
                            <button type="button" class="w120 bg_blue disableBtn">发送成绩与评价</button>
                        <?php } ?>
                    </div>
                </div>

            </li>
            <li>
                <div class="this_class_initialise">
                    <div class="title item_title noBorder test_achievement">

                        <div class="title_r">

                        </div>
                    </div>
                    <div class="test_class_this">
                        <span>您还没有填写班级总评,现在就</span>
                        <button type="button" class="w160 btn50 bg_green c_Btn">班级总评</button>
                    </div>

                </div>
                <div class="this_class_appraise thappraise  commentCont hide">
                    <div class="title item_title noBorder test_achievement test_revise ">
                        <h4><i></i>本班总评</h4>
                    </div>
                    <div class="form_list form_list_left_justifying no_padding_form_list ">
                        <div class="row">
                            <div class="formL">
                                <label>最高分</label>
                            </div>
                            <div class="formR">
                                <span><?= intval($minAndMax->MaxScore) ?></span>

                            </div>
                        </div>
                        <div class="row">
                            <div class="formL">
                                <label>最低分</label>
                            </div>
                            <div class="formR">
                                <span><?= intval($minAndMax->MinScore) ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="formL">
                                <label>分数段</label>

                            </div>
                            <div class="formR people_number">
                                <?php  $i=20;foreach ($scoreSection->socreList as $v) {
                                     $i=$i+20;
                                    ?>
                                    <span><?= $v->bottomlimit . "至" . $v->toplimit . "&nbsp" . "共" . $v->num . "人" ?>
                                        <i data-score="percent<?=$i?>" title="查看学生名单" bottomLimit="<?=$v->bottomlimit?>" topLimit="<?=$v->toplimit?>"></i>
                                    </span>
                                <?php } ?>
                                <ul class="stu_name_list pop percent60">

                                </ul>
                            </div>
                        </div>
                        <div class="row row_margin_bottom ">
                            <div class="formL">
                                <label>班内学习状态</label>
                            </div>
                            <div class="formR people_text">

                                <span class="ql"></span>
                                <input type="text" class="text big_text learnSituation" style="width:600px"
                                       value="<?= $classEvaResult->learnSituation ?>">
                            </div>
                        </div>
                        <div class="row row_margin_bottom ">
                            <div class="formL">
                                <label>共性问题</label>
                            </div>
                            <div class="formR people_question">
                                <span class="ql"></span>
                                <input type="text" class="text big_question commonPro" style="width:600px"
                                       value="<?= $classEvaResult->commonPro ?>">

                            </div>
                        </div>
                        <div class="row row_margin_bottom ">
                            <div class="formL">
                                <label>改进建议</label>
                            </div>
                            <div class="formR people_textarea">
                                <span class="ql"></span>
                                <textarea class="textarea improveAdvise improve_question"
                                          style="width:600px"><?= $classEvaResult->improveAdvise ?></textarea>

                            </div>
                        </div>
                        <div class="row class_btn" style="padding-top:20px;">
                            <div class="formL">
                                <label style="width:85px;"></label>
                            </div>
                            <div class="formR">
                                <button type="button" class="w140 btn40 bg_blue commentEditBtn hide">编辑</button>
                                     	<span class="testList_sub">
                                            <input type="submit" class="w140 test_btn test_b sub okBtn " value="确定">
                                            <button type="button" class="w140 test_btn cancel cancelBtn">取消</button>
                                        </span>
                            </div>
                        </div>
                    </div>

                </div>
            </li>
            <li class="test_statisticsLi">
                <div class="title item_title noBorder">
                    <hr class="dashde">
                    <h4>统计数据分析</h4>
                    <div class="title_r">
                        <a class="blue set_custom_score" href="javascript:;">自定义查询</a>
                    </div>
                </div>
                <div class="test_statistics">
                    <div class="image">
                        <div id="echarts04" class="echarts"></div>
                        <div id="echarts01"></div>
                    </div>
                    <div id="slider-range" class="custom_score_slider hide" style=" width:500px; margin:0 auto"></div>
                </div>

            </li>

        </ul>


    </div>
</div>
<!--单个评价学生-->
<div class="popBox appraiseStu hide" title="评价学生" id="appraiseStu">
    <!--<div class="subTitleBar">
        <h5>选择教研组</h5>
    </div>-->
    <input type="hidden" class="studentID"/>

    <div class="popCont">
        <div class="new_tch_group">
            <form>
                <div class="form_list">
                    <div class="row">
                        <div class="formL">
                            <label>学生姓名：</label>
                        </div>
                        <div class="formR">
                            <input type="text" class="text">
                        </div>
                    </div>
                    <div class="row">
                        <div class="formL">
                            <label>考试名称：</label>
                        </div>
                        <div class="formR">
                            <span>考试名称考试名称考试名称考试名称</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="formL">
                            <label>Ta的成绩：</label>
                        </div>
                        <div class="formR personal">
                            <span>语文 100</span>
                            <span>数学 80</span>
                            <span>英语 60</span>
                            <span>总分 240</span>

                        </div>
                    </div>
                    <div class="row">
                        <div class="formL">
                            <label>本班名次：</label>
                        </div>
                        <div class="formR">
                            <span>12</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="formL">
                            <label>评价学生：</label>
                        </div>
                        <div class="formR">
                            <textarea class="textarea">例: 本阶段的改变、课堂表现、课间表现、学习缺陷、改进方案等</textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="popBtnArea">
        <button type="button" class="okBtn">确定</button>
        <button type="button" class="cancelBtn">取消</button>
    </div>
</div>


<!--多个评价学生-->
<div id="evaluate_Box" class="popBox evaluate_Box hide" title="评价学生">
    <div class="popCont">
        <form class="form_id">
            <div class="form_list">
                <div class="row">
                    <div class="formL">
                        <label>考试名称：</label>
                    </div>
                    <div class="formR">
                        <span id="pop_testName"><?= $minAndMax->examName ?></span>
                    </div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label>学生：</label>
                    </div>
                    <div class="formR">
                        <table class="students_table addBorder">
                            <thead>
                            <tr>
                                <th>姓名</th>
                                <th>总成绩</th>
                                <th>名次</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div>
                </div>

                <div class="row">
                    <div class="formL">
                        <label>评价学生：</label>
                    </div>
                    <div class="formR">
                        <textarea id="evaluate_textarea" class="textarea" style="width: 533px; max-width: 100%">例: 本阶段的改变、课堂表现、课间表现、学习缺陷、改进方案等</textarea>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="popBtnArea">
        <button type="button" class="okBtn writeEva">确定</button>
        <button type="button" class="cancelBtn">取消</button>
    </div>
</div>
<!--录入成绩-->
<div id="score_Box" class="popBox score_pop hide" title="录入成绩">

</div>
<style type="text/css">
    .popo {
        display: none;
    }

    .echarts {
        width: 100%;
        height: 300px;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #ccc
    }
</style>
<script>
    path = '<?php echo publicResources_new() ?>';
    // 路径配置
    require.config({
        paths: {echarts: path + '/js/echarts'}
    });

    //饼图
    var opts = {
        option2: {
            title: {
                text: '本班成绩分布',
                subtext: '',
                x: 'center'
            },
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                x: 'left',
                data:<?= $section?>
            },
            toolbox: {
                show: true,
                feature: {
                    mark: {show: false},
                    dataView: {show: false, readOnly: false},
                    magicType: {
                        show: false,
                        type: ['pie', 'funnel'],
                        option: {
                            funnel: {
                                x: '25%',
                                width: '50%',
                                funnelAlign: 'left',
                                max: 1548
                            }
                        }
                    },
                    restore: {show: true},
                    saveAsImage: {show: false}
                }
            },
            calculable: true,
            series: [
                {
                    name: '成绩',
                    type: 'pie',
                    radius: '55%',
                    center: ['50%', '60%'],
                    data:<?=$data?>
                }
            ]
        }


    };


    require(
        [
            'echarts',
            'echarts/chart/pie' // 使用柱状图就加载bar模块，按需加载
        ],
        function (ec) {
            // 基于准备好的dom，初始化echarts图表
            var myChart = ec.init(document.getElementById('echarts04'));


            // 为echarts对象加载数据
            myChart.setOption(opts.option2);
        }
    );


    $(function () {
        /*$('h2').click(function(){
         require(
         [
         'echarts',
         'echarts/chart/pie' // 使用柱状图就加载bar模块，按需加载
         ],
         function (ec) {
         // 基于准备好的dom，初始化echarts图表
         var myChart = ec.init( document.getElementById('echarts04') );
         // 为echarts对象加载数据
         myChart.setOption(opts.option2);
         }
         );

         })*/

//评价学生弹框
        $('.popBox').dialog({
            autoOpen: false,
            width: 700,
            modal: true,
            resizable: false,
            close: function () {
                $(this).dialog("close")
            }
        });

        (function () {
            function studentInfo(tr, self) {//tr单条评价
                var html = '';
                var target = tr || $('.test_achievement_table tbody tr');
                target.each(function (index, element) {
                    var chkbox = $(this).find('input:checkbox');
                    var condition = "";//判断条件
                    if (tr) {
                        condition = chkbox.attr('checked');
                    }
                    else {
                        condition = chkbox.attr('checked') && !chkbox.attr('disabled');
                    }//排除已评价的学生
                    if (self == "self") {
                        condition = chkbox;
                    }
                    if (condition) {
                        var tds = $(this).children('td').size();
                        var _this = $(this);
                        html+='<tr>';
                        for(var i=0; i<tds; i++){
                            (function(index){
                                if(index==1)html+='<td>'+_this.children('td:eq('+index+')').text()+'</td>';
                                if(index==tds-3)html+='<td>'+_this.children('td:eq('+index+')').text()+'分</td>';
                                if(index==tds-2)html+='<td>'+_this.children('td:eq('+index+')').text()+'名</td>';
                            })(i);
                        }
                        html+='</tr>';
                    }
                });
                $('#evaluate_Box').dialog('open');
                $('#evaluate_Box .students_table tbody').empty().append(html);
                $('#evaluate_Box').find('#evaluate_textarea').val("");
                //return html;
            }
//            $('#evaluate_textarea').placeholder({value: '请输入验证码', ie6Top: 10});
            $('#evaluateBtn').not(".disableBtn").click(function () {
                studentInfo();
                var studentArray = [];
                $(".test_table").find(".chkLabel_ac").not(".chkedDisable").each(function (index, el) {
                    if ($(el).attr("studentID") != null) {
                        studentArray.push($(el).attr("studentID"));
                    }
                });

                studentList = studentArray.join(",");
                $(".studentID").val(studentList);
            });


            //全选
            $('#chkAll').newCheckAll($('.test_table input:checkbox'));

            $('#chkAll').click(function () {//全选按钮判断"评价他们按钮"
                if ($(this).attr('checked') == 'checked') {
                    $('#evaluateBtn').removeClass('disableBtn');
                    $('#evaluateBtn').click(function () {
                        studentInfo();
                        var studentArray = [];
                        $(".test_table").find(".chkLabel_ac").not(".chkedDisable").each(function (index, el) {
                            if ($(el).attr("studentID") != null) {
                                studentArray.push($(el).attr("studentID"));
                            }
                        });
                        studentList = studentArray.join(",");
                        $(".studentID").val(studentList);
                    });
                }
                else {
                    $('#evaluateBtn').addClass('disableBtn');
                    $('#evaluateBtn').unbind('click');
                }
            });

            $('.test_table input:checkbox').click(function () {//单选按钮判断"评价他们按钮"
                var chked = $('.test_table input:checkbox[checked="checked"]').not('[disabled]').size();
                if ($(this).attr('checked') == 'checked' || chked > 0) {
                    $('#evaluateBtn').removeClass('disableBtn');
                    $('#evaluateBtn').click(function () {
                        studentInfo();
                        var studentArray = [];
                            $(".test_table").find(".chkLabel_ac").not(".chkedDisable").each(function (index, el) {
                                if ($(el).attr("studentID") != null) {
                                    studentArray.push($(el).attr("studentID"));
                                }
                            });
                            studentList = studentArray.join(",");
                            $(".studentID").val(studentList);

                    });
                }
                else {
                    $('#evaluateBtn').addClass('disableBtn');
                    $('#evaluateBtn').unbind('click');
                }
            });
            $('.modifyComBtn').live('click', function () {
                var pa = $(this).parents('tr').prev('tr');
                studentInfo(pa);
                $('#evaluate_Box').find('.nameList span').css('line-height', '42px');
                $('#evaluate_Box').find('#evaluate_textarea').val($(this).prev('em').text());
                studentID = $(this).parents(".singleComment").prev("tr").attr("studentID");
                $(".studentID").val(studentID);
            });

            $('.appraise_popo').click(function () {
                var pa = $(this).parents('tr');
                studentInfo(pa, "self");
                $('#evaluate_Box').find('.nameList span').css('line-height', '42px');
                studentID = $(this).attr("studentID");
                $(".studentID").val(studentID);
            });

        })();


//显示评论
        $('.appraise_a .open_my_evaluate').toggle(
            function () {
                studentID = $(this).parents("tr").attr("studentID");
                examID =<?=app()->request->getParam("examID")?>;
                var pa = $(this).parents('tr');
                var tds=pa.children('td').size();
                $(this).addClass('close_my_evaluate');
                $.post("<?=url('teacher/exam/show-per-eva')?>", {
                    "examID": examID,
                    "studentID": studentID
                }, function (result) {

                    if (result.data.isSendMsg == 1) {
                        send = ' <a href="javaScript:;" class=" disableBtn transmission" >发送成绩与评价</a></td></tr>';
                    } else {
                        send = ' <a href="javaScript:;" class=" blue transmission" id="transmission">发送成绩与评价</a></td></tr>';
                    }
                    pa.after('<tr class="singleComment"><td class="tl"  align="left"  colspan="'+tds+'"><em>' + result.data + '</em>&nbsp;&nbsp;&nbsp;<span class="blue txtBtn modifyComBtn">修改</span>' + send
                    );
                    //    发送成绩和评价
                    $("#transmission").click(function () {
                        studentID = $(this).parents(".singleComment").prev().attr("studentID");
                        $.post("<?=url('ajaxteacher/send-message')?>", {
                            objectId: examID,
                            messageType: "507202",
                            studentID: studentID
                        }, function (result) {
                            popBox.successBox(result.message);
                        location.reload();
                        })
                    })
                });

            },
            function () {
                var pa = $(this).parents('tr');
                pa.next('tr').remove();
                $(this).removeClass('close_my_evaluate');
            }
        );
//    填写学生评价
        $(".writeEva").click(function () {
            evaluate = $("#evaluate_textarea").val();
            examID =<?=app()->request->getParam("examID")?>;
            studentID = $(".studentID").val();
            if(evaluate!="") {
                $.post("<?=url('teacher/exam/write-per-eva')?>", {
                    "evaluate": evaluate,
                    "examID": examID,
                    "studentID": studentID
                }, function (result) {
                    if (result.success) {
                        popBox.successBox('评价成功');
                        location.reload();
                    } else {
                        popBox.errorBox('请选择学生');
                    }
                })
            }else{
                popBox.errorBox("请填写评价");
            }
        });
        //选择课程
        $('.hotWord').click(function () {
            $('.hotWordList').show();
            return false
        });
        $('.hotWordList').mouseleave(function () {
            $(this).hide()
        });
        //
        $('.hotWordList dd').live('click', function () {
            $('.hotWordList dd').removeClass('ac');
            $(this).addClass('ac');
        });
        //录入成绩
        $('.hotWordList dd').click(function () {
            examSubID = $(this).attr("examSubID");
            $.post("<?=url('teacher/exam/get-unscored-stu')?>", {examSubID: examSubID}, function (result) {
                if (result.success == true) {
                    $result = $(result.data);
                    $result.find('.cancelBtn').click(function () {
                        $("#score_Box").dialog('close');
                    });
                    $("#score_Box").html($result);
                    $("#score_Box").dialog("open");
                } else {
                    popBox.errorBox("当前科目所有学生的成绩已经录入完毕");
                }

            });

        });
    });
    $(function(){
        var isHaveCEva="<?=$classEvaResult->isHaveCEva?>";
        var state=false;	//true:有数据  false:没有数据
        if(isHaveCEva==1){
            var state=true
        }
        var learnSituation="<?=Html::encode($classEvaResult->learnSituation)?>";
        var  commonPro="<?=Html::encode($classEvaResult->commonPro)?>";
        var improveAdvise="<?= Html::encode($classEvaResult->improveAdvise)?>";
        var people_val_arr=[learnSituation,commonPro,improveAdvise];
        if(state==true){
            $('.test_class_this').hide();
            $('.commentCont,.commentEditBtn').show();
            $('.commentCont').find('input:text,textarea,.okBtn,.cancelBtn').hide();
            $('.placeholder').remove();
            $('.people_text .ql').text(people_val_arr[0]);
            $('.people_question .ql').text(people_val_arr[1]);
            $('.people_textarea .ql').text(people_val_arr[2]);
            $('.commentCont .ql').each(function(index, element) {
                if($(this).text()==""){
                    $(this).parents('.row').hide();
                }
            });
        }
        else{
            $('.test_class_this').show();
        }


        //班级总评切换
        $('.c_Btn').bind('click',function(){
            $(this).parents('.this_class_initialise').hide();
            $('.thappraise').show();
            $('.big_text').placeholder({value:'学习近期班内学习状态如何？',ie6Top:2,ie7Top:2, top:8});
            $('.big_question').placeholder({value:'学习风气、氛围、习惯等共性问题',ie6Top:2,ie7Top:2, top:8});
            $('.improve_question').placeholder({value:'在这里写下您的建议，不断完善，做到最好！',ie6Top:2,ie7Top:2, top:19});
        });

        $('.commentCont .cancelBtn').click(function(){
            $('.commentCont .ql').show();
            $('.commentCont input:text, .commentCont textarea').each(function(index, element) {
                var txt=$(this).val();
                if(txt==""){
                    $(this).parents('.row').hide();
                }
            });
            $('.addPoint,.commentCont input:text, .commentCont textarea').hide();
            $('.placeholder').remove();
            $('.commentEditBtn').show();
            $(this).parent().hide();
        });

        $('.commentCont .okBtn').click(function(){
            learnSituation = $(".learnSituation").val();
            commonPro = $(".commonPro").val();
            improveAdvise = $(".improveAdvise").val();
            examID =<?= app()->request->getParam("examID")?>;
            $.post('<?=url("teacher/exam/write-class-eva")?>', {
                "learnSituation": learnSituation,
                "commonPro": commonPro,
                "improveAdvise": improveAdvise,
                "examID": examID
            }, function (result) {
                popBox.successBox(result.message);
//                location.reload();
            });
            $('.commentCont input:text, .commentCont textarea').each(function(index, element) {
                var txt=$(this).val();
                if(txt==""){
                    $(this).prev('span').text(txt).show();
                    $(this).parents('.row').hide();
                }
                else{
                    $(this).prev('.ql').text(txt).show();
                }
                $(this).hide();
            });
            $('.commentEditBtn').show();
            $('.placeholder').remove();
            $('.addPoint').hide();
            $(this).parent().hide();
        });

        $('.commentEditBtn').click(function(){//编辑
            $(this).hide();
            $('.commentCont .okBtn,.commentCont .cancelBtn,.commentCont .row').show();
            $('.commentCont .ql').each(function(index, element) {
                $(this).next('input,textarea').show().val($(this).text());
                $(this).hide();
                $('.addPoint,.commentCont .testList_sub').show();
            });
            $('.big_text').placeholder({value:'学习近期班内学习状态如何？',ie6Top:2,ie7Top:2, top:8});
            $('.big_question').placeholder({value:'学习风气、氛围、习惯等共性问题',ie6Top:2,ie7Top:2, top:8});
            $('.improve_question').placeholder({value:'在这里写下您的建议，不断完善，做到最好！',ie6Top:2,ie7Top:2, top:19});
        });
    });
    //拖动条设定分数
    $('.set_custom_score').toggle(
        function(){
            $( "#slider-range" ).show();
            $(this).text('默认查询')
        },
        function(){
            $( "#slider-range" ).hide();
            $(this).text('自定义查询');
            location.reload();
        }
    );
    var fullScore=<?=$fullScore?>;
    $( "#slider-range" ).slider({
        range: true,
        min: 0,
        max: fullScore,
        values: [0,fullScore],
        slide:function(event,ui) {
            $('#slider-range b:first').text($( "#slider-range" ).slider( "values", 0 ));
            $('#slider-range b:last').text($( "#slider-range" ).slider( "values", 1 ));

        },
        change: function( event, ui ) {
            var arr=[];
            $('#slider-range b:first').text($( "#slider-range" ).slider( "values", 0 ));
            $('#slider-range b:last').text($( "#slider-range" ).slider( "values", 1 ));
            var lowRate=$( "#slider-range" ).slider( "values", 0 );
            var highRate=$( "#slider-range" ).slider( "values", 1 );
            var examID="<?=app()->request->getParam('examID')?>";
            var url="<?=url('teacher/exam/change-class-statics')?>";
            $.post(url,{highRate:highRate,lowRate:lowRate,examID:examID},function(result){
                        $("#echarts01").html(result);
            });

        }
    });
    $('#slider-range span').append('<b></b>');
    $('#slider-range b:first').text($( "#slider-range" ).slider( "values", 0 ));
    $('#slider-range b:last').text($( "#slider-range" ).slider( "values", 1 ));
</script>
<?php $studentList = implode(",", $noSendArray) ?>
<script>
    $(".senAllScore").click(function () {
        studentID = "<?=$studentList?>";
        examID = "<?=app()->request->getParam('examID')?>";
        $.post("<?=url('ajaxteacher/send-message')?>", {
            objectId: examID,
            messageType: "507202",
            studentID: studentID
        }, function (result) {
            popBox.successBox(result.message);
            location.reload();
        })
    });
    //        班内所有学生已经评价的提示
    $(".allEvaluated").click(function () {
        popBox.errorBox("班内所有学生已经评价了");
    });
    //        如果各个分数段都没学生，数据统计不显示
    allPeos = "<?=$superResult->allPeos?>";
    if (allPeos == 0) {
        $(".test_statisticsLi").hide();
    }

    //查看学生名单
    $('.people_number span i').click(function(){
        $this=$(this);
        var bottomLimit=$(this).attr("bottomLimit");
        var topLimit=$(this).attr("topLimit");
        var examID="<?=app()->request->getParam('examID')?>";
        $.post("<?=url('teacher/exam/get-student-list')?>",{bottomLimit:bottomLimit,topLimit:topLimit,examID:examID},function(result){
            if(result.success){
                $(".stu_name_list").html(result.data);
                var data_core=$this.attr('data-score');
                var oUl=$this.parents('.people_number').children('.stu_name_list');
                oUl.show().removeAttr("class");
                oUl.addClass('stu_name_list pop '+data_core);
                return false;
            }else{
                $(".stu_name_list").html("");
                popBox.errorBox(result.message);
            }

        });

    });
//    没有选择学生的时候出提示信息
    $(".evaluateBtn ").click(function(){
        if($(this).hasClass("disableBtn")&&!$(this).hasClass("allEvaluated")) {
            popBox.errorBox("请选择学生");
        }
    })
</script>