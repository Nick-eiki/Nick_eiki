define(['popBox','jquery_sanhai','jqueryUI'],function(popBox){
    //选择课程 年级
    $('#sel_course').sUI_select();
    $('#sel_grade').sUI_select();

   //打开课程列表
    $('#show_sel_classesBar_btn').click(function(){
        $(".sel_classesBar").slideDown();
        return false;
    });
    //选择学科
    $("#sel_classesBar").sel_list('single');
    $("#sel_classesBar dd").click(function(){
        $('#sel_classes h5').text($(this).text());
        $("#sel_classesBar").hide();
    });

    //目录树
    $('.pointTree').tree();



    //搜索框提示
    $('#mainSearch').placeholder({
        value: "请输入资料名称关键字……",
        left: 15,
        top: 4
    });

    //选择课程
    $(".classes_sel_list").sel_list('single', function () {
    });

    //dom增加阅读数
    $(document).on('click','.addReadNum',function(){
        var _this = $(this);
        var readNum = _this.children('.readNum').text();
        _this.children('.readNum').text(++readNum);
    });

    //收藏，取消收藏
    $(document).on('click','.fav',function() {
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

    //章节树
    $("#problem_tree").find("a").click(function () {
        var _this = $(this);
        var tome = _this.attr("data-value");
        var url = $("#problem_tree").attr('data-url');
        $.get(url, {tome: tome}, function (result) {
            $('#classFile').html(result);
        })
    });

   // $("#classes_sel_list").sel_list('single');
    //类型搜索
    $(document).on('click','.select_mattype',function(){
        var _this = $(this);
        var url = _this.attr('data-url');
        $.get(url,{},function(data){
            $("#classFile").html(data);
        })
    });

    //排序
    $(document).on('click','.sort_search',function(){
        var _this = $(this);
        var url = _this.attr('data-url');
        $.get(url,{},function(data){
            $("#classFile").html(data);
        })
    })


});


