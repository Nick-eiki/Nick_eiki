<?php
/**
 * Created by PhpStorm.
 * User: lsl
 * Date: 2015/7/7
 * Time: 12:00
 */
use common\helper\DateTimeHelper;
use frontend\components\CHtmlExt;
use frontend\components\helper\ImagePathHelper;
use frontend\components\helper\ViewHelper;
use frontend\components\WebDataCache;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */  $this->title='教研组—首页';
?>
<script>
    $(function(){

        //教研资料
        $('.file_list li').hover(
            function(){
                $(this).children('.mask_link').show();
            },
            function(){
                $(this).children('.mask_link').hide();
            }

        );

        //教研课题
        $('.members').each(function(index, element) {
            var _this=$(this);
            var img_size=_this.children('img').size();
            if(img_size>10)	_this.children('.moreUserBtn').show();
            _this.children('img:nth-child(n+12)').addClass('hide');
        });
        $('.moreUserBtn').toggle(
            function(){
                $(this).text('收起').siblings('.hide').show();
            },
            function(){
                $(this).text('更多').siblings('.hide').hide();
            }
        )
    })


</script>

<div class="main_cont">
    <div class="title">
        <h4>教研课题</h4>
        <div class="title_r"> <span><a href="<?= Url::to(['teachgroup/topic', 'groupId' => $groupId]) ?>" class="gray_d underline">更多</a></span> </div>
    </div>
    <ul class="res_topicsList topic_list">
        <?php
        if(empty($course)){
            ViewHelper::emptyView();
        }
        foreach ($course as $val) {
            ?>
            <li>
                <div class="title noBorder">
                    <h4><?= WebDataCache::getGradeName($val->gradeID) ?>：</h4>
                    <h4>
                        <a href="<?= Url::to(['teachgroup/topic-details', 'groupId' => $groupId, 'courseId' => $val->courseID]) ?>"><?= CHtmlExt::encode($val->courseName) ?></a>
                    </h4>

                    <div class="title_r"><span><?= date("Y-m-d H:i", DateTimeHelper::timestampDiv1000($val->createTime)) ?></span>
                    </div>
                </div>
                <p><?= CHtmlExt::encode($val->brief); ?></p>

                <p class="members">
                    <em class="gray_d">课题成员：</em>
                    <?php foreach ($val->groupCourseMember as $v) { ?>
                        <img width="30px" height="30px" data-type="header" onerror="userDefImg(this);"
                             src="<?= WebDataCache::getFaceIcon($v->teacherID) ?>" title="<?= WebDataCache::getTrueName($v->teacherID)?>"/>
                    <?php } ?>
                    <button class="moreUserBtn hide">更多</button>
                </p>
            </li>
        <?php } ?>
    </ul>


    <div class="title" style="margin-top: 30px;">
        <h4>教研资料</h4>
        <div class="title_r">
            <span><a href="<?php echo url('teachgroup/teach-data',array('groupId'=>$groupId))?>" class="gray_d underline">更多</a></span>
        </div>
    </div>
    <ul class="file_list clearfix">
        <?php if($model):?>
            <?php foreach($model as $val){?>
                <li> <img src="<?php echo ImagePathHelper::getFilePic($val->url);?>" width="57" height="57" alt=""/>
                    <h6><a href="javascript:;" title="<?php if(!empty($val->name)){echo Html::encode($val->name);}?>"><?php  if(!empty($val->name)){echo cut_str($val->name,18);}?></a></h6>
                    <p><?php if(!empty($val->creator)){ echo cut_str(WebDataCache::getTrueName($val->creator),8);}?>
                        <span>
                <?php if(!empty($val->createTime)){echo date('Y-m-d H:i',strtotime($val->createTime));}?>
            </span>
                    </p>
                    <div class="mask_link hide">
                        <div class="mask_link_BG"></div>
                        <div class="mask_link_cont"><a class="read" href="<?php if(!empty($val->id)){echo url('teachgroup/teach-data-details',array('groupId'=>$groupId,'id'=>$val->id,'type'=>$val->matType));}?>"><i></i>阅读</a><em><?php if(!empty($val->readNum) && $val->readNum > 0){echo $val->readNum;}else{echo '0';}?>人已阅读</em></div>
                    </div>
                </li>
            <?php }
        else:
            ViewHelper::emptyView();
        endif;
        ?>
    </ul>


</div>

