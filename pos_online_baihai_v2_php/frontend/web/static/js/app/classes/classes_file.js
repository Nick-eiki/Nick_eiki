define(['popBox','jquery_sanhai','jqueryUI'],function(popBox){

    $('#mainSearch').placeholder({value:"请输入关键字……",left:15,top:4});


//单选
    $('.classes_file_list .row').openMore(38);
    $('#classes_sel_list ul a').click(function(){
        var txt=$(this).text();
        $('#classes_file_crumbs').append('<span>学科:<em>'+txt+'</em><i>×</i></span>');

    });

    //收藏，取消收藏
    $('.fav').live('click',function() {
        var _this=$(this);
        var id = _this.attr('data-id');
        var type = _this.attr('data-type');
        var url = _this.attr('data-url');
        var cancelUrl = _this.attr('data-url-cancel');
        if(_this.hasClass('cur')){
            $.post(cancelUrl,{id:id,type:type},function(data){
                if(data.success){
                    _this.children('.collection').text('收藏');
                    var collectNum = _this.children('.collectionNum').text();
                    _this.children('.collectionNum').text(collectNum-1);
                    _this.removeClass('cur');
                }else{
                    popBox.errorBox(data.message);
                }

            });
        }else{
            $.post(url,{id:id,type:type},function(data){
                if(data.success){
                    _this.children('.collection').text('取消收藏');
                    var collectNum = _this.children('.collectionNum').text();
                    _this.children('.collectionNum').text(++collectNum);
                    _this.addClass('cur');
                }else{
                    popBox.errorBox(data.message);
                }
            });
        }
    });


//dom增加阅读数
    $('.addReadNum').live('click',function(){
        var _this = $(this);
        var readNum = _this.children('.readNum').text();
        _this.children('.readNum').text(++readNum);
    });

});









