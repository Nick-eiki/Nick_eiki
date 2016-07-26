<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/12/10
 * Time: 11:24
 */

use frontend\models\dicmodels\EditionModel;
use frontend\models\dicmodels\GradeModel;
use frontend\models\dicmodels\SubjectModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title="修改课程";
$this->registerJsFile(publicResources() . '/js/My97DatePicker/WdatePicker.js', [ 'position'=>\yii\web\View::POS_HEAD] );

$backend_asset = publicResources();
;
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/ztree/jquery.ztree.all-3.5.min.js');



?>
<script>
    $(function () {
        var zNodes = [];
        var zNodes2 = [];

//课时安排-----知识树/章节
        var treeCls;//  true:知识树 false:章节
        function check(btn) {//是否已经选中,通过btn找到相邻元素
            var pa = btn.parent('.treeParent');
            var checkLi = pa.find('li');
            if (checkLi.length > 0) {
                return true;
            }
        }

        //根据选取名称不一样，获取知识点或章节
        $('#subjectID,#schoolLevel,#grade').change(function () {
            $(".treeParent input[type=radio]").attr("checked", false);
            $('.pointArea').hide();
            // $('.addPointBtn').hide();
            $('.labelList').empty();
            $('.hidVal').val('');
        });

        function clear(btn) {//清除已经选中,的通过btn找到相邻元素
            var pa = btn.parent('.treeParent');
            pa.find('.pointArea').hide();
            pa.find('.labelList').empty();
            pa.find('.hidVal').val('');
        }

        $('.pointRadio').live('click', function () { //知识点radio
            $('.addPointBtn').show().text('+知识点');
            //  $(this).nextAll('.addPointBtn').show().text('编辑知识点');
            var subjectID = $('#subjectID').val();
            var grade = $('#grade').val();
            $.post("<?php echo url("ajaxteacher/get-knowledge")?>", {
                "subjectID": subjectID,
                "grade": grade
            }, function (data) {
                if (data.success) {
                    zNodes = data.data;
                }
            });
            if (check($(this)) && treeCls == true)    return true;
            else clear($(this));
            treeCls = true;
        });

        $('.chaptRadio').live('click', function () { //章节radio
            $('.addPointBtn').show().text('+章节');
            // $(this).nextAll('.addPointBtn').show().text('编辑章节');
            var subjectID = $('#subjectID').val();
            var materials = $('#materials').val();
            var grade = $('#grade').val();
            $.post("<?php echo url("ajaxteacher/get-chapter")?>", {
                subjectID: subjectID,
                materials: materials,
                grade: grade
            }, function (data) {
                if (data.success) {
                    zNodes2 = data.data;
                }
            });
            if (check($(this)) && treeCls == false)return true;
            else clear($(this));
            treeCls = false;
        });

        function sel_zNodes(zNodes, zNodes2) {//判断 知识树or章节
            var arr = [];
            if (treeCls == true) {
                arr[0] = zNodes;
                arr[1] = "知识树";
            }
            else {
                arr[0] = zNodes2;
                arr[1] = "章节树";
            }
            return arr;
        }

        $('.addPointBtn').live('click', function () {//编辑
            var arr = sel_zNodes(zNodes, zNodes2);
            popBox.pointTree(arr[0], $(this), arr[1]);
        });

        //添加课时----使用讲义弹框
        $('.addDocBtn').live('click', function () {

            var doc = "";
            var _this = $(this);
            var materials =$('#materials').val();
            var gradeId = $('#grade').val();
            var subjectId =$("#subjectID").val();
            $.post("<?php echo url('teacher/coursemanage/get-doc')?>",{subjectId: subjectId,materials:materials,gradeId:gradeId},function(data){

                $('#updatehandout').html(data);
                $("#DocBox").dialog("open");
                $('#DocBox ul li').removeClass('ac').live('click', function () {
                    $(this).addClass('ac').siblings().removeClass('ac');
                    doc = $(this).clone();
                })
            });
            $('#DocBox').dialog({
                autoOpen: false,
                width: 500,
                modal: true,
                resizable: false,
                buttons: [
                    {
                        text: "确定",
                        click: function () {
                            _this.next('.DocList').empty().append(doc);
                            _this.text('修改讲义');
                            _this.nextUntil('.addHour').val(doc.attr('handout'));
                            $(this).dialog('close');
                        }
                    },
                    {
                        text: "取消",
                        click: function () {
                            $(this).dialog('close');
                        }
                    }
                ]
            });
        });
        //删除图片
        $('.remove_pic').live('click',function(){
            $(this).parent('li').remove();
        });

        done = function (e, data) {
            $.each(data.result, function (index, file) {
                if (file.error) {
                    alert(file.error);
                    return;
                }
                $('.up_img').append('<li style="margin-bottom: 15px;"><input type="hidden" name="picurls[]" value="' + file.url + '" /> <img src="' + file.url + '" alt="" width="70" height="70"><button type="submit" style="background:#F00; margin-left:15px; color:#FFF" class="remove_pic" value="删除">删除</button></li>');
            });
        };
        <?php
            $img = explode(',',$model->url);
            foreach ($img as $imgVal) {
        ?>
        $('.up_img').append('<li style="margin-bottom:15px;"><input type="hidden" name="picurls[]" value="<?php echo $imgVal; ?>" /> <img src="<?php echo $imgVal; ?>" alt="" width="70" height="70"><button type="submit" style="background:#F00; margin-left:15px; color:#FFF" class="remove_pic" value="删除">删除</button></li>');
        <?php } ?>

    });


    $(function () {
        $('#form1').submit(function(){
            //判断验证是否通过
            if ($(this).validationEngine('validate') == false){
                return false;
            }

            if($('.hidVal').val() == '') {
                popBox.alertBox('请选择知识点或章节！');
                return false;
            }

            if($('.handoutVal').val() == '') {
                popBox.alertBox('请选择讲义！');
                return false;
            }

            var startTime = $('#beginTime').val();
            var start = new Date(startTime.replace("-", "/").replace("-", "/"));
            var endTime = $('#finishTime').val();
            var end = new Date(endTime.replace("-", "/").replace("-", "/"));
            if(end<start){
                popBox.alertBox('结束时间小于开始时间，请重新选择时间！');
                return false;
            }
            if(start == end){
                popBox.alertBox('开始时间和结束时间相等，请重新选择时间！');
                return false;
            }
			if(editor.getPlainTxt().length>301){
				popBox.alertBox('课程介绍超过300字数限制，请重新编辑！');
				return false;
			}
            return true;

        })
    })
</script>

<!--主体内容开始-->
<div class="currentRight grid_16 push_2">
<div class="crumbs"><a href="#">教师</a> &gt;&gt; <a href="#">课程</a> &gt;&gt; 新建课程</div>
<div class="noticeH clearfix">
    <h3 class="h3L">修改课程</h3>
</div>
<hr>
<div class="newCourse">
<?php
/** @var $form CActiveForm */
$form =\yii\widgets\ActiveForm::begin( array('enableClientScript' => false,'id' => 'form1'))
?>
<ul class="form_list">
<li>
    <div class="formL">
        <label><i></i>课程名称：</label>
    </div>
    <div class="formR">
        <input type="text" class="text" value="<?php echo $model->courseName ?>"
               name="<?php echo Html::getInputName($model, 'courseName') ?>"
               data-validation-engine="validate[required,maxSize[30]]">
        <span class="altTxt">(30字以内)</span>
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
                'id' => 'materials',
                "prompt" => "请选择",
                'data-errormessage-value-missing' => "教材版本不能为空",
            ));
        ?>
        <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'materials') ?>

    </div>
</li>

<li>
    <div class="formL">
        <label><i></i>年级：</label>
    </div>
    <div class="formR">
        <?php
        echo Html::dropDownList(Html::getInputName($model, 'gradeID'),
            $model->gradeID,
            ArrayHelper::map(GradeModel::model()->getList(), 'gradeId', 'gradeName'),
            array('data-validation-engine' => 'validate[required,custom[number]]',
                'id' => 'grade',
                "prompt" => "请选择",
                'data-errormessage-value-missing' => "年级不能为空",
            ));
        ?>
        <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'grade') ?>

    </div>
</li>
<li>
    <div class="formL">
        <label><i></i>学科：</label>
    </div>
    <div class="formR">
        <?php
        echo Html::dropDownList(Html::getInputName($model, 'subjectID'),
            $model->subjectID,
            SubjectModel::model()->getList(),
            array(
                'id' => 'subjectID',
                'data-validation-engine' => 'validate[required,custom[number]]',
                'prompt' => '请选择',
            ));
        ?>
        <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'subjectID') ?>
    </div>
</li>
<li>
    <div class="formL">
        <label><i></i>班级：</label>
    </div>
    <div class="formR">
        <?php
        echo $form->dropDownList($model, 'classId', ArrayHelper::map(loginUser()->getClassInfo(), 'classID', 'className'),
            [
                'data-validation-engine' => 'validate[required]',
                "defaultValue" => false,
                "prompt" => "请选择",
                'data-prompt-target' => "department_prompt",
                'data-prompt-position' => "inline",
                'data-errormessage-value-missing' => "班级不能为空",
            ]
        );
        ?>
        <span id="department_prompt"></span>
    </div>
</li>

<li>
    <div class="formL">
        <label><i></i>课程相关：</label>
    </div>
    <div class="formR">
        <div class="treeParent">
            <?php if ($model->connetID == 0) { ?>
                <input type="radio" class="radio pointRadio" value="0" checked
                       name="<?php echo Html::getInputName($model, 'connetID') ?>">
                <label>知识点</label>
                &nbsp;&nbsp;&nbsp;
                <input type="radio" class="radio chaptRadio" value="1"
                       name="<?php echo Html::getInputName($model, 'connetID') ?>">
                <label>章节</label>
                <br>
            <?php } elseif ($model->connetID == 1) { ?>
                <input type="radio" class="radio pointRadio" value="0"
                       name="<?php echo Html::getInputName($model, 'connetID') ?>">
                <label>知识点</label>
                &nbsp;&nbsp;&nbsp;
                <input type="radio" class="radio chaptRadio" value="1" checked
                       name="<?php echo Html::getInputName($model, 'connetID') ?>">
                <label>章节</label>
                <br>
            <?php } ?>

            <?php
            if ($model->connetID == 0) {
              echo  frontend\widgets\extree\XTree::widget( array(
                    'model' => $model,
                    'attribute' => 'filesID',
                    'options' => array(
                        'htmlOptions' => array()
                    )));
            } elseif ($model->connetID == 1) {
                echo  frontend\widgets\extree\YTree::widget(array(
                    'model' => $model,
                    'attribute' => 'filesID',
                    'options' => array(
                        'htmlOptions' => array()
                    )));
            }
            ?>
        </div>
    </div>
</li>

<li>
    <div class="formL">
        <label><i></i>课程讲义：</label>
    </div>
    <div class="formR">
        <button id="handoutID" type="button" class="bg_green addDocBtn">选择讲义</button>
        <ul class="DocList">
            <li handout="<?php echo $handoutName->id; ?>" class="ac">讲义名称：<?php echo $handoutName->name ?></li>
        </ul>
        <input id="DocList" value="<?php echo $handoutName->id; ?>"
               name="<?php echo Html::getInputName($model, 'handoutID') ?>" type="hidden"/>
    </div>
</li>
<li>
    <div class="formL">
        <label><i></i>上课时间：</label>
    </div>
    <div class="formR">
        <input id="beginTime" name="<?php echo Html::getInputName($model, 'beginTime') ?>" type="text"
               onclick="WdatePicker({dateFmt:'yyyy-MM-dd H:m:s',minDate:'<?php echo date('Y-m-d h:i:s', time()) ?>'});"
               class="text" data-validation-engine="validate[required]" value="<?php echo $model->beginTime ?>">
        &nbsp;&nbsp;至&nbsp;&nbsp;
        <input id="finishTime" name="<?php echo Html::getInputName($model, 'finishTime') ?>" type="text"
               onclick="WdatePicker({dateFmt:'yyyy-MM-dd H:m:s',minDate:'<?php echo date('Y-m-d h:i:s', time()) ?>'});"
               class="text" data-validation-engine="validate[required]" value="<?php echo $model->finishTime ?>">
    </div>
</li>
<li style="margin-bottom: 25px">
    <div class="formL">
        <label> 广告图片：</label>
    </div>
    <div class="formR">

        <p class="addPic">
            <button type="button" class="bg_green uploadPicBtn" name="<?php echo Html::getInputName($model, 'url') ?>">上传图片
            </button>
            <?php
            $t1 = new frontend\widgets\xupload\models\XUploadForm;
            /** @var $this BaseController */
            echo  \frontend\widgets\xupload\XUploadSimple::widget( array(
                'url' => Yii::$app->urlManager->createUrl("upload/pic"),
                'model' => $t1,
                'attribute' => 'file',
                'autoUpload' => true,
                'multiple' => false,
                'options' => array(
                    "done" => new \yii\web\JsExpression('done'),
                ),
                'htmlOptions' => array(
                    'id' => 'fileupload',
                )
            ));
            ?>
        </p>
        <em class="em_text">文件格式限定为jpg,png</em>

        <div class="up_img">
        </div>
    </div>
</li>
<li>
    <div class="formL">
        <label>课程介绍：</label>
    </div>
    <div class="formR" style="width: 562px;">
        <?php
        echo \frontend\widgets\ueditor\MiniUEditor::widget(
            array(
                'id' => 'editor',
                'model' => $model,
                'attribute' => 'courseBrief',
                'UEDITOR_CONFIG' => array(
					'maximumWords' => '300',
                ),
            ));
        ?></div>
    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'courseBrief') ?>
</li>
</ul>
<p class="conserve">
    <button type="submit" class="bg_red_d w120">确&nbsp;&nbsp;定</button>
</p>
<?php \yii\widgets\ActiveForm::end(); ?>
</div>
</div>
<!--主体内容结束-->
<!--弹出框---使用讲义-->
<div id="DocBox" class="popBox DocBox hide" title="选择讲义">
    <div id="updatehandout">

    </div>
</div>

