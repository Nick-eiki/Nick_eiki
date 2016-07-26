﻿define(["popBox", 'jquery_sanhai','validationEngine','validationEngine_zh_CN','jqueryUI'], function(popBox) {
//验证
    $('#exam_form').validationEngine();    //选择课程 年级
    $('#sel_course').sUI_select();
    $('#sel_grade').sUI_select();
    //打开课程列表
    $('#sch_mag_classesBar_btn').click(function(){
        $(".sch_mag_homes").slideDown();
        return false;
    });
    //选择学科
    $("#sch_mag_homes").sel_list('single');
    $("#sch_mag_homes dd").click(function(){
        $('#sel_classes h5').text($(this).text());
        $("#sch_mag_homes").hide();
    });

    //选择课程
    $(".classes_file_list .row").sel_list('single', function(elm) { });

    //初始化弹框
    $('.popBox').dialog({
        autoOpen: false,
        width: 690,
        modal: true,
        resizable: false,
        close: function() {
            $(this).dialog("close")
        }
    });
    //左侧激活样式
    $(".left_menu li a").click(function(){
        $(".left_menu li a").removeClass("cur");
        $(this).addClass("cur");
    });

    $(".exam_click").live('click',function(){

        var examType = $(this).attr('examType');
        var isSolved = $('.solved_type').find('.sel_ac').attr('isSolved');
        var examYear = $(".year_type").find(".sel_ac").attr("examYear");
        var gradeId = $('.grade_id').attr("gradeId");
        var department = $('.grade_id').attr("department");

        $.get('/exam/default/index',{examYear:examYear,examType:examType,isSolved:isSolved,gradeId:gradeId,schoolLevel:department},function(data){
            $("#answerPage").html(data);
        })
    })
    $(".solved_clcik").live('click',function(){
        var isSolved = $(this).attr('isSolved');
        var examType = $('.exam_type').find('.sel_ac').attr('examType');
        var examYear = $(".year_type").find(".sel_ac").attr("examYear");
        var gradeId = $(".grade_id").attr("gradeId");
        var department = $('.grade_id').attr("department");
        $.get('/exam/default/index',{examYear:examYear,examType:examType,isSolved:isSolved,gradeId:gradeId,schoolLevel:department},function(data){
            $("#answerPage").html(data);
        })
    })
    $(".year_click").live('click',function(){
        var examYear = $(this).attr('examYear');

        var examType = $('.exam_type').find('.sel_ac').attr('examType');
        var isSolved = $('.solved_type').find('.sel_ac').attr('isSolved');
        var gradeId = $('.grade_id').attr("gradeId");
        var department = $('.grade_id').attr("department");
        $.get('/exam/default/index',{examYear:examYear,examType:examType,isSolved:isSolved,gradeId:gradeId,schoolLevel:department},function(data){
            $("#answerPage").html(data);
        })
    })
})
