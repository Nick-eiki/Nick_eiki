// JavaScript Document
var em_bum_number = 1;
var em_bum_number_false = true;
var mutil_QA=false;//  填空题:单个答案/多个小题
var preview ={
	typeName:['单选','多选','填空','问答','应用','完形','阅读理解'],
	typeName2:['高考','中考','升级','普通上学期','期末下学期期末'],
	GetItem: function(options){
		var defaults = { typeDom:'', qDom:'', selDom:'', nDom:''};
		var opts = $.extend(defaults, options);
		var obj = { type: $(opts.typeDom).val(), n:$(opts.nDom).val()};	
	},
	
	
	//新增小题
	//targetBox:存放小题的元素的className
	AddItem:function(targetBox){
		var index = $('[id^=questionBox_]').length || 0;
		var html = '';
			//知识点部分
			if($('[id^=questionBox_]').length<2)
				html += '<div class="itemsMainBox">';
				html += '<div id="questionBox_' + index +'" class="yingYong_b application_title bj_color">';
				html += '<em class="Bji_del B_del_js"></em>';
				html += '<h3 style="font-size:14px;"><i>*</i>第<span class="nub">'+ index + '</span>小题</h3>';
				html += '<ul><li class="clearfix"> '; 
				html += '<label class="species"><i>*</i>涉及知识点:</label>';
				html += '<div id="pt_' + index +'" class="word_box treeParent">';
				html += '<button type="button" class="addPoint2">编辑知识点</button>';
				html += '<div class="pointArea hide">';
				html +='<input class="hidVal" type="hidden" value="">';
				html += '<h6>已选中知识点：</h6><ul class="labelList"></ul>';
				html += '</div></div></li>';
					//题型部分
					html += '<li class="clearfix"><label class="species"><i>*</i>题型:</label>';
							html += '<div class="box_select">';
									html += '<select class="xuanzhe" id="type_' + index + '" name="type">';
										html += '<option value="1">单选</option>';
										html += '<option value="2">多选</option>';
										html += '<option value="3">填空题</option>';
										 html += '<option value="4">问答题</option>';
									html += '</select>';
								html += '</div></li>';
					if($('#type_0').val()!=6){
						html += '<li class="clearfix">';
							html += '<label class="species"><i>*</i>题目:</label>';
							html += '<div class="lis03" id="timu">';
							html += '<textarea id="question_'+index+'" style="width:300px; height:200px;"></textarea></div></li>';
					}
					
					//选项与答案部分
					html += '<div class="mamt_box question_main_box_' + index + '"  id="sel_' + index + '">';
							html += '<ul class="box_data_list mde">';
								html += '<li class="clearfix">';
									html += '<label class="species" style="height:20px; line-height:20px;"><i>*</i>答案选项</label></li>';
								html += '<li class="clearfix alternative selItemBox_' + index + '">';
									html += '<div class="tet_Bji">';
									html += '<em class="tet_Bji_del tet_Bji_del_js"></em>';
										html += '<label class="species"><i>*</i>备选项<em class="spec_em">1</em>:</label>';
										html += '<p class="beixuan"><textarea style="width:300px; height:200px;" id="sel_' + index + '_0"></textarea></p></div></li>';
								html += '<li class="clearfix ap_btnBox_' + index + '">';
									html += '<label class="species"></label>';
										html += '<div class="prompt prompt02"><button class="ap_btn_' + index + ' p_reservation ">添加备选项</button></div></li>';
								html += '<li class="clearfix dDan">';
									html += '<label class="species"><i>*</i>答案:</label>';
								   
									html += '<div class="word_box radioB selCheck_' + index + '">';
											html += '<label class="sub_label"><input checked type="radio" value="0" name="selCheck_' + index + '" id="selCheck_' + index + '_0" />备选项1</label>';
									html += '</div></li></ul></div>';
									html += '</ul></div>';
									 html += '</div>';
		if($('[id^=questionBox_]').length<2)
		if($('[id^=questionBox_]').length<2) html += '<p class="p_resecer"><button value="" id="addQuestion" class="p_reservation ">添加小题</button></p>';
		$(targetBox).append(html);
	},
	
	//新增填空小题
	//targetBox:存放小题的元素的className
	AddIvacant:function(vacantBox){
		$(vacantBox).children('.answerBar').hide();
		var index=$('.alternative').length;
		index++;
		var html = '';
			//知识点部分
			html += '<li class="clearfix alternative selItemBox_' + index + '" style=" position:relative;padding-bottom:20px;background:#ccc; margin:10px 20px">';
					html+='<span class="close">×</span>';
				  html += '<div class="tet_Bji">';
					  html += '<label class="species"><i>*</i>小题<em class="spec_em">'+ index +'</em>:</label>';
					  html += '<div class="beixuan"><textarea style="width:300px; height:50px;" id="sel_' + index + '_q"></textarea></div></div>';
				  html+='<div class="tet_Bji">';
					  html += '<label class="species"><i>*</i>小题答案<em class="em_bum">'+ index +'</em>:</label>';
					  html += '<div class="beixuan"><textarea style="width:300px; height:50px;" id="sel_' + index + '_a"></textarea></div></li>';
		$(vacantBox).append(html);
	},
	
	
	
	
	//新增备选项
	//selBox:"."+存放备选项的元素的className
	//selCheckBox:"."+存放选项的元素的className
	//isSingle:是否单选，1:单选 0:多选 默认：多选
	AddSelItem:function(selBox,selCheckBox,isSingle){
		var selIndex = selBox.split('_')[1];
		var index = $(selBox + ' .tet_Bji').length || 0;
		var isSingle = isSingle || 0;
		var html = '';
			html += '<div class="tet_Bji">';
			html += '<em class="tet_Bji_del tet_Bji_del_js"></em>';
				html += '<label class="species"><i>*</i>备选项 <em class="spec_em">' + (index+1) + '</em>:</label>';
				html += '<p class="beixuan"><textarea style="width:300px; height:200px;" id="sel_'+ selIndex + '_' + index + '"></textarea></p>';
			html += '</div>';

		var html2 = '<label class="sub_label"><input type="' + (isSingle ? 'radio' : 'checkbox') + '" name="selCheck_' + selIndex + '" id="selCheck_' + selIndex + '_' + index + '" value="' + index + '">备选项' + (index+1) + '</label>';
		
		$(selBox).append(html);
		$(selCheckBox).append(html2);
		
		
	},

	//选择题型
	//id:'#'+元素的Id
	ChangeType:function(id){
		var id = id || '#type_0';
		var index = id.split('_')[1];
		var html ='';
		$('.question_main_box_'+index).html(html);
		var val = $(id).val();

		function one(){
			html += '<ul class="box_data_list bdl_bj">';
                       html += '<li class="clearfix">';
                          html += '<label class="species" style="height:20px; line-height:20px;"><i>*</i>答案选项</label>';
                      html += '</li>';
                      html += '<li class="clearfix alternative selItemBox_' + index + '">';
                          html += '<div class="tet_Bji">';
						  html += '<em class="tet_Bji_del tet_Bji_del_js"></em>';
                              html += '<label class="species"><i>*</i>备选项<em class="spec_em">1</em>:</label>';
                              html += '<div class="beixuan"><textarea style="width:300px; height:200px;" id="sel_' + index + '_0"></textarea></div>';
                          html += '</div>';
                      html += '</li>';
                      html += '<li class="clearfix ap_btnBox_0">';
                          html += '<label class="species"></label>';
                              html += '<div class="prompt prompt02">';
                                  html += '<button class="ap_btn_' + index + ' p_reservation">添加备选项</button>';
                                  
                              html += '</div>';
                      html += '</li>';
                      html += '<li class="clearfix dDan">';
                          html += '<label class="species"><i>*</i>答案:</label>';
                         
                          html += '<div class="word_box word_box2 radioB selCheck_' + index + '">';
                              html += '<label class="sub_label"><input type="' + (val==1 ? 'radio':'checkbox') + '" name="selCheck_' + index + '" id="selCheck_' + index + '_0" value="0" checked="checked" class="radio">备选项<em class="em_number">1</em></label>';
                          html += '</div>';
                      html += '</li>';
                  html += '</ul>';

                  $('.question_main_box_'+index).html(html);
		}

		function two(){
			var html='';
			html += '<ul>';
                       html += '<li class="clearfix">';
					html += '<div class="tet_Bji">';
						html += '<label class="species"><i>*</i>答案:</label>';
						html += '<div class="beixuan"><textarea style="width:300px; height:200px;" id="sel_' + index + '_0"></textarea></div>';
					html += '</div>';
				html += '</li>';
			html += '</ul>';
				
			$('.question_main_box_'+index).html(html);	
		}
		
		
		function three(){
			var html='';
			html += '<li class="clearfix anwserCls"><div class="tet_Bji"><label class="species"><i>*</i>答案/多题:</label><div class="beixuan"><input type="radio" class="radio" value="s" name="s_or_m"> 单个答案　　<input type="radio" class="radio" value="m" name="s_or_m"> 多个小题 </div></li>';
			html += '<ul class="moreSelect">';
            html += '<li class="clearfix answerBar hide">';
			html += '<div class="tet_Bji">';
			html += '<label class="species"><i>*</i>答案:</label>';
			html += '<div class="beixuan"><textarea style="width:300px; height:200px;" id="sel_' + index + '_0"></textarea></div></div></li></ul>';
			html +='<div class="muti_QA_Bar hide">';
			html += '<ul class="muti_QA_list"><li class="clearfix alternative selItemBox_1" style=" position:relative;padding-bottom:20px;background:#ccc; margin:10px 20px">';
			html+='<span class="close">×</span>';
			html += '<div class="tet_Bji">';
			html += '<label class="species"><i>*</i>小题<em class="em_bum">1</em>:</label>';
			html += '<div class="beixuan"><textarea style="width:300px; height:50px;" id="sel_1_q"></textarea></div></div>';
			html+='<div class="tet_Bji">';
			html += '<label class="species"><i>*</i>小题答案<em class="em_bum">1</em>:</label>';
			html += '<div class="beixuan"><textarea style="width:300px; height:50px;" id="sel_1_a"></textarea></div></li></ul>';
					  //添加小题 按钮
			html += '<ul class="bdl_bj">';
			html += '<li class="clearfix ap_btnBox_0">';
            html += '<label class="species"></label>';
            html += '<div class="prompt prompt02">';
            html += '<button class="add_btn_' + index + ' p_reservation" id="add_min_btn">添加小题</button>';
            html += '</div></li></ul></div>';
            $('.question_main_box_'+index).html(html);
		}
		
		if(val == 4) two();
		
		else if(val == 3) three();
		
		else if(val == 5||val == 6||val == 7) this.AddItem('.question_main_box_'+index);

		else one();
	},
	
	//获得简单题型的各个属性值
	//index:小题的索引值，如果答题题型为1-4，则index为0
	GetObj:function(index){
		var index = index || 0;
		var type = $('#type_'+index).val();
		var q = $('#question_'+index).val() || '';
		var sel = [];
		if(type==1){
			var selItemDoms = $('[id^=sel_' + index + '_]');
			for(var j=0, selLen=selItemDoms.length; j<selLen; j++){
				var val = selItemDoms.eq(j).val();
				sel.push(val);
			}
			var a = $('[name=selCheck_' + index + ']:checked').val();
		}
		else if(type==2){
			var selItemDoms = $('[id^=sel_' + index + '_]');
			for(var j=0, selLen=selItemDoms.length; j<selLen; j++){
				var val = selItemDoms.eq(j).val();
				sel.push(val);
			}
			
			var aDoms = $('[name=selCheck_' + index + ']:checked');
			var aArr = [];
			for(var i=0, alen=aDoms.length; i<alen; i++){
				aArr.push(aDoms.eq(i).val());
			}
			var a = aArr
		}
		else if(type==3){
			if(mutil_QA==true)	var a=$('.moreSelect textarea').val();
			else{
			var mQ=[];
			var mA=[];
			$('.muti_QA_list li').each(function(index, element) {
				index=index+1;
                var qq=$(this).find('textarea').eq(0).val();
				var aa=$(this).find('textarea').eq(1).val();
				mQ.push('小题'+index+': '+qq+'　');
				mA.push('小题'+index+'答案: '+aa+'　');
            });
			var q=q+'<br>'+mQ;
			var a=mA;
			}
		}
		
		else{ var a = $('#sel_' + index + '_0').val() }

		var n = $('#note').val();
		var obj = {
			type:type,
			q:q,
			a:a,
			sel:sel,
			n:n,
			isItem:index==0 ? false:true
		}
		return obj;
	},
	
	GetFormObj:function(){
		var type = $('#type_0').val();//题型select
		var q = $('#question_0').val();//题目
		var n = $('#note').val();//解析
		var time=$('#data').val();//年份
		var grade=$('#bj').val();//出处
		
		//var type02=$('#type_0').val();
		var Nandu=$('#nandu').val();//难度

		var obj1={'time':time,'grade':grade,'nd':Nandu};
		
		
		if(type==1 || type==2 || type==3 || type==4){//简单题型
			var obj = this.GetObj();
		}
		else{ //综合题型
			var itemDoms = $('.yingYong_b');
			var items = [];
			for(var i=0,len=itemDoms.length; i<len; i++){
				var k = i+1;
				var obj = this.GetObj(k);
				items.push(obj);
			}
			
			var obj = {'q':q,'n':n,'items':items};
		}
		var obj2 = $.extend(obj,obj1);
		return obj2;
	},

	//获得到选择或者填空题的html
	GetHtml: function(options){
		/****参数说明
			type:题目类型 1:单选， 2：多选，3：填空，问答题 默认值为3即填空题
			isItem:布尔值，是否是小题，默认值为false
			q:题干 默认为空字符串，无题干
			a:答案 默认为空字符串，无答案
			n:解析 默认为空字符串，无解析
			sel:备选项，默认为空数组
		****/
		var defaults = { 'type':'3', isItem:false, 'q':'', 'a':'', 'sel':[], 'n':'','time':'2014','grade':1,'nd':1};
		var opts = $.extend(defaults, options);
		var html = '';
			html += '<div class="example">';
				if(!opts.isItem){//如果是小题，无此html
					html += '<h5 class="tile03" id="tile_m">[';
						
						html+='<span>'+ this.typeName[opts.type -1] +'</span>';
						html+='<span>'+ opts.time +'</span>';
						html+='<span>'+ this.typeName2[opts.grade] +'</span>';
					
					html += ']</h5>';
				}
			
				html += '<div class="box_0">';
						if(opts.q != '')html += '<div class="summarize" id="timu_txt">' + opts.q + '</div>';
					   
						html += '<ul class="radioP" id="ulMu">';
						if(opts.type!=3){
							for(var i=0,len=opts.sel.length; i<len; i++){
								if(opts.type==1){
									html += '<li><input type="radio" name="item" value="' + i + '" />' + String.fromCharCode(65+i) + ' ' + opts.sel[i] + '</li>';
								}
								else if(opts.type==2){
									html += '<li><input type="radio" name="item" value="' + i + '" />' + String.fromCharCode(65+i) + ' ' + opts.sel[i] + '</li>';
								}
								else{
									html += '<li><span' + i + '" >' + String.fromCharCode(65+i) + ' ' + opts.sel[i] + '</li>';
									html += '<li><span' + i + '" >' + String.fromCharCode(65+i) + ' ' + opts.sel[i] + '</li>';
								}
							}
						}
						html += '</ul>';
						
						if(!opts.isItem){//如果是小题，无此html
							html += '<p class="click clearfix">';
								html += '<span class="clickBtn">查看解析<i></i></span>';
								html += '<span class="difficulty">';
								html += '<label>难度：</label>';
										html += '<em class="emnber">' + opts.nd + '</em>';
										
										html += '<label>录入：</label>';
										html += '<em class="gray">我自己</em>';
							html += '</span>';
							  html += '</p>';
							html += '<div class="showContent">';
								html += '<div class="clearfix">';
									html += '<label>答案:</label>';
									if(opts.type==3){
										html += '<span class="da_an02" style="font-size:14px; color:#666;">' + opts.a + '</span>';
									}else if(opts.type==1){
										html += '<span class="da_an02" style="font-size:14px; color:#666;">' ;
										html += String.fromCharCode(65+(opts.a-0));
										html += '</span>';	
									}else{
										html += '<span class="da_an02" style="font-size:14px; color:#666;">';
										for(var j=0, len=opts.a.length; j<len; j++){
											html += String.fromCharCode(65+(opts.a[j]-0));
											if(j!=len-1){
												 html += ',';
											}
										}
										html += '</span>';		
									}
									
								html += '</div>';
								html += '<p class="clearfix">';
									html += '<label>解析：</label>';
									if(opts.n!='')html += '<span class="popo_Jiexi">' + opts.n + '</span>';
								html += '</p>';
							html += '</div>';
						}
						
				html += '</div>';
			html += '</div>';
			
			return html;
		
	},
	
	//获得综合题目的答案的html
	GetAnsHtml:function(arr){
		var html = '<span class="da_an02" style="font-size:12px; color:#666;">';
		for(var i=0, len=arr.length; i<len; i++){
			html += '(' + (i+1) +')';
			if(arr[i].type==1){
				html += String.fromCharCode(65+(arr[i].a-0));
			}else if(arr[i].type==2){
				for(var j=0, jlen= arr[i].a.length; j<jlen; j++){
					html += String.fromCharCode(65+(arr[i].a[j]-0));
				}
			}else{
				html += arr[i].a
			}
		}
		return html + '</span>';
	},
	
	//获得综合题目的html
	GetHtmlAll: function(options){
		/****
			type:大题目的题型，默认为3填空题
			q:题干，默认为空字符串
			n:解析，默认为空字符串
			items:由每个小题数据组成的数组，默认长度为0
		****/
		var defaults = {type:3, q:'',n:'',items:[],'time':'2014','grade':1,'nd':1};
		//var defaults = { 'type':'3', isItem:false, 'q':'', 'a':'', 'sel':[], 'n':'','time':'2014','grade':1,'nd':1};
		var opts=$.extend(defaults, options);

		var html = '';
			html += '<div class="example">';
				html += '<h5 class="tile03" id="tile_m">'+'[';
						html+='<span>'+ this.typeName[opts.type -1] +'</span>';
						html+='<span>'+ opts.time +'</span>';
						html+='<span>'+ this.typeName2[opts.grade] +'</span>';
					
					html += ']'+'</h5>';
				html += '<div class="box_0">';
						if(opts.q != '')html += '<div class="summarize" id="timu_txt">' + opts.q + '</div>';
					   	
						if(opts.items.length!=0){
							for(var i=0, len=opts.items.length; i<len; i++){
								html += '(' + (i+1) + ')'
								html += this.GetHtml(opts.items[i]);
							}
						}
						
						html += '<p class="click clearfix">';
							html += '<span class="clickBtn">查看解析<i></i></span>';
							html += '<span class="difficulty">';
								html += '<label>难度</label>';
										html += '<em class="emnber">' + opts.nd + '</em>';
										
										html += '<label>录入：</label>';
										html += '<em class="gray">我自己</em>';
							html += '</span>';
						  html += '</p>';
						html += '<div class="showContent hide">';
							html += '<div class="clearfix">';
								html += '<label>答案:</label>';
								
								html += this.GetAnsHtml(opts.items);
								
							html += '</div>';
							html += '<p class="clearfix">';
								html += '<label>解析：</label>';
								if(opts.n!='')html += '<span class="popo_Jiexi">' + opts.n + '</span>';
							html += '</p>';
						  html += '</div>';
				html += '</div>';
			html += '</div>';
			return html;
	},
	
	//预览窗口
	InsertHtml: function(htmlStr){
		var html = '';
			html += '<div id="layer" class="popBox entry hide" title="题目预览">';
					html += '<div class="result-paper-example clearfix">';
							html += htmlStr;
					html += '</div>';
				html += '</div>';
		$('body').append(html);
		$('#layer').dialog({
			autoOpen:false,
			width:500,
			modal: true,
			resizable:false,
			buttons: [
				{
					text: "保存题目",
					click: function() {
						 $(this).remove(); 
					}
				},
				{
					text: "保存到草稿箱",
					click: function() {
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
		$( "#layer" ).dialog( "open" );
		//点击显示隐藏答案
		$('.showContent').hide();
		$('.clickBtn').toggle(
		function(){
			$(this).parent().parent().children('.showContent').show();	
		},function(){
			$(this).parent().parent().children('.showContent').hide();	
		})
	}
};


$(function(){
	preview.ChangeType('#type_0');
	
	//添加小题
	$('#addQuestion').live('click',function(){
		preview.AddItem('.itemsMainBox');
	});
	
	//填空题--选择是单个答案/多个小题
	$('.anwserCls input:radio').live('click',function(){
		if($(this).val()=="s"){
			mutil_QA=true;//单选题
			$(this).parents('.anwserCls').siblings('.moreSelect').find('.answerBar').show();
			$(this).parents('.anwserCls').find('.muti_QA_Bar').hide();
			$(this).parents('.anwserCls').siblings('.muti_QA_Bar').hide();
		}
		
		else if($(this).val()=="m"){
			mutil_QA=false;//多选题
			$(this).parents('.anwserCls').siblings('.moreSelect').find('.answerBar').hide();
			$(this).parents('.anwserCls').siblings('.muti_QA_Bar').show();
		}
	})
	
	//填空题-添加小题
	$('#add_min_btn').live('click',function(){
		preview.AddIvacant('.muti_QA_list');
	});
	//填空题-删除小题
	$('.muti_QA_list li .close').live('click',function(){
		$(this).parent().remove();
		$('.muti_QA_list li').each(function(index) {
            $(this).find('.em_bum').text(index+1);
			$(this).find('.spec_em').text(index+1);
        });
	})
	
	//添加备选项
	$("button[class*='ap_btn_']").live('click',function(){
		var cla=$(this).attr('class');
		var num = cla.split(' ')[0].split('_')[2];
		var isSingle = $('#type_'+num).val() == 1 ? 1 : 0;
		preview.AddSelItem('.selItemBox_'+num,'.selCheck_'+num,isSingle);
	});
	
	//修改题型
	$('[id ^= type_]').live('change',function(){
		var id=$(this).attr('id');
		preview.ChangeType('#'+id);
	});
	
	$('#preview_button').live('click',function(){
		
		//$('#layer').remove();//删除预览窗口
		var obj = preview.GetFormObj();
		var type = $('#type_0').val();
		var html,html2;
		if(type==1||type==2||type==3||type==4){
			html = preview.GetHtml(obj);
			preview.InsertHtml(html);
		}else{
			html2 = preview.GetAnsHtml(obj);
			html = preview.GetHtmlAll(obj);
			preview.InsertHtml(html+html2);
		}
	});
	
	
	//删除单选的 添加选项
	$('.tet_Bji_del_js').live('click',function(){
		$(this).parents('.alternative').siblings('.dDan').find('.sub_label').last().remove();
		$(this).parent('.tet_Bji').remove();
			$(this).parents('.alternative').children('.tet_Bji').each(function(index, element) {	
				 $(this).find('.spec_em').text(index+1); 
    	    });
	})
	
	//删除新添加的小题
	$('.B_del_js').live('click',function(){
		$(this).parents('.itemsMainBox').children('.yingYong_b').each(function(index, element){	
				 $(this).find('.nub').text(index+1);
				 //alert($(this).children('.nub').text(index)) 
    	 });
		 
		//$(this).parent('.yingYong_b').remove();
	})
	
});


