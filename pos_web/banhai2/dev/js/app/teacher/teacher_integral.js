define(["jquery"], function ($) {
    var num = 4;
    $(".main_tab li").click(function () {
        $(".main_tab li").removeAttr("id");
        $(this).attr("id", "select");
        $(".tab_class").css("display", "none");
        $(".tab_class").eq($(".main_tab li").index($(this))).css("display", "block");
        if ($(".main_tab li").index($(this)) == 0 && $(".main_tab li").index($(this)) != num) {
            //alert("收入明细");    点击收入明细事件

        } else if ($(".main_tab li").index($(this)) == 1 && $(".main_tab li").index($(this)) != num) {
            //alert("我的等级");    点击我的等级事件

        } else if ($(".main_tab li").index($(this)) == 2 && $(".main_tab li").index($(this)) != num) {
            //alert("积分商城");    点击积分商城事件

        }
        num = $(".main_tab li").index($(this));
    })
});