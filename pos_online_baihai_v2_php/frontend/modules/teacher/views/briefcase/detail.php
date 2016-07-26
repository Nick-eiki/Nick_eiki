<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-22
 * Time: 下午2:15
 */
use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */  $this->title='教师-备课--公文包--资料详情'
?>
<script type="text/javascript">
    $(function () {
        $('.sou_btn_js').bind('click', function () {
            $('.souPosition').show();
        });
        $('#downNum').click(function () {
            var id = '<?php echo $model->id;?>';
            $.post("<?php echo url('teacher/briefcase/get-down-num')?>", {id: id}, function (data) {
                if (data.success) {
                    $("#downNum i").html(data.data);
                } else {
                    popBox.alertBox(data.message);
                }
            })
        })

    })


</script>
<!--主体内容开始-->
<div class="currentRight grid_16 push_2">
    <div class="crumbs"><a href="#">教师</a> &gt;&gt; <a href="#">公文包</a> &gt;&gt; 资料详情</div>
    <?php if ($model->matType == 7) { ?>
        <div class="noticeH clearfix">
            <h3 class="h3L"><?php echo $model->name; ?></h3>
        </div>
        <hr>
        <div class="docPack_upload_data">
            <div class="teaching_plan">
                <dl class="introduce_list">
                    <dt><em>描述：</em></dt>
                    <dd><?php echo $model->matDescribe; ?></dd>
                </dl>
                <br>
                <div class="read_div">文件阅读器</div>
                <button type="button" class="bg_blue_l">下载教学计划</button>
            </div>
        </div>
    <?php } else { ?>
        <div class="noticeH clearfix">
            <h3 class="h3L">资料详情</h3>
        </div>
        <hr>
        <div class="docBag_upload_data">
            <div class="data_details details_d">
                <h4><?php echo $model->name; ?></h4>
                <ul class="data_keywords_list clearfix">
                    <li>
                        <p><?php echo $model->subjectname; ?></p>
                    </li>
                    <li>
                        <p><?php echo $model->gradename; ?></p>
                    </li>
                    <li>
                        <p><?php echo $model->versionname; ?></p>
                    </li>
                    <li class="data_source">
                        <p class="sou_btn sou_btn_js"><a style="color: #0000ff;"
                                                         href="<?php echo url('school/index', array('schoolId' => $model->school)); ?>"><?php echo $model->schoolName; ?></a>
                        </p>
                    </li>
                </ul>
                <ul class="data_introduce_list ">
                    <li><em>适用于:　</em><?php echo AreaHelper::getAreaName($model->provience); ?>
                        &nbsp;<?php echo AreaHelper::getAreaName($model->city); ?>
                        &nbsp;<?php echo AreaHelper::getAreaName($model->country); ?></li>
                    <li>
                        <?php
                        if ($model->contentType == 1) { ?>
                            <em>章节讲解：</em>
                            <?php
                            if (isset($model->chapKids)) {
                                foreach (ChapterInfoModel::findChapter($model->chapKids) as $key => $item) {
                                    echo $item->chaptername . "&nbsp;";
                                }
                            }
                        } else { ?>
                            <em>知识点讲解：</em>
                            <?php
                            if (isset($model->chapKids)) {
                                foreach (KnowledgePointModel::findKnowledge($model->chapKids) as $key => $item) {
                                    echo $item->name . "&nbsp;";
                                }
                            }
                        } ?>

                    </li>
                    <li>
                        <dl class="introduce_list">
                            <dt><em>教案介绍：</em></dt>
                            <dd><?php echo $model->matDescribe; ?></dd>
                        </dl>
                    </li>
                </ul>

                <button type="button" class="bg_blue_l" id="downNum">下载教案<span>(<i
                            class="downNum"><?php echo $model->downNum; ?></i>)</span></button>
            </div>
        </div>
    <?php } ?>
</div>

<!--主体内容结束-->