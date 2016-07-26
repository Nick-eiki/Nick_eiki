<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2016/3/4
 * Time: 13:26
 */
use common\models\pos\SeClass;
use frontend\components\WebDataCache;
use frontend\models\dicmodels\EditionModel;
use frontend\models\dicmodels\SchoolLevelModel;
use frontend\models\dicmodels\SubjectModel;

?>
<div class="popCont">
	<div class="new_sch_con">
		<dl class="row clearfix">
			<dt>姓名：</dt>
			<dd>
				<?php
				if(empty($teaInfo["trueName"])){
					echo "*";
				}else{
					echo $teaInfo["trueName"];
				}
				?></dd>
		</dl>
		<dl class="row clearfix">
			<dt>手机号：</dt>
			<dd><?php echo $teaInfo["bindphone"]?></dd>
		</dl>
		<dl class="row clearfix">
			<dt>性别：</dt>
			<dd><?php if($teaInfo["sex"] == 0) {
					echo "男";
				} elseif($teaInfo["sex"] == 1) {
					echo "女";
				} else {
					echo "*";
				} ?></dd>
		</dl>
		<dl class="row clearfix">
			<dt>学段：</dt>
			<dd><?php echo SchoolLevelModel::model()->getSchoolLevelhName($teaInfo['department']); ?></dd>
		</dl>
		<dl class="row clearfix">
			<dt>学科：</dt>
			<dd>
				<?php echo SubjectModel::model()->getSubjectName($teaInfo["subjectID"]); ?>
				&nbsp;&nbsp;
				<?php
				if(empty($teaInfo["textbookVersion"])){
					echo "<em style='color: red'>未设置版本</em>";
				}else{
					echo EditionModel::model()->getEditionName($teaInfo["textbookVersion"]);
				}
				?>
			</dd>

		</dl>
		<dl class="row clearfix">
			<dt>任教班级：</dt>
			<dd>
				<span>
					<?php foreach ($classMem as $item) {
						echo \frontend\components\WebDataCache::getClassesName($item->classID) ."&nbsp;&nbsp;";
					} ?>
				</span>
			</dd>
		</dl>
		<dl class="row clearfix">
			<dt>教研组：</dt>
			<dd>
				<span>
					<?php
					foreach($groupMem as $groupMemVal){
						echo WebDataCache::getTeachingGroupName($groupMemVal->groupID)."&nbsp;&nbsp;";
					}
					?>
				</span>
			</dd>
		</dl>
	</div>
</div>