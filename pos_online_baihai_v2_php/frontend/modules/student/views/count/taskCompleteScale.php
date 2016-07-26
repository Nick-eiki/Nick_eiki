<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-12-5
 * Time: 下午6:21
 */

/* @var $this yii\web\View */
$publicResources = Yii::$app->request->baseUrl;
;
$this->registerJsFile($publicResources . '/js/echarts/echarts.js'.RESOURCES_VER);
$this->registerJsFile($publicResources . '/js/echarts/html5shiv.min.js'.RESOURCES_VER);
$this->registerJsFile($publicResources . '/js/echarts/respond.min.js'.RESOURCES_VER);
 /* @var $this yii\web\View */  /* @var $this yii\web\View */  $this->title="作业完成比例";
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


    //饼图
    require(
        [
            'echarts',
            'echarts/chart/pie' // 使用柱状图就加载bar模块，按需加载
        ],
        function (ec) {
            // 基于准备好的dom，初始化echarts图表
            var myChart = ec.init(document.getElementById('echarts03'));

            var option = {
                title: {
                    text: '作业完成比例',
//                    subtext: '纯属虚构',
                    x: 'center'
                },
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    x: 'left',
                    data: ["完成","未完成"]

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
                        name: '满意度',
                        type: 'pie',
                        radius: '55%',
                        center: ['50%', '60%'],
//                        data: [
//                            {value: 335, name: '非常满意'},
//                            {value: 1310, name: '比较满意'},
//                            {value: 234, name: '一般'},
//                            {value: 135, name: '不满意'},
//                            {value: 48, name: '非常不满意'}
//                        ]
                        data:<?php echo $taskDataJson?>
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

    <div id="echarts03" class="echarts"></div>
</div>