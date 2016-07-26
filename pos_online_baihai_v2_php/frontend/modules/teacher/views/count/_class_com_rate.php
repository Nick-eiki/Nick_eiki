<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-4-10
 * Time: 下午3:57
 */
?>
    //饼图
    var opts = {
        option1: {
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
                data: ['已完成', '未完成']
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
                    data:<?php echo $data?>
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
            var myChart = ec.init(document.getElementById('echarts04'));
// 为echarts对象加载数据
            myChart.setOption(opts.option1);
        }
    );