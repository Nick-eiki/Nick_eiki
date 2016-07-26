<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2016/3/4
 * Time: 13:26
 */
use common\models\pos\SeClass;
use frontend\models\dicmodels\SchoolLevelModel;

?>
<div class="popCont">
	<div class="new_sch_con">
		<dl class="row clearfix">
			<dt>学号：</dt>
			<dd>
				<?php if(empty($studentInfo["stuID"]) && $studentInfo['stuID'] != '0'){
					echo "未设置";
				}else{
					echo $studentInfo["stuID"];
				}?></dd>
		</dl>
		<dl class="row clearfix">
			<dt>姓名：</dt>
			<dd><?php echo $studentInfo["trueName"]?></dd>
		</dl>
		<dl class="row clearfix">
			<dt>手机号：</dt>
			<dd><?php echo $studentInfo["bindphone"]?></dd>
		</dl>
		<dl class="row clearfix">
			<dt>登录名：</dt>
			<dd><?php echo $studentInfo["phoneReg"]?></dd>
		</dl>
		<dl class="row clearfix">
			<dt>性别：</dt>
			<dd><?php if($studentInfo["sex"] == 0) {
					echo "男";
				} elseif($studentInfo["sex"] == 1) {
					echo "女";
				} else {
					echo "未设置";
				} ?></dd>
		</dl>
		<dl class="row clearfix">
			<dt>学段：</dt>
			<dd><?php echo SchoolLevelModel::model()->getSchoolLevelhName($studentInfo['department']); ?></dd>
		</dl>
		<dl class="row clearfix">
			<dt>班级：</dt>
			<dd><?php echo empty($classMembers) ? '暂无班级':\frontend\components\WebDataCache::getClassesName($studentInfo["classID"]); ?></dd>
		</dl>
		<?php if(!empty($parentsInfo)){ ?>
			<h5 style="height: 30px; line-height: 30px; font-size: 16px;background:#f5f5f5; text-indent: 10px">学生家长信息</h5>
			<dl class="row clearfix">
				<dt>家长姓名：</dt>
				<dd><?php if(empty($parentsInfo["trueName"])){
						echo "未设置";
					}else{
						echo $parentsInfo["trueName"];
					} ?></dd>
			</dl>
			<dl class="row clearfix">
				<dt>手机号：</dt>
				<dd><?php echo $parentsInfo["bindphone"]; ?></dd>
			</dl>
		<?php } ?>

	</div>
</div>
