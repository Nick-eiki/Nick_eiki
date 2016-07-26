<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-4-7
 * Time: 下午2:57
 */
?>
    //饼图
    var opts = {
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
                data: ['非常满意', '比较满意', '一般', '不满意', '非常不满意']
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
                    data: [
                        {value: 335, name: '非常满意'},
                        {value: 1310, name: '比较满意'},
                        {value: 234, name: '一般'},
                        {value: 135, name: '不满意'},
                        {value: 48, name: '非常不满意'}
                    ]
                }
            ]
        },
        option1: {
            title: {
                text: '作业完成度',

                x: 'center'
            },
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                x: 'left',
                data: ['已完成','未完成']
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
                    name: '完成度',
                    type: 'pie',
                    radius: '55%',
                    center: ['50%', '60%'],
                    data:
//                        [
//                        {value: 35, name: '非常满意'},
//                        {value: 150, name: '比较满意'},
//                        {value: 3334, name: '一般'},
//                        {value: 135, name: '不满意'},
//                        {value: 8, name: '非常不满意'}
//                    ]
                    <?php echo $data?>
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
            var myChart = ec.init( document.getElementById('echarts04') );
            // 为echarts对象加载数据
            myChart.setOption(opts.option1);
        }
    );

