<?php


/* @var $this yii\web\View */

use frontend\services\pos\pos_MessageSentService;

$r = $this->context->getRoute();
$g = $this->context->getUniqueId();

$key = user()->id.'_' . $r.'_'. $g;
if ($this->beginCache($key, ['duration' => 600])) {



   if(!user()->isGuest){
       $userModel = loginUser();

       $classArr = $userModel->getClassInfo();
       $schoolid = $userModel->schoolID;

//教研组信息
       $teaGroup = $userModel->getGroupInfo();

//手拉手班级

       $binderClass = [];


       if ($userModel->isStudent()) {
           $homeUrl = url('student/setting/my-center');
           $msgUrl = url('student/message/message-list?show=sendwin');
       }
       if ($userModel->isTeacher()) {
           $homeUrl = url('teacher/setting/personal-center');
           $msgUrl = url('teacher/message/message-list?show=sendwin');
       }
   }

    ?>
    <div class="head">
        <div class="cont24">
            <h1>班海网</h1>
            <?php if (!user()->isGuest) {


                if (loginUser()->isStudent()) {
                    $homeUrl = url('student/setting/my-center');
                    $msgUrl = url('student/message/message-list?show=sendwin');
                }
                if (loginUser()->isTeacher()) {
                    $homeUrl = url('teacher/setting/personal-center');
                    $msgUrl = url('teacher/message/message-list?show=sendwin');
                }

                ?>

                <ul class="head_nav">

                    <?php
                    if (loginUser()->isTeacher()) {
                        ?>
                        <li>
                            <a class="<?= $this->context->highLightUrl(['platform/question/keywords-choose', 'platform/question/chapter-choose', 'platform/question/knowledge-choose'], $r) ? 'ac' : '' ?>"
                               href="<?= url('platform/question/chapter-choose') ?>">试题库</a></li>
                        <li><a class="<?= $g == 'teacher/prepare' ? 'ac' : '' ?>"
                               href="<?= url('/platform/file') ?>">课件库</a></li>
                        <li><a class="<?= $g == 'platform/managetask' ? 'ac' : '' ?>"
                               href="<?= url('/platform/managetask/index') ?>">作业库</a>
                        </li>
                    <?php } ?>
                    <li><a class="<?= $g == 'terrace/videopaper' ? 'ac' : '' ?>"
                           href="<?= url('/platform/video/index') ?>">视频库</a>
                    </li>
                    <li><a class="<?= $this->context->highLightUrl(['terrace/answer/index'], $r) ? 'ac' : '' ?>"
                           href="<?= url('terrace/answer/index') ?>">问题答疑</a>
                    </li>
                    <li><a class="<?= $g == 'school' ? 'ac' : '' ?>"
                           href="<?= url('school/index', array('schoolId' => $schoolid)); ?>">学校</a>
                    </li>
                    <li><a class="has_subMenu <?= $g == 'class' ? 'ac' : '' ?>">班级帮</a>
                        <ul class="subMenu hide">

                            <li>
                                <dl>
                                    <dt>我的班级</dt>
                                    <?php foreach ($classArr as $valClass) { ?>
                                        <dd><a href="<?= url('class/index', array('classId' => $valClass->classID)); ?>"
                                               title="<?= $valClass->className ?>"><?= $valClass->className ?></a></dd>
                                    <?php } ?>

                                </dl>
                            </li>

                        </ul>
                    </li>
                    <?php if (loginUser()->isTeacher()) { ?>
                        <?php if (isset($teaGroup[0])){ ?>
                        <li><a class="<?= $g == 'teachgroup' ? 'ac' : '' ?>"
                               href="<?= url('teachgroup/index', array('groupId' => $teaGroup[0]->groupID)); ?>">教研组</a>
                        </li>
                        <?php } ?>
                        <li><a class="" href="http://ppjy.banhai.com">品牌教研</a></li>
                        <li><a class="" href="http://zixun.banhai.com">班海资讯</a></li>
                    <?php } ?>
                </ul>


                <div class="userCenter">
                    <div class="userChannel">
                        <a class="userName" href="<?= $homeUrl ?>" title="<?= loginUser()->getTrueName() ?>"><i></i><?= loginUser()->getTrueName() ?></a>
                    </div>
                    <a href="<?= url('site/logout') ?>" class="logOff">退出</a>

                    <div class="msgAlert hasMsg">
                        <a href="javascript:;" class="sysMsg"><b id="messageSum">(0)</b></a>
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


    <?php $this->endCache();
} ?>
<script type="text/javascript">
    $(function(){
        $(document).ready(function(sumCnt,priMsg,notice,sysMsg){
            $.get("<?php echo url("/ajax/msg-num")?>",{},function(data){

                $("#messageSum").html("(" + data.sumCnt + ")");
                $("#messageNotice").html(data.notice);
                $("#messageSys").html(data.sysMsg);
                if(data.sumCnt>99){
                    $(".sysMsg").addClass("over99");
                }
            });
        });
    })
</script>