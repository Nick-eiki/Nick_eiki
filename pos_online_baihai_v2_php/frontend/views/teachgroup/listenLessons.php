<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 2015/7/20
 * Time: 11:31
 */

$this->registerJsFile(publicResources_new() . "/js/register.js".RESOURCES_VER);
$this->title = "听课评课";
?>
<div class="main_cont  listen_comment">
    <div class="title">
        <h4> 听课安排</h4>

        <div class="title_r">
            <select class="type">
                <option value="0">全部安排</option>
                <option value="1">我主讲的</option>
                <option value="2">我参与的</option>
            </select>
            <button type="button" id="addBtn" class="btn btn40 bg_green"> 安排听课</button>
        </div>
    </div>
    <div class="date_sc">
        <div class="list_box lessonList">
            <?php echo $this->render("_lesson_list", array("lessonList" => $lessonList, "pages" => $pages, 'groupId' => $groupId)); ?>
        </div>
    </div>
</div>
<!--听课评课弹出层-->
<div id="popBox1" class="popBox popBox_hand hide" title="安排听课">
    <?php echo $this->render('_listen_plan', array('teacherList' => $teacherList, 'groupId' => $groupId)); ?>
</div>

<div id="popBox2" class="popBox popBox_hand hide" title="修改听课计划">

</div>
<script type="text/javascript">
    //初始化弹窗
    $(function () {
        $('#popBox1').dialog({
            autoOpen: false,
            width: 720,
            modal: true,
            resizable: false,
            buttons: [
                {
                    text: "确定",
                    click: function () {
                        $this = $(this);
                        if ($("#form_id").validationEngine("validate")) {
                            var title = $("#notice_name").val();
                            var speaker = $("[name='speakers']:checked").val();
                            var obj = $("[name]='joiner':checked");
                            var joinArray = [];
                            obj.each(function (index, el) {
                                joinArray.push($(el).val());
                            });
                            joiner = joinArray.join(",");
                            var listenTime = $("#listenTime").val();

                            groupId = "<?=app()->request->getQueryParam('groupId')?>";
                            $.post("<?=url('teachgroup/arrange-lessons')?>", {
                                title: title,
                                speaker: speaker,
                                joiner: joiner,
                                listenTime: listenTime,
                                groupId: groupId
                            }, function (result) {
                                if (result.success) {
                                    popBox.successBox(result.message);

                                }else{
                                    popBox.errorBox(result.message);
                                }
                                $this.dialog("close");
                                location.reload();
                            })
                        }
                    }
                },
                {
                    text: "取消",
                    click: function () {
                        $(this).dialog("close");
                    }
                }

            ]
        });
        $('#popBox2').dialog({
            autoOpen: false,
            width: 720,
            modal: true,
            resizable: false,
            buttons: [
                {
                    text: "确定",
                    click: function () {
                        $this = $(this);
                        if ($("#update_form_id").validationEngine("validate")) {
                            var title = $("#update_notice_name").val();
                            var speaker = $("[name='updateSpeakers']:checked").val();
                            var obj = $("[name]='updateJoiner':checked");
                            var joinArray = [];
                            obj.each(function (index, el) {
                                joinArray.push($(el).val());
                            });
                            joiner = joinArray.join(",");
                            var listenTime = $("#updateListenTime").val();
                            groupId = "<?=app()->request->getQueryParam('groupId')?>";
                            var lecturePlanID = $("#lecturePlanID").val();
                            $.post("<?=url('teachgroup/update-lessons')?>", {
                                title: title,
                                speaker: speaker,
                                joiner: joiner,
                                listenTime: listenTime,
                                groupId: groupId,
                                lecturePlanID: lecturePlanID
                            }, function (result) {
                                if (result.success) {
                                    popBox.successBox(result.message);

                                }else{
                                    popBox.errorBox(result.message);
                                }
                                $this.dialog("close");
                                location.reload();
                            })
                        }
                    }
                },
                {
                    text: "取消",
                    click: function () {
                        $(this).dialog("close");
                    }
                }

            ]
        });

        /*发布通知弹窗*/
        $('#addBtn').click(function () {
            $("#popBox1").dialog("open");
            $("#notice_name").placeholder({'value':"请输入标题名称"});
            $("#notice_name").trigger("blur");
            return false;
        });
        $('#form_id').validationEngine({
            promptPosition: "centerRight",
            maxErrorsPerField: 1,
            showOneMessage: true,
            addSuccessCssClassToField: 'ok',
            validateNonVisibleFields: true
        });
        $(".type").change(function () {
            var type = $(this).val();
            $.get("<?=url('teachgroup/listen-lessons',array('groupId'=>app()->request->getQueryParam('groupId')))?>", {type: type}, function (result) {
                $(".lessonList").html(result);
            })
        })


    });
</script>