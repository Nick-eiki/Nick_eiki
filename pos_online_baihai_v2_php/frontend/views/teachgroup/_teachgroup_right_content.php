<?php
use frontend\components\WebDataCache;
use yii\helpers\Url;

?>
<div class="main_cont">
    <div class="mainContBorder">
        <div class="item">
            <h4>听课安排</h4>
            <a class="more" href="<?=Url::to(['/teachgroup/listen-lessons','groupId'=>$groupId])?>">更多</a>
            <div class="calendar">
                <div class="controlBar">
                    <div class="widget_select control_year">
                        <h6><span>2015</span><i></i></h6>
                        <ul>
                            <li><a href="javascript:;">2014</a>
                            </li>
                            <li><a href="javascript:;">2015</a>
                            </li>
                            <li><a href="javascript:;">2016</a>
                            </li>
                            <li><a href="javascript:;">2017</a>
                            </li>
                        </ul>
                    </div>

                    <div class="widget_select control_month">
                        <h6><span>01</span><i></i></h6>
                        <ul>
                            <li><a href="javascript:;">01</a>
                            </li>
                            <li><a href="javascript:;">02</a>
                            </li>
                            <li><a href="javascript:;">03</a>
                            </li>
                            <li><a href="javascript:;">04</a>
                            </li>
                            <li><a href="javascript:;">05</a>
                            </li>
                            <li><a href="javascript:;">06</a>
                            </li>
                            <li><a href="javascript:;">07</a>
                            </li>
                            <li><a href="javascript:;">08</a>
                            </li>
                            <li><a href="javascript:;">09</a>
                            </li>
                            <li><a href="javascript:;">10</a>
                            </li>
                            <li><a href="javascript:;">11</a>
                            </li>
                            <li><a href="javascript:;">12</a>
                            </li>
                        </ul>
                    </div>
                    <div class="widget_select control_classes">
                        <h6><span>听课安排</span><i></i></h6>
                        <ul>
                            <li><a href="javascript:;">听课安排</a>
                            </li>
                            <li><a href="javascript:;">我参与的</a>
                            </li>
                            <li><a href="javascript:;">我主讲的</a>
                            </li>

                        </ul>
                    </div>
                    <button type="button" class="transparentBtn noBorder backTodayBtn">返回今天</button>

                </div>
                <ul class="weekList clearfix">
                    <li>一</li>
                    <li>二</li>
                    <li>三</li>
                    <li>四</li>
                    <li>五</li>
                    <li>六</li>
                    <li>日</li>
                </ul>
                <ul class="dateList clearfix">

                </ul>
            </div>
        </div>
        <div class="item group_member">
            <h4>教研组成员</h4>
            <ul class="member_list clearfix">
                <?php

                foreach($model as $key=>$val){
                    $userName = WebDataCache::getTrueName($val->teacherID);
                    if($key < 10){
                    ?>
                <li> <a href="<?= url('teacher/default/index',  ['teacherId' => $val->teacherID]) ?>" title="<?php echo $userName?>">
                        <img width="50px" height="50px" data-type="header" onerror="userDefImg(this);"  src="<?= WebDataCache::getFaceIcon($val->teacherID); ?>">
                        <?php echo $userName?>
                    </a>
                </li>
                <?php }}?>
            </ul>
            <?php if($count>10){ ?>
            <a href="<?=url('teachgroup/teach-group-member',array('groupId'=>$groupId)); ?>" class="blue underline">查看全部 <?=$count; ?> 位成员 ></a>
            <?php } ?>
        </div>
    </div>
</div>
<script>
    var classes_arr=<?=$lessonJson?>;



</script>