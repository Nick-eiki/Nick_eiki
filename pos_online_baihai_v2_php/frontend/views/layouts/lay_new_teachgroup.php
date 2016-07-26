<?php
/** @var $this Controller */
use frontend\services\pos\pos_GroupSloganService;
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->beginContent('@app/views/layouts/main.php');
$this->registerCssFile(publicResources_new() . '/css/tch_group.css'.RESOURCES_VER);
$this->registerJsFile(publicResources_new() . "/js/calendar.js".RESOURCES_VER, [ 'position'=>\yii\web\View::POS_HEAD] );


$this->blocks['bodyclass'] = "tch_group";
$teachingGroupModel=$this->params['teachingGroup'];
$groupId = $teachingGroupModel->ID;

$groupSlogan = new pos_GroupSloganService();
$slogan = $groupSlogan->searchGroupSlogan($groupId)->groupSlogan;
?>
<?php $this->beginBlock('head_html_ext'); ?>
<?php $this->endBlock('head_html_ext') ?>

<div class="cont24 homepage research_materials">

    <div class="grid_24 tch_group_info">
        <div class="infoBar pr">
            <dl class="tch_group_head clearfix">
                <dt>
                    <img src="<?= publicResources_new() . "/images/class.png"; ?>" alt=""/>
                    <div class="head_mask_BG"></div>
                </dt>

                <dd>
                    <h2><?php echo $teachingGroupModel->groupName; ?></h2>
                </dd>

                <dd>
                    <div id="revise" class="revise">
                        <p class="class_opacity"></p>

                        <p class="class_title fl" title="<?=  Html::encode($slogan) ?>"><?=  Html::encode($slogan) ?></p>
                        <i data-id="<?= $groupId ?>" data-action="<?= url('teachgroup/ajax-group-slogan'); ?>"
                           class="ico"></i>
                    </div>
                </dd>
            </dl>
            <div class="tch_group_nav">
                <div class="tch_group_nav_opacity"></div>
                <p class="tch_group_top"></p>
                <ul class="tch_group_nav_list clearfix">
                    <li class="<?= $this->context->highLightUrl('teachgroup/index') ? 'ac' : '' ?>">
                        <a href="<?= url('teachgroup/index', array('groupId' => $groupId)); ?>">主页</a>
                    </li>
                    <li class="<?= $this->context->highLightUrl(['teachgroup/topic','teachgroup/topic-details','teachgroup/topic-report','teachgroup/report-details']) ? 'ac' : '' ?>">
                        <a href="<?= url('teachgroup/topic', array('groupId' => $groupId)); ?>">教研课题</a>
                    </li>

	                <li class="<?= $this->context->highLightUrl(['teachgroup/listen-lessons','teachgroup/listen-report-details','teachgroup/write-report']) ? 'ac' : '' ?>">
		                <a href="<?= url('teachgroup/listen-lessons', array('groupId' => $groupId)); ?>">听课评课</a>
	                </li>

                    <li class="<?= $this->context->highLightUrl(['teachgroup/teach-data','teachgroup/teach-data-details']) ? 'ac' : '' ?>">
                        <a href="<?= url('teachgroup/teach-data', array('groupId' => $groupId)); ?>">教研资料</a>
                    </li>
                    <li class="<?= $this->context->highLightUrl('teachgroup/teach-group-member') ? 'ac' : '' ?>">
                        <a href="<?= url('teachgroup/teach-group-member', array('groupId' => $groupId)); ?>">组内成员</a>
                    </li>

                    <!--<li><a href="#">班级文件</a>
                    </li>
                    <li><a href="#">班内答疑</a>
                    </li>
                    <li><a href="#">大事记</a>
                    </li>-->
                </ul>
            </div>

        </div>

    </div>
    <div class="grid_24 main classes_paper">
        <div class="grid_16 alpha omega main_l">
            <?php echo $content ?>
        </div>
        <div class="grid_8 alpha omega main_r">
            <?php echo $this->context->rightContent($groupId); ?>

        </div>

    </div>
</div>
<?php $this->endContent(); ?>
