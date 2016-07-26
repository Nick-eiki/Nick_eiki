<?php
/* @var $this yii\web\View */  $this->title="教师--日记详情";
?>
<div class="currentRight grid_16 push_2">
    <div class="crumbs noticeB"><a href="#">日记本</a> >> <a href="#">日记详情</a></div>

    <div class="cent_c">
        <a href="<?php echo url('teacher/diary/diary-update',array('id'=>$data->diaryID))?>" class="revise btn bg_blue" style="top: 20px;right: 30px;">去修改</a>
        <h3><?php echo $data->headline ?></h3>
        <?php if ($data->diaryType == 1): ?>
            <dd class="title_s"><span>关于</span>&nbsp;<a
                    href="<?php echo url('teacher/lesson/listen-lessons') ?>"><?php echo $data->teacherName ?>
                    的课程  <?php echo $data->chapterName ?>  的听课报告</a></dd>
        <?php elseif ($data->diaryType == 2): ?>
            <p class="title_s"><i>课题</i><a
                    href="<?php echo url('teacher/researchWork/details', array('id' => $data->courseID)) ?>"><?php echo $data->courseName ?></a>
            </p>
        <?php endif; ?>

        <div class="cent_text">
            <p style="">
                <?php echo $data->diaryInfo ?>
            </p>

            <div class="details_page clearfix">
                <?php if (!empty($previous->diaryID)) { ?>
                    <span class="top">上一篇:
                           <a href="<?php echo url('teacher/diary/diary-view', array('id' => $previous->diaryID,'type'=>$previous->diaryType)) ?>"><?php echo $previous->headline ?></a>
                </span>
                <?php
                } else {
                    ?>
                    <span class="top">上一篇:
                     <a>没有了</a>
                      </span>
                <?php } ?>
                <?php if (!empty($next)) { ?>
                    <span class="bottom">下一篇:<a
                            href="<?php echo url('teacher/diary/diary-view', array('id' => $next->diaryID,'type'=>$next->diaryType)) ?>"><?php echo $next->headline ?></a></span>
                <?php } else { ?>
                    <span class="top">下一篇:
                     <a>没有了</a>
                      </span>
                <?php } ?>


            </div>
        </div>
    </div>

</div>