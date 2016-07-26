<?php

/* @var $this yii\web\View */  $this->title="课程管理";
$this->registerJsFile(publicResources() . '/js/My97DatePicker/WdatePicker.js', [ 'position'=>\yii\web\View::POS_HEAD] );

$backend_asset = publicResources();
;
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);



?>


<!--主体内容开始-->
<div class="currentRight grid_16 push_2 hear">
    <div class="notice c_manage">
        <div class="noticeH clearfix noticeB">
            <h3 class="h3L">课程管理</h3>

            <div class="new_not fr">
                <a href="<?php echo url('teacher/coursemanage/new-course') ?>" class="new_examination">创建课程</a>
            </div>
        </div>
        <hr>
		<?php echo $this->render('_course_list', array('model'=>$model, 'pages'=>$pages) );?>

    </div>
</div>

<!--主体内容结束-->

