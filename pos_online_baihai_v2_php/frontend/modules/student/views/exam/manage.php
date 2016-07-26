<?php
/**
 *
 * @var ExamController $this
 * @var PsiWhiteSpace $"examList"
 * @var \Pagination $"pages"
 */
use yii\helpers\Url;

/* @var $this yii\web\View */  /* @var $this yii\web\View */  $this->title="考务管理";
?>
<div class="grid_19 main_r">
    <div class="main_cont test">
        <div class="title">
            <h4>考务管理</h4>

            <div class="title_r">

            </div>
        </div>

        <div class="testArea">
            <div class="form_list">
                <div class="row">
                    <div class="formL">
                        <label>考试类型:</label>
                    </div>
                    <div class="formR" style="width: 780px">
                        <ul class="resultList noBorder " id="examType">
                            <li class="ac" datatype=""><a>全部</a></li>
                            <?php foreach ($examTypeArr as $key => $val): ?>
                                <li datatype="<?= $key ?>"><a><?= $val ?></a></li>
                            <?php endforeach ?>

                        </ul>
                    </div>
                </div>
            </div>
<hr>
            <br>

            <div id="upview">
                <?php echo $this->render('_exam_list', ['examList' => $examList, 'pages' => $pages]); ?>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#examType li').click(
        function () {
            type = $(this).attr('datatype');
            $.get("<?=Url::to()?>", {
                    type: type
                },
                function (data) {
                    $('#upview').html(data);
                }
            )

        });

</script>