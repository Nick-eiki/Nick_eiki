define(['jquery_sanhai','popBox','jqueryUI'],function(jquery_sanhai,popBox){

////////////////////////////////////////////////////////////platform_question_bank

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
  $('#delCourse').dialog({
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
    if($('.manipulate .move_to').css('display') == 'none'){
      $('.manipulate .move_to').css('display','block');
    }else{
      $('.manipulate .move_to').css('display','none');
    }
  });
  $('.manipulate .move_to li a').click(function(){
    if($('.oneCheck:checked').length <= 0){
      popBox.errorBox('请选择要移动的课件！');
    }else{
      popBox.successBox('课件移动成功！');
    }
  });
  $('.manipulate .remove').click(function(){
    if($('.oneCheck:checked').length <= 0){
      popBox.errorBox('请选择要删除的课件！');
    }else{
      $('#delCourse').dialog('open');
      popBox.successBox('课件删除成功！');
    }
  });



});