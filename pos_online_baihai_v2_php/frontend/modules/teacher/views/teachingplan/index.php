<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-14
 * Time: 上午11:11
 */
use frontend\models\dicmodels\GradeModel;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */  $this->title="教师管理-教学计划";
$backend_asset = publicResources();
;
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);
?>
<script>
    $(function () {
        $('#tp').dialog({
            autoOpen: false,
            width: 600,
            modal: true,
            resizable: false,
            buttons: [
                {
                    text: "确定",
                    click: function () {
                        if ($('#form_plan').validationEngine('validate')) {
                            var planName = $('#planName').val();
                            var gradeID =$('#gradeID').val();
                            var brief =$('#brief').val();
                            var url = $('#url').val();
                            if(url==""){
                                popBox.alertBox('上传计划不能为空！');
                                return false;
                            }
                          $.post("<?php echo url("teacher/teachingplan/add-plan")?>",
                                {planName: planName, gradeID: gradeID, brief: brief, url: url}, function (data) {
                                    if (data.success) {
                                        location.reload();
                                    }else{
                                        popBox.alertBox(data.message);
                                    }
                                });
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

        $('#Edit_plan').dialog({
            autoOpen: false,
            width: 600,
            modal: true,
            resizable: false,
            buttons: [
                {
                    text: "确定",
                    click: function () {
                        if ($('#updateTeachingPlan').validationEngine('validate')) {
                            var id = $("#Edit_plan #plan").val();
                            var planName = $("#Edit_plan #planName").val();
                            var gradeID = $("#Edit_plan #gradeID").val();
                            var url = $("#Edit_plan #editurl").val();
                            if (url == "") {
                                popBox.alertBox('上传计划不能为空！');
                                return false;
                            }
                            var brief = $("#Edit_plan #brief").val();
                            $.post("<?php echo url("teacher/teachingplan/edit-plan")?>",
                                {'id': id, 'planName': planName, 'gradeID': gradeID, 'url': url, 'brief': brief}, function (data) {
                                    if (data.success) {
                                        location.reload();
                                    } else {
                                        popBox.alertBox(data.message);
                                    }
                                });
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
        //修改弹窗
        $('.teachingPlan_list li .changeForJs').live('click',function () {
            _this = $(this);
            var id = _this.attr('teaching');
            $.post("<?php echo url("teacher/teachingplan/get-teaching-plan")?>",
                {id: id}, function (data) {
                    $("#Edit_plan").dialog("open");
                    $('#Edit_plan').html(data);
                });
        });

        /*添加弹窗*/
        $('#addnewPlanJS').click(function(){

            $( "#tp" ).dialog( "open" );
            return false;

        });
        $("#grade").change(function () {
            var grade = $("#grade").val();
            $.post("<?php echo app()->request->url;?>", {'grade': grade}, function (data) {
                $('#teachingPlan').html(data);
            })
        })

    });
    addDone = function (e, data) {
        $.each(data.result, function (index, file) {
            if (file.error) {
                popBox.alertBox(file.error);
                return false;
            }
            $('#addimage').html('<li class="fl" vals="' + file.url + '">'+file.name+'<i></i></li> ');

        });

        var urls = [];

        $("li.fl").each(function (i) {
            urls.push($(this).attr('vals'));
        });

        $('#url').val(urls.join(','));


    };
    editDone = function (e, data) {
        $.each(data.result, function (index, file) {
            if (file.error) {
                popBox.alertBox(file.error);
                return false;
            }
            $('#editimg').html('<li class="fl" vals="' + file.url + '">'+file.name+'<i></i></li>  ');
        });
        var urls = [];

        $("#editimg li.fl").each(function (i) {
            urls.push($(this).attr('vals'));
        });
        $('#editurl').val(urls.join(','));
    }



</script>
<div class="currentRight grid_16 push_2 teachingPlan_div">
    <div class="noticeH clearfix noticeB teachingPlan_title">
        <h3 class="h3L">个人教学计划</h3>
        <div class="new_not fr">
            <?php
            echo Html::dropDownList('grade',
                '',
                ArrayHelper::map( GradeModel::model()->getList(),'gradeId','gradeName'),
                array("prompt" => "所有年级"));
            ?>
            <a href="javascript:;" class="new_examination newPlanJS" id="addnewPlanJS">新计划</a></div>
    </div>
    <hr>
    <div id="teachingPlan">
      <?php echo $this->render('_list_plan',array('teachingList'=>$teachingList,'pages'=>$pages)) ?>
    </div>

</div>
<!--弹出框pop--------------------->
<div id="tp" class=" popBox tp hide" title="创建教学计划">
    <form id="form_plan">
        <div class="impBox">
            <ul class="form_list">
                <li>
                    <div class="formL">
                        <label><i></i>教学计划名称：</label>
                    </div>
                    <div class="formR">
                        <input id="planName" type="text" class="text" data-validation-engine="validate[required]">
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label><i></i>使用年级：</label>
                    </div>
                    <div class="formR">
                        <?php
                        echo Html::dropDownList('gradeID',
                            '',
                            ArrayHelper::map( GradeModel::model()->getList(),'gradeId','gradeName'));
                        ?>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label><i></i>描述：</label>
                    </div>
                    <div class="formR">
                        <textarea id="brief" data-validation-engine="validate[required]" style="width: 200px;height: 60px;"
                                  data-prompt-target="target_error" data-prompt-position="inline"
                                  data-errormessage-value-missing="教学计划描述不能为空"></textarea>
                    </div>
                    <span id="target_error"></span>
                </li>
                <li>
                    <div class="formL">
                        <label><i></i>教学计划：</label>
                    </div>
                    <div class="formR">
                        <div class="fl" style="font-size:12px; color:#999999;">
                            <p class="addPic btn btnpop">
                                <a href="javascript:;" class="id_btn" style="color:#FFF;">上传计划</a>
                                <?php
                                $t1 = new frontend\widgets\xupload\models\XUploadForm;
                                echo  \frontend\widgets\xupload\XUploadSimple::widget( array(
                                    'url' => Yii::$app->urlManager->createUrl("upload/doc"),
                                    'model' => $t1,
                                    'attribute' => 'file',
                                    'autoUpload' => true,
                                    'multiple' => false,
                                    'options' => array(
                                        "done" =>new \yii\web\JsExpression('addDone')
                                    ),
                                    'htmlOptions' => array(
                                        'id' => 't1',
                                    )
                                ));
                                ?>
                            </p>
                            <em style="margin-left:10px;">文件格式限定为doc,docx或pdf</em><span id="url_error"></span> </div>
                        <ul class="add_del addPicUl" id="addimage">
                            <!--                    <li><a href="javascript:;" class="addDel">删除</a></li>-->
                        </ul>
                    </div>
                    <input type="hidden"  id="url"  value=""/>
                </li>
            </ul>
        </div>
    </form>
</div>
<div id="Edit_plan" class=" popBox addPlan hide" title="修改教学计划">
</div>



