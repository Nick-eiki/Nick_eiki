define(['jquery'], function ($) {
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
        var grade =  $("#pie"+ID).find("u");
        grade.addClass("ac");
        grade.parent().siblings().find("u").removeClass("ac");
    });
    $("#btnAnswer").delegate("a","click",function(){
        var self = $(this);
        var ID =self.data("id");
        var grade =  $("#btnAnswer"+ID).find("u");
        grade.addClass("ac");
        grade.parent().siblings().find("u").removeClass("ac");
    });
    $("#myQuestion").click(function(){
            if(''){
                location.href = '/student/answer/add-question?studentId=';
            }else if('1'){
                location.href = '/teacher/answer/add-question?teacherId=';
            }
    });
});
