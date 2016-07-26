<?php
/**
 *
 */
use frontend\services\pos\pos_MessageSentService;


?>

<?php if (!user()->isGuest) {

    $userModel = loginUser()->getModel(false);
    $classArr = $userModel->getClassInfo();
    $schoolid = $userModel->schoolID;

//教研组信息
    $teaGroup = $userModel->getGroupInfo();

//手拉手班级

    $binderClass = [];

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
                <a class="<?= $this->context->highLightUrl(['teacher/searchquestions/knowledge-point-questions','teacher/searchquestions/chapter-questions','teacher/searchquestions/keyword-questions'],$r) ? 'ac' : '' ?>"
                   href="<?= url('teacher/searchquestions/keyword-questions') ?>">试题库</a></li>
            <li><a class="<?= $g == 'teacher/prepare' ? 'ac' : '' ?>"
                   href="<?= url('teacher/prepare') ?>">课件库</a></li>
        <?php } ?>
        <li><a class="<?= $g == 'terrace/videopaper' ? 'ac' : '' ?>"
               href="<?= url('terrace/videopaper') ?>">视频库</a>
        </li>
        <li><a class="<?= $this->context->highLightUrl(['terrace/answer/index'],$r) ? 'ac' : '' ?>"
               href="<?= url('terrace/answer/index') ?>">平台答疑</a>
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
                    <dl class="hand_in_hand_classes_list">
                        <dt class="pr">手拉手班级</dt>
                        <!--<a href="../classes/classes_add_hand_in_hand.html" class=" hideText editLink"></a>-->
                        <?php foreach ($binderClass as $binder) { ?>
                            <dd><a href="<?= url('class/index', array('classId' => $binder->classID)); ?>"
                                   title="<?= $binder->className ?>"><?php echo $binder->className ?></a><i
                                    title="<?= $binder->schoolName ?>"></i></dd>
                        <?php } ?>
                    </dl>


                </li>

            </ul>
        </li>
        <?php if (loginUser()->isTeacher()) { ?>
            <li><a class="<?= $g == 'teachgroup' ? 'ac' : '' ?>"
                   <?php if(isset($teaGroup[0])){?>
                   href="<?= url('teachgroup/index', array('groupId' => $teaGroup[0]->groupID)); ?>">教研组</a>
                    <?php }?>
            </li>
        <?php } ?>
    </ul>
    <div class="userCenter">
        <div class="userChannel"><a class="userName" href="<?= $homeUrl ?>"
                                    title="<?= loginUser()->getTrueName() ?>"><i></i><?= loginUser()->getTrueName() ?>
            </a></div>
        <a href="<?= url('site/logout') ?>" class="logOff">退出</a>

        <div class="msgAlert hasMsg">
            <?php
            $obj = new pos_MessageSentService();
            $result = $obj->stasticUserMessage(user()->id);
            ?>
            <a href="javascript:;">(<?= $result->sumCnt; ?>)</a>
            <ul class="msgList hide ">
                <span class="arrow"></span>

                <li class="sendBar">
                    <?php if (loginUser()->isTeacher()) { ?>
                        <a href="<?= url('teacher/message/msg-contact?show=sendwin'); ?>"
                           class="sendMsg"><i></i><br>发送通知</a>
                    <?php } ?>
                    <a href="<?= $msgUrl; ?>" class="sendLetter"><i></i><br>发送私信</a>
                    <hr class="cutLine">
                </li>


                <?php if (loginUser()->isStudent()) { ?>
                    <li><a href="<?= url('student/message/notice'); ?>">通知(<?= $result->notice; ?>)</a></li>
                    <li><a href="<?= url('student/message/sys-msg'); ?>">系统消息(<?= $result->sysMsg; ?>)</a></li>
                    <li><a href="<?= url('student/message/message-list'); ?>">我的私信(<?= $result->priMsg; ?>)</a>
                    </li>
                <?php } elseif (loginUser()->isTeacher()) { ?>

                    <li><a href="<?= url('teacher/message/msg-contact'); ?>">通知(<?= $result->notice; ?>)</a>
                    </li>
                    <li><a href="<?= url('teacher/message/notice'); ?>">系统消息(<?= $result->sysMsg; ?>)</a></li>
                    <li><a href="<?= url('teacher/message/message-list'); ?>">我的私信(<?= $result->priMsg; ?>)</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
<?php } ?>
