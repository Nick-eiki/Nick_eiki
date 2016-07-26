define(['sanhai_tools'],function(sanhai_tools){
    function init(tag,card_json){
        var _this=$(tag);
        var top=_this.offset().top-50;
        var left=_this.offset().left+25;
       if(!sanhai_tools.vertical_position(_this))top-=240;//判断垂直位置
        var course_html='';
        var stu_school_html='';
        var course_html='';
        if(card_json.courseName!=""){
            var course=card_json.courseName;
            for(var i=0; i<course.length; i++){
                course_html+='<span class="cls_ico bg_'+card_json.courseClass[i]+'">'+course[i]+'</span>';
            }
        }
        var html='<div id="userCard" class="userCard">';
        html+='<div class="card_t">扫码聊天<i class="arrow"></i></div>';
        html+='<img class="QRCode" src='+card_json.QRCode+'>';
        html+='<img class="userHeadPic" src='+card_json.headImg+'>';
        html+='<h5>'+card_json.name+'</h5>';
        html+='<ul>';
        html+='<li class="userCard_teacher"><i></i>'+card_json.identity+course_html+'</li>';
        html+='<li class="userCard_school"><i></i>就读于&nbsp;&nbsp;'+card_json.stu_school+'</li>';
        html+='</ul>';
        html+='</div>';
        $('body').append(html);
        $('#userCard').css({left:left,top:top,zIndex:100,margin:20,display:'none'});
       // $('#userCard').hide();
    }
    return{userCard:init};


})