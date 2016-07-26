define(["popBox",'jquery_sanhai','jqueryUI'],function(popBox){

	$('#mainSearch').placeholder({value:"请输入关键字……",left:15,top:4})
	$(".classes_sel_list").sel_list('single',function(){
    	alert();
    });

	//单选
	$('.classes_file_list .row').openMore(38);

	$('#classes_sel_list ul a').click(function(){
		var txt=$(this).text();
		$('#classes_file_crumbs').append('<span>学科:<em>'+txt+'</em><i>×</i></span>');

	})


})