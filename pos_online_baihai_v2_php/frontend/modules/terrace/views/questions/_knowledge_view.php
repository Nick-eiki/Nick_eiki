<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-11-24
 * Time: 下午4:31
 *
 * 20201 小学部
 * 20202 中学部
 * 20203 高中部
 */
use frontend\models\dicmodels\LoadSubjectModel;

$subject = LoadSubjectModel::model()->getData($department,'');
/**
 * @var Controller $this
 */
?>

<dt>
    <a href="javascript:">
        <?php if ($department == 20201) { ?>
            小学
        <?php } elseif ($department == 20202) { ?>
            中学
        <?php } else { ?>
            高中
        <?php } ?></a>
    <i></i>
</dt>
<dd>
    <ul>
        <?php foreach ($subject as $key => $v) { ?>
            <li>
                <a href="<?php echo $this->createUrl('',array('department'=>$department,'subjectid'=>$v->secondCode)); ?>">
                    <span class="gradeName"><?php echo $v->secondCodeValue; ?></span>
                </a>
            </li>
        <?php } ?>
    </ul>
</dd>