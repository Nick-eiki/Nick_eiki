<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-12
 * Time: 下午4:44
 */
use frontend\components\helper\ImagePathHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title="重置作业";
$publicResources = Yii::$app->request->baseUrl;
;
$this->registerJsFile($publicResources . '/js/My97DatePicker/WdatePicker.js');
$this->registerJsFile($publicResources . '/js/ztree/jquery.ztree.all-3.5.min.js');
$this->registerCssFile($publicResources . '/js/ztree/zTreeStyle/zTreeStyle.css'.RESOURCES_VER);
$this->registerJsFile($publicResources . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($publicResources . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($publicResources . '/js/register.js'.RESOURCES_VER);
?>
<div class="currentRight grid_16 push_2 up_work">
    <div class="noticeH clearfix noticeB">
        <h3>布置作业</h3>


    </div>
    <hr>
    <div class="up_fixup_list">
        <?php $form =\yii\widgets\ActiveForm::begin( array(
            'enableClientScript' => false,
            'id' => 'form1'
        ))?>
        <ul class="form_list">
            <li>
                <div class="formL">
                    <label><i></i>作业名称：</label>
                </div>
                <div class="formR">
                    <input id="<?php echo Html::getInputId($model, 'paperName') ?>" type="text"
                           value="<?php echo $homeworkDetails->name ?>"
                           name="<?php echo Html::getInputName($model, 'paperName') ?>" class="text"
                           data-validation-engine="validate[required,maxSize[30]]">
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'paperName') ?>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>上传作业：</label>
                </div>
                <div class="formR">
                    <div class="up_pic">
                        <p class="addPic btn">
                            <a href="javascript:" class="upload id_btn btn">上传作业</a>


                            <?php
                            $t2 = new frontend\widgets\xupload\models\XUploadForm;
                            echo  \frontend\widgets\xupload\XUploadSimple::widget( array(
                                'url' => Yii::$app->urlManager->createUrl("upload/header"),
                                'model' => $t2,
                                'attribute' => 'file',
                                'autoUpload' => true,
                                'multiple' => true,
                                'options' => array(
                                    'acceptFileTypes' => new \yii\web\JsExpression('/(\.|\/)(jpg|png|jpeg)$/i'),
                                    "done" => new \yii\web\JsExpression('done'),
                                    "processfail" => new \yii\web\JsExpression('function (e, data) {var index = data.index,file = data.files[index]; if (file.error) {alert(file.error);}}')

                                ),
                                'htmlOptions' => array(
                                    'id' => 't2',
                                )
                            ));
                            ?>
                        </p>
                    </div>
                    <ul class="up_img up_img_js clearfix" id="addImage">
                        <?php foreach (ImagePathHelper::getPicUrlArray($homeworkDetails->images) as $item) { ?>
                            <li><img src='<?php echo $item ?>' height='48' width='50'><i></i><input class="url"
                                                                                                    name="PaperForm[PaperRoute][]"
                                                                                                    type="hidden"
                                                                                                    value="<?php echo $item ?>"/>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>地区：</label>
                </div>
                <div class="formR">
                    <span class="area"><?php echo $personArray["provience"] ?><i>
                            &nbsp;&nbsp;<?php echo $personArray["city"] ?></i></span>
                </div>

            </li>
            <li>
                <div class="formL">
                    <label><i></i>科目：</label>
                </div>
                <div class="formR">
                    <span class="area"><?php echo $personArray["subject"] ?></span>
                </div>
            </li>

            <li>
                <div class="formL">
                    <label><i></i>教材版本：</label>
                </div>
                <div class="formR">
                    <span class="area"><?php echo $personArray["edition"] ?></span>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>交作业截止时间：</label>
                </div>
                <div class="formR">
                    <input type="text" class="text"
                           name="<?php echo Html::getInputName($model, 'deadlineTime') ?>"
                           value="<?php echo $homeworkDetails->uploadTime ?>"
                           data-validation-engine="validate[required,maxSize[30]]"
                           onclick="WdatePicker({dateFmt:'yyyy-MM-dd H:m:s',minDate:'<?php echo date('Y-m-d h:i:s', time()) ?>'});"
                        >
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>涉及知识点　</label>
                </div>
                <div class="formR">
                    <div id="point_chapt" class="treeParent">
                        <button id="pointBtn" type="button" class="point_btn id_btn">编辑知识点</button>
                        <div class="pointArea hide">
                            <input class="hidVal" type="hidden"
                                   name="<?php echo Html::getInputName($model, 'knowledgePoint') ?>"
                                   value="<?php echo $homeworkDetails->knowledgeId ?>">
                            <h6>已选中:</h6>
                            <ul class="labelList clearfix">
                            </ul>
                        </div>
                    </div>

                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>作业介绍：</label>
                </div>
                <div class="formR" style="600px">
                    <div class="word_box" style="width: 500px;">
                    <?php
                    echo \frontend\widgets\ueditor\MiniUEditor::widget(
                        array(
                            'id' => 'editor',
                            'model' => $model,
                            'attribute' => 'describe',
                            'UEDITOR_CONFIG' => array(
                                'initialContent' => $homeworkDetails->homeworkDescribe
                            ),

                        ));
                    ?>
                        </div>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label></label>
                </div>
                <div class="formR">
                    <button class="ok_btn" type="submit">确定</button>
                </div>
            </li>
        </ul>
        <?php \yii\widgets\ActiveForm::end() ?>
    </div>


</div>
<script>
    $(function () {
        var zNodes = <?php echo $knowledgePoint?>

            popBox.pointTree2(zNodes, $("#pointBtn"), "知识点");


    })
</script>
<script>
    $(function () {
        $('#msg_parent').dialog({
            autoOpen: false,
            width: 500,
            modal: true,
            resizable: false,
            buttons: [
                {
                    text: "确定",
                    click: function () {
                        $(this).dialog("close");
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

        $('#stuListBox').dialog({
            autoOpen: false,
            width: 500,
            modal: true,
            resizable: false,
            buttons: [
                {
                    text: "确定",
                    click: function () {
                        $(this).dialog("close");
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

        $('.contact_div .msgParentJS').click(function () {
            $("#msg_parent").dialog("open");
            return false;
        });
        $('.contactList .sendPJS').click(function () {
            $("#msg_parent").dialog("open");
            return false;
        });

        $('#msg_parent .selectForJs').click(function () {
            $("#stuListBox").dialog("open");
            return false;
        });


        /*删除添加的作业*/
        $('.up_img_js li').live('mouseover', function () {
            $(this).children('i').show();
        });
        $('.up_img_js li').live('mouseout', function () {
            $(this).children('i').hide();
        });
        $('.up_img_js li i').live('click', function () {
            $(this).parent().remove();
        });

        $('.addPicUl li').live('mouseover', function () {
            $(this).children('i').show();
        });
        $('.addPicUl li i').live('click', function () {
            $(this).parent('li').remove();

        });
        done = function (e, data) {
            $.each(data.result, function (index, file) {
                if (!file.error) {
                    $('#addImage').append('<li><img   src="' + file.url + '" alt="" height="48" width="50"><i></i><input class="url" name="PaperForm[PaperRoute][]" type="hidden" value="' + file.url + '" /></li>  ');
                }
                else {
                    alert(file.error);
                }

            });
        }


    })


</script>