<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-9-29
 * Time: 上午10:24
 */
?>

<!--中间内容开始-->
<div class="reg_content cont24">
    <div class="reg_c_content">
        <?php if($type==1){?>
            <dl class="link_list">
                <dt><i></i>激活验证码成功</dt>
                <dd>系统将在<i id="time">5</i>秒后自动跳转到登录页面......</dd>
                <dd>若未发生跳转，请点击<a href="<?php echo url('site/login',array('username'=>$email))?>">“我要登录&gt;&gt;”</a></dd>
            </dl>
      <?php  }else{?>
            <dl class="link_list">
                <dt class="emailError"><i></i>激活验证码失败</dt>
                <dd>请重新发送验证码......</dd>
                <dd>返回，请点击<a href="<?php echo url('register/sensitize')?>">“重新发送验证码&gt;&gt;”</a></dd>
            </dl>
    <?php    }?>


    </div>
</div>
<!--中间内容结束-->


<?php if($type==1){?>

<script  type="text/javascript">



    var count = 0;
    var timeID;

    function stopCount()
    {
        clearTimeout(timeID);
    }
    function delayURL(url) {
        var delay = document.getElementById("time").innerHTML;
        if (delay > 0) {
            delay--;
            document.getElementById("time").innerHTML = delay
        } else {
            window.top.location.href = url;
            stopCount();
        }
        timeID=   setTimeout("delayURL('" + url + "')", 1000)
    }

</script>
<script type="text/javascript">

    delayURL("<?php echo url('site/login',array('username'=>$email))?>");

</script>
<?php }?>