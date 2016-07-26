<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-4-23
 * Time: 下午3:44
 */
use frontend\components\helper\TreeHelper;
use frontend\models\dicmodels\ChapterInfoModel;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = "备课";
?>
<div class="main_center prepare_lessons">
    <div class="main_cont">
        <div class="title">
            <a class="txtBtn backBtn" href="<?= Url::to(['/teacher/favoritematerial/index', 'yourtype' => 2]) ?>"></a>
            <h4>上传课件
            </h4>
        </div>
        <br>
        <a class="bg_green btn50 w180 iconBtn a_button" style="position:relative; overflow: hidden"><i
                class="btn_ico_upload"></i>
            <?php
            $t1 = new frontend\widgets\xupload\models\XUploadForm;
            /** @var $this BaseController */
            echo \frontend\widgets\xupload\XUploadSimple::widget(array(
                'url' => Yii::$app->urlManager->createUrl("upload/prepare"),
                'model' => $t1,
                'attribute' => 'file',
                'autoUpload' => true,
                'multiple' => false,
                'options' => array(
                    'acceptFileTypes' => new \yii\web\JsExpression('/\.(pdf|doc|docx|ppt|pptx|zip|rar|jpg|png)$/i'),
                    'maxFileSize' => '4000000',
                    "done" => new \yii\web\JsExpression('done'),
                    'start' => new \yii\web\JsExpression('start'),
                    "progressall" => new \yii\web\JsExpression('fileuploadprogressall'),
                    "processfail" => new \yii\web\JsExpression('function (e, data) {var index = data.index,file = data.files[index]; if (file.error) {alert(file.error);}}')
                ),
                'htmlOptions' => array(
                    'id' => 'fileupload',
                )
            ));
            ?>
            选择我的文件</a>

        <div class="item attentionTxt">
            <h5>上传须知</h5>

            <p class="gray_d">1. 您可以上传日常积累和撰写的备课资料，每个文件大小不超过4M；支持多种文件类型pdf，doc（docx），ppt（pptx），jpg，png。
            </p>

            <p class="gray_d">2. 上传时可设置文件为私有或共享权限，设置为共享的文件经过班海网审核通过后，任何人将可以浏览或下载。
            </p>

            <p class="gray_d">3.上传涉及侵权或违法内容的文档将会被移除。
            </p>
        </div>
        <div class="item uploadFiles">
            <h5> 上传文件列表</h5>
            <br>
            <ul class="uploadFileList" style="display: none;">
                <div class="big_progress">
                    <span class="num">0%</span>

                    <div class="bg"></div>
                </div>
                <li id="uploadfile"></li>

            </ul>
        </div>
        <br>
        <hr>
        <?php $form = \yii\widgets\ActiveForm::begin(array(
            'enableClientScript' => false,
            'id' => "form_id"
        ))?>
        <div class="item clearfix sel_file_location">
            <h5 class="noBorder">1.选择文件的上传位置</h5>

            <div class="sel_step">
                <input type="radio" value="2" class="hide" id="raido1" name="point" checked="checked"><label
                    for="raido1" class="radioLabel radioLabel_ac">章节单元</label>
                <input type="radio" value="1" class="hide" id="raido2" name="point"><label for="raido2"
                                                                                           class="radioLabel">知识点</label>

                <div style="position: relative">
                    <span class="selectWrap big_sel"> <i></i> <em>请选择</em>
                        <?php
                        echo Html::dropDownList("", $gradeID
                            ,
                            $gradeArray,
                            array(
                                "id" => "grade",
                                'data-validation-engine' => 'validate[required]',
                                'data-prompt-target' => "grade_prompt",
                                'data-prompt-position' => "inline",
                                'data-errormessage-value-missing' => "年级不能为空",
                            ));
                        ?>
                        <span id="grade_prompt" style=" position:absolute;left: 305px;"></span>
                          </span>

                </div>
                <br>

                <div style="position: relative"> <span class="selectWrap big_sel"> <i></i> <em>请选择</em>
                        <?php
                        echo Html::dropDownList("", $subjectID
                            ,
                            $subjectArray,
                            array(
                                "id" => "subject",
                                'data-validation-engine' => 'validate[required]',
                                'data-prompt-target' => "subject_prompt",
                                'data-prompt-position' => "inline",
                                'data-errormessage-value-missing' => "科目不能为空",
                            ));
                        ?>
                        <span id="subject_prompt" style=" position:absolute;left: 305px;"></span>
                        </span>
                </div>
                <br>

                <div style="position: relative"> <span class="selectWrap big_sel"> <i></i> <em>请选择</em>
                        <?php
                        echo Html::dropDownList("", $versionID
                            ,
                            $versionArray,
                            array(
                                "id" => "version",
                                'data-validation-engine' => 'validate[required]',
                                'data-prompt-target' => "version_prompt",
                                'data-prompt-position' => "inline",
                                'data-errormessage-value-missing' => "版本不能为空",
                            ));
                        ?>
                        <span id="version_prompt" style=" position:absolute;left: 305px;"></span>
                            </span>

                </div>

                <br>
            </div>
            <div class="sel_step">
                <h6>目录</h6>

                <div class="treeBar">
                    <h6>全部</h6>

                    <div id="problem_tree">
                        <?php
                        $department = loginUser()->getModel()->department;
                        $obj = ChapterInfoModel::searchChapterPointToTree($subjectID, $department, $versionID, "", "");
                        $treeData = TreeHelper::streefun($obj, "", "tree pointTree");
                        echo $this->render("_know_tree", array("treeData" => $treeData));
                        ?>
                    </div>

                </div>
            </div>
            <div class="sel_step">
                <h6>分类</h6>
                <ul class="resultList">
                    <li data="7">教学计划</li>
                    <li data="1">教案</li>
                    <li data="8">课件</li>
                    <li data="6">素材</li>
                    <li data="99">其它</li>
                </ul>

            </div>
        </div>
        <div class="item">
            <h5>2.设置文件权限</h5>

            <p><input type="radio" class="hide" id="raido3" value="1" name="limit" checked="checked"><label for="raido3"
                                                                                                            class="radioLabel radioLabel_ac">共享</label>
                <span class="font12">共享到班级或者教研组</span></p>

            <p><input type="radio" class="hide" id="raido4" value="2" name="limit"><label for="raido4"
                                                                                          class="radioLabel">私有</label>
            </p>
        </div>
        <br>
        <hr>
        <?php \yii\widgets\ActiveForm::end(); ?>
        <div class="submitBtnBar">
            <button style="margin-right:20px" class="btn40 bg_blue w120 confirm">确认上传</button>
            <button class="btn40 bg_blue_l w120  back">取消</button>

        </div>

    </div>
</div>

<script>
    $(function () {
        //滚动条
        $('.tree').tree({openSubMenu: true, operate: true});
        var q_data = {
            "currPage": "当前页码",
            "totalPages": "总页数",
            "countSize": "总记录数",
            "pageSize": "每页数据的条数",
            "questionList": [
                {
                    "id": "01",//题目id
                    "provience": "北京",//地区1
                    "city": "北京",//地区2
                    "country": "北京",//地区3
                    "gradeid": "三年级",//适用年级
                    "gradename": "三年级",//适用年级名称
                    "subjectid": "shuxue",//科目
                    "subjectname": "数学",//科目名称
                    "versionid": "renjiao",//版本
                    "versionname": "人教版",//版本名称
                    "kid": "钟表,时间",//知识点
                    "tqtid": "tiankong",// 题型
                    "showTypeId": "填空",//题目显示类型
                    "questiontypename": "填空题",// 题型名称
                    "provenance": "",//出处
                    "provenanceName": "",//出处名称
                    "year": "2012",//年份
                    "school": "",//名校
                    "schoolName": "",//名校名称
                    "complexity": "5",//难易程度
                    "capacityText": "",//能力提升
                    "capacity": "",//能力提升id
                    "capacityText": "",//能力提升
                    "Tags": "",//自定义标签
                    "content": "",//题目
                    "operater": "stu002",//录入人id
                    "operaterName": "李清",//录入人
                    "createTime": "",//创建时间
                    "updateTime": "",//更新时间
                    "tqName": "人教版小学三年级数学上册第五单元《时、分、秒》测试题",//题目名称
                    "analytical": "",//解析
                    " answerOption": "",//选择题答案
                    "answerContent": "",//答案
                    "questionPrice": "20",//价格
                    " childnum": "3",//小题数量
                    "childQues": [//小题
                        {
                            "id": 1017,
                            "tqtid": "01",// 题型
                            "questiontypename": "填空题",// 题型名称
                            "content": "",
                            "answerOption": "",
                            "answerContent": "xiao 1 daan",
                            "analytical": "",
                            "childnum": 0,
                            "childQues": []//小小题
                        }
                    ],
                    "status": ""//题目状态(0待审核,1通过,2禁用)
                }
            ],
            "resCode": "000000",
            "resMsg": "成功"
        };
        url = "";
        name = "";
        done = function (e, data) {
            $.each(data.result, function (index, file) {
                if (file.error) {
                    popBox.errorBox(file.error);
                    return;
                }
                url = file.url;
                name = file.name;
                $('#uploadfile').html(name + '<span>上传成功</span> ');
            });
        };
        fileuploadprogressall = function (e, data) {
            $('.uploadFileList').show();
            $('#uploadfile').html('');
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('.big_progress .num').text(progress + '%');
            $('.big_progress .bg').css('width', progress + '%');
        };

        start = function (e) {
            $('.big_progress .num').text('0%');
            $('.big_progress .bg').css('width', '0%');
        };
//    切换版本加载对应的章节树或者知识点树
        $("#version").change(function () {
            version = $(this).val();
            subject = $("#subject").val();
            type = $("[name='point']:checked").val();
            $.post("<?=url('teacher/prepare/get-know-tree')?>", {
                type: type,
                subject: subject,
                version: version
            }, function (result) {
                $("#problem_tree").html(result);
                $(".tree").tree();
            });

        });
        //    切换科目加载对应的章节树或者知识点树
        $("#subject").change(function () {
            subject = $(this).val();
            version = $("#version").val();
            type = $("[name='point']:checked").val();
            grade = $("#grade").val();
            $.post("<?=url('teacher/prepare/get-know-tree')?>", {
                type: type,
                subject: subject,
                version: version
            }, function (result) {
                $("#problem_tree").html(result);
                $(".tree").tree();
            });
            $.get("<?=Url::to(['/ajax/get-version','prompt'=>false])?>", {
                subject: subject,
                grade: grade
            }, function (result) {
                $("#version").html(result);
            })
        });
//        切换章节知识点 加载对应的章节树或者知识点树
        $(".sel_step").find('input:radio').change(function () {
            subject = $("#subject").val();
            version = $("#version").val();
            type = $("[name='point']:checked").val();
            $.post("<?=url('teacher/prepare/get-know-tree')?>", {
                type: type,
                subject: subject,
                version: version
            }, function (result) {
                $("#problem_tree").html(result);
                $(".tree").tree();
            });
        });
        $(".confirm").click(function () {
            gradeID = $("#grade").val();
            subjectID = $("#subject").val();
            versionID = $("#version").val();
            matType = $(".resultList .ac").attr("data");
            contentType = $("[name='point']:checked").val();
            access = $("[name='limit']:checked").val();
            chapKids = $("#problem_tree .ac").attr("data-value");
            if ($("#form_id").validationEngine("validate")) {
                if (url != "") {
                    if (matType != null) {

                        $.post("<?=url('teacher/prepare/ajax-upload')?>", {
                            gradeID: gradeID,
                            subjectID: subjectID,
                            versionID: versionID,
                            matType: matType,
                            contentType: contentType,
                            name: name,
                            url: url,
                            access: access,
                            chapKids: chapKids
                        }, function (result) {
                            if (result.success) {
                                popBox.successBox(result.message);
                                location.href = "<?=url('teacher/favoritematerial/index-create')?>"
                            }
                        })

                    } else {
                        popBox.errorBox("请选择分类");
                    }
                } else {
                    popBox.errorBox("请上传文件");
                }
            }
        });
//        取消返回上一页
        $(".back").click(function () {
            window.history.go(-1);
        });
//        删除我选择的文件
        $('.delFile').live("click", function () {
            $(this).parents('li').remove();
        })


    })
</script>