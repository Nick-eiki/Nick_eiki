<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-10-14
 * Time: 下午4:50
 */
/* @var $this yii\web\View */  $this->title="教研组管理";
?><!--主体内容开始-->


<div class="main_c clearfix" style="padding-bottom:50px;">
<div class="edit_title">
    <ul class="edit_list clearfix">
        <li class="beforeOne">校内组织</li>
    </ul>
</div>
<div class="editBox">
<div style="width:798px;margin:0 auto" class="sc_add edit_Div">
<form>
<dl class="sc_manage">
    <dt>教研组管理</dt>
    <?php $department = $schoolModel->department;
    $departmentArray = explode(",", $department);
    ?>
    <?php if (in_array("20201", $departmentArray)) { ?>
        <dd class="clearfix">
            <div class="takeL">
                <strong>小学部教研组:</strong>
            </div>

            <div class="takeC">
                <?php if (!empty($primaryTeachingList)) {
                    foreach ($primaryTeachingList->teachingGroupList as $v) { ?>
                        <div class="tack_box"><span><?php echo $v->groupName ?></span></div>
                    <?php }
                } ?>
            </div>
            <div class="takeR">
                                <span class="span_hide hide">
                                    <select class="subject">
                                        <option value="">请选择</option>
                                        <option value="1">语文</option>
                                        <option value="2">数学</option>
                                    </select>
                                	<input type="text" value="" class="text text_Width">
                                    <input type="hidden" value="20201"/>
                                    <button type="button" class="btn take_ok1">确定</button>
                                    <button type="button" class="btn btn_cancel">取消</button>
                                </span>
<!--                <button type="button" class="takeR_btn take_add">+教研组</button>-->
            </div>
            <hr>
        </dd>
    <?php
    }
    if (in_array("20202", $departmentArray)) {
        ?>
        <dd class="clearfix">
            <div class="takeL">
                <strong>初中部教研组:</strong>
            </div>

            <div class="takeC">
                <?php if (!empty($juniorTeachingList)) {
                    foreach ($juniorTeachingList->teachingGroupList as $v) { ?>
                        <div class="tack_box"><span><?php echo $v->groupName ?></span></div>
                    <?php }
                } ?>
            </div>
            <div class="takeR">
                                <span class="span_hide hide">
                                    <select class="subject">
                                        <option value="">请选择</option>
                                        <option value="1">语文</option>
                                        <option value="2">数学</option>
                                    </select>
                                	<input type="text" value="" class="text text_Width">
                                     <input type="hidden" value="20202"/>
                                    <button type="button" class="btn take_ok1">确定</button>
                                    <button type="button" class="btn btn_cancel">取消</button>
                                </span>
<!--                <button type="button" class="takeR_btn take_add">+教研组</button>-->
            </div>
            <hr>
        </dd>
    <?php
    }
    if (in_array("20203", $departmentArray)) {
        ?>
        <dd class="clearfix">
            <div class="takeL">
                <strong>高中部教研组:</strong>
            </div>

            <div class="takeC">
                <?php if (!empty($highTeachingList)) {
                    foreach ($highTeachingList->teachingGroupList as $v) { ?>
                        <div class="tack_box"><span><?php echo $v->groupName ?></span></div>
                    <?php }
                } ?>
            </div>
            <div class="takeR">
                                <span class="span_hide hide">
                                    <select class="subject">
                                        <option value="">请选择</option>
                                        <option value="1">语文</option>
                                        <option value="2">数学</option>
                                    </select>
                                	<input type="text" value="" class="text text_Width">
                                     <input type="hidden" value="20203"/>
                                    <button type="button" class="btn take_ok1">确定</button>
                                    <button type="button" class="btn btn_cancel">取消</button>
                                </span>
<!--                <button type="button" class="takeR_btn take_add">+教研组</button>-->
            </div>
            <hr>
        </dd>
    <?php } ?>
</dl>


</form>
</div>

</div>


</div>
<script>
function add_no() {
    $('.take_add').bind('click', function () {
        $(this).siblings('.span_hide').show();
        $(this).hide();

    });
    $('.take_ok1').bind('click', function () {
        var $this = $(this);
        var url = "<?php echo url('school/add-teaching-group')?>";
        var textVal8 = $this.siblings('.text_Width').val();
        var subjectID = $this.siblings(".subject").val();
        if (textVal8 == ''||subjectID=="") {
            popBox.alertBox('亲,里面不填东西怎么行呢？');
        }
        else {
            var department = $this.siblings("[type=hidden]").val();
            $.post(url, {schoolId:<?php echo $schoolId?>, subjectID: subjectID, groupName: textVal8, department: department}, function (result) {
                if (result.code == 1) {
                    $this.parent().parent().siblings('.takeC').append('<div class="tack_box"><span>' + textVal8 + '</span></div>');
                    popBox.alertBox(result.success);
                }
                else {
                    popBox.alertBox(result.success);

                }
            })
        }

        $(this).parent().hide();
        $(this).parent().siblings('.take_add').show()
    });
    $('.btn_cancel').bind('click', function () {
        $(this).parent().hide();
        $(this).parent().siblings('.take_add').show();
        var textVal8 = $(this).siblings('.text_Width').val('');
    })
}
add_no();

function add() {
    var _this_left = 0;
    var _this_index = 0;
    $('.gradeJs').bind('click', function () {
        $(this).siblings('.span_hide').show();
        $(this).hide();
    });
    $('.take_oks').bind('click', function () {
        var textVal = $(this).siblings('select').val();
        if (textVal == '') {
            popBox.conformBox('亲,里面不填东西怎么行呢？')
        }
        else {
            /*生成出来的html*/
            var html = '';
            html += '<div class="tack_box b_jq_number">';
            html += '<span class="tackL">' + textVal + '<i></i></span>';
            html += '<div class="add_class clearfix hide overJs pop">';
            html += '<div class="u_tran_t"></div>';
            html += '<div class="addL">';
            html += '</div>';
            html += '<div class="addR">';
            html += '<span class="span_hide span_click hide">';
            html += '<select>' +
                '<option value="1班">1班</option>' +
                '<option value="2班">2班</option>' +
                '<option value="3班">3班</option>' +
                '<option value="4班">4班</option>' +
                '<option value="5班">5班</option>' +
                '<option value="6班">6班</option>' +
                '</select>';
            html += '<input type="text" value="" id="className" class="text text_Width text_02">';
            html += '<button type="button" class="btn take_ok">确定</button>';
            html += '<button type="button" class="btn btn_cancel">取消</button>';
            html += '</span>';
            html += '<button type="button" class="takeR_btn take_addClass" style="float:right;">+添加班级</button>';
            html += '</div>';
            html += '</div>';

            $('.tackL').live('click', function (event) {
                $(this).siblings('.overJs').show();
                event.stopPropagation();
            });

            $('.take_addClass').live('click', function () {
                $(this).siblings('.span_hide').show();
                $(this).hide();
            });

            $('.take_ok').die('click').live('click', function () {
                $that = $(this);
                var oTxt05 = $that.siblings('select').val();
                var oTxt06 = $that.siblings('.text_02').val();
                var joinYear = $that.parent().parent().parent().siblings("span").text();
                var department = $that.parent().parent().parent().parent().parent().siblings(".takeR").find("[type=hidden]").val();
                if (oTxt06 == '') {
                    popBox.alertBox('亲,里面不填东西怎么行呢？');
                }
                else {
                    $that.parent().hide();
                    $that.parent().siblings('.take_addClass').show();
                    $.post("<?php echo url('school/add-class')?>", {schoolId: "<?php  echo  $schoolId?>", classNumber: oTxt05, className: oTxt06, joinYear: joinYear, department: department}, function (result) {
                        if (result.code == 1) {
                            $that.parent().parent().siblings('.addL').append('<span class="add_name"><em>' + oTxt05 + '</em><i>' + oTxt06 + '</i></span>');
                            popBox.alertBox(result.success);
                        }
                        else {
                            popBox.alertBox(result.success);
                        }

                    });
                    var oTxt01 = $(this).siblings('.text_01').val('');
                    var oTxt02 = $(this).siblings('.text_02').val('');
                }


            });
            $('.btn_cancel').live('click', function () {
                $(this).parent().hide();
                $(this).parent().siblings('.take_addClass').show();
                var oTxt01 = $(this).siblings('select').val('');
                var oTxt02 = $(this).siblings('.text_02').val('');
            });
            var grade = $(this).siblings('select').val();
            var years = $(this).parent().parent().siblings('.takeC').find(".tack_box");
//                判断是入学年份是否存在
            var yearArray = [];
            $.each(years, function (index, file) {
                yearArray.push($(file).find(".tackL").text());
            });

            if ($.inArray(grade, yearArray) == -1) {
                html += '</div>';

                $(this).parent().parent().siblings('.takeC').append(html);
            }
            else {
                popBox.alertBox("年级已经存在");
            }


            /*生成出来的班级多了，选项卡切换*/
            function tab_new() {
                var nav = $('.takeC .tackL');
                var nbox = $('.takeC .overJs');
                nav.click(function () {
                    nav.next("div").hide();
                    $(this).next("div").show();
                    return false;
                })
            }

            tab_new();


            //控制每个三角的left
            _this_left = $('.takeC').children('.b_jq_number').eq(_this_index).offset().left - $(this).parents('.clearfix').children('.takeL').offset().left - $(this).parents('.clearfix').children('.takeL').width() + ($('.takeC').children('.b_jq_number').eq(_this_index).width() / 2 - 14);

            //$('.tackL').css('backgroundColor','red');


            $('.takeC').children('.b_jq_number').eq(_this_index).find('.u_tran_t').css('left', +_this_left);

            _this_index++;

        }
        var textVal = $(this).siblings('.text_js').val('');
        $(this).parent().hide();
        $(this).parent().siblings('.gradeJs').show()
    });
    $('.no_ok').bind('click', function () {
        $(this).parent().hide();
        $(this).parent().siblings('.gradeJs').show();
    });


}
add();
function has() {
    var oBtn = $('.b_jq_number');
    oBtn.each(function (index, element) {
        $(this).click(function () {
            //oBtn.hide();
            $(this).siblings('.b_jq_number').children('.overJs').hide();
            $(this).children('.overJs').show().animate({left: '-16px', top: '-10px'}, 0);

        });
    });
    /*显示val和两个按钮*/
    $('.take_addClass').live('click', function () {
        $(this).siblings('.span_hide').show();
        $(this).hide();
        var oTxt03 = $(this).siblings('.text_one').val('');
        var oTxt04 = $(this).siblings('.text_tow').val('');
    });

    /*获取val的值*/
    /*添加班级的*/
    $('.overJs .take_ok2').live('click', function () {
        $this = $(this);
        var oTxt03 = $this.siblings('select').val();
        var oTxt04 = $this.siblings('.text_tow').val();
        var joinYear = $this.parent().parent().parent().siblings("span").text();
        var department = $this.parent().parent().parent().parent().parent().siblings(".takeR").find("[type=hidden]").val();

        if (oTxt03 == '') {
            alert('此处不能为空');
            //return false;
        }
        else if (oTxt04 == '') {
            popBox.alertBox("此处不能为空");
            //return false;
        }
        else {
            $this.parent().hide();
            $this.parent().siblings('.take_addClass').show();
            $.post("<?php echo url('school/add-class')?>", {schoolId: "<?php  echo  $schoolId?>", classNumber: oTxt03, className: oTxt04, joinYear: joinYear, department: department}, function (result) {

                if (result.code == 1) {
                    $this.parent().parent('.addR').siblings('.addL').append('<span class="add_name"><em>' + oTxt03 + '</em><i>' + oTxt04 + '</i></span>');
                    popBox.alertBox(result.success);
                }
                else {
                    popBox.alertBox(result.success);
                }

            });
//                var oTxt03 = $(this).siblings('.text_one').val('');
//                var oTxt04 = $(this).siblings('.text_tow').val('');
        }
    });
    $('.btn_cancel').bind('click', function () {
        $(this).parent().hide();
        $(this).parent().siblings('.take_addClass').show();
        var oTxt03 = $(this).siblings('.text_one').val('');
        var oTxt04 = $(this).siblings('.text_tow').val('');
    });

    $('.gradeJs').bind('click', function () {
        $('.overJs').hide();

    });
}
has()
</script>