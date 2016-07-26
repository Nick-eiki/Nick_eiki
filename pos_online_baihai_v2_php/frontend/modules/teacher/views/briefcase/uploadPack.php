<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-1-19
 * Time: 下午12:07
 */
use frontend\models\dicmodels\SubjectModel;
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title='教师-备课--素材库--添加素材';
$backend_asset = publicResources();
;
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);
?>

    <script>
        function getsubmit(){
            var url = $('#url').val();
            if(url ==""){
                popBox.alertBox('上传附件不能为空，请上传附件！');
                return false;
            }
            return true;
        }
        addDone = function (e, data) {
            $.each(data.result, function (index, file) {
                if (file.error) {
                    popBox.alertBox(file.error);
                    return false;
                }
                $('#addimage').html('<li class="fl" vals="' + file.url + '">'+file.name+' <i></i></li>  ');
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
        <?php  $infoId = app()->request->getParam('infoId','');
            if($infoId ==''){ ?>
                <div class="crumbs"><a href="#">教师</a> &gt;&gt; <a href="<?php echo url('teacher/briefcase/data-list')?>">素材库</a> &gt;&gt; 添加素材</div>
                <div class="noticeH clearfix">
                    <h3 class="h3L">添加素材</h3>
                </div>
          <?php  }else{ ?>
                <div class="crumbs"><a href="#">教师</a> &gt;&gt; <a href="<?php echo url('teacher/briefcase/data-list')?>">素材库</a> &gt;&gt; 修改素材</div>
                <div class="noticeH clearfix">
                    <h3 class="h3L">修改素材</h3>
                </div>
         <?php   }
        ?>

        <hr>
        <div class="upload_data">
                <?php $form =\yii\widgets\ActiveForm::begin( array(
                    'enableClientScript' => false,
                    'id' => 'form1'
                ))?>
                <ul class="form_list upDataList data_up">
                    <li>
                        <div class="formL">
                            <label><i></i>素材名称：</label>
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
                    <li>
                        <div class="formL">
                            <label>科目：</label>
                        </div>
                        <div class="formR">
                            <?php
                            echo Html::dropDownList(Html::getInputName($model, 'subjectID'),
                                $model->subjectID,
                                SubjectModel::model()->getList(),
                                array('data-validation-engine' => 'validate[required,custom[number]]',
                                    'id' => 'subjectID'));
                            ?>
                            <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'subjectID') ?>
                        </div>
                    </li>
                    <li>
                        <div class="formL">
                            <label>自定义标签：</label>
                        </div>
                        <div class="formR">
                            <input name="<?php echo Html::getInputName($model, 'tags') ?>"
                                   id="<?php echo Html::getInputId($model, 'tags') ?>"
                                   class="input_box text" type="text" value="<?php echo $model->tags ?>"/>
                            <span class="prompt"> (多个标签用","隔开,例如"数学,教案,勾股定理")</span>
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
                                    <a href="javascript:" class="a_button bg_green id_btn">选择文件</a>
                                    <?php
                                    $t1 = new frontend\widgets\xupload\models\XUploadForm;
                                    echo  \frontend\widgets\xupload\XUploadSimple::widget( array(
                                        'url' => Yii::$app->urlManager->createUrl("upload/source"),
                                        'model' => $t1,
                                        'attribute' => 'file',
                                        'autoUpload' => true,
                                        'multiple' => false,
                                        'options' => array(
                                            'acceptFileTypes' => new \yii\web\JsExpression('/\.(doc|doc?x|pdf|ppt|jpg|png|avi|flv|mkv|wmv|mov|rmvb|mp4|mp3|wav)$/i'),
                                            "done" => new \yii\web\JsExpression('addDone'),
                                            "processfail" => new \yii\web\JsExpression('function (e, data) {var index = data.index,file = data.files[index]; if (file.error) {alert(file.error);}}')
                                        ),
                                        'htmlOptions' => array(
                                            'id' => 't1',
                                        )
                                    ));
                                    ?>

                                </p>
                                <em style="margin-left:10px;">文件格式限定为doc,pdf,ppt,jpg,png或视频或音频(文件不能超过4M)</em><span id="url_error"></span>
                            </div>

                            <ul class="add_del" id="addimage">
                                <?php if($model->url){ ?>
                                    <li class="fl" vals="<?php echo $model->url;?>">这里放上传名称 <i></i></li>
                               <?php }?>

                            </ul>
                        </div>
                        <input type="hidden" id="url" value="<?php echo $model->url;?>" name="<?php echo Html::getInputName($model, 'url') ?>"/>
                    </li>
                    <li>
                        <div class="formL">
                            <label>简介：</label>
                        </div>
                        <div class="formR">
                            <div style="width: 562px;">
                                <textarea name="<?php echo Html::getInputName($model, 'brief') ?>"
                                          id="<?php echo Html::getInputId($model, 'brief') ?>"><?php echo strip_tags($model->brief);?></textarea>

                            </div>
                            <?php echo frontend\components\CHtmlExt::validationEngineError($model, 'brief') ?>
                        </div>
                    </li>
                </ul>
                <p class="conserve">
                    <button type="submit" class="bg_red_d B_btn110" onclick="return  getsubmit();">提&nbsp;&nbsp;交</button>
                </p>
            <?php \yii\widgets\ActiveForm::end() ?>
        </div>
    </div>

<!--主体内容结束-->

