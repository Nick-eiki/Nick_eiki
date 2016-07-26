 $(function(){
//点击空白 关闭弹出窗口	
$(document).bind("mousedown",function(e){var target=$(e.target);if(target.closest(".pop").length==0)$(".pop").hide()})


//top右侧菜单----------------------------------------------------------	 
$('.userAccount,.userMsg').hover(
	function(){$(this).children('ul').show()},
	function(){$(this).children('ul').hide()}
)



//左侧动画菜单
$('.setupMenu .subMenu .ac').parents('ul').show().prev('a').addClass('close');
$(".setupMenu li:has('.subMenu')").children('a').click(function(){
	$(this).toggleClass('close').next('ul').stop().slideToggle();
	$(this).parent('li').siblings().children('a').removeClass('close');
	$(this).parent('li').siblings().children('.subMenu').slideUp();
})
$('.subMenu a').click(function(){
	$('.subMenu a').removeClass('ac');
	$(this).addClass('ac')
})


//表格隔行变色
$('table tbody tr').live('mouseover',function(){$(this).addClass('trOver')})
$('table tbody tr').live('mouseout',function(){$(this).removeClass('trOver')})

//textarea模块(剩余数字 添加表情)
$('.textareaBox textarea').charCount();
$('.textareaBox textarea').live('focus',function(){$(this).next('.placeholder').hide()});
$('.textareaBox textarea').live('blur',function(){if($(this).val()=="") $(this).next('.placeholder').show()});
$('.textareaBox .placeholder').live('click',function(){$(this).hide();$(this).prev().focus()})





	  
/*找密码--切换按钮*/
$('.pass span').click(function(){
	$('.pass i').removeClass('i_btn');
	$(this).children('i').addClass('i_btn');
	var index=$(this).index();
	$('.passWordBox ul:eq('+index+')').show().siblings().hide();

})

	

/*个人设置下拉框*/
$('.class_R .setJs').click(function(){
	$(this).parent().children('ul').show();
		return false;
	}
	
)
$(document).click(function(){
	$('.class_R .setJs').parent().children('ul').hide();	
})
/*大事记更多*/
$('.moreJS').toggle(function(){
	$('.class_Box_JS').show();
},
function(){
	$('.class_Box_JS').hide();
})

//焦点的文字
 $('.new_span .text_fous').placeholder({"value":"考研组名称","color":"#ccc"});
$('.new_span .t_fous').placeholder({"value":"部门名称","color":"#ccc"});


//题目预览------------------------------------------------

//查看答案与解析
$('.openAnswerBtn').click(function(){
	$(this).children('i').toggleClass('close');
	$(this).parents('.paper').find('.answerArea').toggle();
})


//修改课程详情
$('.course_deta_title').children('.course_list:first').addClass('course_list_first')


//学生我要提问加的样式
$('.tye_que').children('span:first').css('font-weight','bold');



//上传头像
$('.centico').mouseover(function(){
	$(this).children('a').show();	
})
$('.centico').mouseout(function(){
	$(this).children('a').hide();	
})





//平台页面
//选项卡
;(function($){
	
$.fn.tabso=function( options ){

	var opts=$.extend({},$.fn.tabso.defaults,options );
	
	return this.each(function(i){
		var _this=$(this);
		var $menus=_this.children( opts.menuChildSel );
		var $container=$( opts.cntSelect ).eq(i);
		
		if( !$container) return;
		
		if( opts.tabStyle=="move"||opts.tabStyle=="move-fade"||opts.tabStyle=="move-animate" ){
			var step=0;
			if( opts.direction=="left"){
				step=$container.children().children( opts.cntChildSel ).outerWidth(true);
			}else{
				step=$container.children().children( opts.cntChildSel ).outerHeight(true);
			}
		}
		
		if( opts.tabStyle=="move-animate" ){ var animateArgu=new Object();	}
			
		$menus[ opts.tabEvent]( function(){
			var index=$menus.index( $(this) );
			$( this).addClass( opts.onStyle )
				.siblings().removeClass( opts.onStyle );
			switch( opts.tabStyle ){
				case "fade":
					if( !($container.children( opts.cntChildSel ).eq( index ).is(":animated")) ){
						$container.children( opts.cntChildSel ).eq( index ).siblings().css( "display", "none")
							.end().stop( true, true ).fadeIn( opts.aniSpeed );
					}
					break;
				case "move":
					$container.children( opts.cntChildSel ).css(opts.direction,-step*index+"px");
					break;
				case "move-fade":
					if( $container.children( opts.cntChildSel ).css(opts.direction)==-step*index+"px" ) break;
					$container.children( opts.cntChildSel ).stop(true).css("opacity",0).css(opts.direction,-step*index+"px").animate( {"opacity":1},opts.aniSpeed );
					break;
				case "move-animate":
					animateArgu[opts.direction]=-step*index+"px";
					$container.children( opts.cntChildSel ).stop(true).animate( animateArgu,opts.aniSpeed,opts.aniMethod );
					break;
				default:
					$container.children( opts.cntChildSel ).eq( index ).css( "display", "block")
						.siblings().css( "display","none" );
			}
	
		});
		
		$menus.eq(0)[ opts.tabEvent ]();
		
	});
};	

$.fn.tabso.defaults={
	cntSelect : ".content_wrap",
	tabEvent : "mouseover",
	tabStyle : "normal",
	direction : "top",
	aniMethod : "swing",
	aniSpeed : "fast",
	onStyle : "this",
	menuChildSel : "*",
	cntChildSel : "*"
};

})(jQuery);



//end  
})