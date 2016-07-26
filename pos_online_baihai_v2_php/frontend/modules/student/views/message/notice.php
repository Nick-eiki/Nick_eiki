<?php
/**
 * Created by unizk.
 * User: ysd
 * Date: 14-12-11
 * Time: 下午6:59
 */
/* @var $this yii\web\View */  $this->title="我的通知";
$this->registerCssFile(publicResources_new() . '/js/fancyBox/jquery.fancybox.css'.RESOURCES_VER);
$this->registerJsFile(publicResources_new() . "/js/fancyBox/jquery.fancybox.js".RESOURCES_VER, [ 'position'=>\yii\web\View::POS_HEAD] );
?>

<div class="grid_19 main_r">
    <div class="main_cont notice">
        <div class="title">
            <h4>通知</h4>
        </div>

        <div id="notice">
            <?php echo $this->render('_notice_list',array('model'=>$model,'pages' => $pages, "classId"=>$classId));?>
        </div>

    </div>
</div>

<!--主体end-->
