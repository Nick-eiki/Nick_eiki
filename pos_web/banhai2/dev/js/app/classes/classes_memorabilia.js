define(["popBox",'userCard','jquery_sanhai','validationEngine','validationEngine_zh_CN','jqueryUI'],function(popBox,userCard,jquery_sanhai,validationEngine,validationEngine_zh_CN){

	//时间线fixed
	$(window).scroll(function() {
		var scrollTop = $(window).scrollTop();
		var screenH=$(window).height()
		var timeLine=$('#timeLine');
		var ulWrap=$('#ulWrap');

		if(scrollTop>160){
			ulWrap.height(screenH-120);
			timeLine.addClass('timeLineFixed');
		}
		else{
			ulWrap.height("auto");
			timeLine.removeClass('timeLineFixed');
		}
	});


	//拖拽排序
	$('.upImgFile ul').sortable({items:"li:not(.disabled)"});

	$('#memor_name').placeholder({'value':'请在这里输入大事记标题'})

	//添加大事记验证
	$('#form_abcd').validationEngine();


	//时间线回到顶部
	$('#time_gotoTop').click(function(){
		$('#ulWrap').scrollTop(0);
	});


	//大事记时间线
	$('#timeLine').on('mouseenter','#timeLine dd',function(){
				var _this=$(this);
				_this.children('.toolBar').show();
				_this.find('.memorabilia_del').unbind('click').click(function(){
					popBox.confirmBox('真的要删除吗?',function(){
						_this.remove();
						//ajax
					})
				})
			});
	$('#timeLine').on('mouseleave','#timeLine dd',function(){
				$(this).children('.toolBar').hide();
			}
		);


	//时间线_更多大事记
	var time_json=[{"year":"63793","month":"11","day":"12","sec":"53:20","cont":"wwwwwwwwwwwwwww","eventID":305459},{"year":"47959","month":"05","day":"19","sec":"40:26","cont":"\u54e5\u54e5","eventID":133303301},{"year":"47915","month":"11","day":"08","sec":"31:45","cont":"\u6765\u4e86\u6765\u4e86\u6765\u4e86","eventID":133542},{"year":"47915","month":"11","day":"05","sec":"52:57","cont":"\u90fd\u597d\u597d\u7684\u6d3b\u52a8\u56de\u7535\u8bdd","eventID":133541},{"year":"47915","month":"11","day":"03","sec":"58:02","cont":"\u5566\u5566\u5566\u5fb7\u739b\u897f\u4e9a","eventID":133540},{"year":"47915","month":"11","day":"01","sec":"37:00","cont":"\u556a\u556a\u556a","eventID":133539},{"year":"47915","month":"10","day":"28","sec":"15:03","cont":"\u5f88\u559c\u6b22\u7684","eventID":133538},{"year":"47915","month":"10","day":"22","sec":"27:06","cont":"\u521a\u624d\u53d1\u5e7f\u544a\u8bcd","eventID":133537},{"year":"47915","month":"10","day":"21","sec":"53:59","cont":"\u4f60\u597d","eventID":133536},{"year":"47915","month":"10","day":"20","sec":"01:42","cont":"\u5f88\u65b9\u4fbf\u70e6\u607c\u70e6\u607c\u70e6\u607c\u80a5\u725b\u996d","eventID":133535},{"year":"47915","month":"10","day":"18","sec":"12:24","cont":"\u4f60\u54c8\u65f6\u5019\u4f4e\u529f\u8017\u5b50","eventID":133534},{"year":"47915","month":"10","day":"16","sec":"52:38","cont":"\u4f60\u597d","eventID":133533},{"year":"47915","month":"10","day":"08","sec":"10:09","cont":"\u5927\u7ed3\u5c40","eventID":133532},{"year":"47915","month":"10","day":"07","sec":"52:37","cont":"\u8c01\u90fd\u4f1a\u8bf4","eventID":133531},{"year":"47912","month":"05","day":"24","sec":"36:44","cont":"lao shi hao","eventID":133530},{"year":"47912","month":"05","day":"19","sec":"08:41","cont":"banjidashiji","eventID":133529},{"year":"47912","month":"05","day":"19","sec":"01:16","cont":"243432","eventID":133528},{"year":"47867","month":"05","day":"12","sec":"00:00","cont":"\u6dfb\u52a0\u5927\u4e8b\u8bb0","eventID":528},{"year":"47849","month":"12","day":"18","sec":"45:33","cont":"2015-11-18","eventID":133526},{"year":"47833","month":"10","day":"12","sec":"30:50","cont":"dsvCds","eventID":133525},{"year":"47812","month":"01","day":"01","sec":"50:07","cont":"qwerqwerqwerqwerqw","eventID":133514},{"year":"47811","month":"12","day":"24","sec":"46:40","cont":"asdfasdfasdf","eventID":133513},{"year":"47811","month":"11","day":"29","sec":"33:23","cont":"111111111","eventID":133512},{"year":"47811","month":"11","day":"26","sec":"07:45","cont":"11111","eventID":133511},{"year":"47809","month":"11","day":"12","sec":"00:00","cont":"2015-11-04","eventID":133493},{"year":"47809","month":"11","day":"12","sec":"00:00","cont":"\u56fe\u7247\u4e2d\u7684\u4e66\u6cd5","eventID":495},{"year":"47806","month":"01","day":"18","sec":"51:58","cont":"9090","eventID":133492},{"year":"47806","month":"01","day":"17","sec":"12:06","cont":"\u6dfb\u52a0\u5927\u4e8b\u8bb0","eventID":133491},{"year":"47798","month":"11","day":"29","sec":"00:00","cont":"\u5706\u53c8\u5706","eventID":485},{"year":"47798","month":"11","day":"29","sec":"00:00","cont":"\u5566\u5566\u5566\u5566","eventID":486}]






	function addClip(time_json){

		var time_line=$('#time_line_list');
		var this_year=$('#time_line_list .timeLine_year:last');//找到最后一年

		var this_month=0;
		var month_num=0;
		var year_num=0;

		for(var i=0; i<time_json.length; i++){
			var year=time_json[i].year;
			var month=time_json[i].month;

				var _this=this_year;
				year_num=parseInt(_this.text());
				if(year==year_num){//如果年相同
					this_month=_this.parent('li').find('.timeLine_month:last');
					month_num=parseInt(this_month.text());
					if(month!=month_num){
						_this.parents('li').append('<dl><dt class="timeLine_month">'+month+'月</dt></dl>');
						this_month=_this.parent('li').find('.timeLine_month:last');
						month_num=parseInt(this_month.text());
					}
				}
				else{
					time_line.append('<li><a class="timeLine_year">'+year+'</a><dl><dt class="timeLine_month">'+month+'月</dt></dl></li>')	;
					this_year=$('#time_line_list .timeLine_year:last');
					year_num=parseInt(this_year.text());
					this_month=this_year.parent('li').find('.timeLine_month:last');
				}
				var pa=this_month.parent('dl');
				var html='<dd>';
				html+='<em>'+time_json[i].day+'日<br><b>'+time_json[i].sec+'</b></em><i></i>';
				html+='<span class="arrow_l"></span>';
				html+='<div>'+time_json[i].cont+'</div>';
				html+='<div class="toolBar hide" style="display: none;"><a class="memorabilia_edit" href="javascript:;"></a><a href="javascript:;" class="memorabilia_del"></a></div>';
				html+='</dd>';
				pa.append(html);
		};

		var this_year=$('#time_line_list .timeLine_year');
		this_year.each(function(){
			if($(this).text()==""){
				$(this).parent('li').remove();
			}
		})


	};

	addClip(time_json);

	$('#time_addMore').click(function(){
		addClip(time_json)
	})


	//班级大事记
	$('.popBox').dialog({
	autoOpen: false,
	width:840,
	modal: true,
	resizable:false,
		close:function(){$(this).dialog("close")}
	});
	//添加班级大事记
	$('#addmemor_btn').click(function(){
		 $( "#memor_popbox" ).dialog( "open" );
		  return false;
	});

	//班级大事记
	imgArr=["../dev/images/testPaper1.jpg","../dev/images/testPaper2.jpg","../dev/images/testPaper3.jpg","../dev/images/testPaper4.jpg","../dev/images/testPaper5.jpg","../dev/images/testPaper1.jpg","../dev/images/testPaper2.jpg","../dev/images/testPaper3.jpg","../dev/images/testPaper4.jpg","../dev/images/testPaper5.jpg"];
	//大事记幻灯
	$('#slide').slide({'width':715,'Clip_width':177});



	//供页面调用
	function sst(){
		$('#nav').click(function(){
			$(this).css('background','red')
		});
	}
	return {sst:sst};

})