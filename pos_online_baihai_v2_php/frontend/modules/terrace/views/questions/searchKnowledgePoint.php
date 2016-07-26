<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-11-21
 * Time: 下午2:13
 */
use frontend\components\CHtmlExt;
use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\EditionModel;
use frontend\models\dicmodels\EliteModel;
use frontend\models\dicmodels\FromModel;
use frontend\models\dicmodels\GradeModel;
use frontend\models\dicmodels\LoadSubjectModel;
use frontend\models\dicmodels\QueryTypeModel;
use frontend\models\dicmodels\SchoolLevelModel;
use frontend\models\dicmodels\SubjectModel;
use yii\helpers\ArrayHelper;

/** @var $this QuestionsController */
/* @var $this yii\web\View */  $this->title="题目筛选--高级搜索";
$backend_asset = publicResources_new();
$this->registerCssFile($backend_asset . "/js/ztree/zTreeStyle/zTreeStyle.css".RESOURCES_VER);
$this->registerJsFile($backend_asset . "/js/ztree/jquery.ztree.all-3.5.min.js".RESOURCES_VER);

?>

<script>
    $(function () {

//搜索按钮切换
        $('.terrace_btn_js span').bind('click', function () {
            $(this).addClass('s_btn').siblings('span').removeClass('s_btn');

        });

        //高级搜索
        $('#senior').toggle(function () {

            $('.senior_form').show();
        }, function () {
            $('.senior_form').hide();
        });
        $('#text').placeholder({"value":"请填写自定义标签","color":"#ccc"})
    })
</script>
<script>
    $(function () {
       zNodes =<?php echo  json_encode($kModel); ?>;
      setting = {
            check:{enable:false},
            data:{simpleData: {enable: true} },
//            callback{beforeExpand:},
            view:{showIcon:false,showLine:false,fontCss: getFont,
                nameIsHTML: true,expandSpeed:"fast"}

        };
        $.fn.zTree.init($("#treeList2"), setting, zNodes);
        SelectNode();
    });
    function getFont(treeId, node) {
        return node.font ? node.font : {};
    }
    function SelectNode() {
        var treeObj = $.fn.zTree.getZTreeObj("treeList2");
        var treenode = treeObj.getNodeByParam("id","<?php echo app()->request->getParam('kid','') ?>", null);
        treeObj.expandNode(treenode, true, true, true);
        treeObj.selectNode(treenode);
    }

</script>
<script>
    $(function () {
        var oDateBax = $('#bookVersionSelect');
        var aDl = $('#bookVersionSelect dl');
        var add = $('#bookVersionSelect dl dd');
        var timer = null;


        $('#dateBox').bind('mouseover', function () {
            $('#dateBox h4').addClass('tre_hover');
            $('#bookVersionSelect').show();
        });


        $('#dateBox').bind('mouseout', function () {
            $('#dateBox h4').removeClass('tre_hover');
            $('#bookVersionSelect').hide();
        });


        for (var i = 0; i < aDl.length; i++) {
            aDl[i].index = i;
            aDl[i].onmouseover = function () {
                clearTimeout(timer);

                var this_ = this;
                for (var i = 0; i < aDl.length; i++) {
                    aDl[i].className = '';
                    add[i].className = '';
                    add[i].style.display = 'none';
                }
                add[this_.index].style.display = 'block';


                add[this_.index].className = 'greend';
                this_.className = 'greend';

            };

            aDl[i].onmouseout = function () {
                for (var i = 0; i < aDl.length; i++) {
                    aDl[i].index = i;
                    var _this = this;

                }
                timer = setTimeout(function () {
                    add[_this.index].style.display = 'none';
                    aDl.removeClass('greend');
                    add.removeClass('greend');

                }, 30);
            }
        }


    });
</script>

<!--主体内容开始-->
<div class="replace">
<div class="deta_de">
    <a href="#">首页</a>&gt;&gt;<a href="#" class="this">题目筛选</a>
</div>
<div class="class_c clearfix tch" style="min-height:500px">
<div class="currentLeft grid_16 filterSubject" style=" float:right;">
<div class="noticeH clearfix">
    <h3 class="h3L">题目筛选</h3>

</div>
<hr>
<br>

<div class="searchBar">

    <ul class="form_list">

        <?php echo Html::form( $this->createUrl('',array('subjectid'=>app()->request->getParam('subjectid',''))), 'get') ?>
        <li>
            <div class="formL">
                <label>题目关键字：</label>
            </div>

            <div class="formR" style="position:relative;">
                <input type="text" class="text" id="text"  name="tags">

                <input type="submit" class="btn" value="搜索" id="">
                &nbsp;&nbsp;<a href="javascript:" class="senior" id="senior">高级搜索</a></div>
        </li>
        <?php echo Html::endForm() ?>
    </ul>
    <?php echo Html::form($this->createUrl(''), 'get', ['id' => 'searchForm','style'=>'display:none;','class'=>'senior_form']) ?>
        <ul class="form_list">
            <li>
                <div class="formL">
                    <label>使用地区:</label>
                </div>
                <div class="formR">
                    <?php
                    echo CHtmlExt::dropDownListAjax("provience", $model->provience, ArrayHelper::map(AreaHelper::getProvinceList(), 'AreaID', 'AreaName'), array(
                        "defaultValue" => false, "prompt" => "请选择",
                        'ajax' => [
                            'url' => Yii::$app->urlManager->createUrl('ajax/get-area'),
                            'data' => ['id' => new \yii\web\JsExpression('this.value')],
                            'success' => 'function(html){jQuery("#city").html(html).change();}'
                        ],
                        "id" =>  "provience"
                    ));
                    ?>
                    <label>省</label>
                    <?php
                    echo CHtmlExt::dropDownListAjax( "city", $model->city, ArrayHelper::map(AreaHelper::getCityList($model->provience), 'AreaID', 'AreaName'), array(
                        "defaultValue" => false, "prompt" => "请选择", "id" => "city",
                        'ajax' => [
                            'url' => Yii::$app->urlManager->createUrl('ajax/get-area'),
                            'data' => ['id' => new \yii\web\JsExpression('this.value')],
                            'success' => 'function(html){jQuery("#country").html(html).change();}'
                        ],
                    ));
                    ?>
                    <label>市</label>
                    <?php
                    echo CHtmlExt::dropDownListAjax("country", $model->country, ArrayHelper::map(AreaHelper::getRegionList($model->city), 'AreaID', 'AreaName'), [
                         "defaultValue" => false, "prompt" => "请选择", "id" =>"country"

                    ]);
                    ?>
                    <label>区</label>

                </div>
            </li>
            <li>
                <div class="formL">
                    <label>适用年级:</label>
                </div>
                <div class="formR">
                    <?php
                    echo CHtmlExt::dropDownListAjax( 'gradeid',
                        $model->gradeid,
                        ArrayHelper::map(GradeModel::model()->getList(), 'gradeId', 'gradeName'),
                        array("prompt" => "请选择",
                            'id' => 'gradeid'));
                    ?>

                </div>
            </li>

            <li>
                <div class="formL">
                    <label>版本:</label>
                </div>
                <div class="formR">
                    <?php
                    echo CHtmlExt::dropDownListAjax( 'versionid',
                        $model->versionid,
                        EditionModel::model()->getListData(),
                        array("prompt" => "请选择",
                            'id' =>'versionid'));
                    ?>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label>学部:</label>
                </div>
                <div class="formR">
                    <?php
                    echo CHtmlExt::dropDownListAjax( 'schoolLevel',
                        $model->schoolLevel,
                 SchoolLevelModel::model()->getListData(),
                        array("prompt" => "请选择",
                            'id' =>'schoolLevel',
                            'ajax' => array(
                        'url' => Yii::$app->urlManager->createUrl('ajax/get-subject'),
                        'data' => array('schoolLevel' => new \yii\web\JsExpression('this.value')),
                        'success' => 'function(html){jQuery("#subjectid").html(html).change();}'
                        )));
                    ?>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label>科目、题型:</label>
                </div>
                <div class="formR">
                    <?php
                    echo CHtmlExt::dropDownListAjax( 'subjectid',
                        $model->subjectid,
                        ArrayHelper::map(LoadSubjectModel::model()->getData($model->schoolLevel,''),'secondCode','secondCodeValue'),
                        array('id' =>'subjectid',
                            'ajax' => [
                                'url' => Yii::$app->urlManager->createUrl('ajax/get-topic'),
                                'data' => ['subject' => new \yii\web\JsExpression('this.value'),'schoolLevel'=> new \yii\web\JsExpression('jQuery("#schoolLevel").val()')],
                                'success' => 'function(html){jQuery("#typeId").html(html).change();}'
                            ]));
                    ?>
                </div>

                <div class="formR" style="padding-left: 10px;">
                        <?php
                        echo CHtmlExt::dropDownListAjax('typeId',
                            $model->typeId,
                            ArrayHelper::map( QueryTypeModel::queryQuesType($model->schoolLevel, $model->subjectid), 'paperQuesTypeId', 'paperQuesType'),
                            ["prompt" => "请选择",'id' =>'typeId']);
                        ?>

                </div>
            </li>
            <li>
                <div class="formL">
                    <label>出处:</label>
                </div>
                <div class="formR">
                  <?php
                    echo CHtmlExt::dropDownListAjax( 'provenance',
                        $model->provenance,
                        FromModel::model()->getListData(),
                        ["prompt" => "请选择",'id' =>'provenance']);
                    ?>
                </div>
            </li>

            <li>
                <div class="formL">
                    <label>年份:</label>
                </div>
                <div class="formR">
                    <?php
                    echo CHtmlExt::dropDownListAjax('year',
                        '',
                        getClassYears(),
                        ['prompt'=>'请选择']);
                    ?>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label>名校:</label>
                </div>
                <div class="formR">
                    <?php
                    echo CHtmlExt::dropDownListAjax( 'school',
                        $model->school,
                        EliteModel::model()->getListData(),
                        ["prompt" => "请选择",'id' =>'school']);
                    ?>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label>题目关键字:</label>
                </div>
                <div class="formR">
                    <input type="text" value="" class="input_box text" name="tags">
                </div>
            </li>
            <li>
                <div class="formL">
                    <label></label>
                </div>
                <div class="formR">
                    <button class="bg_red_d" type="submit" id="seniorSearch">搜索</button>
                </div>
            </li>
        </ul>
<!--    </form>-->
    <?php echo Html::endForm() ?>

</div>
<br>
<br>

<div class="schResult">
    <h3>搜索结果:</h3>
    <ul class="form_list showSearchKey"  style="display: none">
        <li>
            <div class="formL">
                <label>搜索条件：</label>
            </div>
            <div class="formR  schTxtArea">
               <p><span class="searchKey"></span></p>
            </div>
        </li>
    </ul>
    <h4 class="searchcount">共有题目0道题如下:</h4>


    <div  id="update">
    <?php echo $this->render("_search_list", ['list' => $list, 'pages' => $pages]); ?>
    </div>
</div>
</div>
<div class="centRight centStyle"
     style=" float:left; background:#fff; margin-top:0px; padding:0px; width:228px; overflow:visible; position:relative;">
    <div class="tree_none" style="">
        <ul id="treeList2" class="clearfix ztree" style="">

        </ul>
        <div class="link">
<!--            <span  class="searchcount">共有0道题</span>-->
            <!--span 靠左-->

        </div>
    </div>
    <div class="tree" id="dateBox">
        <h4 class="tree_h4"><?php echo SchoolLevelModel::model()->getSchoolLevelhName($department); ?> <?php echo SubjectModel::model()->getSubjectName($subject); ?>
            <i class="top"></i></h4>

        <div id="bookVersionSelect" class="menuCon" style="display:none;">

            <dl class="">
                <?php echo $this->render('_knowledge_view', ['department' => '20201']); ?>
            </dl>
            <dl>
                <?php echo $this->render('_knowledge_view', ['department' => '20202']); ?>
            </dl>
            <dl>
                <?php echo $this->render('_knowledge_view', ['department' => '20203']); ?>
            </dl>

        </div>


    </div>


</div>
</div>
</div>

<!--主体内容结束-->



