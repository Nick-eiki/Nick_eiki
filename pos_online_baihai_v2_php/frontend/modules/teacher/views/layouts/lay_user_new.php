<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2016/5/27
 * Time: 15:11
 */
use yii\helpers\Url;
use yii\web\View;


/* @var $this yii\web\View */
$this->beginContent('@app/views/layouts/main_v2.php');

$this->registerCssFile(publicResources_new2() . '/css/teacher.css'.RESOURCES_VER);

$this->registerCssFile(publicResources_new2() . '/css/platform.css'.RESOURCES_VER);

$this->registerCssFile(publicResources_new() . '/js/ztree/zTreeStyle/zTreeStyle.css'.RESOURCES_VER);

$this->registerJsFile(publicResources_new() . "/js/ztree/jquery.ztree.all-3.5.min.js".RESOURCES_VER,[ 'position'=> View::POS_HEAD] );
?>
	<div class="col1200">
		<div class="tch_head container currency_hg">
			<ul>
				<li>
					<a class="<?php echo $this->context->highLightUrl(['teacher/setting/personal-center', 'teacher/setting/change-password', 'teacher/setting/set-head-pic']) ? 'ac' : '' ?>"
				       href="<?php echo  Url::to(['/teacher/setting/personal-center']) ?>">个人中心</a>
				</li>

				<li>
					<a class="<?php echo $this->context->highLightUrl(['teacher/message/notice', 'teacher/message/notice']) ? 'ac' : '' ?>"
					   href="<?php echo  Url::to(['/teacher/message/notice']) ?>">我的消息</a>
				</li>

				<li>
					<a class="<?php echo $this->context->highLightUrl(['teacher/resources/collect-work-manage','teacher/resources/my-create-work-manage','teacher/question/index','teacher/favoritematerial/index','teacher/favoritematerial/index-create']) ? 'ac' : '' ?>"
				       href="<?php echo Url::to(["/teacher/resources/collect-work-manage"])?>">我的资源</a>
				</li>

				<li>
					<a class="<?php echo $this->context->highLightUrl(['teacher/answer/answer-questions', 'teacher/answer/view-test', 'teacher/answer/add-question', 'teacher/answer/update-question']) ? 'ac' : '' ?>"
					   href="<?php echo Url::to(['/teacher/answer/answer-questions']) ?>">答疑</a>
				</li>
<!--				<li><a href="javascript:;">我的组织</a></li>-->
<!--				<li><a href="javascript:;">教研日志</a></li>-->
				<li>
					<a class="<?php echo $this->context->highLightUrl(['teacher/integral/income-details']) ? 'ac' : '' ?>"
					   href="<?php echo  Url::to(['/teacher/integral/income-details']) ?>">我的积分</a>
				</li>
<!--				<li><a href="javascript:;">我的关注</a></li>-->
				<li>
					<a class="<?php echo $this->context->highLightUrl([ 'teacher/setting/change-password', 'teacher/setting/set-head-pic']) ? 'ac' : '' ?>"
					   href="<?php echo  Url::to(['/teacher/setting/set-head-pic']) ?>">个人设置</a>
				</li>
			</ul>
		</div>
	</div>
<?php echo $content ?>
<?php $this->endContent() ?>