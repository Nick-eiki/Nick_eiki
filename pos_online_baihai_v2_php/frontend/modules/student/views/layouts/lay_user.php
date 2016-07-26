<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-9-19
 * Time: 上午9:56
 */
/** @var $this Controller */
$this->beginContent('@app/views/layouts/main.php');
$this->blocks['bodyclass'] = "student";
$this->registerCssFile(publicResources_new() . '/css/student.css'.RESOURCES_VER);
$this->registerCssFile(publicResources_new() . '/js/ztree/zTreeStyle/zTreeStyle.css'.RESOURCES_VER);

$this->registerJsFile(publicResources_new() . "/js/jquery.validationEngine.min.js".RESOURCES_VER,[ 'position'=>\yii\web\View::POS_HEAD] );
$this->registerJsFile(publicResources_new() . "/js/jquery.validationEngine-zh_CN.js".RESOURCES_VER,[ 'position'=>\yii\web\View::POS_HEAD] );
$this->registerJsFile(publicResources_new() . "/js/ztree/jquery.ztree.all-3.5.min.js".RESOURCES_VER,[ 'position'=>\yii\web\View::POS_HEAD] );

?>



<div class="cont24">
    <div class="grid24 main">
        <?php echo $this->render("_new_user_left") ?>
        <?php echo $content ?>
    </div>
</div>
<?php $this->endContent() ?>
