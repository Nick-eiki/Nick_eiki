<?php
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title="学校-编辑学校信息";
$backend_asset = publicResources_new();
$this->registerCssFile($backend_asset . '/css/schoolPage.css'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/My97DatePicker/WdatePicker.js');
$this->registerJsFile($backend_asset . '/js/jquery.ui.widget.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js');
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);
?>
    <script>
        $(function () {
            $('h2.class_t i').editPlus({target: "h2 span"});
            $('h3.Signature i').editPlus({target: "h3.Signature span"});
            $('h2.class_t').click(function () {
                popBox.successBox();
            })
        })
    </script>
    <!--主体内容开始-->

    <div class="main_c clearfix" style="padding-bottom:50px;">
        <div class="edit_title">
            <ul class="edit_list clearfix">
                <li class="beforeOne">编辑学校信息</li>
            </ul>
        </div>

        <div class="editBox">
            <div style="width:798px;margin:0 auto; display:block" class="edit_Div">
                <?php $form =\yii\widgets\ActiveForm::begin( array(
                    'enableClientScript' => false,
                    'id' => 'form1'
                ))?>
                <ul class="form_list">
                    <li>
                        <div class="formL"><label for="name"><i></i>学校名称：</label></div>
                        <div class="formR">
                            <input type="text" name="<?php echo Html::getInputName($schoolModel, 'schoolName') ?>"
                                   value="<?php echo $schoolSelect->data->schoolName ?>"
                                   data-validation-engine="validate[required]" class="text">

                        </div>
                    </li>
                    <li>
                        <div class="formL"><label for="name">学校别名：</label></div>
                        <div class="formR">
                            <input type="text"
                                   value="<?php echo $schoolSelect->data->nickName ?>"
                                   name="<?php echo Html::getInputName($schoolModel, 'nickName') ?>" class="text">

                        </div>
                    </li>
                    <li>
                        <div class="formL"><label for="name">学校学制：</label></div>
                        <div class="formR">
                            <?php
                            echo Html::dropDownList(Html::getInputName($schoolModel, 'lengthOfSchooling'),
                                $schoolSelect->data->lengthOfSchooling,
                                array("20501" => "六三学制", "20502" => "五四学制", "20503" => "五三学制"),

                                array(
                                    " onchange" => "lengthChange();",

                                    "id" => "lengthOfSchooling",

                                ));
                            ?>
                        </div>
                    </li>
                    <li>
                        <div class="formL"><label>学校学部设置：</label></div>
                        <div class="formR" id="department">
                            <?php $departmentNameArray = explode(",", $schoolSelect->data->departmentName);
                            foreach ($departmentNameArray as $v) {
                                echo $v;
                                echo "&nbsp";
                            }
                            ?>
                        </div>
                    </li>
                    <li>
                        <div class="formL"><label for="name">上传头像：</label></div>
                        <div class="formR">
                            <div class="up_pic">
                                <span id="uploadPicBtn">上传头像</span>
                                <em>支持文件不大于2M的jpg、gif、png格式的图片。</em>
                                <input class="faceIcon"
                                       value="<?php echo $schoolSelect->data->logoUrl ?>"
                                       name="<?php echo Html::getInputName($schoolModel, 'faceIcon') ?>"
                                       type="hidden"/>
                            </div>
                            <div class="up_img">
                                <img id="face-img" src="<?php echo $schoolSelect->data->logoUrl ?>" alt="">
                            </div>

                        </div>
                    </li>
                    <li>


                        <p class="fomr_p fo_ap" style="display: none" id="beginTimeShow">
                            <span class="">新学制开始执行时间:</span>
                            <select id="beginTime" name="<?php echo Html::getInputName($schoolModel, 'beginTime') ?>"
                                    style="display: none">

                            </select>
                        <span>
                        <?php
                        echo Html::dropDownList(Html::getInputName($schoolModel, 'beginTime'),
                            '',
                            array("" => "请选择", "2014" => "14级", "2013" => "13级"),
                            array(
                                "id" => "beginTime"
                            ));
                        ?>
                            </span>

                        </p>

                    </li>

                    <li>
                        <div class="formL"><label for="name">学校简介：</label></div>
                        <div class="formR">
                            <div class="word_box" style="width:500px;"> <?php
                                echo \frontend\widgets\ueditor\MiniUEditor::widget(
                                    array(
                                        'id' => 'editor',
                                        'model' => $schoolModel,
                                        'attribute' => 'brief',
                                        'UEDITOR_CONFIG' => array(
                                        ),

                                    ));
                                ?></div>
                        </div>
                    </li>
                </ul>
                <hr>
                <p class="conserve">
                    <button class="btn B_btn110">保&nbsp;&nbsp;存</button>
                </p>
                <?php \yii\widgets\ActiveForm::end() ?>
            </div>


        </div>


    </div>


    <!--弹出框-->
<?php echo $this->render("_faceUpload") ?>


    <!--主体内容结束-->
    <script>
        function lengthChange() {
            $("#beginTimeShow").show();
        }
    </script>
<?php
$list = explode(",", $schoolSelect->data->department);
foreach ($list as $v) {

    if ($v == 20201) {
        ?>
        <script>

            $("#department").find("i").eq(0).addClass("myCheckboxChecked");

        </script>
    <?php
    } elseif ($v == 20202) {
        ?>
        <script>

            $("#department").find("i").eq(1).addClass("myCheckboxChecked");

        </script>
    <?php
    } elseif ($v == 20203) {
        ?>
        <script>

            $("#department").find("i").eq(2).addClass("myCheckboxChecked");

        </script>
    <?php
    }
}
?>