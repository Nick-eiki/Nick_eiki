<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2016/5/30
 * Time: 14:25
 */
use frontend\components\helper\ViewHelper;

?>
<ul>

	<?php
	if(empty($homeworkList)){
		echo ViewHelper::emptyView("暂无该学部学科的作业。");
	}
	/** @var common\models\pos\SeHomeworkTeacher[] $homeworkList */
	foreach ($homeworkList as $val) {
		echo "<div id='one-work-content".$val->id."'>";
		echo $this->render("_teacher_work_manage_list_content",["val"=>$val]);
		echo "</div>";
	} ?>
</ul>

<?php
if (isset($pages)) {
	echo \frontend\components\CLinkPagerExt::widget(array(
					'pagination' => $pages,
					'updateId' => '#work_list_page',
					'maxButtonCount' => 5,
					'showjump' => true
			)
	);
}
?>