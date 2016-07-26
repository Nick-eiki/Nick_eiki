<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/11/14
 * Time: 17:58
 */
use frontend\models\dicmodels\NewTypeModel;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
$this->title = "发布资讯";
$this->registerJsFile(publicResources() . '/js/My97DatePicker/WdatePicker.js', ['position' => \yii\web\View::POS_HEAD]);

$backend_asset = publicResources();;
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/ztree/jquery.ztree.all-3.5.min.js');
$this->registerJsFile($backend_asset . '/js/ztree/zTreeStyle/zTreeStyle.css'.RESOURCES_VER);
?>

<script>
    $(function () {
        $(function () {
            $('#form1').submit(function () {
                //判断验证是否通过
                if ($(this).validationEngine('validate') == false) {
                    return false;
                }

                if (editor.getContentTxt().length == 0) {
                    popBox.alertBox('请填写课程介绍！');
                    return false;
                }
            })
        })
    })
</script>

<!--主体内容开始-->
<div class="currentRight grid_16 push_2 make_testpaper">
    <div class="notice information">
        <div class="noticeH noticeB clearfix">
            <h3 class="h3L">发布资讯</h3>
        </div>
        <?php
        /** @var $form CActiveForm */
        $form = ActiveForm::begin([
            'enableClientScript' => false,
            'id' => 'form1'
        ])
        ?>
        <ul class="form_list">
            <li>
                <div class="formL">
                    <label><i></i>标题：</label>
                </div>
                <div class="formR">
                    <input id="name" type="text" class="text"
                           name="<?php echo Html::getInputName($model, 'informationTitle') ?>"
                           data-validation-engine="validate[required,maxSize[30]]">
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'informationTitle') ?>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>资讯类型：</label>
                </div>
                <div class="formR">
                    <?php
                    echo Html::dropDownList(Html::getInputName($model, 'informationType'),
                        $model->informationType,
                        NewTypeModel::model()->getListData(),
                        array(
                            'id' => Html::getInputId($model, 'informationType'),
                            "prompt" => "请选择",
                            'data-validation-engine' => 'validate[required,custom[number]]'
                        ));
                    ?>
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'informationType') ?>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>关键词：</label>
                </div>
                <div class="formR">
                    <input id="name" type="text" class="text"
                           name="<?php echo Html::getInputName($model, 'informationKeyWord') ?>"
                           data-validation-engine="validate[required,maxSize[30]]">
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'informationKeyWord') ?>
                    <span class="textc">词语之间请用“|”隔开</span>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>新闻内容：</label>
                </div>
                <div class="formR">
                    <?php
                    echo \frontend\widgets\ueditor\MiniUEditor::widget(
                        array(
                            'id' => 'editor',
                            'model' => $model,
                            'attribute' => 'informationContent',
                            'UEDITOR_CONFIG' => array(
                                'initialContent' => '',
                                'initialFrameHeight' => '120',
                                'initialFrameWidth' => '480',
                            ),
                        ));
                    ?>
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'informationContent') ?>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label></label>
                </div>
                <div class="formR">
                    <button type="submit" class="w120">确 定</button>
                </div>
            </li>
        </ul>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<!--主体内容结束-->

