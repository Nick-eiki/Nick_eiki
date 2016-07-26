<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2016/3/12
 * Time: 11:14
 */
use frontend\models\dicmodels\SchoolLevelModel;
use frontend\models\dicmodels\SubjectModel;

?>

<td width="100px"><?php echo $item['trueName']; ?></td>
<td width="40px">
	<?php if ($item['sex'] == 0) {
		echo "男";
	} elseif ($item['sex'] == 1) {
		echo "女";
	} else {
		echo "未设置";
	} ?>
</td>
<td width="50px"><?php echo SchoolLevelModel::model()->getSchoolLevelhName($item['department']); ?></td>
<td width="50px"><?php echo SubjectModel::model()->getSubjectName($item["subjectID"]) ?></td>
<td width="130px"><?php echo $item['bindphone']; ?></td>
<td><?php echo $item['phoneReg']; ?></td>
<td width="160px" class="oper fathers_td" uId="<?php echo $item["userID"] ?>">
	<a href="javascript:;" class="see_b view_info viewInfo" id="">查看</a>
	<span class="blue fl">|</span>
	<a href="javascript:;" class="edit_b edit_stu_info editInfo">编辑</a>
	<span class="blue fl">|</span>

	<div data-noChange class="sUI_select sUI_select_min fl other_operation">
		<em class="sUI_select_t">其它操作</em>
		<ul class="sUI_selectList pop">
			<li><a href="javascript:;" class="reset_passwd_bt reset_pwd">重置密码</a></li>
			<li><a class="update_class" href="javascript:;" data-userId="<?php echo  $item["userID"]?>" data-departmentName="<?php echo SchoolLevelModel::model()->getSchoolLevelhName($item['department']); ?>" data-departmentId="<?php echo $item['department']?>">修改班级</a></li>
			<li><a  class="live-school-th" href="javascript:;" data-userId="<?php echo  $item["userID"]?>" data-userName="<?php echo $item['trueName']; ?>">移除学校</a></li>
		</ul>
		<i class="sUI_select_open_btn"></i>
	</div>
</td>
