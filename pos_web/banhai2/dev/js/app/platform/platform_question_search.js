define(['jquery_sanhai','jqueryUI'],function(jquery_sanhai){

    //搜索框提示
    $('#mainSearch').placeholder({
        value: "请输入资料名称关键字……",
        left: 15,
        top: 4
    });

    //单选
    $("#classes_sel_list").sel_list('single',function(){
        alert('ajax');
    });
    $("#hard_list").sel_list('single',function(){
        alert('ajax');
    });

    //$(document).on('click','.ico_basket',function(){
    //    alert(556)
    //    //$('#basket_cont').show();
    //    //return false;
    //})

    //$('#quest_basket').click(function(){
    //    $('#basket_cont').show();
    //    return false;
    //});


    //打开课程列表
    $('#show_sel_classesBar_btn').click(function(){
        $(".sel_classesBar").slideDown();
        return false;
    });
    //选择学科
    $("#sel_classesBar").sel_list('single');
    $("#sel_classesBar dd").click(function(){
        $('#sel_classes h5').text($(this).text());
        $("#sel_classesBar").hide();
        alert('ajax')
    });


    //查看解析答案按钮
    $('.show_aswerBtn').click(function(){
        var _this=$(this);
        var pa=_this.parents('.quest')
        pa.toggleClass('A_cont_show');
        _this.toggleClass('icoBtn_close');
        if(pa.hasClass('A_cont_show')){_this.html('收起答案解析 <i></i>');open=true}
        else{_this.html('查看答案解析 <i></i>');open=false;}
    })


    //添加选题篮
    $('.join_basket_btn').click(function(){
        var _this=$(this);
        var pa=_this.parents('.quest');
        var q_num=$('.q_num').html();
        if(pa.hasClass('join_basket')){
            pa.removeClass('join_basket');
            q_num--;
        }else{
            pa.addClass('join_basket');
            q_num++;
        }
        $('.q_num').html(q_num);

        //$('.q_num').html($('.join_basket').size());

    })

})