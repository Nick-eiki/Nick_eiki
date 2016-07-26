<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-8
 * Time: 下午8:43
 */
use frontend\components\CHtmlExt;
use frontend\models\IdentityModel;
use yii\helpers\Html;

?>
<li class="input_p">
    <?php echo CHtmlExt::activeHiddenInput($tcItem, "[$key]classID", ['class' => 'clearinput']); ?>
    <?php echo CHtmlExt::activeTextInput($tcItem, "[$key]className", ["class" => "text w120 sel_class_input clearinput",
        'data-validation-engine' => "validate[required]",
        "readOnly" => 'readOnly',
        'data-errormessage-value-missing' => "班级不能为空",
        'data-prompt-position'=>"inline",
        'data-prompt-target'=>CHtmlExt::getInputId($tcItem, "[$key]className_prompt")
    ]) ?>
    <span id="<?php echo CHtmlExt::getInputId($tcItem, "[$key]className_prompt"); ?>" class="errorTxt" ></span>

    <span class="selectWrap big_sel" style="width:120px">
          <?php  echo frontend\components\CHtmlExt::dropDownListCustomize(Html::getInputName($tcItem, "[$key]identity"),$tcItem->identity, IdentityModel::getTeacherIdentity(),
              array(
                  "defaultValue" => true,
              ));?>
        </span>
    <button type="button" class="delBtn <?= ($key === 0  ? 'hide' : '') ?> " ></button>
</li>
