define(["popBox",'userCard','echarts/echarts','echarts/chart/pie','jquery_sanhai','jqueryUI'],function(popBox,userCard,ec){
        //回答 答案
        (function(){

            //选择课程
            $('.classes_file_list .row').openMore(38);
            $('#classes_sel_list').sel_list('single',function(){
                alert(5345)
            });



            $('.reply').click(function () {
                var pa = $(this).parents('.QA_li');
                if(!pa.hasClass('open_ask')){
                    pa.addClass('open_ask').removeClass('open_answer');
                    pa.siblings('li').removeClass('open_ask open_answer');
                }
                else{
                    pa.removeClass('open_ask');
                }
            });
            $('.answer').click(function () {
                var pa = $(this).parents('.QA_li');
                if(!pa.hasClass('open_answer')){
                    pa.addClass('open_answer').removeClass('open_ask');
                    pa.siblings('li').removeClass('open_answer open_ask');
                }
                else{
                    pa.removeClass('open_answer')
                }
            });

            $('.QA_cancelBtn').click(function(){
                var pa = $(this).parents('.QA_li');
                pa.removeClass('open_ask');
            })

            $('.QA_answerBtn').click(function(){
                //$.post('url',{id:"abdc"},function(result){
                //
                //})
            });
        })();

        //	显示卡片
        (function(){
            var overTime,outTime,card;
            $('.askerList img').on({
                mouseover:function(){
                    clearTimeout(overTime);
                    var _this=$(this);
                    $.get('/card_json.txt',function(data){
                        data=eval("("+data+")");
                        userCard.userCard(_this,data);
                        card=$('.userCard');
                    });
                    overTime=setTimeout(function(){
                        card.show();
                    },200);
                },
                mouseout:function(){
                    clearTimeout(overTime);
                    //card=$('.userCard');
                    function removeCard(){
                        outTime=setTimeout(function(){
                            card.remove();
                        },30);
                    };
                    removeCard();
                    overTime=setTimeout(function(){removeCard()},30);
                    card.mouseover(function(){
                        clearTimeout(overTime);
                        clearTimeout(outTime);
                    });
                    card.mouseout(function(){
                        removeCard();
                    });
                }
            });
        })();

        //(function(){
        //    var overTime,outTime;
        //    $('.askerList img').on({
        //        mouseover:function(){
        //            clearTimeout(overTime);
        //            var _this=$(this);
        //            overTime=setTimeout(function(){
        //                $.get('/card_json.txt',function(data){
        //                    data= eval("(" + data + ")");
        //                    userCard.userCard(_this,data);
        //                })
        //            },200);
        //        },
        //        mouseout:function(){
        //            clearTimeout(overTime);
        //            var card=$('.userCard');
        //            function removeCard(){
        //                outTime=setTimeout(function(){
        //                    card.remove();
        //                },60);
        //            };
        //            removeCard();
        //            overTime=setTimeout(function(){removeCard()},200);
        //            card.mouseover(function(){
        //                clearTimeout(overTime);
        //                clearTimeout(outTime);
        //                clearTimeout(moveTime);
        //            });
        //            card.mouseout(function(){
        //                removeCard();
        //            });
        //            var moveTime;
        //            $(document).one("mousemove",function(){
        //                moveTime=setTimeout(function(){
        //                    card.remove();
        //                },60);
        //                //removeCard();
        //                console.log("document.mousemove");
        //            });
        //            card.mousemove(function(event){
        //                event.stopPropagation();
        //            });
        //        },
        //        mousemove:function(event){
        //            event.stopPropagation();
        //        }
        //    });
        //})();



    //答疑统计

    $('#anwser_rate_tab').tab(function(){
        alert('ajax');

    });
    var myChart1 = ec.init(document.getElementById('anwser_rate'));
    option = {
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)",
            enterable:true
        },
        legend: {
            orient : 'vertical' ,
            x : 'left',
            y:'15px',
            data:['语文','数学','英语','历史','地理','物理','化学','生物','语文','数学','英语','历史','地理','物理','化学','生物']
        },

        calculable : true,
        series : [
            {
                name:'访问来源',
                center:['60%','60%'],
                type:'pie',
                radius : ['30%', '50%'],
                itemStyle : {
                    normal : {
                        label : {
                            show : false
                        },
                        labelLine : {
                            show : false
                        }
                    },
                    emphasis : {
                        label : {
                            show : true,
                            position : 'center',
                            textStyle : {
                                fontSize : '30',
                                fontWeight : 'bold'
                            }
                        }
                    }
                },
                data:[
                    {value:20, name:'语文'},
                    {value:310, name:'数学'},
                    {value:234, name:'英语'},
                    {value:135, name:'历史'},
                    {value:158, name:'地理'},
                    {value:234, name:'物理'},
                    {value:135, name:'化学'},
                    {value:58, name:'生物'},
                    {value:20, name:'语文'},
                    {value:310, name:'数学'},
                    {value:234, name:'英语'},
                    {value:135, name:'历史'},
                    {value:158, name:'地理'},
                    {value:234, name:'物理'},
                    {value:135, name:'化学'},
                    {value:58, name:'生物'}
                ]
            }
        ]
    };
    myChart1.setOption(option);


    $("#tabList").delegate("li","click",function(){
        var self = $(this);
        var ID =self.data("id");
        var grade =  $("#upload"+ID).find("a");
        grade.addClass("ac");
        grade.parent().siblings().find("a").removeClass("ac");
    });
    $("#statistics_pie").delegate("li","mouseover",function(){
        var self = $(this);
        var ID =self.data("id");
        var grade =  $("#pie"+ID).find("a");
        grade.addClass("ac");
        grade.parent().siblings().find("a").removeClass("ac");
    });
})