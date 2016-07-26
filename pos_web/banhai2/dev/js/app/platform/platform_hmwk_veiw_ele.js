define(['popBox','jquery_sanhai','jqueryUI'],function(popBox,jquery_sanhai){

  $('.textareaBox').speak('请输入意见反馈...',300);

  //查看解析答案按钮
  $('.show_aswerBtn').click(function(){
    var _this=$(this);
    var pa=_this.parents('.quest')
    pa.toggleClass('A_cont_show');
    _this.toggleClass('icoBtn_close');
    if(pa.hasClass('A_cont_show')) _this.html('收起答案解析 <i></i>');
    else _this.html('查看答案解析 <i></i>');
  });


  $('#popBox1').dialog({
    autoOpen: false,
    width: 720,
    modal: true,
    resizable: false,

    buttons: [{
      text: "确定",
      click: function() {
        alert("安排");
      }
    },{
      text: "取消",
      click: function() {
        $(this).dialog("close");
      }
    }
    ]
  });
  $('#popBox2').dialog({
    autoOpen: false,
    width: 720,
    modal: true,
    resizable: false,
    buttons: [{
      text: "确定",
      click: function() {
        $(this).dialog("close");
      }
    }

    ]
  });
  function placeholder(obj, defText) {
    obj.val(defText)
      .css("color", "#ccc")
      .focus(function() {
        if ($(this).val() == defText) {
          $(this).val("").css("color", "#333");
        }
      }).blur(function() {
        if ($(this).val() == "") {
          $(this).val(defText).css("color", "#ccc");
        }
      });
  }
  /*布置给学生弹窗*/
  $('#upbtn').click(function() {
    if(true){
      popBox.confirmBox("你还没有放置作业，是否现在放置？",function(){
        //alert(2342);
        $("#popBox1").dialog("open");
      })
    }
    else{
      $("#popBox1").dialog("open");
    }
  });


  /*放入作业弹窗*/
  $('#upbtnBox').click(function() {
    $("#popBox2").dialog("open");
  });

  $('.textareaBox').speak('请输入您的反馈意见',300);

  //$(".myclass_table dt").click(function(){
  //  $(this).parent().toggleClass("ac");
  //})

  $(".myclass_table dt span").click(function(){
    var checkbox = $(this).siblings("input");
    if(checkbox.is(":checked")){
      checkbox.prop("checked",false);
    }else{
      checkbox.prop("checked",true);
    }
    pitchOn(checkbox);
  });

  $(".myclass_table dt input").click(function(){
    var checkbox = $(this);
    pitchOn(checkbox);
  });

  function pitchOn(checkbox){
    var dt = checkbox.parent('dt');
    if(checkbox.is(":checked")){
      var classId = dt.attr('data-id');
      dt.siblings("input").val(classId);
    }else{
      dt.siblings("input").val('');
    }
  }

});



