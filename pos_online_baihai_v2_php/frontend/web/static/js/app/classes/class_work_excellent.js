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
    $(".sel_test_bar .row").sel_list('single');


    $("#select_year,#select_month li").on('click' , function(){

        var url = $("#select_year").attr('data-url');
        var data_year = $('#select_year .sel_ac').attr('data-value');
        var data_month = $('#select_month .sel_ac').attr('data-value');

        $.post(url,{year:data_year , month:data_month},function(data){

            $("#statistics").html(data);

        });

    });


})
