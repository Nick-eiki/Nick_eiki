<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 2015/6/11
 * Time: 16:20
 */
?>
<script>
    path = '<?php echo publicResources_new() ?>';
    // 路径配置
    require.config({
        paths: {echarts: path + '/js/echarts'}
    });
    //饼图
    var opts = {
        option2: {
            title: {
                text: '本班成绩分布',
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
                data:<?= $section?>
            },
            toolbox: {
                show: true,
                feature: {
                    mark: {show: false},
                    dataView: {show: false, readOnly: false},
                    magicType: {
                        show: false,
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
                    saveAsImage: {show: false}
                }
            },
            calculable: true,
            series: [
                {
                    name: '成绩',
                    type: 'pie',
                    radius: '55%',
                    center: ['50%', '60%'],
                    data:<?=$data?>
                }
            ]
        }


    };


    require(
        [
            'echarts',
            'echarts/chart/pie' // 使用柱状图就加载bar模块，按需加载
        ],
        function (ec) {
            // 基于准备好的dom，初始化echarts图表
            var myChart = ec.init(document.getElementById('echarts04'));


            // 为echarts对象加载数据
            myChart.setOption(opts.option2);
        }
    );
</script>