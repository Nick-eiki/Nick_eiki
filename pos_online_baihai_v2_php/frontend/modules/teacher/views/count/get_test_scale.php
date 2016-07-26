<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-12-25
 * Time: 下午5:13
 */
?>

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
                    text: '考试成绩统计',
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
//                    data: ['非常满意', '比较满意', '一般', '不满意', '非常不满意']
                    data:<?php echo $sectionNameJson?>

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
                        name: '成绩分布',
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
                        data:<?php echo $scoreDataJson?>
                    }
                ]
            };

            // 为echarts对象加载数据
            myChart.setOption(option);
        }
    );

