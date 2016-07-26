<?php
/**
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2016/1/12
 * Time: 13:30
 */
use frontend\components\WebDataCache;
use yii\helpers\Url;

$this->title='批改作业';
$this->blocks['requireModule']='app/classes/tch_hmwk_paper';
?>
<div class="main col1200 clearfix tch_homework_paper"  id="requireModule" rel="app/classes/tch_hmwk_paper">
    <input type="hidden" id="homeworkAnswerID" value="<?=$homeworkAnswerID?>">
    <div class="container homework_title">
        <span class="return_btn"><a id="addmemor_btn" href="<?=url::to(['/class/work-detail','classId'=>$classId,'classhworkid'=>$oneAnswerResult->relId])?>"  class="btn">返回</a></span>
        <h4><?=$oneAnswerResult->homeWorkTeacher->name?></h4>
    </div>

    <div class="aside col230 alpha">
        <div id="userList" class="userList">
            <?php foreach($homeworkAnswerResult as $v){?>
            <dl class="<?=$v->homeworkAnswerID==$homeworkAnswerID?'cur':''?>">
                <a href="<?=url::to(['/class/correct-pic-hom','classId'=>$classId,'homeworkAnswerID'=>$v->homeworkAnswerID])?>">
                    <dt><img onerror="userDefImg(this);" width='50px' height='50px'
                             src="<?= WebDataCache::getFaceIcon($v->studentID) ?>"></dt>
                <dd>
                    <h5><?= WebDataCache::getTrueName($v->studentID) ?></h5>
                    <?php if($v->correctLevel==4){?>
                        <em class="check_btn"></em>
                    <?php }elseif($v->correctLevel==3){?>
                        <em class="half_btn"></em>
                    <?php }elseif($v->correctLevel==2){ ?>
                        <em class="wrong_btn"></em>
                    <?php }elseif($v->correctLevel==1){?>
                        <em class="bad_btn"></em>
                    <?php }else{?>
                        <em></em>
                    <?php }?>
                </dd>
                </a>
            </dl>
            <?php }?>
        </div>
    </div>
    <div class="container col940 omega">
        <div class="pd25">
            <div class="work_btnmark">
                <div id="slide" class="slide slide820">
                    <div class="slidePaperWrap mc">
                        <ul id="slidePaperList" class="slidePaperList ">
                            <?php foreach($imageArray as $v){?>
                            <li name="111"><img src="<?=publicResources().$v?>"  alt=""/><span></span></li>
                            <?php }?>
                        </ul>
                        <a href="javascript:;" id="prevBtn" class="slidePaperPrev">上一页</a> <a href="javascript:;" id="nextBtn" class="slidePaperNext">下一页</a> </div>
                    <div class="sliderBtnBar hide"></div>
                    <div class="slidClip">
                        <span class="ClipPrevBtn" onselectstart="return false">prev</span>
                        <div class="slidClipWrap">
                            <div class="slidClipArea">
                                <?php foreach($imageArray as $v){?>
                                    <a class="">
                                        <img src="<?=publicResources().$v?>">
                                    </a>
                                <?php  }?>
                            </div>

                        </div>
                        <span class="ClipNextBtn" onselectstart="return false">next</span>

                    </div>
                </div>
                <div class="cor_btn">
                    <a href="javascript:;" class="check"></a>
                    <a href="javascript:;" class="half"></a>
                    <a href="javascript:;" class="wrong"></a>
                    <a href="javascript:;" class="bad"></a>
                </div>
            </div>
        </div>
    </div>

</div>