<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-4-13
 * Time: 上午9:55
 */

?>
<?php
//用户个人管理中心统计项查询
//$infoService = new pos_PersonalInformationService();
//$userItemCnt = $infoService->queryUserItemCnt(user()->id);
//$ClassInfoList=loginUser()->getClassInfo();

$classid = app()->request->getParam('classid')?app()->request->getParam('classid'):app()->request->getParam('classID');

?>
<div class="grid_5 main_l">
    <div class="clearfix item l_userInfo" style="height: auto;"><img data-type="header" onerror="userDefImg(this);"  width="230" height="230"
                                                                     src="<?php echo publicResources() . loginUser()->getFaceIcon() ?>">
        <!--        <a  href="-->
        <?php //echo url('teacher/managepaper/index');?><!--" style="border-right:1px solid #ddd"><em>-->
        <?php //echo $userItemCnt->teaPaperCnt ?><!--</em><br>试卷</a>-->
        <!--        <a><em>--><?php //echo $userItemCnt->stuCrsCnt ?><!--</em><br>课程</a>-->
    </div>
    <div class="item l_asideMenu">
        <!--        <h4>我的档案</h4>-->
        <ul class="setupMenu">

	        <li><a class="lisA noBg <?php echo $this->context->highLightUrl(['teacher/setting/personal-center', 'teacher/setting/change-password', 'teacher/setting/set-head-pic']) ? 'ac' : '' ?>"
                   href="<?php echo url('teacher/setting/personal-center') ?>"><i></i>个人中心</a>
            </li>
            <li><a class="lisA" href="javascript:"><i></i>我的积分</a>
                <ul class="subMenu hide">

                    <li>
                        <a class="<?php echo $this->context->highLightUrl(['teacher/integral/income-details']) ? 'ac' : '' ?>"
                           href="<?php echo url('teacher/integral/income-details') ?>">收入明细</a></li>

                    <li>
                        <a class="<?php echo $this->context->highLightUrl(['teacher/integral/my-ranking']) ? 'ac' : '' ?>"
                           href="<?php echo url('teacher/integral/my-ranking') ?>">我的等级</a></li>
                    <li>
                        <a class="<?php echo $this->context->highLightUrl(['teacher/integral/integral-exchange']) ? 'ac' : '' ?>"
                           href="<?php echo url('teacher/integral/integral-exchange') ?>">积分兑换</a></li>
                </ul>
            </li>

            <li><a class="lisA" href="javascript:"><i></i>信息</a>
                <ul class="subMenu hide">
                    <li><a class="<?php echo $this->context->highLightUrl(['teacher/message/msg-contact']) ? 'ac' : '' ?>"
                           href="<?php echo url('teacher/message/msg-contact'); ?>">通知</a></li>
                    <li>
                        <a class="<?php echo $this->context->highLightUrl(['teacher/message/notice', 'teacher/message/notice']) ? 'ac' : '' ?>"
                           href="<?php echo url('teacher/message/notice') ?>">提醒消息</a></li>
<!--                    <li>-->
<!--                        <a class="--><?php //echo $this->context->highLightUrl(['teacher/message/message-list', 'teacher/message/view-message']) ? 'ac' : '' ?><!--"-->
<!--                           href="--><?php //echo url('teacher/message/message-list') ?><!--">我的私信</a></li>-->
                </ul>
            </li>
<!--            <li>-->
<!--                <a class="lisB noBg --><?php //echo $this->context->highLightUrl(['/platform/file', '/platform/question/keywords-choose','platform/question/keywords-choose', 'platform/question/chapter-choose', 'platform/question/knowledge-choose','platform/video/list','platform/video/index','platform/video/detail']) ? 'ac' : '' ?><!--"-->
<!--                   href="--><?php //echo url('/platform/file') ?><!--">课件库</a>-->
<!--            </li>-->
            <li>
                <a class="lisC noBg <?php echo $this->context->highLightUrl(['teacher/managetask/work-manage', 'teacher/managetask/work-details', 'teacher/managetask/new-update-work-detail',
                    'teacher/managetask/new-fixup-work','teacher/managetask/new-update-work','teacher/managetask/new-preview-organize-paper','teacher/managetask/work-details',
                    'teacher/managetask/view-correct','teacher/managetask/new-correct-paper','teacher/managetask/new-correct-paper','teacher/managetask/organize-work-details-new']) ? 'ac' : '' ?>"
                   href="<?php echo url('teacher/resources/collect-work-manage');?>">作业</a>
            </li>

<!--            <li><a class="lisD" href="javascript:"><i></i>考务管理</a>-->
<!--                <ul class="subMenu hide">-->
<!--                    --><?php //foreach ($ClassInfoList as $v) { ?>
<!--                        <li>-->
<!--                            <a class="--><?php //echo $this->context->highLightUrl(['teacher/exam/manage', 'teacher/exam/create-exam', 'teacher/exam/over-all-appraise','teacher/exam/subject-details'])&& $classid == $v->classID ? 'ac' : '' ?><!--"-->
<!--                               href="--><?php //echo url('teacher/exam/manage', array('classid' => $v->classID)) ?><!--">--><?php //echo $v->className ?><!--</a>-->
<!--                        </li>-->
<!--                    --><?php //} ?>
<!--                </ul>-->
<!--            </li>-->
            <li>
                <a class="lisE noBg <?php echo $this->context->highLightUrl(['teacher/managepaper/index', 'teacher/managepaper/index','teacher/managepaper/upload-paper','teacher/makepaper/paper-subject',
                    'teacher/make-paper/index','teacher/make-paper/paper-structure','teacher/make-paper/paper-set-score']) ? 'ac' : '' ?>"
                   href="<?php echo url('teacher/managepaper/index'); ?>">试卷管理</a>
            </li>
<!--            <li>-->
<!--                <a class="lisF --><?php //echo $this->context->highLightUrl(['teacher/searchquestions/chapter-questions', 'teacher/searchquestions/view-test', 'teacher/searchquestions/chapter-questions'
//                    , 'teacher/searchquestions/chapter-questions', 'teacher/testpaper/add-topic', 'teacher/testpaper/camera-upload-new-topic', 'teacher/testpaper/save-ques-content', 'teacher/testpaper/topic-finish', 'teacher/testpaper/camera-uploadnewtopic',
//                    'teacher/testpaper/modify-topic','teacher/testpaper/modify-quescontent','teacher/testpaper/modify-camera-upload-new-topic','teacher/testpaper/modify-ques-content']) ? 'ac' : '' ?><!-- noBg"-->
<!--                   href="--><?php //echo url('teacher/searchquestions/chapter-questions') ?><!--">题目管理</a>-->
<!--            </li>-->
            <li>
                <a class="noBg <?php echo $this->context->highLightUrl(['teacher/answer/answer-questions', 'teacher/answer/view-test', 'teacher/answer/add-question', 'teacher/answer/update-question']) ? 'ac' : '' ?>"
                   href="<?php echo url('teacher/answer/answer-questions') ?>">答疑</a></li>
<!--            <li><a class="lisI" href="javascript:"><i></i>大数据</a>-->
<!--                <ul class="subMenu hide">-->
<!--                    <li><a href="javascript:" class="hasSubMenu">学生个人数据</a>-->
<!--                        <ul class="subMenu">-->
<!--                            --><?php //foreach ($ClassInfoList as $v) { ?>
<!--                                <li>-->
<!--                                    <a class="--><?php //echo ($this->context->highLightUrl(['teacher/count/personal-statics']) && app()->request->getParam('classID') == $v->classID) ? 'ac' : '' ?><!--"-->
<!--                                       href="--><?php //echo url('teacher/count/personal-statics', array('classID' => $v->classID)) ?><!--">-->
<!--                                        &nbsp;&nbsp;&nbsp;&nbsp;--><?php //echo $v->className ?><!--</a>-->
<!--                                </li>-->
<!---->
<!--                            --><?php //} ?>
<!--                        </ul>-->
<!--                    </li>-->
<!--                    <li><a href="javascript:" class="hasSubMenu">班级统计</a>-->
<!--                        <ul class="subMenu">-->
<!--                            --><?php //foreach ($ClassInfoList as $v) { ?>
<!--                                <li>-->
<!--                                    <a class="--><?php //echo ($this->context->highLightUrl(['teacher/count/class-statics']) && app()->request->getParam('classID') == $v->classID) ? 'ac' : '' ?><!--"-->
<!--                                       href="--><?php //echo url('teacher/count/class-statics', array('classID' => $v->classID)) ?><!--">-->
<!--                                        &nbsp;&nbsp;&nbsp;&nbsp;--><?php //echo $v->className ?><!--</a>-->
<!--                                </li>-->
<!---->
<!--                            --><?php //} ?>
<!--                        </ul>-->
<!--                    </li>-->
<!--                    <li><a href="javascript:" class="hasSubMenu">藤条棍</a>-->
<!--                        <ul class="subMenu">-->
<!--                            --><?php //foreach ($ClassInfoList as $v) { ?>
<!--                                <li>-->
<!--                                    <a class="--><?php //echo ($this->context->highLightUrl(['teacher/count/new-bear-child']) && app()->request->getParam('classID') == $v->classID) ? 'ac' : '' ?><!--"-->
<!--                                       href="--><?php //echo url('teacher/count/new-bear-child', array('classID' => $v->classID)) ?><!--"> &nbsp;&nbsp;&nbsp;&nbsp;--><?php //echo $v->className ?><!--</a>-->
<!--                                </li>-->
<!---->
<!--                            --><?php //} ?>
<!--                        </ul>-->
<!--                    </li>-->
<!--                </ul>-->
<!---->
<!--            </li>-->
        </ul>
    </div>
</div>