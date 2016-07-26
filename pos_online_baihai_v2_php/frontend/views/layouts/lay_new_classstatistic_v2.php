<?php
/**
 * Created by yangjie
 * User: Administrator
 * Date: 14-9-19
 * Time: 上午9:56
 */
use yii\helpers\Url;


/* @var $this yii\web\View */
$this->beginContent('@app/views/layouts/main_v2.php');
$this->blocks['bodyclass'] = "classes classes_theme1";
$this->registerCssFile(publicResources_new2() . '/css/statistic.css'.RESOURCES_VER);
$this->registerCssFile(publicResources_new2() . '/css/classes.css'.RESOURCES_VER);
$this->registerJsFile(publicResources_new2() . "/js/lib/echarts/echarts.js" );

$classModel = $this->params['classModel'];
$classId = $classModel->classID;

?>
<div class="col1200">
    <div class="class_nav">
        <div class="class_nav_opacity"></div>
        <p class="class_top"></p>
        <ul class="class_nav_list clearfix">
            <li class="<?= $this->context->highLightUrl('class/index') ? 'ac' : '' ?>">
                <a href="<?= url('class/index', ['classId' => $classId]) ?>">班级首页</a>
            </li>
            <?php if(loginUser()->isTeacher()){?>
                <li class="<?= $this->context->highLightUrl(['class/homework','class/work-detail','workstatistical/work-statistical-student','workstatistical/work-statistical-topic','workstatistical/work-statistical-all']) ? 'ac' : '' ?>">
                    <a href="<?= url('class/homework', ['classId' => $classId]) ?>">作业</a>
                </li>
            <?php }elseif(loginUser()->isStudent()){ ?>
                <li class="<?= $this->context->highLightUrl(['class/student-homework','classes/managetask/details']) ? 'ac' : '' ?>">
                    <a href="<?= url('class/student-homework', ['classId' => $classId]) ?>">作业</a>
                </li>
            <?php } ?>
            <li class="<?= $this->context->highLightUrl('class/member-manage') ? 'ac' : '' ?>">
                <a href="<?= url('class/member-manage', ['classId' => $classId]) ?>">班级成员</a>
            </li>
            <li class="<?= $this->context->highLightUrl(['class/class-file','class/class-file-details']) ? 'ac' : '' ?>">
                <a href="<?= url('class/class-file', ['classId' => $classId]) ?>">班级文件</a>
            </li>
            <li class="<?= $this->context->highLightUrl('class/answer-questions') ? 'ac' : '' ?>"><a
                    href="<?= Url::to(['//class/answer-questions','classId' => $classId]) ?>">班内答疑</a>
            </li>
            <li class="<?= $this->context->highLightUrl(['class/memorabilia','class/add-memorabilia','class/memorabilia-album']) ? 'ac' : '' ?>"><a
                    href="<?= url('class/memorabilia', ['classId' => $classId]) ?>">大事记</a>
            </li>
            <li class="<?= $this->context->highLightUrl(['classstatistics/default/index','classstatistics/default/overview','classstatistics/default/classes-contrast','classstatistics/namelist/index','classstatistics/onlinescore/index','classstatistics/teachercontrast/index',
                'classstatistics/homeworkexcellentrate/index','classstatistics/homeworkunfinish/index']) ? 'ac' : '' ?>"><a
                    href="<?= url('classstatistics/default/index', ['classId' => $classId]) ?>">班级统计</a>
            </li>
        </ul>
    </div>
</div>
<?php echo $content ?>

<?php $this->endContent() ?>
