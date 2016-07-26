define(['sanhai_tools'],function (sanhai_tools){

	//点击空白 关闭弹出窗口
	$(document).bind("mouseup",function(e){var target=$(e.target);if(target.closest(".pop").length==0)$(".pop").hide()});

	//页面侧边
	var fixedHtml='<div class="backTop hide"></div>';//返回顶部
	
	$("body").append(fixedHtml);

	$('.foot i').click(function(){
		$(this).children('.QRCord').show();
		return false;
	})

	//头部
	$(document).on('mouseover','.head .msgAlert',function(){
		$(this).children('a').addClass('msgAlert_hover');
		$('.head .msgList').show();
	});
	$(document).on('mouseout','.head .msgAlert',function(){
		$(this).children('a').removeClass('msgAlert_hover');
		$('.head .msgList').hide();
	})


	$(document).on('mouseover','.head_nav li',function(){
		$(this).children('.subMenu').show();
	});
	$(document).on('mouseout','.head_nav li',function(){
		$(this).children('.subMenu').hide();
	})


	 $(".centerBox").hover(
        function() {
            $(this).addClass('centerBox_hover');
        },
        function() {
            $(this).removeClass('centerBox_hover');
        }
    );

	//表格隔行变色
	$(document).on('mouseover','table tbody tr',function(){
		$(this).addClass('trOver');
	})
	$(document).on('mouseout','table tbody tr',function(){
		$(this).removeClass('trOver');
	})

})



