define(['jquery'], function ($) {
    $("#tabList").delegate("li","click",function(){
        var self = $(this);
        var ID =self.data("id");
        var grade =  $("#grade"+ID).find("a");
        grade.addClass("ac");
        grade.siblings().removeClass("ac");
    });
});
