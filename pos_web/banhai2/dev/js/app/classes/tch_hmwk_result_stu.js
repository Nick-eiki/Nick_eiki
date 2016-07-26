define(['sanhai_tools'],function(sanhai_tools){

	$(".u_list .subject a").click(function() {
            //var ajax_cont="ajax内容";   
            //拼接html
            var layerTopic = $('#layerTopic');
            if (layerTopic.size() > 0) layerTopic.remove();
            var elm_pos = sanhai_tools.horizontal_position($(this));
            var offset = $(this).offset(),
                left = offset.left - 100,
                top = offset.top + 55;
            var ajax_cont = "dgsdsdf3453453gsdsdf34534534535ersdgsdsdf34534534535ersdgsdsdf";
            var layerHtml = [
                '<div id="layerTopic" class="original_num" style="top:' + top + 'px; left:' + left + 'px">' +
                '   <div class="exhibition">' +
                '       <b class="close_box">×</b>' +
                '       <i class="v_r_arrow"></i>' +
                '       <div class="content">' + ajax_cont +
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
        // 弹层关闭按钮
        $(document).on('click', $(".close_box"), function() {
            $("#layerTopic").remove();
        });


})