define(['popBox','jquery_sanhai','jqueryUI'],function(popBox,jquery_sanhai){

    //弹框
    function bskt_conf_hmwk_Box(){
        //var treeHtml='<ul id="pointTree" class="tree pointTree">';
        //        treeHtml+='<li><i class="openSubMenu"></i><a href="javascript:;" data-value="3451" title="七年级下">六级子菜单 第一级</a>';
        //            treeHtml+='<ul class="subMenu">';
        //                treeHtml+='<li><i></i><a href="javascript:;" data-value="3452" title="第五章 相交线与平行线">第二级0001</a></li>';
        //                treeHtml+='<li><i></i><a href="javascript:;" data-value="3453" title="5.1 相交线">第二级0002</a></li>';
        //                treeHtml+='<li><i class="openSubMenu"></i><a href="javascript:;" data-value="3454" title="5.1 相交线">第二级0003</a>';
        //                    treeHtml+='<ul class="subMenu">';
        //                        treeHtml+='<li><i></i><a href="javascript:;" data-value="3455" title="第五章 相交线与平行线">第三级0001</a></li>';
        //                        treeHtml+='<li><i></i><a href="javascript:;" data-value="3456" title="5.1 相交线">第三级0002</a></li>';
        //                        treeHtml+='<li><i></i><a href="javascript:;" data-value="3457" title="5.1 相交线">第三级0003</a></li>';
        //                    treeHtml+='</ul>';
        //                treeHtml+='</li>';
        //            treeHtml+='</ul>';
        //        treeHtml+='</li>';
        //treeHtml+='</ul>';
        //
        //
        //var html='<div id="bskt_conf_hmwk_Box" class="popBox bskt_conf_hmwk_Box hide" title="确认出题" >';
        //        html+='<div class="popCont">';
        //            html+='<div class="subTitleBar"><h5>完善作业信息</h5></div>';
        //            html+='<div class="sUI_formList" style="padding-bottom: 0">';
        //                html+='<div class="row">名称：<input type="text" class="text"  style="width: 402px"></div>';
        //                     html+='<div class="row">';
        //                        html+='<label>学段：<select id="sel_term">';
        //                            html+='<option>请选择</option>';
        //                            html+='<option>小学</option>';
        //                            html+='<option>中学</option>';
        //                        html+='</select> </label>';
        //                        html+='<label>学科：<select class="sel_sbj">';
        //                            html+='<option>请选择</option>';
        //                            html+='<option>语文</option>';
        //                            html+='<option>数学</option>';
        //                        html+='</select> </label>';
        //                        html+='<label>版本：<select id="sel_ver" class="sel_ver">';
        //                            html+='<option>请选择</option>';
        //                            html+='<option>人教版</option>';
        //                            html+='<option>苏教版</option>';
        //                        html+='</select> </label>';
        //                        html+='<label>分册：<select id="sel_vol" class="sel_vol">';
        //                            html+='<option>请选择</option>';
        //                            html+='<option>第一册</option>';
        //                            html+='<option>第二册</option>';
        //                        html+='</select> </label>';
        //                html+='</div>';
        //                html+='<div class="row">';
        //                    html+='章节：<div class="chapter_sel clearfix">';
        //                        html+='<div class="cha_box cha_l">';
        //                            html+=treeHtml;
        //                        html+='</div>';
        //                    html+='<div class="cha_box cha_c">';
        //                    html+='<br><br><button id="add_custom_btn" type="button" class="bg_blue">添加</button><br><br>';
        //                    html+='<button id="del_custom_btn" type="button" class="">删除</button>';
        //                html+='</div>';
        //            html+='<div class="cha_box cha_r"><ul id="custom_sel_list" class="custom_sel_list"></ul></div>';
        //        html+='</div></div>';
        //    html+='</div>';
        //html+='</div>';
        //html+='<div class="popBtnArea">';
        //html+='<button type="button" class="okBtn">确定</button><button type="button" class="cancelBtn">取消</button>';
        //html+='</div></div>';
        //
        //
        //$('body').append(html);
        $('#pointTree').tree();



        //初始化弹框
        $('.popBox').dialog({
            autoOpen: true,
            width:840,
            modal: true,
            resizable:false,
            close:function(){$(this).remove()}
        });



        $('#bskt_conf_hmwk_Box .cancelBtn').click(function(){
            $('#bskt_conf_hmwk_Box').hide();
        });

        //选择 学段 学科……
        $('select').change(function(){
            alert('ajax');
            if($(this).attr('id')=="sel_vol" ||$(this).attr('id')=="sel_ver"){
                $('#custom_sel_list').empty();
            }
        });

        //向右侧添加节点
        $('#add_custom_btn').click(function(){
            var sel_item=$('#pointTree .ac');
            var txt=sel_item.text();
            var id=sel_item.attr('data-value');
            if($('#custom_sel_list #'+id).size()!=1){
                $('#custom_sel_list').append('<li id="'+id+'">'+txt+'</li>');
            }
            else{ popBox.errorBox('已添加!')}
        });




        //选择右侧"已选"项
        $('.cha_r').on('click','#custom_sel_list li',function(){
            $(this).addClass('ac').siblings().removeClass('ac');
        });

        $('#del_custom_btn').click(function(){
            if($('#custom_sel_list .ac').size()>0){
                var id=$('#custom_sel_list .ac').attr('id');
                $('#'+id).remove();
            }
            else{ popBox.errorBox('请选择要删除项目!') }


        })




    };





    //显示确认菜单
    $('#conformBtn').click(function(){
        $('#conformList').show();
        return false;
    })
    //显示弹框
    $('#conformList a').click(function(){
        var popBoxID=$(this).attr('data-popBox');
        switch(popBoxID){
            case "hmwk":
            bskt_conf_hmwk_Box();

            break;
        };
        $('#conformList').hide();
    });



    //查看解析答案按钮
    $('.show_aswerBtn').click(function(){
        var _this=$(this);
        var pa=_this.parents('.quest')
        pa.toggleClass('A_cont_show');
        _this.toggleClass('icoBtn_close');
        if(pa.hasClass('A_cont_show')) _this.html('收起答案解析 <i></i>');
        else _this.html('查看答案解析 <i></i>');
    });


    //选题蓝上移下移
    $('.move_up_btn,.move_down_btn').click(function(){
        var originQuest = $(this).parents('.quest');
        var prevQuest = originQuest.prev();
        var nextQuest = originQuest.next();
        var originTitle = originQuest.children('.quest_title');
        var prevTitle = prevQuest.children('.quest_title');
        var nextTitle = nextQuest.children('.quest_title');
        var originPlace = originQuest.children('.pd25');
        var prevPlace = prevQuest.children('.pd25');
        var nextPlace = nextQuest.children('.pd25');
        if(prevQuest[0] && ($(this).attr('class') == 'move_up_btn')){
            originQuest.css('z-index',1);
            prevQuest.css('z-index',0);
            originQuest.animate({top:'-'+prevQuest.height()+'px'},'slow',function(){
                originPlace.insertAfter(prevTitle);
                prevPlace.insertAfter(originTitle);
            });
            originQuest.animate({top:0},0);
            prevQuest.animate({top:'+'+originQuest.height()+'px'},'slow');
            prevQuest.animate({top:0},0);
        }else if(nextQuest[0] && ($(this).attr('class') == 'move_down_btn')){
            originQuest.css('z-index',1);
            nextQuest.css('z-index',0);
            originQuest.animate({top:'+'+nextQuest.height()+'px'},'slow',function(){
                originPlace.insertAfter(nextTitle);
                nextPlace.insertAfter(originTitle);
            });
            originQuest.animate({top:0},0);
            nextQuest.animate({top:'-'+originQuest.height()+'px'},'slow');
            nextQuest.animate({top:0},0);
        }
    });
    (function(){
        var allQuest = $('.quest');
        var firstQuest = allQuest.first();
        var lastQuest = allQuest.last();
        firstQuest.find('.move_up_btn').css('color','#bbb');
        lastQuest.find('.move_down_btn').css('color','#bbb');
    })();



})
