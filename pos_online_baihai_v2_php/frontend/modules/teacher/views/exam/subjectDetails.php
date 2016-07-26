<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-4-14
 * Time: 下午2:03
 */
use yii\helpers\Html;

$publicResources = Yii::$app->request->baseUrl;
;
$this->registerJsFile(publicResources_new() . '/js/echarts/echarts-all.js');
$this->registerJsFile(publicResources_new() . '/js/ztree/jquery.ztree.all-3.5.min.js');
/* @var $this yii\web\View */  $this->title="科目总评";
?>
<div class="grid_19 main_r">
<div class="main_cont test justifying">
<div class="title"><a href="<?= url('teacher/exam/manage', array('classid' => $examResult->classID)) ?>"
                      class="txtBtn backBtn"></a>
    <h4><?php echo $examResult->examName ?></h4>

    <div class="title_r">
        考试时间:<?= $examResult->examTime ?>
    </div>
</div>
<div class="title item_title noBorder statistics_title">
    <h4>统计数据分析</h4>
</div>
<div class="test_statistics">
    <div class="echarts">
        <div id="echarts04" class="echarts"
             style="width:100%; height:300px; margin-bottom:30px; padding-bottom:20px">
        </div>
    </div>
</div>
<hr>
<a id="subjectEvaluate"></a>

<div class="comment">
    <div class="title item_title noBorder">
        <h4>科目总评<em class="amend_ico hide"></em></h4>

    </div>
    <?php
    $isHaveEva = empty($subEvaResult->summary) ? 0 : 1; ?>
    <?php if (!$isHaveEva) { ?>
        <div class="test_class_this">
            <span>您还没有填写本次考试的科目总评,现在就</span>
            <button type="button" class="w160 btn50 bg_green c_Btn">科目总评</button>
        </div>
    <?php } ?>
    <div class="form_list  no_padding_form_list commentCont hide">
        <div class="row">
            <div class="formL">
                <label>班级:</label>
            </div>
            <div class="formR"><?php echo $examResult->className ?> </div>
        </div>
        <div class="row">
            <div class="formL">
                <label>最高分:</label>
            </div>
            <div class="formR"> <?php echo intval($minAndMax->MaxScore) ?> </div>
        </div>
        <div class="row">
            <div class="formL">
                <label>最低分:</label>
            </div>
            <div class="formR"> <?php echo intval($minAndMax->MinScore) ?>  </div>
        </div>
        <div class="row">
            <div class="formL">
                <label>分数段:</label>
            </div>
            <div class="formR  people_number">
                <?php $i=20; foreach ($scoreSection->socreList as $v) {
                    $i=$i+20;
                    ?>
                    <span><?php echo $v->bottomlimit . "至" . $v->toplimit . "共" . $v->num . "人" ?>
                        <i data-score="percent<?=$i?>" title="查看学生名单" bottomLimit="<?=$v->bottomlimit?>" topLimit="<?=$v->toplimit?>"></i>
                        </span>
                <?php } ?>
                <ul class="stu_name_list pop percent60">

                </ul>

            </div>
        </div>
        <div class="row">
            <div class="formL">
                <label>试卷难点:</label>
            </div>
            <div class="formR">
                <span></span>

                <div id="tree_0" class="treeParent">
                    <button class="addPoint">+ 编辑知识点</button>
                    <div class="pointArea hide">
                        <input class="hidVal" type="hidden" value="<?= $subEvaResult->knowledgePoint ?>">
                        <h6>已选中知识点：</h6>
                        <ul class="labelList clearfix">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="formL">
                <label>学习情况:</label>
            </div>
            <div class="formR">
                <span> <?php echo Html::encode($subEvaResult->summary) ?></span><textarea class="studyStatus" style="width:700px"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="formL">
                <label></label>
            </div>
            <div class="formR submitBtnBar">
                <button type="button" class="bg_blue btn40 okBtn">确定</button>
                <button type="button" class="bg_blue_l btn40 cancelBtn">取消</button>
            </div>
        </div>
    </div>
</div>
<br>

<hr>
<a name="score"></a>

<div class="testpaper_and_score">

    <div class="title item_title noBorder">
        <h4>学生答卷与成绩</h4>

        <div class="title_r">
            <a href="javascript:;" class="bg_blue w100 a_button logScore">批量录入成绩</a>
        </div>
    </div>
    <?php if ($answerList->answerlistSize > 0) {   ?>
        <!--        --><?php //if (!$isLogScore) { ?>
        <!--            <div class="test_class_this">-->
        <!--                <span>您还没有录入学生成绩,现在就</span>-->
        <!--                <button type="button" class="w160 btn50 bg_green c_Btn">录入成绩</button>-->
        <!--            </div>-->
        <!--        --><?php //} ?>

        <div
            class="form_list no_padding_form_list  studentList">
            <?php echo  $this->render('_student_answer_list',array('answerList'=>$answerList,'pages'=>$stuPages, "isTheTeacher" => $isTheTeacher,
                "isLogScore"=>$isLogScore,
                "subScore" => $subScore))?>
        </div>
    <?php } ?>
</div>

<br>
<a name="upload"></a>
<?php if (empty($curPaperResult)) { ?>
    <hr>
    <div class="original_testpaper">

        <div class="title item_title noBorder">
            <h4>原始试卷</h4>
        </div>


        <div class="test_class_this">

            <span>您还没有上传原始试卷,现在就</span>
            <?php
            $teacherSubjectID = loginUser()->getModel()->subjectID;
            //            当前考试科目既不是理综也不是文综
            if($examResult->subjectID!="10028"&&$examResult->subjectID!="10027"){?>
                <?php if ($isMaster || $isTheTeacher) { ?>
                    <button type="button" class="w160 btn50 bg_green c_Btn">上传试卷</button>
                <?php } else { ?>
                    <button type="button" class="w160 btn50 bg_green disableBtn notTheTeacher">上传试卷</button>
                <?php } ?>
                <!--                当前考试科目是文综-->
            <?php }elseif($examResult->subjectID=="10028"){
                if($isMaster||($teacherSubjectID=="10016"||$teacherSubjectID=="10017"||$teacherSubjectID=="10018")){?>
                    <button type="button" class="w160 btn50 bg_green c_Btn">上传试卷</button>
                <?php }else{?>
                    <button type="button" class="w160 btn50 bg_green disableBtn notTheTeacher">上传试卷</button>
                <?php }
                ?>
                <!--             当前考试科目是理综-->
            <?php }elseif($examResult->subjectID=="10027"){?>
                <?php if($isMaster||($teacherSubjectID=="10014"||$teacherSubjectID=="10015"||$teacherSubjectID=="10013")){?>
                    <button type="button" class="w160 btn50 bg_green c_Btn">上传试卷</button>
                <?php }else{?>
                    <button type="button" class="w160 btn50 bg_green disableBtn notTheTeacher">上传试卷</button>
                <?php }?>
            <?php }?>
        </div>

        <div class="selectFileTab hide">
            <div class="tab" style="margin-bottom:20px">
                <ul class="tabList clearfix">
                    <li><a href="javascript:;" class="ac">上传试卷</a></li>
                    <li><a href="javascript:;" class="use">使用试卷</a></li>
                </ul>
                <div class="tabCont select_test_tab">
                    <div class="tabItem up_test">
                        <ul class="up_test_list clearfix uploadPaper">

                            <li class="more">
                                <?php
                                $t1 = new frontend\widgets\xupload\models\XUploadForm;
                                /** @var $this BaseController */
                                echo  \frontend\widgets\xupload\XUploadSimple::widget( array(
                                    'url' => Yii::$app->urlManager->createUrl("upload/paper"),
                                    'model' => $t1,
                                    'attribute' => 'file',
                                    'autoUpload' => true,
                                    'multiple' => false,
                                    'options' => array(
                                        "done" => new \yii\web\JsExpression('done')
                                    ,
                                    ),
                                    'htmlOptions' => array(
                                        'id' => 'fileupload',
                                    )
                                ));
                                ?>
                            </li>
                        </ul>
                    </div>
                    <div class="tabItem use_test hide">
                        <div class="subTitleBar">
                            <div class="subTitle_r pr">
                                <input type="text" class="text searchText" id="sclName" value="">
                                <button type="button" class="hideText searchBtn subTitle_Btn">搜索</button>
                            </div>
                            <div class="subTitleBar_box useList">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="bg_blue w120 btn40 sendPaper">确定</button>
            <button type="button" class="bg_blue_l w120 btn40 uploadCancel cancelBtn">取消</button>
        </div>
    </div>
<?php } ?>
<br>
<hr>
<?php if (!empty($curPaperResult)) { ?>
    <div class="choose_original_testpaper ">
        <div class="title item_title noBorder">
            <h4>原始试卷</h4>
        </div>
        <?php if ($curPaperResult->getType == 0) { ?>
            <div class="imgFile">
                <h5><?= $curPaperResult->name ?></h5>

                <ul class="up_test_list clearfix  updatePaper">
                    <?php $imageArray = explode(",", $curPaperResult->imageUrls);
                    foreach ($imageArray as $v) {
                        ?>
                        <li><img src="<?php echo $publicResources . $v ?>" alt=""><span
                                class="delBtn hide"></span></li>
                    <?php } ?>
                    <li class="more hide">       <?php
                        $t1 = new frontend\widgets\xupload\models\XUploadForm;
                        /** @var $this BaseController */
                        echo  \frontend\widgets\xupload\XUploadSimple::widget( array(
                            'url' => Yii::$app->urlManager->createUrl("upload/paper"),
                            'model' => $t1,
                            'attribute' => 'file',
                            'autoUpload' => true,
                            'multiple' => false,
                            'options' => array(
                                "done" => new \yii\web\JsExpression('done')
                            ,
                            ),
                            'htmlOptions' => array(
                                'id' => 'fileupdate',
                            )
                        ));
                        ?>

                    </li>
                </ul>
                <div class="subTitleBar_box hide uploadList">

                </div>
                <a href="<?= url('teacher/exam/paper-preview', array('paperID' => $curPaperResult->paperId)) ?>"
                   class="a_button bg_blue_l w120 btn40 previewBtn">预览试卷</a>
                <?php if (($isMaster || $isTheTeacher) && $answerList->isHaveStudentAnswer ==0) { ?>
                    <button type="button" class="bg_blue_l w120 btn40 modifyBtn">修改试卷</button>
                <?php } elseif(!($isMaster || $isTheTeacher)) { ?>
                    <button type="button" class="bg_blue_l w120 btn40 disableBtn notTheTeacher">修改试卷</button>
                <?php }elseif(($isMaster || $isTheTeacher)&&$answerList->isHaveStudentAnswer !=0){?>
                    <button type="button" class="bg_blue_l w120 btn40 disableBtn nolongerHave">修改试卷</button>
                <?php }?>
                <button type="button" class="bg_blue_l w120 btn40 saveUpdate hide"
                        paperID="<?= $curPaperResult->paperId ?>">确定修改
                </button>

                <?php if (($isMaster || $isTheTeacher) && $answerList->isHaveStudentAnswer== 0) { ?>
                    <button type="button" class="bg_blue_l w120 btn40 changeBtn changeUplPaper">更换试卷</button>
                <?php } elseif(!($isMaster || $isTheTeacher))  { ?>
                    <button type="button" class="bg_blue_l w120 btn40 disableBtn notTheTeacher ">更换试卷</button>
                <?php }elseif(($isMaster || $isTheTeacher)&&$answerList->isHaveStudentAnswer !=0){ ?>
                    <button type="button" class="bg_blue_l w120 btn40 disableBtn nolongerHave ">更换试卷</button>
                <?php }?>

                <button type="button" class="bg_blue w120 btn40 okBtn hide">确定更换</button>
                <button type="button" class="bg_blue_l w120 btn40 cancelBtn hide">取 消</button>
            </div>
        <?php } else { ?>
            <div class="digtalFile">
                <h6>
                    <a href="<?= url('teacher/exam/paper-preview', array('paperID' => $curPaperResult->paperId)) ?>"
                       class="blue_d font16"><?= $curPaperResult->name ?></a></h6>

                <div class="subTitleBar_box hide orgList ">

                </div>
                <br>
                <?php if (($isMaster || $isTheTeacher) && $answerList->answerlistSize ==0) { ?>
                    <button type="button" class="bg_blue_l w120 btn40 modifyBtn">更换试卷</button>
                <?php } elseif(!($isMaster || $isTheTeacher))  { ?>
                    <button type="button" class="bg_blue_l w120 btn40 disableBtn notTheTeacher">更换试卷</button>
                <?php }elseif(($isMaster || $isTheTeacher)&&$answerList->isHaveStudentAnswer !=0){ ?>
                    <button type="button" class="bg_blue_l w120 btn40 disableBtn nolongerHave">更换试卷</button>
                <?php }?>
                <a class="a_button bg_blue_l w120 btn40 previewBtn"
                   href="<?= url('teacher/exam/paper-preview', array('paperID' => $curPaperResult->paperId)) ?>">预览试卷</a>
                <button type="button" class="bg_blue w120 btn40 okBtn hide">确定更换</button>
                <button type="button" class="bg_blue_l w120 btn40 cancelBtn hide">取 消</button>
            </div>
        <?php } ?>


    </div>
<?php } ?>
</div>

</div>
</div>
</div>
</div>
<!--弹窗-->
<div class="popBox ChangetestNameBox hide" title="考试名称">
    <div class="popCont">
        <div class="pr al">
            <p class="warningTxt">由于目前修改的试卷，同时被另外一次考试使用,为了保持数据的一致性与完整性，需要您为本次考试中的相应试卷重新命名：
            </p>
        </div>
        <div class="form_list no_padding_form_list">
            <div class="row">
                <div class="formL">
                    <label class="w80">试卷名称</label>
                </div>
                <div class="formR w310">
                    <input type="text" class="text ChgeNameText">
                </div>
            </div>
        </div>

    </div>
    <div class="popBtnArea">
        <button type="button" class="okBtn">确定</button>
        <button type="button" class="cancelBtn">取消</button>
    </div>
</div>
<!--录入成绩-->
<div id="score_Box" class="popBox score_pop hide" title="录入成绩">

</div>
<script>
$(function () {
//统计图-------------------------------------

    //饼图
    var opts = {
        option1: {
            title: {
                text: '<?=$examResult->subjectName?>成绩公布图',

                x: 'center'
            },
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                x: 'left',
                data:<?php echo $section?>
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
                    data:<?php echo $number?>
                }
            ]
        },
        option2: {
            title: {
                text: '本站满意度调查',
                subtext: '纯属虚构',
                x: 'center'
            },
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                x: 'left',
                data: ['0-59', '60-69', '70-79', '80-89', '90-100']
            },
            toolbox: {
                show: true,
                feature: {
                    mark: {show: true},
                    dataView: {show: true, readOnly: false},
                    magicType: {
                        show: true,
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
                    saveAsImage: {show: true}
                }
            },
            calculable: true,
            series: [
                {
                    name: '成绩',
                    type: 'pie',
                    radius: '55%',
                    center: ['50%', '60%'],
                    data: [
                        {value: 10, name: '0-59'},
                        {value: 20, name: '60-69'},
                        {value: 20, name: '70-79'},
                        {value: 30, name: '80-89'},
                        {value: 20, name: '90-100'}
                    ]
                }
            ]
        }
    };

    var myChart = echarts.init(document.getElementById('echarts04'));
    // 为echarts对象加载数据
    myChart.setOption(opts.option1);


//知识树
    var zNodes =<?php echo $knowledge?>;
    popBox.pointTree2(zNodes, $('.addPoint'));
//    $('.addPoint').die().live('click', function () {
//
//
//    });

//上传试卷----------------------------------------------

//打开编辑区域
    $('.test_class_this .c_Btn').click(function () {
        $(this).parent().hide().next().show();
    });
    $('.test_class_this .disableBtn').click(function () {
        popBox.errorBox("您既不是班主任也不是任课老师，无法上传试卷");
    });


//编辑科目总评
    (function () {
        var state = true;
        $('.commentCont .cancelBtn').click(function () {
            if (state == true) {
                $(this).parents('.commentCont').hide().prev('.test_class_this').show();
            }
            else {
                $('.commentCont input:text, .commentCont textarea').each(function (index, element) {
                    $(this).prev('span').text($(this).val()).show();
                    $(this).hide();
                    $('.amend_ico').show();
                });
                $('.addPoint').hide();
                $(this).parent().hide();
            }
        });

        $('.commentCont .okBtn').click(function () {
            state = false;
            summary = $(".studyStatus").val();
            if (summary == "") {
                popBox.errorBox("请填写学习情况");
                return;
            }
            knowledgePoint = $(".hidVal").val();
            examSubID =<?php echo app()->request->getParam("examSubID")?>;
            $.post("<?php echo url('teacher/exam/write-sub-eva')?>", {
                "summary": summary,
                "knowledgePoint": knowledgePoint,
                "examSubID": examSubID
            }, function (result) {
                popBox.successBox(result.message);
                location.reload();
            });
            $('.commentCont input:text, .commentCont textarea').each(function (index, element) {
                $(this).prev('span').text($(this).val()).show();
                $(this).hide();
                $('.amend_ico').show();
            });
            $('.addPoint').hide();
            $(this).parent().hide();
        });

        $('.amend_ico').click(function () {//编辑
            state = false;
            $('.commentCont span').each(function (index, element) {
                $(this).next('input,textarea').show().val($(this).text());
                $(this).hide();
                $('.addPoint,.commentCont .submitBtnBar').show();
                $(".people_number span").show();
            });
        });
    })();

//修改成绩
    $('.testpaperList .editBtn').click(function () {
        var pa = $(this).parent('.testpaperList');
        var _this = $(this);
        $(this).hide();
        pa.children('.score').hide();
        pa.find('.text').val(pa.children('.score').text());
        pa.children('.inputbar').show();
        pa.find('.text').placeholder({value: "分数"});

        function modify() {
            var subScore = "<?=$subScore?>";
            var score = pa.find('.text').val();

            var studentID = pa.find(".scoreOkBtn").attr("studentID");

            var examSubID = "<?=app()->request->getParam('examSubID')?>";
            if (isNaN(score)) {
                popBox.errorBox("请输入数字");
            }
            else if (score >= 0 && score <= parseInt(subScore)) {
                $.post("<?=url('teacher/exam/log-stu-score')?>", {
                    score: score,
                    studentID: studentID,
                    examSubID: examSubID
                }, function (result) {
                    if (result.success) {
                        location.reload();
                    } else {
                        popBox.errorBox(result.message);
                        return false;
                    }
                });
                _this.show();
                pa.children('.sco   re').show().text(pa.find('.text').val());
                pa.children('.inputbar').hide();
            }
            else if (score > parseInt(subScore)) {
                popBox.errorBox('分数不能超过当前科目最高分');
            } else if (score < 0) {
                popBox.errorBox("分数不能小于0");
            }
        }
        $(document).keyup(function (event) {
            if (event.keyCode == 13)modify()//确定
        });;

        $(document).keyup(function (event) {
            if (event.keyCode == 27) {//esc
                _this.show();
                pa.children('.score').show();
                pa.children('.inputbar').hide();
                pa.children('.text').hide().val("");
            }
        });
        $('.testpaperList .scoreOkBtn').unbind("click").click(function () {
            modify();
        });
    });
//    上传试卷的取消按钮
//    $(".uploadCancel").click(function () {
//        $(this).parents(".selectFileTab").hide().prev().show();
//
//    });
//修改图片试卷
    $('.imgFile .modifyBtn').toggle(
        function () {
            $(".saveUpdate,.imgFile .delBtn,.imgFile .more ,.imgFile .cancelBtn").show();
            $('.previewBtn,.modifyBtn,.changeBtn').hide();
        },
        function () {
            $('.previewBtn,.modifyBtn,.changeBtn').show();
            $(this).removeClass('bg_blue').text('修改试卷');
        }
    );
//    纸质类型的取消按钮
//    $(".imgFile .cancelBtn").click(function () {
//        $(".imgFile .delBtn,.imgFile .more,.saveUpdate,.imgFile .okBtn ,.imgFile .subTitleBar_box").hide();
//        $(this).hide();
//        $('.previewBtn,.modifyBtn,.changeBtn,.imgFile .up_test_list').show();
//    });
    $('.imgFile .changeBtn').toggle(
        function () {
            $('.previewBtn,.modifyBtn ,').hide();
            $('.imgFile .subTitleBar_box,.imgFile .cancelBtn').show();
            $('.imgFile .okBtn').show();
            $(this).hide();
            $('.imgFile .up_test_list').hide();
            //$(this).addClass('bg_blue').text('保存修改');
        },
        function () {
            $('.previewBtn,.modifyBtn').show();
            $('.imgFile .subTitleBar_box').hide();
            $('.imgFile .up_test_list').show();
            $(this).removeClass('bg_blue').text('更换试卷');
        }
    );


//更换电子试卷
    $('.digtalFile .modifyBtn').click(function () {
        $('.digtalFile h6,.digtalFile .modifyBtn,.digtalFile .previewBtn').hide();
        $('.digtalFile .subTitleBar_box,.digtalFile .okBtn,.digtalFile .cancelBtn').show();

    });
    $(".digtalFile .cancelBtn").click(function () {
        $('.digtalFile h6,.digtalFile .modifyBtn,.digtalFile .previewBtn').show();
        $('.digtalFile .subTitleBar_box,.digtalFile .okBtn,.digtalFile .cancelBtn').hide();
    });
    $('.digtalFile .subTitleBar_box li').click(function () {
        var txt = $(this).text();
        $('.digtalFile h6').show();
        $('.digtalFile h6 a').text(txt);
        $('.digtalFile .subTitleBar_box').hide();
    });


});


</script>
<script type="text/javascript">
    done = function (e, data) {
        $.each(data.result, function (index, file) {
            if (file.error) {
                popBox.errorBox(file.error);
                return;
            }
            $('<li><img src="' + file.url + '" alt=""><span class="delBtn"></span></li>').insertBefore($(e.target).parent());
        });
    };

    //    上传试卷和使用试卷
    $(".sendPaper").click(function () {
        examSubID =<?php echo app()->request->getParam("examSubID")?>;
        var paperID = $(".useList").find(".ac").attr("paperID");
        if (paperID == null) {
            srcArray = [];
            uplPaper = $(".uploadPaper").find("img");
            if (uplPaper.size() == 0) {
                popBox.errorBox("请上传试卷");
            } else {
                uplPaper.each(function (index, el) {
                    var url = $(el).attr("src");
                    var obj = {"url": url};
                    srcArray.push(obj);
                });
                var imageUrls = {"images": srcArray};

                $.post("<?php echo url('teacher/exam/upl-and-use-paper')?>", {
                    "imageUrls": JSON.stringify(imageUrls),
                    "examSubID": examSubID
                }, function (result) {
                    if (result.success == true) {
                        location.reload();
                    } else {
                        popBox.errorBox(result.message);
                    }
                });
            }
        }
        if (paperID != null) {
            $.post("<?php echo url('teacher/exam/use-paper')?>", {
                "examSubID": examSubID,
                "paperID": paperID
            }, function (result) {
                if (result.success == true) {
                    location.reload();
                } else {
                    popBox.successBox(result.message);
                }
            })
        }
    });
    //    修改并使用试卷
    $(".imgFile .saveUpdate").click(function () {
        examSubID =<?php echo app()->request->getParam("examSubID")?>;
        paperID = $(this).attr("paperID");
        srcArray = [];
        updPaper = $(".updatePaper").find("img");

        if (updPaper.size() == 0) {
            popBox.errorBox("请上传试卷");
        } else {
            updPaper.each(function (index, el) {
                var url = $(el).attr("src");
                var obj = {"url": url};
                srcArray.push(obj);
            });
            var imageUrls = {"images": srcArray};
            $.post("<?=url('teacher/exam/paper-if-used')?>", {
                "paperID": paperID,
                "examSubID": examSubID
            }, function (result) {
                if (result.code == 0) {
                    $.post("<?php echo url('teacher/exam/upd-and-use-paper')?>", {
                        "imageUrls": JSON.stringify(imageUrls),
                        "examSubID": examSubID,
                        "paperID": paperID
                    }, function (result) {
                        if (result.success == true) {
                            location.reload();
                        } else {
                            popBox.errorBox(result.message);
                        }
                    });
                } else {
                    //考试名称修改
                    $('.ChangetestNameBox').dialog({
                        autoOpen: true,
                        width: 480,
                        modal: true,
                        resizable: false,
                        close: function () {
                            $(this).dialog("close")
                        }
                    });
                    $('.ChangetestNameBox .okBtn').click(function () {
                        name = $(".ChgeNameText").val();
                        $.post("<?=url('teacher/exam/upl-and-use-paper')?>", {
                            "name": name,
                            "imageUrls": JSON.stringify(imageUrls),
                            "examSubID": examSubID
                        }, function (result) {
                            if (result.success == true) {
                                location.reload();
                            } else {
                                popBox.successBox(result.message);
                            }
                        });
                        $(this).parents('.ChangetestNameBox').dialog("close");
                    });

                }
            })
        }
    });
    //       更换上传的试卷
    $(".imgFile .okBtn ").click(function () {
        examSubID =<?php echo app()->request->getParam("examSubID")?>;
        var paperID = $(this).parents(".imgFile").find(".ac").attr("paperID");
        if (paperID == null) {
            popBox.errorBox("请选择试卷");
        } else {
            $.post("<?php echo url('teacher/exam/use-paper')?>", {
                "examSubID": examSubID,
                "paperID": paperID
            }, function (result) {
                if (result.success == true) {
                    location.reload();
                } else {
                    popBox.successBox(result.message);
                }
            })
        }
    });
    //    更换电子试卷
    $(".digtalFile .okBtn ").click(function () {
        examSubID =<?php echo app()->request->getParam("examSubID")?>;
        var paperID = $(this).parents(".digtalFile").find(".ac").attr("paperID");
        if (paperID == null) {
            popBox.errorBox("请选择试卷");
        } else {
            $.post("<?php echo url('teacher/exam/use-paper')?>", {
                "examSubID": examSubID,
                "paperID": paperID
            }, function (result) {
                if (result.success == true) {
                    location.reload();
                } else {
                    popBox.successBox(result.message);
                }


            })
        }
    });
    //    上传试卷的试卷列表的显示
    $(".selectFileTab").find(".use").click(function () {
        examSubID =<?php echo app()->request->getParam("examSubID")?>;
        $.post("<?=url('teacher/exam/subject-details')?>", {
            "replace": "useList",
            "examSubID": examSubID
        }, function (result) {
            $(".useList").html(result);
        })
    });
    //    更改上传试卷试卷列表的显示
    $(".changeUplPaper").click(function () {
        examSubID =<?php echo app()->request->getParam("examSubID")?>;
        $.post("<?=url('teacher/exam/subject-details')?>", {
            "replace": "uploadList",
            "examSubID": examSubID
        }, function (result) {
            $(".uploadList").html(result);
        })
    });
    //    更换电子试卷列表的显示
    $(".digtalFile .modifyBtn").click(function () {
        examSubID =<?php echo app()->request->getParam("examSubID")?>;
        $.post("<?=url('teacher/exam/subject-details')?>", {
            "replace": "orgList",
            "examSubID": examSubID
        }, function (result) {
            $(".orgList").html(result);
        })
    });
    $(".notTheTeacher").click(function () {
        popBox.errorBox("对不起，您没有权限");
    });
    $(".nolongerHave").click(function () {
        popBox.errorBox("对不起，已经有学生提交了答案");
    })


</script>
<script>
    //    根据名字查询试卷
    $(".subTitle_Btn").click(function () {
        name = $("#sclName").val();
        examSubID = "<?=app()->request->getParam('examSubID')?>";
        $.post("<?=url('teacher/exam/subject-details')?>", {
            name: name,
            replace: "useList",
            examSubID: examSubID
        }, function (result) {
            $(".useList").html(result);
        })
    });
    $(".changeState").click(function ()  {
        testAnswerID = $(this).attr("testAnswerID");
        $.post("<?=url('teacher/exam/finish-correct')?>", {testAnswerID: testAnswerID}, function (result) {
            popBox.successBox("批改完成");
            location.reload();
        })
    });
    //    有科目总评的状态
    isHaveEva =<?=$isHaveEva?>;
    if (isHaveEva) {
        $('.commentCont span').each(function (index, element) {
            $('.amend_ico').next('input,textarea').show().val($('.amend_ico').text());
            $('.amend_ico').show();
            $('.commentCont').show();
            $('.addPoint,.commentCont .submitBtnBar').hide();
            $(".studyStatus").hide();
        })

    }
    //    如果各个分数段都没有学生，统计结果不显示
    allPeos =<?=$allPeos?>;
    if (allPeos == 0) {
        $(".test_statistics,.statistics_title").hide();
    }
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
    //录入成绩
    $('.logScore').click(function () {
        examSubID = '<?=app()->request->getParam("examSubID")?>';
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
    //    取消按钮刷新
    $(".cancelBtn").click(function(){
        location.reload();
    });
    //查看学生名单
    $('.people_number span i').click(function(){
        $this=$(this);
        var bottomLimit=$(this).attr("bottomLimit");
        var topLimit=$(this).attr("topLimit");
        var examSubID="<?=app()->request->getParam('examSubID')?>";
        $.post("<?=url('teacher/exam/get-student-list')?>",{bottomLimit:bottomLimit,topLimit:topLimit,examSubID:examSubID},function(result){
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


</script>