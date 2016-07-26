<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 14-9-19
 * Time: 下午4:06
 */
use yii\helpers\Url;

?>
<?php if(!empty($data)):?>
    <?php foreach ($data as $v): ?>
<div class="class_B" style="width:685px;">

        <?php if ($v->diaryType == 1): //评课?>
            <dl class="class_time">
                <dt>
                    <span><?php echo date('Y年m月', strtotime($v->createTime)) ?><i></i></span>
                <h4>
                    <a href="<?php echo url('teacher/diary/diary-view', array('id' => $v->diaryID,'type'=>$v->diaryType)) ?>"><em>[评课]&nbsp;<?php echo $v->headline ?></em></a>
                    <a href="<?php echo url('teacher/diary/diary-update', array('id' => $v->diaryID)) ?>"
                       class="pic_link"></a><em
                        class="time"><?php echo date('Y-m-d', strtotime($v->createTime)) ?></em>
                </h4>
                </dt>

                <dd><span>关于</span>&nbsp;<a
                        ><?php echo $v->teacherName ?>
                        的课程  <?php echo $v->chapterName ?>  的听课报告</a></dd>
            </dl>
        <?php elseif ($v->diaryType == 2): //课题?>
            <dl class="class_time">
                <dt>
                    <span><?php echo date('Y年m月', strtotime($v->createTime)) ?><i></i></span>
                <h4>
                    <a href="<?php echo url('teacher/diary/diary-view', array('id' => $v->diaryID,'type'=>$v->diaryType)) ?>"><em>[课题]&nbsp;<?php echo $v->headline ?></em></a>
                    <a href="<?php echo url('teacher/diary/diary-update', array('id' => $v->diaryID)) ?>"
                       class="pic_link"></a><em
                        class="time"><?php echo date('Y-m-d', strtotime($v->createTime)) ?></em>
                </h4>
                </dt>

                <dd><span>课题</span>&nbsp;<a
                        ><?php echo $v->courseName ?></a>
                </dd>

            </dl>
        <?php
        else: //随笔
            ?>
            <dl class="class_time">
                <dt>
                    <span><?php echo date('Y年m月', strtotime($v->createTime)) ?><i></i></span>
                <h4>
                    <a href="<?php echo url('teacher/diary/diary-view', array('id' => $v->diaryID,'type'=>$v->diaryType)) ?>"><em>[随笔]&nbsp;<?php echo $v->headline ?></em></a>
                    <a href="<?php echo url('teacher/diary/diary-update', array('id' => $v->diaryID)) ?>"
                       class="pic_link"></a><em
                        class="time"><?php echo date('Y-m-d', strtotime($v->createTime)) ?></em>
                </h4>
                </dt>
            </dl>
        <?php endif; ?>

</div>
    <?php endforeach; ?>
<?php else:?>
    没有数据
<?php endif;?>
<?php if ($pages->getPageCount() > $pages->getPage() + 1): ?>
    <div class="addMore morejs">
        <span>更多&gt;&gt;</span>
    </div>
    <script>
        $(function () {
            $('.morejs').bind('click', function () {
                var url = '<?php   /** @var $this Controller */
echo Url::to();  ?>';
                var type = $('#diary_type').val();
                $.get(url, {page: <?php echo $pages->getPage() + 2?>, type: type}, function (result) {
                    $('.morejs').replaceWith(result);
                })
            })
        })
    </script>

<?php endif; ?>

<script type="text/javascript">
    $(function(){
    var obj = $(".currentRight").find(".class_B");
    var array = [];
    obj.each(function (index, el) {
        var result = $(el).find(".class_time").find("dt").find("span").text();
        if ($.inArray(result, array) == -1) {
            array.push(result);
        }
        else {
            $(el).find(".class_time").find("dt").find("span").hide();
        }

    })
    })
</script>
