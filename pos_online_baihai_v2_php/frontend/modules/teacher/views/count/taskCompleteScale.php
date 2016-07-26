<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-12-6
 * Time: 上午10:37
 */
$publicResources = Yii::$app->request->baseUrl;
;
$this->registerJsFile($publicResources . '/js/echarts/echarts.js');
$this->registerJsFile($publicResources . '/js/echarts/html5shiv.min.js');
$this->registerJsFile($publicResources . '/js/echarts/respond.min.js');
/* @var $this yii\web\View */  $this->title="作业完成比例";
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
    // 柱状图
    require(
        [
            'echarts',
            'echarts/chart/bar' // 使用柱状图就加载bar模块，按需加载
        ],
        function (ec) {
            // 基于准备好的dom，初始化echarts图表
            var _this=this;
            var myChart = ec.init(document.getElementById('echarts01'));

            var option = {
                tooltip: {
                    show: true,
                    formatter: "{b} : {c}"
                },
                color:['#f00'],
                legend: {
                    data:['作业完成比例统计']
                },
                xAxis : [
                    {
                        type : 'category',
                        data:<?php echo json_encode($scoreNameJson)?>
                    }
                ],
                yAxis : [
                    {
                        type : 'value', name:'比例'
                    }
                ],
                series : [
                    {
                        "name":"作业完成比例统计",
                        "type":"bar",

                        "data":<?php echo json_encode($scoreDataJson)?>
                    }
                ]
            };

            // 为echarts对象加载数据
            myChart.setOption(option);
        }
    );
</script>
<div class="currentRight grid_16 push_2 topic_input">
    <hr>
    <div id="echarts01" class="echarts"></div>
    <div id="echarts03" class="echarts"></div>
</div>