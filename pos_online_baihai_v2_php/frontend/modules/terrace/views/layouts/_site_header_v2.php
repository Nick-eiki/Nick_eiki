<?php
use frontend\components\WebDataCache;
use frontend\services\pos\pos_MessageSentService;
use yii\helpers\Url;


/* @var $this yii\web\View */

$r = $this->context->getRoute();
$g = $this->context->getUniqueId();

$key = user()->id.'_' . $r.'_'. $g;

    $userModel = loginUser();
    $userId = $userModel->userID;

    $classArr = $userModel->getClassInfo();
    $schoolid = $userModel->schoolID;

    //教研组信息
    $teaGroup = $userModel->getGroupInfo();

    if (loginUser()->isStudent()) {
        $homeUrl = url('student/setting/my-center');
        $msgUrl = url('student/message/message-list?show=sendwin');
        $userSetUrl = url('student/setting/set-head-pic');
        $userUrl = Url::to(['/student/default/index','studentId'=>$userId]);
    }
    if (loginUser()->isTeacher()) {
        $homeUrl = url('teacher/setting/personal-center');
        $msgUrl = url('teacher/message/message-list?show=sendwin');
        $userSetUrl = url('teacher/setting/set-head-pic');
        $userUrl = Url::to(['/teacher/default/index','teacherId'=>$userId]);
    }

$obj = new pos_MessageSentService();
$result = $obj->stasticUserMessage(user()->id);
    ?>
    <div class="headWrap">
        <div class="col1200">
            <div class="head">
                <a href="#"><h1>班海网</h1></a>
                <?php if (!user()->isGuest) {?>
                    <ul class="head_nav">
                        <li>
                            <a class="has_subMenu  <?=$this->context->highLightUrl(['platform/question/keywords-choose', 'platform/question/chapter-choose', 'platform/question/knowledge-choose','platform/video/list','platform/video/index','platform/video/detail'])?'ac':''?>" href="javascript:;">平台资源<i></i></a>
                            <ul class="subMenu hide">
                                <?php if (loginUser()->isTeacher()) {?>
                                    <li>
                                        <a class="<?= $this->context->highLightUrl(['platform/question/keywords-choose', 'platform/question/chapter-choose', 'platform/question/knowledge-choose'], $r) ? 'ac' : '' ?>"
                                           href="<?= url('platform/question/keywords-choose') ?>">试题库</a></li>
                                    <li><a class="<?= $g == 'platform/file' ? 'ac' : '' ?>"
                                           href="<?= url('/platform/file') ?>">课件库</a></li>
                                <?php } ?>
                                <li><a class="<?= $g == 'platform/video' ? 'ac' : '' ?>"
                                       href="<?= url('/platform/video/index') ?>">视频库</a>
                                </li>
                            </ul>
                        </li>
                        <li><a class="has_subMenu tacit" href="javascript:;">平台应用<i></i></a>
                            <ul class="subMenu hide">
                                <li><a class="<?= $this->context->highLightUrl(['terrace/answer/index'], $r) ? 'ac' : '' ?>"
                                       href="<?= url('terrace/answer/index') ?>">平台答疑</a>
                                </li>
                                <?php if(loginUser()->isTeacher()){?>
                                <li><a class="" href="http://ppjy.banhai.com">品牌教研</a></li>
                                <li><a class="" href="http://zixun.banhai.com">平台资讯</a></li>
                                <?php }?>
                            </ul>
                        </li>
                        <li><a class="has_subMenu tacit <?= $g == 'school' ? 'ac' : '' ?>"
                               href="<?= url('school/index', array('schoolId' => $schoolid)); ?>">学校</a>
                        </li>
                        <li><a class="has_subMenu tacit">班级帮<i></i></a>
                            <ul class="subMenu hide">
                                <li>
                                    <?php foreach ($classArr as $valClass) { ?>
                                        <a class="<?= $this->context->highLightUrl(['classes/managetask/details','class/work-detail','workstatistical/work-statistical-student','workstatistical/work-statistical-topic','workstatistical/work-statistical-all','class/index', 'class/homework', 'class/member-manage','class/class-file','class/answer-questions','class/memorabilia','class/add-memorabilia','class/memorabilia-album'], $r) ? 'ac' : '' ?>"
                                            href="<?= url('class/index', array('classId' => $valClass->classID)); ?>"
                                               title="<?= $valClass->className ?>"><?= $valClass->className ?></a>
                                    <?php } ?>
                                </li>
                            </ul>
                        </li>
                        <?php if (loginUser()->isTeacher()) { ?>
                            <li><a class=" tacit <?= $g == 'teachgroup' ? 'ac' : '' ?>"
                                    <?php if (isset($teaGroup[0])){ ?>
                                   href="<?= url('teachgroup/index', array('groupId' => $teaGroup[0]->groupID)); ?>">教研组<i></i></a>
                                <?php } ?>
                            </li>
                        <?php } ?>
                    </ul>
                    <div class="userCenter">
                        <div class="userChannel">

                            <div class="centerBox">
                                <i></i>
                                <ul class="personal_center">
                                    <li>
                                        <a href="<?= $homeUrl ?>"><i class="center_pep"></i>个人中心</a>
                                    </li>
                                    <li>
                                        <a href="<?= $userUrl?>"><i class="center_space"></i>我的空间</a>
                                    </li>
                                    <li>
                                        <a href="<?= $userSetUrl?>"><i class="center_set"></i>账号设置</a>
                                    </li>
                                    <li>
                                        <a href="<?= url('site/logout') ?>" class="logOff"><i class="center_quit"></i>退出登录</a>
                                    </li>
                                </ul>
                            </div>
                            <a class="userName" href="<?= $homeUrl ?>" title="<?= loginUser()->getTrueName() ?>">
                                <img src="<?= WebDataCache::getFaceIcon(user()->id) ?>" style="vertical-align: middle;" data-type="header" onerror="userDefImg(this);" />
                                <?= loginUser()->getTrueName() ?>
                            </a>
                        </div>
                        <div class="msgAlert hasMsg">

                            <a href="javascript:;"><i></i>(<b id="messageCount"><?= $result->sumCnt; ?></b>)</a>
                            <ul class="msgList hide ">
                                <span class="arrow"></span>
                                <?php if (loginUser()->isTeacher()) { ?>
                                    <li class="sendBar">

                                        <a href="<?= url('teacher/message/msg-contact?show=sendwin'); ?>"
                                           class="sendMsg"><i></i><br>发送通知</a>
                                        <hr class="cutLine">
                                    </li>
                                <?php } ?>
                                <?php if (loginUser()->isStudent()) { ?>
                                    <li><a href="<?= url('student/message/notice'); ?>">通知(<b id="messageNotice"><?= $result->notice; ?></b>)</a>
                                    </li>
                                    <li><a href="<?= url('student/message/sys-msg'); ?>">系统消息(<b id="messageSys"><?= $result->sysMsg; ?></b>)</a>
                                    </li>

                                <?php } elseif (loginUser()->isTeacher()) { ?>

                                    <li><a href="<?= url('teacher/message/msg-contact'); ?>">通知(<b id="messageNotice"><?= $result->notice; ?></b>)</a>
                                    </li>
                                    <li><a href="<?= url('teacher/message/notice'); ?>">系统消息(<b id="messageSys"><?= $result->sysMsg; ?></b>)</a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <a class="help" href="http://www.banhai.com/pub/help/focus_map_video.html" title="帮助"></a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>



<script type="text/javascript">
//    require(['jquery'], function($) {
//        $('.centerBox').hover(
//            function() {
//                $(this).addClass('centerBox_hover');
//            },
//            function() {
//                $(this).removeClass('centerBox_hover');
//            }
//        )
//    });
</script>


