<?php
/**
 * Created by PhpStorm.
 * User: liquan
 * Date: 2014/12/5
 * Time: 18:04
 */
/* @var $this yii\web\View */  $this->title='课程管理-题目管理';
$backend_asset = publicResources();
;
$this->registerJsFile($backend_asset . '/js/My97DatePicker/WdatePicker.js', [ 'position'=>\yii\web\View::POS_HEAD] );
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/ztree/jquery.ztree.all-3.5.min.js');
//$this->registerJsFile($backend_asset . "/js/preview.js");
$this->registerCssFile($backend_asset . '/css/addquestion.css'.RESOURCES_VER);
?>

<script language="JavaScript" type="text/javascript">
var intervalId=null;
	 function daoshu(){

     var timer = document.getElementById("time");

     if(timer.innerHTML == 0){
        window.location.href="<?php echo url('/teacher/managepaper/topic-manage')?>";
        window.clearInterval(intervalId);
	 }
     timer.innerHTML = timer.innerHTML - 1;
}
intervalId=  window.setInterval("daoshu()", 1000);
</script>
<div class="currentRight grid_16 push_2">
	<div class="success_div">
    	<h3><?php if($status==1){ echo "<img src='".publicResources()."/images/f00c.png'/>操作成功";}else{ echo "<img src='".publicResources()."/images/cross.png'/>操作失败";}?></h3>
        <p>系统将在<i id="time">5</i>秒后自动跳转到题目列表，若未发生跳转，请点击<a href="<?php echo url('/teacher/managepaper/add-topic')?>">“录入题目”</a>或<a href="<?php echo url('/teacher/managepaper/topic-manage')?>">“查看列表”</a>。</p>
        <!--继续录入跳转到题目录入页面，查看录入跳转到我录入的页面，两个按钮谁都不点击，跳转到我录入的页面-->
    </div>    
  </div>
</div>
</div>
<!--主体内容结束-->
