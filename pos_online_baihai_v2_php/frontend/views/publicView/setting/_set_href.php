<?php
/**
 * Created by PhpStorm.
 * User: gaoli_000
 * Date: 2015/6/3
 * Time: 11:45
 */
?>
<ul class="tabList clearfix">
	<?php if(loginUser()->isTeacher()){?>
	<li><a href="<?php echo url('teacher/setting/set-head-pic')?>" class="<?php echo $this->context->highLightUrl(['teacher/setting/set-head-pic']) ? 'ac' : '' ?>">修改头像</a></li>
	<li><a href="<?php echo url('teacher/setting/change-password')?>" class="<?php echo $this->context->highLightUrl(['teacher/setting/change-password']) ? 'ac' : '' ?>">修改密码</a></li>
	<?php }elseif(loginUser()->isStudent()){ ?>
		<li><a href="<?php echo url('student/setting/set-head-pic')?>" class="<?php echo $this->context->highLightUrl(['student/setting/set-head-pic']) ? 'ac' : '' ?>">修改头像</a></li>
		<li><a href="<?php echo url('student/setting/change-password')?>" class="<?php echo $this->context->highLightUrl(['student/setting/change-password']) ? 'ac' : '' ?>">修改密码</a></li>
	<?php }?>
</ul>