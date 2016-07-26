<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-4-11
 * Time: 下午3:21
 */
?>
    //饼图
    var opts = {
        option1: {
            title: {
                text: '考试成绩分布图',
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
                data: <?php echo $section?>
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
                    data:<?php echo $number?>
                }
            ]
        }


    }


    require(
        [
            'echarts',
            'echarts/chart/pie' // 使用柱状图就加载bar模块，按需加载
        ],
        function (ec) {
// 基于准备好的dom，初始化echarts图表
            var myChart = ec.init(document.getElementById('echarts03'));
// 为echarts对象加载数据
            myChart.setOption(opts.option1);
        }
    );
