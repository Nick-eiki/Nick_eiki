<div class="input_list input_p">
    <?php
    use frontend\models\dicmodels\SubjectModel;
    use frontend\models\IdentityModel;
    use yii\helpers\Html;

    echo Html::activeHiddenInput($tcItem, "[$key]classID"); ?>
    <?php echo Html::activeTextInput($tcItem, "[$key]className", ["class" => "text text_defined",
        'data-validation-engine' => "validate[required]",
        "onfocus"=>'this.blur()',
        'data-errormessage-value-missing' => "班级不能为空",
    ]) ?>
    <?php  echo Html::activeDropDownList($tcItem, "[$key]identity", IdentityModel::getTeacherIdentity(),
        array(
            "defaultValue" => false, "prompt" => "请选择",
        ));?>
    <?php  echo Html::activeDropDownList($tcItem, "[$key]subjectNumber", SubjectModel::model()->getListData(),
        array(
            "defaultValue" => false, "prompt" => "请选择",
        ));?>
    <a href="javascript:" class="apde_btn apde_js apde_btn_2"
       style="background-color:#999;">删除<i>此班级</i></a>
</div>