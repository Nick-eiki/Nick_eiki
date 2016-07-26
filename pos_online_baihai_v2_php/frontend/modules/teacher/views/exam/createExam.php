<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-10-15
 * Time: 下午5:28
 */
use frontend\models\dicmodels\ExamTypeModel;

$publicResources = Yii::$app->request->baseUrl;
;
$this->registerJsFile($publicResources . '/pub/js/My97DatePicker/WdatePicker.js');
/* @var $this yii\web\View */  $this->title="新建考试";
?>
<div class="grid_19 main_r">
    <div class="main_cont test">
        <div class="title">
            <a href="<?= url('teacher/exam/manage', array('classid' => $classId)) ?>"
               class="txtBtn backBtn"></a>
            <h4>创建考试</h4>
        </div>

        <?php $form =\yii\widgets\ActiveForm::begin( array(
            'enableClientScript' => false,
            'id' => "form_id"
        )) ?>
        <div class="form_list testList_new">
            <div class="row">
                <div class="formL">
                    <label>考试类型:</label>
                </div>
                <div class="formR">
                    <ul class="resultList clearfix testClsList">
                        <?php foreach (ExamTypeModel::model()->getManualType() as $key => $value) { ?>
                            <li type="<?php echo $value->secondCode ?>"><a><?php echo $value->secondCodeValue ?></a></li>

                        <?php } ?>
                    </ul>
                    <input type="hidden" name="ExamForm[type]" class="type"/>
                </div>
            </div>
            <div class="row">

                <div class="formL">
                    <label>考试名称:</label>
                </div>
                <div class="formR">
                    <div class="testList_r">
                        <input type="text" class="text" id="test_name" name="ExamForm[examName]"
                               data-validation-engine="validate[required] validate[minSize[2]] validate[maxSize[20]]"
                               data-prompt-position="inline" data-prompt-target="nameError">

                        <div id="nameError" class="errorTxt" style="left:205px; top:13px"></div>
                    </div>

                </div>
            </div>
            <div class="row" style="padding-bottom:0px">
                <div class="formL">
                    <label>科目满分:</label>
                </div>
                <div class="formR">
                    <ul class="clearfix testList_score">
                        <?php foreach ($subjectArray as $v) { ?>
                            <li>
                                        <span class="testList_score_l">
                                          <?php if ($v->secondCode == $subjectID) { ?>
                                              <input type="checkbox" checked="checked" id="<?= 'a' . $v->secondCodeValue ?>"
                                                     value="<?php echo $v->secondCode ?>"
                                                     name="ExamForm[subjectList][<?php echo $v->secondCode ?>][subject]"
                                                     class="hide">
                                          <?php } else { ?>
                                              <input type="checkbox" id="<?= 'a' . $v->secondCode ?>"
                                                     value="<?php echo $v->secondCode ?>"
                                                     name="ExamForm[subjectList][<?php echo $v->secondCode ?>][subject]"
                                                     class="hide">
                                          <?php } ?>
                                            <label
                                                class='<?= $v->secondCode != $subjectID ? "chkLabel " : "chkLabel chkLabel_ac" ?>'
                                                for="<?= 'a' . $v->secondCode ?>"><?php echo $v->secondCodeValue ?></label>
                                        </span>
                                        <span class="testList_score_r">
                                            <input type="text" class="text"
                                                   name="ExamForm[subjectList][<?php echo $v->secondCode ?>][score]">
                                        </span>
                            </li>
                        <?php } ?>


                    </ul>
                </div>
            </div>
            <div class="row cut_apart">
                <div class="formL">
                    <label>考试时间:</label>
                </div>
                <div class="formR">
                    <div class="testList_r">
                        <input type="text" class="text" id="test_time" name="ExamForm[examTime]"  placeholder="例如：2015-4-10"
                               onclick="WdatePicker({dateFmt:'yyyy-MM-dd',maxDate:'2099'})"
                               data-validation-engine="validate[required] validate[minSize[2]] validate[maxSize[20]]"
                               data-prompt-position="inline" data-prompt-target="nameTime">

                        <div id="nameTime" class="errorTxt" style="left:205px; top:13px"></div>

                    </div>

                </div>
            </div>


            <div class="row">
                <div class="formL" style=" width:70px;">
                    <label></label>
                </div>
                <div class="formR">
                                <span class="testList_sub">
                                    <input type="submit" class="w140 test_btn sub" value="确定">

                                </span>

                </div>
            </div>


        </div>
        <?php \yii\widgets\ActiveForm::end(); ?>


    </div>
</div>
<script>
    $(function () {
        //文字提示
        $('#test_name').placeholder({value: '请输入此次考试名称', ie6Top: 2, ie7Top: 2, top: 10});
//        $('#test_time').placeholder({value: '例如：2015-4-10', ie6Top: 2, ie7Top: 2, top: 10});
        //$('#sclName').placeholder({value:'输入学校名称',ie6Top:10, top:2});
        $(".resultList ").find("li").click(function () {
            type = $(this).attr("type");
            $(".type").val(type);
        });
        $("#form_id").submit(function () {

            $result = true;
            if ($(".type").val() == "") {
                popBox.errorBox("请选择考试类型");
                return false;
            }
            var texts = $('.testList_score input:text');//.parents('li').find('input:text').val();
            texts.each(function (index, element) {
                if ($(this).parents('li').find('input:checkbox').attr('checked')) {
                    if ($(this).val() == '') {
                        popBox.errorBox("分数不能为空");
                        this.focus();
                        $result = false;
                        return false;
                    }
                    else if (isNaN($(this).val())) {
                        popBox.errorBox("分数要为数字");
                        this.focus();
                        $result = false;
                        return false;
                    }
                    else if ($(this).val() <= 0) {
                        popBox.errorBox('分数要大于零');
                        this.focus();
                        $result = false;
                        return false;
                    }

                }

            });

            return $result;
        })
    })

</script>
