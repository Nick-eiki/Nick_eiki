define(['jquery_sanhai','popBox','jqueryUI'],function(jquery_sanhai,popBox){
   $('.main').click(function(){
       popBox.successBox('chesdfasdfasfasdf')
   })
    //单选
    $("#classes_sel_list").sel_list('single',function(elm){
        alert(elm.attr('data-id'));
    });


    $("#hard_list").sel_list('multi',function(){
        alert('ajax');
    });

    //选择课程 年级
    $('#sel_course').sUI_select(function(){
        alert(3453453)
    });
    $('#sel_grade').sUI_select();

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

    //选题篮
    $('#quest_basket').click(function(){
        $('#basket_cont').show();
        return false;
    });



    //目录树
    $('#pointTree').tree();

    //查看解析答案按钮
    (function(){
        var open=false;
        $('.show_aswerBtn').click(function(){
            var _this=$(this);
            var pa=_this.parents('.quest')
            pa.toggleClass('A_cont_show');
            _this.toggleClass('icoBtn_close');
            if(open==false){_this.html('收起答案解析 <i></i>');open=true}
            else{_this.html('查看答案解析 <i></i>');open=false;}
        })
    })()

})