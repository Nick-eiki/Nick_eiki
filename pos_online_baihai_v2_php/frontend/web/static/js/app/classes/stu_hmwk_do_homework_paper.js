define(["popBox",'userCard','jquery_sanhai','jqueryUI'],function(popBox,userCard,jquery_sanhai){

    //fixed答题卡
    $(window).scroll(function() {
        var scrollTop = $(window).scrollTop();
        var testpaperArea=$('.testpaperArea');
        var answer_card=$('#answer_card');
        var answer_card_h=answer_card.height();
        var homwork_info;//答题卡之前的元素
        if($(".homwork_ladder")[0]){
            homwork_info=$(".homwork_ladder");
        }else{
            homwork_info=$(".homwork_info");
        }
        var homwork_info_top=homwork_info.offset().top + homwork_info.height() + 20;
        if(scrollTop>homwork_info_top){
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
        var homwork_info;//答题卡之前的元素
        if($(".homwork_ladder")[0]){
            homwork_info=$(".homwork_ladder");
        }else{
            homwork_info=$(".homwork_info");
        }
        var homwork_info_top=homwork_info.offset().top + homwork_info.height() + 17;
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
                if($('#answer_card').hasClass("answer_card_fixed")){
                    $(window).scrollTop(homwork_info_top);
                }
            }
        })
    })();

    function leftPicCal(){
        var liSize=$('.upImgFile').find('li').size();
        $('.uploadFileBtn').find('span').html(21-liSize);
        if(liSize > 20){
            $('.addResult').hide();
        }else{
            $('.addResult').show();
        }
    }

//拖拽排序
    $('.upImgFile ul').sortable({items:"li:not(.disabled)"});


    $(document).off('click').on('click','.delBtn',function(){
        $(this).parent().remove();
        leftPicCal();
    });
    //幻灯
    $('#slide').slide({'width':1151})


});