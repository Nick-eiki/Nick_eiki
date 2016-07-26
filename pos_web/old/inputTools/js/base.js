//数组新增方法---删除指定元素
Array.prototype.remove=function(val){
	var index = this.indexOf(val.toString());  
	if (index > -1) {this.splice(index, 1)}  
};
//数组新增方法---排序
Array.prototype.arrq=function(a,b){
	var temp=this[a];
	this[a]=this[b];
	this[b]=temp;
	return this;
};



//光标位置插入文本  插入表情(face)-------------------------------------------------------------
//获取光标位置
function getCursortPosition (ctrl) {
    var CaretPos=0;   // IE Support
    if (document.selection) {
    	ctrl.focus();
        var Sel=document.selection.createRange();
        Sel.moveStart ('character', -ctrl.value.length);
        CaretPos =Sel.text.length;
    }
    // Firefox support
    else if (ctrl.selectionStart || ctrl.selectionStart=='0'){
        CaretPos = ctrl.selectionStart;
	}
    return CaretPos;
};

//设置光标位置
function setCaretPosition(ctrl, pos){
	if(ctrl.setSelectionRange) {
		ctrl.focus();
		ctrl.setSelectionRange(pos,pos);
	}
	else if (ctrl.createTextRange) {
		var range = ctrl.createTextRange(); range.collapse(true); range.moveEnd('character', pos); range.moveStart('character', pos); range.select();
		}
 };

//插件
;(function($){
	$.fn.extend({
	insertAtCaret: function(myValue){
		var $t=$(this)[0];
		if(document.selection) {
			this.focus();
			sel = document.selection.createRange();
			sel.text = myValue;
			this.focus();
		}
		else 
		if ($t.selectionStart || $t.selectionStart == '0') {
			var startPos = $t.selectionStart;
			var endPos = $t.selectionEnd;
			var scrollTop = $t.scrollTop;
			$t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
			this.focus();
			$t.selectionStart = startPos + myValue.length;
			$t.selectionEnd = startPos + myValue.length;
			$t.scrollTop = scrollTop;
		}
		else {
			this.value += myValue;
			this.focus();
		}
		}
	}) 
})(jQuery);

//数字统计-----------------------------------------------------------------------
;(function($){
	$.fn.charCount = function(opts){
		var defaults = {
			divName:"textareaBox", //外层容器的class
   			textareaName:"checkTextarea", //textarea的class
   			numName:"num", //数字的class
   			num:140 //数字的最大数目	
		}; 
		var opts = $.extend(defaults, opts); 
		return this.each(function() {
			//定义变量
		   var $onthis;//指向当前
		   var $divname=opts.divName; //外层容器的class
		   var $textareaName=opts.textareaName; //textarea的class
		   var $numName=opts.numName; //数字的class
		   var $num=opts.num; //数字的最大数目
		   
		   function isChinese(str){  //判断是不是中文
			var reCh=/[u00-uff]/;
			return !reCh.test(str);
		   }
		   function numChange(){
			var strlen=0; //初始定义长度为0
			var txtval = $.trim($onthis.val());
			for(var i=0;i<txtval.length;i++){
				if(isChinese(txtval.charAt(i))==true) strlen=strlen+2;//中文为2个字符
				else strlen=strlen+1;//英文一个字符
			}
			strlen=Math.ceil(strlen/2);//中英文相加除2取整数
			if($num-strlen<0){
			 $par.html("超出 <b style='color:red;font-weight:bold' class="+$numName+">"+Math.abs($num-strlen)+"</b> 字"); //超出的样式
			}
			else{
			 $par.html("可以输入 <b class="+$numName+">"+($num-strlen)+"</b> 字"); //正常时候
			}
			$b.html($num-strlen);   
		   }
		   $("."+$textareaName).live("focus",function(){
			$b=$(this).parents("."+$divname).find("."+$numName); //获取当前的数字
			$par=$b.parent(); 
			$onthis=$(this); //获取当前的textarea
			var setNum=setInterval(numChange,500);    
		   });
		})
	}
})(jQuery);


//select发生变化时,弹出变更提示-------------------------------------------------------------
;(function($){
	$.fn.extend({
		selectAlert:function(opts) {
			var def={
			txt:"确定变更?",
			trueFn:function(){},  //确定 回调函数
			falseFn:function(){},  //取消 回调函数
			}
		var opts=$.extend(def,opts);
			return this.each(function() {
				var oldElemt=$(this).children('[selected="selected"]');
				$(this).change(function(){
				var newElemt=$(this).children('[selected="selected"]');
					popBox.confirmBox(opts.txt,
					function(){
						$(this).children().removeAttr('selected');
						newElemt.attr('selected',true);
						oldElemt=newElemt;
						opts.trueFn();
					},
					function(){
						$(this).children().removeAttr('selected');
						oldElemt.attr('selected',true);
						opts.falseFn();
					})
				})
			});
		}
	});
})(jQuery);



//checkbox多选  checkAll-------------------------------------------------------------
;(function($){
	$.fn.extend({
		checkAll:function(checkboxs) {
			return this.each(function() {
				var _this=$(this);
				var num=checkboxs.length;
				var checkNum=num;
				$(this).live('click',function(){
					if(!_this.attr('checked')){
						checkboxs.removeAttr('checked');
						checkNum=0;
					}
					else{
						checkboxs.attr('checked',true);
						checkNum=num;
					}
				})
				checkboxs.each(function(){
					$(this).live('click',function(){
					if(!$(this).attr('checked')){
						_this.removeAttr('checked');
						checkNum--;
					}
					else{checkNum++}
					if(checkNum==num){	_this.attr('checked',true)	}
					})
				})
			});
		}
	});
})(jQuery);


//-自定义下拉框select----------------------------------------------------------------------
;(function($){
	$.fn.extend({
	mySelect: function(opts) {
		var def={
			title:"title",  //显示选中的文本的class
			openBtn:"openBtn",  //打开下拉列表按钮的class
			selectList:"selectList",  //下拉列表的class
			fn:function(){}//回调函数
			}
		var opts=$.extend(def,opts);
		return this.each(function() {
			$(this).children("."+opts.openBtn).click(function(){
				$(this).siblings("."+opts.selectList).css("z-index",200).show();
				return false;
			})
			$(this).find("."+opts.selectList+" a").click(function(){
				$(this).parents('.mySelect').children("."+opts.title).text($(this).text());
				$(this).parents("."+opts.selectList).hide();
				opts.fn();
			})
		})	
     }
 });
})(jQuery);


//修改指定文本 ajax----------------------------------------------------
;(function($){
	$.fn.extend({
		editPlus:function(opts) {
			var def={
				type:"input",//选择输入框的类型 txt:input  menu:select
				list:[],//输入框为select,此为select的选项
				target:'',//要修改文字的目标元素
				top:0, //定位top值
				url:'',//ajax地址
				data:[],//ajax自定义属性
				tag:null,//ajax 属性元素
				customData:[]//后台自定义属性(后台操作)
			}
			var opts=$.extend({},def,opts);
			
			function ajax(val,btn,clsName,value){
				var attrsTarget = opts.tag || opts.target;
				var data = {};
				var len=opts.data.length;
				if(len>0){
					for(var i=0 ; i<len; i++){
						data[opts.data[i]] = attrsTarget.attr(opts.data[i]);
					}
				}
				$.ajax({
					url:opts.url,
					data:$.extend({},data,{'data':val}),
					dataType:"json",
					success: function(msg){
						if(msg.success){
							opts.target.text(value||val);
							if(value){opts.target.attr('value',val);}
							$('.'+clsName).remove();
							opts.target.css({'visibility':'visible'});
							btn.css({'visibility':'visible'});
						}else{
							popBox.errorBox('修改失败,请重试!');	
						}
					}
				})
			}
			return this.each(function() {
				$(this).click(function(){
					
					var _this=$(this);
					opts.target=_this.prev();
					var _target=opts.target;
					var inputTop=_target.offset().top-3 ;
					var inputLeft=_target.offset().left;
					var inputW=_target.width()+20;
					var oldTxt=_target.text();
					var oldVal="";
					var clsName="editPlus";//+parseInt(Math.random()*100);
					$(document).keyup(function(event){
					  if(event.keyCode ==13){
						$('.'+clsName+' .btn').trigger("click");
					  }
					});
					$('.'+clsName).remove();
					
					if(opts.type=="input"){//input输入框
						var html='<div class="'+clsName+'" style="position:absolute; z-index:100;top:'+(inputTop+opts.top)+'px; left:'+inputLeft+'px"><input type="text" class="text" style="width:'+inputW+'px;" value="'+oldTxt+'"><input type="button" class="btn" value="确定" style=" padding:0 5px; font-size:12px; *border:none 0; *height:24px"></div>';
						opts.top=0;
						$("body").append(html);
						
						$('.'+clsName+' .btn').click(function(){
							var val = $('.'+clsName+' .text').val();
							if(val == ""){popBox.errorBox('不能为空!');	};
							if(val>900 || val<0){popBox.errorBox('超长范围!0--900');	}
							else if(val == oldTxt){
								$('.'+clsName).remove();
							}
							else{ ajax(val,_this,clsName) }
						})
					}
					else{//select下拉列表
						oldVal=_target.attr('value');
						var opation_html="";
						for(var i=0; i<opts.list.length;i++){
							if(opts.list[i].key==oldVal){
								opation_html+='<option selected value="'+opts.list[i].key +'">'+opts.list[i].value+'</option>';
							}
							opation_html+='<option value="'+opts.list[i].key +'">'+opts.list[i].value+'</option>';
						}
						var html='<div class="'+clsName+'" style="position:absolute; z-index:100;top:'+(inputTop+opts.top)+'px; left:'+(inputLeft-6)+'px"><select style="font-size:12px">'+opation_html+'</select><input type="button" class="btn" value="确定" style=" padding:0 5px; font-size:12px; *border:none 0; *height:24px"></div>';
						opts.top=0;
						$("body").append(html);
						var sel_opts=$('.'+clsName).find('option');
						sel_opts.each(function(){//选定旧值
							if($(this).val()==oldVal) $(this).attr('selected',true);
						})
						
						
						$('.'+clsName+' .btn').click(function(){
							if($('.'+clsName+' select').val()=="undefined"){
								$('.'+clsName).remove();
							}
							else{
								var key=$('.'+clsName+' select').val();
								var value=$('.'+clsName+' select').children(":selected").text();
								ajax(key,_this,clsName,value);
							}
						})
						;
					}
				})
		   });
     	}
	});
/*demo
$('#ss').editPlus({type:"select",list:[{'key':001,'value':"张三"},{'key':002,'value':"李四"},{'key':003,'value':"王五"}]})
$('#ss').editPlus({url:'http://www.baidu.com',tag:$('h1'),data:['tid','tname']})
*/
})(jQuery);


// 选项卡--------------------------------------------------------
;(function($){
	$.fn.extend({
		tabCard:function(opts) {
			var def={
				tabs:"li",//选项卡(标签/class)
				cardBox:".cardBox"// 卡片区
			}
			var opts=$.extend(def,opts);
			return this.each(function() {
				var tabIndex=$(this).children(opts.tabs).index();
				$(this).children(opts.tabs).click(function(){
					var tabIndex=$(this).index();
					$(this).addClass('on').siblings().removeClass('on');
					$(opts.cardBox).children().eq(tabIndex).show().siblings().hide();
				})
		   });
     	}
	});
})(jQuery);
$(function(){
	$('.tabList').tabCard();
})



//拖拽-----------------------------------------------------------
;(function($){
	$.fn.extend({
		drag:function() {
			return this.each(function() {
				var _this=$(this);
				var _move=true;//移动标记  
				var _x,_y;//鼠标离控件左上角的相对位置  
				$(this).mousedown(function(e){  
					_move=true;  
					_x=e.pageX-parseInt($(this).position().left);  
					_y=e.pageY-parseInt($(this).position().top);  
					$(this).fadeTo(20, 0.5);//点击后开始拖动并透明显示  
				});
				$(document).mousemove(function(e){ 
					if(_move){  
						var x=e.pageX-_x;//移动时根据鼠标位置计算控件左上角的绝对位置  
						var y=e.pageY-_y; 
						$(_this).css({top:y,left:x});//控件新位置  
					}  
				}).mouseup(function(){  
					_move=false;  
					$(_this).fadeTo("fast", 0.9);//松开鼠标后停止移动并恢复成不透明  
				})
		   });
     	}
	});
	
})(jQuery);



//placeholder----------------------------
;(function($){
	$.fn.extend({
	placeholder: function(opts) {
		var def={
			value:"",//提示信息文本
			top:0,//自定义top显示位置
			ie6Top:"",
			left:0, //自定义left显示位置
			color:"#ccc"//文字颜色
		}
		var opts=$.extend(def,opts);
		return this.each(function() {
			var inputH=$(this).outerHeight();
			var lineH=parseInt($(this).css("line-height"));
			var inputTop=$(this).position().top;
			var inputLeft=$(this).position().left;
			var spanLeft=inputLeft+5;
			if(opts.top!==0)var spanTop=parseInt(opts.top);
			else spanTop=parseInt(inputTop+(inputH-lineH)/2+1);

			$(this).after('<span style="position:absolute;top:'+spanTop+'px;*top:'+opts.ie6Top+';left:'+spanLeft+'px;color:'+opts.color+'; font-size:12px">'+opts.value+'</span>')	
			if($(this).val()!="") $(this).next().hide()
			$(this).focus(function(){$(this).next().hide()});
			$(this).next().click(function(){
				$(this).hide();
				$(this).prev().focus();//弹出框聚焦
				$(this).prev().click();//添加点击事件(JqueryUI弹框)
			})
			$(this).blur(function(){
				if($(this).val()=="") $(this).next().show();
			})
       });
     }
 });
	
})(jQuery);



//-弹出框居中---------------------------------------------------------------------------------
;(function($){
	$.fn.extend({
	popBoxShow: function(opts) {
		var def={
			blackBg:true,//黑色半透明遮罩
			remove:false,//关闭后是否移除弹出框
			cancelBtn:"cancel",//"取消"按钮class
			closeBtn:"close",//"取消"按钮class
			okBtn:"ok",//"确定"按钮class
			fn:function(){return true}//回调函数
		}
		var opts=$.extend(def,opts);
		return this.each(function() {
			var _this=$(this);//removeParent内部要用到
			var topRange= $(window).scrollTop();
			var boxWidth=_this.outerWidth();
			var boxHeight=_this.outerHeight();
			var windowWidth =$(window).width();
			var windowHeight=$(window).height();
			if(boxHeight<windowHeight){//判断弹框高度是否大于浏览器窗口
				var positionTop=parseInt((windowHeight+boxHeight)/2+topRange-boxHeight);
			}
			else{positionTop=30}
			
			//移除mask
			function removeMask(){$(".mask").fadeOut(500,function(){$(this).remove()});	};
			
			//移除或者隐藏popBox
			function removeParent(){
				if(opts.remove==true){
					_this.fadeOut(500,function(){
						_this.remove();
						removeMask()
					});
				}
				else{
					_this.fadeOut();
					removeMask()
				}
			};
			
			//是否显示黑色遮罩mask
			if(opts.blackBg==true){
				 var maskH = $(document).height();
				$(".mask").css({"background":"#000","opacity":"0.5","height":maskH, "width":"100%", "position":"absolute","top":0,"left":0, "z-index":100 });
			};
			//显示弹出框
			_this.fadeIn(500).css({"top":positionTop, "left":parseInt((windowWidth-boxWidth)/2)});
			_this.find('.'+opts.closeBtn+', .'+opts.cancelBtn).click(function(){
				removeParent();
			});
			//点击ok键,是否关闭窗口
			_this.find('.'+opts.okBtn).click(function(){
				if(opts.fn()){removeParent()}
			});
       	});
     }
 });
})(jQuery);



//返回顶部-----------------------------------------------------------------------------------------
$(function(){$("body").append('<div class="backTop hide"></div>')})
;(function($){
	$.fn.extend({
	backTop: function(opts) {
		var def={
			top:600,//滚动条高度
			backSpeed:800//滚动回顶部的速度
		}
		var opts=$.extend(def,opts);
		return this.each(function() {
			if($(window).scrollTop() >= opts.top){
				$('.backTop').fadeIn();
				$('.backTop').click(function(){
					$("html, body").stop().animate({"scrollTop":0}, opts.backSpeed);
				})
			}
			else{$('.backTop').fadeOut()}
       	});
     }
 });
})(jQuery);
$(window).scroll(function(){$('body').backTop()})

//试卷预览slid-----------------------------------------------------------------------------------------
;(function($){
	$.fn.extend({
	testpaperSlider:function(opts){
		var defaults = {
			width:800,
			speed:500,
			next:"#nextBtn",
			prev:"#prevBtn",
			pageCount:".pageCount",
			slidClip:'.slidClip',//缩略图div
			sliderBtnBar:'.sliderBtnBar',//按钮div
			ClipArr:[]//缩略图数组
		}; 
		var opts = $.extend(defaults, opts); 
		var testPaperSize=0;
		var page=0;

		return this.each(function() {
			var _this=$(this);
			testPaperSize=_this.find('li').size();//翻页
			_this.find('ul').css("width",testPaperSize*opts.width);
			  for(var i=0; i<testPaperSize; i++){
				  $(opts.sliderBtnBar).append('<a>'+(i+1)+'</a>');
				  if(opts.ClipArr.length>0){
					  $(opts.slidClip).append('<a><img src="'+opts.ClipArr[i]+'"/></a>')
				  }
			  }
			$(opts.sliderBtnBar+' a:first,'+opts.slidClip+' a:first').addClass('ac');
			function current(){
				_this.find('li').eq(page).addClass('current').siblings().removeClass('current');
				$(opts.sliderBtnBar+' a').eq(page).addClass('ac').siblings().removeClass('ac');
				$(opts.slidClip+' a').eq(page).addClass('ac').siblings().removeClass('ac');
				$(opts.pageCount).text('共'+testPaperSize+'页,第'+(page+1)+'页');
			}
			current();
			$(opts.next).click(function(){//下一页
				if(page<testPaperSize-1){
					page++;
					$(_this.find('ul')).animate({"left":-opts.width*page},opts.speed);
					current();
				}
				else popBox.errorBox('没有啦!')
			});
			
			$(opts.prev).click(function(){//上一页
				if(page>0){
					page--;
					$(_this.find('ul')).animate({"left":-opts.width*page},opts.speed);
					current();
				}
				else popBox.errorBox('已到首页!')
			});
			$(opts.sliderBtnBar+' a,'+opts.slidClip+' a').click(function(){//选择页
				page=$(this).index();
				$(opts.sliderBtnBar+' a:eq('+page+'),'+opts.slidClip+' a:eq('+page+')').addClass('ac').siblings().removeClass('ac');
				$(_this.find('ul')).animate({"left":-opts.width*page},100);
				$(opts.pageCount).text('共'+testPaperSize+'页,第'+(page+1)+'页');
			});
		})
	}
});
})(jQuery);






// jQuery Cycle Plugin for light-weight slideshows
;(function($){
var ver='2.22';
var ie6=$.browser.msie && /MSIE 6.0/.test(navigator.userAgent);
function log(){
    if(window.console && window.console.log)
        window.console.log('[cycle] ' + Array.prototype.join.call(arguments,''));
};
$.fn.cycle=function(options){
    return this.each(function(){
        options=options||{};
        if(options.constructor==String){
            switch(options){
            	case 'stop':if(this.cycleTimeout) clearTimeout(this.cycleTimeout);this.cycleTimeout=0;return;
            	case 'pause':this.cyclePause=1;return;
            	case 'resume':this.cyclePause=0;return;
            	default:options={fx:options};
            };
        }
        if(this.cycleTimeout) clearTimeout(this.cycleTimeout);
        this.cycleTimeout=0;
        this.cyclePause=0;
        var $cont=$(this);
        var $slides=options.slideExpr?$(options.slideExpr,this):$cont.children();
        var els=$slides.get();
        if(els.length<2){log('terminating; too few slides: ' + els.length);return}
        var opts=$.extend({},$.fn.cycle.defaults,options||{},$.metadata?$cont.metadata():$.meta?$cont.data() :{});
        if(opts.autostop)
            opts.countdown=opts.autostopCount||els.length;
        	opts.before=opts.before?[opts.before]:[];
        	opts.after=opts.after?[opts.after]:[];
        	opts.after.unshift(function(){opts.busy=0; });
        if(opts.continuous) opts.after.push(function(){go(els,opts,0,!opts.rev)});
        if(ie6 && opts.cleartype && !opts.cleartypeNoBg)clearTypeFix($slides);
        	var cls=this.className;
        	opts.width=parseInt((cls.match(/w:(\d+)/)||[])[1])||opts.width;
        	opts.height=parseInt((cls.match(/h:(\d+)/)||[])[1])||opts.height;
        	opts.timeout=parseInt((cls.match(/t:(\d+)/)||[])[1])||opts.timeout;
        if($cont.css('position')=='static')
            $cont.css('position','relative');
        if(opts.width)$cont.width(opts.width);
        if(opts.height && opts.height!='auto')$cont.height(opts.height);
        if(opts.random){
            opts.randomMap=[];
            for (var i=0; i<els.length; i++){
				opts.randomMap.push(i);
				opts.randomMap.sort(function(a,b){return Math.random()-0.5});
				opts.randomIndex=0;
				opts.startingSlide=opts.randomMap[0];
			}
        }
        else if(opts.startingSlide>=els.length)
            opts.startingSlide=0;
        	var first=opts.startingSlide||0;
        	$slides.css({position: 'absolute',top:0,left:0}).hide().each(function(i){
            var z=first?i>=first?els.length - (i-first):first-i:els.length-i;
            $(this).css('z-index',z)
        });
        $(els[first]).css('opacity',1).show(); 
        if($.browser.msie) els[first].style.removeAttribute('filter');
        if(opts.fit && opts.width)$slides.width(opts.width);
        if(opts.fit && opts.height && opts.height!='auto')$slides.height(opts.height);
        if(opts.pause)
            $cont.hover(function(){this.cyclePause=1},function(){this.cyclePause=0});
        var init=$.fn.cycle.transitions[opts.fx];
        if($.isFunction(init))init($cont,$slides,opts);
        else if(opts.fx!='custom')log('unknown transition:'+opts.fx);
        $slides.each(function(){
            var $el=$(this);
            this.cycleH=(opts.fit && opts.height)?opts.height:$el.height();
            this.cycleW=(opts.fit && opts.width)?opts.width:$el.width();
        });
        opts.cssBefore=opts.cssBefore||{};
        opts.animIn=opts.animIn||{};
        opts.animOut=opts.animOut||{};
        $slides.not(':eq('+first+')').css(opts.cssBefore);
        if(opts.cssFirst) $($slides[first]).css(opts.cssFirst);
        if(opts.timeout){
            if(opts.speed.constructor==String)opts.speed={slow:600,fast:200}[opts.speed]||400;
            if(!opts.sync)opts.speed=opts.speed/2;
            while((opts.timeout-opts.speed)<250)opts.timeout+=opts.speed;
        }
        if(opts.easing)opts.easeIn=opts.easeOut=opts.easing;
        if(!opts.speedIn)opts.speedIn=opts.speed;
        if(!opts.speedOut)opts.speedOut=opts.speed;
 		opts.slideCount=els.length;
        opts.currSlide=first;
        if(opts.random){
            opts.nextSlide=opts.currSlide;
            if(++opts.randomIndex==els.length)
                opts.randomIndex=0;
            	opts.nextSlide=opts.randomMap[opts.randomIndex];
        }
        else opts.nextSlide=opts.startingSlide>=(els.length-1)?0:opts.startingSlide+1;
        var e0=$slides[first];
        if(opts.before.length)opts.before[0].apply(e0,[e0,e0,opts,true]);
        if(opts.after.length>1)opts.after[1].apply(e0,[e0,e0,opts,true]);
        if(opts.click && !opts.next)opts.next=opts.click;
        if(opts.next) $(opts.next).bind('click',function(){return advance(els,opts,opts.rev?-1:1)});
        if(opts.prev) $(opts.prev).bind('click',function(){return advance(els,opts,opts.rev?1:-1)});
        if(opts.pager) buildPager(els,opts);
        opts.addSlide=function(newSlide){
            var $s=$(newSlide),s=$s[0];
            if(!opts.autostopCount)
                opts.countdown++;
            	els.push(s);
            if(opts.els)
				opts.els.push(s);
            	opts.slideCount=els.length;
            	$s.css('position','absolute').appendTo($cont);
            if(ie6 && opts.cleartype && !opts.cleartypeNoBg) clearTypeFix($s);
            if(opts.fit && opts.width) $s.width(opts.width);
            if(opts.fit && opts.height && opts.height!='auto')
                $slides.height(opts.height);
            s.cycleH=(opts.fit && opts.height)?opts.height:$s.height();
            s.cycleW=(opts.fit && opts.width)?opts.width:$s.width();
            $s.css(opts.cssBefore);
            if(typeof opts.onAddSlide=='function')opts.onAddSlide($s);
        };
        if(opts.timeout||opts.continuous)
            this.cycleTimeout=setTimeout(
                function(){go(els,opts,0,!opts.rev)},
                opts.continuous?10:opts.timeout+(opts.delay||0));
    });
};
function go(els,opts,manual,fwd){
    if(opts.busy) return;
    var p=els[0].parentNode,curr=els[opts.currSlide],next=els[opts.nextSlide];
    if(p.cycleTimeout===0 && !manual) return;
    if(!manual && !p.cyclePause &&
        ((opts.autostop && (--opts.countdown<=0))||
        (opts.nowrap && !opts.random && opts.nextSlide<opts.currSlide))){
        if(opts.end)opts.end(opts);return;
    }
    if(manual||!p.cyclePause){
        if(opts.before.length)
            $.each(opts.before,function(i,o){o.apply(next,[curr,next,opts,fwd])});
        var after=function(){
            if($.browser.msie && opts.cleartype) this.style.removeAttribute('filter');
            $.each(opts.after,function(i,o){o.apply(next,[curr,next,opts,fwd])});
        };
        if(opts.nextSlide!=opts.currSlide){
            opts.busy=1;
            if(opts.fxFn) opts.fxFn(curr,next,opts,after,fwd);
            else if($.isFunction($.fn.cycle[opts.fx])) $.fn.cycle[opts.fx](curr,next,opts,after);
            else $.fn.cycle.custom(curr,next,opts,after);
        }
        if(opts.random){
            opts.currSlide=opts.nextSlide;
            if(++opts.randomIndex==els.length)
                opts.randomIndex=0;
            opts.nextSlide=opts.randomMap[opts.randomIndex];
        }
        else{// sequence
            var roll=(opts.nextSlide+1)==els.length;
            opts.nextSlide=roll?0:opts.nextSlide+1;
            opts.currSlide=roll?els.length-1:opts.nextSlide-1;
        }
        if(opts.pager) $.fn.cycle.updateActivePagerLink(opts.pager,opts.currSlide);
    }
    if(opts.timeout && !opts.continuous)
        p.cycleTimeout=setTimeout(function(){go(els,opts,0,!opts.rev)},opts.timeout);
    else if(opts.continuous && p.cyclePause)
        p.cycleTimeout=setTimeout(function(){go(els,opts,0,!opts.rev)},10);
};

$.fn.cycle.updateActivePagerLink=function(pager,currSlide){
    $(pager).find('a').removeClass('activeSlide').filter('a:eq('+currSlide+')').addClass('activeSlide');
};

// advance slide forward or back
function advance(els,opts,val) {
    var p=els[0].parentNode,timeout=p.cycleTimeout;
    if (timeout) {
        clearTimeout(timeout);
        p.cycleTimeout=0;
    }
    opts.nextSlide=opts.currSlide+val;
    if(opts.nextSlide<0) {
        if(opts.nowrap) return false;
        opts.nextSlide=els.length-1;
    }
    else if(opts.nextSlide>=els.length){
        if(opts.nowrap) return false;
        opts.nextSlide=0;
    }
    if(opts.prevNextClick && typeof opts.prevNextClick=='function')
        opts.prevNextClick(val>0, opts.nextSlide, els[opts.nextSlide]);
    go(els,opts,1,val>=0);
    return false;
};
function buildPager(els,opts){
    var $p=$(opts.pager);
    $.each(els,function(i,o){
        var $a=(typeof opts.pagerAnchorBuilder=='function')
           ?$(opts.pagerAnchorBuilder(i,o))
           :opts.pagerNum==false?$('<a href="#"></a>'):$('<a href="#">'+(i+1)+'</a>');//显示数字
        if($a.parents('body').length==0)$a.appendTo($p);
        $a.bind(opts.pagerEvent,function(){
            opts.nextSlide=i;
            var p=els[0].parentNode,timeout=p.cycleTimeout;
            if(timeout){
                clearTimeout(timeout);
                p.cycleTimeout=0;
            }
            if(typeof opts.pagerClick=='function')
                opts.pagerClick(opts.nextSlide,els[opts.nextSlide]);
            go(els,opts,1,!opts.rev);
            return false;
        });
    });
   $.fn.cycle.updateActivePagerLink(opts.pager,opts.startingSlide);
};

function clearTypeFix($slides){
    function hex(s){
        var s=parseInt(s).toString(16);
        return s.length<2?'0'+s:s;
    };
    function getBg(e){
        for (;e && e.nodeName.toLowerCase()!='html';e=e.parentNode){
            var v=$.css(e,'background-color');
            if(v.indexOf('rgb')>=0){
                var rgb=v.match(/\d+/g);
                return '#'+hex(rgb[0])+hex(rgb[1])+hex(rgb[2]);
            }
            if(v && v !='transparent') return v;
        }
        return '#fff';
    };
    $slides.each(function(){$(this).css('background-color',getBg(this)) });
};


$.fn.cycle.custom=function(curr,next,opts,cb){
    var $l=$(curr),$n=$(next);
    $n.css(opts.cssBefore);
    var fn=function(){$n.animate(opts.animIn,opts.speedIn,opts.easeIn,cb)};
    $l.animate(opts.animOut,opts.speedOut,opts.easeOut,function(){
        if(opts.cssAfter) $l.css(opts.cssAfter);
        if(!opts.sync) fn();
    });
    if(opts.sync) fn();
};

$.fn.cycle.transitions={
    fade: function($cont,$slides,opts){
        $slides.not(':eq('+opts.startingSlide+')').css('opacity',0);
        opts.before.push(function(){$(this).show()});
        opts.animIn={opacity:1};
        opts.animOut={opacity:0};
        opts.cssBefore={opacity:0};
        opts.cssAfter={display:'none'};
    }
};

$.fn.cycle.ver=function(){return ver};
// override these globally ifyou like (they are all optional)
$.fn.cycle.defaults={
    fx:           'scollLeft',// one of: fade,scrollLeft,scrollRight,scrollDown
    timeout:       4000, // milliseconds between slide transitions (0 to disable auto advance)
    continuous:    0,    // true to start next transition immediately after current one completes
    speed:         800, // speed of the transition (any valid fx speed value)
    speedIn:       null, // speed of the 'in' transition
    speedOut:      null, // speed of the 'out' transition
    next:          null, // id of element to use as click trigger for next slide
    prev:          null, // id of element to use as click trigger for previous slide
    prevNextClick: null, // callback fn for prev/next clicks:  function(isNext,zeroBasedSlideIndex,slideElement)
    pager:         null, // id of element to use as pager container
    pagerClick:    null, // callback fn for pager clicks:  function(zeroBasedSlideIndex,slideElement)
    pagerEvent:   'click',// event which drives the pager navigation
	pagerNum:      false,  //是否显示分页数字
    pagerAnchorBuilder: null,// callback fn for building anchor links
    before:        null, // transition callback (scope set to element to be shown)
    after:         null, // transition callback (scope set to element that was shown)
    end:           null, // callback invoked when the slideshow terminates (use with autostop or nowrap options)
    easing:        null, // easing method for both in and out transitions
    easeIn:        null, // easing for "in" transition
    easeOut:       null, // easing for "out" transition
    shuffle:       null, // coords for shuffle animation,ex:{top:15,left: 200 }
    animIn:        null, // properties that define how the slide animates in
    animOut:       null, // properties that define how the slide animates out
    cssBefore:     null, // properties that define the initial state of the slide before transitioning in
    cssAfter:      null, // properties that defined the state of the slide after transitioning out
    fxFn:          null, // function used to control the transition
    height:       'auto',// container height
    startingSlide: 0,    // zero-based index of the first slide to be displayed
    sync:          1,    // true ifin/out transitions should occur simultaneously
    random:        0,    // 随机顺序
    fit:           0,    // force slides to fit container
    pause:         1,    // true to enable "pause on hover"
    autostop:      0,    // true to end slideshow after X transitions (where X==slide count)
    autostopCount: 0,    // number of transitions (optionally used with autostop to define X)
    delay:         0,    // 延时开始(单位:ms)
    slideExpr:     null, // expression for selecting slides (ifsomething other than all children is required)
    cleartype:     0,    // true ifclearType corrections should be applied (for IE)
    nowrap:        0      // true to prevent slideshow from wrapping
};


$.fn.cycle.transitions.scrollLeft=function($cont,$slides,opts){
    $cont.css('overflow','hidden');
    opts.before.push(function(curr,next,opts){
        $(this).show();
        opts.cssBefore.left=next.offsetWidth;
        opts.animOut.left=0-curr.offsetWidth;
    });
    opts.cssFirst={left:0};
    opts.animIn={left:0};
};
$.fn.cycle.transitions.scrollRight=function($cont,$slides,opts){
    $cont.css('overflow','hidden');
    opts.before.push(function(curr,next,opts){
        $(this).show();
        opts.cssBefore.left=0-next.offsetWidth;
        opts.animOut.left=curr.offsetWidth;
    });
    opts.cssFirst={left:0};
    opts.animIn={left:0};
};

$.fn.cycle.transitions.scrollDown=function($cont,$slides,opts){
    $cont.css('overflow','hidden');
    opts.before.push(function(curr,next,opts){
        $(this).show();
        opts.cssBefore.top=0-next.offsetHeight;
        opts.animOut.top=curr.offsetHeight;
    });
    opts.cssFirst={left:0};
    opts.animIn={left:0};
};
})(jQuery);



//actual 读取隐藏元素的宽高属性------------------------------------------------------------
/*$( '.hidden' ).actual( 'width' );
 
// get hidden element actual innerWidth
$( '.hidden' ).actual( 'innerWidth' );
 
// get hidden element actual outerWidth
$( '.hidden' ).actual( 'outerWidth' );
 
// get hidden element actual outerWidth and set the `includeMargin` argument
$( '.hidden' ).actual( 'outerWidth', { includeMargin : true });
 
// get hidden element actual height
$( '.hidden' ).actual( 'height' );
 
// get hidden element actual innerHeight
$( '.hidden' ).actual( 'innerHeight' );
 
// get hidden element actual outerHeight
$( '.hidden' ).actual( 'outerHeight' );
 
// get hidden element actual outerHeight and set the `includeMargin` argument
$( '.hidden' ).actual( 'outerHeight', { includeMargin : true });
 
// if the page jumps or blinks, pass a attribute '{ absolute : true }'
// be very careful, you might get a wrong result depends on how you makrup your html and css
$( '.hidden' ).actual( 'height', { absolute : true });
 
// if you use css3pie with a float element 
// for example a rounded corner navigation menu you can also try to pass a attribute '{ clone : true }'
// please see demo/css3pie in action
$( '.hidden' ).actual( 'width', { clone : true });*/

;(function(a){a.fn.addBack=a.fn.addBack||a.fn.andSelf;
a.fn.extend({actual:function(b,l){if(!this[b]){throw'$.actual => The jQuery method "'+b+'" you called does not exist';}var f={absolute:false,clone:false,includeMargin:false};
var i=a.extend(f,l);var e=this.eq(0);var h,j;if(i.clone===true){h=function(){var m="position: absolute !important; top: -1000 !important; ";e=e.clone().attr("style",m).appendTo("body");
};j=function(){e.remove();};}else{var g=[];var d="";var c;h=function(){c=e.parents().addBack().filter(":hidden");d+="visibility: hidden !important; display: block !important; ";
if(i.absolute===true){d+="position: absolute !important; ";}c.each(function(){var m=a(this);var n=m.attr("style");g.push(n);m.attr("style",n?n+";"+d:d);
});};j=function(){c.each(function(m){var o=a(this);var n=g[m];if(n===undefined){o.removeAttr("style");}else{o.attr("style",n);}});};}h();var k=/(outer)/.test(b)?e[b](i.includeMargin):e[b]();
j();return k;}});})(jQuery);


//jQuery Easing v1.3 -------------------------------------------------------------------------
jQuery.easing.jswing=jQuery.easing.swing;jQuery.extend(jQuery.easing,{def:"easeOutQuad",swing:function(e,f,a,h,g){return jQuery.easing[jQuery.easing.def](e,f,a,h,g)},easeInQuad:function(e,f,a,h,g){return h*(f/=g)*f+a},easeOutQuad:function(e,f,a,h,g){return -h*(f/=g)*(f-2)+a},easeInOutQuad:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f+a}return -h/2*((--f)*(f-2)-1)+a},easeInCubic:function(e,f,a,h,g){return h*(f/=g)*f*f+a},easeOutCubic:function(e,f,a,h,g){return h*((f=f/g-1)*f*f+1)+a},easeInOutCubic:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f+a}return h/2*((f-=2)*f*f+2)+a},easeInQuart:function(e,f,a,h,g){return h*(f/=g)*f*f*f+a},easeOutQuart:function(e,f,a,h,g){return -h*((f=f/g-1)*f*f*f-1)+a},easeInOutQuart:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f*f+a}return -h/2*((f-=2)*f*f*f-2)+a},easeInQuint:function(e,f,a,h,g){return h*(f/=g)*f*f*f*f+a},easeOutQuint:function(e,f,a,h,g){return h*((f=f/g-1)*f*f*f*f+1)+a},easeInOutQuint:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f*f*f+a}return h/2*((f-=2)*f*f*f*f+2)+a},easeInSine:function(e,f,a,h,g){return -h*Math.cos(f/g*(Math.PI/2))+h+a},easeOutSine:function(e,f,a,h,g){return h*Math.sin(f/g*(Math.PI/2))+a},easeInOutSine:function(e,f,a,h,g){return -h/2*(Math.cos(Math.PI*f/g)-1)+a},easeInExpo:function(e,f,a,h,g){return(f==0)?a:h*Math.pow(2,10*(f/g-1))+a},easeOutExpo:function(e,f,a,h,g){return(f==g)?a+h:h*(-Math.pow(2,-10*f/g)+1)+a},easeInOutExpo:function(e,f,a,h,g){if(f==0){return a}if(f==g){return a+h}if((f/=g/2)<1){return h/2*Math.pow(2,10*(f-1))+a}return h/2*(-Math.pow(2,-10*--f)+2)+a},easeInCirc:function(e,f,a,h,g){return -h*(Math.sqrt(1-(f/=g)*f)-1)+a},easeOutCirc:function(e,f,a,h,g){return h*Math.sqrt(1-(f=f/g-1)*f)+a},easeInOutCirc:function(e,f,a,h,g){if((f/=g/2)<1){return -h/2*(Math.sqrt(1-f*f)-1)+a}return h/2*(Math.sqrt(1-(f-=2)*f)+1)+a},easeInElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k)==1){return e+l}if(!j){j=k*0.3}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}return -(g*Math.pow(2,10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j))+e},easeOutElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k)==1){return e+l}if(!j){j=k*0.3}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}return g*Math.pow(2,-10*h)*Math.sin((h*k-i)*(2*Math.PI)/j)+l+e},easeInOutElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k/2)==2){return e+l}if(!j){j=k*(0.3*1.5)}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}if(h<1){return -0.5*(g*Math.pow(2,10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j))+e}return g*Math.pow(2,-10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j)*0.5+l+e},easeInBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}return i*(f/=h)*f*((g+1)*f-g)+a},easeOutBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}return i*((f=f/h-1)*f*((g+1)*f+g)+1)+a},easeInOutBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}if((f/=h/2)<1){return i/2*(f*f*(((g*=(1.525))+1)*f-g))+a}return i/2*((f-=2)*f*(((g*=(1.525))+1)*f+g)+2)+a},easeInBounce:function(e,f,a,h,g){return h-jQuery.easing.easeOutBounce(e,g-f,0,h,g)+a},easeOutBounce:function(e,f,a,h,g){if((f/=g)<(1/2.75)){return h*(7.5625*f*f)+a}else{if(f<(2/2.75)){return h*(7.5625*(f-=(1.5/2.75))*f+0.75)+a}else{if(f<(2.5/2.75)){return h*(7.5625*(f-=(2.25/2.75))*f+0.9375)+a}else{return h*(7.5625*(f-=(2.625/2.75))*f+0.984375)+a}}}},easeInOutBounce:function(e,f,a,h,g){if(f<g/2){return jQuery.easing.easeInBounce(e,f*2,0,h,g)*0.5+a}return jQuery.easing.easeOutBounce(e,f*2-g,0,h,g)*0.5+h*0.5+a}});


//上传头像裁切图片---------------------------------------------------------------
;(function($){$.Jcrop=function(obj,opt){var obj=obj,opt=opt;if(typeof(obj)!=="object"){obj=$(obj)[0]}if(typeof(opt)!=="object"){opt={}}if(!("trackDocument" in opt)){opt.trackDocument=$.browser.msie?false:true;if($.browser.msie&&$.browser.version.split(".")[0]=="8"){opt.trackDocument=true}}if(!("keySupport" in opt)){opt.keySupport=$.browser.msie?false:true}var defaults={trackDocument:false,baseClass:"jcrop",addClass:null,bgColor:"black",bgOpacity:0.6,borderOpacity:0.4,handleOpacity:0.5,handlePad:5,handleSize:9,handleOffset:5,edgeMargin:14,aspectRatio:0,keySupport:true,cornerHandles:true,sideHandles:true,drawBorders:true,dragEdges:true,boxWidth:0,boxHeight:0,boundary:8,animationDelay:20,swingSpeed:3,allowSelect:true,allowMove:true,allowResize:true,minSelect:[0,0],maxSize:[0,0],minSize:[0,0],onChange:function(){},onSelect:function(){}};var options=defaults;setOptions(opt);var $origimg=$(obj);var $img=$origimg.clone().removeAttr("id").css({position:"absolute"});$img.width($origimg.width());$img.height($origimg.height());$origimg.after($img).hide();presize($img,options.boxWidth,options.boxHeight);var boundx=$img.width(),boundy=$img.height(),$div=$("<div />").width(boundx).height(boundy).addClass(cssClass("holder")).css({position:"relative",backgroundColor:options.bgColor}).insertAfter($origimg).append($img);if(options.addClass){$div.addClass(options.addClass)}var $img2=$("<img />").attr("src",$img.attr("src")).css("position","absolute").width(boundx).height(boundy);var $img_holder=$("<div />").width(pct(100)).height(pct(100)).css({zIndex:310,position:"absolute",overflow:"hidden"}).append($img2);var $hdl_holder=$("<div />").width(pct(100)).height(pct(100)).css("zIndex",320);var $sel=$("<div />").css({position:"absolute",zIndex:300}).insertBefore($img).append($img_holder,$hdl_holder);var bound=options.boundary;var $trk=newTracker().width(boundx+(bound*2)).height(boundy+(bound*2)).css({position:"absolute",top:px(-bound),left:px(-bound),zIndex:290}).mousedown(newSelection);var xlimit,ylimit,xmin,ymin;var xscale,yscale,enabled=true;var docOffset=getPos($img),btndown,lastcurs,dimmed,animating,shift_down;var Coords=function(){var x1=0,y1=0,x2=0,y2=0,ox,oy;function setPressed(pos){var pos=rebound(pos);x2=x1=pos[0];y2=y1=pos[1]}function setCurrent(pos){var pos=rebound(pos);ox=pos[0]-x2;oy=pos[1]-y2;x2=pos[0];y2=pos[1]}function getOffset(){return[ox,oy]}function moveOffset(offset){var ox=offset[0],oy=offset[1];if(0>x1+ox){ox-=ox+x1}if(0>y1+oy){oy-=oy+y1}if(boundy<y2+oy){oy+=boundy-(y2+oy)}if(boundx<x2+ox){ox+=boundx-(x2+ox)}x1+=ox;x2+=ox;y1+=oy;y2+=oy}function getCorner(ord){var c=getFixed();switch(ord){case"ne":return[c.x2,c.y];case"nw":return[c.x,c.y];case"se":return[c.x2,c.y2];case"sw":return[c.x,c.y2]}}function getFixed(){if(!options.aspectRatio){return getRect()}var aspect=options.aspectRatio,min_x=options.minSize[0]/xscale,min_y=options.minSize[1]/yscale,max_x=options.maxSize[0]/xscale,max_y=options.maxSize[1]/yscale,rw=x2-x1,rh=y2-y1,rwa=Math.abs(rw),rha=Math.abs(rh),real_ratio=rwa/rha,xx,yy;if(max_x==0){max_x=boundx*10}if(max_y==0){max_y=boundy*10}if(real_ratio<aspect){yy=y2;w=rha*aspect;xx=rw<0?x1-w:w+x1;if(xx<0){xx=0;h=Math.abs((xx-x1)/aspect);yy=rh<0?y1-h:h+y1}else{if(xx>boundx){xx=boundx;h=Math.abs((xx-x1)/aspect);yy=rh<0?y1-h:h+y1}}}else{xx=x2;h=rwa/aspect;yy=rh<0?y1-h:y1+h;if(yy<0){yy=0;w=Math.abs((yy-y1)*aspect);xx=rw<0?x1-w:w+x1}else{if(yy>boundy){yy=boundy;w=Math.abs(yy-y1)*aspect;xx=rw<0?x1-w:w+x1}}}if(xx>x1){if(xx-x1<min_x){xx=x1+min_x}else{if(xx-x1>max_x){xx=x1+max_x}}if(yy>y1){yy=y1+(xx-x1)/aspect}else{yy=y1-(xx-x1)/aspect}}else{if(xx<x1){if(x1-xx<min_x){xx=x1-min_x}else{if(x1-xx>max_x){xx=x1-max_x}}if(yy>y1){yy=y1+(x1-xx)/aspect}else{yy=y1-(x1-xx)/aspect}}}if(xx<0){x1-=xx;xx=0}else{if(xx>boundx){x1-=xx-boundx;xx=boundx}}if(yy<0){y1-=yy;yy=0}else{if(yy>boundy){y1-=yy-boundy;yy=boundy}}return last=makeObj(flipCoords(x1,y1,xx,yy))}function rebound(p){if(p[0]<0){p[0]=0}if(p[1]<0){p[1]=0}if(p[0]>boundx){p[0]=boundx}if(p[1]>boundy){p[1]=boundy}return[p[0],p[1]]}function flipCoords(x1,y1,x2,y2){var xa=x1,xb=x2,ya=y1,yb=y2;if(x2<x1){xa=x2;xb=x1}if(y2<y1){ya=y2;yb=y1}return[Math.round(xa),Math.round(ya),Math.round(xb),Math.round(yb)]}function getRect(){var xsize=x2-x1;var ysize=y2-y1;if(xlimit&&(Math.abs(xsize)>xlimit)){x2=(xsize>0)?(x1+xlimit):(x1-xlimit)}if(ylimit&&(Math.abs(ysize)>ylimit)){y2=(ysize>0)?(y1+ylimit):(y1-ylimit)}if(ymin&&(Math.abs(ysize)<ymin)){y2=(ysize>0)?(y1+ymin):(y1-ymin)}if(xmin&&(Math.abs(xsize)<xmin)){x2=(xsize>0)?(x1+xmin):(x1-xmin)}if(x1<0){x2-=x1;x1-=x1}if(y1<0){y2-=y1;y1-=y1}if(x2<0){x1-=x2;x2-=x2}if(y2<0){y1-=y2;y2-=y2}if(x2>boundx){var delta=x2-boundx;x1-=delta;x2-=delta}if(y2>boundy){var delta=y2-boundy;y1-=delta;y2-=delta}if(x1>boundx){var delta=x1-boundy;y2-=delta;y1-=delta}if(y1>boundy){var delta=y1-boundy;y2-=delta;y1-=delta}return makeObj(flipCoords(x1,y1,x2,y2))}function makeObj(a){return{x:a[0],y:a[1],x2:a[2],y2:a[3],w:a[2]-a[0],h:a[3]-a[1]}}return{flipCoords:flipCoords,setPressed:setPressed,setCurrent:setCurrent,getOffset:getOffset,moveOffset:moveOffset,getCorner:getCorner,getFixed:getFixed}}();var Selection=function(){var start,end,dragmode,awake,hdep=370;var borders={};var handle={};var seehandles=false;var hhs=options.handleOffset;if(options.drawBorders){borders={top:insertBorder("hline").css("top",$.browser.msie?px(-1):px(0)),bottom:insertBorder("hline"),left:insertBorder("vline"),right:insertBorder("vline")}}if(options.dragEdges){handle.t=insertDragbar("n");handle.b=insertDragbar("s");handle.r=insertDragbar("e");handle.l=insertDragbar("w")}options.sideHandles&&createHandles(["n","s","e","w"]);options.cornerHandles&&createHandles(["sw","nw","ne","se"]);function insertBorder(type){var jq=$("<div />").css({position:"absolute",opacity:options.borderOpacity}).addClass(cssClass(type));$img_holder.append(jq);return jq}function dragDiv(ord,zi){var jq=$("<div />").mousedown(createDragger(ord)).css({cursor:ord+"-resize",position:"absolute",zIndex:zi});$hdl_holder.append(jq);return jq}function insertHandle(ord){return dragDiv(ord,hdep++).css({top:px(-hhs+1),left:px(-hhs+1),opacity:options.handleOpacity}).addClass(cssClass("handle"))}function insertDragbar(ord){var s=options.handleSize,o=hhs,h=s,w=s,t=o,l=o;switch(ord){case"n":case"s":w=pct(100);break;case"e":case"w":h=pct(100);break}return dragDiv(ord,hdep++).width(w).height(h).css({top:px(-t+1),left:px(-l+1)})}function createHandles(li){for(i in li){handle[li[i]]=insertHandle(li[i])}}function moveHandles(c){var midvert=Math.round((c.h/2)-hhs),midhoriz=Math.round((c.w/2)-hhs),north=west=-hhs+1,east=c.w-hhs,south=c.h-hhs,x,y;"e" in handle&&handle.e.css({top:px(midvert),left:px(east)})&&handle.w.css({top:px(midvert)})&&handle.s.css({top:px(south),left:px(midhoriz)})&&handle.n.css({left:px(midhoriz)});"ne" in handle&&handle.ne.css({left:px(east)})&&handle.se.css({top:px(south),left:px(east)})&&handle.sw.css({top:px(south)});"b" in handle&&handle.b.css({top:px(south)})&&handle.r.css({left:px(east)})}function moveto(x,y){$img2.css({top:px(-y),left:px(-x)});$sel.css({top:px(y),left:px(x)})}function resize(w,h){$sel.width(w).height(h)}function refresh(){var c=Coords.getFixed();Coords.setPressed([c.x,c.y]);Coords.setCurrent([c.x2,c.y2]);updateVisible()}function updateVisible(){if(awake){return update()}}function update(){var c=Coords.getFixed();resize(c.w,c.h);moveto(c.x,c.y);options.drawBorders&&borders["right"].css({left:px(c.w-1)})&&borders["bottom"].css({top:px(c.h-1)});seehandles&&moveHandles(c);awake||show();options.onChange(unscale(c))}function show(){$sel.show();$img.css("opacity",options.bgOpacity);awake=true}function release(){disableHandles();$sel.hide();$img.css("opacity",1);awake=false}function showHandles(){if(seehandles){moveHandles(Coords.getFixed());$hdl_holder.show()}}function enableHandles(){seehandles=true;if(options.allowResize){moveHandles(Coords.getFixed());$hdl_holder.show();return true}}function disableHandles(){seehandles=false;$hdl_holder.hide()}function animMode(v){(animating=v)?disableHandles():enableHandles()}function done(){animMode(false);refresh()}var $track=newTracker().mousedown(createDragger("move")).css({cursor:"move",position:"absolute",zIndex:360});$img_holder.append($track);disableHandles();return{updateVisible:updateVisible,update:update,release:release,refresh:refresh,setCursor:function(cursor){$track.css("cursor",cursor)},enableHandles:enableHandles,enableOnly:function(){seehandles=true},showHandles:showHandles,disableHandles:disableHandles,animMode:animMode,done:done}}();var Tracker=function(){var onMove=function(){},onDone=function(){},trackDoc=options.trackDocument;if(!trackDoc){$trk.mousemove(trackMove).mouseup(trackUp).mouseout(trackUp)}function toFront(){$trk.css({zIndex:450});if(trackDoc){$(document).mousemove(trackMove).mouseup(trackUp)}}function toBack(){$trk.css({zIndex:290});if(trackDoc){$(document).unbind("mousemove",trackMove).unbind("mouseup",trackUp)}}function trackMove(e){onMove(mouseAbs(e))}function trackUp(e){e.preventDefault();e.stopPropagation();if(btndown){btndown=false;onDone(mouseAbs(e));options.onSelect(unscale(Coords.getFixed()));toBack();onMove=function(){};onDone=function(){}}return false}function activateHandlers(move,done){btndown=true;onMove=move;onDone=done;toFront();return false}function setCursor(t){$trk.css("cursor",t)}$img.before($trk);return{activateHandlers:activateHandlers,setCursor:setCursor}}();var KeyManager=function(){var $keymgr=$('<input type="radio" />').css({position:"absolute",left:"-30px"}).keypress(parseKey).blur(onBlur),$keywrap=$("<div />").css({position:"absolute",overflow:"hidden"}).append($keymgr);function watchKeys(){if(options.keySupport){$keymgr.show();$keymgr.focus()}}function onBlur(e){$keymgr.hide()}function doNudge(e,x,y){if(options.allowMove){Coords.moveOffset([x,y]);Selection.updateVisible()}e.preventDefault();e.stopPropagation()}function parseKey(e){if(e.ctrlKey){return true}shift_down=e.shiftKey?true:false;var nudge=shift_down?10:1;switch(e.keyCode){case 37:doNudge(e,-nudge,0);break;case 39:doNudge(e,nudge,0);break;case 38:doNudge(e,0,-nudge);break;case 40:doNudge(e,0,nudge);break;case 27:Selection.release();break;case 9:return true}return nothing(e)}if(options.keySupport){$keywrap.insertBefore($img)}return{watchKeys:watchKeys}}();function px(n){return""+parseInt(n)+"px"}function pct(n){return""+parseInt(n)+"%"}function cssClass(cl){return options.baseClass+"-"+cl}function getPos(obj){var pos=$(obj).offset();return[pos.left,pos.top]}function mouseAbs(e){return[(e.pageX-docOffset[0]),(e.pageY-docOffset[1])]}function myCursor(type){if(type!=lastcurs){Tracker.setCursor(type);lastcurs=type}}function startDragMode(mode,pos){docOffset=getPos($img);Tracker.setCursor(mode=="move"?mode:mode+"-resize");if(mode=="move"){return Tracker.activateHandlers(createMover(pos),doneSelect)}var fc=Coords.getFixed();var opp=oppLockCorner(mode);var opc=Coords.getCorner(oppLockCorner(opp));Coords.setPressed(Coords.getCorner(opp));Coords.setCurrent(opc);Tracker.activateHandlers(dragmodeHandler(mode,fc),doneSelect)}function dragmodeHandler(mode,f){return function(pos){if(!options.aspectRatio){switch(mode){case"e":pos[1]=f.y2;break;case"w":pos[1]=f.y2;break;case"n":pos[0]=f.x2;break;case"s":pos[0]=f.x2;break}}else{switch(mode){case"e":pos[1]=f.y+1;break;case"w":pos[1]=f.y+1;break;case"n":pos[0]=f.x+1;break;case"s":pos[0]=f.x+1;break}}Coords.setCurrent(pos);Selection.update()}}function createMover(pos){var lloc=pos;KeyManager.watchKeys();return function(pos){Coords.moveOffset([pos[0]-lloc[0],pos[1]-lloc[1]]);lloc=pos;Selection.update()}}function oppLockCorner(ord){switch(ord){case"n":return"sw";case"s":return"nw";case"e":return"nw";case"w":return"ne";case"ne":return"sw";case"nw":return"se";case"se":return"nw";case"sw":return"ne"}}function createDragger(ord){return function(e){if(options.disabled){return false}if((ord=="move")&&!options.allowMove){return false}btndown=true;startDragMode(ord,mouseAbs(e));e.stopPropagation();e.preventDefault();return false}}function presize($obj,w,h){var nw=$obj.width(),nh=$obj.height();if((nw>w)&&w>0){nw=w;nh=(w/$obj.width())*$obj.height()}if((nh>h)&&h>0){nh=h;nw=(h/$obj.height())*$obj.width()}xscale=$obj.width()/nw;yscale=$obj.height()/nh;$obj.width(nw).height(nh)}function unscale(c){return{x:parseInt(c.x*xscale),y:parseInt(c.y*yscale),x2:parseInt(c.x2*xscale),y2:parseInt(c.y2*yscale),w:parseInt(c.w*xscale),h:parseInt(c.h*yscale)}}function doneSelect(pos){var c=Coords.getFixed();if(c.w>options.minSelect[0]&&c.h>options.minSelect[1]){Selection.enableHandles();Selection.done()}else{Selection.release()}Tracker.setCursor(options.allowSelect?"crosshair":"default")}function newSelection(e){if(options.disabled){return false}if(!options.allowSelect){return false}btndown=true;docOffset=getPos($img);Selection.disableHandles();myCursor("crosshair");var pos=mouseAbs(e);Coords.setPressed(pos);Tracker.activateHandlers(selectDrag,doneSelect);KeyManager.watchKeys();Selection.update();e.stopPropagation();e.preventDefault();return false}function selectDrag(pos){Coords.setCurrent(pos);Selection.update()}function newTracker(){var trk=$("<div></div>").addClass(cssClass("tracker"));$.browser.msie&&trk.css({opacity:0,backgroundColor:"white"});return trk}function animateTo(a){var x1=a[0]/xscale,y1=a[1]/yscale,x2=a[2]/xscale,y2=a[3]/yscale;if(animating){return}var animto=Coords.flipCoords(x1,y1,x2,y2);var c=Coords.getFixed();var animat=initcr=[c.x,c.y,c.x2,c.y2];var interv=options.animationDelay;var x=animat[0];var y=animat[1];var x2=animat[2];var y2=animat[3];var ix1=animto[0]-initcr[0];var iy1=animto[1]-initcr[1];var ix2=animto[2]-initcr[2];var iy2=animto[3]-initcr[3];var pcent=0;var velocity=options.swingSpeed;Selection.animMode(true);var animator=function(){return function(){pcent+=(100-pcent)/velocity;animat[0]=x+((pcent/100)*ix1);animat[1]=y+((pcent/100)*iy1);animat[2]=x2+((pcent/100)*ix2);animat[3]=y2+((pcent/100)*iy2);if(pcent<100){animateStart()}else{Selection.done()}if(pcent>=99.8){pcent=100}setSelectRaw(animat)}}();function animateStart(){window.setTimeout(animator,interv)}animateStart()}function setSelect(rect){setSelectRaw([rect[0]/xscale,rect[1]/yscale,rect[2]/xscale,rect[3]/yscale])}function setSelectRaw(l){Coords.setPressed([l[0],l[1]]);Coords.setCurrent([l[2],l[3]]);Selection.update()}function setOptions(opt){if(typeof(opt)!="object"){opt={}}options=$.extend(options,opt);if(typeof(options.onChange)!=="function"){options.onChange=function(){}}if(typeof(options.onSelect)!=="function"){options.onSelect=function(){}}}function tellSelect(){return unscale(Coords.getFixed())}function tellScaled(){return Coords.getFixed()}function setOptionsNew(opt){setOptions(opt);interfaceUpdate()}function disableCrop(){options.disabled=true;Selection.disableHandles();Selection.setCursor("default");Tracker.setCursor("default")}function enableCrop(){options.disabled=false;interfaceUpdate()}function cancelCrop(){Selection.done();Tracker.activateHandlers(null,null)}function destroy(){$div.remove();$origimg.show()}function interfaceUpdate(alt){options.allowResize?alt?Selection.enableOnly():Selection.enableHandles():Selection.disableHandles();Tracker.setCursor(options.allowSelect?"crosshair":"default");Selection.setCursor(options.allowMove?"move":"default");$div.css("backgroundColor",options.bgColor);if("setSelect" in options){setSelect(opt.setSelect);Selection.done();delete (options.setSelect)}if("trueSize" in options){xscale=options.trueSize[0]/boundx;yscale=options.trueSize[1]/boundy}xlimit=options.maxSize[0]||0;ylimit=options.maxSize[1]||0;xmin=options.minSize[0]||0;ymin=options.minSize[1]||0;if("outerImage" in options){$img.attr("src",options.outerImage);delete (options.outerImage)}Selection.refresh()}$hdl_holder.hide();interfaceUpdate(true);var api={animateTo:animateTo,setSelect:setSelect,setOptions:setOptionsNew,tellSelect:tellSelect,tellScaled:tellScaled,disable:disableCrop,enable:enableCrop,cancel:cancelCrop,focus:KeyManager.watchKeys,getBounds:function(){return[boundx*xscale,boundy*yscale]},getWidgetSize:function(){return[boundx,boundy]},release:Selection.release,destroy:destroy};$origimg.data("Jcrop",api);return api};$.fn.Jcrop=function(options){function attachWhenDone(from){var loadsrc=options.useImg||from.src;var img=new Image();img.onload=function(){$.Jcrop(from,options)};img.src=loadsrc}if(typeof(options)!=="object"){options={}}this.each(function(){if($(this).data("Jcrop")){if(options=="api"){return $(this).data("Jcrop")}else{$(this).data("Jcrop").setOptions(options)}}else{attachWhenDone(this)}});return this}})(jQuery);

//弹出窗口----------------------------------------------------------------------------
var popBox={};

/*一秒钟遮罩*/
popBox.modal=function(){
	$('body').append('<div class="modal hide"></div>');
		$('.modal').fadeIn(1).delay(1000).fadeOut(300, function(){
		$(this).remove();
	});
};


//操作错误提示（自动关闭）
popBox.errorBox=function(txt){
	var text=txt || "出错啦!";
	var top=$(document).scrollTop();
	var sW=$(document).width()/2;
	$('body').append('<div class="popBox errorBox hide">'+text+'</div>');
	var boxW=-($('.errorBox').width()/2);
	var boxH=$('.errorBox').height();
	$('.errorBox').css({'top':top,'margin-left':boxW,'left':sW});
	$('.errorBox').show().animate({'top':top+boxH,opacity:.9}).delay(1500).fadeOut(300, function(){
		$(this).remove();
	});
	
	
};

//成功提示框（自动关闭）
popBox.successBox=function(txt){
	var text=txt || "操作成功!";
	$('body').append('<div class="popBox successBox hide">'+text+'</div>');
	$('.successBox').popBoxShow({"blackBg":false});
	$('.successBox').fadeIn(300).delay(1000).fadeOut(300, function(){
		$(this).remove();
	});
};



//确认提示框
/*popBox.alertBox=function(html){
	var html=html||"你确定要如此操作吗?";
	var popHtml='<div class="popBox alertBox" title="提示">'+html+'</div>';
	$("body").append(popHtml);
	$('.alertBox').dialog(
		{ close:function(){$(this).remove()},modal: true,
			buttons: [{text: "确定",click:function(){$(this).remove()} }]
		});
}*/

//确认提示框
popBox.alertBox=popBox.confirmBox=function(html,trueFn,falseFn){
	var html=html||"你确定吗?";
	var popHtml='<div class="popBox confirmBox" title="确认">'+html+'</div>';
	$("body").append(popHtml);
	$('.confirmBox').dialog(
		{modal:true,width:"auto", close:function(){$(this).remove()},
			buttons: [
			{text: "确定",click:function(){$(this).remove();trueFn()}},
			{text: "取消",click:function(){$(this).remove();falseFn()}}
			]
		});
};


//添加表情弹窗
popBox.face=function(btn,insertTarget,alt){//添加表情按钮/插入目标
	var html='<div class="faceBox pop"><i></i>';
		html+='<img src="/images/face/88_thumb.gif" alt="[拜拜]" title="/拜拜">';
		html+='<img src="/images/face/angrya_thumb.gif" alt="[发怒]" title="/发怒">';
		html+='<img src="/images/face/bba_thumb.gif" alt="[害羞]" title="/害羞">';
		html+='<img src="/images/face/bs_thumb.gif" alt="[快哭了]" title="/快哭了">';
		html+='<img src="/images/face/bs2_thumb.gif" alt="[鄙视]" title="/鄙视">';
		html+='<img src="/images/face/bz_thumb.gif" alt="[闭嘴]" title="/闭嘴">';
		html+='<img src="/images/face/cj_thumb.gif" alt="[惊恐]" title="/惊恐">';
		html+='<img src="/images/face/cool_thumb.gif" alt="[得意]" title="/得意">';
		html+='<img src="/images/face/crazya_thumb.gif" alt="[抓狂]" title="/抓狂">';
		html+='<img src="/images/face/cry.gif" alt="[衰]" title="[衰]">';
		html+='<img src="/images/face/dizzya_thumb.gif" alt="[晕]" title="/晕">';
		html+='<img src="/images/face/gza_thumb.gif" alt="[鼓掌]" title="/鼓掌">';
		html+='<img src="/images/face/hatea_thumb.gif" alt="[糗大了]" title="/糗大了">';
		html+='<img src="/images/face/hearta_thumb.gif" alt="[爱心]" title="/爱心">';
		html+='<img src="/images/face/heia_thumb.gif" alt="[偷笑]" title="/偷笑">';
		html+='<img src="/images/face/hsa_thumb.gif" alt="[色]" title="/色">';
		html+='<img src="/images/face/k_thumb.gif" alt="[哈欠]" title="/哈欠">';
		html+='<img src="/images/face/kl_thumb.gif" alt="[可怜]" title="/可怜">';
		html+='<img src="/images/face/kbsa_thumb.gif" alt="[抠鼻子]" title="/抠鼻子">';
		html+='<img src="/images/face/laugh.gif" alt="[憨笑]" title="/憨笑">';
		html+='<img src="/images/face/ldln_thumb.gif" alt="[惊讶]" title="/惊讶">';
		html+='<img src="/images/face/lovea_thumb.gif" alt="[快哭了]" title="/快哭了">';
		html+='<img src="/images/face/mb_thumb.gif" alt="[可爱]" title="/可爱">';
		html+='<img src="/images/face/nm_thumb.gif" alt="[咒骂]" title="/咒骂">';
		html+='<img src="/images/face/ok_thumb.gif" alt="[ok]" title="/ok">';
		html+='<img src="/images/face/qq_thumb.gif" alt="[亲亲]" title="/亲亲">';
		html+='<img src="/images/face/sada_thumb.gif" alt="[大哭]" title="/大哭">';
		html+='<img src="/images/face/sb_thumb.gif" alt="[撇嘴]" title="/撇嘴">';
		html+='<img src="/images/face/shamea_thumb.gif" alt="[冷汗]" title="/冷汗">';
		html+='<img src="/images/face/sleepa_thumb.gif" alt="[困]" title="/困">';
		html+='<img src="/images/face/sleepya_thumb.gif" alt="[睡觉]" title="/睡觉">';
		html+='<img src="/images/face/smilea_thumb.gif" alt="[微笑]" title="/微笑">';
		html+='<img src="/images/face/yw_thumb.gif" alt="[疑问]" title="/疑问">';
		html+='<img src="/images/face/yhh_thumb.gif" alt="[右哼哼]" title="/右哼哼">';
		html+='<img src="/images/face/zhh_thumb.gif" alt="[左哼哼]" title="/左哼哼">';
		html+='</div>';
	$('body').append(html);
	var btnTop=$(btn).offset().top+30;//获取添加表情按钮的坐标
	var btnLeft=$(btn).offset().left;
	$('.faceBox').show().css({'position':'absolute','top':btnTop+'px','left':btnLeft+'px','z-index':500});
	$('.faceBox img').click(function(){
		var pos=getCursortPosition($(insertTarget).get(0));
		setCaretPosition($(insertTarget).get(0),pos);
		var alt=$(this).attr('alt');
		$('.JS_textarea').append(alt);
		$(insertTarget).insertAtCaret(alt);
		$(this).parent().remove();
		return false;
	})
};

		
//发私信
popBox.private_msg=function(arr){//[{'id':1,'name':'zhangsan'},{'id':3,'name':'李四'}]
	popBox.className = "private_msg";
	var opts="";
	var nameBar;
	for(var i=0; i<arr.length; i++){
		opts+='<option value='+arr[i].id+'>'+arr[i].name+'</option>';
	}
	nameBar='<select class="sel">'+opts+'</select>';
	var html='<div class="popBox sendspop hide" title="发私信">';
			html+='<div class="popCont JS_textareaBox">';
				html+='<ul class="form_list">';
					html+='<li><div class="formL"><label>收信人：</label></div><div class="formR">'+nameBar+'</li>';
					html+='<li><div class="formL"><label>内　容：</label></div>';
						html+='<div class="formR" style="width:360px">';
							html+='<div class="textareaBox">';
								html+='<textarea class="textarea checkTextarea"></textarea>';
								html+='<span class="placeholder">提示信息</span>';
								html+='<div class="btnArea">';
									html+='<span class="addFace"><i class="addFaceBtn"></i>表情</span>';
									html+='<em class="txtCount">可以输入 <b class="num">140</b> 字</em>';
									html+='<button type="button" class="sendBtn">回复</button>';
								html+='</div>';
							html+='</div>';
						html+='</div></li></ul>';
		html+='</div></div>';
	
	$('body').append(html);
	$('.sendspop .checkTextarea').charCount();//显示剩余数字
	$('.sendspop').dialog({
			autoOpen: false,
			width:500,
			modal: true,
			resizable:false,
			close:function(){
				$(this).remove();
			}
		});
	$( ".sendspop" ).dialog( "open" );
	$('.sendspop .sendBtn').unbind('click').click(function(){
		if($('.sendspop textarea').val()!=""){
			$('.sendspop').fadeOut(500,function(){
				$(this).remove();
			});
		}
		else popBox.errorBox("内容不能为空!");
	})
};	
	


//上传头像
popBox.uploadPic=function(){
/*	var html='<div id="uploadPic" class="popBox uploadPic" title="上传图片">';
	html+='<span class="fileinput-button uploading"><span class="id_btn Continue">选择文件</span><input id="fileupload" type="file" name="files[]" multiple class="file"></span>';
    html+='<div class="zxxWrap"><hr><h5>裁剪头像</h5><h6>最终头像:</h6>';
	html+='<div class="zxx_main_con">';
	html+='<div class="zxx_test_list pr">';
	html+='<img id="xuwanting" src="../images/head.jpg"/><span id="preview_box230" class="crop_preview230"><img id="crop_preview230" src="../images/head.jpg" /></span><span id="preview_box110" class="crop_preview110"><img id="crop_preview110" src="../images/head.jpg" /></span><span id="preview_box70" class="crop_preview70"><img id="crop_preview70" src="../images/head.jpg" /></span><span id="preview_box40" class="crop_preview40"><img id="crop_preview40" src="../images/head.jpg" /><span></div></div></div></div>';
	$('body').append(html);
	
	$('#uploadPic' ).dialog({
		width:880,
		modal: true,
		resizable:false,
		buttons: [
			{
				text: "确定",
				click: function() {
					$( this ).dialog( "close" );
				}
			},
			{
				text: "取消",
				click: function() {
					$( this ).dialog( "close" );
				} 
			}
		]
	});
*/	
	$("#xuwanting").Jcrop({
		onChange:showPreview,
		onSelect:showPreview,
		aspectRatio:1
	});	
	function showPreview(coords){
		if(parseInt(coords.w)>0){
			var rx230 = $("#preview_box230").width() / coords.w; 
			var ry230 = $("#preview_box230").height() / coords.h;
			var rx110 = $("#preview_box110").width() / coords.w; 
			var ry110 = $("#preview_box110").height() / coords.h;
			var rx70 = $("#preview_box70").width() / coords.w; 
			var ry70 = $("#preview_box70").height() / coords.h;
			var rx40 = $("#preview_box40").width() / coords.w; 
			var ry40 = $("#preview_box40").height() / coords.h;
			
			$("#jcrop_x1").val(coords.x);
			$("#jcrop_y1").val(coords.y);
			$("#jcrop_x2").val(coords.x2);
			$("#jcrop_y2").val(coords.y2);
			$("#jcrop_w").val(coords.w);
			$("#jcrop_h").val(coords.h);

			//通过比例值控制图片的样式与显示
			$("#crop_preview230").css({
				width:Math.round(rx230 * $("#xuwanting").width()) + "px",height:Math.round(rx230 * $("#xuwanting").height()) + "px",	marginLeft:"-" + Math.round(rx230 * coords.x) + "px",marginTop:"-" + Math.round(ry230 * coords.y) + "px"});
			$("#crop_preview110").css({width:Math.round(rx110 * $("#xuwanting").width()) + "px",	height:Math.round(ry110 * $("#xuwanting").height()) + "px",	marginLeft:"-" + Math.round(rx110 * coords.x) + "px",marginTop:"-" + Math.round(ry110 * coords.y) + "px"});
			
			$("#crop_preview70").css({width:Math.round(rx70 * $("#xuwanting").width()) + "px",height:Math.round(ry70 * $("#xuwanting").height()) + "px",marginLeft:"-" + Math.round(rx70 * coords.x) + "px",marginTop:"-" + Math.round(ry70 * coords.y) + "px"});
			$("#crop_preview40").css({width:Math.round(rx40 * $("#xuwanting").width()) + "px",height:Math.round(ry40 * $("#xuwanting").height()) + "px",marginLeft:"-" + Math.round(rx40 * coords.x) + "px",marginTop:"-" + Math.round(ry40 * coords.y) + "px"});
		}
	}
};


//知识树
popBox.pointTree=function(zNodes,clickBtn,title,treeCls,fn){
	//zNodes:树(数组)  clickBtn:调用按钮  title:弹框标题 treeCls:树类型(知识树/章节 fn:回调函数)
	var boxTitle=title || "知识树";
	var pa;
	treeCls? pa=clickBtn.parent('.'+treeCls+'TreeWrap') : pa=clickBtn.parent('.treeParent');
	var pointList;//已选中li集合
	var id_arr=[];
	id_arr.length=0;
	var hidVal=pa.find('.hidVal');
	if($(hidVal).val()!=""){
		id_arr=(hidVal.val()).split(',');//读取隐藏域的id
	}
	
	for(var i=0; i<zNodes.length; i++){//清除zNodes所有checked
		zNodes[i].checked=false;
	};
	
	if(id_arr.length>0){
		for(var i=0; i<id_arr.length; i++){//重新为zNodes添加checked
			for(var j=0; j<zNodes.length; j++){
				if(zNodes[j].id==id_arr[i]){
					zNodes[j].checked=true;
				}
			}
		}
		pointList=pa.find('.labelList').children('li').clone();	
	}
	
	//生成树
	var html='<div id="treeBox" class="popBox treeBox hide" title="'+boxTitle+'">';
	html+='<ul id="treeList" class="clearfix ztree"></ul>';
	html+='<hr><div class="chooseLabel hide"><h6>已选中:</h6>';
	html+='<ul class="labelList clearfix"></ul>';
	html+='</div></div>';
	$("#treeBox").remove();
	$('body').append(html);	
	
	var setting = {
		check:{enable:true,chkboxType:{"Y" : "", "N" : ""} },
		data:{simpleData: {	enable: true} },
		callback: {onCheck:zTreeOnCheck},
		view:{showIcon:false,showLine:false}
	};
	$.fn.zTree.init($("#treeList"), setting, zNodes);
	$('#treeBox').dialog({
		autoOpen:false,
		width:500,
		modal: true,
		close:function(){ $(this).remove()},
		resizable:false,
		buttons: [
			{
				text: "确定",
				click: function() {
					clickOKBtn();//点击ok的函数
					 $(this).remove(); 
				}
			},
			{
				text: "取消",
				click: function() {
					 $(this).remove(); 
				} 
			}
		]
	});	
	if(id_arr.length>0){
		$('#treeBox .chooseLabel').show()
		$('#treeBox .labelList').empty().append(pointList);
	}
	$( "#treeBox" ).dialog( "open" );



//点击树上的checkbox
	function zTreeOnCheck(event, treeId, treeNode){
		if(treeNode.checked==true){
			$('#treeBox .chooseLabel').show();
			$('#treeBox .labelList').append('<li val="'+treeNode.id+'"  index="'+treeNode.tId+'">'+treeNode.name+'</li>');
			return false;
		}
		else{
			$('#treeBox .labelList li[val='+treeNode.id+']').remove();
			if($('#treeBox .labelList li').size()<1){
				$('#treeBox .chooseLabel').hide();
			}
			return false;
			}
	}
	
	//点击ok按钮
	function clickOKBtn(){
		id_arr.length=0;
		var newLi=$('.treeBox .labelList li');
		if(newLi.length>0){
			for(var i=0; i<newLi.length; i++){
				id_arr.push($(newLi).eq(i).attr('val'));
			}
			hidVal.val(id_arr);
			pa.find('.pointArea').show();
			pa.find('.labelList').empty().append(newLi);
		}
		else{
			pa.find('.pointArea').hide();
			pa.find('.labelList').empty();
		
		}
	}		
};



//知识树2
popBox.pointTree2=function(zNodes,clickBtn,title){
	$(clickBtn).each(function(index, element) {
		var boxTitle=title || "知识树";
		var checkArr=[];//存放被选中的zNodes对
		var pointList;//知识点li集合
		var pa=$(this).parent('.treeParent');//按钮的父级
		var old_pointArea=$(this).next('.pointArea');
		var id_arr=[];
		id_arr.length=0;
		if(old_pointArea.children('.hidVal').val()!=""){
			id_arr=(old_pointArea.children('.hidVal').val()).split(',');//读取隐藏域的id
		}
		
		function reset_zNodes(){//将zNodes全部取消checked
			for(var i=0; i<zNodes.length; i++){
				if(zNodes[i].checked){
					zNodes[i].checked=false;
				}
			};
		}
		
		function check(){//初始化已选中节点
			reset_zNodes()
			
			//将id_arr里面的id,匹配到zNodes上面
			if(id_arr.length>0){
				for(var i=0; i<id_arr.length; i++){
					for(var j=0; j<zNodes.length; j++){
						if(zNodes[j].id==id_arr[i]){
							zNodes[j].checked=true;
							checkArr.push(zNodes[j]);
						}
					}
				}	
			}
			
			if(checkArr.length>0){
				old_pointArea.show();
				old_pointArea.children('ul').empty();
				for(var i=0;i<checkArr.length;i++){
					old_pointArea.children('ul').append('<li val="'+checkArr[i].id+'" >'+checkArr[i].name+'</li>');
				}
				old_pointArea.children('.hidVal').val(id_arr);//保存id
				pointList=$(old_pointArea).children('ul').children('li').clone();
			}
			else{
				old_pointArea.hide();
				old_pointArea.children('ul').empty();
				old_pointArea.children('.hidVal').val("");
			}
		}
		check()
	
		//点击按钮,弹出ztree窗口
		$(this).click(function(){
			//重置zNodes
			var old_pointArea=$(this).nextAll('.pointArea');
			var id_arr=[];
			id_arr.length=0;
			if(old_pointArea.children('.hidVal').val()!=""){
				id_arr=(old_pointArea.children('.hidVal').val()).split(',');//读取隐藏域的id
			}
			reset_zNodes();
			if(id_arr.length>0){//将id_arr里面的id,匹配到zNodes上面
				for(var i=0; i<id_arr.length; i++){
					for(var j=0; j<zNodes.length; j++){
						if(zNodes[j].id==id_arr[i]){
							zNodes[j].checked=true;
						}
					}
				}	
			};
		
			//生成树
			var html='<div id="treeBox" class="popBox treeBox hide" title="'+boxTitle+'">';
			html+='<ul id="treeList" class="clearfix ztree"></ul>';
			html+='<hr><div class="chooseLabel hide"><h6>已选中:</h6>';
			html+='<ul class="labelList clearfix"></ul>';
			html+='</div></div>';
			$("#treeBox").remove();
			$('body').append(html);	
			
			var setting = {
				check:{enable:true,chkboxType:{"Y" : "", "N" : ""} },
				data:{simpleData: {	enable: true} },
				callback: {onCheck:zTreeOnCheck},
				view:{showIcon:false,showLine:false}
			};
			$.fn.zTree.init($("#treeList"), setting, zNodes);
			$('#treeBox').dialog({
				autoOpen:false,
				width:500,
				close:function(){ $(this).remove()},
				modal: true,
				resizable:false,
				buttons: [
					{
						text: "确定",
						click: function() {
							clickOKBtn();//点击ok的函数
							 $(this).remove(); 
						}
					},
					{
						text: "取消",
						click: function() {
							 $(this).remove(); 
						} 
					}
				]
			});	
			if(id_arr.length>0){
				$('#treeBox .chooseLabel').show()
				$('#treeBox .labelList').empty().append(pointList);
			}
			$( "#treeBox" ).dialog( "open" );
		})
	
	
	//点击树上的checkbox
		function zTreeOnCheck(event, treeId, treeNode){
			if(treeNode.checked==true){
				$('#treeBox .chooseLabel').show();
				$('#treeBox .labelList').append('<li val="'+treeNode.id+'"  index="'+treeNode.tId+'">'+treeNode.name+'</li>');
				return false;
			}
			else{
				$('#treeBox .labelList li[val='+treeNode.id+']').remove();
				if($('#treeBox .labelList li').size()<1){
					$('#treeBox .chooseLabel').hide();
				}
				return false;
				}
		}
		
		//点击ok按钮
		function clickOKBtn(){
			checkArr.length=0;
			id_arr.length=0;
			var newLi=$('.treeBox .labelList li');
			if(newLi.size()>0){
				for(var j=0;j<zNodes.length; j++){//清除zNodes上的所有checked
					zNodes[j].checked=false;
				}
				for(var i=0; i<newLi.length;i++){
					for(var j=0;j<zNodes.length; j++){
						if(zNodes[j].id==$(newLi[i]).attr('val')){
							zNodes[j].checked=true;
							id_arr.push(zNodes[j].id);
						}
					}
				}
			}
			check();
		}		
	});
};
//新增的属性 给图片加路径
$("img[data-type='header']").attr("onerror",function(i){
    this.src='/images/tx.jpg';this.onerror=null;
});

 



