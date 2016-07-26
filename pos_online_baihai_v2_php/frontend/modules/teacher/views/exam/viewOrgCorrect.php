<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-26
 * Time: 下午2:21
 */
/* @var $this yii\web\View */  $this->title="查看批改";
?>
<div class="grid_19 main_r">
    <div class="main_cont test_class_overall_appraisal">
        <div class="title"> <a  class="txtBtn backBtn"></a>
            <h4><?php echo $testResult->studentName."的".$testResult->name?></h4>
            <!-- <div class="title_r">
                 <button type="button" class="btn40 bg_blue">上传新试卷</button>
             </div>-->
        </div>
        <div class="correctPaper">
            <h5><?php echo $testResult->studentName."的".$testResult->name?></h5>
            <!-- <ul class="up_details_list">
                 <li class="clearfix">
                     <p>地区：<span>北京&nbsp;&nbsp;海淀区</span></p>
                     <p>年级：<span>一年级</span></p>
                     <p>科目：<span>数学</span></p>
                     <p>版本：<span>人教版</span></p>
                 </li>
                 <li class="clearfix">
                     <p>知识点：<span>追击问题，相遇问题</span></p>
                 </li>
                 <li class="clearfix">
                     <p>作业简介：<span>试卷简介试卷简介试卷简介试卷简介试卷简介试卷简介试卷简介试卷简介试卷简介试卷简介试卷简介试卷简介试卷简介试卷
                         简介试卷简介试卷简介试卷简介试卷简介</span></p>
                 </li>
             </ul>
             <div class="slidClip"></div>-->
            <div class="correctPaperSlide">
                <div class="testPaperWrap mc pr">
                    <ul class="testPaperSlideList slid">
                        <?php foreach($testResult->picList as $v){?>
                            <li><img src="<?=publicResources().$v->picUrl?>" width="830" alt=""/>
                                <?php $checkInfoList = json_decode($v->checkJson);
                                if (!empty($checkInfoList->checkInfoList)) {
                                    foreach ($checkInfoList->checkInfoList as $value) { ?>

                                        <div class="tips" style="<?php echo $value->style ?>">
                                            <span class="scoreTxt"><?php echo $value->comments ?></span>
                                            <br>
                                            <?php if(!empty($value->scoreVal)){?>
                                                <strong class="scoreVal"><?php echo $value->scoreVal ?></strong>
                                                <?php if (!empty($value->scoreVal)) {
                                                    echo "分";
                                                } }?>
                                        </div>
                                    <?php }
                                } ?>
                            </li>
                        <?php }?>
                    </ul>
                    <a href="javascript:;" id="prevBtn" class="correctPaperPrev">上一页</a> <a href="javascript:;" id="nextBtn" class="correctPaperNext">下一页</a> </div>
                <div class="sliderBtnBar"></div>
            </div>
        </div>
    </div>
    <?php $imageArr=array();
    foreach($testResult->picList as $v){
        array_push($imageArr,$v->picUrl);
    }
    $image=implode(",",$imageArr);
    ?>
</div>
<script type="text/javascript">
    $(function() {
        image="<?=$image?>";
        imgArr=image.split(",");
//        imgArr=["../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg"]
        $('.correctPaperSlide').testpaperSlider({ClipArr:imgArr});
        $(".backBtn").click(function(){
            window.history.go(-1);
        })
    })
</script>