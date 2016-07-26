<?php
/**
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2015/6/24
 * Time: 11:13
 */
use frontend\models\dicmodels\SubjectModel;
use yii\helpers\Html;

$publicResources = Yii::$app->request->baseUrl;
;
$this->registerJsFile($publicResources . "/pub/js/register.js".RESOURCES_VER);

$this->registerCssFile(publicResources_new() . '/js/fancyBox/jquery.fancybox.css'.RESOURCES_VER);
$this->registerJsFile(publicResources_new() . "/js/fancyBox/jquery.fancybox.js".RESOURCES_VER,[ 'position'=>\yii\web\View::POS_HEAD] );

/* @var $this yii\web\View */  $this->title="学霸养成记";
?>
<div class="grid_19 main_r cultivate">
    <div class="main_cont student_home pr ">
        <div class="title">
            <h4>学霸养成记</h4>
            <div class="title_r"><a class="btn btn40 bg_green w120 addmemor_btn">记录成长</a></div>
        </div>
        <div class="memorabilia_QA_list">
            <?php echo $this->render("_record_list",array("superResult"=>$superResult,"pages"=>$pages))?>
        </div>
    </div>
</div>
<!--记录成长-->
<div class="popBox hide pushNotice memor_popbox" title="记录成长" id="addMemor">
    <div class="popCont">
        <div class="new_tch_group">
            <form id="growup">
                <div class="form_list">
                    <div class="row">
                        <div class="formL">
                            <label>事件名称：</label>
                        </div>
                        <div class="formR">
                            <input type="text" class="text titleID eventName"   data-validation-engine="validate[required]">

                        </div>
                    </div>
                    <div class="row">
                        <div class="formL">
                            <label>类型：</label>
                        </div>
                        <div class="formR">
                            <span class="selectWrap big_sel">
                           	<i></i>
                           	<em>请选择</em>
                                <?php

                                echo Html::dropDownList("","",
                                    $typeArray,
                                    array(

                                        "id" => "type",
                                    ));
                                ?>
                            </span>
                            <span id="typeError" class="errorTxt" style="width: 100px">
                                </span>
                            <span class="selectWrap big_sel">
                           	<i></i>
                           	<em>请选择</em>
                                <?php

                                echo Html::dropDownList("","",
                                    SubjectModel::model()->getListData(),
                                    array(
                                        "prompt" => "请选择",
                                        "id" => "subjectID",
                                        "data-validation-engine"=>"validate[required]",
                                        "data-prompt-target"=>"subjectError",
                                        "data-prompt-position"=>"inline"
                                    ));
                                ?>
                                <span id="subjectError" class="errorTxt" style="width: 100px;left:130px">
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="formL">
                            <label>详情：</label>
                        </div>
                        <div class="formR personal">
                            <button type="button" class="bg_green btn w140 btn40 up_pic_Js">上传图片</button>
                            <ul class="up_test_list clearfix hide">
                                <li class="more">
                                    <?php
                                    $t1 = new frontend\widgets\xupload\models\XUploadForm;
                                    /** @var $this BaseController */
                                    echo  \frontend\widgets\xupload\XUploadSimple::widget( array(
                                        'url' => Yii::$app->urlManager->createUrl("upload/paper"),
                                        'model' => $t1,
                                        'attribute' => 'file',
                                        'autoUpload' => true,
                                        'multiple' => false,
                                        'options' => array(
                                            "done" => new \yii\web\JsExpression('done')
                                        ,
                                        ),
                                        'htmlOptions' => array(
                                            'id' => 'fileupload',
                                        )
                                    ));
                                    ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row" style="margin-top:-10px">
                        <div class="formL">
                            <label>总结：</label>
                        </div>
                        <div class="formR">

                                <textarea class="textarea summary" style="width: 400px ;" data-validation-engine="validate[required]"  data-prompt-position="inline"  data-prompt-target="summaryError" ></textarea>
                                <span id="summaryError" class="errorTxt" style="width: 100px; left:409px">
                                </span>
                                <span class="placeholder"></span>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="popBtnArea">
        <button type="button" class="okBtn">确定</button>
        <button type="button" class="cancelBtn">取消</button>
    </div>
</div>
<script>
    $(function(){
        $('.popBox').dialog({
            autoOpen: false,
            width:720,
            modal: true,
            resizable:false,
            close:function(){$(this).dialog("close")}
        });

        $('.addmemor_btn').click(function(){

            /*手拉手*/
            $( ".popBox" ).dialog( "open" );
            $('.popBox .eventName').blur().placeholder({value:"请输入事件名称"});
            return false;

        });
        $('.up_pic_Js').live('click',function(){
            $(this).siblings('.up_test_list').removeClass('hide');
            $(this).addClass('hide');
        });
        done = function (e, data) {
            $.each(data.result, function (index, file) {
                if (file.error) {
                    popBox.errorBox(file.error);
                    return;
                }
                $('<li><img src="' + file.url + '" alt=""><span class="delBtn"></span></li>').insertBefore($(e.target).parent());
            });
        };
        $(".okBtn").click(function(){
            if($("#growup").validationEngine("validate")) {
                title = $(".titleID").val();
                type = $("#type").val();
                summary = $(".summary").val();
                subjectID = $("#subjectID").val();
                var urlArray = [];
                $(".up_test_list ").find("img").each(function (index, el) {
                    var url = $(el).attr("src");
                    urlArray.push(url);
                });
                content = urlArray.join(",");
                $.post("<?=url('student/super/set-growup-record')?>", {
                    title: title,
                    type: type,
                    summary: summary,
                    subjectID: subjectID,
                    content: content
                }, function (result) {
                    popBox.successBox(result.message);
                    location.reload();
                })
            }
        })
    })
</script>