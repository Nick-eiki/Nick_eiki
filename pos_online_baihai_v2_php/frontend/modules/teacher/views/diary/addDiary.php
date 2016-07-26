<?php
use frontend\services\pos\pos_ListenTeachingService;
use frontend\services\pos\pos_TaskCourseInfoService;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title="教师--写日记";
$backend_asset = publicResources();
;
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
?>
<script>
    $(function () {

        $('#form1').validationEngine({
            promptPosition:"centerRight",
            maxErrorsPerField:1,
            showOneMessage:true,
            addSuccessCssClassToField:'ok'
        });
        tingkeTitle = $('#<?php echo Html::getInputId($model, 'tingkeTitle') ?>');
        ketiTitle = $('#<?php echo Html::getInputId($model, 'ketiTitle') ?>');
        //默认是随笔时隐藏其他的
        if ('<?php echo $model->type?>' == 2) {
            tingkeTitle.parents('li').hide();
            ketiTitle.parents('li').show();
        } else if ('<?php echo $model->type?>' == 3) {
            tingkeTitle.parents('li').hide();
        }

        $('#form1').submit(function () {
            //判断验证是否通过

            if ($(this).validationEngine('validate') == false) {
                return false;
            }
            if (editor.getContentTxt().length == 0) {
                alert('请填写课程介绍！');
                return false;
            }
            return true;
        })
    });

    //通过类别改变听课标题或课题
    function changeDiaryType(self) {
        if ($(self).val() == 1) {//听课
            tingkeTitle.parents('li').show();
            ketiTitle.parents('li').hide();
        }
        if ($(self).val() == 2) {//课题
            tingkeTitle.parents('li').hide();
            ketiTitle.parents('li').show();
        } else if ($(self).val() == 3) {//随笔
            tingkeTitle.parents('li').hide();
            ketiTitle.parents('li').hide();
        }
        //清空原有值
        tingkeTitle.val('');
        ketiTitle.val('');
    }
</script>

<div class="currentRight grid_16 push_2 cp_email_cl">
    <div class="crumbs"><a href="#">日记本</a> >> <a href="#">写日记</a></div>
    <div style="width:798px;margin:10px auto; display:block" class="edit_Div">
        <?php $form =\yii\widgets\ActiveForm::begin( array(
            'enableClientScript' => false,
            'id' => 'form1'
        ))?>
        <ul class="form_list">
            <li>
                <div class="formL">
                    <label><i></i>日记标题：</label>
                </div>
                <div class="formR">
                    <input type="text"
                           data-validation-engine="validate[required,maxSize[30]]"
                           value="<?php echo $model->name ?>"
                           name="<?php echo Html::getInputName($model, 'name') ?>"
                           id="<?php echo Html::getInputId($model, 'name') ?>"
                           class="text input_box">
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'name') ?>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>日记类别：</label>
                </div>
                <div class="formR">
                    <?php
                    echo Html:: dropDownList(Html::getInputName($model, "type"), $model->type, array('1' => '评课', '2' => '课题', '3' => '随笔'), array(
                        "defaultValue" => false,
                        "id" => Html::getInputId($model, "type"),
                        'onchange' => 'changeDiaryType(this)'
                    ));
                    ?>
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'type') ?>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>听课标题：</label>
                </div>
                <div class="formR">
                    <?php
                    $listen = new pos_ListenTeachingService();
                    $result = $listen->queryListenTeachNp(user()->id);
                    echo Html:: dropDownList(Html::getInputName($model, "tingkeTitle"), $model->tingkeTitle, ArrayHelper::map($result->data->list,'ID','title'), array(
                        "defaultValue" => false, 'prompt' => '请选择',
                        'data-validation-engine' => 'validate[required]',
                        "id" => Html::getInputId($model, "tingkeTitle")
                    ));
                    ?>
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'tingkeTitle') ?>
                </div>
            </li>
            <li class="hide">
                <div class="formL">
                    <label><i></i>课题：</label>
                </div>
                <div class="formR">
                    <?php
                                        $task = new pos_TaskCourseInfoService();
                                        $result = $task->taskCourseSearchByMember(user()->id);

                                        echo Html:: dropDownList(Html::getInputName($model, "ketiTitle"), $model->ketiTitle, ArrayHelper::map($result->taskCourseList, 'courseID', 'courseName','teachingGroupName'), array(
                                            "defaultValue" => false, 'prompt' => '请选择',
                                            'data-validation-engine' => 'validate[required]',
                                            "id" => Html::getInputId($model, "ketiTitle")
                                        ));
                                        ?>
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'ketiTitle') ?>
                </div>
            </li>

            <li>
                <div class="formL">
                    <label><i></i>详情：</label>
                </div>
                <div class="formR">
                    <?php
                    echo \frontend\widgets\ueditor\MiniUEditor::widget(
                        array(
                            'id' => 'editor',
                            'model' => $model,
                            'attribute' => 'content',
                            'UEDITOR_CONFIG' => array(
                                'initialContent' => '',
                                'initialFrameHeight' => '200',
                                'initialFrameWidth' => '600',
                            ),

                        ));
                    ?>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label></label>
                </div>
                <div class="formR">
                	<button type="submit" class="bg_red_d B_btn110">确&nbsp;&nbsp;定</button>
                </div>
            </li>
        </ul>
        <?php \yii\widgets\ActiveForm::end() ?>
    </div>
</div>

<!--弹出框pop--------------------->
<script type="text/javascript">
    //点击更多显示全部
    $(function () {
        $('.morejs').live('click', function () {
            $('.classHeight').css('display', 'block');
        })

    })


</script>