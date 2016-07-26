<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2016/1/11
 * Time: 10:29
 * 教师 未提交
 */
?>
<div class="tab_con">
	<div class="stat">
		未作答 学生(共<?php echo $noStudentMember; ?>人)
	</div>
	<div class="stu_list" id="stuList">
		<div class="stu_inner">
			<?php
			if (empty($answerStuList)) {
				foreach ($studentList as $k => $stuVal) {
					echo '<div class="chk">';
					//echo '<input type="checkbox" name="chk1" class="checkbox1">';
					echo '<label>';
					echo '<a title="' . $stuVal->memName  .'"><span>' . $stuVal->memName . '</span> </a>';
					echo '</label>';
					echo '</div>'; //注意这里
				}
			} else {
//                                获取未答作业的学生
				$answerStuArray = array();
				foreach ($answerStuList as $v) {
					array_push($answerStuArray, $v->studentID);
				}
				$unAnswerStuList = array();
				foreach ($studentList as $v) {
					if (!in_array($v->userID, $answerStuArray)) {
						array_push($unAnswerStuList, $v);
					}
				}
				foreach ($unAnswerStuList as $stuVal) {
					echo '<div class="chk">';
					//echo '<input type="checkbox" name="chk1" class="checkbox1">';
					echo '<label>';
					echo '<a title="' . $stuVal->memName  .'"><span>' . $stuVal->memName . '</span> </a>';
					echo '</label>';
					echo '</div>'; //注意这里
				}
			}
			?>
		</div>
	</div>
<!--	--><?php //if (!empty($noStudentMember)) {
//		echo '<div class="operbox">';
//		echo '<div class="chk">';
//		echo '<input type="checkbox" id="checkAll" class="checkbox1">';
//		echo '<label>全选</label>';
//		echo '</div>';
//		echo '<a id="i_askBtn" type="button" class="btn bg_blue put_ques">催作业</a>';
//		echo '<a id="i_askBtn" type="button" class="btn bg_blue put_ques">请假</a>';
//		echo '</div>';
//	} ?>
</div>
