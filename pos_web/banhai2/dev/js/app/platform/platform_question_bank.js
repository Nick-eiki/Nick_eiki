﻿﻿define(['jquery_sanhai'],function(){
    //选择课程 年级
    $('#sel_course').sUI_select();
    $('#sel_grade').sUI_select();

   //打开课程列表
    $('#show_sel_classesBar_btn').click(function(){
        $(".sel_classesBar").slideDown();
        return false;
    });
    //选择学科
    $("#sel_classesBar").sel_list('single');
    $("#sel_classesBar dd").click(function(){
        $('#sel_classes h5').text($(this).text());
        $("#sel_classesBar").hide();
        alert('ajax')
    });

    //目录树
    $('#pointTree').tree();

    //搜索框提示
    $('#mainSearch').placeholder({
        value: "请输入资料名称关键字……",
        left: 15,
        top: 4
    });

    //选择课程
    $(".classes_file_list").sel_list('single', function(data) {
        alert(data);
    });

    //收藏
    (function(){
        var faved=false;
        $('.cls_rList .fav').click(function(){
            var _this=$(this);
            if(faved==false){
                _this.addClass('cur');
                faved=true;
                alert('ajax');
            }
            else{
                _this.removeClass('cur');
                faved=false;
                alert('ajax');
            }
        })

    })();



})


