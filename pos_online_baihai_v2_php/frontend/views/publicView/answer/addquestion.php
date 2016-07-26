<?php
/**
 * Created by Unizk.
 * User: ysd
 * Date: 14-10-31
 * Time: 下午2:41
 */
use frontend\models\dicmodels\SubjectModel;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $this yii\web\View */  $this->title="提问问题";
$this->registerJsFile(publicResources() . '/js/My97DatePicker/WdatePicker.js', [ 'position'=>\yii\web\View::POS_HEAD] );

$backend_asset = publicResources();
;
$this->registerJsFile($backend_asset . "/js/jquery.validationEngine-zh_CN.js");
$this->registerJsFile($backend_asset . "/js/jquery.validationEngine.min.js");
$this->registerJsFile($backend_asset . "/js/register.js");


?>
<script type="text/javascript">
    $(function(){
        $("#qa_add").click(function(){

            var type = $('#type').val();
            if(type == ''){
                popBox.errorBox("科目不能为空!");
                return false;
            }
            var title = $("#takeffice").val();
            if(title == ''){
                popBox.errorBox("描述不能为空!");
                return false;
            }else if(title.length > 140){
                popBox.errorBox("描述超过140字!");
                return false;
            }

            var title_num = UE.getEditor('editor').getContentTxt().length;
            if(title_num > 1000){
                popBox.errorBox("问题补充超过1000字!");
                return false;
            }

            var img_num = $('.up_test_list.clearfix li').length;
            if(img_num > 7){
                popBox.errorBox("最多上传6张图片!");
                return false;
            }
            //查询当天提问
            $.get("<?php echo Url::to("/answernew/check-answer"); ?>", {}, function(result){
                if(result.success){
                    //用于禁止多次提交
                    $("form").submit(function(){
                        $(":button",this).attr("disabled","disabled");
                    });
                    popBox.successBox("提问成功！");
                    $form = $('#form1');
                    $form.attr('method','post').attr("action","<?php echo app()->request->url?>").submit();
                }else{
                    popBox.errorBox(result.message);
                    return false;

                }

            });
           return true;
        });

        //科目列表
        $('.resultList.testClsList li').bind('click',function(){
            var type= $(this).attr('type');
            $('#type').val(type);
        });

        //抛向宇宙
        $('#sendto_world').click(function(){
            if($("[name = more_idea]:checkbox").attr("checked")){
                $(this).val(1);
                $(this).attr('checked',true);
            }else{
                $(this).val(0);
                $(this).attr('checked',false);
            }
        });

    })
</script>
<script type="text/javascript">
    /*剩余数字*/
    function checkLength(which) {
        var maxChars = 140;
        if (which.value.length > maxChars)
            which.value = which.value.substring(0,maxChars);
        var curr = maxChars - which.value.length;
        document.getElementById("chLeft").innerHTML = curr.toString();
    }

</script>
<div class="grid_19 main_r">
    <div class="main_cont AQ_ask">
        <div class="title">
            <h4>我的提问</h4>
        </div>

    <?php

    $form =\yii\widgets\ActiveForm::begin( array('enableClientScript' => false, 'id'=>"form1"  ))
    ?>
            <div class="form_list" style="">
                <div class="row">
                    <div class="formL">
                        <label>问题隶属科目：</label>
                    </div>
                    <div class="formR">
                        <?php //echo Html::radioList('type','',SubjectModel::model()->getList(),['data-validation-engine' => 'validate[required]','data-errormessage-value-missing' => "请选择一门科目",'separator'=>'&nbsp']) ?>
                        <ul class="resultList  clearfix testClsList" >
                            <?php
//                                $subject = SubjectModel::model()->getData();
//                                $teaSub = user()->getModel(false)->subjectID;
                            $department = loginUser()->getModel()->department;
                            $subjectArray = SubjectModel::model()->getSubjectByDepartmentListData($department);
                            $teaSub = loginUser()->getModel()->subjectID;
                            foreach($subjectArray as $key=>$val){
	                        ?>
	                            <li type="<?=$key ?>" <?php if($key == $teaSub){echo 'class="ac"';}?>><a href="javascript:;"><?= $val?></a></li>
                            <?php } ?>
                            <input type="hidden" value="<?= $teaSub;?>" name="type" id="type">
                        </ul>
                    </div>
                </div>
            </div>
            <hr>

            <div class="replen_list textareaBox">
                <h4 class="font14">一句话描述您的疑问：</h4>
                <div class="textareaBox pr">
                    <textarea class="textarea" id="takeffice" onkeyup="checkLength(this);" name="<?php echo Html::getInputName($model, 'title') ?>" ></textarea>
                    <span class="placeholder"></span>
                    <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'title') ?>
                    <div class="btnArea"> <em class="txtCount">你还可以输入 <b class="num" id="chLeft">140</b> 字</em></div>
                </div>
                <!--<p class="province_m">还可以输入<i class="JS_num" id="chLeft">500</i>字</p>-->
            </div>


            <div class="replen_list textareaBox_pro2" style="border:none">
                <h4 class="font14">问题补充（选填）：</h4>
                <div><?php
                    echo \frontend\widgets\ueditor\MiniNoImgUEditor::widget(
                        array(
                            'id'=>'editor',
                            'model'=>$model,
                            'attribute'=>'detail',
                            'UEDITOR_CONFIG'=>array(
                                'maximumWords'=>1000,
                                'initialFrameHeight' => '200',
                            ),
                        ));
                    ?></div>
                <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'detail') ?>
            </div>

            <div class="form_list ">
                <div class="row">
                    <div class="formL">
                        <label>添加图片:</label>
                    </div>
                    <div class="formR" style="width:700px" >
                        <div class="imgFile">
                            <span class="gray_d">最多可添加6张图片</span>
                            <ul class="up_test_list clearfix ">
                                <li class="more">
                                    <?php
                                    $t1 = new frontend\widgets\xupload\models\XUploadForm;
                                    /** @var $this BaseController */
                                    echo  \frontend\widgets\xupload\XUploadSimple::widget( array(
                                        'url' => Yii::$app->urlManager->createUrl("upload/pic"),
                                        'model' => $t1,
                                        'attribute' => 'file',
                                        'autoUpload' => true,
                                        'multiple' => true,
                                        'options' => array(
                                            'acceptFileTypes' => new \yii\web\JsExpression('/(\.|\/)(jpg|png|jpeg|gif)$/i'),
                                            "done" => new \yii\web\JsExpression('doneTwo'),
                                            "processfail" => new \yii\web\JsExpression('function (e, data) {var index = data.index,file = data.files[index]; if (file.error) {alert(file.error);}}')
                                        ),
                                        'htmlOptions' => array(
                                            'id' => 'imgupload',
                                        )
                                    ));
                                    ?>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>

            <p class="submit_b">
                    <button type="button" id="qa_add" class="btn40 w140 bg_blue">提交问题</button>

                    <input type="checkbox"  checked="checked" value="1" name="more_idea" id="sendto_world">
                    <label>联盟</label>
                <input type="checkbox"  value="2" name="more_idea" id="sendto_world">
                <label>学校</label>
                <input type="checkbox"  value="3" name="more_idea" id="sendto_world">
                <label>班级</label>
            </p>

<?php \yii\widgets\ActiveForm::end(); ?>
    </div>
</div>
<!--主体内容结束-->

<script type="text/javascript">
    k=0;
    doneTwo = function(e, data) {
        $.each(data.result, function (index, file) {
            k++;
            if(file.error){
                popBox.errorBox(file.error);
                return ;
            }
            $('<li><input type="hidden" id="imgurls" name="imgurls[]" value="' + file.url + '" /> <img src="' + file.url + '" alt=""><span class="delBtn"></span></li>').insertBefore( $(e.target).parent());
        });
    };

</script>