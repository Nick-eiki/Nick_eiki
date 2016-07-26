<?php
/**
 * Created by Unizk.
 * User: ysd
 * Date: 14-10-31
 * Time: 下午2:41
 */
/* @var $this yii\web\View */
/* @var $this yii\web\View */  $this->title="补充问题";
$this->registerJsFile(publicResources() . '/js/My97DatePicker/WdatePicker.js', [ 'position'=>\yii\web\View::POS_HEAD] );

$backend_asset = publicResources();

$this->registerJsFile($backend_asset . "/js/jquery.validationEngine-zh_CN.js");
$this->registerJsFile($backend_asset . "/js/jquery.validationEngine.min.js");
$this->registerJsFile($backend_asset . "/js/register.js");
?>

<script type="text/javascript">
    $(function(){
        $("#qa_modify").click(function(){
            var img_num = $('.up_test_list.clearfix li').length;
            if(img_num > 7){
                popBox.errorBox("最多上传6张图片!");
                return false;
            }

            var title_num = UE.getEditor('editor').getContentTxt().length;
            if(title_num > 1000){
                popBox.errorBox("问题补充超过1000字!");
                return false;
            }

            return true;
        });
        //用于禁止多次提交
        $("form").submit(function(){
            $(":submit",this).attr("disabled","disabled");
        });
    })
</script>

<div class="grid_19 main_r">
    <div class="main_cont AQ_ask">
        <div class="title">
            <h4>补充问题</h4>
        </div>
    <?php
    /** @var $form CActiveForm */
    $form =\yii\widgets\ActiveForm::begin( ['enableClientScript' => false,])
    ?>
        <div class="replen_list textareaBox">
            <h4 class="font14">一句话描述您的疑问：</h4>
            <div class="textareaBox pr">
            <textarea class="re_input_box JS_textarea takeffice" readonly><?php echo $result->aqName; ?></textarea>
            </div>
            <!--<p class="province_m">还可以输入<i class="JS_num" id="chLeft">500</i>字</p>-->
        </div>
        <div class="replen_list textareaBox_pro2" style="border:none">
            <h4 class="font14">问题补充（选填）：</h4>

            <div><?php
                echo \frontend\widgets\ueditor\MiniNoImgUEditor::widget(
                    array(
                        'id' => 'editor',
                        'model' => $model,
                        'attribute' => 'detail',
                        'UEDITOR_CONFIG' => array(
                            'maximumWords'=>1000,
                            'initialContent' => $result->aqDetail,
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
                        <?php
                        $images = $result->imgUri;
                        if(isset($images) && !empty($images)){
                            $image = explode(',',$images);
                            foreach($image as $val){
                                ?>
                                <li><input type="hidden" name="imgurls[]" value="<?=$val; ?>">
                                    <img src="<?=$val; ?>" alt="">
                                    <span class="delBtn"></span>
                                </li>
                            <?php  }}?>
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
                                    'acceptFileTypes' => new \yii\web\JsExpression('/(\.|\/)(gif|jpg|png|jpeg)$/i'),
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
            <button type="submit" class="btn40 w140 bg_blue" id="qa_modify">提交问题</button>
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