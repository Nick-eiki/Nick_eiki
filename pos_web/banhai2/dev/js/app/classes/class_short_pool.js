define(["popBox", 'FlexoCalendar', 'jquery_sanhai', 'jqueryUI'], function (popBox) {

    $('#time_tab').tab();

    //日
    $("#calendar").flexoCalendar({
        selectDate: 'each-each-each',
        onselect: function (date) {
            $(".text2").attr("value", date);
        }
    });

    //周
    $("#calendar-weekly").flexoCalendar({
        type: 'weekly',
        onselect: function (date) {
            $(".text1").attr("value", date);
            var reg = /^(\d+\-\d+\-\d+)\,(\d+\-\d+\-\d+)$/g, textAttr = reg.exec(date);
            $(".text1").attr("start", textAttr[1]);
            $(".text1").attr("end", textAttr[2]);
        }
    });

    //月
    $("#calendar-monthly").flexoCalendar({
        type: 'monthly',
        onselect: function (date) {
            $(".text0").attr("value", date);
        }
    });


    $(".text0").click(function () {
        $("#month").show();
    })
    $(".text1").click(function () {
        $("#week").show();
    })
    $(".text2").click(function () {
        $("#day").show();
    })

    $(".text1").attr("value",$("#calendar-weekly .current-week").text());

    var ary = [
        {
            "num": "108",
            "name": "1234基础知识累积与运用用基础知识累积与运用用"
        },
        {
            "num": "18",
            "name": "基础知识累积与运用用"
        },
        {
            "num": "10",
            "name": "基础知识累积与运用用"
        },
        {
            "num": "80",
            "name": "基础知识累积与运用用"
        },
        {
            "num": "11",
            "name": "基础知识累积与运用用"
        },
        {
            "num": "98",
            "name": "基础知识累积与运用用"
        },
        {
            "num": "23",
            "name": "基础知识累积与运用用"
        },
        {
            "num": "17",
            "name": "基础知识累积与运用用"
        },
        {
            "num": "35",
            "name": "基础知识累积与运用用"
        },
        {
            "num": "44",
            "name": "基础知识累积与运用用"
        }
    ]
    var str = "",MaxAry = parseInt(ary[0].num);
    for (var i = 0; i < ary.length; i++) {
        var cur=parseInt(ary[i].num),item=ary[i].name;
        cur > MaxAry ? MaxAry = cur  : null;
        var num = Number((cur / MaxAry) * 100).toFixed(0);
        str += "<div class='knowledgeBar'><span class='knowledgeNum' title="+ item +">"+ item + "</span><div class='knowledgeError'><span class='width' style=width:" + num + '%' + "><em class='errorNum'>错误次数：" + cur + " 次</em></span></div></div>";
    };

    $(".knowledge").html(str);

    $('.knowledge').each(function(){
        $(this).find(".knowledgeError:last").css({"border-bottom":"2px solid #008acd"});

    });


    //查看解析答案按钮
    $('.show_aswerBtn').click(function () {
        var _this = $(this);
        var pa = _this.parents('.quest')
        pa.toggleClass('A_cont_show');
        _this.toggleClass('icoBtn_close');
        if (pa.hasClass('A_cont_show')) _this.html('收起答案解析 <i></i>');
        else _this.html('查看答案解析 <i></i>');
    });


    /*
    点击右侧百分比条出现错题
    $(".width").live("click", function () {
        $(this).parent().siblings().css({"color": "#10ade5"});
        $(this).parent().parent().siblings().children(".knowledgeNum").css({"color": "black"});
        $(".testPaper").show(400);
    });
    */

    $(".knowledgeNum").live("click", function () {
        $(this).css({"color": "#10ade5"}).parent().siblings().children(".knowledgeNum").css({"color": "black"});
        $(".testPaper").show(400);
    })


    $(".search").click(function () {
        $(".shortKnowledge").show(400);
    })

    //var currentWeek=$("#week .current-week");
    //currentWeek.parent().prev().children().addClass("current-week current-day");
    //currentWeek.removeClass("current-week current-day ");

})

















