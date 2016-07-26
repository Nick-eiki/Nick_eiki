<?php
/**
 * Created by unizk.
 * User: ysd
 * Date: 2015/3/19
 * Time: 14:24
 */
use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */  $this->title="试卷详情";
?>

<script type="text/javascript">
    $(function() {
        imgArr=["<?php foreach($result->imageUrls as $val){echo $val->url;}?>"];
        $('.correctPaperSlide').testpaperSlider({ClipArr:imgArr});
    });

</script>

<!--主体内容开始-->

<div class="main cont24 clearfix">
    <div class=" clearfix grid_24 bg_white" style="width:1080px;">
        <div class="currentRight">
            <div class="noticeH clearfix noticeB uploadedPaper_title">
                <h3 class="h3L"><?php echo $result->name;?></h3>
            </div>
            <hr>
            <div class="correctPaper">
                <ul class="up_details_list">
                    <li class="clearfix">
                        <p>地区：<span><?php echo AreaHelper::getAreaName($result->provience);?>&nbsp;&nbsp;<?php echo AreaHelper::getAreaName($result->city);?></span></p>
                        <p>年级：<span><?php echo $result->gradename;?></span></p>
                        <p>科目：<span><?php echo $result->subjectname;?></span></p>
                        <p>版本：<span><?php echo $result->versionname;?></span></p>
                    </li>
                    <li class="clearfix">
                        <p>知识点：<span><?php
                                if(isset($result->knowledgeId)){
                                    echo KnowledgePointModel::findKnowledgeStr($result->knowledgeId);
                                } ?></span></p>
                    </li>
                    <li class="clearfix">
                        <p>作业简介：<span><?php echo $result->paperDescribe;?></span></p>
                    </li>
                </ul>
                <div class="slidClip"></div>
                <div class="correctPaperSlide" style="position:relative;">
                    <div class="testPaperWrap mc">
                        <ul class="testPaperSlideList slid">
                            <?php foreach($result->imageUrls as $val){?>
                            <li><img src="<?php echo $val->url;?>" width="830" height="508"  alt=""/></li>
                            <?php }?>
                        </ul>
                        <a href="javascript:;" id="prevBtn" class="correctPaperPrev">上一页</a> <a href="javascript:;" id="nextBtn" class="correctPaperNext">下一页</a> </div>
                    <div class="sliderBtnBar"></div>
                </div>
            </div>
        </div>
    </div>


</div>

<!--主体内容结束-->