define(["popBox",'userCard','jquery_sanhai','jqueryUI'],function(popBox,userCard,jquery_sanhai){

    //拖拽排序
    $('.upImgFile ul').sortable({items:"li:not(.disabled)"});

    //fixed答题卡
    $(window).scroll(function() {
        var scrollTop = $(window).scrollTop();
        var testpaperArea=$('.testpaperArea');
        var answer_card=$('#answer_card');
        var answer_card_h=answer_card.height();
        if(scrollTop>340){
            answer_card.addClass('answer_card_fixed');
            testpaperArea.css('marginTop',answer_card_h);
        }
        else{
            answer_card.removeClass('answer_card_fixed');
            testpaperArea.css('marginTop',18)
        }
    });


    //显示隐藏答题卡
    (function(){
        var open=false;
        $('#open_cardBtn').click(function(){
            var _this=$(this);
            if(open==false){
                $('#answer_card').addClass('answer_card_show');
                _this.html('收起<i></i>');
                open=true;
            }
            else{
                $('#answer_card').removeClass('answer_card_show');
                _this.html('展开<i></i>');
                open=false;
            }
        })
    })();


    //幻灯
    $('#slide').slide({'width':1151})


})