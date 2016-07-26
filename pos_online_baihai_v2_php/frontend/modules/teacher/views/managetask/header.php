<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-12-11
 * Time: 下午4:47
 */
use frontend\models\dicmodels\GradeModel;
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title="组卷";
$backend_asset = publicResources_new();
;
$this->registerJsFile($backend_asset . '/js/My97DatePicker/WdatePicker.js');
$this->registerJsFile($backend_asset . '/js/json2.js' . RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);
?>
<div class="currentRight grid_16 push_2 make_testpaper">
    <div class="noticeH clearfix">
        <h3 class="h3L">组卷</h3>
    </div>
    <hr>
    <ul class="stepList clearfix">
        <li class="ac"><span>试卷标题</span><i class="step01"></i></li>
        <li class="over"><span>试卷结构</span><i class="step02"></i></li>
        <li class="over"><span>筛选题目</span><i class="step03"></i></li>
    </ul>
    <br>
    <?php echo Html::beginForm('', 'post', ['id' => "makePaper"]) ?>
    <ul class="form_list">
        <li>
            <div class="formL">
                <label><i></i>作业名称：</label>
            </div>
            <div class="formR">
                <input name="<?php echo Html::getInputName($model, 'paperName') ?>"
                       id="<?php echo Html::getInputId($model, 'paperName') ?>"
                       data-validation-engine="validate[required,maxSize[30]]"
                       class="input_box text" type="text" value="<?php echo $model->paperName ?>"/>
                <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'paperName') ?>
            </div>
        </li>
        <li>
            <div class="formL">
                <label><i></i>所在地区：</label>
            </div>
            <div class="formR">
 <span class="area"><?php echo $personArray["provience"] ?><i>
         &nbsp;&nbsp;<?php echo $personArray["city"] ?></i></span>
            </div>
        </li>
        <li>
            <div class="formL">
                <label>年级：</label>
            </div>
            <div class="formR">
                <?php
                echo Html:: activeDropDownList($model, "gradeId", GradeModel::model()->getListData(),
                    array(
                        'data-validation-engine' => 'validate[required]', "defaultValue" => false, "prompt" => "请选择",
                        'data-prompt-target' => "grade_prompt",
                        'data-prompt-position' => "inline",
                        'data-errormessage-value-missing' => "年级不能为空",
                        'ajax' => array(
                            'url' => Yii::$app->urlManager->createUrl('ajax/get-item-for-grade'),
                            'data' => array('id' => new \yii\web\JsExpression('this.value')),
                            'success' => 'function(html){jQuery("#' . Html::getInputId($model, 'subject') . '").html(html).change();}'
                        ),
                    )); ?>
            </div>
        </li>
        <li>
            <div class="formL">
                <label>科目：</label>
            </div>
            <div class="formR">
                <span class="area"><?php echo $personArray["subject"] ?></span>
            </div>
        </li>
        <li>
            <div class="formL">
                <label>版本：</label>
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
                       name="<?php echo Html::getInputName($model, 'deadLineTime') ?>"

                       data-validation-engine="validate[required,maxSize[30]]"
                       onclick="WdatePicker({dateFmt:'yyyy-MM-dd H:m:s',minDate:'<?php echo date('Y-m-d h:i:s', time()) ?>'});"
                    >
            </div>
        </li>
        <li>
            <div class="formL">
                <label><i></i>涉及的知识点：</label>
            </div>
            <div class="formR">
                <?php
                echo  frontend\widgets\extree\XTree::widget( [
                    'model' => $model,
                    'attribute' => 'knowledgePointId',
                    'options' => [
                        'htmlOptions' => []
                    ]]) ?>
            </div>
        </li>
        <li>
            <div class="formL">
                <label>试卷作者：</label>
            </div>
            <div class="formR">
                <?php echo Html::activeDropDownList($model, 'author', ['0' => "学校", '1' => "老师"], ["class" => "mySel", "defaultValue" => false, "prompt" => "请选择"]) ?>
            </div>
        </li>
        <li>
            <div class="formL">
                <label>试卷简介：</label>
            </div>
            <div class="formR">
                <?php echo Html::activeTextArea($model, 'paperDescribe') ?>
            </div>
        </li>
        <li>
            <div class="formL">
                <label>试卷类型：</label>
            </div>
            <div class="formR">
                <?php echo Html::activeRadioButtonList($model, 'paperType', [1 => '标准', 2 => '小测验', 3 => '作业', 4 => '自定义'], ['separator' => '']) ?>
            </div>
        </li>
    </ul>
    <p class="tc bottomBtnBar">
        <button type="submit" class="nextStepBtn">下一步</button>
    </p>
    <?php echo Html::endForm() ?>
</div>

<!--知识树-->
<script>

    var zNodes = <?php echo $knowledgePoint?>;

    $('.addPointBtn').click(function () {


            popBox.pointTree(zNodes,$(this));


    })
</script>

