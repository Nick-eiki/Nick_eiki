var json=[{'id':32421,'value':1},{'id':234,'value':2},{'id':434531,'value':3},{'name':1,'value':4},{'name':1,'value':5},{'name':1,'value':6},{'name':1,'value':7},{'name':1,'value':8},{'name':1,'value':9},{'name':1,'value':10},{'name':1,'value':11},{'name':1,'value':12},{'name':1,'value':13},{'name':1,'value':14},{'name':1,'value':15},{'name':1,'value':16},{'name':1,'value':17},{'name':1,'value':18},{'name':1,'value':19},{'name':1,'value':50}]

define(
    [
        'echarts/echarts',
        'sanhai_tools',
        "popBox",
        "jquery_sanhai",
        'echarts/chart/line',
        'echarts/chart/bar',

    ],
    function (ec,sanhai_tools,popBox) {
        // 基于准备好的dom，初始化echarts图表
        var myChart4 = ec.init(document.getElementById('homework_rate')); //题目难度 柱状图

        var zrColor = require('zrender/tool/color');
        var colorList = [ '#7edb92','#10ade5','#f3d883','#f97373'];
        var itemStyle = {
            normal: {
                label : {show: true, position: 'top'},
                color: function(params) {
                    if (params.dataIndex < 0) {
                        // for legend
                        return zrColor.lift(
                            colorList[colorList.length - 1], params.seriesIndex * 0.1
                        );
                    }
                    else {
                        // for bar
                        return zrColor.lift(
                            colorList[params.dataIndex], params.seriesIndex * 0.1
                        );
                    }
                }
            }
        };

        var option4 = {
            tooltip: {
                trigger: 'axis',
                backgroundColor: 'rgba(255,255,255,0.7)',
                axisPointer: {type: 'shadow'},
                formatter: function(params) {
                    // for text color
                    var color = colorList[params[0].dataIndex];
                    var res = '<div style="color:' + color + '">';
                    res += '<strong>' + params[0].name + '</strong>'
                    /*for (var i = 0, l = params.length; i < l; i++) {
                     res += '<br/>' + params[i].seriesName + ' : ' + params[i].value
                     }*/
                    res += '</div>';
                    return res;
                }
            },

            calculable: true,
            grid: {
                y: 80,
                y2: 40,
                x2: 40
            },
            xAxis: [
                {
                    type: 'category',
                    data: ['优 50%', '良 30%', '中 20%', '差 2%'],
                    axisLabel:{textStyle:{fontSize:'18',color:'#666'}}
                }
            ],
            yAxis: [
                {
                    type: 'value',
                    name:"人数"
                }
            ],
            series: [
                {
                    name: '优',
                    type: 'bar',
                    itemStyle: itemStyle,
                    "barWidth":50,
                    data: [50,30,26,3]
                }
            ]
        };

        // 为echarts对象加载数据
        myChart4.setOption(option4);

    }
);
