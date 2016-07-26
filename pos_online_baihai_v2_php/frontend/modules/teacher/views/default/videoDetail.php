<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-12-1
 * Time: 下午5:51
 */

use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */  $this->title="教师-收藏夹";
?>

<!--主体内容开始-->

    <div class="centLeft">
        <div class="crumbs noticeB"> <a href="#">教师收藏</a> >> <a href="#">详细页</a></div>
        <hr>
        <div class="notice ">
            <div class="plan_l details">

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
                            <li><img src="<?php echo publicResources() . $val->videoUrl; ?>" alt=""></li>
                        </ul>
                        <ul class="clearfix video_ul">
                            <li><?php echo $val->cName; ?></li>
                            <li>
                                <?php if (isset($val->kcid)) {
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
                            </video></div>
                        </div>
                    </div>
                    <?php } ?>
                </div>


            </div>

        </div>
    </div>
    <div class="centRight">
        <div class="item Ta_teacher">
            <h4>Ta的老师</h4>
            <a class="more" href="#">更多</a>
            <ul class="teacherList">
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="../images/user_m.jpg">
                    张三丰
                </li>
            </ul>


        </div>
    </div>

<!--主体内容结束-->
