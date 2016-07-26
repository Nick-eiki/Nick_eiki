<?php
/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 2015/6/1
 * Time: 15:08
 */
use frontend\components\helper\ViewHelper;

?>
<div class="studentList">
	<?php if (empty($answerCorrected)): ?>
		<?php ViewHelper::emptyView(); ?>
	<?php endif ?>
    <?php foreach ($answerCorrected as $item) {
        echo $this->render('_fixanswer_student', ['item' => $item, 'homeworkAnswerID'=>$item->homeworkAnswerID,'homeWorkTeacher'=>$homeWorkTeacher]);
    }?>


</div>
<?php
if(isset($pagesCorrected)){
     echo \frontend\components\CLinkPagerExt::widget( array(
           'pagination'=>$pagesCorrected,
            'updateId' => '#fixwork_id',
            'maxButtonCount' => 5
        )
    );
}

?>