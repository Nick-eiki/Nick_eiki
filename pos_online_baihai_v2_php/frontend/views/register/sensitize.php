<?php /* @var $this yii\web\View */  $this->title="激活账号" ?>


<div class="reg_content cont24">

    <div class=" reg_c_content reg_c clearfix">

        <div class="pass_L">
            <ul class="flow_path clearfix">
                <li class="flow_path01 flow_path03">
                    <span class="flow_w flow_w1 flow_w3">欢迎注册</span>
                    <span class="flow_n flow_1 flow_5"></span>
                </li>
                <li class="flow_path01">
                    <span class="flow_w">激活帐号</span>
                    <span class="flow_n flow_2 flow_4"></span>
                </li>
                <li class="flow_path01 flow_path02">
                    <span class="flow_w flow_w2">找组织</span>
                    <span class="flow_n flow_2 flow_3"></span>
                </li>
            </ul>
            <h3 class="pass pass_color">激活帐号</h3>
            <ul class="activation">
                <li>您已成功注册本站账号，请前往邮箱<a href="http://mail.<?php echo $emailUrl?>"><?php echo $email?></a>进行激活</li>

                <li>没有收到验证信<button class="btn act_btn" id="resend">重发验证信</button></li>
                <li>如果您依旧没有收到验证信，可以<a id="showli">修改注册邮箱&gt;&gt;</a></li>
                <li class="senul_li04">新邮箱：<input type="text" class="text" id="newEmail" value="<?php echo $email?>"> <br/>密码：<input type="password" class="text" id="passWd"><br/><button class=" btn act_ok">确定</button></li>

            </ul>
        </div>
        <div class="pass_R">
            <i class="pass_bj"></i>
            <div class="r_btn">
                <strong>已有账号？</strong>
                <a href="<?php echo url('site/login')?>" class="btn B_btn">去登录</a>
            </div>
        </div>

    </div>


</div>

<script>
    $(function(){
        $('.senul_li04').hide();
        $('#showli').click(function(){
            $('.senul_li04').show();
        });
        //重发邮件
        $('#resend').click(function(){
            $.post('<?php echo url("register/resend")?>', {email:'<?php echo $email?>'}, function(data){
                if(data.success == true){
                    popBox.alertBox(data.message);
                }
            });
        });

        $('.act_ok').click(function(){
          var newEmail = $("#newEmail").val();
            var passWd =$("#passWd").val();
            if(passWd ==""){
                popBox.alertBox('请输入密码！');
                return false;
            }
          $.post("<?php echo url("register/reset-email")?>", {'newEmail': newEmail,'passWd':passWd}, function (data) {

              if (data.success) {

                 window.location.reload(true);
              } else {
                  popBox.alertBox(data.message);
              }

          });
        })



    })

</script>


