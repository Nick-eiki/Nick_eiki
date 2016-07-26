<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-17
 * Time: 上午11:09
 */
/* @var $this yii\web\View */  $this->title="教师-备课-公文包";
$backend_asset = publicResources();
;
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);
?>
<script type="text/javascript">
    $(function () {
//定义弹出框
        $('#addDocBagBox').dialog({
            autoOpen: false,
            width: 550,
            modal: true,
            resizable: false,
            buttons: [
                {
                    text: "确定",
                    click: function () {
                        if ($('#form_add').validationEngine('validate')) {
                            var name = $('#name').val();
                            var stuLimit = 0;
                            var groupMemberLimit = 0;

                            if ($("#departmentMemLimit").attr("checked") == "checked") {
                                var departmentMemLimit = 1;
                            }
                            $.post('<?php echo url('teacher/briefcase/add-briefcase');?>', {
                                name: name,
                                stuLimit: stuLimit,
                                groupMemberLimit: groupMemberLimit,
                                departmentMemLimit: departmentMemLimit
                            }, function (data) {
                                if (data.success) {
                                    location.reload();
                                } else {
                                    popBox.alertBox(data.message);
                                }
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

        $('#editDocBagBox').dialog({
            autoOpen: false,
            width: 550,
            modal: true,
            resizable: false,
            buttons: [
                {
                    text: "确定",
                    click: function () {
                        if ($('#updatebriefcase').validationEngine('validate')) {
                            var id = $('#briefcaseId').val();//公文包id
                            var name = $('#edit_name').val();
                                var student = 0;
                                var group = 0;

                            if ($("#department").attr("checked") == "checked") {
                                var department = 1;
                            }
                            $.post("<?php echo url('teacher/briefcase/edit-briefcase')?>", {
                                id: id,
                                name: name,
                                student: student,
                                group: group,
                                department: department
                            }, function (data) {
                                if (data.success) {
                                    location.reload();
                                } else {
                                    popBox.alertBox(data.message);
                                }
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

//编辑公文袋
        $('.docBagList li button').live('click', function () {
            _this = $(this);
            var id = _this.attr('briefcaseId');
            $.post("<?php echo url("teacher/briefcase/get-briefcase-id")?>",
                {id: id}, function (data) {
                    $("#editDocBagBox").dialog("open");
                    $('#editDocBagBox').html(data);
                });

        });

//新建公文袋
        $('.addDocBagBtn').click(function () {
            $("#addDocBagBox").dialog("open");
        })


    })
</script>
<!--主体内容开始-->

<div class="currentRight grid_16 push_2">
    <div class="noticeH clearfix">
        <h3 class="h3L">公文包</h3>

        <div class="fr">
            <a href="javascript:" class="B_btn120 addDocBagBtn">添加公文包</a>
        </div>
    </div>
    <hr>
    <div class="docPack pr">
        <ul class="docBagList clearfix">
            <?php foreach ($modelList as $key => $item) {
                ?>
                <li>
                    <button type="button" title="编辑" briefcaseId="<?php echo $item->ID; ?>"></button>
                    <h5>
                        <a href="<?php echo url('teacher/briefcase/get-list', array('id' => $item->ID)) ?>"><?php echo cut_str($item->Name, 12); ?></a>
                    </h5>

                    <p><em>可见:<?php
                            if ($item->departmentMemLimit) {
                                ?>
                                所有人可见
                            <?php }else{ ?>
                                所有人不可见
                          <?php  } ?>
                    </p>
                    <p><?php foreach ($item->cntLst as $key => $val) { ?>
                            <?php echo $val->typeName; ?>:<?php echo $val->cnt; ?>
                        <?php } ?>

                        <!--                            <em>教案:</em> &nbsp;&nbsp;<em>讲义:</em>3&nbsp;&nbsp;<em>课件:</em>3-->
                    </p>
                </li>
            <?php } ?>
        </ul>
    </div>
        <?php
         echo \frontend\components\CLinkPagerExt::widget( array(
               'pagination'=>$pages,
//                    'updateId'=>'#collection',
                'maxButtonCount' => 5
            )
        );
        ?>
</div>


<!--主体内容结束-->

<!--弹出框  创建/编辑公文袋-->
<div class="popBox docBagBox hide" id="addDocBagBox" title="创建公文包">
    <form id="form_add">
        <ul class="form_list">
            <li>
                <div class="formL">
                    <label><i></i>公文包名称:</label>
                </div>
                <div class="formR">
                    <input id="name" type="text" class="text" data-validation-engine="validate[required,maxSize[30]]">(最多可输入30个字)
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>公文袋权限:</label>
                </div>
                <div class="formR">
                    <input type="radio" value="0" id="stuLimit" name="stuLimit[stuLimit]"
                           data-validation-engine="validate[minCheckbox[1]]"> <label>所有人不可见</label>&nbsp;&nbsp;
                    <input type="radio" value="0" id="departmentMemLimit" name="stuLimit[stuLimit]"
                           data-validation-engine="validate[minCheckbox[1]]"> <label>所有人可见</label>
                    <br>
                </div>
            </li>
        </ul>
    </form>
</div>

<div class="popBox docBagBox hide" id="editDocBagBox" title="修改公文包">

</div>