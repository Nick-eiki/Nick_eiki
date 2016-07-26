<div class="userCenter grid_6 push_12 pr">
    <?php
    use frontend\components\helper\ImagePathHelper;

    if (!$this->context->isLogin()) {
        if ($this->getRoute() != "site/login") { ?>
            <div class="login "><a class="loginBtn" href="<?php echo url('site/login') ?>">登录</a><a class="userReg"
                                                                                                    href="<?php echo url('register/') ?>">免费注册</a>
            </div>
        <?php }
    } else { ?>
        <div class="userAccount  grid_3 alpha omega">
            <img height="40px" width="40px" data-type="header" onerror="userDefImg(this);"
                 src="<?php echo publicResources(). ImagePathHelper::getImage(loginUser()->getFaceIcon());
                ?>" />
            <h5><?php echo loginUser()->getTrueName() ?><i></i></h5>
            <ul class="hide">
                <li><a class="tB" href="<?php echo $this->getManageHoneUrl() ?>">个人管理中心</a></li>
                <li><a class="tC"
                       href="<?php echo url('schoolroom/index', array('schoolId' => loginUser()->getSchoolId())) ?>">我的学校</a>
                </li>
                <?php foreach (loginUser()->getGroupInfo() as $val) { ?>
                    <li><a class="tC"
                           href="<?php echo url('teachinggroup/index', array('groupId' => $val->groupID)) ?>"><?php echo $val->groupName; ?></a>
                    </li>
                <?php } ?>

                <?php foreach (loginUser()->getClassInfo() as $v) { ?>
                    <li><a class="tC"
                           href="<?php echo url('classroom/index', array('classId' => $v->classID)) ?>"><?php echo $v->className; ?></a>
                    </li>
                <?php } ?>
                <li><a class="quit" href="<?php echo url('site/logout') ?>">退出</a></li>
            </ul>
        </div>

        <?php

        ?>
        <div class="userMsg grid_2  alpha omega">
            <h5><i></i>(<em class="notenum"><?php echo loginUser()->getMessageCount(); ?></em>)</h5>
            <ul class="subMenu hide">

                <?php if (loginUser()->isStudent()) { ?>
                    <li><a class="tC" href="<?php echo url('student/message/notice') ?>">我的通知</a></li>
                <?php } elseif (loginUser()->isTeacher()) { ?>
                    <li><a class="tC" href="<?php echo url('teacher/message/notice') ?>">我的通知</a></li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>
</div>


