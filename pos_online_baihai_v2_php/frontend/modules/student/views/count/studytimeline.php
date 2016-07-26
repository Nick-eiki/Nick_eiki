<?php
/**
 * Created by PhpStorm.
 * User: liquan
 * Date: 2014/12/3
 * Time: 12:59
 */
$publicResources = Yii::$app->request->baseUrl;
;
$this->registerJsFile($publicResources . '/js/echarts/echarts.js'.RESOURCES_VER);
$this->registerJsFile($publicResources . '/js/echarts/html5shiv.min.js'.RESOURCES_VER);
$this->registerJsFile($publicResources . '/js/echarts/respond.min.js'.RESOURCES_VER);
/* @var $this yii\web\View */  /* @var $this yii\web\View */  $this->title="学习时间轴";
?>
<div class="currentRight grid_16 push_2 sends time_axle">
    <div class="notice ">
        <div class="noticeH clearfix noticeB">
            <h3 class="h3L">我的学习时间轴</h3>
        </div>
        <hr>
        <div class="sends_list">
            <!--我的时间轴-->
            <?php echo $this->render("_studyline",array("list"=>$list,'pages'=>$pages))?>
        </div>

    </div>
</div>
<!--主体内容结束-->

