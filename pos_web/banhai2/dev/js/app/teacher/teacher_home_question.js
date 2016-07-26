define(['jquery_sanhai','popBox','jqueryUI'],function(jquery_sanhai,popBox){

////////////////////////////////////////////////////////////platform_question_search

////////////////////////////////////////////////////////////

  $('#modify').dialog({
    autoOpen: false,
    width: 350,
    modal: true,
    resizable: false
  });
  $('#addGroup').dialog({
    autoOpen: false,
    width: 350,
    modal: true,
    resizable: false
  });
  $('#delGroup').dialog({
    autoOpen: false,
    width: 350,
    modal: true,
    resizable: false
  });
  $('#delQuestion').dialog({
    autoOpen: false,
    width: 350,
    modal: true,
    resizable: false
  });
  $.fn.extend({
    checkAll:function(arr){
      return this.each(function(){
        var _this = $(this);
        var num = 0;
        _this.click(function(){
          if(_this.is(":checked")){
            arr.prop("checked",true);
            num = arr.length;
          }else{
            arr.prop("checked",false);
            num = 0;
          }
        });
        arr.each(function(){
          $(this).click(function(){
            if($(this).is(":checked")){
              num++;
            }else{
              num--;
            }
            if(num == arr.length){
              _this.prop("checked",true);
            }else{
              _this.prop("checked",false);
            }
          })
        })
      })
    }
  });
  $(".allCheck").checkAll($(".oneCheck"));
  

  $('.collections .modify').click(function(){
    $('#modify').dialog('open');
  });
  $('.custom_groups .addGroup').click(function(){
    $('#addGroup').dialog('open');
  });
  $('.collections .delGroup').click(function(){
    $('#delGroup').dialog('open');
  });
  $('.manipulate .move').click(function(){
    //if($('.manipulate .move_to').css('display') == 'none'){
    //  $('.manipulate .move_to').css('display','block');
    //}else{
    //  $('.manipulate .move_to').css('display','none');
    //}
    $('.manipulate .move_to').show();
    return false;
  });
  $('.manipulate .move_to li a').click(function(){
    if($('.oneCheck:checked').length <= 0){
      popBox.errorBox('请选择要移动的题目！');
    }else{
      popBox.successBox('题目移动成功！');
    }
  });
  $('.manipulate .remove').click(function(){
    if($('.oneCheck:checked').length <= 0){
      popBox.errorBox('请选择要删除的题目！');
    }else{
      $('#delQuestion').dialog('open');
      popBox.successBox('题目删除成功！');
    }
  });


  //打开课程列表
  $('#show_sel_classesBar_btn').click(function(){
    $(".sel_classesBar").slideDown();
    return false;
  });
  //添加选题篮
  $('.join_basket_btn').click(function(){
    var _this=$(this);
    var pa=_this.parents('.quest');
    var q_num=$('.q_num').html();
    if(pa.hasClass('join_basket')){
      pa.removeClass('join_basket');
      q_num--;
    }else{
      pa.addClass('join_basket');
      q_num++;
    }
    $('.q_num').html(q_num);
    //$('.q_num').html($('.join_basket').size());
  })


});