<?php
/**
 * Created by unizk.
 * User: ysd
 * Date: 2015/4/17
 * Time: 18:32
 */
use frontend\components\CHtmlExt;
use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\DegreeModel;
use frontend\models\dicmodels\EditionModel;
use frontend\models\dicmodels\GradeModel;
use frontend\models\dicmodels\SubjectModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title='修改拍照录题';

?>
<script>
    $(function(){

        $('#upWork').validationEngine({
            validateNonVisibleFields:true,
            promptPosition:"centerRight",
            maxErrorsPerField:1,
            showOneMessage:true,
            addSuccessCssClassToField:'ok'
        });

        $('.confirm').click(function(){

            var up_img = $("input[name='picurls[]']").size();
            if(up_img == 0){
                popBox.alertBox('请上传题目详情！');
                return false;
            }
        })
    })
</script>
<div class="grid_19 main_r">
    <div class="main_cont test justifying">
        <div class="title"> <a href="<?= url('teacher/searchquestions/knowledge-point-questions');?>" class="txtBtn backBtn"></a>
            <h4>修改拍照录题</h4>
        </div>
        <br>
        <?php echo Html::beginForm(['/teacher/testpaper/modify-camera-upload-new-topic','id'=>app()->request->getParam('id')],'post',['id'=>'upWork']);?>
        <div class="form_list  form_style">
            <div class="row">
                <div class="formL">
                    <label><i>*</i>适用地区:</label>
                </div>
                <div class="formR">
                    <?php
                    echo CHtmlExt::dropDownListAjax(Html::getInputName($model, "provience"), $model->provience, ArrayHelper::map(AreaHelper::getProvinceList(), 'AreaID', 'AreaName'), array(
                        "defaultValue" => false, "prompt" => "请选择",
                        'ajax' => [
                            'url' => Yii::$app->urlManager->createUrl('ajax/get-area'),
                            'data' => ['id' => new \yii\web\JsExpression('this.value')],
                            'success' => 'function(html){jQuery("#' . Html::getInputId($model, "city") . '").html(html).change();}'
                        ],
                        "id" => Html::getInputId($model, "provience")
                    ));
                    ?>
                    <label>省</label>
                    <?php
                    echo CHtmlExt::dropDownListAjax(Html::getInputName($model, "city"), $model->city, ArrayHelper::map(AreaHelper::getCityList($model->provience), 'AreaID', 'AreaName'), array(
                        "defaultValue" => false, "prompt" => "请选择", "id" => Html::getInputId($model, "city"),
                        'ajax' => [
                            'url' => Yii::$app->urlManager->createUrl('ajax/get-area'),
                            'data' => ['id' => new \yii\web\JsExpression('this.value')],
                            'success' => 'function(html){jQuery("#' . Html::getInputId($model, "country") . '").html(html).change();}'
                        ],
                    ));
                    ?>
                    <label>市</label>
                    <?php
                    echo CHtmlExt::dropDownListAjax(Html::getInputName($model, "country"), $model->country, ArrayHelper::map(AreaHelper::getRegionList($model->city), 'AreaID', 'AreaName'), array(
                        'data-validation-engine' => 'validate[required]', "defaultValue" => false, "prompt" => "请选择", "id" => Html::getInputId($model, "country"),
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
                    <?php echo CHtmlExt::dropDownListAjax(Html::getInputName($model, 'gradeid'), $model->gradeid, GradeModel::model()->getListData(), array(
                        'prompt' => '请选择',
                        'data-validation-engine' => 'validate[required]',
                        'data-prompt-target' => "grade_prompt",
                        'data-prompt-position' => "inline",
                        'id' => Html::getInputId($model, 'gradeid'),
                        'ajax' => [
                            'url' => Yii::$app->urlManager->createUrl('ajax/get-item-for-grade'),
                            'data' => ['id' => new \yii\web\JsExpression('this.value')],
                            'success' => 'function(html){jQuery("#' . Html::getInputId($model, 'subjectid') . '").html(html).change();}'
                        ],
                    ))?>
                    <span id="grade_prompt"  class="errorTxt" style="left:130px"></span>
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'gradeid') ?>
                </div>
            </div>
            <div class="row">
                <div class="formL">
                    <label><i>*</i>科目:</label>
                </div>
                <div class="formR">
                    <?php echo CHtmlExt::dropDownListAjax(Html::getInputName($model, 'subjectid'), $model->subjectid, SubjectModel::model()->getListData(), array(
                        'prompt' => '请选择', 'data-validation-engine' => 'validate[required]',
                        'data-prompt-target' => "subject_prompt",
                        'data-prompt-position' => "inline",
                        'id' => Html::getInputId($model, 'subjectid'),
                        'ajax' => array(
                            'url' => Yii::$app->urlManager->createUrl('ajax/get-version'),
                            'data' => array('subject' => new \yii\web\JsExpression('this.value')),
                            'success' => 'function(html){jQuery("#' . Html::getInputId($model, "versionid") . '").html(html).change(); }'
                        )
                    ))?>
                    <span id="subject_prompt" class="errorTxt" style="left:130px"></span>
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'subjectid') ?>
                </div>
            </div>
            <div class="row">
                <div class="formL">
                    <label><i>*</i>版本:</label>
                </div>
                <div class="formR">
                    <?php echo CHtmlExt::dropDownListAjax(Html::getInputName($model, 'versionid'), $model->versionid, EditionModel::model()->getListData(), array(
                        'prompt' => '请选择', 'data-validation-engine' => 'validate[required]',
                        'data-prompt-target' => "textbookVersion_prompt",
                        'data-prompt-position' => "inline",
                        'id' => Html::getInputId($model, 'versionid')
                    ))?>
                    <span id="textbookVersion_prompt" class="errorTxt" style="left:130px"></span>
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'versionid') ?>
                </div>
            </div>
            <div class="row">
                <div class="formL">
                    <label><i>*</i>难易程度:</label>
                </div>
                <div class="formR">
                    <?php echo CHtmlExt::dropDownListAjax(Html::getInputName($model, 'complexity'), $model->complexity, DegreeModel::model()->getListData(), array(
                        'prompt' => '请选择', 'data-validation-engine' => 'validate[required]',
                        'data-prompt-target' => "complexity_prompt",
                        'data-prompt-position' => "inline",
                        'id' => Html::getInputId($model, 'complexity')
                    ))?>
                    <span id="complexity_prompt" class="errorTxt" style="left:130px"></span>
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'complexity') ?>
                </div>
            </div>
            <div class="row">
                <div class="formL">
                    <label><i>*</i>题目详情</label>
                </div>
                <div class="formR " style="width:700px">
                    <div class="imgFile">
                        <ul class="up_test_list clearfix ">
                            <?php
                            $images = $model->content;
                            if(isset($images) && !empty($images)){
                                $image = explode(',',$images);
                                foreach($image as $val){
                                    ?>
                                    <li>
                                        <input type="hidden" id="picurls" name="picurls[]" value="<?=$val; ?>">
                                        <img src="<?=$val; ?>" alt="">
                                        <span class="delBtn"></span>
                                    </li>
                                <?php  }}?>
                            <li class="more">
                                <?php
                                $t1 = new frontend\widgets\xupload\models\XUploadForm;
                                /** @var $this BaseController */
                                echo  \frontend\widgets\xupload\XUploadSimple::widget( array(
                                    'url' => Yii::$app->urlManager->createUrl("upload/pic"),
                                    'model' => $t1,
                                    'attribute' => 'file',
                                    'autoUpload' => true,
                                    'multiple' => true,
                                    'options' => array(
                                        'acceptFileTypes' => new \yii\web\JsExpression('/(\.|\/)(jpg|png|jpeg)$/i'),
                                        "done" => new \yii\web\JsExpression('done'),
                                        "processfail" => new \yii\web\JsExpression('function (e, data) {var index = data.index,file = data.files[index]; if (file.error) {alert(file.error);}}')
                                    ),
                                    'htmlOptions' => array(
                                        'id' => 'fileupload',
                                    )
                                ));
                                ?>
                            </li>
                            <input class="paperRoute" name="<?php echo Html::getInputName($model, 'content')?>" type="hidden"/>
                        </ul>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="formL">
                    <label>答案与解析</label>
                </div>
                <div class="formR" style="width:700px" >
                    <div class="imgFile">
                        <ul class="up_test_list clearfix ">
                            <?php
                            $images = $model->answerContent;
                            if(isset($images) && !empty($images)){
                                $image = explode(',',$images);
                                foreach($image as $val){
                                    ?>
                                    <li>
                                        <input type="hidden" id="imgurls" name="imgurls[]" value="<?=$val; ?>">
                                        <img src="<?=$val; ?>" alt="">
                                        <span class="delBtn"></span>
                                    </li>
                                <?php  }}?>
                            <li class="more">
                                <?php
                                $t1 = new frontend\widgets\xupload\models\XUploadForm;
                                /** @var $this BaseController */
                                echo  \frontend\widgets\xupload\XUploadSimple::widget( array(
                                    'url' => Yii::$app->urlManager->createUrl("upload/pic"),
                                    'model' => $t1,
                                    'attribute' => 'file',
                                    'autoUpload' => true,
                                    'multiple' => true,
                                    'options' => array(
                                        'acceptFileTypes' => new \yii\web\JsExpression('/(\.|\/)(jpg|png|jpeg)$/i'),
                                        "done" => new \yii\web\JsExpression('doneTwo'),
                                        "processfail" => new \yii\web\JsExpression('function (e, data) {var index = data.index,file = data.files[index]; if (file.error) {alert(file.error);}}')
                                    ),
                                    'htmlOptions' => array(
                                        'id' => 'imgupload',
                                    )
                                ));
                                ?>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="formL">
                    <label></label>
                </div>
                <div class="formR submitBtnBar">
                    <button type="submit" class="bg_blue btn40 confirm">保存题目</button>
                </div>
            </div>
        </div>
        <?=Html::endForm();?>
    </div>
</div>

<!--主体end-->

<script>
    k=0;
    done = function(e, data) {

        $.each(data.result, function (index, file) {
            k++;
            if(file.error){
                popBox.errorBox(file.error);
                return ;
            }
            var url = $('.paperRoute').val();
            if (url.length == 0) {
                $('.paperRoute').val(file.url);
            } else {
                $('.paperRoute').val(url + ',' + file.url);
            }

            $('<li><input type="hidden" id="picurls" name="picurls[]" value="' + file.url + '" /> <img src="' + file.url + '" alt=""><span class="delBtn"></span></li>').insertBefore( $(e.target).parent());

        });
    };

    doneTwo = function(e, data) {

        $.each(data.result, function (index, file) {
            k++;
            if(file.error){
                popBox.errorBox(file.error);
                return ;
            }
            $('<li><input type="hidden" id="imgurls" name="imgurls[]" value="' + file.url + '" /> <img src="' + file.url + '" alt=""><span class="delBtn"></span></li>').insertBefore( $(e.target).parent());

        });
    };
    $('#form1').validationEngine({
        validateNonVisibleFields:true,
        promptPosition:"centerRight",
        maxErrorsPerField:1,
        showOneMessage:true,
        addSuccessCssClassToField:'ok'
    });

</script>