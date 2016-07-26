<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-14
 * Time: 下午6:26
 */
use frontend\models\dicmodels\LoadSubjectModel;
use frontend\models\dicmodels\SchoolLevelModel;

$subject = LoadSubjectModel::model()->getData($department,''); //查询科目
?>
<dt><?php echo SchoolLevelModel::model()->getSchoolLevelhName($department);?></dt>
<?php foreach($subject as $key => $v){ ?>
    <dd class="<?php echo $v->secondCode==app()->request->getParam('subjectID',$subjectID)&& $department ==app()->request->getParam('department',$departments) ?'ac':'';?>">

			<a href="<?= Url::to(['','department'=>$department,'subjectID'=>$v->secondCode]); ?>"><?php echo $v->secondCodeValue; ?></a>

	</dd>
<?php }?>
