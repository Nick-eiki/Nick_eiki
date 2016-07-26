<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/4/20
 * Time: 17:53
 */
use frontend\components\helper\PinYinHelper;
use frontend\components\helper\ViewHelper;
use frontend\models\dicmodels\SubjectModel;

/* @var $this yii\web\View */  $this->title="错题列表";
$subject=new SubjectModel();
?>

<!--主体-->

		<div class="grid_19 main_r">
			<div class="main_cont total_mistake">
				<div class="title">
					<h4>我的错题集</h4><!--名称后天调用-->
				</div>
				<br>
				<ul class="clearfix testList">
					<?php
					if(empty($questionList)){
						echo ViewHelper::emptyView();
					}else{
						foreach($questionList as $val){
					?>
                    <li class="<?php echo PinYinHelper::firstChineseToPin($subject->getSubjectName($val->subjectId))?>"> <i></i>
						<h5><?=$subject->getSubjectName($val->subjectId)?>错题集</h5>
						<p class="test_detail">
							共收录：
							<em><?php echo $val->questionNum?>题</em>
						</p>
						<p>
							<a href="<?= url('student/wrongtopic/wro-top-for-item',array('subjectId'=>$val->wrongSubjectId)); ?>" class="a_button btn20 bg_blue viewBtn w80">查看题目</a>
						</p>
					</li>
					<?php }} ?>
				</ul>
			</div>
		</div>