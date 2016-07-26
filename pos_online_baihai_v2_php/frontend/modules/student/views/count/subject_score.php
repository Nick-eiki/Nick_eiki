<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-12-8
 * Time: 下午1:44
 */
?>
<script>
    //单科折线图
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
                    data: ['单科分数']
                },
                xAxis: [
                    {
                        type: 'category',
//                    data: ["2001", "2002", "2003", "2004"],
                        data:<?php echo $examNameJson?>
                    }
                ],
                yAxis: [
                    {
                        type: 'value', name: '分数', min: 0, max: 100
                    },
                ],
                series: [
                    {
                        "name": "单科分数",
                        "type": "line",
//                    "data": [78, 81, 89, 85]
                        "data":<?php echo $subjectScoreJson ?>
                    }
                ]
            };

            // 为echarts对象加载数据
            myChart.setOption(option);
        }
    );
</script>
