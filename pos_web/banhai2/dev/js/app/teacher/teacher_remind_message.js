define(['jquery'],function($){
    $("#tab li").click(function(){
        $("#tab li").removeClass("select");
        $(this).addClass("select");
        $(".tab_1").css("display","none");
        $(".tab_1").eq($("#tab li").index($(this))).css("display","block");
    });
    $(".cut").css("display","none");
    $(".tab_1>ul>li").hover(function(){
        /*显示垃圾桶*/
        $(this).children().find('.cut').css("display","inline-block");
    },function(){
        /*隐藏垃圾桶*/
        $(this).children().find('.cut').css("display","none");
    });
});