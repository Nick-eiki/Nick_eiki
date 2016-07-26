<?php
/**
 * Created by
 * User: Administrator
 * Date: 15-4-13
 * Time: 上午9:55
 */
use frontend\components\helper\ImagePathHelper;
use frontend\models\dicmodels\SubjectModel;
use frontend\models\IdentityModel;
use frontend\services\pos\pos_PersonalInformationService;
use frontend\services\pos\pos_UserSloganService;

?>


<div class="main_head pr stu_head">
    <img class="img_lm" data-type="header" width="110" height="110" onerror="userDefImg(this);"
         src="<?php echo publicResources() ?><?php echo ImagePathHelper::getImage(loginUser()->getFaceIcon()) ?>">
    <?php $infoService = new pos_PersonalInformationService();
    $userItemCnt = $infoService->queryUserItemCnt(user()->id);
    ?>
    <ul class="infoList">
        <li><a href="<?php echo url('/teacher/managetask/index') ?>">
                <span><?php echo isset($userItemCnt) ? $userItemCnt->teaHwCnt : 0; ?> </span>作业</a></li>
        <li>
            <a href="<?php echo url('teacher/briefcase/briefcase-list') ?>"><span><?php echo isset($userItemCnt) ? $userItemCnt->teaMatCnt : 0; ?></span>公文包</a>
        </li>
        <li>
            <a href="<?php echo url('teacher/coursemanage/demand') ?>"><span><?php echo isset($userItemCnt) ? $userItemCnt->teaCrsCnt : 0; ?></span>精品课程</a>
        </li>
    </ul>

    <h2><?php echo loginUser()->getTrueName() ?></h2>
    <?php
    $Slogan = new pos_UserSloganService();
    $userSlogan = $Slogan->searchUserSlogan(user()->id);
    ?>
    <p class="signTxt"><span><?php echo isset($userSlogan) ? $userSlogan->userSlogan : ''; ?></span><i>编辑</i></p>

    <?php $schoolinfo = loginUser()->getSchoolInfo(); ?>
    <?php if ($schoolinfo != null) { ?>
        <p>学　校: <a
                href="<?php echo url('school/index', array('schoolId' => $schoolinfo->schoolID)) ?>"> <?php echo $schoolinfo->schoolName ?></a>
        </p>
    <?php } ?>
    <?php $classInfo = loginUser()->getClassInfo(); ?>
    <?php if (!empty($classInfo)) { ?>
        <p class="handCls">班　级:<?php foreach ($classInfo as $k => $v) {
                $className = $v->className;
                $identity = $v->identity;
                $subjectName = SubjectModel::model()->getSubjectName($v->subjectNumber);
                $identityName = IdentityModel::getIdentityNameByID($identity) . "老师"; ?>
                <a href="<?php echo url('class/index', array('classId' => $v->classID)) ?>"> <?php echo $className . "&nbsp" . $identityName; ?></a> &nbsp;&nbsp;
            <?php } ?></p>
    <?php } ?>
    <?php $groupInfo = loginUser()->getGroupInfo(); ?>
    <?php if (!empty($groupInfo)) { ?>
    <p>教研组: <?php foreach ($groupInfo as $k => $v) { ?>
            <a href="<?php echo url('teachinggroup/index', array('groupId' => $v->groupID)) ?>"><?php echo $v->groupName; ?></a> &nbsp;&nbsp;
        <?php } ?>

    <p>
        <?php } ?>

</div>
<script type="text/javascript">

    var url = "<?php echo url('ajax/ajax-user-slogan');?>";

    $('.signTxt i').editPlus({url:url,data:['val']});

</script>
