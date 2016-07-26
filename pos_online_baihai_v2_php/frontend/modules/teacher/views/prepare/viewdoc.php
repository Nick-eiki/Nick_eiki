<?php
/**
 *
 * @var PrepareController $this
 */
use frontend\components\helper\ImagePathHelper;

/* @var $this yii\web\View */
$this->title = '资料库-详情';

$arr = ImagePathHelper::resImage($result->url);
?>
<script>
    $(function () {
        var id = '<?php echo $id;?>';
        $.post("<?php echo url('teacher/prepare/get-read-num')?>", {id: id}, function (data) {
        });

        $('.correctPaper').testpaperSlider();
    });
</script>
<!--主体-->
<div class="cont24">
    <div class="grid24 main">
        <div class="grid_24 main_r">
            <div class="main_cont tea_prepare">
                <div class="title">
                    <h4>资料详情</h4>
                </div>

                <div class="teaching_plan">
                    <h5 style="padding-top: 30px;text-align: center"><?php echo $result->name; ?></h5>
                    <dl class="introduce_list font14">
                        <dt><em>描述：</em></dt>
                        <dd><?php echo $result->matDescribe; ?></dd>
                    </dl>
                    <br/><!--ImagePathHelper::resUrl($result->url);-->
                    <a href="<?php echo url('/ajax/download-file', array('id' => $id)); ?>"
                       class="btn white btn40 w140 bg_blue">下载</a>&nbsp;
                    <?php if (count($arr) == 0) { ?>
                        <a target="_blank"
                           href="http://officeweb365.com/o/?i=5362&furl=<?= urlencode(ImagePathHelper::resUrl1($result->url)); ?>"
                           class="btn white btn40 w140 bg_blue">预 览</a>
                    <?php } ?>
                    &nbsp;<a href="javascript:;" onclick="popBox.errorCorrect_resources(<?= $id ?>)"
                             class="btn white btn40 w140 bg_blue">我要纠错</a>&nbsp;
                    <br>
                    <br>
                    <?php ?>
                    <?php if (!empty($arr)): ?>
                        <div class=" font16 tr pageCount" style="padding-right:10px"></div>
                        <div class="correctPaper" style="border:1px solid #ddd; padding:30px">
                            <div class="testPaperWrap mc pr">
                                <ul class="testPaperSlideList slid">
                                    <?php foreach ($arr as $i): ?>
                                        <li><img src="<?= $i ?>"/></li>
                                    <?php endforeach ?>

                                </ul>
                                <a href="javascript:" id="prevBtn" class="correctPaperPrev">上一页</a> <a
                                    href="javascript:;" id="nextBtn" class="correctPaperNext">下一页</a>
                            </div>
                        </div>
                    <?php endif ?>
                </div>

            </div>
        </div>
    </div>
</div>
<!--主体end-->


