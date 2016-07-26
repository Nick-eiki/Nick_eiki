<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-25
 * Time: 下午4:48
 */
use frontend\components\helper\ImagePathHelper;

/* @var $this yii\web\View */  $this->title="试卷预览";
?>
<div class="grid_19 main_r">
    <div class="main_cont test_class_overall_appraisal">
        <div class="title"> <a href="javascript:;" class="txtBtn backBtn"></a>
            <h4>查看试卷</h4>
            <!-- <div class="title_r">
                 <button type="button" class="btn40 bg_blue">上传新试卷</button>
             </div>-->
        </div>
        <div class="correctPaper">
            <h5><?php echo $testResult->paperName ?></h5>

            <div class="correctPaperSlide">
                <div class="testPaperWrap mc pr">
                    <ul class="testPaperSlideList slid">
                        <?php foreach (ImagePathHelper::getPicUrlArray($testResult->imageUrls) as $k => $v){?>
                            <li><img src="<?=publicResources().$v?>" width="830"  alt=""/>

                            </li>
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
        image="<?=$testResult->imageUrls?>";
        imgArr=image.split(",");
//        imgArr=["../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg"]
        $('.correctPaperSlide').testpaperSlider({ClipArr:imgArr});
        $(".backBtn").click(function(){
            window.history.go(-1);
        })
    })
</script>