<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-12-8
 * Time: 下午12:01
 */
?>
<script>
    //    饼图 单科分布图
    require(
        [
            'echarts',
            'echarts/chart/pie' // 使用柱状图就加载bar模块，按需加载
        ],
        function (ec) {
            // 基于准备好的dom，初始化echarts图表
            var myChart = ec.init(document.getElementById('echarts04'));

            var option = {
                title: {
                    text: '成绩分布',
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
//                    data: ['非常满意', '比较满意', '一般', '不满意', '非常不满意']
                    data:<?php echo $subScoreName?>
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
                        data:<?php echo $subSectionJson?>
                    }
                ]
            };

            // 为echarts对象加载数据
            myChart.setOption(option);
        }
    );
</script>