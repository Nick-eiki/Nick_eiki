define(["popBox", 'jquery_sanhai', 'jqueryUI'], function(popBox) {

      //打开课程列表
    $('#sch_mag_classesBar_btn').click(function(){
        $(".sch_mag_homes").slideDown();
        return false;
    });

    //左侧激活样式
    $(".left_menu li a").click(function(){
        $(".left_menu li a").removeClass("cur");
        $(this).addClass("cur");
    });

    //选择考试
    $(".sel_test_bar .row").sel_list('single',function(){
        alert(3434)
    });


})
