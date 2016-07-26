<?php
use frontend\components\WebDataCache;
use yii\helpers\Url;


/* @var $this yii\web\View */

$r = $this->context->getRoute();
$g = $this->context->getUniqueId();

$key = user()->id.'_' . $r.'_'. $g;

    $userModel = loginUser();
    $userId = $userModel->userID;

    $classArr = $userModel->getClassInfoCache();
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
    ?>
    <div class="headWrap">
        <div class="col1200">
            <div class="head">
                <a href="#"><h1>班海网</h1></a>
                <?php if (!user()->isGuest) {?>
                    <ul class="head_nav">
                        <li>
                            <a class="has_subMenu  <?=$this->context->highLightUrl(['platform/question/keywords-choose', 'platform/question/chapter-choose', 'platform/question/knowledge-choose','platform/video/list','platform/video/index','platform/video/detail','platform/managetask/index'])?'ac':''?>" href="javascript:;">教学资源<i></i></a>
                            <ul class="subMenu hide">
                                <?php if (loginUser()->isTeacher()) {?>
                                    <li>
                                        <a class="<?= $this->context->highLightUrl(['platform/question/keywords-choose', 'platform/question/chapter-choose', 'platform/question/knowledge-choose'], $r) ? 'ac' : '' ?>"
                                           href="<?= url('platform/question/chapter-choose') ?>">试题库</a></li>
                                    <li><a class="<?= $g == 'platform/file' ? 'ac' : '' ?>"
                                           href="<?= url('/platform/file') ?>">课件库</a></li>
                                    <li><a class="<?= $g == 'platform/managetask' ? 'ac' : '' ?>"
                                           href="<?= url('/platform/managetask/index') ?>">作业库</a>
                                    </li>
                                <?php } ?>
                                <li><a class="<?= $g == 'platform/video' ? 'ac' : '' ?>"
                                       href="<?= url('/platform/video/index') ?>">视频库</a>
                                </li>
                            </ul>

                        </li>
                        <li><a class="has_subMenu tacit" href="javascript:;">班海应用<i></i></a>
                            <ul class="subMenu hide">
                                <li><a class="<?= $this->context->highLightUrl(['terrace/answer/index'], $r) ? 'ac' : '' ?>"
                                       href="<?= url('terrace/answer/index') ?>">问题答疑</a>
                                </li>
                                <?php if(loginUser()->isTeacher()){?>
                                <li><a class="" href="http://ppjy.banhai.com">品牌教研</a></li>
                                <li><a class="" href="http://zixun.banhai.com">班海资讯</a></li>
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
                            <li>
                                <?php if (isset($teaGroup[0])){ ?>
                                        <a class=" tacit <?= $g == 'teachgroup' ? 'ac' : '' ?>" href="<?= url('teachgroup/index', array('groupId' => $teaGroup[0]->groupID)); ?>">
                                            教研组
                                            <i></i>
                                        </a>
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
                                        <a class="<?= $this->context->highLightUrl(['teacher/setting/personal-center'], $r) ? 'ac' : '' ?>" href="<?= $homeUrl ?>"><i class="center_pep"></i>个人中心</a>
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

                            <a href="javascript:;" class="sysMsg"><i></i><b id="messageCount">(0)</b></a>
                            <ul class="msgList hide ">

                                <?php if (loginUser()->isTeacher()) { ?>
                                    <li class="sendBar">

                                        <a href="<?= url('teacher/message/msg-contact?show=sendwin'); ?>"
                                           class="sendMsg">发送通知</a>

                                    </li>
                                <?php } ?>
                                <?php if (loginUser()->isStudent()) { ?>
                                    <li><a href="<?= url('student/message/notice'); ?>">学校通知(<b id="messageNotice">0</b>)</a>
                                    </li>
                                    <li><a href="<?= url('student/message/sys-msg'); ?>">提醒消息(<b id="messageSys">0</b>)</a>
                                    </li>

                                <?php } elseif (loginUser()->isTeacher()) { ?>

                                    <li><a href="<?= url('teacher/message/msg-contact'); ?>">学校通知(<b id="messageNotice">0</b>)</a>
                                    </li>
                                    <li><a href="<?= url('teacher/message/notice'); ?>">提醒消息(<b id="messageSys">0</b>)</a>
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
    $(function(){
        $(document).ready(function(sumCnt,priMsg,notice,sysMsg){
            $.get("<?php echo url("/ajax/msg-num")?>",{},function(data){
                $("#messageCount").html("(" + data.sumCnt + ")");
                $("#messageNotice").html(data.notice);
                $("#messageSys").html(data.sysMsg);
                if(data.sumCnt>99){
                    $(".sysMsg").addClass("over99");
                }
            });
        });
    })
</script>

