<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-4-7
 * Time: 下午4:29
 */
?>

    // 柱状图
    require(
        [
            'echarts',
            'echarts/chart/bar' // 使用柱状图就加载bar模块，按需加载
        ],
        function (ec) {
// 基于准备好的dom，初始化echarts图表
            var _this = this
            var myChart = ec.init(document.getElementById('echarts01'));

            var option = {
                tooltip: {
                    show: true,
                    formatter: "{b} : {c}"
                },
                color: ['#f00'],
                legend: {
                    data: ['单科作业完成度']
                },
                xAxis: [
                    {
                        type: 'category',
                        data: <?php echo $subject?>
                    }
                ],
                yAxis: [
                    {
                        type: 'value', name: '完成度'
                    }
                ],
                series: [
                    {
                        "name": "分数统计",
                        "type": "bar",
                        "barWidth": "50",
                        "data": <?php echo $data ?>
                    }
                ]
            };

// 为echarts对象加载数据
            myChart.setOption(option);
        }
    );