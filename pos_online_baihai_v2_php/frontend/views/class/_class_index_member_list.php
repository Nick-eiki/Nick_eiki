<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2015/12/10
 * Time: 17:23
 */
use frontend\components\WebDataCache;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var common\models\pos\SeClass $classModel */
$classTea = $classModel->getClassTea();
$classStu = $classModel->getClassStu();

$stuMem = count($classStu);
?>
<script>
    $(function () {
        //截取右侧班级成员10个显示
        $('.ta_student_list').children('li:gt(9)').hide();
        //点击展开
        $(".interlink .all").toggle(
            function () {
                $(".student_list").css("height", "auto");
                $(this).text("收起");
            },
            function () {
                $(".student_list").css("height", 100);
                $(this).text("查看全部<?php echo $stuMem; ?>位成员");
            }
        );
    });
</script>
<div class="sUI_formList sUI_formList6 sUI_formList_min head_portrait">
    <div class="row">
        <h5>教师</h5>
        <ul class="sUI_user_list sUI_user_list_big clearfix">
            <?php foreach ($classTea as $teaVal) { ?>

                <li>
                    <a href="<?php echo Url::to(['student/default/index', 'studentId' => $teaVal->userID]) ?>"
                       title="<?php echo Html::encode(WebDataCache::getTrueName($teaVal->userID)); ?>">
                        <img data-type='header' onerror="userDefImg(this);"
                             src="<?php echo publicResources() . WebDataCache::getFaceIcon($teaVal->userID,50); ?>"
                             width="50" height="50" alt="">
                        <?php echo Html::encode(WebDataCache::getTrueName($teaVal->userID)); ?>
                    </a>
                </li>

            <?php } ?>
        </ul>
    </div>
    <div class="row noBorder">
        <h5>学生</h5>
        <ul class="sUI_user_list sUI_user_list_big clearfix">
            <?php foreach ($classStu as $tuVal) { ?>

                <li>
                    <a href="<?php echo Url::to(['student/default/index', 'studentId' => $tuVal->userID]) ?>"
                       title="<?php echo Html::encode(WebDataCache::getTrueName($tuVal->userID)); ?>">
                        <img data-type='header' onerror="userDefImg(this);"
                             src="<?php echo publicResources() . WebDataCache::getFaceIcon($tuVal->userID,50); ?>"
                             width="50" height="50" alt="">
                        <?php echo Html::encode(WebDataCache::getTrueName($tuVal->userID)); ?>
                    </a>
                </li>

            <?php } ?>
        </ul>


    </div>
</div>