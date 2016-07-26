<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 2015/5/22
 * Time: 9:37
 */
?>
<?php foreach ($answerList->answerlist as $v) { ?>
    <div class="row">
        <div class="formL">
            <label title="<?= $v->studentName ?>"><?= $v->studentName ?><br><?= $v->studentID ?></label>
        </div>
        <div class="formR testpaperList">
            <?php if ($v->getType == 0) { ?>
                <?php foreach ($v->testCheckInfoS as $key => $value) {
                    if ($key < 3) {
                        ?>
                        <a style="cursor:default"
                           href="<?= url('teacher/exam/view-correct', array('testAnswerID' => $v->testAnswerID)) ?>"><img
                                src="<?= publicResources() . $value->imageUrl ?>" width="120"
                                height="90"></a>
                    <?php
                    }
                } ?>
                <span class="score"><?= $v->stuSubScore ?></span><span class="inputbar hide"><input
                        type="text" class="text"><button
                        type="button" class="bg_blue scoreOkBtn" studentID="<?= $v->studentID ?>">确定
                    </button></span> <i class="editBtn"></i>
                <?php if($v->isUploadAnswer==1){ if ($v->isCheck == 0 || $v->isCheck == 2) {
                    if ($isTheTeacher||$whetherCorrect) {
                        ?>
                        <a href="<?= url('teacher/exam/correct-paper', array('testAnswerID' => $v->testAnswerID)) ?>"
                           class="btn bg_blue w100">批改试卷</a>
                    <?php } else { ?>
                        <button class="disableBtn a_button">批改试卷</button>
                    <?php
                    } ?>
                <?php } else { ?>
                    <a href="<?= url('teacher/exam/view-correct', array('testAnswerID' => $v->testAnswerID)) ?>"
                       class="btn bg_blue w100">查看批改</a>
                <?php } } ?>
            <?php } else { ?>
                <?php if (!empty($v->objQuestionAnswerList)) { ?>
                    <h6>客观题答案</h6>
                    <p>
                        <?php foreach ($v->objQuestionAnswerList as $key => $value) { ?>
                            <span
                                class="<?= $value->answerRight ? 'Q_correct' : 'Q_error' ?>"><?= $key + 1 ?>
                                . <?= LetterHelper::getLetter($value->userAnswerOption) ?></span>
                        <?php } ?>

                    </p>
                <?php } ?>
                <?php if(!empty($v->resQueAllPicList)){?>
                    <h6>主观题答案</h6>
                    <?php foreach ($v->resQueAllPicList as $key => $value) {
                        if ($key < 3) {
                            ?>
                            <a style="cursor:default"
                               href="<?= url('teacher/exam/view-correct', array('testAnswerID' => $v->testAnswerID)) ?>"><img
                                    src="<?= publicResources() . $value->picUrl ?>" width="120"
                                    height="90"></a>
                        <?php
                        }
                    }  }?>
                <span class="score"><?= $v->stuSubScore ?></span><span class="inputbar hide"><input
                        type="text" class="text"><button
                        type="button" class="bg_blue scoreOkBtn" studentID="<?= $v->studentID ?>">确定
                    </button></span> <i class="editBtn"></i>
                <?php if ($v->picNum > 0) {
                    if ($v->isCheck == 0) {
                        if ($isTheTeacher) {
                            ?>
                            <a href="<?= url('teacher/exam/correct-paper', array('testAnswerID' => $v->testAnswerID)) ?>"
                               class="btn bg_blue w100">批改试卷</a>
                        <?php } else { ?>
                            <button class="disableBtn a_button">批改试卷</button>
                        <?php
                        }
                        ?>
                    <?php } else { ?>
                        <a href="<?= url('teacher/exam/view-correct', array('testAnswerID' => $v->testAnswerID)) ?>"
                           class="btn bg_blue w100">查看批改</a>
                    <?php
                    }
                } else {
                    if ($v->isCheck == 0) {
                        ?>
                        <a class="btn bg_blue w100 changeState"
                           testAnswerID="<?= $v->testAnswerID ?>">批改</a>
                    <?php
                    }
                }
            } ?>
        </div>
    </div>
<?php } ?>
<?php
echo \frontend\components\CLinkPagerExt::widget( array(
        'updateId' => '.studentList',
        'pagination' => $pages,
        'maxButtonCount' => 5
    )
);
?>
<script>
    ajaxreload = function () {
        $.ajax({
            'url': '<?php echo  app()->request->getUrl() ?>', 'cache': false, 'success': function (html) {
                $('.studentList').html(html)
            }
        });
    };
    //修改成绩
    $('.testpaperList .editBtn').click(function () {
        var pa = $(this).parent('.testpaperList');
        var _this = $(this);
        $(this).hide();
        pa.children('.score').hide();
        pa.find('.text').val(pa.children('.score').text());
        pa.children('.inputbar').show();
        pa.find('.text').placeholder({value: "分数"});

        function modify() {
            var subScore = "<?=$subScore?>";
            var score = pa.find('.text').val();

            var studentID = pa.find(".scoreOkBtn").attr("studentID");

            var examSubID = "<?=app()->request->getParam('examSubID')?>";
            if (isNaN(score)) {
                popBox.errorBox("请输入数字");
            }
            else if (score >= 0 && score <= parseInt(subScore)) {
                $.post("<?=url('teacher/exam/log-stu-score')?>", {
                    score: score,
                    studentID: studentID,
                    examSubID: examSubID
                }, function (result) {
                    if (result.success) {
                        ajaxreload();
                    } else {
                        popBox.errorBox(result.message);
                        return false;
                    }
                });
                _this.show();
                pa.children('.sco   re').show().text(pa.find('.text').val());
                pa.children('.inputbar').hide();
            }
            else if (score > parseInt(subScore)) {
                popBox.errorBox('分数不能超过当前科目最高分');
            } else if (score < 0) {
                popBox.errorBox("分数不能小于0");
            }
        }
        $(document).keyup(function (event) {
            if (event.keyCode == 13)modify()//确定
        });;

        $(document).keyup(function (event) {
            if (event.keyCode == 27) {//esc
                _this.show();
                pa.children('.score').show();
                pa.children('.inputbar').hide();
                pa.children('.text').hide().val("");
            }
        });
        $('.testpaperList .scoreOkBtn').unbind("click").click(function () {
            modify();
        });
    });
</script>