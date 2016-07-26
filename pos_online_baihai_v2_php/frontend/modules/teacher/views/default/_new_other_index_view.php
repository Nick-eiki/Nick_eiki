<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/5/6
 * Time: 11:23
 */
use frontend\services\pos\pos_ClassMembersService;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
?>
<?php if (loginUser()->getUserInfo(app()->request->getParam("teacherId"))->isTeacher()) {
	$groupArray = $this->context->isSameGroup(app()->request->getParam("teacherId"));
	if (count($groupArray) > 0) {
		?>
		<h4>Wo 的教研組同事</h4>
		<ul class="ta_student_list clearfix">
			<?php foreach ($this->context->getGroupNumber($groupArray[0]["groupID"])->memberList as $key=>$v) { ?>
				<li>
					<a href="<?php echo url('student/default/index', array('studentId' => $v->userID)) ?>">
						<img data-type='header' onerror="userDefImg(this);" src="<?php echo publicResources() . loginUser()->getUserInfoCache($v->userID)->getFaceIcon() ?>" width="50" height="50" alt="" title="<?= $v->trueName?>" />
					</a>
					<?php echo $v->trueName ?>
				</li>
			<?php } ?>
		</ul>
		<?php if($key>10){?>
		<a href="<?php echo url('class/member-manage', array('classId' => $classId)) ?>" class="blue underline">
			查看全部 <?php echo $key; ?> 位成员 》</a>
			<?php } ?>
	<?php }elseif (count($this->context->isSameClass(app()->request->getParam("teacherId"))) > 0) {
		$classArray = $this->context->isSameClass(app()->request->getParam("teacherId"));
		$array = array();
		foreach ($classArray as $v) {
			array_push($array, array("className" => $v["className"], "classID" => $v["classID"]));
		}
		?>
		<h4>Wo 们的班级:<?php
			echo Html::dropDownList("", app()->request->getParam('classID', '')
				,
				ArrayHelper::map($array, 'classID', 'className'),
				array(
					//"prompt" => "请选择",
					"id" => "classID"
				));
			?></h4>

		<ul class="ta_student_list clearfix" id="classMember">
			<?php
			foreach ($classArray as $v1) {
				$classId = $v1['classID'];
				$classServer = new pos_ClassMembersService();
				$classResult = $classServer->loadRegisteredMembers($classId, '' , $teacherId);
				echo $this->render("_class_member", array("classResult" => $classResult, 'classId'=>$classId,'teacherId'=>$teacherId));
				break;
			}
			?>
		</ul>

	<?php } else { ?>
		<h4>Ta 的班级:<?php
			$array = array();
			$classInfo = loginUser()->getUserInfo($teacherId)->getClassInfo();
			foreach ($classInfo as $v) {
				array_push($array, array("classID" => $v->classID, "className" => $v->className));
			}
			echo Html::dropDownList("", app()->request->getParam('classID', '')
				,
				ArrayHelper::map($array, 'classID', 'className'),
				array(
					//"prompt" => "请选择",
					"id" => "allClassID"
				));
			?></h4>

		<ul class="ta_student_list clearfix" id="allClassMember">
			<?php
			foreach ($classInfo as $v2) {
				$classId = $v2->classID;
				$classServer = new pos_ClassMembersService();
				$classResult = $classServer->loadRegisteredMembers($classId, '' , $teacherId);
				echo $this->render("_class_member", array("classResult" => $classResult,'classId'=>$classId,'teacherId'=>$teacherId));
				break;
			}
			?>
		</ul>
	<?php }
} ?>