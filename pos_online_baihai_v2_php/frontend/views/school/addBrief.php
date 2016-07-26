<?php
/**
 * Created by 王
 * User: Administrator
 * Date: 14-9-10
 * Time: 上午11:29
 */
use frontend\models\dicmodels\SchoolLevelModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

if ($this->getAction()->getId() == 'addBrief') {
    /* @var $this yii\web\View */  $this->title="添加招生简章";
} else {
    /* @var $this yii\web\View */  $this->title="编辑招生简章";
}

$backend_asset = publicResources_new();
$this->registerJsFile($backend_asset . "/js/jquery.validationEngine-zh_CN.js");
$this->registerJsFile($backend_asset . "/js/jquery.validationEngine.min.js");
$this->registerJsFile($backend_asset . "/js/register.js".RESOURCES_VER);
?>
<!--主体内容开始-->
<script>
    $(function () {
        $('#form1').submit(function () {
            //判断验证是否通过
            if ($(this).validationEngine('validate') == false) {
                return false;
            }
            if (editor.getContentTxt().length == 0) {
                popBox.alertBox('请填写简章内容！');
                return false;
            }
        })
    })
</script>


<div class="main_c clearfix add_x" style="padding-bottom:50px;">

    <div class="addRight">
        <?php if ($this->getAction()->getId() == 'addBrief'): ?>
            <h4>新建招生简章</h4>
        <?php else: ?>
            <h4>编辑招生简章</h4>
        <?php endif; ?>

        <hr>
        <?php $form =\yii\widgets\ActiveForm::begin( array(
            'enableClientScript' => false,
            'id' => 'form1',
        ))?>
        <ul class="form_list">
            <li>
                <div class="formL"><label><i></i>简章名称：</label></div>
                <div class="formR">
                    <input type="text"
                           data-validation-engine="validate[required,maxSize[50]]"
                           value="<?php echo $model->name ?>"
                           name="<?php echo Html::getInputName($model, 'name') ?>"
                           id="<?php echo Html::getInputId($model, 'name') ?>"
                           class="text input_box">
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'name') ?>
                </div>
            </li>
            <li>
                <div class="formL"><label><i></i>学部：</label></div>
                <div class="formR">
                    <?php
                    echo Html::dropDownList(Html::getInputName($model, 'schoolLevel'),
                        $model->schoolLevel,
                        SchoolLevelModel::model()->getListData(),
                        array('id' => Html::getInputId($model, 'schoolLevel'),
                            'data-validation-engine' => 'validate[required,custom[number]]'
                        ));
                    ?>
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'schoolLevel') ?>
                </div>
            </li>
            <li>
                <div class="formL"><label><i></i>年份：</label></div>
                <div class="formR">
                    <?php
                    echo Html::dropDownList(Html::getInputName($model, 'year'),
                        $model->year,
                        ArrayHelper::map(getYears(), 'year', 'year'),
                        array('id' => Html::getInputId($model, 'year'),
                            'data-validation-engine' => 'validate[required,custom[number]]','style'=>'width:73px;'
                        ));
                    ?>
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'year') ?>
                </div>
            </li>
            <li>
                <div class="formL"><label><i></i>简章内容：</label></div>
                <div class="formR" style="width: 700px">
                    <?php
                    echo \frontend\widgets\ueditor\MiniUEditor::widget(
                        array(
                            'id' => 'editor',
                            'model' => $model,
                            'attribute' => 'content',
                            'UEDITOR_CONFIG' => array(
                                'initialFrameHeight' => '150',
                                'autoHeightEnabled'=>false,
                            ),

                        ));
                    ?>
                </div>
            </li>
            <li>
                <div class="formL"></div>
                <div class="formR" style="margin-left:150px;">
                    <?php if ($this->getAction()->getId() == 'addBrief'): ?>
                        <button type="submit" class="btn">添&nbsp;&nbsp;加</button>
                    <?php else: ?>
                        <button type="submit" class="btn">保&nbsp;&nbsp;存</button>
                    <?php endif; ?>
                    <button type="button" class="btn btn_gray" onclick="window.history.go(-1)">取&nbsp;&nbsp;消</button>
                </div>
            </li>
        </ul>
        <?php \yii\widgets\ActiveForm::end() ?>
    </div>
</div>