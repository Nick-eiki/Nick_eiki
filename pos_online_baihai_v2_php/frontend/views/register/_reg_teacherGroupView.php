<div class="input_list input_p">
    <?php
    use frontend\models\dicmodels\TeachingResearchDutyModel;
    use yii\helpers\Html;

    echo Html::activeHiddenInput($teacherGroup, "[$key]groupID"); ?>
    <?php echo frontend\components\CHtmlExt::validationEngineError($teacherGroup, "[$key]groupID"); ?>

    <?php echo Html::activeTextInput($teacherGroup, "[$key]groupName", ["class" => "text text_defined text_l text_t",
        'data-validation-engine' => "validate[required]",
        "onfocus"=>'this.blur()',
        'data-errormessage-value-missing' => "教研组不能为空"
    ]) ?>
    <?php echo frontend\components\CHtmlExt::validationEngineError($teacherGroup, "[$key]groupName"); ?>
    <?php  echo Html::activeDropDownList($teacherGroup, "[$key]identity", TeachingResearchDutyModel::model()->getListData(),
        array(
            "defaultValue" => false, "prompt" => "请选择",
            'data-validation-engine' => "validate[required]",
            'data-errormessage-value-missing' => "请选择"
        ));?>
    <?php echo frontend\components\CHtmlExt::validationEngineError($teacherGroup, "[$key]identity"); ?>
    <a href="javascript:"
       class="apde_btn apde_btn_c apde_btn_2"
       style="background-color:#999;"><i>删除</i>教研组</a>
</div>