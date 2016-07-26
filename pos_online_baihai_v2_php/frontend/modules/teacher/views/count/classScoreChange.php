<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-12-4
 * Time: 下午3:24
 */
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$publicResources = Yii::$app->request->baseUrl;
;
$this->registerJsFile($publicResources . '/js/echarts/echarts.js');
$this->registerJsFile($publicResources . '/js/echarts/html5shiv.min.js');
$this->registerJsFile($publicResources . '/js/echarts/respond.min.js');
/* @var $this yii\web\View */  $this->title="班级成绩变动";
?>
<style>
    .echarts {
        width: 100%;
        height: 300px;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #ccc
    }
</style>
<script>
    // 路径配置
    require.config({
        paths: {echarts: '<?php echo publicResources()?>' + '/js/echarts'}
    });
    //折线图
    require(
        [
            'echarts',
            'echarts/chart/line' // 使用柱状图就加载bar模块，按需加载
        ],
        function (ec) {
            // 基于准备好的dom，初始化echarts图表
            var myChart = ec.init(document.getElementById('echarts02'));
            var option = {
                tooltip: {
                    show: true,
                    formatter: "{b} : {c}分"
                },
                color: ['#09f', '#02d', '#11d'],
                legend: {
                    data: ['总分平均分', '总分最高分', '总分最低分']
                },
                xAxis: [
                    {
                        type: 'category',
                        "data":<?php echo $examName?>
                    }
                ],
                yAxis: [
                    {
                        type: 'value', name: '分数', min: 50, max: 300
                    }
                ],
                series: [
                    {
                        "name": "总分平均分",
                        "type": "line",
                        "data":<?php echo $avgTotalScore?>
                    },
                    {
                        "name": "总分最高分",
                        "type": "line",
                        "data":<?php echo $maxTotalScore?>
                    },
                    {
                        "name": "总分最低分",
                        "type": "line",
                        "data":<?php echo $minTotalScore?>
                    }

                ]
            };

            // 为echarts对象加载数据
            myChart.setOption(option);
        }
    );
</script>
<div class="currentRight grid_16 push_2 topic_input">
    <div class="noticeH clearfix">
        <h3 class="h3L">成绩统计</h3>

        <div class="new_not fr">
            <em>我的班级：</em>
            <?php

            echo Html::dropDownList("", app()->request->getParam('classID', '')
                ,
                ArrayHelper::map($classArray, 'classID', 'className'),
                array(
                    "prompt" => "请选择",
                    "id" => "classID"
                ));
            ?>
        </div>
    </div>
    <hr>
    <div id="echarts02" class="echarts"></div>
    <div>
        <em>我的考试：</em>
        <?php

        echo Html::dropDownList("", app()->request->getParam('examID', '')
            ,
            ArrayHelper::map($examArray, 'examID', 'examName'),
            array(
                "prompt" => "请选择",
                "id" => "examID"
            ));
        ?>
    </div>

    <div id="echarts03" class="echarts"></div>
    <div id="subject">
    </div>

    <div id="echarts04" class="echarts"></div>
    <div id="subjectshow"></div>


</div>
<script>
    $("#classID").change(function () {
        location.href = "<?php echo url('teacher/count/class-score-change')?>" + "/classID/" + $("#classID").val();
    });
    $("#examID").change(function () {
        var examID = $(this).val();
        $.post("<?php echo url('teacher/count/total-scale')?>", {examID: examID}, function (result) {
            $("#subject").html(result);
        })
    })
</script>

