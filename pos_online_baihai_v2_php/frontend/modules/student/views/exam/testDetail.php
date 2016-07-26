<?php
/**
 *
 * @var ExamController $this
 * @var Field reference $"score"
 * @var Field reference $"evaluate"
 * @var Field reference $"minAndMax"
 * @var Field reference $"scoreSection"
 * @var Field reference $"studentEvaluate"
 * @var PsiWhiteSpace $"subjectList"
 */
use frontend\components\helper\PinYinHelper;
use yii\helpers\Html;

$this->registerJsFile(publicResources_new() . "/js/echarts/echarts-all.js".RESOURCES_VER);
/* @var $this yii\web\View */  $this->title="考试详情";
?>

<?php $this->beginBlock('head_html'); ?>
<script>

    //饼图
    var opts = {
        option1: {
            title: {
                text: '成绩公布图',

                x: 'center'
            },
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                x: 'left',
                data:<?= json_encode(from($scopreTotal->list)->select(function($v){return $v->low.'-'.$v->high;})->toList()) ?>
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
                    data:<?=json_encode(from($scopreTotal->list)->select(function($v){return ['value'=>$v->peos, 'name'=>$v->low.'-'.$v->high];  })->toList()) ?>

                }
            ]
        }
    };

    $(function () {
        // 基于准备好的dom，初始化echarts图表
        var myChart = echarts.init(document.getElementById('scoreCont'));
        // 为echarts对象加载数据
        myChart.setOption(opts.option1);
//        $('.upload_test_Btn').click(function () {
//            $(this).parents('.testItem').children('.my_answer').show();
//        });
        $('.total_comtBtn').click(function(){
            var pa=$(this).parents('.testItem');
            pa.find('.total_comt').show();
            if(sanhai_tools.vertical_position($(this))) pa.find('.total_comt').removeClass("total_comt_upside");
            else pa.find('.total_comt').addClass("total_comt_upside");
            return false;
        });

        $('.imgFile').imgFileUpload("答案");

        $('.topModify_Btn').click(function(){
            var pa=$(this).parents('.testItem');
            pa.find('.delBtn,.finishBtn,.more').show();

        });

        $('.upload_test_Btn').click(function(){
            var pa=$(this).parents('.testItem');
            pa.find('.imgFile,.my_answer').show();

        });
        //上传图片模块
//        $('.upload_test_Btn,.topModify_Btn').click(function () {
////            $(this).parents('.testItem').children('.my_answer').show();
//        });
//        (function () {
//            var upload_test_Btn = $('.imgFile').parents('.testItem').find('.upload_test_Btn');
//            var modifyBtn = $('.imgFile').parents('.testItem').find('.topModify_Btn');
//            $('.imgFile').imgFileUpload(modifyBtn);
//            $('.imgFile').imgFileUpload(upload_test_Btn);
//
//        })();

    });

    function done(e, data) {
        $.each(data.result, function (index, file) {
            if (file.error) {
                popBox.errorBox(file.error);
                return;
            }
            var i = $(e.target).parent().siblings('li').length + 1;
            $('<li data-img-url="' + file.url + '" ><img src="' + file.url + '" alt=""><b>答案第' + i + '页</b><span class="delBtn"></span></li>').insertBefore($(e.target).parent());
        });
    }

    function savepage(obj, examsubId) {
        var imgArray = [];
        $(obj).parent('.imgFile').find('li[data-img-url]').each(function (n, index) {
            imgArray.push($(index).attr('data-img-url'));
        });
        var imgUrl = imgArray.join(",");
        if (imgUrl == "") {
            popBox.errorBox("请选择图片");
            return false;
        }

        $.post("<?php echo url('student/managepaper/upload-paper')?>", {
            imageurl: imgUrl,
            examid: examsubId
        }, function (result) {
            if (result.success) {
                popBox.successBox(result.message);
                location.reload();
            }
            else {
                popBox.errorBox(result.message);
            }
        });
    }


</script>
<?php $this->endBlock('head_html'); ?>


<div class="grid_19 main_r">
    <div class="main_cont test_detail">
        <div class="title"><a href="<?=url('student/exam/manage')?>" class="txtBtn backBtn"></a>
            <h4><?php echo $minAndMax->examName ?></h4>
            <div class="title_r">
                考试时间:<?=$subjectList->examTime?>
            </div>
        </div>
        <div class="scoreAnalyze pr">
            <div class="title item_title noBorder">
                <h4>成绩分析</h4>

            </div>

            <div id="scoreCont" class="scoreCont" style="height:400px; width:700px"></div>

            <div class="<?=$rankChange->rankChange>=0?'scoreSequence score_up':'scoreSequence score_down'?>"><span><?=abs($rankChange->rankChange)?></span><em>名</em>
            </div>

        </div>
        <hr>
        <div class="commentCont">
            <div class="title item_title noBorder">
                <h4>本班总评</h4>
            </div>
            <div class="form_list no_padding_form_list">
                <?php if($evaluate->isHaveCEva==1){?>
                <div class="row">
                    <div class="formL">
                        <label>班内学习状态</label>
                    </div>
                    <div class="formR"> <?= $evaluate->learnSituation ?></div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label>共性问题</label>
                    </div>
                    <div class="formR"><?= $evaluate->commonPro ?></div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label>改进建议</label>
                    </div>
                    <div class="formR"><?= $evaluate->improveAdvise ?></div>
                </div>
                <?php }?>
                <div class="row">
                    <div class="formL">
                        <label>我的成绩单</label>
                    </div>
                    <div class="formR ">  <?php foreach ($score->examScoresList as $v) { ?>
                            <?php echo $v->subjectName ?>：
                            <b><?php echo intval($v->stuSubScore) ?></b>分&nbsp;&nbsp;&nbsp;
                        <?php } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label></label>
                    </div>
                    <div class="formR">总分&nbsp;&nbsp;&nbsp;
                        最高分:<b><?= intval($minAndMax->MaxScore) ?></b>分&nbsp;&nbsp;&nbsp;
                        最低分:<b><?= intval($minAndMax->MinScore) ?></b>分
                    </div>
                </div>
                <?php if($studentEvaluate->evaluate!=""){?>
                <div class="row">
                    <div class="formL">
                        <label>班主任评语</label>
                    </div>
                    <div class="formR">
                        <dl class="clearfix teacherComd ">
                            <?php echo Html::encode($studentEvaluate->evaluate) ?>
                        </dl>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
        <br>
        <hr>

        <div class="my_test  my_testBox">
            <div class="title item_title noBorder">
                <h4>我的答卷</h4>
            </div>


            <?php foreach ($subjectList->examSubList as $item): ?>
                <?php if ($item->paperId != "") { ?>
                    <div class="testpaperList  <?= PinYinHelper::firstChineseToPin($item->subjectName) ?>">
                        <em></em>
                        <?php
                        if ($item->getType == 0) {
                            echo $this->render('zz_testDetail_partview', ['item' => $item]);
                        } else {
                            echo $this->render('dz_testDetail_partview', ['item' => $item]);
                        }
                        ?>
                    </div>
                <?php } endforeach ?>

        </div>
    </div>
</div>
<script>
    <?php if($scopreTotal->allPeos==0){?>
    $(".scoreAnalyze").hide();
    <?php }?>
</script>