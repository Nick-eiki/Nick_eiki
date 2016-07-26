`<?php
/**
 * Created by unizk.
 * User: ysd
 * Date: 2015/4/13
 * Time: 10:49
 */
/* @var $this yii\web\View */  $this->title='修改手机号';
?>

<script>
    $(function(){
        $('#editPhone').click(function(){
            if ($('#form_id').validationEngine('validate')) {
                var verify_num = $('#verify_num').val();
                var oldphonenum = $('#oldphonenum').val();
                $.post('<?php echo url('register/edit-regist-phone')?>', {verify_num: verify_num,oldphonenum:oldphonenum}, function (data) {
                    if (data.success) {
                        location.href = '<?php echo url('register/sms-verification');?>';
                    } else {
                        $("#verify_num").validationEngine("showPrompt", data.message, "error");
                    }
                });
            }
        });
    })
</script>

<!--主体部分-->
<div class="cont24">
    <div class="grid_19 push_3">
        <h1>修改手机号码</h1>
        <div class="formArea" style="height:550px">
            <form id="form_id">
                <div class="form_list">
                    <div class="row">
                        <div class="formL">
                            <label></label>
                        </div>
                        <div class="formR">
                            <h5 style="padding-bottom:10px">您希望将登录手机号码<?=$phoneReg; ?>修改为:</h5>
                            <input type="hidden" id="oldphonenum" value="<?=$phoneReg; ?>">
                            <input id="verify_num" type="text" class="text"
                                   data-validation-engine="validate[required,custom[phoneNumber]]"
                                   data-prompt-position="inline"
                                   data-prompt-target="phoneError"
                                ><span id="phoneError" class="errorTxt" style="left:300px; top:55px"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="formL">
                            <label></label>
                        </div>
                        <div class="formR submitBtnBar">
                            <button type="button" id="editPhone" class="btn40 bg_green nextBtn" style="margin-bottom:10px">下一步</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--主体end-->