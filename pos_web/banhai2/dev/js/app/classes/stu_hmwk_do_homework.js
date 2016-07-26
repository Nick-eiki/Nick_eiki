define(["popBox",'jquery_sanhai','sanhai_tools','jqueryUI'],function(popBox,jquery_sanhai,sanhai_tools){

    //标记答题卡
    //var quest=$('.quest');
    //quest.each(function(){
    //    var _this=$(this);
    //    var p_id=_this.attr('id');
    //    var radios=_this.find('input:radio');
    //    radios.click(function(){
    //        $('#'+p_id+'_clip').addClass('done');
    //    });
    //
    //    var checkboxs= _this.find('input:checkbox');
    //    checkboxs.click(function(){
    //        var chked_num=_this.find('input:checkbox[checked]').size();
    //        if(chked_num>0)$('#'+p_id+'_clip').addClass('done');
    //        else $('#'+p_id+'_clip').removeClass('done');
    //    })
    //});

    $('input:radio').click(function(){
        var pa=$(this).parents('.quest');
        var p_id=pa.attr('id');
        $('#'+p_id+'_clip').addClass('done');

    });

    $('input:checkbox').click(function(){
        var pa=$(this).parents('.quest');
        if(pa.size()>1){
            pa=$(this).parents('.sub_quest');
        }
        var p_id=pa.attr('id');
        var chked_num=pa.find(':checked').size();
            console.log(pa.size());
            if(chked_num!=0)$('#'+p_id+'_clip').addClass('done');
            else $('#'+p_id+'_clip').removeClass('done');
    })





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
        if(scrollTop > homwork_info_top){
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

    //radio checkbox效果
    sanhai_tools.input();

    //标记答题卡


})