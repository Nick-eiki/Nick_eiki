<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-16
 * Time: 下午4:35
 */
use frontend\components\CHtmlExt;
use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\EditionModel;
use frontend\models\dicmodels\GradeModel;
use frontend\models\dicmodels\KnowledgePointModel;
use frontend\models\dicmodels\SubjectModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title="教师-备课-公文包-上传资料";
$backend_asset = publicResources();
;
$this->registerJsFile($backend_asset . '/js/ztree/jquery.ztree.all-3.5.min.js');
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
?>
<script>
    $(function () {
        // JavaScript Document
        $('form').validationEngine({
            promptPosition: "centerRight",
            maxErrorsPerField: 1,
            showOneMessage: true,
            addSuccessCssClassToField: 'ok'
        });
        var m_type = "<?php echo $model->type;?>";
        if (m_type == 7) {
            $('.noPlan').hide();
        } else {
            $('.noPlan').show();
        }
        $('.doc_cls_radio').click(function () {
            if ($('#InformationPackForm_type_2').attr('checked') == "checked")$('.noPlan').hide();
            else $('.noPlan').show();
        })
    });
    //数据表单提交
    function getsubmit() {

        if ($('#InformationPackForm_type_2').attr('checked') !== "checked") {
            var chapKids = $('#val').val();
            if (chapKids == "") {
                popBox.alertBox('请选择章节或知识点！');
                return false;
            }
        }


        var url = $('#url').val();
        if (url == "") {
            popBox.alertBox('上传附件不能为空，请上传附件！');
            return false;
        }
        return true;
    }
    $(function(){

        var type =$('.treeParent').find(':radio[^name="<?php  echo Html::getInputName($model, 'contentType')?>"]:checked').val();
        if(type == 0){
            $('.treeParent').find('.pointTreeWrap').show();
            $('.treeParent').find('.pointArea').show();
            //$pa.find('.labelList').html()
        }
        if(type == 1){
            $('.treeParent').find('.chaptTreeWrap').show();
            $('.treeParent').find('.pointArea').show();
        }


        var zNodes =[];
        var zNodes2 =[];

//课时安排-----知识树/章节
        $('.pointBtn').live('click',function(){ //知识点radio
            $(this).siblings('.pointTreeWrap').show();
            $(this).siblings('.pointTreeWrap').find('input:hidden').prop('disabled',false);
            $(this).siblings('.chaptTreeWrap').hide();
            $(this).siblings('.chaptTreeWrap').find('input:hidden').prop('disabled',true);
        });

        $('.chaptBtn').live('click',function(){ //章节radio
            $(this).siblings('.pointTreeWrap').hide();
            $(this).siblings('.pointTreeWrap').find('input:hidden').prop('disabled',true);
            $(this).siblings('.chaptTreeWrap').show();
            $(this).siblings('.chaptTreeWrap').find('input:hidden').prop('disabled',false);
        });

        $('.pointTreeWrap .addPointBtn').live('click',function(){//编辑

            var grade = $("#grade").val();
            var subjectID = $("#subjectID").val();
            var url = "/ajaxTeacher/getKnowledge";
            $this = $(this);
            $.post(url,{'subjectID':subjectID,'grade':grade},function(msg){
                zNodes = msg.data;
                popBox.pointTree(zNodes,$this,"知识点","point");
            });
        });
        $('.chaptTreeWrap .addPointBtn').live('click',function(){//编辑
            var grade = $("#grade").val();
            var subjectID = $("#subjectID").val();
            var materials = $("#materials").val();
            var url = "/ajaxTeacher/GetChapter";
            $this = $(this);
            $.post(url,{subjectID:subjectID,grade:grade,materials:materials},function(msg){
                zNodes2 = msg.data;
                popBox.pointTree(zNodes2,$this,"章节","chapt");
            });
        })

    });
    addDone = function (e, data) {
        $.each(data.result, function (index, file) {
            if (file.error) {
                popBox.alertBox(file.error);
                return false;
            }
            $('#addimage').html('<li class="fl" vals="' + file.url + '">' + file.name + ' <i></i></li>  ');
        });
        var urls = [];

        $("li.fl").each(function (i) {
            urls.push($(this).attr('vals'));
        });
        $('#url').val(urls.join(','));

    };


</script>
<!--主体内容开始-->

<div class="currentRight grid_16 push_2">

<div class="crumbs"><a href="#">教师</a> &gt;&gt; <a href="#">公文包</a> &gt;&gt; 上传资料</div>

<div class="noticeH clearfix">
    <?php if($isEdit==1){ ?>
        <h3 class="h3L">上传资料</h3>
  <?php  }elseif($isEdit==2){ ?>
        <h3 class="h3L">修改资料</h3>
    <?php   }?>

</div>
<hr>
<div class="upload_data">
<?php $form =\yii\widgets\ActiveForm::begin( array(
    'enableClientScript' => false,
    'id' => 'form1'
))?>
<ul class="form_list upDataList">
<li>
    <div class="formL">
        <label>资料类型：</label>
    </div>
    <div class="formR">
        <?php
              if($isEdit=='2'){
         if($model->type ==1){ ?>
             教案
        <?php }elseif($model->type ==2){ ?>
             讲义
        <?php }elseif($model->type ==7){ ?>
         教学计划
      <?php   } ?>
                  <input  type="hidden" name="<?php echo Html::getInputName($model, 'type') ?>" value="<?php echo $model->type;?>">
       <?php }else {

            echo Html:: radioButtonList(Html::getInputName($model, "type"), $model->type, array('1' => '教案', '2' => '讲义', '7' => '教学计划'), array(
                "defaultValue" => false,
                "class" => 'doc_cls_radio',
                "separator" => '',
                "id" => Html::getInputId($model, "type")
            ));
            echo frontend\components\CHtmlExt::validationEngineError($model, 'type');
        }
        ?>

    </div>
</li>
<li>
    <div class="formL">
        <label><i></i>名称：</label>
    </div>
    <div class="formR">
        <input name="<?php echo Html::getInputName($model, 'name') ?>"
               id="<?php echo Html::getInputId($model, 'name') ?>"
               data-validation-engine="validate[required,maxSize[30]]"
               class="input_box text" type="text" value="<?php echo $model->name ?>"/>
        <span class="prompt">30字以内</span>
        <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'name') ?>
    </div>
</li>
<li class="noPlan">
    <div class="formL">
        <label><i></i>适用地区：</label>
    </div>
    <div class="formR">
        <?php
        echo CHtmlExt:: dropDownListAjax(Html::getInputName($model, "provience"), $model->provience, ArrayHelper::map(AreaHelper::getProvinceList(), 'AreaID', 'AreaName'), array(
            "defaultValue" => false, "prompt" => "请选择",
            'ajax' => array(
                'url' => Yii::$app->urlManager->createUrl('ajax/get-area'),
                'data' => array('id' => new \yii\web\JsExpression('this.value')),
                'success' => 'function(html){jQuery("#' . Html::getInputId($model, "city") . '").html(html).change();}'
            ),
            "id" => Html::getInputId($model, "provience")
        ));
        ?>
        <label>省</label>
        <?php
        echo CHtmlExt:: dropDownListAjax(Html::getInputName($model, "city"), $model->city, ArrayHelper::map(AreaHelper::getCityList($model->provience), 'AreaID', 'AreaName'), array(
            "defaultValue" => false, "prompt" => "请选择", "id" => Html::getInputId($model, "city"),
            'ajax' => array(
                'url' => Yii::$app->urlManager->createUrl('ajax/get-area'),
                'data' => array('id' => new \yii\web\JsExpression('this.value')),
                'success' => 'function(html){jQuery("#' . Html::getInputId($model, "county") . '").html(html).change();}'
            ),
        ));
        ?>
        <label>市</label>
        <?php
        echo CHtmlExt:: dropDownListAjax(Html::getInputName($model, "county"), $model->county, ArrayHelper::map(AreaHelper::getRegionList($model->city), 'AreaID', 'AreaName'), array(
            'data-validation-engine' => 'validate[required]', "defaultValue" => false, "prompt" => "请选择", "id" => Html::getInputId($model, "county"),
            'data-prompt-target' => "diqu_prompt",
            'data-prompt-position' => "inline"
        ));
        ?>
        <label>区</label>
        <span id="diqu_prompt"></span>
    </div>
</li>
<li>
    <div class="formL">
        <label><i></i>适用年级：</label>
    </div>
    <div class="formR">
        <?php
        echo CHtmlExt:: dropDownListAjax(Html::getInputName($model, 'grade'),
            $model->grade,
            ArrayHelper::map(GradeModel::model()->getList(), 'gradeId', 'gradeName'),
            array('data-validation-engine' => 'validate[required,custom[number]]',
                'id' => 'grade'));
        ?>
        <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'grade') ?>
    </div>
</li>
<li class="noPlan">
    <div class="formL">
        <label><i></i>科目：</label>
    </div>
    <div class="formR">
        <?php
        echo CHtmlExt:: dropDownListAjax(Html::getInputName($model, 'subjectID'),
            $model->subjectID,
            SubjectModel::model()->getList(),
            array('data-validation-engine' => 'validate[required,custom[number]]',
                'id' => 'subjectID'));
        ?>
        <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'subjectID') ?>
    </div>
</li>

<li class="noPlan">
    <div class="formL">
        <label><i></i>教材版本：</label>
    </div>
    <div class="formR">
        <?php
        echo CHtmlExt:: dropDownListAjax(Html::getInputName($model, 'materials'),
            $model->materials,
            EditionModel::model()->getListData(),
            array('data-validation-engine' => 'validate[required,custom[number]]',
                'id' => 'materials'));
        ?>
        <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'materials') ?>
    </div>
</li>
<li class="noPlan">
    <div class="formL">
        <label>公文相关性：</label>
    </div>
    <div class="formR">
        <div class="treeParent">
            <input type="radio" class="radio pointBtn" name="<?php  echo Html::getInputName($model, 'contentType')?>" value ='0' <?php if($model->contentType==0){echo "checked";}?>>
            <label>知识点</label>
            &nbsp;&nbsp;&nbsp;
            <input type="radio" class="radio chaptBtn" name="<?php  echo Html::getInputName($model, 'contentType')?>" value ='1' <?php if($model->contentType==1){echo "checked";}?>>
            <label>章节</label>
            <br>
            <div class="pointTreeWrap hide">
                <button type="button" class="btn20 addPointBtn">编辑知识树</button>
                <div class="pointArea hide">
                    <?php echo Html::hiddenInput(Html::getInputName($model, "chapKids"), ( $model->contentType == 0) ? $model->chapKids : "", ['class' => 'hidVal', 'id' => false,'disabled'=>$model->contentType != 0]) ?>
                    <h6>已选中知识点:</h6>
                    <ul class="labelList">
                        <?php if($model->contentType ==0){
                            $nodeList = KnowledgePointModel::findKnowledge($model->chapKids); ?>
                            <?php foreach ($nodeList as $item) { ?>
                                <li val="<?php echo $item->id ?>"><?php echo $item->name ?>
                                </li>
                            <?php }
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="chaptTreeWrap hide">
                <button type="button" class="btn20 addPointBtn">编辑章节</button>
                <div class="pointArea hide">
                    <?php echo Html::hiddenField(Html::getInputName($model, "chapKids"), ( $model->contentType == 1) ? $model->chapKids : "", ['class' => 'hidVal', 'id' => false,'disabled'=>$model->contentType != 1]) ?>
                    <h6>已选中章节:</h6>
                    <ul class="labelList">
                        <?php if($model->contentType==1){
                            $chaterList = ChapterInfoModel::findChapter($model->chapKids); ?>
                            <?php foreach ($chaterList as $item) { ?>
                                <li val="<?php echo $item->id ?>"><?php echo $item->chaptername ?>
                                </li>
                            <?php }

                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</li>

<li class="noPlan">
    <div class="formL">
        <label><i></i>自定义标签：</label>
    </div>
    <div class="formR">
        <input name="<?php echo Html::getInputName($model, 'tags') ?>"
               id="<?php echo Html::getInputId($model, 'tags') ?>"
               data-validation-engine="validate[required]"
               class="input_box text" type="text" value="<?php echo $model->tags ?>"/>
        <span class="ationTxt"> (多个标签用","隔开)</span>
        <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'tags') ?>
    </div>
</li>
<li>
    <div class="formL">
        <label><i></i>上传附件：</label>
    </div>
    <div class="formR">
        <div class="fl" style="font-size:12px; color:#999999;">
            <!--                        <div class="up_pic"> <span id="uploadPicBtn">选择文件</span></div>-->
            <p class="addPic btn  btnpop">
                <a href="javascript:;" class="a_button bg_green btn20 id_btn">选择文件</a>
                <?php
                $t1 = new frontend\widgets\xupload\models\XUploadForm;
                echo  \frontend\widgets\xupload\XUploadSimple::widget( array(
                    'url' => Yii::$app->urlManager->createUrl("upload/doc"),
                    'model' => $t1,
                    'attribute' => 'file',
                    'autoUpload' => true,
                    'multiple' => false,
                    'options' => array(
                        'acceptFileTypes' => new \yii\web\JsExpression('/\.(doc|doc?x|pdf)$/i'),
                        "done" => new \yii\web\JsExpression('addDone'),
                        "processfail" => new \yii\web\JsExpression('function (e, data) {var index = data.index,file = data.files[index]; if (file.error) {alert(file.error);}}')
                    ),
                    'htmlOptions' => array(
                        'id' => 't1',
                    )
                ));
                ?>

            </p>
            <em style="margin-left:10px;">文件格式限定为doc,docx或pdf(文件不能超过2M)</em><span id="url_error"></span>
        </div>

        <ul class="add_del" id="addimage">
            <?php if ($model->url) { ?>
                <li class="fl" vals="<?php echo $model->url; ?>">这里放上传名称 <i></i></li>
            <?php } ?>
        </ul>
    </div>
    <input type="hidden" id="url" value="<?php echo $model->url; ?>"
           name="<?php echo Html::getInputName($model, 'url') ?>"/>
</li>
<li>
    <div class="formL">
        <label>简介：</label>
    </div>
    <div class="formR">
        <div style="width: 562px;">
            <textarea name="<?php echo Html::getInputName($model, 'brief') ?>" id="<?php echo Html::getInputId($model, 'brief') ?>"><?php echo strip_tags($model->brief); ?></textarea>
        </div>
        <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'brief') ?>
    </div>
</li>
</ul>
<p class="conserve">
    <button type="submit" class="bg_red_d submitBtn" onclick="return  getsubmit();">确&nbsp;&nbsp;定</button>
</p>
<?php \yii\widgets\ActiveForm::end() ?>
</div>
</div>

<!--主体内容结束-->

<!--主体内容结束-->