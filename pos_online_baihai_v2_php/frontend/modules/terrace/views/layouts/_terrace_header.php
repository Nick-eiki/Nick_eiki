<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/11/14
 * Time: 17:05
 */
$studentId = app()->request->getParam('studentId', '');
$teacherId = app()->request->getParam('teacherId', '');
$tags = app()->request->getParam('tags','');
?>
<!--顶部开始-->
<div class="head">
	<div class="cont24">
		<h1>班海</h1>
		<ul class="head_nav">
			<li><a href="#">班级</a></li>
			<li><a class="ac" href="#">备课</a></li>
			<li><a href="#">视频</a></li>
		</ul>
		<div class="userCenter">
			<a class="userName" href="#"><i></i>张三</a><a href="javascript:;" class="logOff">退出</a>
			<div class="msgAlert">
				<a href="javascript:;">(99)</a>

				<ul class="msgList hide">
					<span class="arrow"></span>
					<li><a href="javascript:;">通知(33)</a></li>
					<li><a href="javascript:;">系统消息(33)</a></li>
					<li><a href="javascript:;">我的私信(33)</a></li>
				</ul>

			</div>

		</div>
	</div>

</div>
<!--top_end-->

