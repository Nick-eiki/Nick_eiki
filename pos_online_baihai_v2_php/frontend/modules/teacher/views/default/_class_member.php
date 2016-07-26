<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-12-9
 * Time: 下午5:32
 */
use frontend\components\WebDataCache;

?>

<?php foreach ($classResult as $key=>$v) {
	if($key<=9){?>
    <li>
		<a href="<?php echo url('student/default/index', array('studentId' => $v->userID)) ?>">
			<img data-type='header' onerror="userDefImg(this);" src="<?php echo publicResources() . WebDataCache::getFaceIcon($v->userID) ?>" width="50" height="50" alt="" title="<?= $v->memName; ?>"/> <?php echo $v->memName; ?>
		</a>
    </li>
<?php }} ?>

<?php if(!empty($key)){ if($key>9){ ?>
<a href="<?php echo url('class/member-manage', array('classId' => $classId)) ?>" class="blue underline">
	查看全部 <?php echo $key;?> 位成员 》
</a>
<?php }} ?>