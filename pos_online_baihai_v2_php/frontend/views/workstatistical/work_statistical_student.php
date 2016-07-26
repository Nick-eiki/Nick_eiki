﻿<?php
use common\models\pos\SeHomeworkAnswerQuestionAll;
use yii\helpers\Html;

$this->registerJsFile(publicResources_new2() . '/js/lib/echarts/echarts.js', ['position' => \yii\web\View::POS_HEAD]);
$classModel = $this->params['classModel'];
$classId = $classModel->classID;

$this->title = "作业统计";
$this->blocks['requireModule']='app/classes/tch_hmwk_result_stu';
?>

<div class="main col1200 clearfix tch_hmwk_stu" id="requireModule" rel="app/classes/tch_hmwk_result_stu.js">
    <div class="container homework_title">
        <a id="addmemor_btn" href="<?= url('class/homework', ['classId' => $classId]) ?>" class="btn bg_gray icoBtn_back return_btn"><i></i>返回</a>
        <h4><?php echo cut_str(Html::encode($homeWorkTeacher->name), 30) ?></h4>
    </div>
    <div class="container">
        <div class="sUI_tab">
            <ul class="tabList clearfix">
                <li class="tabListShow"><a
                        href="<?php echo \yii\helpers\Url::to(["/workstatistical/work-statistical-all", 'relId' => $relId]); ?>">总体分析</a>
                </li>
                <?php if ($homeWorkTeacher->getType == 1): ?>
                    <li class="tabListShow"><a
                            href="<?php echo \yii\helpers\Url::to(["/workstatistical/work-statistical-topic", 'relId' => $relId]); ?>">题目分析</a>
                    </li>
                    <li class="tabListShow"><a
                            href="<?php echo \yii\helpers\Url::to(["/workstatistical/work-statistical-student", 'relId' => $relId]); ?>"
                            class="ac">学生分析</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <?php if (!empty($homeworkAnswerID)): ?>
        <div class="container">
            <h4 class="cont_title"><i class="t_accuracy"></i>作业正确率排名</h4>

            <div class="workbox">
                <ul class="u_list">

                    <?php
                    $per = 0;
                    $top = 1;
                    foreach ($orderStudent as $k => $v):

                        if ($k == 0) {
                            $per = $v['per'];
                            $top = $k + 1;
                        }
                        if ($v['per'] <> $per) {
                            $per = $v['per'];
                            $top = $k + 1;
                        }
                        $i = $top;

                        if ($i < 10) {
                            $i = '0'.$i;
                        }
                        $rangeClass = 'range range_bg';
                        if ($i > 3) {
                            $rangeClass = 'range';
                        }

                        $key='work_statistical_student'.$v['studentId']. $relId;
                        if ($this->beginCache($key, ['duration' => 600])) {
                            $homeworkAnswer = \common\models\pos\SeHomeworkAnswerInfo::homeworkAnswerID($v['studentId'], $relId);
                        ?>


                        <li class="clearfix">
                            <span class="<?php echo $rangeClass; ?>"><?php echo $i; ?></span>

                            <p class="headimg">
                                <img src="<?php echo \frontend\components\WebDataCache::getFaceIcon($v['studentId']) ?>"
                                     class="qr" data-type="header" onerror="userDefImg(this);">
                            </p>

                            <p class="username"><?php echo \frontend\components\WebDataCache::getTrueName($v['studentId']); ?></p>

                            <p class="per"><?php echo sprintf("%.2f",$v['per'] * 100) ?>%</p>

                            <div class="subject">
                                <?php
                                $answerAllResult = SeHomeworkAnswerQuestionAll::find()->where(['homeworkAnswerID'=>$homeworkAnswer['homeworkAnswerID']])->all();
                                foreach ($questionIdResult as $k1 => $v1):
                                    $correctResult = 0;
                                    $answerOption = '';
                                    foreach($answerAllResult as $key2=> $val){
                                        if($val['questionID'] == $k1){
                                            $correctResult = $val['correctResult'];
                                            $answerOption=$val['answerOption'];
                                            unset($answerAllResult[$key2]);
                                        }
                                    }

                                    switch ($correctResult) {
                                        case 0:
                                            $pic = 'drakred';
                                            break;
                                        case 1:
                                            $pic = 'drakred';
                                            break;
                                        case 2:
                                            $pic = 'orange';
                                            break;
                                        case 3:
                                            $pic = '';
                                            break;
                                        default:
                                            $pic = 'drakred';
                                    }
                                    ?>
                                    <a title="点击查看原题" class="<?php echo $pic; ?>"
                                       answerOption="<?php echo $answerOption; ?>"
                                       questionId="<?php echo $k1; ?>" relId="<?php echo $relId; ?>">
                                        <?php echo $v1; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </li>
                    <?php  $this->endCache(); }; endforeach; ?>

                </ul>
            </div>
        </div>
    <?php else: ?>
        <div class="container">
            <div style='font-size:18px;margin:20px;'>暂无数据！</div>
        </div>
    <?php endif; ?>
</div>