<?php
/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 2015/7/20
 * Time: 15:59
 */
use yii\helpers\Html;

$this->title='教研课题——报告详情';
?>
<div class="main_cont  report_details">

    <div class="title">
        <a href="<?= url('teachgroup/topic-details',array('groupId'=>app()->request->getParam('groupId'),'courseId'=>$courseId)); ?>" class="txtBtn backBtn"></a>
        <h4>报告详情</h4>
    </div>
    <div>
        <div class="grade">
            <h4><?= $gradeName; ?>：<?= \frontend\components\CHtmlExt::encode($quData->reportTitle); ?></h4>
            <p>作者：<?= \frontend\components\WebDataCache::getTrueName($userId); ?></p>
            <div class="article">
                <p><?= $quData->reportContent; ?></p>
            </div>

        </div>
        <div class="flip">
            <div class="title noBorder">
										<span>
											上一篇：
                                            <?php if(empty($lastData)){?>
                                                <a href="javascript:;">没有了</a>
                                            <?php }else{?>
                                                <a href="<?=url('teachgroup/report-details',array('groupId'=>app()->request->getParam('groupId'),'courseReportId'=>$lastData->courseReportId,'courseId'=>$courseId))?>"><?=Html::encode($lastData->reportTitle)?></a>
                                            <?php }?>
										</span>
                <div class="title_r">
                    下一篇：
                    <?php if(empty($nextData)){?>
                        <a href="javascript:;">没有了</a>
                    <?php }else{?>
                        <a href="<?=url('teachgroup/report-details',array('groupId'=>app()->request->getParam('groupId'),'courseReportId'=>$nextData->courseReportId,'courseId'=>$courseId))?>"><?=Html::encode($nextData->reportTitle)?></a>
                    <?php }?>
                </div>
            </div>
        </div>

    </div>
</div>