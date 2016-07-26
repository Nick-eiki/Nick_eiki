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
        var overTime,outTime;
        $('.askerList img').on({
            mouseover:function(){
                clearTimeout(overTime);
                var _this=$(this);
                overTime=setTimeout(function(){
                    $.get('/card_json.txt',function(data){
                        data= eval("(" + data + ")");
                        userCard.userCard(_this,data);
                    })
                },200);
            },
            mouseout:function(){
                clearTimeout(overTime);
                var card=$('#userCard');
                function removeCard(){
                    outTime=setTimeout(function(){
                        card.remove();
                    },100);
                };
                removeCard();
                overTime=setTimeout(function(){removeCard()},200);
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



    //答疑统计

    $('#anwser_rate_tab').tab(function(){
        alert('ajax');

    });




})