<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-9-19
 * Time: 上午9:56
 */
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <script src="<?php echo publicResources() ?>/js/jquery-1.7.1.min.js" type="text/javascript"></script>
    <script src="<?php echo publicResources() ?>/js/main.js" type="text/javascript"></script>
    <script src="<?php echo publicResources() ?>/js/base.js" type="text/javascript"></script>
    <script src="<?php echo publicResources() ?>/js/jquery-ui.min.js" type="text/javascript"></script>
    <link href="<?php echo publicResources() ?>/css/base.css" type="text/css" rel="stylesheet">
    <link href="<?php echo publicResources() ?>/css/student.css" type="text/css" rel="stylesheet">
    <link href="<?php echo publicResources() ?>/css/jquery-ui.css" type="text/css" rel="stylesheet">
    <link href="<?php echo publicResources() ?>/css/popBox.css" type="text/css" rel="stylesheet">
    <title><?php echo $this->getPageTitle()?></title>
</head>

<body>
<?php echo $this->render("application.views.layouts._user_header")?>
<!--主体内容开始-->
<div class="main cont24 clearfix">
    <?php echo $this->render("application.modules.student.views.layouts._user_info_main")?>
    <div class="mainNav grid_24">
        <ul class="mainNav_L">
            <li class="<?php echo $this->context->highLightUrl(['student/setting/set-info']) ? 'ac' : '' ?>"><a
                    href="<?php echo url('student/setting/set-info') ?>">基本信息</a></li>
            <li class="<?php echo $this->context->highLightUrl(['student/setting/set-head-pic']) ? 'ac' : '' ?>"><a
                    href="<?php echo url('student/setting/set-head-pic') ?>">头像管理</a></li>
            <li class="<?php echo $this->context->highLightUrl(['student/setting/change-password']) ? 'ac' : '' ?>"><a
                    href="<?php echo url('student/setting/change-password') ?>">密码管理</a></li>
            <li class="<?php echo $this->context->highLightUrl(['student/setting/set-email']) ? 'ac' : '' ?>"><a
                    href="<?php echo url('student/setting/set-email') ?>">邮箱管理</a></li>
        </ul>
        <ul class="mainNav_R clearfix">
            <li> <i class="set"></i><span class="bColor setJs"><a href="<?php echo $this->getSetHoneUrl()?>">个人设置</a></span>
                <ul class="tab hide">
                    <li>零食</li>
                    <li>零食</li>
                    <li>零食</li>
                    <li>零食</li>
                </ul>
            </li>
            <li><i class="dressUp"></i><span class="dress_k">装扮空间</span></li>
            <li><i class="management"></i><span><a href="<?php echo $this->getManageHoneUrl() ?>">个人管理中心</a></span></li>
        </ul>
    </div>
    <?php echo $content?>
</div>
</div>
<div class="footWrap">
    <?php
    echo $this->render('application.views.layouts._user_footer');
    ?>
</div>

<!--弹出框pop--------------------->

</body>
</html>
