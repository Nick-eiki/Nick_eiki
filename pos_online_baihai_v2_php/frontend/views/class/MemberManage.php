<?php
/** @var $this Controller */
use frontend\components\helper\ViewHelper;
use frontend\components\WebDataCache;
use frontend\models\dicmodels\ClassDutyModel;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title="班级成员管理";


?>
<div class="main clearfix classes_member col1200">
    <div class="container">
        <div class="sUI_pannel classify">
            <h4><span>老师</span></h4>
        </div>
        <div class="pd25">
            <div class="tch_classify clearfix">
                <div class="left">
                    <h5 class="class_teacher">班主任<i></i></h5>
                    <?php if (empty($master)) {
                        echo ViewHelper::emptyView("无数据！");
                    } else { ?>
                    <ul class="tch_left_list">
                        <li>
                            <a href="<?= Url::to(['teacher/default/index','teacherId'=>$master->userID])?>">
                                <img src="<?= WebDataCache::getFaceIcon($master->userID)?>" data-type="header" onerror="userDefImg(this);" />
                            </a>
                            <span><a href="<?= Url::to(['teacher/default/index','teacherId'=>$master->userID])?>"><?= WebDataCache::getTrueName($master->userID) ?></a><br>
                                <?= WebDataCache::getSubjectName($master->userID)?></span>
                        </li>
                    </ul>
                    <?php }?>
                </div>
                <div class="right">
                    <h5 class="attend_class">任课老师<i></i></h5>
                    <?php if (empty($teacherList)) {
                        echo ViewHelper::emptyView("无数据！");
                    } else { ?>
                    <ul class="tch_right_list">
                        <?php
                        foreach ($teacherList as $teacher) {?>
                        <li>
                            <a href="<?= Url::to(['teacher/default/index','teacherId'=>$teacher->userID])?>">
                                <img src="<?= WebDataCache::getFaceIcon($teacher->userID)?>" data-type="header" onerror="userDefImg(this);" />
                            </a>
                            <span><a href="<?= Url::to(['teacher/default/index','teacherId'=>$teacher->userID])?>"><?= WebDataCache::getTrueName($teacher->userID) ?></a><br><?= WebDataCache::getSubjectName($teacher->userID)?></span>
                        </li>
                        <?php }?>
                    </ul>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="sUI_pannel  classify">
            <h4><span>学生</span></h4>
        </div>
        <div class="pd25">
            <table cellspacing="0" cellpadding="0" class="stu_con">
                <?php if (empty($studentList)) {
                    echo ViewHelper::emptyView("无数据！");
                } else { ?>
                <tr>
                    <th style="width: 240px">学号</th>
                    <th style="width: 240px">头像</th>
                    <th style="width: 240px">姓名</th>
                    <th>用户名</th>
                    <th style="width: 240px">身份</th>
                </tr>
                <?php foreach($studentList as $student){?>
                <tr>
                    <td><?php echo  !empty($student->stuID)?$student->stuID:'——'?></td>
                    <td><a href="<?= Url::to(['student/default/index','studentId'=>$student->userID])?>"><img src="<?= WebDataCache::getFaceIcon($student->userID)?>" data-type="header" onerror="userDefImg(this);" /></a></td>
                    <td><?= Html::a(WebDataCache::getTrueName($student->userID), Url::to(['student/default/index', 'studentId' => $student->userID])) ?></td>
                    <td><?= WebDataCache::getUserName($student->userID)?></td>
                    <td><?= ClassDutyModel::model()->getClassDutyName($student->job) ?></td>
                </tr>
                <?php }?>
                <?php }?>
            </table>
        </div>
    </div>
</div>