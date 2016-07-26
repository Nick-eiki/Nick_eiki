<?php
/**
 * Created by PhpStorm.
 * User: 邓奇文
 * Date: 2016/4/29
 * Time: 10:11
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
<td width="50px"><?php echo SubjectModel::model()->getSubjectName($item["subjectID"]); ?></td>
<td width="130px"><?php echo $item['bindphone']; ?></td>
<td><?php echo $item['phoneReg']; ?></td>
<td>未激活</td>