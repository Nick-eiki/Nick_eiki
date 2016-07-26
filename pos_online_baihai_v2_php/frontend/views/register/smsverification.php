<?php
/**
 * Created by unizk.
 * User: ysd
 * Date: 2015/4/7
 * Time: 17:48
 */
/* @var $this yii\web\View */
$this->title="注册";
?>
<script>
    $(function () {
        //验证码时间未过期
        <?php
        $timeDiff = $times-time();
        if($times){
        ?>
        $('.countdown,.attention span').show();
        countdown(<?php echo $timeDiff;?>, '#second_show', function () {
            $(".sendCode").show().text('重新发送');
            $('.countdown').hide();
            $('.countdown,.attention span').hide();
        });
        $(".sendCode").hide();
        <?php }?>

        //发送验证码
        $('#verify_num').placeholder({value: '请输入验证码', ie6Top: 10});
        $('.sendCode').click(function () {
            var _this = $(this);
            $(this).hide();
            $('#second_show').text(60);
            //发送验证码
            var phoneReg = $("#phoneReg").val();
            $.post('<?php echo url("register/get-activite-tolken-phone");?>', {phoneReg: phoneReg}, function (data) {
                if (data.success) {
                    $('.countdown,.attention span').show();
                    countdown(60, '#second_show', function () {
                        _this.show().text('重新发送');
                        $('.countdown').hide();
                        $('.countdown,.attention span').hide();
                    });
                }
            })
        });

        //点击下一步
        $("#nextStep").click(function () {

            if ($('#form_id').validationEngine('validate')) {
                var verify_num = $('#verify_num').val();
                var phoneReg = $('#phoneReg').val();
                $.post('<?php echo url('register/sms-verification')?>', {
                    verify_num: verify_num,
                    phoneReg: phoneReg
                }, function (data) {
                    if (data.success) {
                        if(data.data == 1){
                            location.href = '<?php echo url('register/teacher-find-group');?>';
                        }else if(data.data ==0){
                            location.href = '<?php echo url('register/student-find-group');?>';
                        }
                    } else {
                        $("#verify_num").validationEngine("showPrompt", data.message, "error");

                    }
                });
            }
        })

    })

</script>

<!--<script type="text/javascript">
var intDiff = parseInt(10);//倒计时总秒数量
$(function(){
    $('#btn_line_js').one('click',function(){
		$(this).css('background','#ccc')
		timer(intDiff);

	})

});
</script>-->

<!--主体部分-->
<div class="cont24">
    <div class="grid_19 push_3">
        <h1>注册班海账号</h1>
        <div class="formArea" style="height:550px">
            <form id="form_id">
                <div class="form_list">
                    <div class="row" style="padding: 0">
                        <div class="formL">
                            <label></label>
                        </div>
                        <div class="formR">
                            <p class="font12 attention"><span class="hide">我们向您的手机<?php echo loginUser()->getPhoneReg(); ?>
                                    发送了一条验证短信</span></p>
                            <input type="hidden" id="phoneReg" name="phoneReg"
                                   value="<?php echo loginUser()->getPhoneReg(); ?>">
                            <input id="verify_num" name="verify_num" type="text" class="text validate[required]"
                                   data-prompt-position="inline" data-prompt-target="codeError"
                                   data-errormessage-value-missing="验证码不能为空！" onkeydown= "if(event.keyCode==13)return false;"
                                   style="margin-right:10px"><span id="codeError" class="errorTxt"
                                                                   style="top:46px"></span>
                            <div><span
                                class="red txtBtn sendCode">立即发送</span>
                            <span class="gray countdown hide">(<em id="second_show"><?php if ($times) {
                                        echo $timeDiff;
                                    } else {
                                        echo 60;
                                    } ?></em>) 秒后可重新发送</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="formL">
                            <label></label>
                        </div>
                        <div class="formR submitBtnBar">
                            <button type="button" id="nextStep" class="btn40 bg_green submitBtn"
                                    style="margin-bottom:10px">下一步
                            </button>
                            <button type="button" class="btn40 noBg gray_d nextBtn editPhone" style="border:1px solid #ddd">
                                修改手机号
                            </button>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--主体end-->
<script type="text/javascript">
    $(function(){
        $('.editPhone').click(function(){
            location.href = '<?=url('register/edit-regist-phone'); ?>';
        });
    })
</script>