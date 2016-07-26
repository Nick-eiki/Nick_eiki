define(['jquery_sanhai','jqueryUI'],function(jquery_sanhai){

    //单选
    $("#classes_sel_list").sel_list('single',function(){
        alert('ajax');
    });
    $("#hard_list").sel_list('single',function(){
        alert('ajax');
    });

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





    $('#quest_basket').click(function(){
        $('#basket_cont').show();
        return false;
    });

    //目录树
    $('#pointTree').tree();


    $('.fav').click(function(){
        alert('ajax')
    });

    //查看解析答案按钮
    (function(){
        var open=false;
        $('.show_aswerBtn').click(function(){
            var _this=$(this);
            var pa=_this.parents('.quest')
            pa.toggleClass('A_cont_show');
            _this.toggleClass('icoBtn_close');
            if(open==false){_this.html('收起答案解析 <i></i>');open=true}
            else{_this.html('查看答案解析 <i></i>');open=false;}
        })
    })();

    //纠错弹框

    $('.correction').click(function(){
        $('.popBox').dialog({
            autoOpen: false,
            width: 640,
            modal: true,
            resizable: false,
            close: function() {
                $(this).dialog("close")
            }
        });
        $('.textareaBox').speak('发送纠错内容',10);
        $('#correctionBox').dialog('open');
    })






})