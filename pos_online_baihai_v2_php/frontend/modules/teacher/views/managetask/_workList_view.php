<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/5/21
 * Time: 15:22
 */
use frontend\components\helper\ViewHelper;

?>
	<div class="studentList">
		<?php if (empty($answer)): ?>
			<?php ViewHelper::emptyView(); ?>
		<?php endif ?>
		<?php foreach ($answer as $item) {
			echo $this->render('_answer_student', ['item' => $item, 'homeworkAnswerID'=>$item->homeworkAnswerID,'homeWorkTeacher'=>$homeWorkTeacher]);
		}?>
	</div>
<?php
if(isset($page)){
	echo \frontend\components\CLinkPagerExt::widget( array(
			'pagination' => $page,
			'updateId' => '#work_id',
			'maxButtonCount' => 5
		)
	);
}

?>