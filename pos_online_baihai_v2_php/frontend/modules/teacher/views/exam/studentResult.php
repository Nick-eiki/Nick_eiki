<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-10-15
 * Time: 下午5:33
 */
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */  $this->title="学生成绩";
$publicResources = Yii::$app->request->baseUrl;
;
$this->registerJsFile($publicResources . '/js/ztree/jquery.ztree.all-3.5.min.js'.RESOURCES_VER);
$this->registerCssFile($publicResources . '/js/ztree/zTreeStyle/zTreeStyle.css'.RESOURCES_VER);
$this->registerJsFile($publicResources . "/js/jquery.validationEngine-zh_CN.js".RESOURCES_VER);
$this->registerJsFile($publicResources . "/js/jquery.validationEngine.min.js".RESOURCES_VER);
$this->registerJsFile($publicResources . "/js/register.js".RESOURCES_VER);
?>
<div class="currentRight grid_16 push_2 test_details">
    <h3><?php echo $data->examName ?></h3>
    <hr>
    <div class="test_list">
        <h4><?php echo $data->subjectName ?>试卷成绩表</h4>
        <dl class="test_dl clearfix">
            <dt>试卷内容：</dt>
            <?php foreach (explode(",", $data->imageUrls) as $v) { ?>
                <dd><a href="javascript:">
                        <img src="<?php echo publicResources() ?><?php echo $v ?>"
                             width="40px" height="40px" alt=""></a></dd>
            <?php } ?>
        </dl>
        <div class="hidden h_js">
            <table class="test_tab" cellpadding="0" cellspacing="0" width="808">
                <thead>
                <tr class="tr_bj_color">
                    <th width="133">姓名</th>
                    <th width="166">学号</th>
                    <th width="174">成绩</th>
                    <th width="336">他的答卷</th>
                </tr>
                </thead>
                <tbody id="studentList">
                <?php echo $this->render("_student_list", array("examSubID" => $examSubID, "studentAnswer" => $studentAnswer, "minAndMax" => $minAndMax, "evaluationResult" => $evaluationResult)) ?>
                </tbody>

            </table>
        </div>
        <div class="test_btn">
            <span id="zclick">展开↓</span>
            <?php if (!$data->isHaveSummary) { ?>
                <button type="button" class="bg_red_l t_btn_js">填写科目总评</button>
            <?php } else { ?>
                <button type="button" class="bg_green_l look_btn_js">查看科目总评</button>
            <?php } ?>
        </div>


        <div class="test_show popoLook_js hide">
            <div class="test_look_b">
                <em class="midified midified_js">修改</em>

                <div class="look_list">
                    <span class="list_h">最高分：<em><?php echo $minAndMax->data->MaxandMin[0]->MaxPersonalScore ?>
                            分</em></span>
                    <span class="list_h">最低分：<em><?php echo $minAndMax->data->MaxandMin[0]->MinPersonalScore ?>
                            分</em></span>

                </div>
                <div class="look_list">


                    <?php foreach ($scoreSection->data->socreList as $k => $v) {
                        if ($k == 0) {
                            ?>
                            <span class="list_field">分数段： <em><?php echo $v->bottomlimit . "-" . $v->toplimit ?>
                                    分</em><em><?php echo $v->num ?>人</em></span>
                        <?php } else { ?>
                            <span class="list_field"><em><?php echo $v->bottomlimit . "-" . $v->toplimit ?>
                                    分</em><em><?php echo $v->num ?>人</em></span>
                        <?php
                        }
                    } ?>
                </div>
                <div class="test_look_b clearfix">
                    <span class="look_aporia fl">试卷难点：</span>
                    <input type="hidden" value="">
                    <ul class="tree2">
                        <?php $knowledgeArray = explode(",", $evaluationResult->knowledgePoint);
                        foreach ($knowledgeArray as $v) {
                            ?>
                            <li><?php echo KnowledgePointModel::getNamebyId($v) ?></li>

                        <?php } ?>
                    </ul>


                </div>
                <div class="look_list">
                    <span>学习规划：</span>
                    <span><?php echo $evaluationResult->summary ?></span>
                </div>

            </div>
        </div>
    </div>
</div>
<!--填写科目总评-->
<div id="hear" class="popBox hearBox write_popo hide clearfix">
    <div class="hearLeft">
        <h2>章节树</h2>

        <div class="hear_tree">
            <ul id="treeList" class="clearfix ztree"></ul>
        </div>
    </div>
    <div class="impBox">
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
                    <label>最高-最低分：</label>
                </div>
                <div class="formR">
                    <span class="score">最高分：<em><?php echo $minAndMax->data->MaxandMin[0]->MaxPersonalScore ?>
                            分</em></span>
                    <span class="score">最低分：<em><?php echo $minAndMax->data->MaxandMin[0]->MinPersonalScore ?>
                            分</em></span>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label>分数段：</label>
                </div>
                <div class="formR pointarea">
                    <?php foreach ($scoreSection->data->socreList as $k => $v) { ?>
                        <span class="scorex"><?php echo $v->bottomlimit ?>-<?php echo $v->toplimit ?>
                            分<em>共<?php echo $v->num ?>人</em></span>

                    <?php } ?>
                </div>
            </li>
            <form id="evaluation">
                <li>
                    <div class="formL">
                        <label><i></i>试卷难点：</label>
                    </div>
                    <div class="formR">
                        <div id="point_chapt" class="treeParent">

                            <div class="pointArea hide">
                                <input class="hidVal" type="hidden"
                                       value="<?php echo $evaluationResult->knowledgePoint ?>">
                                <h5>已选中知识点:</h5>
                                <ul class="labelList">
                                    <!--<li>语文</li>
                                    <li>造句</li>
                                    <li>形容词</li>
                                    <li>语文</li>
                                    <li>造句</li>
                                    <li>形容词</li>-->
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label><i></i>学习规划：</label>
                    </div>
                    <div class="formR">
                        <textarea id="studyPlan" data-validation-engine="validate[required,maxSize[300]]"></textarea>
                    </div>
                </li>
            </form>
        </ul>
    </div>

</div>
<script type="text/javascript">

    $(function () {

        $('.test_tab i').editPlus({url: '<?php echo url("teacher/exam/update-score")?>', data: ['examSubID', 'studentID']});
        $('#zclick').live('click', function () {
            $('.h_js').css('height', 'auto');
        });
        $('.look_btn_js').toggle(function () {
                $('.popoLook_js').show();
            },
            function () {
                $('.popoLook_js').hide();
            }
        );


        /*填写科目总评*/
        $('.t_btn_js').click(function () {
            $('#hear').dialog({
                autoOpen: false,
                width: 600,
                modal: true,
                title: "填写科目总评",
                resizable: false,
                buttons: [
                    {
                        text: "确定",
                        class: "okBtn",
                        click: function () {
                            if ($('#mySchoolPop .text').val() == 1) {
                                $(this).dialog("close");
                            }
                            else {
                                if ($("#evaluation").validationEngine("validate")) {
                                    var studyPlan = $("#studyPlan").val();
                                    var knowledgePoint = $('#point_chapt .hidVal').val();
                                    var examSubID = "<?php echo $examSubID?>";
                                    $.post("<?php echo url('teacher/exam/subject-evaluation')?>", {studyPlan: studyPlan, knowledgePoint: knowledgePoint, examSubID:examSubID}, function (data) {
                                        if (data.code == 1) {
                                            popBox.successBox(data.message);
                                        location.reload();
                                        } else {
                                            popBox.errorBox(data.message);
                                        }
                                    });
                                    $(this).dialog("close");
                                }

                            }

                        }
                    },
                    {
                        text: "取消",
                        class: "cancelBtn",
                        click: function () {
                            $(this).dialog("close");
                        }
                    }

                ]
            });
            $("#hear").dialog("open");
            //event.preventDefault();
            return false;

        });
//        修改科目总评
        $('.midified_js').click(function () {
            $("#studyPlan").val("<?php echo $evaluationResult->summary?>");
            $('#hear').dialog({
                autoOpen: false,
                width: 600,
                title: "修改科目总评",
                modal: true,
                resizable: false,
                buttons: [
                    {
                        text: "确定",
                        class: "okBtn",
                        click: function () {
                            if ($('#mySchoolPop .text').val() == 1) {
                                $(this).dialog("close");
                            }
                            else {
                                if ($("#evaluation").validationEngine("validate")) {
                                    var studyPlan = $("#studyPlan").val();
                                    var knowledgePoint = $('#point_chapt .hidVal').val();
                                    var examSubID = "<?php echo $examSubID?>";
                                    $.post("<?php echo url('teacher/exam/subjecte-valuation')?>", {studyPlan: studyPlan, knowledgePoint: knowledgePoint, examSubID:examSubID}, function (data) {
                                        if (data.code == 1) {
                                            popBox.successBox(data.message);
                                        location.reload();
                                        } else {
                                            popBox.errorBox(data.message);
                                        }
                                    });
                                    $(this).dialog("close");
                                }

                            }

                        }
                    },
                    {
                        text: "取消",
                        class: "cancelBtn",
                        click: function () {
                            $(this).dialog("close");
                        }
                    }

                ]
            });
            $("#hear").dialog("open");
            //event.preventDefault();
            return false;

        });
//知识树1
        var id_arr = [];//后台要的id
        var setting = {
            check: {enable: true, chkboxType: {"Y": "", "N": ""} },
            data: {simpleData: {    enable: true} },
            callback: {onCheck: zTreeOnCheck},
            view: {showIcon: false, showLine: false}
        };
//
//        var zNodes = [
//            { id: 1, pId: 0, name: "语文" },
//            { id: 11, pId: 1, name: "拼音"},
//            { id: 111, pId: 11, name: "声母"},
//            { id: 112, pId: 11, name: "韵母"},
//            { id: 113, pId: 11, name: "语法"},
//            { id: 12, pId: 1, name: "标点符号"},
//            { id: 13, pId: 1, name: "造句"},
//            { id: 14, pId: 1, name: "语法"},
//        ];
        var zNodes =<?php echo $knowledgePointJson ?>
        <!--        var knowledgePoint="-->
        <?php //echo $evaluationResult->data->knowledgePoint?><!--";-->
        var knowledgePoint = $('#hear #point_chapt .hidVal').val();
        var knowledgePointArray = knowledgePoint.split(",");

        $.each(zNodes, function (index, result) {
            if (knowledgePointArray.indexOf(result.id.toString()) > -1) {
                result["checked"] = true;
            }
        });
        $.fn.zTree.init($("#treeList"), setting, zNodes);
        var treeObj = $.fn.zTree.getZTreeObj("treeList");
        var sNodes = treeObj.getCheckedNodes(true);
        $('#hear #point_chapt .pointArea').show();
        for (var i = 0; i < sNodes.length; i++) {
            $('#hear #point_chapt .labelList').append('<li val="' + sNodes[i].id + '"  index="' + sNodes[i].tId + '">' + sNodes[i].name + '</li>');
        }
        //点击树上的checkbox
        function zTreeOnCheck(event, treeId, treeNode) {
            if (treeNode.checked == true) {
                $('#hear #point_chapt .pointArea').show();
                $('#hear #point_chapt .labelList').append('<li val="' + treeNode.id + '"  index="' + treeNode.tId + '">' + treeNode.name + '</li>');
                var chapter = $('#hear #point_chapt .hidVal').val();
                id_arr = chapter.length == 0 ? [] : chapter.split(",");
                id_arr.push(treeNode.id);
                $('#hear #point_chapt .hidVal').val(id_arr);
                return false;
            }
            else {
                $('#hear #point_chapt .labelList li[index=' + treeNode.tId + ']').remove();
                var chapter = $('#hear #point_chapt .hidVal').val();
                id_arr = chapter.split(",");
                id_arr.remove(treeNode.id)//base.js中定义的arr方法
                $('#hear #point_chapt .hidVal').val(id_arr);
                return false;
            };
        }

//        展开所有的学生
        $("#zclick").click(function () {
            $("#studentList").find("tr").show();
            $(this).hide();
        });
        var size = $("#studentList").find("[type='hidden']").size();
        if (size <= 2) {
            $("#zclick").hide();
        }
    });;


</script>