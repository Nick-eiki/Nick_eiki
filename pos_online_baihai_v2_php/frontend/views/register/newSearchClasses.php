<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-10
 * Time: 下午2:03
 */
use frontend\components\CHtmlExt;
use yii\helpers\Url;

?>
<div class="subTitleBar">
    <h5>选择年级</h5>
</div>
<div class="popCont">
    <div class="crumbListWrap"><a href="javascript:;" class="txtBtn gray_d creatNewClass">创建新班级</a>

        <div class="crumbList clearfix hide">
            <!--<span class="ac">三年级</span>-->
            <a class="back_selectArea" href="javascript:;">返回上级</a></div>
    </div>
    <dl class="clearfix gradeList">
        <?php foreach ($gradeList as $v) { ?>
            <dd id="<?php echo $v->gradeId; ?>"><?php echo $v->gradeName ?></dd>
        <?php } ?>
    </dl>
    <ul class="resultList myclassList clearfix hide" id="newSearchClassesInfo">

    </ul>

    <div class="myNewClass hide">
        <form id="addClassInfo" action="<?php echo Url::to(['add-class-info']) ?>">
            <div class="form_list">
                <div class="row">
                    <input type="hidden" name="department" value="<?php echo $department; ?>">
                    <input type="hidden" name="schoolId" value="<?php echo $schoolId; ?>">

                    <div class="formL">
                        <label>班级编号</label>
                    </div>
                    <div class="formR">
                        <span class="selectWrap big_sel w130">
                            <?php echo frontend\components\CHtmlExt::dropDownListCustomize('joinYear', '', getClassYears(), ["defaultValue" => true,
                                "prompt" => "请选择",
                            ]) ?>
                            </span>
                        年&nbsp;
                       <span class="selectWrap big_sel w130">
                        <?php echo CHtmlExt::dropDownListCustomize('classNumber', '', getClassNumber(), ["defaultValue" => true,
                            "prompt" => "请选择",
                        ]) ?>

                           </span>
                        班
                       </div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label>班级别名</label>
                    </div>
                    <div class="formR">
                        <?php echo CHtmlExt::textInput('classesName','',['class'=>'text','data-validation-engine' => 'validate[maxSize[30]]', 'data-prompt-position' => 'inline','data-prompt-target'=>'prompts_className'])?>

                        <span id="prompts_className" class="errorTxt"></span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="popBtnArea">
    <button type="button" class="okBtn">确定</button>
    <button type="button" class="cancelBtn">取消</button>
</div>
<script type="text/javascript">
    $('#addClassInfo').validationEngine({'maxErrorsPerField': 1});
</script>