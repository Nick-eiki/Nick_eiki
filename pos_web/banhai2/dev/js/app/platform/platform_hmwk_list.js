﻿﻿define(['jquery_sanhai'],function(){
    //单选
    $("#hard_list").sel_list('single',function(){
        alert('ajax');
    });

    //打开课程列表
    $('#show_sel_classesBar_btn').click(function(){
        $(".sel_classesBar").slideDown();
        return false;
    });

    $("#sel_classesBar dd").click(function(){
        $('#sel_classes h5').text($(this).text());
        $("#sel_classesBar").hide();
        alert('ajax')
    });
    //选择版本 学制
    $('#sel_course,#sel_grade').sUI_select();

    //目录树
    $('#pointTree').tree();

})


