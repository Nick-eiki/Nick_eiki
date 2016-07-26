<?php

/**
 * Created by PhpStorm.
 * User: unizk
 * Date: 14-10-30
 * Time: 下午4:47
 */
use frontend\components\CHtmlExt;
use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\CapacityModel;
use frontend\models\dicmodels\DegreeModel;
use frontend\models\dicmodels\EditionModel;
use frontend\models\dicmodels\FromModel;
use frontend\models\dicmodels\GradeModel;
use frontend\models\dicmodels\ItemTypeModel;
use frontend\models\dicmodels\KnowledgePointModel;
use frontend\models\dicmodels\QuesLevelModel;
use frontend\models\dicmodels\SubjectModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title='课程管理-题目管理';
$backend_asset = publicResources();
;
$this->registerJsFile($backend_asset . '/js/My97DatePicker/WdatePicker.js', [ 'position'=>\yii\web\View::POS_HEAD] );
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/ztree/jquery.ztree.all-3.5.min.js');
$this->registerCssFile($backend_asset . '/css/addquestion.css'.RESOURCES_VER);

?>
    <!--知识树-->
    <script>
        $(function(){
            var zNodes = [];
            var url = "/ajaxteacher/getKnowledge";
            $('.addPoint2').live('click',function(){
                var subjectId = $("#subjectID").val();
                var grade = $("#gradeID").val();
                $this=$(this);
                $.post(url,{'subjectID':subjectId,'grade':grade},function(msg){
                    var zNodes =msg.data;
                    popBox.pointTree(zNodes,$this);
                });
            });


            $('.p_reservation').live('click',function(){
                if ($('#form_id').validationEngine('validate')) {
                    var url="<?php echo url('/teacher/managepaper/save-question-head') ?>";
                    var questionID = $("#topid").val();
                    var questionPrice=$("#questionPrice").val();
                    var provience=$("#provience").val();
                    var city=$("#city").val();
                    var county=$("#county").val();
                    var gradeID=$("#gradeID").val();
                    var subjectID=$("#subjectID").val();
                    var versionID=$("#versionID").val();
                    var source=$("#bj").val();//考试分类
                    var year=$("#date1").val();// 年份
                    var from =$('#source').val();//来源
                    var nandu=$("#nandu").val(); // 难度
                    var queslevel=$("#queslevel").val();// 题目等级
                    var capacity =$("#capacity").val();//掌握程度
                    var tags=$("#tags").val();
                    var tqtid=$("#type_0").val();
                    var kid=$("#treeval").val();
                    var name=$("#question_0").val();
                    if(tags==""){
                        popBox.alertBox('自定义标签不能为空！');
                        return false;
                    }
                    if(tqtid==""){
                        popBox.alertBox('请选择题型！');
                        return false;
                    }
                    if (name == "") {
                        popBox.alertBox('题目不能为空！');
                        return false;
                    }
                    if (kid == "") {
                        popBox.alertBox('知识点不能为空！');
                        return false;
                    }
                    $.post(url,{'questionID':questionID,'questionPrice':questionPrice,'provience':provience,'city':city,'county':county,'gradeID':gradeID,
                              'subjectID':subjectID,  'versionID':versionID,'source':source,'year':year,'from':from,'nandu':nandu,
                            'queslevel':queslevel,'capacity':capacity,'tags':tags,'tqtid':tqtid,'kid':kid,'name':name},
                        function(msg){
                            location.href="<?php echo url('/teacher/managepaper/save-ques-content')?>"+"?question="+msg+"&operation=<?php echo $data->operation?>";
                        })
                }
            })
        })
    </script>
<div class="currentRight grid_16 push_2 topic_input">
    <div class="noticeH clearfix">
        <h3 class="h3L">组卷</h3>
    </div>
    <hr>
    <ul class="stepList clearfix">
        <li class="ac"><span>试卷结构</span><i class="step01"></i></li>
        <li><span>筛选题目</span><i class="step02"></i></li>
        <li><span>设定分值</span><i class="step03"></i></li>
    </ul>
    <br>
    <div class="item c_uploadTestPaper c_subject">
        <div class="registration_left  registration_left_auto"> <a href="#" class="entry_link">题目<?php if($data->operation == 1)echo "录入"; else echo"修改";   ?></a>
        <?php $form =\yii\widgets\ActiveForm::begin( array(
            'enableClientScript' => false,
            'id' => 'form_id',
        ))?>
            <ul class="box_data_list">
                <input type="hidden" value="<?php echo $data->id;?>" id="topid">
               <!-- <li class="clearfix">
                    <label class="species">题目售价:</label>
                    <div class="word_box">

                        <span class="prompt_y">元</span> </div>
                </li>-->
                <input type="hidden" value="<?php echo $data->questionPrice ?>" class="input_text text" id="questionPrice">
                <li class="clearfix">
                    <label class="species">适用地区:</label>
                    <div class="box_select">
                        <?php
                        echo CHtmlExt::dropDownListAjax("provience", $data->provience, ArrayHelper::map(AreaHelper::getProvinceList(), 'AreaID', 'AreaName'), array(
                           "defaultValue" => false, "prompt" => "请选择",
                            'ajax' => array(
                                'url' => Yii::$app->urlManager->createUrl('ajax/get-area'),
                                'data' => array('id' => new \yii\web\JsExpression('this.value')),
                                'success' => 'function(html){jQuery("#city").html(html).change();}'
                            ),
                            "id" => "provience",

                        ));
                        ?>
                        <label></label>
                        <?php
                        echo CHtmlExt::dropDownListAjax("city", $data->city, ArrayHelper::map(AreaHelper::getCityList( $data->provience), 'AreaID', 'AreaName'),array(
                           "defaultValue" => false, "prompt" => "请选择",
                            'ajax' => array(
                                'url' => Yii::$app->urlManager->createUrl('ajax/get-area'),
                                'data' => array('id' => new \yii\web\JsExpression('this.value')),
                                'success' => 'function(html){jQuery("#county").html(html).change();}'
                            ),
                            "id" => "city",

                        ));
                        ?>
                        <label></label>
                        <?php
                        echo CHtmlExt::dropDownListAjax("county", $data->country, ArrayHelper::map(AreaHelper::getRegionList($data->city), 'AreaID', 'AreaName'), array(
                           "defaultValue" => false, "prompt" => "请选择",
                            "id" => "county",

                        ));
                        ?>
                        <label></label>
                    </div>
                </li>
                <li class="clearfix">
                    <label class="species"><i>*</i>适合年级:</label>
                    <div class="box_select">
                        <?php
                        echo CHtmlExt::dropDownListAjax("grade",$data->gradeid, GradeModel::model()->getListData(),array(
                            "defaultValue" => false, "prompt" => "请选择",
                            'data-validation-engine' => 'validate[required]',
                            'ajax' => array(
                                'url' => Yii::$app->urlManager->createUrl('ajax/get-item-for-grade'),
                                'data' => array('id' => new \yii\web\JsExpression('this.value')),
                                'success' => 'function(html){jQuery("#subjectID").html(html).change();}'
                            ),
                            "id" => "gradeID",

                        ));
                        ?>
                    </div>
                </li>
                <li class="clearfix">
                    <label class="species"><i>*</i>科目:</label>
                    <div class="box_select">
                        <?php
                        echo Html::dropDownList('item', $data->subjectid, ArrayHelper::map(SubjectModel::getSubByGrade($data->gradeid), 'secondCode', 'secondCodeValue'),
                            array(
                                'ajax' => array(
                                    'url' => Yii::$app->urlManager->createUrl('ajax/get-topic-type'),
                                    'data' => ['grade'=>new \yii\web\JsExpression('jQuery("#gradeID").val()'),'subject' =>new \yii\web\JsExpression('this.value')],
                                    'success' => 'function(html){jQuery("#type_0").html(html).change();jQuery("#treeval").html("");jQuery("#treeli").html("")}'
                                ),
                                "defaultValue" => false, "prompt" => "请选择 ",
                                'data-validation-engine' => 'validate[required]',
                                'id'=>"subjectID",
                            ));
                        ?>
                    </div>
                </li>
                <li class="clearfix">
                    <label class="species">版本:</label>
                    <div class="box_select">
                        <?php
                        echo Html::dropDownList('banben',  $data->versionid,
                            EditionModel::model()->getListData(),
                            array(
                                "defaultValue" => false, "prompt" => "请选择 ",
                                'id'=>'versionID',
                            ));
                        ?>
                    </div>
                </li>
                <li class="clearfix">
                    <label class="species">考试分类:</label>
                    <div class="box_select">
                        <?php
                        echo Html::dropDownList('provenance', $data->provenance,
                            FromModel::model()->getListData(),
                            array(
                                "defaultValue" => false, "prompt" => "请选择 ",
                                'id'=> 'bj',
                            ));
                        ?>
                    </div>
                </li>
                <li class="clearfix">
                    <label class="species">年份:</label>
                    <div class="box_select">
                        <input  type="text"class="Wdate" value="<?php echo $data->year ?>" id="date1" onclick="WdatePicker({dateFmt:'yyyy',minDate:'1900',maxDate:'2099'})" readonly="readonly" />
                    </div>
                </li>
                <li class="clearfix">
                    <label class="species">来源:</label>
                    <div class="box_select">
                        <input type="text" class="text" value="<?php echo $data->quesFrom ?>" id="source">
                    </div>
                </li>
                <li class="clearfix">
                    <label class="species">题目等级:</label>
                    <div class="box_select">
                        <?php
                        echo Html::dropDownList('queslevel',  $data->quesLevel,
                                QuesLevelModel::model()->getListData(),
                            array(
                                "defaultValue" => false, "prompt" => "请选择 ",
                                'id'=> 'queslevel',
                            ));
                        ?>
                    </div>
                </li>
                <li class="clearfix">
                    <label class="species">掌握程度:</label>
                    <div class="box_select">
                        <?php
                        echo Html::dropDownList('capacity', $data->capacity,
                            CapacityModel::model()->getListData(),
                            array(
                                "defaultValue" => false, "prompt" => "请选择 ",
                                'id'=> 'capacity',
                            ));
                        ?>
                    </div>
                </li>
                <li class="clearfix">
                    <label class="species"><i>*</i>难易程度:</label>
                    <div class="box_select">
                        <?php
                        echo Html::dropDownList('norm',  $data->complexity,
                            DegreeModel::model()->getListData(),
                            array(
                                "defaultValue" => false, "prompt" => "请选择 ",
                                'data-validation-engine' => 'validate[required]',
                                'id'=>'nandu',
                            ));
                        ?>
                    </div>
                </li>
                <li class="clearfix">
                    <label class="species"><i>*</i>自定义标签:</label>
                    <div class="word_box">
                        <input type="text" value="<?php echo $data->Tags ?>" class="input_box text" style="width:270px" id="tags">
                        <span class="altTxt">(多个标签用“,”隔开)</span> </div>
                </li>
            </ul>
            <!--整体-->

            <div class="itemsBox">
                <div id="questionBox_0">
                    <ul class="box_data_list">
                        <li class="clearfix">
                            <label class="species"><i>*</i>知识点:</label>

                            <div id="tree_0" class="word_box treeParent"  style="height: auto;">
                                <button class="addPoint2" type="button">编辑知识点</button>
                                <?php if(!empty($data->kid)){
                                    echo '<div class="pointArea hide" style="display: block;">';
                                }else{
                                    echo '<div class="pointArea hide">';
                                }
                                ?>

                                 <input  name="hidval" class="hidVal" type="hidden" id="treeval" value="<?php echo $data->kid ?>">
                                    <h5>已选中知识点：</h5>
                                    <ul class="labelList" id="treeli">
                                        <?php $nodeList = KnowledgePointModel::findKnowledge($data->kid); foreach ($nodeList as $item) { ?>
                                            <li val="<?php echo $item->id ?>"><?php echo $item->name ?> </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li class="clearfix">
                            <label class="species"><i>*</i>题型:</label>
                            <div class="box_select">
                                <?php
                                echo Html::dropDownList('qType', $data->tqtid, ArrayHelper::map(ItemTypeModel::model(),'secondCode','secondCodeValue'),
                                    array(
                                        "defaultValue" => false, "prompt" => "请选择 ",
                                        'data-validation-engine' => 'validate[required]',
                                        'id'=>"type_0",
                                        'class'=>"xuanzhe02",
                                    ));
                                ?>
                            </div>
                        </li>
                        <li class="clearfix">
                            <label class="species"><i>*</i>题目:</label>
                            <div class="timu">
                                <textarea id="question_0" style="width:440px; height:100px;"><?php echo $data->content?></textarea>
                            </div>
                        </li>
                    </ul>
                    <?php \yii\widgets\ActiveForm::end(); ?>
                </div>
            </div>
        <div class="clearfix">
            <label class="species"></label>
            <div class="prompt02 prompt03">
                <button class="p_reservation">下一步</button>
            </div>
        </div>
        </div>
</div>
</div>
