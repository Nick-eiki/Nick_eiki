<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-25
 * Time: 上午10:03
 */
use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */  $this->title="开始答题";
?>
<div class="currentRight grid_16 push_2 hear">
    <div class="notice">
        <div class="noticeH clearfix noticeB">
            <h3 class="h3L">日常测验</h3>
        </div>
        <hr>
        <div class="unanswer_main">
            <h4><?php echo $testResult->name ?></h4>
            <ul class="unanswer_list">
                <li><span><em>地区：</em> <?php echo AreaHelper::getAreaName($testResult->provience) . "&nbsp" . AreaHelper::getAreaName($testResult->city) . "&nbsp" . AreaHelper::getAreaName($testResult->country) ?></span> <span><em>年级：</em><?php echo $testResult->gradename ?></span> <span><em>科目：</em>数学</span>
                    <span><em>版本：</em><?php echo $testResult->versionname ?></span></li>
                <li><em>知识点：</em><?php echo KnowledgePointModel::findKnowledgeStr($testResult->knowledgeId) ?></li>
                <li><em>试卷简介：</em>
                    <?php echo $testResult->paperDescribe ?>
                </li>
            </ul>
            <p class="conserve clearBoth">
                <a href="<?php echo url('student/managepaper/online-answering',array('examID'=>app()->request->getQueryParam('examID')))?>" class="a_button bg_blue">开始答题</a>
            </p>
        </div>
    </div>
</div>