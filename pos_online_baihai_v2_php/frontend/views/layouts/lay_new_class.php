<?php
/**
 * Created by yangjie
 * User: Administrator
 * Date: 14-9-19
 * Time: 上午9:56
 */
use frontend\services\pos\pos_ClassSloganService;
use frontend\services\pos\pos_PersonalInformationService;
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
$this->beginContent('@app/views/layouts/main.php');
$this->blocks['bodyclass'] = "classes";
$this->registerCssFile(publicResources_new() . '/css/classes.css'.RESOURCES_VER);
$this->registerJsFile(publicResources_new() . "/js/jquery.validationEngine.min.js", [ 'position'=>\yii\web\View::POS_HEAD] );
$this->registerJsFile(publicResources_new() . "/js/jquery.validationEngine-zh_CN.js", [ 'position'=>\yii\web\View::POS_HEAD] );
$this->registerJsFile(publicResources_new() . "/js/ztree/jquery.ztree.all-3.5.min.js".RESOURCES_VER, [ 'position'=>\yii\web\View::POS_HEAD] );
$this->registerJsFile(publicResources_new() . "/js/schoolEdit.js".RESOURCES_VER, [ 'position'=>\yii\web\View::POS_HEAD] );

$classModel=   $this->params['classModel'];
$classId = $classModel->classID;
$classSlogan = new pos_ClassSloganService();
$slogan = $classSlogan->searchClassSlogan($classId)->classSlogan;
?>

<div class="cont24 homepage classes_home">
    <div class="grid_24 myInfo class_home_info">
        <div class="infoBar pr">
            <dl class="classes_head clearfix">
                <dt class="fl"><img src="<?=publicResources_new() . "/images/class.png";?>" alt=""></dt>
                <dd class=""><h2><?= $classModel->className ?></h2></dd>
                <dd class="clearfix">
                    <div id="revise">
                        <p class="class_opacity"></p>
                        <p class="class_title fl" title="<?=  Html::encode($slogan) ?>"><?= Html::encode($slogan) ?></p>
                        <?php
                        //访问手拉手班级时隐藏判断
                        $isCanModify = pos_PersonalInformationService::isCanModify($classId);
                        if($isCanModify){
                        ?>
                        <i data-id="<?= $classId ?>" data-action="<?= url('class/ajax-class-slogan'); ?>" class="ico"></i>
                        <?php }?>
                    </div>
                </dd>
            </dl>
            <div class="class_nav">
                <div class="class_nav_opacity"></div>
                <!--<p class="class_top"></p>-->
                <ul class="class_nav_list clearfix">
                    <li class="<?= $this->context->highLightUrl('class/index') ? 'ac' : '' ?>">
                        <a href="<?= url('class/index', ['classId' => $classId]) ?>">首页</a>
                    </li>
                    <?php
                    //访问手拉手班级时隐藏判断
                        $isCanModify = pos_PersonalInformationService::isCanModify($classId);
                        if($isCanModify){
                    ?>
                    <li  class="<?= $this->context->highLightUrl('class/homework') ? 'ac' : '' ?>">
                        <a href="<?= url('class/homework', ['classId' => $classId]) ?>">作业</a>
                    </li>
                    <li>
                        <a href="<?= url('class/exam', ['classId' => $classId]) ?>">考试</a>
                    </li>
                    <?php }?>
                    <li class="<?= $this->context->highLightUrl('class/member-manage') ? 'ac' : '' ?>">
                        <a href="<?= url('class/member-manage', ['classId' => $classId]) ?>">班级成员</a>
                    </li>
                    <li class="<?= $this->context->highLightUrl(['class/class-file','class/class-file-details']) ? 'ac' : '' ?>">
                        <a href="<?= url('class/class-file', ['classId' => $classId]) ?>">班级文件</a>
                    </li>
                    <li class="<?= $this->context->highLightUrl('class/answer-questions') ? 'ac' : '' ?>"><a
                            href="<?= Url::to(['class/answer-questions','classId' => $classId]) ?>">班内答疑</a>
                    </li>
                    <li class="<?= $this->context->highLightUrl('class/memorabilia') ? 'ac' : '' ?>"><a
                            href="<?= url('class/memorabilia', ['classId' => $classId]) ?>">大事记</a>
                    </li>
                </ul>
            </div>

        </div>
    </div>
    <div class="grid_24 main classes_members classes_paper">
        <div class="grid_16 alpha omega main_l">
            <?php echo $content ?>
        </div>
        <div class="grid_8 alpha omega main_r">
            <?php echo  $this->context->rightContent($classId) ?>
        </div>
    </div>

</div>


<?php $this->endContent() ?>
