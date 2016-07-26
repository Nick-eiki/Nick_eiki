define(['jquery'],function(){
    $("#tab li").click(function(){
        $("#tab li").removeClass('select');
        $(this).addClass('select');
        $(".tab").css("display","none");
        $(".tab").eq($("#tab li").index($(this))).css("display","block");
    });
    $(".cut").css("display","none");
    $(".tab>ul>li").hover(function(){
        /*显示垃圾桶*/
        $(this).children().find('.cut').css("display","inline-block");
    },function(){
        /*隐藏垃圾桶*/
        $(this).children().find('.cut').css("display","none");
    });
    $("span.f_l b").toggle(function(){
        /* 显示收取信息学生&家长 */
        $(this).css("background","url('../dev/images/ico.png') -84px -828px no-repeat");
        $(this).parents('p').next().css('display','block');
    },function(){
        /* 隐藏收取信息学生&家长 */
        $(this).css("background","url('../dev/images/ico.png') -64px -828px no-repeat");
        $(this).parents('p').next().css('display','none');
    });
     function alert_n() {
        $("#alert").css({"left":($(window).width() - $("#alert").width()) / 2,"top": $(window).scrollTop() + (($(window).height() - $("#alert").height()) / 2),"display":"block"});
        $("#alert_bg").css({"height": $(document).height()});
        return;
    }
    $("#alert_remove").live("click", function () {//remove 弹出框
        $("#alert").css("display","none");
        $("#alert_bg").css("display","none");
        return;
    });
    $(".tab>ul>li").live('click',function(){
        alert_n();
    });
    $(window).resize(function(){
        $("#alert").css("top", $(window).scrollTop() + (($(window).height() - $("#alert").height()) / 2));
        $("#alert").css("left", ($(window).width() - $("#alert").width()) / 2);
        $("#alert_bg").css({"height": $(document).height()});
        $("#alert_bg").css({"width": $(document).width()});
    })
});