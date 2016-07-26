<?php
/**
 * Created by Unizk.
 * User: ysd
 * Date: 14-12-3
 * Time: 下午1:52
 */
/* @var $this yii\web\View */  $this->title="教师答疑列表";
?>
<script type="text/javascript">
    $(function(){
        //解答问题
        $('.red_btn_js2').live('click',function(){
            var aqid = $(this).attr('val');
            var answer = $(".textarea.textarea_js" + aqid).val();
            if(answer=='')
            {
                popBox.errorBox("内容不能为空!");
                return false;
            }
            else
            {
                $.post('<?php echo url('teacher/Default/result-question');?>',{answer:answer,aqid:aqid},function(data){
                    if(data.success){
                        location.reload();
                    }else{
                        popBox.alertBox(data.message);
                    }
                });
                $(this).parent().parent('.pop_up_js').hide();
            }
        });

        /*增加同问的数字*/
        $('.q_add').one('click',function(){
            var creatorid = $(this).attr('user');
            var userid = "<?php echo user()->id;?>";
            if(creatorid == userid){
                return false;
            }else{
                var aqid = $(this).attr('val');
                $.post('<?php echo url('teacher/default/same-question');?>',{aqid:aqid},function(data){
                    if(data.success){
                        location.reload();
                    }else{
                        //popBox.alertBox(data.message);
                    }
                })
            }
        });

        /*点击采用变成已采用*/
        $('.btn_c').live('click',function(){
            var resultid = $(this).attr('val');
            $.post('<?php echo url('teacher/default/use-the-answer');?>',{resultid:resultid},function(data){
                if(data.success){
                    location.reload();
                }else{
                    popBox.alertBox(data.message);
                }
            })
        });

        $('.red_gray_js').live('click',function(){
            $(this).parent().parent('.pop_up_js').hide();
            $(this).parent().siblings('.textarea_js').val('');
        });

        $('.red_btn_0js').live('click',function(){
            $(this).parent().next('dd').children('.pop_up_js').hide();
            $(this).parent().next('dd').children('.pop_upD_js').show();
        });

        $('.red_btn_js').live('click',function(){
            $(this).parent().next('dd').children('.pop_up_js').show();
            $(this).parent().next('dd').children('.pop_upD_js').hide();
        })

    })

</script>
<script type="text/javascript">
    $(function(){
        //增加提示信息
        $('.search_ansewr').append('<i>一句话描述</i>');
        $('.search_ansewr_text').focus(function(){
            var _this=$(this);
            _this.siblings('i').hide();
        });
        $('.search_ansewr_text').blur(function(){
            var _this=$(this);
            if(_this.val()!='')
            {
                _this.siblings('i').hide();
            }
            else
            {
                _this.siblings('i').show();
            }

        });

        //我的问题弹窗
        $('.myQuestion').click(function(){
            $('#my_question').dialog({
                autoOpen: false,
                width:400,
                modal: true,
                resizable:false,
                buttons: [
                    {
                        text: "前去检索",

                        click: function() {
                            if($('#mySchoolPop .text').val()==1){
                                $( this ).dialog( "close" );
                            }
                            else{
                                location.href = '<?php echo url('/ku/Answer/answer-questions');?>';
                            }
                        }
                    },
                    {
                        text: "我要提问",

                        click: function() {
                            if($('#mySchoolPop .text').val()==1){
                                $( this ).dialog( "close" );
                            }
                            else{
                                if('<?php echo loginUser()->isStudent();?>'){
                                    location.href = '<?php echo url('student/answer/add-question',array('studentId'=>app()->request->getParam('studentId','')));?>';
                                }else if('<?php echo loginUser()->isTeacher();?>'){
                                    location.href = '<?php echo url('teacher/answer/add-question',array('teacherId'=>app()->request->getParam('teacherId','')))?>);?>';
                                }
                            }
                        }
                    }
                ]
            });
            $( "#my_question" ).dialog( "open" );
            return false;
        });

        //点击搜索按钮
        $('#search_word').click(function(){
            var content = $('#search_ansewr_text_content').val();
            $.get('<?php echo url("teacher/default/answer-questions");?>',{content:content},function(data){
                $('#answerquestions').html(data);
            });
        });
    })

</script>

<div class="centLeft hisQuLeft">
    <div class="noticeH hisQuestion clearfix">
        <h3 class="h3b">答疑管理</h3>
        <div class="search_ansewr fl" style="top:10px;">
            <input type="text" class="text search_ansewr_text" id="search_ansewr_text_content">
            <button type="button" class="bg_red_d searchBtn" id="search_word">搜索</button>
        </div>
        <a href="javascript:;" class="a_button bg_green_d answer_question myQuestion">我要提问</a>
    </div>
    <hr>
    <div id="answerquestions">
        <?php echo $this->render('_answerquestions_list', array('modelList'=>$modelList,'pages' => $pages))?>
    </div>
</div>
<div class="centRight">
    <div class="item Ta_teacher">
        <h4>Ta的老师</h4>

        <ul class="teacherList">
            <li>
                <img src="../images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="../images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="../images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="../images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="../images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="../images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="../images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="../images/user_m.jpg">
                张三丰
            </li>
            <li>
                <img src="../images/user_m.jpg">
                张三丰
            </li>
        </ul>


    </div>
</div>
</div>
<!--主体内容结束-->
<!--新增加我要提问弹窗-->
<div id="my_question" class="my_question popoBox hide " title="答疑管理">
    <div class="impBox">
        <form>
            <div class="answer_text" style="text-align:center; line-height: 55px;">
                请先看一下是否已有相同问题
            </div>

        </form>
    </div>
</div>
