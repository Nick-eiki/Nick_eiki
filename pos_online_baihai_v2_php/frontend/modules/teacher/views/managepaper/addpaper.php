    <?php
/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 14-11-17
 * Time: 下午5:21
 */
    use frontend\components\CHtmlExt;
    use frontend\components\helper\AreaHelper;
    use frontend\models\dicmodels\GradeModel;
    use frontend\models\dicmodels\SubjectModel;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;

    /* @var $this yii\web\View */  $this->title="题目管理";
$this->registerJsFile(publicResources() . '/js/My97DatePicker/WdatePicker.js', [ 'position'=>\yii\web\View::POS_HEAD] );
$backend_asset = publicResources_new();
$this->registerJsFile($backend_asset . '/js/pubjs.js'.RESOURCES_VER);
?>

<script>
    $(function(){

        formValidationIni('#tmform');

        //根据选取名称不一样，获取知识点
        $('#addpaperform-subjectid,#addpaperform-gradeid').change(function(){
            $('.pointArea').hide();
            $('.labelList').empty();
            $('.hidVal').val('');
        });

        $('.addPointBtn').click(function(){
            var subjectID = $('#addpaperform-subjectid').val();
            if(subjectID == ''){
                popBox.errorBox('请选择科目！');
                return false;
            }
            var zNodes=[];
            var grade =$('#addpaperform-gradeid').val();
            var subjectID =$('#addpaperform-subjectid').val();

            $.post("<?php echo url("ajaxteacher/get-knowledge")?>", {"subjectID": subjectID, "grade": grade}, function (data) {
                if (data.success) {
                    zNodes = data.data;
                    popBox.pointTree(zNodes,$('.addPointBtn'));
                }
            });

        })
    })


</script>
<div class="grid_19 main_r">

    <div class="notice main_cont test titlePush">
        <div class="title">
            <h4>新建题目组</h4>
        </div>

        <div class="form_list form_style">
            <?php $form =\yii\widgets\ActiveForm::begin( array(
                //'action' => '/teacher/managepaper/addtopic',
                'enableClientScript' => false,'id'=>'tmform'
            ))?>
            <div class="row">
                <div class="formL">
                    <label><i>*</i>题目组名称:</label>
                </div>
                <div class="formR">
                    <input  type="text" class="text txt" name="<?php echo Html::getInputName($model, 'questionTeamName')?>"
                            data-prompt-target="nameError"
                            data-prompt-position="inline"
                            data-validation-engine="validate[required,maxSize[30]]">
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'questionTeamName') ?><span class="altTxt">(30字以内)</span>
                </div>
            </div><span id="nameError" class="errorTxt"  style="left:427px"></span>
            <div class="row">
                <div class="formL">
                    <label><i>*</i>适用地区:</label>
                </div>
                <div class="formR">
                    <?php
                    echo CHtmlExt::dropDownListAjax(Html::getInputName($model, "provience"), $model->provience, ArrayHelper::map(AreaHelper::getProvinceList(), 'AreaID', 'AreaName'), array(
                        "defaultValue" => false, "prompt" => "请选择",
                        'ajax' => array(
                            'url' => Yii::$app->urlManager->createUrl('ajax/get-area'),
                            'data' => array('id' => new \yii\web\JsExpression('this.value')),
                            'success' => 'function(html){jQuery("#' . Html::getInputId($model, "city") . '").html(html).change();}'
                        ),
                        "id" => Html::getInputId($model, "provience")
                    ));
                    ?>
                    <label>省</label>
                    <?php
                    echo CHtmlExt::dropDownListAjax(Html::getInputName($model, "city"), $model->city, ArrayHelper::map(AreaHelper::getCityList($model->provience), 'AreaID', 'AreaName'), array(
                        "defaultValue" => false, "prompt" => "请选择", "id" => Html::getInputId($model, "city"),
                        'ajax' => array(
                            'url' => Yii::$app->urlManager->createUrl('ajax/get-area'),
                            'data' => array('id' => new \yii\web\JsExpression('this.value')),
                            'success' => 'function(html){jQuery("#' . Html::getInputId($model, "county") . '").html(html).change();}'
                        ),
                    ));
                    ?>
                    <label>市</label>
                    <?php
                    echo CHtmlExt::dropDownListAjax(Html::getInputName($model, "county"), $model->county, ArrayHelper::map(AreaHelper::getRegionList($model->city), 'AreaID', 'AreaName'), array(
                        'data-validation-engine' => 'validate[required]', "defaultValue" => false, "prompt" => "请选择", "id" => Html::getInputId($model, "county"),
                        'data-prompt-target' => "diqu_prompt",
                        'data-prompt-position' => "inline"
                    ));
                    ?>
                    <label>区</label>
                    <span id="diqu_prompt" class="errorTxt" style="left:427px"></span>
                </div>
            </div>
            <div class="row">
                <div class="formL">
                    <label><i>*</i>年级:</label>
                </div>
                <div class="formR">


                    <?php echo CHtmlExt::dropDownListAjax(Html::getInputName($model, 'gradeID'), $model->gradeID, GradeModel::model()->getListData(), array(
                        'prompt' => '请选择', 'data-validation-engine' => 'validate[required]',
                        'data-prompt-target' => "gradeError",
                        'data-prompt-position' => "inline",
                        'id' => Html::getInputId($model, 'gradeID'),
                        'ajax' => array(
                            'url' => Yii::$app->urlManager->createUrl('ajax/get-item-for-grade'),
                            'data' => array('id' => new \yii\web\JsExpression('this.value')),
                            'success' => 'function(html){jQuery("#' . Html::getInputId($model, 'subjectID') . '").html(html).change();}'
                        ),
                    ))?>
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'gradeID') ?><span id="gradeError" class="errorTxt" style="left:120px"></span>
                </div>
            </div>
            <div class="row">
                <div class="formL">
                    <label><i>*</i>科目:</label>
                </div>
                <div class="formR">
                    <?php echo CHtmlExt::dropDownListAjax(Html::getInputName($model, 'subjectID'), $model->subjectID, SubjectModel::model()->getListData(), array(
                        'prompt' => '请选择', 'data-validation-engine' => 'validate[required]',
                        'data-prompt-target' => "subjectError",
                        'data-prompt-position' => "inline",
                        'id' => Html::getInputId($model, 'subjectID')
                    ))?>
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'subjectID') ?><span id="subjectError" class="errorTxt" style="left:120px"></span>
                </div>
            </div>
            <div class="row">
                <div class="formL">
                    <label><i>*</i>知识点:</label>
                </div>
                <div class="formR">
                    <?php
                    echo  frontend\widgets\extree\XTree::widget( array(
                        'model' => $model,
                        'attribute' => 'knowledgePoint',
                        'options' => array(

                        ),
                        'htmlOptions' => array('data-validation-engine' => 'validate[required]',
                            'data-prompt-target' => "knowledgePoint_prompt",
                            'data-prompt-position' => "inline",
                            'data-errormessage-value-missing' => "请选择知识点")
                    )) ?>
                    <span id="knowledgePoint_prompt" class="errorTxt" style="left:120px"></span>
                </div>
            </div>
            <div class="row">
                <div class="formL">
                    <label>自定义标签:</label>
                </div>
                <div class="formR">
                    <input  type="text" value="" class="input_box text" name="<?php echo Html::getInputName($model, 'labelName')?>" >
                    <span class="altTxt">(用","分割)</span>
                </div>
            </div>
            <div class="row">
                <div class="formL">
                    <label>题目组描述:</label>
                </div>
                <div class="formR">
                    <?php
                    echo \frontend\widgets\ueditor\MiniUEditor::widget(
                        array(
                            'id' => 'editor',
                            'model' => $model,
                            'attribute' => 'questionTeamMark',
                            'UEDITOR_CONFIG' => array(
                                'initialFrameHeight' => '200',
                                'initialFrameWidth' => '600',
                            ),

                        ));
                    ?>
                </div>
            </div>
            <p class="tc bottomBtnBar">
                <button type="submit" class="btn bg_blue">下一步</button>
            </p>
            <?php \yii\widgets\ActiveForm::end(); ?>
        </div>
    </div>
</div>

<!--主体内容结束-->
