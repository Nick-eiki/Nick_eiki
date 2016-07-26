<?php
use frontend\components\WebDataCache;
use frontend\components\WebDataKey;
use yii\helpers\Html;

$this->title = '教师个人中心';
$this->blocks['requireModule']='app/teacher/teacher_home';

?>
<div class="main col1200 clearfix tch_home" id="requireModule" rel="app/teacher/teacher_home">
  <div class="container col910 alpha no_bg">
    <div class="tch_nav">
      <ul>
          <li class="send"><a href="<?= url('teacher/message/msg-contact?show=sendwin'); ?>"><i></i>发通知</a></li>
          <li class="leave"><a href="<?= url('teacher/resources/collect-work-manage');?>"><i></i>留作业</a></li>
          <li class="ask"><a href="<?= url(['teacher/answer/add-question']); ?>"><i></i>提问题</a></li>
          <li class="upload noBorder"><a href="<?= url('teacher/prepare/upload-files');?>"><i></i>上传课件</a></li>
      </ul>
    </div>
    <div class="myResources">
      <div class="title_pannel sUI_pannel">
        <div class="pannel_l"><h4><i></i><span>我的资源</span></h4></div>
      </div>
      <div class="resources">
          <?php

          if ($this->beginCache(WebDataKey::WEB_TEACHER_PERSONAL_STATISTICS_CACHE_KEY . '_' . user()->id, ['duration' => 600])) {
              echo $this->render('_teacher_home_statistics', ['userId' => user()->id]);
              $this->endCache();
          }
          ?>
      </div>
    </div>
    <div class="myOrganizes">
      <div class="title_pannel sUI_pannel">
        <div class="pannel_l"><h4><i></i><span>我的组织</span></h4></div>
<!--        <div class="pannel_r"><a href="#"></a></div>-->
      </div>
      <div class="organizes pd25">
        <div class="groups">
          <p>学校</p>
          <ul>
              <li>
                  <a href="<?= url(['school/index','schoolId'=>$schoolModel->schoolID]); ?>" title="<?php echo Html::encode($schoolModel->schoolName); ?>">
                      <img data-type='header' onerror="userDefImg(this);" src="<?php echo WebDataCache::getSchoolFaceIcon($schoolModel->schoolID)?>" width="50" height="50" alt="">
                  </a>
              </li>
          </ul>
        </div>
        <div class="groups <?php if (!isset($teaGroup[0])){ echo 'noBorder';}?>">
          <p>班级</p>
          <ul>
              <?php foreach ($classArr as $classVal) { ?>

                  <li>
                      <a href="<?= url(['class/index','classId' => $classVal->classID]); ?>" title="<?php echo Html::encode($classVal->className); ?>">
                          <img data-type='header' onerror="userDefImg(this);" src="<?php echo WebDataCache::getClassFaceIcon($classVal->classID)?>" width="50" height="50" alt="">
                      </a>
                  </li>

              <?php } ?>
          </ul>
        </div>
        <?php if (isset($teaGroup[0])){ ?>
            <div class="groups noBorder">
              <p>教研组</p>
              <ul>
                  <?php foreach ($teaGroup as $teaVal) { ?>

                      <li>
                          <a href="<?= url(['teachgroup/index','groupId' => $teaVal->groupID]); ?>" title="<?php echo Html::encode($teaVal->groupName); ?>">
                              <img data-type='header' onerror="userDefImg(this);" src="<?php echo WebDataCache::getTeaGroupFaceIcon($teaVal->groupID)?>" width="50" height="50" alt="">
                          </a>
                      </li>

                  <?php } ?>
              </ul>
            </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <div class="aside col260 omega no_bg">
    <div class="myMessages">
      <div class="title_pannel sUI_pannel">
        <div class="pannel_l"><h4><i></i><span>我的消息</span></h4></div>
        <div class="pannel_r"><a href="<?php echo url('teacher/message/notice')?>"></a></div>
      </div>
      <div class="messages pd25">
        <ul id="teachermsg">

        </ul>
      </div>
    </div>
    <div class="myScores">
      <div class="title_pannel sUI_pannel">
        <div class="pannel_l"><h4><i></i><span>我的积分</span></h4></div>
        <div class="pannel_r"><a href="<?php echo url('teacher/integral/income-details')?>"></a></div>
      </div>
      <div class="scores pd25">
        <div class="score">
          <div><span><?php echo $todayPoints;?></span>今日积分</div>
          <div><span><?php echo $totalPoints;?></span>累计积分</div>
          <div><span><?php echo $points;?></span>可用积分</div>
          <i class="scoreArrow"></i>
        </div>

        <?php if(empty($totalPoints)){?>
            <p>等级：<em><?php echo '翰林院编修';?></em></p>
            <div class="percent">
                <div class="percentRate" style="width:0%;">
                    <div class="percentNumWhite"><em><?php echo $totalPoints;?></em><em>/</em><em><?php echo 300;?></em></div>
                </div>
                <div class="percentNumBlue"><em><?php echo $totalPoints;?></em><em>/</em><em><?php echo 300;?></em></div>
            </div>
      <?php }else{?>
            <p>等级：<em><?php echo $gradePonits->gradeName;?></em></p>
            <div class="percent">
                <div class="percentRate" style="width:<?php echo ceil(($points/$gradePonits->endPoints)*100)?>%;">
                    <div class="percentNumWhite"><em><?php echo $totalPoints;?></em><em>/</em><em><?php echo $gradePonits->endPoints;?></em></div>
                </div>
                <div class="percentNumBlue"><em><?php echo $totalPoints;?></em><em>/</em><em><?php echo $gradePonits->endPoints;?></em></div>
            </div>
      <?php }?>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var userId = <?php echo user()->id;?>;
        $.get("<?=url('teacher/setting/get-messages')?>", {userId: userId}, function (result) {
            $('#teachermsg').html(result);
        })

    });
</script>