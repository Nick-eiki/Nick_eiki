<?php
/**
 * Created by unizk.
 * User: ysd
 * Date: 2015/4/16
 * Time: 15:24
 */
use common\models\pos\SeHomeworkTeacher;
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title='上传作业';
$backend_asset = publicResources_new();
;
$this->registerJsFile($backend_asset . '/js/echarts/echarts-all.js');
?>

<script>
    $(function(){

        $('.confirm').click(function(){

            var up_img = $("input[name='picurls[]']").size();
            if(up_img == 0){
                popBox.alertBox('请上传题目详情！');
                return false;
            }

            var _this = $(this);
            $upWork = $('#upWork');
            $.post($upWork.attr('action'), $upWork.serialize(),function(data){
                if(data.success){
                    _this.hide();
                    $('.amend').show();
                    $('.up_test_list .delBtn').hide();
                    $('.up_test_list .more').hide();
                    $('.cancel').hide();
                    $('.showTask').show();
                    $('.message').show();

                }else{
                   // popBox.alertBox(data.message);
                }
            });

        });

        $('.message').click(function(){
            var _this = $(this);
            var homeworkId = _this.attr('dataType');
            $.post('<?=url('/teacher/managetask/send-message-by-object-id');?>',{homeworkid:homeworkId},function(data){
                if(data.success){
                    _this.text('已通知');
                    location.href="<?=url('/teacher/resources/collect-work-manage'); ?>";
                }
            });

        });

        $('.amend').click(function(){
            $(this).hide();
            $('.confirm').show();
            $('.up_test_list .delBtn').show();
            $('.up_test_list .more').show();
            $('.showTask').hide();
            $('.message').hide();
        });

        $('.showTask').hide();
    })
</script>

<div class="grid_19 main_r">
    <div class="notice main_cont test_up">
        <div class="title">
            <a href="<?= url('teacher/resources/collect-work-manage');?>" class="txtBtn backBtn"></a>
            <h4>上传作业</h4>
        </div>
        <?php echo Html::beginForm('/teacher/managetask/new-update-work','post',['id'=>'upWork']);?>
        <div class="form_list no_padding_form_list teac_up">
            <div class="row" style="padding-top:27px;">
                <div class="formL">
                    <label><i></i>作业名称</label>
                </div>
                <div class="formR">
                    <?php  /* @var    $workContent SeHomeworkTeacher   */?>
                    <span><?=$workContent->name; ?></span>
                </div>
            </div>
            <div class="row teac_up_border">
                <div class="formL">
                    <label><i>*</i>上传作业</label>
                </div>
                <div class="formR" style="width:68%;">
                    <div class="imgFile">
                        <ul class="up_test_list clearfix up_img">
                            <?php
                            $images =$homeworkImages;
                            if(isset($images) && !empty($images)){
                                foreach($images as $val){
                                    ?>
                                    <li><input type="hidden" name="picurls[]" value="<?=$val['url']; ?>">
                                        <img src="<?=$val['url']; ?>" alt="">
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
                                        'acceptFileTypes' => new \yii\web\JsExpression('/(\.|\/)(jpg|png|jpeg)$/i'),
                                        'maxFileSize' => 2*1024*1024,
                                        "done" => new \yii\web\JsExpression('done'),
                                        "processfail" => new \yii\web\JsExpression('function (e, data) {var index = data.index,file = data.files[index]; if (file.error) {alert(file.error);}}')
                                    ),
                                    'htmlOptions' => array(
                                        'id' => 'fileupload',
                                    )
                                ));
                                ?>
                            </li>
                            <input class="paperRoute" name="content" type="hidden"/>


                        </ul>
                        <input type="hidden" name="homeworkid" value="<?=app()->request->getParam('homeworkid'); ?>">
                        <div class="teacher_up_test">
                            <button type="button" class="bg_blue confirm w120  font14 btn40" style="margin-right:20px;">确定</button>
                            <button type="button" class="bg_blue_l w120 btn40 modifyBtn hide amend" style="margin-right:20px;">修改作业</button>
                            <a class="bg_blue_l w120 btn40  a_button showTask" href="<?=url('/teacher/managetask/new-update-work-detail',array('homeworkid'=>$workContent->id)); ?>">
                                预览作业
                            </a>

                        </div>

                    </div>
                </div>
            </div>
            <?= Html::endForm();?>




        </div>
        <div class="two_dimension_code">
            <h4 class="font12">扫一扫，轻松布置作业</h4>
            <img width="100px" height="100px" src="<?= url('qrcode/zy/'.$workContent->id) ?>" alt="">
        </div>

    </div>
</div>

<!--主体end-->

<script>
    k=0;
    done = function(e, data) {

        $.each(data.result, function (index, file) {
            k++;
            if(file.error){
                popBox.errorBox(file.error);
                return ;
            }
            var url = $('.paperRoute').val();
            if (url.length == 0) {
                $('.paperRoute').val(file.url);
            } else {
                $('.paperRoute').val(url + ',' + file.url);
            }
            $('<li><input type="hidden" name="picurls[]" value="' + file.url + '" /> <img src="' + file.url + '" alt=""><span class="delBtn"></span></li>').insertBefore('.more');

        });
    };
    $('#form1').validationEngine({
        validateNonVisibleFields:true,
        promptPosition:"centerRight",
        maxErrorsPerField:1,
        showOneMessage:true,
        addSuccessCssClassToField:'ok'
    });

</script>