<?php
/**
 * Created by unizk
 * User: ysd
 * Date: 2015/3/19
 * Time: 14:24
 */
use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */  $this->title="试卷详情";
?>
<div class="main cont24  clearfix">


    <div class=" clearfix grid_24 bg_white" style="width:1080px;">
        <div class="currentRight">
            <div class="crumbs noticeB"> <a href="<?php echo url('teacher/managepaper');?>">试卷管理</a> >> <a href="#">试卷预览</a></div>
            <div class="noticeH clearfix">
                <h3 class="h3L">试卷预览</h3>
                <div class="fr">
                    <span style="font-size:12px;">组卷人:<?php echo loginUser()->getTrueName();?></span>&nbsp;&nbsp;&nbsp;<span style="font-size:12px;">时间:<?php echo $result->uploadTime;?></span>&nbsp;&nbsp;&nbsp;
                </div>
            </div>
            <hr>
            <div class="testPaperView pr">
                <h4><?php echo $result->name;?></h4>
                <div class="subTitle tc"><?php echo AreaHelper::getAreaName($result->provience);?>&nbsp;&nbsp;<?php echo AreaHelper::getAreaName($result->city);?>&nbsp;&nbsp;<?php echo $result->gradename;?>&nbsp;&nbsp;<?php echo $result->subjectname;?>&nbsp;&nbsp;<?php echo $result->versionname;?></div>
                <p>1.考察知识点:<?php
                    if(isset($result->knowledgeId)){
                        echo KnowledgePointModel::findKnowledgeStr($result->knowledgeId);
                    } ?></p>
                <p>2.本试卷包含<?php echo $result->questCnt;?>道题</p>
                <div class="paperArea">
                    <?php foreach($result->pageMain->win_paper_typeone->questionTypes as $val){
                            if(isset($val->questions) && !empty($val->questions)){
                                foreach($val->questions as $item){
                    ?>

                    <?php echo $this->render('//publicView/paper/_recombinationItemPreview', array('item' => $item)); ?>

                    <?php }}}?>
                    <?php foreach($result->pageMain->win_paper_typetwo->questionTypes as $val){
                        if(isset($val->questions) && !empty($val->questions)){
                            foreach($val->questions as $item){
                                ?>

                                <?php echo $this->render('//publicView/paper/_recombinationItemPreview', array('item' => $item)); ?>

                            <?php }}}?>
                    <div class="testPaper_view_btn">
                        <a href="<?php echo url('teacher/managepaper');?>" class="min_btn btn vied_l bg_blue">取消预览</a>
                    </div>


                </div>


            </div>


        </div>
    </div>


</div>
<!--主体内容结束-->

<script type="text/javascript">
    //查看答案与解析
    $('.openAnswerBtn').click(function(){
        $(this).children('i').toggleClass('close');
        $(this).parents('.paper').find('.answerArea').toggle();
    })
</script>
