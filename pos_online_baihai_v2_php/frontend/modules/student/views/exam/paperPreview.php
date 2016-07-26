<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-25
 * Time: 下午4:48
 */
/* @var $this yii\web\View */  /* @var $this yii\web\View */  $this->title="试卷预览";
?>
<div class="grid_19 main_r">
    <div class="main_cont test_class_overall_appraisal">
        <div class="title"> <a class="txtBtn backBtn"></a>
            <h4><?=$result->name?></h4>
            <div class="title_r">
                <button type="button" class="btn40 bg_blue">上传新试卷</button>
            </div>
        </div>
        <div class="correctPaper">
            <h5><?=$result->name?></h5>
            <ul class="up_details_list">
                <li class="clearfix">
                    <p>地区：<span><?=$result->provience."&nbsp".$result->city."&nbsp".$result->country?></span></p>
                    <p>年级：<span><?=$result->gradename?></span></p>
                    <p>科目：<span><?=$result->subjectname?></span></p>
                    <p>版本：<span><?=$result->versionname?></span></p>
                </li>
                <li class="clearfix">
                    <p>知识点：<span></span></p>
                </li>
                <li class="clearfix">
                    <p>作业简介：<span><?=$result->paperDescribe?></span></p>
                </li>
            </ul>
            <div class="slidClip"></div>
            <div class="correctPaperSlide">
                <div class="testPaperWrap mc pr">

                    <ul class="testPaperSlideList slid">
                        <?php foreach(explode(",",$result->imageUrls) as $v){?>
                        <li><img src="<?php echo publicResources().$v?>" width="830" height="508"  alt=""/></li>
                    <?php }?>
                    </ul>
                    <a href="javascript:;" id="prevBtn" class="correctPaperPrev">上一页</a> <a href="javascript:;" id="nextBtn" class="correctPaperNext">下一页</a> </div>
                <div class="sliderBtnBar"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        img="<?php echo $result->imageUrls?>";
//        imgArr=JSON.stringify(img.split(","));
        imgArr=img.split(",");
        $('.correctPaperSlide').testpaperSlider({ClipArr:imgArr});
        $(".backBtn").click(function(){
            window.history.go(-1);
        })
    })
</script>