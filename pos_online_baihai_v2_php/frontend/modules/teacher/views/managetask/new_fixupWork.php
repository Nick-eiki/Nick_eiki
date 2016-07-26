<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-16
 * Time: 上午10:54
 */
use frontend\components\helper\TreeHelper;
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\LoadTextbookVersionModel;
use frontend\services\apollo\Apollo_chapterInfoManage;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;


/* @var $model \common\models\pos\SeHomeworkTeacher */
/* @var $this yii\web\View */
$this->title='教师--作业管理-布置作业';
$publicResources = publicResources_new();
$this->registerJsFile($publicResources . '/js/My97DatePicker/WdatePicker.js',['position'=>View::POS_HEAD]);
$this->registerJsFile($publicResources . '/js/register.js'.RESOURCES_VER,['position'=>View::POS_HEAD]);
?>
<script>
    $(function(){
        $('.tree').tree({expandAll:true,operate:true});

        //    切换版本获取分册
        $("#versionID").change(function () {
            versionId = $(this).val();
            $.post("<?=Url::to(['/ajax/get-section','prompt'=>false])?>",{versionId:versionId},function(result){
                $("#SectionId").html(result).change();
            })
        });

        $('.upWork').live('click',function(){
            var chapId = $("a[class='ac']").attr('data-value');

            if(typeof (chapId) == 'undefined'){
                popBox.errorBox('请选择章节！');
                return false;
            }else{
                $("#getChapId").val(chapId);
            }

        });

        // 改变分册获取目录
        $("#SectionId").change(function(){
            var sectionId = $(this).val();

            $.post("<?=Url::to(['/ajax/get-chaplist','prompt'=>false])?>",{sectionId:sectionId},function(result){
                $("#problem_tree").html(result);
                $('.tree').tree({expandAll:true,operate:true});
            })
        });

    })

</script>

<!--主体-->
<div class="grid_19 main_r">
    <div class="main_cont assign_homework">
        <div class="title">
            <a href="<?php echo url('teacher/resources/collect-work-manage');?>" class="txtBtn backBtn"></a>
            <h4>布置作业</h4>
        </div>
        <?php $form =\yii\widgets\ActiveForm::begin( array(
            'enableClientScript' => false,
            'id' => 'form1'
        ))?>
        <div class="form_list">
            <div class="row" style="padding:0">
                <div class="formL">
                    <label><i>*</i>作业名称：</label>
                </div>
                <div class="formR">
                    <input id="<?php echo Html::getInputId($model, 'name') ?>" type="text"
                           name="<?php echo Html::getInputName($model, 'name') ?>" class="text"
                           data-validation-engine="validate[required,maxSize[30]]" data-prompt-target="paperName_prompt"
                           data-prompt-position="inline"
                           data-errormessage-value-missing="作业名称不能为空" value="<?=$model->name ?>" />
                    <em class="gray_d" >（30字以内)</em><br/>&nbsp;&nbsp;&nbsp;&nbsp;<a class="blue" href="<?=url::to('library-list')?>">去看看别人的作业，前往作业库>></a>
                    <span id="paperName_prompt" class="errorTxt" style="left:376px"></span>
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'name') ?>

                </div>
            </div>
            <div class="row">
                <div class="formL">
                    <label><i>*</i>涉及章节：</label>
                </div>
                <div class="formR">
                    <div class="sel_step">
                        <label>章节单元</label>
                        <div><span class="selectWrap big_sel"> <i></i> <em>请选择</em>
                                <?php
                                $versionList = LoadTextbookVersionModel::model($subjectid, null, $departments)->getList();
                                echo Html::dropDownList('version', "",
                                    LoadTextbookVersionModel::model($subjectid, null, $departments)->getListData(),
                                    array(
                                        "id" => "versionID",
                                        'data-validation-engine' => 'validate[required]',
                                        'data-prompt-target' => "grade_prompt",
                                        'data-prompt-position' => "inline",
                                        'data-errormessage-value-missing' => "版本不能为空",
                                    ));
                                ?>
                                <span id="grade_prompt" style=" position:absolute;left: 305px;"></span>
                          </span>
                        </div>
                        <div>

                            <?php

                            $chapterTomeModel = new Apollo_chapterInfoManage();
                            $chapterTomeResult = $chapterTomeModel->chapterBaseNodeSearchList($subjectid, $departments, $versionList[0]->secondCode, null, null);

                            ?>
                            <span class="selectWrap big_sel" id="Section"> <i></i> <em>请选择</em>
                                <?php

                                echo \frontend\components\CHtmlExt::dropDownListAjax('SectionId', "",
                                    $chapterTomeResult,
                                    array(
                                        "id" => "SectionId",
                                        'data-validation-engine' => 'validate[required]',
                                        'data-prompt-target' => "chap_prompt",
                                        'data-prompt-position' => "inline",
                                        'data-errormessage-value-missing' => "分册不能为空",
                                    ));
                                ?>
                                <span id="chap_prompt" style=" position:absolute;left: 305px;"></span>
                          </span>
                        </div>
                    </div>

                    <div class="sel_step">
                        <h6>目录</h6>
                        <div class="treeBar">
                            <h6>全部</h6>

                            <div id="problem_tree">
                                <?php
                                $department = loginUser()->getModel()->department;
                                $obj = ChapterInfoModel::searchChapterPointToTree($subjectid, $departments,  $versionList[0]->secondCode, "", "");
                                $treeData = TreeHelper::streefun($obj, "", "tree pointTree");
                                echo $treeData;
                                ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="formL">
                    <label>作业介绍：</label>
                </div>
                <div class="formR">
                    <textarea name="<?php echo Html::getInputName($model, 'homeworkDescribe') ?>"
                         style="width: 500px;"></textarea>
                    <span id="describe_prompt" class="errorTxt" style="left: 520px;top: 80px;"></span>
                </div>
            </div>
            <input type="hidden" name="<?php echo Html::getInputName($model, 'chapterId') ?>" value="" id="getChapId" />
            <div class="row">
                <div class="formL">
                    <label></label>
                </div>
                <div class="formR">
                    <button class="btn bg_blue w100 upWork" type="submit" name="PaperForm[workStyle]" value="upWork">上传作业
                    </button>
                    <button class="btn bg_blue w100 upWork" type="submit" name="PaperForm[workStyle]" value="OrgWork">在线选题
                    </button>
                    <button class="btn bg_blue w100 upWork" type="submit" name="PaperForm[workStyle]" value="FinWork">暂不安排内容
                    </button>
                </div>
            </div>


        </div>
        <?php \yii\widgets\ActiveForm::end() ?>
    </div>
</div>
<!--主体end-->