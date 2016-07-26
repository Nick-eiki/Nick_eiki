<?php
/**
 * Created by unizk.
 * User: ysd
 * Date: 2015/4/15
 * Time: 12:09
 */
use frontend\services\pos\pos_PersonalInformationService;

?>
<?php
//用户个人管理中心统计项查询
$infoService = new pos_PersonalInformationService();
$userItemCnt = $infoService->queryUserItemCnt(user()->id);

?>
<div class="grid_5 main_l l_stuInfo">
    <div class="clearfix item l_userInfo " style="height: auto;"><img data-type="header" onerror="userDefImg(this);" width="230" height="230" src="<?php echo publicResources() . loginUser()->getFaceIcon() ?>">
    </div>
    <div class="item l_asideMenu">

        <ul class="setupMenu">
	        <li><a class="noBg <?php echo $this->context->highLightUrl(['student/setting/my-center', 'student/setting/set-head-pic', 'student/setting/change-password']) ? 'ac' : '' ?>"
                   href="<?php echo url('student/setting/my-center') ?>"><i></i>个人中心</a></li>

            <li><a class="lisA" href="javascript:"><i></i>我的积分</a>
                <ul class="subMenu hide">

                    <li>
                        <a class="<?php echo $this->context->highLightUrl(['student/integral/income-details']) ? 'ac' : '' ?>"
                           href="<?php echo url('student/integral/income-details') ?>">收入明细</a></li>
                    <li>
                        <a class="<?php echo $this->context->highLightUrl(['student/integral/my-ranking']) ? 'ac' : '' ?>"
                           href="<?php echo url('student/integral/my-ranking') ?>">我的等级</a></li>

                    <li>
                        <a class="<?php echo $this->context->highLightUrl(['student/integral/integral-exchange']) ? 'ac' : '' ?>"
                           href="<?php echo url('student/integral/integral-exchange') ?>">积分兑换</a></li>
                </ul>
            </li>
            <li><a class="lisA" href="javascript:;"><i></i>信息</a>
                <ul class="subMenu hide">
                    <li>
                        <a class="<?php echo $this->context->highLightUrl(['student/message/notice', 'student/message/notice']) ? 'ac' : '' ?>"
                           href="<?php echo url('student/message/notice') ?>">我的通知</a></li>

                    <li><a class="<?php echo $this->context->highLightUrl(['student/message/sys-msg', 'student/message/sys-msg']) ? 'ac' : '' ?>"
                       href="<?php echo url('student/message/sys-msg') ?>">提醒消息</a></li>
<!--                    <li><a class="--><?php //echo $this->context->highLightUrl(['student/message/message-list', 'student/message/view-message']) ? 'ac' : '' ?><!--"-->
<!--                           href="--><?php //echo url('student/message/message-list') ?><!--">我的私信</a></li>-->
                </ul>
            </li>
            <li><a class="lisB" href="javascript:;"><i></i>作业</a>
                <ul class="subMenu hide">
                    <?php
                    $className = loginUser()->getClassInfo();
                    foreach($className as $key=>$item ){ ?>
                        <li><a class="<?php echo $this->context->highLightUrl(['student/manage-task/work-manage', 'student/managetask/work-manage', 'student/managetask/details','student/managetask/new-online-answered','student/managetask/view-correct','student/managetask/new-online-answering']) ? 'ac' : '' ?>"
                                href="<?php echo url('student/managetask/work-manage',array('classid'=>$item->classID));?>"><?php echo $item->className;?></a></li>
                    <?php }?>

                </ul>
            </li>

<!--            <li>-->
<!--                <a class="noBg --><?php //echo $this->context->highLightUrl(['student/exam/manage', 'student/exam/test-detail','student/exam/upload-preview','student/exam/online-answers','student/exam/online-answered']) ? 'ac' : '' ?><!--"-->
<!--                   href="--><?php //echo url('student/exam/manage') ?><!--">考务管理</a>-->
<!--            </li>-->

            <li>
                <a class="noBg <?php echo $this->context->highLightUrl(['student/answer/answer-questions','student/answer/add-question','student/answer/update-question']) ? 'ac' : '' ?>"
                   href="<?php echo url('student/answer/answer-questions') ?>">答疑</a>
            </li>
            <li><a class="lisG noBg <?=$this->context->highLightUrl(['student/super/curve-wrecker'])?'ac':''?>" href="<?=url('student/super/curve-wrecker')?>"><i></i>学霸养成记</a>
            <li><a class="lisH" href="javascript:;"><i></i>错题管理</a>
                <ul class="subMenu hide">
                    <li>
                        <a class="<?php echo $this->context->highLightUrl(['student/wrongtopic/manage', 'student/wrongtopic/wro-top-for-item', 'student/wrongtopic/wrong-detail', 'student/wrongtopic/re-answer', 'student/wrongtopic/wrong-enter', 'student/wrongtopic/save-ques-content']) ? 'ac' : '' ?>"
                           href="<?php echo url('student/wrongtopic/manage'); ?>">错题集</a></li>
<!--                    <li>-->
<!--                        <a class="--><?php //echo $this->context->highLightUrl(['student/wrongtopic/take-photo-topic']) ? 'ac' : '' ?><!--"-->
<!--                           href="--><?php //echo url('student/wrongtopic/take-photo-topic'); ?><!--">拍照录题</a></li>-->
                </ul>
            </li>

        </ul>
    </div>

</div>