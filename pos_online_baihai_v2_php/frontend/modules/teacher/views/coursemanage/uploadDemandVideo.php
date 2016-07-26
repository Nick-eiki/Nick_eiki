<?php
/*
* Created by PhpStorm.
 * User: wgl
* Date: 14-11-5
* Time: 下午11:56
*/
/** @var $this CoursemanageController */
use frontend\components\CHtmlExt;
use frontend\components\helper\AreaHelper;
use frontend\models\ClassHourForm;
use frontend\models\dicmodels\EditionModel;
use frontend\models\dicmodels\GradeModel;
use frontend\models\dicmodels\SubjectModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title="上传视频";
$this->registerJsFile(publicResources() . '/js/My97DatePicker/WdatePicker.js', [ 'position'=>\yii\web\View::POS_HEAD] );

$backend_asset = publicResources();
;
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/ztree/jquery.ztree.all-3.5.min.js');
$hourListCount = count($hourList);


$teacherClassCount = count($teacherClassList);
//$teacherGroupCount = count($teacherGroupList);
?>
<script type="text/javascript">
	$(function(){
            var zNodes=[];
            var zNodes2 =[];

//课时安排-----知识树/章节
            var treeCls;//  true:知识树 false:章节

            function check(btn){//是否已经选中,通过btn找到相邻元素
                var pa=btn.parent('.treeParent');
                var checkLi=pa.find('li');
                if(checkLi.length>0){
                    return true;
                }
            }
            //根据选取名称不一样，获取知识点或章节
            $('#subjectID,#schoolLevel,#grade').change(function(){
                $(".treeParent input[type=radio]").attr("checked", false);
                $('.pointArea').hide();
                $('.addPointBtn').hide();
                $('.labelList').empty();
                $('.hidVal').val('');
            });

            function clear(btn){//清除已经选中,的通过btn找到相邻元素
                var pa=btn.parent('.treeParent');
                pa.find('.pointArea').hide();
                pa.find('.labelList').empty();
                pa.find('.hidVal').val('');
            }

            $('.pointRadio').live('click',function(){ //知识点radio
                $(this).nextAll('.addPointBtn').show().text('编辑知识点');
                var subjectID =$('#subjectID').val();
                var grade =$('#grade').val();
                $.post("<?php echo url("ajaxteacher/get-knowledge")?>", {"subjectID": subjectID, "grade": grade}, function (data) {
                    if (data.success) {
                        zNodes = data.data;
                    }
                });
                if(check($(this)) && treeCls==true)	return true;
                else clear($(this));
                treeCls=true;
            });

            $('.chaptRadio').live('click',function(){ //章节radio
                $(this).nextAll('.addPointBtn').show().text('编辑章节');
                var subjectID =$('#subjectID').val();
                var materials =$('#NewDemandForm_version').val();
                var grade =$('#grade').val();
                $.post("<?php echo url("ajaxteacher/get-chapter")?>", {subjectID: subjectID,materials:materials,grade:grade}, function (data) {
                    if (data.success) {
                        zNodes2 = data.data;
                    }
                });
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

            $('.addPointBtn').live('click',function(){//编辑
                var arr=sel_zNodes(zNodes,zNodes2);
                popBox.pointTree(arr[0],$(this),arr[1]);
            });

        function chaptTitle(){//第?节 重排序列号
            var title=$('.chaptTitle');
            title.each(function(index, element) {
                $(this).text('第'+(index+1)+'节');
            });
        }

        var hourListCount = <?php echo $hourListCount;?>;
        var appe_html =<?php $out = $this->render("_hour_view",array("hourItem"=> new ClassHourForm(),'key'=>"#temp#"));echo json_encode($out);?>;

        $('.addClassesBtn').live('click', function () {
            $('#classesInput').append(appe_html.replace(/#temp#/g, ++hourListCount));
            jQuery("input[type='file']").fileupload({'done':done,'url':'/upload/video','autoUpload':true,'formData':{},'dataType':'json','maxNumberOfFiles':1});
        });

        $('table .delBtn').live('click',function(){
            $(this).parents('tr').remove();
            chaptTitle()
        });

//添加课时----使用讲义弹框

        $('.addDocBtn').live('click',function(){
            var doc="";
            var _this=$(this);
            var materials =$('#materials').val();
            var gradeId = $('#grade').val();
            var subjectId =$("#subjectID").val();
            $.post("<?php echo url('teacher/coursemanage/get-doc')?>",{subjectId: subjectId,materials:materials,gradeId:gradeId},function(data){
                $('#updatehandout').html(data);
                $( "#DocBox" ).dialog( "open" );
                $('#DocBox ul li').removeClass('ac').live('click',function(){
                    var handout = $(this).attr('handout');
                    $("#DocList").val(handout);
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
                            var it=$(doc).attr('handout');
                            _this.next('.DocList').next('input').val(it);
                            _this.text('修改讲义');
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
                        $( '#<?php echo   Html::getInputId($model, 'teacherID')  ?>').val( $(this).find($(".teacherListBox .ac")).attr('teacherid'));
                        $('.sel_teacher #teacher_btn').prev('span').css('margin-right',20).text($( ".teacherListBox .ac").text());
                        $('.sel_teacher #teacher_btn').text('重新选择');
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
        $('.sel_teacher #teacher_btn').live('click',function(){
            var _this=$(this);
            var schoolId = '<?php echo $schoolModel;?>';
            $.post("<?php echo url('teacher/coursemanage/get-teacher')?>",{schoolId:schoolId},function(data){
                $('#updateTeacher').html(data);
                $( "#teacherListBox" ).dialog( "open" );
                $( ".teacherListBox li").click(function(){
                    $(this).addClass('ac').siblings().removeClass('ac');
                })
            });

        });

		//删除当前添加的名称
		$('.del_js').live('click',function(){
			$(this).parent('li').remove();

		})
	})
</script>

<script type="text/javascript">
    $(function () {
        $('#form1').submit(function(){
            //判断验证是否通过
            if ($(this).validationEngine('validate') == false){
                return false;
            }

			if(editor.getPlainTxt().length>301){
				popBox.alertBox('课程介绍超过300字数限制，请重新编辑！');
				return false;
			}

			if($('.hidVal').val() == '') {
				popBox.alertBox('请选择知识点或章节！');
				return false;
			}
			if($('.handouts').val() == '') {
				popBox.alertBox('请选择讲义！');
				return false;
			}
			if($('.video_url').val() == '') {
				popBox.alertBox('请上传视频！');
				return false;
			}

			if($('#NewDemandForm_teacherID').val() == ''){
				popBox.alertBox('请选择授课老师！');
				return false;
			}
        })
    });
    var number="";
    $('.addPic').live('click',function(){
        number=($(this).children('.numberVideo').attr("number"));
    });
    done = function (e, data) {
        $.each(data.result, function (index, file) {
            if(file.error){
                alert(file.error);
                return ;
            }
            $('.video_url').val(file.url);
            $('.videoList'+number).html('上传成功!');
        })
    }

</script>


<!--主体内容开始-->

<div class="currentRight grid_16 push_2 upload_r">

<div class="noticeH clearfix">
	<h3 class="h3L">上传视频</h3>
</div>
<hr>
<div class="upload_video">
<?php  /** @var $form CActiveForm */
$form =\yii\widgets\ActiveForm::begin( array(
	'enableClientScript' => false,
    'id' => 'form1'
))?>
<ul class="form_list uploadVedioList">
<li>
	<div class="formL">
		<label><i></i>视频类型：</label>
	</div>
	<div class="formR weekly_lesson">
        <?php
        echo CHtmlExt:: dropDownListAjax(Html::getInputName($model, "type"),$model->type, array('0'=>'精品课程'), array(
            "defaultValue" => false,
            "id" => Html::getInputId($model, "type")
        ));
        ?>
        <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'type') ?>
	</div>
</li>
<li>
	<div class="formL">
		<label><i></i>地区：</label>
	</div>
	<div class="formR">
		<?php
		echo CHtmlExt:: dropDownListAjax(Html::getInputName($model, "provience"), $model->provience, ArrayHelper::map(AreaHelper::getProvinceList(), 'AreaID', 'AreaName'),
			array(
				"defaultValue" => false, "prompt" => "请选择",
				'ajax' => array(
					'url' => Yii::$app->urlManager->createUrl('ajax/get-area'),
					'data' => array('id' => new \yii\web\JsExpression('this.value')),
					'success' => 'function(html){jQuery("#' . Html::getInputId($model, "city") . '").html(html).change();}'
				),
				"id" => Html::getInputId($model, "provience"),

			));
		?>
		<?php
		echo CHtmlExt:: dropDownListAjax(Html::getInputName($model, "city"), $model->city, ArrayHelper::map(AreaHelper::getCityList($model->provience), 'AreaID', 'AreaName'), array(
			"defaultValue" => false, "prompt" => "请选择", "id" => Html::getInputId($model, "city"),
			'ajax' => array(
				'url' => Yii::$app->urlManager->createUrl('ajax/get-area'),
				'data' => array('id' => new \yii\web\JsExpression('this.value')),
				'success' => 'function(html){jQuery("#' . Html::getInputId($model, "country") . '").html(html).change();}'
			),
		));
		?>
		<?php
		echo CHtmlExt:: dropDownListAjax(Html::getInputName($model, "country"), $model->country, ArrayHelper::map(AreaHelper::getRegionList($model->city), 'AreaID', 'AreaName'),
			array(
				'data-validation-engine' => 'validate[required]', "defaultValue" => false, "prompt" => "请选择", "id" => Html::getInputId($model, "country"),
				'data-prompt-target' => "county_prompt",
				'data-prompt-position' => "inline",
				'data-errormessage-value-missing' => "所在地不能为空",
			));?>
	</div>
</li>

<li>
    <div class="formL">
        <label><i></i>班级：</label>
    </div>
    <div class="formR">
        <?php $tcItem = array_shift($teacherClassList); ?>
        <?php
        echo $form->dropDownList($tcItem, 'classID', ArrayHelper::map(loginUser()->getClassInfo(), 'classID', 'className'),
            [
                'data-validation-engine' => 'validate[required]', "defaultValue" => false,
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
		<label><i></i>科目：</label>
	</div>
	<div class="formR">

        <?php
        echo Html::dropDownList(Html::getInputName($tcItem, 'subjectNumber'),
            $tcItem->subjectNumber,
            SubjectModel::model()->getList(),
            array(
                'data-validation-engine' => 'validate[required,custom[number]]',
                'id' =>'subjectID',
                'prompt' => '请选择',
            ));
        ?>
	</div>
</li>
<li>
	<div class="formL">
		<label><i></i>教材版本：</label>
	</div>
	<div class="formR">
		<?php echo $form->dropDownList($model, 'version', EditionModel::model()->getListData(),
			[
				'data-validation-engine' => 'validate[required]', "defaultValue" => false,
				"prompt" => "请选择",
				'data-prompt-target' => "versionID_prompt",
				'data-prompt-position' => "inline",
				'data-errormessage-value-missing' => "版本不能为空",
			]
		);
		?>
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
            array(
                'data-validation-engine' => 'validate[required,custom[number]]',
                'id' => 'grade',
                "prompt" => "请选择",
                'data-errormessage-value-missing' => "年级不能为空",
            ));
        ?>
    </div>
</li>

<li>
	<div class="formL">
		<label><i></i>课程名称：</label>
	</div>
	<div class="formR">
		<input type="text" class="text" value="<?php echo $model->courseName ?>"
		       name="<?php echo Html::getInputName($model, 'courseName') ?>"
               data-validation-engine="validate[required,maxSize[30]]"
			>
		<?php echo frontend\components\CHtmlExt::validationEngineError($model, 'courseName') ?>
	</div>
</li>
<li>
	<div class="formL">
		<label>课程介绍：</label>
	</div>
	<div class="formR">
        <div style="width: 562px;"><?php
            echo \frontend\widgets\ueditor\MiniUEditor::widget(
                array(
                    'id'=>'editor',
                    'model'=>$model,
                    'attribute'=>'courseBrif',
                    'UEDITOR_CONFIG'=>array(
                        'initialContent'=>'',
						'maximumWords' => 300,
                    ),
                ));
            ?></div>
		<?php echo frontend\components\CHtmlExt::validationEngineError($model, 'courseBrif') ?>
	</div>
</li>
<li class="classesTable classesTable_js">
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
			<th><i style="color: red"> * </i> 课时安排</th>
			<th><i style="color: red"> * </i> 节次名称</th>
			<th><i style="color: red"> * </i> 讲义</th>
			<th><i style="color: red"> * </i> 视频</th>
			<th width="50"><i style="color: red"> * </i> 操作</th>
		</tr>
		</thead>
		<tbody id="classesInput">
		<tr>
            <?php $hourItem = array_shift($hourList);?>
            <td class="Valign"><span class="chaptTitle">第1节</span>
                <?php echo Html::activeHiddenInput($hourItem, '[0]cNum',array('value'=>'1')); ?>
            </td>
            <td>
                <input type="text" class="text" name="<?php echo Html::getInputName($hourItem,'[0]cName')?>"  data-validation-engine="validate[required,maxSize[30]]">
                <div class="treeParent">
                    <input type="radio" class="radio pointRadio"  value="0" name="<?php echo Html::getInputName($hourItem,'[0]type')?>">
                    <label>知识点</label>
                    &nbsp;&nbsp;&nbsp;
                    <input type="radio" class="radio chaptRadio" value="1" name="<?php echo Html::getInputName($hourItem,'[0]type')?>">
                    <label>章节</label>
                    <br>
                    <button type="button" class="bg_green_l addPointBtn hide"></button>
                    <div class="pointArea hide">
                        <h6>已选中知识点:</h6>
                        <ul class="labelList">
                        </ul>
                        <input id="val" class="hidVal" type="hidden" name="<?php echo Html::getInputName($hourItem, '[0]kcid')?>"  value="" />
                    </div>
                </div>
            </td>

            <td><button id="handoutID" type="button" class="addDocBtn">使用讲义</button>
                <ul class="DocList">
                </ul>
                <input class="handouts addHour" id="ClassHourForm_0_teachMaterialID"  name="ClassHourForm[0][teachMaterialID]" id="DocList" value="" type="hidden" />
            </td>
            <td>
                <p class="addPic" >
                    <a href="javascript:;" class="a_button bg_green_l addVideoBtn">添加视频</a>
                    <?php
                        $t1 = new frontend\widgets\xupload\models\XUploadForm;
                        echo  \frontend\widgets\xupload\XUploadSimple::widget( array(
                            'url' => Yii::$app->urlManager->createUrl("upload/video"),
                            'model' => $t1,
                            'attribute' => 'file',
                            'autoUpload' => true,
                            'multiple' => false,
                            'options' => array(
                                'maxFileSize' => 40*1024*1024,
								'acceptFileTypes' => new \yii\web\JsExpression('/(\.|\/)(avi|flv|mkv|wmv|mov|rmvb|mp4)$/i'),
                                'done' => new \yii\web\JsExpression('done'),
                                "processfail" => new \yii\web\JsExpression('function (e, data) {var index = data.index,file = data.files[index]; if (file.error) {alert(file.error);}}')
                            ),
                            'htmlOptions' => array(
                                'id' => 'fileupload',
                            )
                        ));
                    ?>

                    <input class="video_url numberVideo" number="0" id="ClassHourForm_0_videoUrl" name="<?php echo Html::getInputName($hourItem, '[0]videoUrl')?>" type="hidden"  />
                    <ul class="videoList0">
                    </ul>
                </p>
            </td>

            <td class="Valign"><span class="delBtn">删除</span></td>
		</tr>
        <?php foreach($hourList as $key=>$hourItem){?>
            <?php echo $this->render('_hour_view',array('hourItem'=>$hourItem,'key'=>$key+1))?>
        <?php    } ?>
		</tbody>
	</table>
	<button type="button" class="fr bg_red_l addClassesBtn">添加课时</button>
</li>
<li class="look_z">
    <div class="formL">
        <label><i></i>授课老师：</label>
    </div>
    <div class="formR sel_teacher">
        <span></span><button type="button" id="teacher_btn" class="btn20 bg_green">选择老师</button>
    </div>
    <?php echo Html::activeHiddenInput($model, 'teacherID'); ?>
    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'teacherID') ?>
</li>

<li class="look_z">
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


</ul>
<p class="conserve">
    <input type="hidden" name="<?php echo Html::getInputName($model, 'creatorID') ?>" value="<?php echo user()->id;?>" >
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
<!--老师列表-->
<div class="popBox teacherListBox hide" id="teacherListBox" title="选择教师">
    <div id="updateTeacher">
    </div>

</div>
<style>
	.box{ margin: 10px 20px;  height:100px; width:100px; background-color:#fff;box-shadow:0 -2px 3px red,0 2px 3px red;}
</style>