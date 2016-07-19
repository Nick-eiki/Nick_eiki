$(function(){
    var window_width=$(window).width();
    if(window_width>800) {
        var overflow_header = (1680 - window_width) / 2;
        //alert(overflow_header);
        $("#header").css({"background": "url('img/uploads/home.png') -" + overflow_header + "px 0 no-repeat"});
    }
});