define(['jquery'], function ($) {
    /**
     * 页头标题选择输入框
     */
    $(".write_pen").one('click', function () {
        console.log("ss");
        $('.write_pen').html('<input id="title_txt" />');
        $('#title_txt').focus();
    });
    /**
     * 收件人关联
     * @type {number}
     */
    //第几个班级
    var num = 0;
    //点空白控制开关
    var type = false;
    /**
     * 点击收件人下拉框 更换selected时 触发
     */
    $(".choose").change(function () {
        //选中部分学生时  选择学生按钮显示
        if ($(this).find('option:selected').html() == '部分学生') {
            $(this).parent().next().css('display', 'inline-block');
        } else {//选中全部学生时
            $(this).parent().next().css('display', 'none');
            $(this).parents('.ul_1').next().css('display', 'none');
        }
    });

    /**
     * 点击 选择学生 按钮 时选择学生列表显示
     */
    $(".hide button").live('click', function () {
        type = true;
        $(this).parent().next().children('li.bg_blue_').removeClass('bg_blue_');
        for (var i = 0, len = $(this).parents('.ul_1').next().children('li').length; len > i; i++) {
            for (var j = 0, len_ = $(this).parent().next().children('li').length; len_ > j; j++) {
                if ($(this).parents('.ul_1').next().children('li').eq(i).html() == $(this).parent().next().children('li').eq(j).html()) {
                    $(this).parent().next().children('li').eq(j).addClass('bg_blue_');
                }
            }
        }
        num = $('.ul_1').index($(this).parents('.ul_1'));
        $(this).parent().next().css('display', 'block');
        $(this).parents('.ul_1').next().css('display', 'block');
    });
    $(".ul_2 li").live("click", function () {
        $(this).remove();
        if ($('.ul_2').html() == '') {
            $('.ul_2').css('display', 'none');
        }
    });

    /**
     * 点击其他地方隐藏 选择学生列表
     */
    $('body').click(function (event) { // 如果是元素本身，则返回
        if (type) {
            event = event || window.event;
            var evt = event.srcElement ? event.srcElement : event.target;
            //alert(evt);
            if (evt.classList == 'choose_stu' || evt.parentNode.classList == 'choose_stu') {
                //return;
            } else { // 如不是则隐藏元素
                //console.log('num::' + num);
                var len = $('.ul_1').eq(num).find('.choose_stu li.bg_blue_').length;
                console.log(len);
                $('.ul_1').eq(num).next().html('');
                if (len != 0) {
                    for (var i = 0; len > i; i++) {
                        //console.log($('.ul_1').eq(num).find('.choose_stu li.bg_blue_').eq(i).html());
                        $('.ul_1').eq(num).next().append('<li class="bg_blue_">' + $('.ul_1').eq(num).find('.choose_stu li.bg_blue_').eq(i).html() + '</li>');
                    }
                } else {
                    $('.ul_1').eq(num).next().css('display', 'none');
                }
                $('.choose_stu').hide();
                type = false;
            }
        }
    });
    /**
     * 选择学生点击增添 .bg_blue_ 类
     */
    $('.choose_stu li').live('click', function () {
        $(this).toggleClass('bg_blue_');
    });
    /**
     * 删除图片事件
     */
    $('.remove_images').live('click', function () {
        $(this).parent('li').remove();
        img_num++;
        $("#remove_img").css('display','block');
    });
    /**
     * 添加图片事件
     * @img_num 剩余图片数量
     */
    //var img_num = 20;
    //var loadImageFile = (function () {
    //    if (window.FileReader) {
    //        var oPreviewImg = null, oFReader = new window.FileReader(),
    //                rFilter = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;
    //        oFReader.onload = function (oFREvent) {
    //            var newPreview = document.createElement('li');
    //            oPreviewImg = new Image();
    //            oPreviewImg.style.width = "179px";
    //            oPreviewImg.style.height = "120px";
    //            newPreview.innerHTML = '<i class="remove_images"></i>';
    //            newPreview.appendChild(oPreviewImg);
    //            var img_ul = document.getElementById('images_ul');
    //            img_ul.insertBefore(newPreview, document.getElementById('imageInput').parentNode);
    //            img_num--;
    //            document.getElementById('img_num').innerHTML = img_num;
    //            oPreviewImg.src = oFREvent.target.result;
    //        };
    //        return function () {
    //            var aFiles = document.getElementById("imageInput").files;
    //            if (aFiles.length === 0) {
    //                return;
    //            }
    //            if (!rFilter.test(aFiles[0].type)) {
    //                alert("You must select a valid image file!");
    //                return;
    //            }
    //            oFReader.readAsDataURL(aFiles[0]);
    //        }
    //    }
    //})();
});