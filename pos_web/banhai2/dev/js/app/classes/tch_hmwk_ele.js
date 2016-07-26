define(["popBox",'userCard','jquery_sanhai','jqueryUI'],function(popBox,userCard,jquery_sanhai){
    $('#sss').toggle(function(){
        alert('234234');
    },
    function(){
        alert('dfsdfsdf');
    }

    )

    $('#slide').slide({'width':821});


        var scroll_Left=0;
        //右侧点击状态
        $("#q_list li").click(function(){
            scroll_Left=$('.cor_questions').scrollLeft();
            $(this).addClass("act").siblings().removeClass("act");
            $(".original").removeClass("show_original");
        });



        var q_lis=$("#q_list li");
        var ul_w=0;

        q_lis.each(function(index, element) {
            ul_w+=$(this).outerWidth(true);
        });
        $("#q_list").width(ul_w+20);

        //批改状态
        $('.cor_btn a').click(function(){
            var num=$("#q_list li").size();
            var index=0;
            var cls_name=$(this).attr('class');
            cls_name=="check" && chk("check")||cls_name=="half" && chk("half")||cls_name=="wrong" && chk("wrong")
            function chk(cls_name){
                index=$("#q_list .act").index()+1;
                scroll_Left+=50;
                $("#q_list .act i").removeClass().addClass(cls_name+"_btn");
                $("#q_list .act").removeClass('act').next().addClass('act');
                $('.cor_questions').scrollLeft(scroll_Left);
            };


            var topic_num=$("#q_list i[class$=btn]").size();
            $('#userList .cur span').text(topic_num);
            if(topic_num==num) popBox.confirmBox('已全部批改完成!',
                function(){alert("确定")},
                function(){alert("取消")}
            );
        });


    //原题弹窗
    $(".original").click(function(){
        $(this).toggleClass("show_original");
    });


    //图片工具
    $('#slide .slidePaperWrap').hover(
      function(){
        if($(this).children('.slideTools').length == 0){
            $(this).append('<div class="slideTools"><i class="rotateLeft"></i><i class="rotateRight"></i><i class="amplify"></i></div>');
        }
      },
      function(){
        $('.slideTools').remove();
      }
    );
    $('.slideTools .rotateLeft').live('click',function(){
        var curImg = $('.slidePaperList .current img');
        var imgWidth = parseInt(curImg.css('width'));
        var imgHeight = parseInt(curImg.css('height'));
        if((!curImg.attr('style'))||(curImg.attr('style').indexOf('rotate')==-1)){
            if(imgWidth < imgHeight){
                curImg[0].style.transform = 'rotate(90deg) scale('+imgWidth/imgHeight+')';
            }else{
                curImg[0].style.transform = 'rotate(90deg)';
            }
        }else{
            var current = (parseInt(curImg[0].style.transform.substring(7)) + 90)%360;
            if((imgWidth < imgHeight)&&(current == 90 || current == 270)){
                curImg[0].style.transform = 'rotate('+current+'deg) scale('+imgWidth/imgHeight+')';
            }else{
                curImg[0].style.transform = 'rotate('+current+'deg)';
            }
        }
    });
    $('.slideTools .rotateRight').live('click',function(){
        var curImg = $('.slidePaperList .current img');
        var imgWidth = parseInt(curImg.css('width'));
        var imgHeight = parseInt(curImg.css('height'));
        if((!curImg.attr('style'))||(curImg.attr('style').indexOf('rotate')==-1)){
            if(imgWidth < imgHeight){
                curImg[0].style.transform = 'rotate(-90deg) scale('+imgWidth/imgHeight+')';
            }else{
                curImg[0].style.transform = 'rotate(-90deg)';
            }
        }else{
            var current = (parseInt(curImg[0].style.transform.substring(7)) - 90)%360;
            if((imgWidth < imgHeight)&&(current == -90 || current == -270)){
                curImg[0].style.transform = 'rotate('+current+'deg) scale('+imgWidth/imgHeight+')';
            }else{
                curImg[0].style.transform = 'rotate('+current+'deg)';
            }
        }
    });
    $('.slideTools .amplify').live('click',function(){
        if($('#slide .slidePaperWrap').children('.mask').length == 0){
            $('#slide .slidePaperWrap').append('<div class="mask"></div>');
            $('#slide .slidePaperWrap').append('<div class="superMask"></div>');
            larTop=185;
            larLeft=175;
        }else{
            $('.mask').remove();
            $('.superMask').remove();
        }
    });
    $('.superMask').live('click',function(){
        $('.mask').remove();
        $('.superMask').remove();
        $('.largeDiv').remove();
    });
    var larTop=185;
    var larLeft=175;
    $('.superMask').live('mousemove',function(){
        var maskH=parseFloat($('.mask').css('height'));
        var maskW=parseFloat($('.mask').css('width'));
        var maxTop=parseFloat($(this).css('height'))-maskH;
        var maxLeft=parseFloat($(this).css('width'))-maskW;
        var e=window.event||arguments[0];
        var x=e.offsetX;
        var y=e.offsetY;
        var top=y-maskH/2;
        var left=x-maskW/2;
        top=top<0?0:top>maxTop?maxTop:top;
        left=left<0?0:left>maxLeft?maxLeft:left;
        $('.mask')[0].style.top=top+"px";
        $('.mask')[0].style.left=left+"px";
        var imgW=parseInt($('.slidePaperList .current img').css('width'));
        var imgH=parseInt($('.slidePaperList .current img').css('height'));
        var smaskW=parseFloat($(this).css('width'));
        var smaskH=parseFloat($(this).css('height'));
        var current = parseInt($('.slidePaperList .current img')[0].style.transform.substring(7));
        if(current==90||current==-270){
            if(imgW < imgH){
                var scale = imgW/imgH;
                var topBg=imgH*scale+(smaskW-imgH*scale)/2-left-maskW;
                var leftBg=top-(smaskH-imgW*scale)/2;
            }else{
                var topBg=imgH+(smaskW-imgH)/2-left-maskW;
                var leftBg=top-(smaskH-imgW)/2;
            }
        }else if(current==180||current==-180){
            var topBg=imgH+(smaskH-imgH)/2-top-maskH;
            var leftBg=imgW+(smaskW-imgW)/2-left-maskW;
        }else if(current==270||current==-90){
            if(imgW < imgH){
                var scale = imgW/imgH;
                var topBg=left-(smaskW-imgH*scale)/2;
                var leftBg=imgW*scale+(smaskH-imgW*scale)/2-top-maskH;
            }else{
                var topBg=left-(smaskW-imgH)/2;
                var leftBg=imgW+(smaskH-imgW)/2-top-maskH;
            }
        }else{
            var topBg=top-(smaskH-imgH)/2;
            var leftBg=left-(smaskW-imgW)/2;
        }
        $('.largeDiv')[0].style.backgroundPosition=-2*leftBg+"px "+-2*topBg+"px";
        var minCliY = parseInt($('.largeDiv').css('height'))+maskH/2;
        var maxCliY = document.documentElement.clientHeight-parseInt($('.largeDiv').css('height'))-maskH/2;
        var minCliX = parseInt($('.largeDiv').css('width'))+maskW/2;
        var maxCliX = document.documentElement.clientWidth-parseInt($('.largeDiv').css('width'))-maskW/2;
        var cliY = e.clientY||e.y;
        var cliX = e.clientX||e.x;
        if(cliY < minCliY){
            larTop = 175+10;
        }else if(cliY > maxCliY){
            larTop = -350+10;
        }
        if(cliX < minCliX){
            larLeft = 175;
        }else if(cliX > maxCliX){
            larLeft = -350;
        }
        $('.largeDiv')[0].style.top=top+larTop+"px";
        $('.largeDiv')[0].style.left=left+larLeft+"px";
    });
    $('.superMask').live('mouseover',function(){
        if($('#slide').children('.largeDiv').length == 0){
            $('#slide').append('<div class="largeDiv"></div>');
            var src=$('.slidePaperList .current img')[0].src;
            var imgW=parseInt($('.slidePaperList .current img').css('width'))*2;
            var imgH=parseInt($('.slidePaperList .current img').css('height'))*2;
            var curImg=$('.slidePaperList .current img');
            var current = parseInt(curImg[0].style.transform.substring(7));
            $('.largeDiv')[0].style.backgroundColor="#fff";
            $('.largeDiv')[0].style.backgroundImage="url("+src+")";
            $('.largeDiv')[0].style.backgroundRepeat="no-repeat";
            $('.largeDiv')[0].style.backgroundSize=imgW+"px "+imgH+"px";
            $('.mask')[0].style.opacity=0.2;
            if((curImg.attr('style'))&&(curImg.attr('style').indexOf('rotate')!=-1)){
                $('.largeDiv')[0].style.transform = 'rotate('+current+'deg)';
            }
            if((imgW < imgH)&&(current == 90 || current == 270 || current == -90 || current == -270)){
                var scale = imgW/imgH;
                $('.largeDiv')[0].style.backgroundSize=imgW*scale+"px "+imgH*scale+"px";
            }
        }
    });
    $('.superMask').live('mouseout',function(){
        $('.largeDiv').remove();
        $('.mask')[0].style.opacity=0;
    });


});