<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-11-15
 * Time: 上午11:34
 */
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */  $this->title="在线答题";
?>
<div class="currentRight grid_16 push_2 online_answer">
<div class="noticeH clearfix noticeB">
    <h3>在线答题</h3>

    <p>
        <span>组卷人：<?php echo loginUser()->getUserInfo($homeworkResult->creator)->getTrueName();?></span>
        <span>时间：<?php echo $homeworkResult->uploadTime?></span>
    </p>

</div>
<hr>
<div class="work_detais_cent">
    <h4>作业名称</h4>
    <ul class="ul_list">
        <li><span>1、</span>考察知识点：<?php echo  KnowledgePointModel::findKnowledgeStr($homeworkResult->knowledgeId)?></li>
        <li><span>2、</span>本试卷共包含10道题目，其中
           <?php foreach($homeworkResult->qeustionTypeNumList as $k=>$v){
               echo $k<count($homeworkResult)?$v->questiontypename.$v->cnum."道，":$v->questiontypename.$v->cnum."道";
           }?>
        </li>
        <li><span>3、</span>各题目分值情况：1--10分，2--10分，共计100分</li>
        <li><span>4、</span>答题时间控制在 40 分钟内，其中选择题必须在线回答，填空题与应用题提交手写答案</li>

    </ul>
    <div class="btn_online">
        <button type="button" class="mini_btn btn_line" id="btn_line_js">开始答题</button>
        <div class="time-item">
            <!-- <span id="day_show">0天</span>-->
            <strong id="hour_show">0时</strong>
            <strong id="minute_show">0分</strong>
            <strong id="second_show">0秒</strong>
        </div>
    </div>
    <div id="uploadId">
        <div class="schResult">
            <div class="testPaperView pr">
                <div class="paperArea">

                    <?php foreach ($homeworkResult->questionList  as $key => $item) {?>
                        <div class="paper">

                            <?php echo $this->render('//publicView/paper/_itemAnswerType', array('item' => $item)); ?>

                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="online_coom">

        <div class="btnD">
            <button class="mini_btn btn btn_js " id="finish" >完成答题</button>
        </div>
    </div>



</div>


</div>
<script>

    $(function () {

        /*删除按钮*/
        $('.minute li i').live('click', function () {
            $(this).parent().remove();
        });

//完成答题

        $('.btn_js').click(function () {
            /*上传试卷*/
            $('#dati').dialog({
                autoOpen: false,
                width: 600,
                modal: true,
                resizable: false,
                buttons: [
                    {
                        text: "完成测试",

                        click: function () {
                            if ($('#mySchoolPop .text').val() == 1) {
                                $(this).dialog("close");
                            }
                            else {
                              //  alert(4545)
                            }

                        }
                    },
                    {
                        text: "取消",

                        click: function () {
                            $(this).dialog("close");
                        }
                    }

                ]
            });
            $("#dati").dialog("open");
            //event.preventDefault();
            return false;
        });


        /*删除添加的试卷*/
        $('.up_img_js li').live('mouseover', function () {
            $(this).children('i').show();
        });
        $('.up_img_js li').live('mouseout', function () {
            $(this).children('i').hide();
        });
        $('.up_img_js li i').live('click', function () {
            $(this).parent().remove();
        });

//更多图片
        $('.more_js').toggle(function () {
                $(this).parent('ul').css('height', 'auto')
            },
            function () {
                $(this).parent('ul').css('height', '50px')
            }

        )

    })
</script>
<script type="text/javascript">
    $(function () {
        //显示选中的值--单选
        var radio = $(".alternative");
        var arr_d = [];
        radio.live('click', function () {
            $(this).parents('dd').siblings('.stu_answer').text('');

            if (this.checked == true) {
                arr_d.push(arr_d);
                var text = $(this).siblings('i').text();
                $(this).parents('dd').siblings('.stu_answer').append('<em>' + text + '</em>');
            }


        });

        //显示选中的值--多选
        var ck = $(".checkbox");
        var arr_select = [];
        ck.live('click', function () {
            //显示放答案的标签
            $(this).parents('dd').next('.stu_answer').show();
            //先清空掉
            $(this).parents('dd').siblings('.stu_answer').children('em').text('');
            //$(this).parents('dd').siblings('.stu_answer').text('');

            var text = $(this).siblings('i').text();
            if (this.checked == true) {
                //arr.push(text);

                //如果数组里面没有找到text   就让他插入一个
                if (arr_select.indexOf(text) == -1) {
                    arr_select.push(text);
                }
            }
            else {
                //否则的话 就删掉你现在取消掉的这个东西
                if (!('indexOf' in Array.prototype)) {
                    Array.prototype.indexOf = function (find, i /*opt*/) {
                        if (i === undefined) i = 0;
                        if (i < 0) i += this.length;
                        if (i < 0) i = 0;
                        for (var n = this.length; i < n; i++)
                            if (i in this && this[i] === find)
                                return i;
                        return -1;
                    };
                }

                arr_select.splice(arr_select.indexOf(text), 1);
            }
            document.title = arr_select;


            //插入数组里面的东西
            for (var i = 0; i < arr_select.length; i++) {
                $(this).parents('dd').siblings('.stu_answer').append('<em>' + arr_select[i] + '</em>')
            }
        });


        //显示选中的值--完形单选
        var radio = $('.alternative');
        var arr_d = [];
        radio.live('click', function () {
            $(this).parents('.an_ul').next('.answer').text('');

            if (this.checked == true) {
                arr_d.push(arr_d);
                var text = $(this).siblings('i').text();
                $(this).parents('.an_ul').next('.answer').append('<em>您的答案是：' + text + '</em>');
            }


        });


        /*移入显示删除按钮*/
        $('.minute li').live('mouseover mouseout', function (event) {

            if (event.type == 'mouseover') {
                $(this).children('i').show();
            } else {
                $(this).children('i').hide();
            }
        });
        /*删除按钮*/
        $('.minute li i').live('click', function () {
            $(this).parent().remove();
        })

    })
</script>
<script type="text/javascript">
    var intDiff = parseInt(10);//倒计时总秒数量
    function timer(intDiff) {
      var off=  window.setInterval(function () {
            var day_show = $('#day_show');//天
            var hours = $('#hour_show');//小时
            var minutes = $('#minute_show');//分钟
            var seconds = $('#second_show');//秒
            var day = 0,
                hour = 0,
                minute = 0,
                second = 0;//时间默认值
            if (intDiff > 0) {
                //day = Math.floor(intDiff / (60 * 60 * 24));
                hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
                minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
                second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
            }
            if (minute <= 9) minute = '0' + minute;
            if (second <= 9) second = '0' + second;
            if (second == 0) {
                clearTimeout(off);
                popBox.errorBox('不要意思，时间到');
            }
            //day_show.html(day+"天");
            hours.html('<s id="h"></s>' + hour + '时');
            minutes.html('<s></s>' + minute + '分');
            seconds.html('<s></s>' + second + '秒');
            intDiff--;
        }, 1000);


    }
    $(function () {
        $('#btn_line_js').one('click', function () {
            $(this).css('background', '#ccc');
            timer(intDiff);

        });
        $("#finish").click(function(){
            var bigArray=[];
          $(".bigTitle").each(function(index,el){
              var bigTitleID=$(el).find(".bigTitleID").val();
              if($(el).find(".bigType").val()=="5"||$(el).find(".bigType").val()=="6"||$(el).find(".bigType").val()=="7"){
              var bigTitle=[];
            $(el).find(".middleTitle").each(function(indexx,ell){
                var middleTitleID=$(ell).find(".middleTitleID").val();
                if($(ell).find(".smallTitle").length>0){
                var smallTitleObj=[];
                  $(ell).find(".smallTitle").each(function(indexxx,elll){
//                    获取图片答案路径并且转成字符串
                      smallImageArray=[];
                 $(elll).find(".addImage").find("li").each(function(n,e){
                      smallImageArray.push($(e).find("img").attr("src"));
                  });
                      var smallImage=smallImageArray.join(",");
                   var smallTitle={"questionId":$(elll).find(".smallTitleID").val(),answerUrl:smallImage};
                    smallTitleObj.push(smallTitle);
                  });

                    var middleTitle={"questionId":middleTitleID,"detail":smallTitleObj};
                }
               if($(ell).find(".type").val()==1){
                     var middleAnswer=$(ell).find("[type=radio]:checked").val();
                    var middleTitleID=$(ell).find(".middleTitleID").val();
                   var middleTitle={"questionId":middleTitleID,"answerOption":middleAnswer};
               }
                if($(ell).find(".type").val()==2){
//                    获取复选框内容
                    var middleAnswerArray=[];
                    $(ell).find("[type=checkbox]").each(function(n,e){
                      if($(e).attr("checked")=="checked"){
                           middleAnswerArray.push($(e).val());
                      }
                    });
                    var middleAnswer=middleAnswerArray.join(",");

                    var middleTitle={"questionId":middleTitleID,"answerOption":middleAnswer};
                }
                if($(ell).find(".smallTitle").length<1&&$(ell).find(".type").val()!=1&&$(ell).find(".type").val()!=2){
                    middleImageArray=[];
                    $(ell).find(".addImage").find("li").each(function(n,e){
                        middleImageArray.push($(e).find("img").attr("src"));
                    });
                    var middleImage=middleImageArray.join(",");
                    var middleTitle={"questionId":middleTitleID,"answerUrl":middleImage};
                }
             bigTitle.push(middleTitle);
              })

              }
              if($(el).find(".bigType").val()=="1"){
                  var bigTitle=[];
                  var bigAnswer=$(el).find("[type=radio]:checked").val();
                  var bigTitleID=$(el).find(".bigTitleID").val();
                  var bigTitleObj={"questionId":bigTitleID,"answerOption":bigAnswer};
                  bigTitle.push(bigTitleObj);
              }
              if($(el).find(".bigType").val()=="2"){
                  var bigAnswerArray=[];
                  $(el).find("[type=checkbox]").each(function (n, e) {
                      if($(e).attr("checked")=="checked"){
                          bigAnswerArray.push($(e).val());
                      }
                  });
                  var bigAnswer=bigAnswerArray.join(",");
                  var bigTitle=[];
                  var bigTitleObj={"questionId":bigTitleID,"answerOption":bigAnswer};
                  bigTitle.push(bigTitleObj);
              }
              if($(el).find(".bigType").val()=="4"){
                  var bigTitle=[];

//                    获取图片答案路径并且转成字符串
                      bigImageArray=[];
                      $(el).find(".addImage").find("li").each(function(n,e){
                          bigImageArray.push($(e).find("img").attr("src"));
                      });
                      var   bigImage=bigImageArray.join(",");
                  var bigTitleObj={"questionId":bigTitleID,"answerUrl":bigImage};
                  bigTitle.push(bigTitleObj);

          }if($(el).find(".bigType").val()=="3"){
                  if($(el).find(".middleTitle").length>0){
                      var bigTitle=[];
                     $(el).find(".middleTitle").each(function(n,e){
                      var middleTitleID=$(e).find(".middleTitleID").val();
                      var middleImageArray=[];
                         $(e).find(".addImage").find("li").each(function(nn,ee){
                             middleImageArray.push($(ee).find("img").attr("src"));
                         });
                         var middleImage=middleImageArray.join(",");
                         bigTitle.push({"questionId":middleTitleID,"answerUrl":middleImage});
                     })
                  }else{
                      var middleImageArray=[];
                      var bigTitle=[];
                      $(el).find(".addImage").find("li").each(function(n,e){
                          middleImageArray.push($(e).find("img").attr("src"));
                      });
                      var middleImage=middleImageArray.join(",");
                      var bigTitleObj={"questionId":bigTitleID,"answerUrl":middleImage};
                      bigTitle.push(bigTitleObj);
                  }
              }

			  if($(el).find(".bigType").val() == "8"){
				  var bigTitle=[];
				  var bigTitleID = $(el).find(".bigTitleID").val();
				  var smallTitleObj=[];
				  //获取图片答案路径并且转成字符串
				  smallImageArray=[];
				  $(el).find(".addImage").find("li").each(function(n,e){
//                  console.info($(e).find("img").attr("src"));
					  smallImageArray.push($(e).find("img").attr("src"));
				  });
				  var smallImage=smallImageArray.join(",");
				  var smallTitle={"questionId":$(el).find(".bigTitleID").val(),answerUrl:smallImage};
				  bigTitle.push(smallTitle);
			  }

              var bigTitleAnswer={"mainQusId":bigTitleID,"detail":bigTitle};
              bigArray.push(bigTitleAnswer);
              });
            var titleObj={"answers":bigArray};
            var homeworkID="<?php echo app()->request->getQueryParam('homeworkID')?>";
            $.post("<?php echo url('student/managetask/finish-answer')?>",{"answerList":JSON.stringify(titleObj),"homeworkID":homeworkID},function(result){
                if(result.code=="0"){
                    popBox.alertBox(result.message);
                } else{
                    popBox.alertBox(result.message);
                    location.href="<?php echo url('student/managetask/index')?>";
                }
            })

        })


    });
</script>