 $(function(){
//点击空白 关闭弹出窗口	
$(document).bind("mousedown",function(e){var target=$(e.target);if(target.closest(".pop").length==0)$(".pop").hide()})


//表格隔行变色
$('table tbody tr').live('mouseover',function(){$(this).addClass('trOver')})
$('table tbody tr').live('mouseout',function(){$(this).removeClass('trOver')})


})


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
	$.fn.extend({
		charCount:function(opts){
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
	})
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




//jQuery Easing v1.3 -------------------------------------------------------------------------
jQuery.easing.jswing=jQuery.easing.swing;jQuery.extend(jQuery.easing,{def:"easeOutQuad",swing:function(e,f,a,h,g){return jQuery.easing[jQuery.easing.def](e,f,a,h,g)},easeInQuad:function(e,f,a,h,g){return h*(f/=g)*f+a},easeOutQuad:function(e,f,a,h,g){return -h*(f/=g)*(f-2)+a},easeInOutQuad:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f+a}return -h/2*((--f)*(f-2)-1)+a},easeInCubic:function(e,f,a,h,g){return h*(f/=g)*f*f+a},easeOutCubic:function(e,f,a,h,g){return h*((f=f/g-1)*f*f+1)+a},easeInOutCubic:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f+a}return h/2*((f-=2)*f*f+2)+a},easeInQuart:function(e,f,a,h,g){return h*(f/=g)*f*f*f+a},easeOutQuart:function(e,f,a,h,g){return -h*((f=f/g-1)*f*f*f-1)+a},easeInOutQuart:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f*f+a}return -h/2*((f-=2)*f*f*f-2)+a},easeInQuint:function(e,f,a,h,g){return h*(f/=g)*f*f*f*f+a},easeOutQuint:function(e,f,a,h,g){return h*((f=f/g-1)*f*f*f*f+1)+a},easeInOutQuint:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f*f*f+a}return h/2*((f-=2)*f*f*f*f+2)+a},easeInSine:function(e,f,a,h,g){return -h*Math.cos(f/g*(Math.PI/2))+h+a},easeOutSine:function(e,f,a,h,g){return h*Math.sin(f/g*(Math.PI/2))+a},easeInOutSine:function(e,f,a,h,g){return -h/2*(Math.cos(Math.PI*f/g)-1)+a},easeInExpo:function(e,f,a,h,g){return(f==0)?a:h*Math.pow(2,10*(f/g-1))+a},easeOutExpo:function(e,f,a,h,g){return(f==g)?a+h:h*(-Math.pow(2,-10*f/g)+1)+a},easeInOutExpo:function(e,f,a,h,g){if(f==0){return a}if(f==g){return a+h}if((f/=g/2)<1){return h/2*Math.pow(2,10*(f-1))+a}return h/2*(-Math.pow(2,-10*--f)+2)+a},easeInCirc:function(e,f,a,h,g){return -h*(Math.sqrt(1-(f/=g)*f)-1)+a},easeOutCirc:function(e,f,a,h,g){return h*Math.sqrt(1-(f=f/g-1)*f)+a},easeInOutCirc:function(e,f,a,h,g){if((f/=g/2)<1){return -h/2*(Math.sqrt(1-f*f)-1)+a}return h/2*(Math.sqrt(1-(f-=2)*f)+1)+a},easeInElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k)==1){return e+l}if(!j){j=k*0.3}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}return -(g*Math.pow(2,10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j))+e},easeOutElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k)==1){return e+l}if(!j){j=k*0.3}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}return g*Math.pow(2,-10*h)*Math.sin((h*k-i)*(2*Math.PI)/j)+l+e},easeInOutElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k/2)==2){return e+l}if(!j){j=k*(0.3*1.5)}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}if(h<1){return -0.5*(g*Math.pow(2,10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j))+e}return g*Math.pow(2,-10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j)*0.5+l+e},easeInBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}return i*(f/=h)*f*((g+1)*f-g)+a},easeOutBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}return i*((f=f/h-1)*f*((g+1)*f+g)+1)+a},easeInOutBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}if((f/=h/2)<1){return i/2*(f*f*(((g*=(1.525))+1)*f-g))+a}return i/2*((f-=2)*f*(((g*=(1.525))+1)*f+g)+2)+a},easeInBounce:function(e,f,a,h,g){return h-jQuery.easing.easeOutBounce(e,g-f,0,h,g)+a},easeOutBounce:function(e,f,a,h,g){if((f/=g)<(1/2.75)){return h*(7.5625*f*f)+a}else{if(f<(2/2.75)){return h*(7.5625*(f-=(1.5/2.75))*f+0.75)+a}else{if(f<(2.5/2.75)){return h*(7.5625*(f-=(2.25/2.75))*f+0.9375)+a}else{return h*(7.5625*(f-=(2.625/2.75))*f+0.984375)+a}}}},easeInOutBounce:function(e,f,a,h,g){if(f<g/2){return jQuery.easing.easeInBounce(e,f*2,0,h,g)*0.5+a}return jQuery.easing.easeOutBounce(e,f*2-g,0,h,g)*0.5+h*0.5+a}});

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

//确定提示框
popBox.alertBox=function(html){
	var html=html||"你确定吗?";
	var popHtml='<div class="popBox alertBox" title="确认">'+html+'</div>';
	$("body").append(popHtml);
	$('.alertBox').dialog(
		{modal:true,width:300, close:function(){$(this).remove()},
			buttons: [
			{text: "确定",click:function(){$(this).remove()}}
			]
		});
};


//判断提示框
popBox.confirmBox=function(html,trueFn,falseFn){
	var html=html||"你确定吗?";
	var popHtml='<div class="popBox confirmBox" title="确认">'+html+'</div>';
	$("body").append(popHtml);
	$('.confirmBox').dialog(
		{modal:true,width:300, close:function(){$(this).remove()},
			buttons: [
			{text: "确定",click:function(){$(this).remove();if(trueFn)trueFn()}},
			{text: "取消",click:function(){$(this).remove();if(falseFn)falseFn()}}
			]
		});
};



$(function(){
	$('.mainNav li').hover(function(){
		$(this).children('a').addClass('hover');	
	},function(){
		$(this).children('a').removeClass('hover');
	})	
})


