define(['sanhai_tools'],function(sanhai_tools){

    $(".u_list .subject a").click(function() {

        var questionId = $(this).attr('questionId');
        var answerOption = $(this).attr('answerOption');
        var relId = $(this).attr('relId');
        var _this=$(this);
        $.post("/workstatistical/question-info",{questionId:questionId,answerOption:answerOption,'relId':relId,student:'student'},function(data){

           //拼接html
            var layerTopic = $('#layerTopic');
            if (layerTopic.size() > 0) layerTopic.remove();
            var elm_pos = sanhai_tools.horizontal_position(_this);
            var offset = _this.offset(),
                left = offset.left - 100,
                top = offset.top + 55;
           var layerHtml = [
                '<div id="layerTopic" class="original_num" style="top:' + top + 'px; left:' + left + 'px">' +
                '   <div class="exhibition">' +
                '       <b class="close_box">×</b>' +
                '       <i class="v_r_arrow"></i>' +
                '       <div class="content">' +data +
                '       </div>' +
                '   </div>' +
                '</div>'
            ];
            $("body").append(layerHtml.join(""));
            if (!elm_pos) {
                $('#layerTopic').addClass("layer_right").css({
                    'left': left - 300
                });
            } else {
                $('#layerTopic').removeClass("layer_right");
            }
            return false;
        });
    });
    // 弹层关闭按钮
    $(document).on('click', $(".close_box"), function() {
        $("#layerTopic").remove();
    });


});