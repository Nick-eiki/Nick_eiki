<?php
/**
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2015/11/25
 * Time: 14:41
 */
use frontend\components\WebDataCache;
use yii\helpers\Url;

$this->title='批改作业';
?>
<!--主体-->
<div class="cont24">
<div class="grid24 main">
<div class="grid_24 main_r">
<div class="main_cont">
<div class="title">
    <div class="title">
        <a class="txtBtn backBtn" href="<?=url::to(['/teacher/managetask/work-details','classhworkid'=>$oneAnswerResult->relId])?>"></a>
    <h4>作业批改</h4>
        </div>
</div>
<div class="correctcon_work">
<div class="tab">
<div class="correctcon">
<!--按题批改-->
<div class="tabItem correctcon questionscon">
<div id="userList" class="left_correct">
    <?php foreach($homeworkAnswerResult as $v){?>
    <dl class="<?=$v->homeworkAnswerID==$homeworkAnswerID?'cur':''?>">
        <a href="<?=url::to(['/teacher/managetask/new-pic-correct','homeworkAnswerID'=>$v->homeworkAnswerID])?>">
        <dt><img onerror="userDefImg(this);" width='50px' height='50px'
                 src="<?= WebDataCache::getFaceIcon($v->studentID) ?>"></dt>
        <dd>
            <p><?= WebDataCache::getTrueName($v->studentID) ?></p>
            <?php if($v->correctLevel==4){?>
            <span class="you1">优</span>
            <?php }elseif($v->correctLevel==3){?>
                <span class="liang1">良</span>
            <?php }elseif($v->correctLevel==2){ ?>
                <span class="zhong1">中</span>
            <?php }elseif($v->correctLevel==1){?>
                <span class="cha1">差</span>
            <?php }else{?>
                <span></span>
            <?php }?>
        </dd>
        </a>
    </dl>
    <?php }?>

</div>
<div class="right_correct">
    <div class="work_btnmark">
        <div class="correctPaper">
            <div id="correctPaperSlide2" class="correctPaperSlide">
                <div class="testPaperWrap mc pr">
                    <ul class="testPaperSlideList slid">
                        <?php foreach($imageArray as $v){?>
                        <li><img src="<?=publicResources().$v?>"  alt=""/></li>
                        <?php }?>
                    </ul>
                    <a href="javascript:;" id="prevBtn" class="correctPaperPrev">上一页</a>
                    <a href="javascript:;" id="nextBtn" class="correctPaperNext">下一页</a>
                </div>
            </div>
        </div>
        <div id="slidClip2" class="slidClip"></div>
        <div class="original">
            <div class="exhibition hide">
                <i class="v_r_arrow"></i>
                <h4>题目：【2014年  高考  选择题】</h4>
                <h4>答案与解析</h4>
            </div>
        </div>
        <div class="cor_btn">
            <a href="#" class="you1"></a>
            <a href="#" class="liang1"></a>
            <a href="#" class="zhong1"></a>
            <a href="#" class="cha1"></a>
        </div>
    </div>
</div>
</div>
</div>
</div>

</div>
</div>
</div>
</div>
</div>
<!--主体end-->
<script>
    $(function(){
        //判卷
        imgArr=["../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg"];
         imgArr=<?=json_encode($imageArray)?>;
        $('#correctPaperSlide2').testpaperSlider({ClipArr:imgArr,slidClip:"#slidClip2"});

        $('#userList dl').click(function(){
            $(this).addClass('cur').siblings().removeClass();
        });

        //原题弹窗
        $(".original").click(function(){
            $(this).toggleClass("show_original");
            $(".exhibition").toggle();
        });
        //判卷
        $('.cor_btn a').click(function(){
            var cls_name=$(this).attr('class');
            function chk(cls_name,txt){
                $('#userList .cur span').removeClass().addClass(cls_name).text(txt);
            }
            homeworkAnswerID=<?=$homeworkAnswerID?>;
            switch(cls_name){
                case "you1":
                    correctLevel=4;
                    chk("you1","优");
                    break;
                case "liang1":
                    correctLevel=3;
                    chk("liang1","良");
                    break;
                case "zhong1":
                    correctLevel=2;
                    chk("zhong1","中");
                    break;
                case "cha1":
                    correctLevel=1;
                    chk("cha1","差");
                    break;
            }
            $.post("<?=url::to('/teacher/managetask/ajax-pic-correct')?>",{correctLevel:correctLevel,homeworkAnswerID:homeworkAnswerID})
        })

    })
</script>