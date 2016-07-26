<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-9-18
 * Time: 下午3:10
 */
?>

<!--时间轴-->

<?php foreach ($teachingListenList->data->list as $v) { ?>
    <div class="class_B">
        <dl class="class_time">
            <dt>
                <span><?php echo date("Y", strtotime($v->joinTime)) ?>年<?php echo date('m', strtotime($v->joinTime)) ?>月<i></i></span>
            <h4><em><i style="color:#ff8282; font-size:14px;">[主讲人：<?php
                            if ($v->teacherID == user()->id) {
                                echo '我自己';
                            } else {
                                echo $v->teacherName;
                            } ?>]</i>&nbsp;<?php echo $v->chapterName ?></em><span></span><i></i><em
                    class="time"><?php echo $v->joinTime ?></em></h4>

            </dt>

            <dd><?php echo $v->title ?></dd>
            <dd>听课人<?php foreach ($v->joinUsers as $value) { ?><span
                    style="margin-left:10px;"><?php echo $value->userName ?></span>
                <?php } ?>
            </dd>
            <dd>
                听课报告<span
                    class="click_btn">共<i><?php echo !empty($v->teachDairy) ? count($v->teachDairy) : 0 ?></i>篇<b></b></span>
                <?php
                foreach ($v->joinUsers as $val) {
                    if ($val->userId == user()->id && empty($v->teachDairy)) {
                        ?>
                        <a href="/teacher/diary/addDiary" class="blue">去评课</a>
                    <?php
                    }
                }
                foreach ($v->teachDairy as $key => $item) {
                    if ($item->teacherID !== user()->id) {
                        ?>
                        <a href="/teacher/diary/addDiary" class="blue">去评课</a>
                    <?php
                    }
                } ?>
                <div class="tch_popo pop">
                    <em></em>
                    <?php if (!empty($v->teachDairy)) { ?>
                        <ul class="clearfix">
                            <?php foreach ($v->teachDairy as $value) {
                                if($value->teacherID ==user()->id){ ?>
                                    <li>
                                        <a href="<?php echo url('teacher/diary/diary-view', array('id' => $value->diaryID, 'type' => '1')) ?>"><?php echo $value->headline ?></a>
                                    </li>
                              <?php  }else{ ?>
                                    <li>
                                        <a href="<?php echo url('teachinggroup/listen-detail', array('groupId' => $v->teachingGroupID, 'diaryID' => $value->diaryID)) ?>"><?php echo $value->headline ?></a>
                                    </li>
                            <?php } } ?>

                        </ul>
                    <?php } ?>

                </div>

            </dd>

        </dl>

    </div>
<?php
}
if ($pages->getPageCount() > $pages->getPage() + 1) {
    ?>
    <div class="addMore morejs" id="teachingMore">
        <span onclick="return getTeachingLessons(<?php echo $pages->getPage() + 2 ?>);">更多&gt;&gt;</span>
    </div>

<?php } ?>
<!--时间轴-->
<script>
    var getTeachingLessons = function (page) {
        $.get('<?php echo  url( 'teacher/lesson/get-lessons-page') ?>', {page: page, queryType: $(".select_tab").val()}, function (data) {
            $("#teachingMore").replaceWith(data);
        });


    };
    var obj = $(".teaching").find(".class_B");
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
</script>

