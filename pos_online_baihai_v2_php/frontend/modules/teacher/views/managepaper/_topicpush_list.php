<?php
/**
 * Created by unizk
 * User: ysd
 * Date: 14-11-18
 * Time: 下午6:26
 */
use frontend\components\helper\ViewHelper;
use frontend\models\dicmodels\KnowledgePointModel;
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title="题目推送列表";
?>
<script>
    $(function () {
        $('#titlePush_Box').dialog({
            autoOpen: false,
            width: 700,
            modal: true,
            resizable: false,
            buttons: [
                {
                    text: "确定",
                    click: function () {
                        var classID = $('#class').val();
                        var studentNum = $("#student-num").val();
                        if (classID == "") {
                            popBox.alertBox('班级不能为空！');
                            return false;
                        } else if(studentNum == ''){
                            popBox.alertBox('请选择学生！');
                            return false;
                        }
                        if ($('#form_id').validationEngine('validate')) {
                            var option = $("#student-num").val();
                            if (option == 'single') {
                                var num_stu = $(".stu_sel_list.clearfix li").text();
                                if (num_stu == '') {
                                    popBox.alertBox('请选择指定人！');
                                    return false;
                                } else {
                                    $form_id = $('#form_id');
                                    $.post("<?php echo url('teacher/managepaper/title-push')?>", $form_id.serialize(), function (data) {
                                        if (data.success) {
                                            location.reload();
                                        } else {
                                            popBox.alertBox(data.message);
                                        }
                                    })
                                }
                            }

                            if (option == 'all') {

                                $form_id = $('#form_id');
                                $.post("<?php echo url('teacher/managepaper/title-push')?>", $form_id.serialize(), function (data) {
                                    if (data.success) {
                                        location.reload();
                                    } else {
                                        popBox.alertBox(data.message);
                                    }
                                })
                            }
                        }
                        $(this).dialog("close");
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

        $('.titlePush_List .pushBtnJs').click(function () {
            var questionTeamID = $(this).val();
            $('#questionTeamID').val(questionTeamID);
            $("#titlePush_Box").dialog("open");
            var studentNum = $("#student-num").val();
            if(studentNum == 'single') {
                $('.selectForJs').show();
            }else{
                $('.selectForJs').hide();
            }
            return false;
        });

        //添加指定人到列表
        //$('.stu_sel_list').height('0');
        function add_student() {
            var _selLi = $('#multi_resultList .ac');
            var html = '';
            for(var i=0, _len=_selLi.length; i<_len; i++){
                var _curEl = _selLi.eq(i);
                html += '<li data_user="' + _curEl.attr('data_user') + '" class="multiLi" ><p>'+ _selLi.eq(i).html()  +'</p><span class="delBtn"><input type="hidden" name="receiver[]" value="' +_curEl.attr('data_user') + '"></span></li>';
            }
            $('#choose_stu_list').html(html);
        }

        //删除已选学生
        $('.stu_sel_list li i').live('click', function () {
            $(this).parent().remove();
        });

        $('#stuListBox').dialog({
            autoOpen: false,
            width: 500,
            modal: true,
            resizable: false,
            buttons: [
                {
                    text: "确定",
                    click: function () {
                        add_student();
                        $(this).dialog("close");
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

        //指定人按钮的显示/隐藏切换
        $("#student-num").change(function () {
            var stunum = $(this).val();
            if (stunum == 'single') {
                $('.selectForJs').show();
               // $('.stu_more').show();
               // $('.stu_more').text('展开全部');
            } else {
                $('.stu_sel_list.clearfix').children().remove();
                if ($("#student-num").val() != '部分学生') {
                    $('.stu_more').hide();
                }
                $('.selectForJs').hide();
            }
        });

        //点击指定人按钮查询学生列表
        $('.selectForJs').click(function () {
            var classID = $('#class').val();
            var studentNum = $("#student-num").val();
            if (classID == "") {
                popBox.alertBox('班级不能为空！');
                return false;
            } else if(studentNum == ''){
                popBox.alertBox('请选择学生！');
                return false;
            } else{

                $.post('<?php echo url('teacher/managepaper/get-class');?>', {classId: classID}, function (data) {
                    $('#stuListBox').html(data);
                    var selList=$('#choose_stu_list li');
                    var _len = selList.length;
                    if(_len>0){
                        $('#stuListBox li').removeClass('ac');
                        for(var i=0; i<_len; i++){
                            var _userId = selList.eq(i).attr('data_user');
                            $( '#stuListBox li[data_user="' + _userId + '"]').addClass('ac');
                        }
                    }
                    $("#stuListBox").dialog("open");

                    return false;
                });
            }
        });

        //更多
        $('.more').toggle(function(){
            $(this).siblings('.content').css('height','auto');
            $(this).text('收起')
        },function(){
            $(this).siblings('.content').css('height','24px');
            $(this).text('更多')
        });

        //更多
        $('.txtOverCont').openOverCont({width:780});

    });


</script>

<div class="titlePush_main">
    <ul class="titlePush_List">
        <?php
        if($model):
        foreach ($model as $val) {
            ?>
            <li class="pr clearfix">
                <h5>[<?php echo $val->gradename; ?> <?php echo $val->subjectname; ?>]
                <a href="<?php echo url("teacher/managepaper/detail-paper", array('questionTeamID' => $val->questionTeamID)); ?>"
                   class="title_p"><?php echo Html::encode($val->questionTeamName); ?></a>
                </h5>
                <p>知识点：<em><?php
                        $res = KnowledgePointModel::findKnowledge($val->connetID);
                        foreach ($res as $value) {
                            echo $value->name . '&nbsp;&nbsp;';
                        }
                        ?></em></p>

                <div class="clearfix titlePush_more">
                    <div class="content fl gray_d">
                        <em class="fl">推送记录：</em>
                        <div class="txtOverCont fl">

                            <div class="cont">
                                <?php
                                $num = 0;
                                foreach ($val->deliverList as $key_time => $time) {
                                    echo '<b>' . date("Y-m-d H:i",strtotime($time->notesTime)) . '</b>&nbsp;&nbsp;';
                                    $num++;
                                }
                                ?>
                            </div>

                            <?php if($val->isSend==1){?>
                                <?php if($num > 4){?>
                                    <span class="openContBtn">展开</span>
                                <?php }?>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <div class="check_div">
                    <?php if (isset($val->quesCnt) && $val->quesCnt > 0) { ?>
                        <button class="pushBtn pushBtnJs btn w100 bg_green" value="<?php echo $val->questionTeamID; ?>">
                            推送
                        </button>
                    <?php } ?>
                    <?php if ($val->isSend == '0') { ?>
                        <a href="<?php echo url("teacher/managepaper/reset-paper", array('questionTeamID' => $val->questionTeamID)) ?>"
                           class="rearrangeBtn a_button bg_blue w100">重新布置</a>
                    <?php } ?>
                </div>
            </li>
        <?php }
        else:
            ViewHelper::emptyView();
        endif;
        ?>
    </ul>
        <?php
         echo \frontend\components\CLinkPagerExt::widget( array(
               'pagination'=>$pages,
                'updateId' => '#questionTeam',
                'maxButtonCount' => 5
            )
        );
        ?>
</div>