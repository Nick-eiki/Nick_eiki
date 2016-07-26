<?php
/**
 *  @var $hourList ClassHourForm[]
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-22
 * Time: 下午12:08
 */
use frontend\components\helper\AreaHelper;
use frontend\models\ClassHourForm;
use frontend\models\dicmodels\EditionModel;
use frontend\models\dicmodels\GradeModel;
use frontend\models\dicmodels\SubjectModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title='上传视频';
$backend_asset = publicResources();
;
$this->registerJsFile($backend_asset . '/js/ztree/jquery.ztree.all-3.5.min.js'.RESOURCES_VER);
$this->registerCssFile($backend_asset . '/js/ztree/zTreeStyle/zTreeStyle.css'.RESOURCES_VER);
$hourListCount = count($hourList);
?>
<script>
    $(function(){
        var zNodes =[
            { id:1, pId:0, name:"语文"},
            { id:11, pId:1, name:"拼音"},
            { id:111, pId:11, name:"声母"},
            { id:112, pId:11, name:"韵母"},
            { id:113, pId:11, name:"语法"},
            { id:12, pId:1, name:"标点符号"},
            { id:13, pId:1, name:"造句"},
            { id:14, pId:1, name:"语法"},
        ];
        var zNodes2 =[
            { id:1, pId:0, name:"章节一"},
            { id:11, pId:1, name:"拼音"},
            { id:111, pId:11, name:"声母"},
            { id:112, pId:11, name:"韵母"},
            { id:113, pId:11, name:"语法"},
            { id:12, pId:1, name:"章节二"},
            { id:13, pId:1, name:"章节三"},
            { id:14, pId:1, name:"章四"},
        ];

//课时安排-----知识树/章节
        var treeCls;//  true:知识树 false:章节

        function check(btn){//是否已经选中,通过btn找到相邻元素
            var pa=btn.parent('.treeParent');
            var checkLi=pa.find('li');
            if(checkLi.length>0){
                return true;
            }
        }

        function clear(btn){//清除已经选中,的通过btn找到相邻元素
            var pa=btn.parent('.treeParent');
            pa.find('.pointArea').hide();
            pa.find('.labelList').empty();
            pa.find('.hidVal').val('');
        }

        $('.pointRadio').live('click',function(){ //知识点radio
            $(this).nextAll('.editBtn').show().text('编辑知识点');
            if(check($(this)) && treeCls==true)	return true;
            else clear($(this));
            treeCls=true;
        });

        $('.chaptRadio').live('click',function(){ //章节radio
            $(this).nextAll('.editBtn').show().text('编辑章节');
            if(check($(this)) && treeCls==false )return true;
            else clear($(this));
            treeCls=false;
        });

        function sel_zNodes(zNodes,zNodes2){//判断 知识树or章节
            var arr=[];
            if(treeCls==true){
                arr[0]=zNodes;
                arr[1]="知识树";
            }
            else{
                arr[0]=zNodes2;
                arr[1]="章节树";
            }
            return arr;
        }

        $('.editBtn').live('click',function(){//编辑
            var arr=sel_zNodes(zNodes,zNodes2);
            popBox.pointTree(arr[0],$(this),arr[1]);
        });



//课时安排-----添加课时


//添加课时----使用讲义弹框
        $('.addDocBtn').live('click',function(){

            var doc="";
            var _this=$(this);
            var id = _this.attr('docID');
            $.post("<?php echo url('teacher/briefcase/get-doc')?>",{id:id},function(data){

                $('#updatehandout').html(data);
                $( "#DocBox" ).dialog( "open" );
                $('#DocBox ul li').removeClass('ac').click(function(){
                    $(this).addClass('ac').siblings().removeClass('ac');
                    doc=$(this).clone();
                })
                });

            $('#DocBox').dialog({
                autoOpen:false,
                width:500,
                modal: true,
                resizable:false,
                buttons: [
                    {
                        text: "确定",
                        click: function() {
                            _this.next('.DocList').empty().append(doc);
                            _this.text('修改讲义');
                             _this.nextUntil('.addHour').val(doc.attr('handout'));
                            $( this ).dialog('close');
                        }
                    },
                    {
                        text: "取消",
                        click: function() {
                            $( this ).dialog('close');
                        }
                    }
                ]
            });

        });




//选择老师弹窗
        $('.teacherListBox').dialog({
            autoOpen:false,
            width:500,
            modal: true,
            resizable:false,
            buttons: [
                {
                    text: "确定",
                    click: function() {
                      $( '#<?php echo   Html::getInputId($model, 'teacher')  ?>').val( $(this).find($(".teacherListBox .ac")).attr('teacherid'));
                            $('.sel_teacher .btn').prev('span').css('margin-right',20).text($( ".teacherListBox .ac").text());
                            $('.sel_teacher .btn').text('重新选择');
                        $( this ).dialog('close');
                    }
                },
                {
                    text: "取消",
                    click: function() {
                        $( this ).dialog('close');
                    }
                }
            ]
        });
        //点击添加教师
        $('.sel_teacher .btn').live('click',function(){
            var _this=$(this);
            var schoolId = '<?php echo $schoolModel;?>';
            $.post("<?php echo url('teacher/briefcase/get-teacher')?>",{schoolId:schoolId},function(data){
                $('#updateTeacher').html(data);
                $( "#teacherListBox" ).dialog( "open" );
                $( ".teacherListBox li").click(function(){
                    $(this).addClass('ac').siblings().removeClass('ac');
                })
            });

        })



    })
</script>
<div class="currentRight grid_16 push_2">
    <div class="crumbs"><a href="#">教师</a> &gt;&gt; <a href="#">资料袋</a> &gt;&gt; 上传视频</div>
    <div class="noticeH clearfix">
        <h3 class="h3L">上传视频</h3>
    </div>
    <hr>
    <div class="docBag_uploadVideo">
    <?php $form =\yii\widgets\ActiveForm::begin( array(
        'enableClientScript' => false,
        'id' => 'form1'
    ))?>
            <ul class="form_list uploadVedioList">
                <li>
                    <div class="formL">
                        <label><i></i>资料类型：</label>
                    </div>
                    <div class="formR">
                        <?php
                        echo Html:: dropDownList(Html::getInputName($model, "type"),$model->classType, array('3'=>'视频'), array(
                            "defaultValue" => false,
                            "id" => Html::getInputId($model, "type")
                        ));
                        ?>
                        <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'type') ?>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label><i></i>适用地区：</label>
                    </div>
                    <div class="formR">
                        <?php
                        echo Html:: dropDownList(Html::getInputName($model, "provience"), $model->provience, ArrayHelper::map(AreaHelper::getProvinceList(), 'AreaID', 'AreaName'), array(
                            "defaultValue" => false, "prompt" => "请选择",
                            'ajax' => array(
                                'url' => Yii::$app->urlManager->createUrl('ajax/get-area'),
                                'data' => array('id' => new \yii\web\JsExpression('this.value')),
                                'success' => 'function(html){jQuery("#' . Html::getInputId($model, "city") . '").html(html).change();}'
                            ),
                            "id" => Html::getInputId($model, "provience")
                        ));
                        ?>
                        <?php
                        echo Html:: dropDownList(Html::getInputName($model, "city"), $model->city, ArrayHelper::map(AreaHelper::getCityList($model->provience), 'AreaID', 'AreaName'), array(
                            "defaultValue" => false, "prompt" => "请选择", "id" => Html::getInputId($model, "city"),
                            'ajax' => array(
                                'url' => Yii::$app->urlManager->createUrl('ajax/get-area'),
                                'data' => array('id' => new \yii\web\JsExpression('this.value')),
                                'success' => 'function(html){jQuery("#' . Html::getInputId($model, "county") . '").html(html).change();}'
                            ),
                        ));
                        ?>
                        <?php
                        echo Html:: dropDownList(Html::getInputName($model, "county"), $model->county, ArrayHelper::map(AreaHelper::getRegionList($model->city), 'AreaID', 'AreaName'), array(
                            'data-validation-engine' => 'validate[required]', "defaultValue" => false, "prompt" => "请选择", "id" => Html::getInputId($model, "county"),
                            'data-prompt-target' => "diqu_prompt",
                            'data-prompt-position' => "inline"
                        ));
                        ?>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label><i></i>适用年级：</label>
                    </div>
                    <div class="formR">
                        <?php
                        echo Html::dropDownList(Html::getInputName($model, 'gradeID'),
                            $model->gradeID,
                            ArrayHelper::map(GradeModel::model()->getList(), 'gradeId', 'gradeName'),
                            array('data-validation-engine' => 'validate[required,custom[number]]',
                                'id' => Html::getInputId($model, 'gradeID')));
                        ?>
                        <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'gradeID') ?>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label><i></i>科目：</label>
                    </div>
                    <div class="formR">
                        <?php
                        echo Html::dropDownList(Html::getInputName($model, 'subjectID'),
                            $model->subjectID,
                            SubjectModel::model()->getList(),
                            array('data-validation-engine' => 'validate[required,custom[number]]',
                                'id' => Html::getInputId($model, 'subjectID')));
                        ?>
                        <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'subjectID') ?>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label><i></i>教材版本：</label>
                    </div>
                    <div class="formR">
                        <?php
                        echo Html::dropDownList(Html::getInputName($model, 'versionID'),
                            $model->versionID,
                            EditionModel::model()->getListData(),
                            array('data-validation-engine' => 'validate[required,custom[number]]',
                                'id' => Html::getInputId($model, 'versionID')));
                        ?>
                        <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'versionID') ?>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label><i></i>课程名称：</label>
                    </div>
                    <div class="formR">

                        <input name="<?php echo Html::getInputName($model, 'videoName') ?>"
                               id="<?php echo Html::getInputId($model, 'videoName') ?>"
                               data-validation-engine="validate[required,maxSize[30]]"
                               class="text" type="text" value="<?php echo $model->videoName ?>"/>
                        <span class="prompt">30字以内</span>
                        <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'videoName') ?>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label>简介：</label>
                    </div>
                    <div class="formR">
                        <div style="width: 562px;"><?php
                            echo \frontend\widgets\ueditor\MiniNoImgUEditor::widget(
                                array(
                                    'id'=>'editor',
                                    'model'=>$model,
                                    'attribute'=>'introduce',
                                    'UEDITOR_CONFIG'=>array(
                                    ),
                                ));
                            ?></div>
                    </div>
                </li>
                <li class="classesTable">
                    <table>
                        <colgroup>
                            <col width="80px">
                            <col width="200px">
                            <col width="140px">
                            <col width="140px">
                            <col width="40px">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>课时安排</th>
                            <th>节次名称</th>
                            <th>讲义</th>
                            <th>视频</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody id="classesInput">
                        <tr>
                            <?php $hourItem = array_shift($hourList);?>
                            <td class="Valign"><span class="chaptTitle">第1节</span>
                                <?php echo  Html::activeHiddenInput($hourItem, '[0]cNum',array('value'=>'1')); ?>
                            </td>
                            <td><input type="text" class="text" name="<?php echo Html::getInputName($hourItem,'[0]cName')?>">
                                <div class="treeParent">
                                    <input type="radio" class="radio pointRadio" name="<?php echo Html::getInputName($hourItem,'[0]type')?>" value="0"> <label>知识点</label>&nbsp;&nbsp;&nbsp;<input type="radio" class="radio chaptRadio" name="<?php echo Html::getInputName($hourItem,'[0]type')?>" value="1"> <label>章节</label>
                                    <br>
                                    <button type="button" class="hide editBtn"></button>
                                    <div class="pointArea hide">
<!--                                        <input class="hidVal" type="hidden" value="">-->

                                        <?php echo  Html::activeHiddenInput($hourItem, '[0]kcid') ?>
                                        <h6>已选中知识点:</h6>
                                        <ul class="labelList">
                                            <li>语文</li>
                                            <li>造句</li>
                                            <!--<li>形容词</li>
                                            <li>语文</li>
                                            <li>造句</li>
                                            <li>形容词</li>-->
                                        </ul>
                                    </div>
                                </div>
                            </td>
                            <td><button type="button" class="a_button btn20 addDocBtn" docID="<?php echo $id;?>">使用讲义</button>
                                <ul class="DocList">

                                </ul>
                                <?php echo  Html::activeHiddenInput($hourItem, '[0]teachMaterialID',['class'=>'addHour']); ?>
                            </td>
                            <td><button type="button" class="a_button btn20 addVideoBtn">上传视频</button>
                                <ul class="videoList">

                                </ul>
                                <?php echo  Html::activeHiddenInput($hourItem, '[0]videoUrl') ?>
                            </td>
                            <td class="Valign"><span class="delBtn">删除</span></td>
                        </tr>
                        <?php foreach($hourList as $key=>$hourItem){?>
                            <?php echo $this->render('_hour_view',array('hourItem'=>$hourItem,'key'=>$key+1,'id'=>$id))?>
                     <?php    } ?>
                        </tbody>
                    </table>
                    <button type="button" class="a_button btn20 addClassesBtn" id="addClass">添加课时</button>
                </li>
                <li>
                    <div class="formL">
                        <label><i></i>授课老师：</label>
                    </div>
                    <div class="formR sel_teacher">
                        <span></span><button type="button" class="bg_blue_l">选择老师</button>
                    </div>
                    <?php echo  Html::activeHiddenInput($model, 'teacher') ?>
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'teacher') ?>
                </li>
                <li>
                    <div class="formL">
                        <label><i></i>优质资源分享：</label>
                    </div>
                    <div class="formR sel_teacher">
                        <input type="radio" class="radio" name="<?php echo Html::getInputName($model, 'isShare') ?>" value="1" >
                        <label>分享</label>
                        &nbsp;&nbsp;
                        <input type="radio" class="radio" name="<?php echo Html::getInputName($model, 'isShare') ?>" checked value="0">
                        <label>不分享</label>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label><i></i>是否收费：</label>
                    </div>
                    <div class="formR">
                        <input type="radio" class="radio" name="<?php echo Html::getInputName($model, 'isCharge') ?>" value="1">
                        <label>是</label>
                        &nbsp;&nbsp;
                        <input type="radio" class="radio" name="<?php echo Html::getInputName($model, 'isCharge') ?>" value="0">
                        <label>否</label>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label><i></i>销售价格：</label>
                    </div>
                    <div class="formR">
                        <input type="text" class="text" style="width:60px" name="<?php echo Html::getInputName($model, 'price') ?>">
                        元 </div>
                </li>
                <li>
                    <div class="formL">
                        <label><i></i>分账比例：</label>
                    </div>
                    <div class="formR"> 学校:教师&nbsp;&nbsp;&nbsp;
                        <input type="text" class="text"style="width:40px" value="0">
                        :
                        <input type="text" class="text" style="width:40px" value="0">
                    </div>
                </li>
                <li>
                    <div class="formL"><label></label></div>
                    <div class="formR">
                        <br>
                        <input type="checkbox" checked  name="<?php echo Html::getInputName($model, 'isAgreement') ?>" value="1"> 针对本课程,已经达成分账协议
                    </div>
                </li>
            </ul>
            <p class="conserve">
                <button type="submit" class="bg_red_d B_btn110">确&nbsp;&nbsp;定</button>
            </p>
    <?php \yii\widgets\ActiveForm::end()?>
    </div>
</div>
<!--弹出框---使用讲义-->
<div id="DocBox" class="popBox DocBox hide" title="选择讲义">
<div id="updatehandout">

</div>
</div>
<div class="popBox teacherListBox hide" id="teacherListBox" title="选择教师">
    <div id="updateTeacher">
    </div>

</div>
<script type="text/javascript">
    var hourListCount = <?php echo $hourListCount;?>;
    var appe_html =<?php $out= $this->render("_hour_view",array("hourItem"=> new ClassHourForm(),'key'=>"#temp#",'id'=>$id),true);echo json_encode($out);?>;

        $('#addClass').bind('click', function () {
            $('#classesInput').append(appe_html.replace(/#temp#/g, ++hourListCount));
        });
</script>


