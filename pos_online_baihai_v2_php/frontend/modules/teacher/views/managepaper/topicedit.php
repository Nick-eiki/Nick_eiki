<?php
/**
 * Created by PhpStorm.
 * User: liquan
 * Date: 2014/11/21
 * Time: 13:58
 */
use frontend\components\CHtmlExt;
use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\DegreeModel;
use frontend\models\dicmodels\EditionModel;
use frontend\models\dicmodels\EliteModel;
use frontend\models\dicmodels\FromModel;
use frontend\models\dicmodels\GradeModel;
use frontend\models\dicmodels\ItemTypeModel;
use frontend\models\dicmodels\SubjectModel;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */  $this->title='课程管理-题目修改';
$backend_asset = publicResources_new();
;
$this->registerJsFile($backend_asset . '/js/My97DatePicker/WdatePicker.js', [ 'position'=>\yii\web\View::POS_HEAD] );
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . "/js/ztree/jquery.ztree.all-3.5.min.js");
$this->registerJsFile($backend_asset . '/js/preview.js'.RESOURCES_VER);
$this->registerCssFile($backend_asset . '/css/addquestion.css'.RESOURCES_VER);

?>

<!--知识树-->
<script>
    $(function(){

        var zNodes =[];
        $('.addPoint2').live('click',function(){
            var subjectId = $("#item").val();
            var grade = $("#gradeId").val();
            var url = "/ajaxteacher/getKnowledge";
            $this=$(this);
            $.post(url,{'subjectID':subjectId,'grade':grade},function(msg){
                var zNodes =msg.data;
                popBox.pointTree(zNodes,$this);
            });
        });
        $("#save").click(function(){
            $('#form_id').validationEngine();
            var type = $("#type_0").val();
            if(type < 5){
                if ($('#form_id').validationEngine('validate')) {
                    if($("#question_0").val() == ""){
                        popBox.alertBox('题目不能为空！');
                        return false;
                    }
                    if($(".hidVal").val() == ""){
                        popBox.alertBox('知识点不能为空！');
                        return false;
                    }
                    if($(".anwser").val() == ""){
                        popBox.alertBox('答案不能为空！');
                        return false;
                    }
                    if($("#note").val() == ""){
                        popBox.alertBox('解析不能为空！');
                        return false;
                    }
                }
            }

        })
    })
</script>
<div class="currentRight grid_16 push_2 topic_input">
<div class="noticeH clearfix">
    <h3 class="h3L">题目录入</h3>
</div>
<hr>

<br>

<div class="item c_uploadTestPaper c_subject">

<div class="registration_left  registration_left_auto">
<?php $form =\yii\widgets\ActiveForm::begin( array(
    'action' => '/teacher/managepaper/topicedit',
    'enableClientScript' => false,
    'id' => 'form_id',
))?>
<ul class="box_data_list">
    <li class="clearfix">
        <label class="species"><i>*</i>题目售价:</label>
        <div class="word_box">
            <input name="price" type="text" value="" class="input_text text" data-validation-engine="validate[required,custom[number]]" >
            <span class="prompt_y">元</span>
        </div>
    </li>

    <li class="clearfix">
        <label class="species"><i>*</i>适用地区:</label>
        <div class="box_select">
            <?php
            echo Html:: dropDownList("provience", '', ArrayHelper::map(AreaHelper::getProvinceList(), 'AreaID', 'AreaName'), array(
                "defaultValue" => false, "prompt" => "请选择 ",
                'ajax' => array(
                    'url' => Yii::$app->urlManager->createUrl('ajax/get-area'),
                    'data' => array('id' => new \yii\web\JsExpression('this.value')),
                    'success' => 'function(html){jQuery("#city").html(html).change();}'

                ),
                "id" => "provience",
            ));
            ?>
            <label>省</label>
            <?php
            echo Html:: dropDownList("city", '', array(), array(
                "defaultValue" => false, "prompt" => "请选择",
                "id" => "city",
                'ajax' => array(
                    'url' => Yii::$app->urlManager->createUrl('ajax/get-area'),
                    'data' => array('id' => new \yii\web\JsExpression('this.value')),
                    'success' => 'function(html){jQuery("#county").html(html).change();}'
                )
            ));
            ?>
            <label>市</label>
            <?php
            echo Html:: dropDownList("county", '', array(), array(
                'data-validation-engine' => 'validate[required]',
                'data-errormessage-value-missing'=> "请选择地区",
                "defaultValue" => false, "prompt" => "请选择",
                "id" => "county"
            ));
            ?>
        </div>

    </li>
    <li class="clearfix">
        <label class="species"><i>*</i>适合年级:</label>
        <div class="box_select">
            <?php
            echo CHtmlExt::dropDownListAjax('grade',  '',
                GradeModel::model()->getListData(),
                array(
                    "id"=>"gradeId",
                ));
            ?>
        </div>
    </li>

    <li class="clearfix">
        <label class="species"><i>*</i>科目:</label>
        <div class="box_select">

            <?php
            echo CHtmlExt::dropDownListAjax('item',  '',
                SubjectModel::model()->getListData(),
                array(
                    'id'=>"item",
                ));
            ?>
        </div>
    </li>
    <li class="clearfix">
        <label class="species"><i>*</i>版本:</label>
        <div class="box_select">
            <?php
            echo CHtmlExt::dropDownListAjax('banben',  '',
                EditionModel::model()->getListData(),
                array(
                    'id'=>'banben',
                ));
            ?>
        </div>
    </li>

    <li class="clearfix">
        <label class="species"><i>*</i>出处:</label>
        <div class="box_select">
            <?php
            echo CHtmlExt::dropDownListAjax('from',  '',
                FromModel::model()->getListData(),
                array(
                    'data-validation-engine' => 'validate[required]',
                ));
            ?>
        </div>
    </li>
    <li class="clearfix">
        <label class="species"><i>*</i>年份:</label>
        <div class="box_select">
            <select class="boxnian" id="data" name="year">
                <option value="2013">2013</option>
                <option value="2014">2014</option>
            </select>
        </div>
    </li>

    <li class="clearfix">
        <label class="species"><i>*</i>名校:</label>
        <div class="box_select">
            <?php
            echo CHtmlExt::dropDownListAjax('school',  '',
                EliteModel::model()->getListData(),
                array(
                    'data-validation-engine' => 'validate[required]',
                ));
            ?>

        </div>
    </li>
    <li class="clearfix">
        <label class="species"><i>*</i>难易程度:</label>
        <div class="box_select">
            <?php
            echo CHtmlExt::dropDownListAjax('norm',  '',
                DegreeModel::model()->getListData(),
                array('data-validation-engine' => 'validate[required]',
                ));
            ?>

        </div>
    </li>

    <li class="clearfix">
        <label class="species"><i>*</i>自定义标签:</label>
        <div class="word_box">
            <input type="text" name="biaoqian" data-validation-engine="validate[required]" value="" class="input_box text" style="width:270px" id="zdy">
            <label class="text_color">(多个标签用“,”隔开)</label>
        </div>
    </li>
</ul>
<!--整体-->

<div class="itemsBox">
    <div id="questionBox_0">
        <ul class="box_data_list">

            <li class="clearfix">
                <label class="species"><i>*</i>知识点:</label>
                <div id="tree_0" class="word_box treeParent">
                    <button type="button" class="addPoint2">编辑知识点</button>
                    <div class="pointArea hide">
                        <input  name="hidval" class="hidVal" type="hidden" value="">
                        <h6>已选中知识点：</h6>
                        <ul class="labelList"></ul>
                    </div>
                </div>
            </li>


            <li class="clearfix">
                <label class="species"><i>*</i>题型:</label>
                <div class="box_select">
                    <?php
                    echo CHtmlExt::dropDownListAjax('tixing',  '',
                        ItemTypeModel::model()->getListData(),
                        array('data-validation-engine' => 'validate[required]',
                            'id'=>"type_0",
                            'class'=>"xuanzhe02",
                        ));
                    ?>
                    <!--<select id="type_0" class="xuanzhe02" name="tixing">
                        <option value="1">单选</option>
                        <option value="2">多选</option>
                        <option value="3">填空题</option>
                        <option value="4">问答题</option>
                        <option value="5">应用题</option>
                        <option value="6">完形填空</option>
                        <option value="7">阅读理解</option>
                    </select>-->
                </div>
            </li>
            <li class="clearfix">
                <label class="species"><i>*</i>题目:</label>
                <div class="timu"><textarea name="timu" id="question_0" style="width:300px; height:200px;"></textarea></div>
            </li>

            <li class="clearfix question_main_box_0 bdl_bj qmb_0">

            </li>
            <li class="clearfix">
                <label class="species"><i>*</i>解析:</label>
                <div class="jeixi"><textarea name="explan" id="note" style="width:300px; height:200px;"></textarea></div>
            </li>
        </ul>
    </div>
</div>

<div class="clearfix">
    <label class="species"></label>
    <div class="prompt prompt02 prompt03">
        <button type="submit" class="p_reservation" id="save" value="">保存题目</button>
        <button type="button" class="p_reservation p_blue preview_btn" id="preview_button">题目预览</button>
        <button type="button" class="p_reservation p_gray">保存到草稿箱</button>
    </div>
</div>
<!--整体-->
<?php \yii\widgets\ActiveForm::end(); ?>

</div>
</div>
</div>
<!--主体内容结束-->

