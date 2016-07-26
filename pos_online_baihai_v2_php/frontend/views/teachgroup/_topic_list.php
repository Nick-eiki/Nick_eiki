<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/27
 * Time: 18:26
 */
use frontend\components\CHtmlExt;
use frontend\components\helper\ViewHelper;
use frontend\components\WebDataCache;
use yii\helpers\Url;

?>
    <script type='text/javascript'>
        $(function () {
            /*编辑弹窗*/
            $('.edit_btn').click(function () {
                var courseId = $(this).attr('data-course');
                var groupId = '<?= app()->request->getQueryParam('groupId','')?>';
                $.post('<?= Url::to(['/teachgroup/modify-topic'])?>', {
                    'courseId': courseId,
                    'groupId': groupId
                }, function (data) {
                    $('#popBox2').html(data);
                    $("#popBox2").dialog("open");
                    return false;
                });

                //点击取消操作
                $('#popBox2 .cancelBtn').live('click', function () {
                    $("#popBox2").dialog("close");
                    return false;
                })

            });
        })
    </script>

    <ul class="res_topicsList topic_list">
        <?php
            if(empty($course)){
                ViewHelper::emptyView();
            }
            foreach ($course as $val) {
            ?>
            <li>
                <div class="title noBorder">
                    <h4><?= \frontend\components\WebDataCache::getGradeName($val->gradeID) ?>：</h4>
                    <h4>
                        <a href="<?= Url::to(['teachgroup/topic-details', 'groupId' => $groupId, 'courseId' => $val->courseID]) ?>"><?= CHtmlExt::encode($val->courseName); ?></a>
                    </h4>
                    <a href="javascript:;;" class="edit_btn" data-course="<?= $val->courseID ?>"></a> <a
                        href="<?= Url::to(['teachgroup/topic-report', 'groupId' => $groupId, 'courseId' => $val->courseID]) ?>"
                        class="edit_btn add_btn"></a>

                    <div class="title_r"><span><?= date("Y-m-d H:i", $val->createTime/1000) ?></span>
                    </div>
                </div>
                <p><?= CHtmlExt::encode($val->brief); ?></p>

                <p class="members">
                    <em class="gray_d">课题成员：</em>
                    <?php foreach ($val->groupCourseMember as $v) { ?>
                        <img width="30px" height="30px" data-type="header" onerror="userDefImg(this);"
                             src="<?= WebDataCache::getFaceIcon($v->teacherID) ?>" title="<?= WebDataCache::getTrueName($v->teacherID)?>"/>
                    <?php } ?>
                    <button class="moreUserBtn hide">更多</button>
                </p>
            </li>
        <?php } ?>
    </ul>
<?php


echo \frontend\components\CLinkPagerExt::widget([
        'pagination' => $pages,
        'updateId' => '#topicPage',
        'maxButtonCount' => 5
    ]
);
?>