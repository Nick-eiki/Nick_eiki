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
        var myChart1 = ec.init(document.getElementById('echarts01')); //主观题
        var myChart2 = ec.init(document.getElementById('echarts02')); //客观题
        var myChart3 = ec.init(document.getElementById('echarts03')); //题目难度 柱状图
        //var myChart4 = ec.init(document.getElementById('homework_rate')); //题目难度 柱状图


        var option1={

            tooltip:{
                show:true,
                formatter: "{c}分 <点击显示原题>",
                position: function () {
                    var newx=arguments[0][0]-25;
                    var newy=arguments[0][1]-30;
                    return [newx,newy];
                }
            },
            dataZoom: {
                show: true,
                start : 0,
                height:20
            },
            color:['#09f'],

            xAxis:[
                {
                    type : 'category',
                    data :json
                }
            ],
            yAxis : [
                {
                    type : 'value', name:'分数',min:0,max:100
                }
            ],
            series : [
                {
                    symbol:'circle',//原点样式
                    symbolSize:5,//原点大小
                    "name":"echarts01",
                    "type":"line",
                    "data":[14, 21, 19, 15,14,23,18, 31, 49, 55,34,23,38, 51, 49, 35,34,23,43,29,14, 21, 19, 15,14,23,18, 31, 49, 55,34,23,38, 51, 49, 35,34,23,43,29,14, 21, 19, 15,14,23,18, 31, 49, 55,34,23,38, 51, 49, 35,34,23,43,29]
                }
            ]
        };


        var option2={//客观题
            id:"myChart2",
            tooltip:{
                show:true,
                formatter: "{c}分 <点击显示原题>"
            },
            dataZoom: {
                show: true,
                start : 0,
                height:20
            },
            color:['#09f'],

            xAxis:[
                {
                    type : 'category',
                    data : ["1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40","41","42","43","44","45","46","47","48","49","50","51","52","53","54","55","56","57","58","59","60"]
                }
            ],
            yAxis : [
                {
                    type : 'value', name:'分数',min:0,max:100
                }
            ],
            series : [
                {
                    symbol:'circle',//原点样式
                    symbolSize:5,//原点大小
                    "name":"echarts02",
                    "type":"line",
                    "data":[14, 21, 19, 15,14,23,58, 31, 89, 55,34,23,28, 51, 49, 95,34,23,63,29,14, 21, 19, 15,14,23,58, 31, 89, 55,34,23,28, 51, 49, 95,34,23,63,29,14, 21, 19, 15,14,23,58, 31, 89, 55,34,23,28, 51, 49, 95,34,23,63,29]
                }
            ]
        };

        var option3 = {//题目难度 柱状图
            tooltip: {
                show: true,
                formatter: "{b} : {c}人"
            },
            color:['#4EBBFE'],
            legend: {
                data:['正确率统计']
            },
            xAxis : [
                {
                    type : 'category',
                    data : ["简单题","中档题","难题"]
                }
            ],
            yAxis : [
                {
                    type : 'value', name:'人数'
                }
            ],
            series : [
                {
                    "name":"正确率",
                    "type":"bar",
                    "barWidth":"50",
                    "data":[5, 64, 8]
                }
            ]
        };


        //var zrColor = require('zrender/tool/color');
        //var colorList = [ '#1ED348','#E3A55A','#2CB3F2','#FF664E'];
        //var itemStyle = {
        //    normal: {
        //        label : {show: true, position: 'top'},
        //        color: function(params) {
        //            if (params.dataIndex < 0) {
        //                // for legend
        //                return zrColor.lift(
        //                    colorList[colorList.length - 1], params.seriesIndex * 0.1
        //                );
        //            }
        //            else {
        //                // for bar
        //                return zrColor.lift(
        //                    colorList[params.dataIndex], params.seriesIndex * 0.1
        //                );
        //            }
        //        }
        //    }
        //};
        //
        //var option4 = {
        //    tooltip: {
        //        trigger: 'axis',
        //        backgroundColor: 'rgba(255,255,255,0.7)',
        //        axisPointer: {type: 'shadow'},
        //        formatter: function(params) {
        //            // for text color
        //            var color = colorList[params[0].dataIndex];
        //            var res = '<div style="color:' + color + '">';
        //            res += '<strong>' + params[0].name + '</strong>'
        //            /*for (var i = 0, l = params.length; i < l; i++) {
        //             res += '<br/>' + params[i].seriesName + ' : ' + params[i].value
        //             }*/
        //            res += '</div>';
        //            return res;
        //        }
        //    },
        //
        //    calculable: true,
        //    grid: {
        //        y: 80,
        //        y2: 40,
        //        x2: 40
        //    },
        //    xAxis: [
        //        {
        //            type: 'category',
        //            data: ['优 50%', '良 30%', '中 20%', '差 2%'],
        //            axisLabel:{textStyle:{fontSize:'18',color:'#666'}}
        //        }
        //    ],
        //    yAxis: [
        //        {
        //            type: 'value',
        //            name:"人数"
        //        }
        //    ],
        //    series: [
        //        {
        //            name: '优',
        //            type: 'bar',
        //            itemStyle: itemStyle,
        //            "barWidth":50,
        //            data: [50,30,26,3]
        //        }
        //    ]
        //};

        // 为echarts对象加载数据
        myChart1.setOption(option1);
        myChart2.setOption(option2);
        myChart3.setOption(option3);
        //myChart4.setOption(option4);





        //添加点击事件
        var ecConfig = require('echarts/config');
        function open_topic_box(param){

            var dataIndex=param.dataIndex;
            var table_id=param.seriesName;
            function delBox(){
                var box=document.getElementById('statistics_topic_box');
                if(box) document.body.removeChild(box);
            }
            delBox();

            if(sanhai_tools.isIE(6) ||sanhai_tools.isIE(7)||sanhai_tools.isIE(8)){
                popBox.errorBox('您的浏览器版本过低,无法显示原题,请升级浏览器,推荐安装<a href="http://dlsw.baidu.com/sw-search-sp/soft/9d/14744/ChromeStandalone_46.0.2490.86_Setup.1447296650.exe" style="color:#fff; text-decoration:underline"><谷歌浏览器></a>');
            }
            else{
                var pageX=param.event.clientX;
                var pageY=param.event.clientY;
                var screenW=document.body.offsetWidth;
                var scrollH=document.body.scrollTop || document.documentElement.scrollTop ;
                var cls="statistics_topic_box";
                if(pageX<screenW/2)	pageX=pageX
                else{
                    pageX=pageX-400;
                    cls="statistics_topic_box statistics_topic_box_l";
                };
                pageY=pageY+scrollH;
                var box=document.createElement('div');
                var arrow=document.createElement('i');
                var delBtn=document.createElement('a');
                var boxCont=document.createElement('div');

                boxCont.innerHTML="Ajax内容";

                delBtn.innerHTML="×";
                delBtn.setAttribute('href','javascript:;');
                delBtn.className="delBoxBtn";
                arrow.className="arrow";
                box.appendChild(arrow);
                box.appendChild(delBtn);
                box.appendChild(boxCont);
                box.id="statistics_topic_box";
                box.className=cls;
                box.style.top=pageY+20+"px";
                box.style.left=pageX-100+"px";

                document.body.appendChild(box);
                delBtn.onclick=function(){delBox()}
            }
        }
        myChart1.on(ecConfig.EVENT.CLICK,open_topic_box);
        myChart2.on(ecConfig.EVENT.CLICK,open_topic_box);
    }
);
