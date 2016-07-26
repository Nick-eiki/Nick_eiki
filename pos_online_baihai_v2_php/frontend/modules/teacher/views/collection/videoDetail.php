<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-11-11
 * Time: 下午3:38
 */
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */  $this->title='教师-收藏列表--视频详情';
?>
<script type="text/javascript">
    $(function () {
        $('.blue span').mouseover(function () {
            $('.souPosition_js').show();
        })

    })

</script>
<!--主体内容开始-->

<div class="currentRight grid_16 push_2 course_details">

    <div class="noticeH clearfix">
        <h3 class="h3L">视频名称</h3>
    </div>
    <hr>
    <div class="diary_text diary_text2">
        <h4><?php echo $model->videoName ?></h4>
        <ul class="course_list clearfix">
            <li><?php echo $model->subjectName; ?></li>
            <li><?php echo $model->gradeName; ?></li>
            <li><?php echo $model->versionName; ?></li>
            <li class="blue">
                <span><a href="<?php echo url('school/index',array('schoolId'=>$model->schoolID));?>"><?php echo $model->schoolName;?></a></span>
            </li>
        </ul>
        <h5>讲义介绍：</h5>

        <p style=""><?php echo $model->introduce; ?></p>
        <h6>课时安排：</h6>
        <?php foreach ($model->lessoninfo as $key => $val) {
            ?>
            <div class="video clearfix">
                <span>第<?php echo $val->cNum; ?>堂课</span>
                <ul class="clearfix video_li">
                    <li><img src="<?php echo publicResources() . $val->videoUrl; ?>/images/video.png" alt=""></li>
                </ul>
                <ul class="clearfix video_ul">
                    <li><?php echo $val->cName; ?></li>
                    <li><?php if (isset($val->kcid)) {
                            if ($val->type == 0) {
                                ?>
                                知识难点：
                                <?php
                                foreach (KnowledgePointModel::findKnowledge($val->kcid) as $key => $item) {
                                    echo $item->name . "&nbsp;";
                                }
                            } else {
                                ?> 章节难点：<?php
                                foreach (ChapterInfoModel::findChapter($val->kcid) as $key => $item) {
                                    echo $item->chaptername . "&nbsp;";
                                }
                            }
                        } ?>
                    </li>
                    <li><em><a href="#"
                               id="<?php echo $val->teachMaterialID; ?>">讲义名称：<?php echo $val->teachMaterialName; ?></a></em><i></i>
                    </li>
                </ul>
                <div class="video_mix">
                    <video  controls="controls">
                        <source src="<?php echo $val->videoUrl;?>" >
                    </video>
                </div>
            </div>
        <?php } ?>
    </div>

</div>

<!--主体内容结束-->
