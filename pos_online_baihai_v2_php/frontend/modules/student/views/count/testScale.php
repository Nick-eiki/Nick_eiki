<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-12-5
 * Time: 下午2:48
 */
$publicResources = Yii::$app->request->baseUrl;
;
$this->registerJsFile($publicResources . '/js/echarts/echarts.js'.RESOURCES_VER);
$this->registerJsFile($publicResources . '/js/echarts/html5shiv.min.js'.RESOURCES_VER);
$this->registerJsFile($publicResources . '/js/echarts/respond.min.js'.RESOURCES_VER);
/* @var $this yii\web\View */  /* @var $this yii\web\View */  $this->title="测验的成绩变动";
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
        paths: {echarts: '<?php echo publicResources()?>'+'/js/echarts'}
    });


    //总分折线图
    require(
        [
            'echarts',
            'echarts/chart/line' // 使用柱状图就加载bar模块，按需加载
        ],
        function (ec) {
            // 基于准备好的dom，初始化echarts图表
            var myChart = ec.init(document.getElementById('echarts03'));
            var option = {
                tooltip: {
                    show: true,
                    formatter: "{b} : {c}分"
                },
                color: ['#09f'],
                legend: {
                    data: ['总分']
                },
                xAxis: [
                    {
                        type: 'category',
//                    data: ["2001", "2002", "2003", "2004"],
                        data:<?php echo $testNameJson?>
                    }
                ],
                yAxis: [
                    {
                        type: 'value', name: '分数', min: 100, max: 500
                    },
                ],
                series: [
                    {
                        "name": "总分",
                        "type": "line",
//                    "data": [78, 81, 89, 85]
                        "data":<?php echo $testScoreJson?>
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
    </div>
    <hr>
    <div id="echarts03" class="echarts"></div>
</div>