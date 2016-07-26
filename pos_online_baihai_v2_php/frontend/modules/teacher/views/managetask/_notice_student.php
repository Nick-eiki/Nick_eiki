<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-21
 * Time: 上午9:44
 */
?>

<div class="row submitBtnBar ">

    <?php if ($getType == 1) { ?>
        <a class="bg_green w150 btn40 okBtn btn" target="_blank"
           href="<?= url('teacher/managetask/organize-work-details-new', ['homeworkid' => $homeworkId]) ?>">
            作业预览(电子)
        </a>
    <?php } else { ?>
        <a href="<?= url('teacher/managetask/new-update-work-detail', ['homeworkid' => $homeworkId]); ?>"
           class="bg_green w150 btn40 okBtn btn">作业预览(纸质)</a>
    <?php } ?>
    <a class="bg_green w150 btn40 okBtn btn" target="_blank"
       href="<?= url('teacher/workstatistical/work-statistical', ['relId' => $classhworkid]) ?>">
        作业统计
    </a>

</div>