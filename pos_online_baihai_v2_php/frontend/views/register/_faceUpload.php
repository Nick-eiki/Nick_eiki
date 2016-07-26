<?php
$publicResources = Yii::app()->request->baseUrl;
$this->registerCssFile($publicResources . '/css/jquery.fileupload.css');
$this->registerCssFile($publicResources . '/css/popBox.css');

?>

<script>
    //上传完成后调用
    done = function (e, data) {
        $.each(data.result, function (index, file) {
            if(file.error){
                popBox.alertBox(file.error);
            return ;
            }
            //给隐藏表单赋值
            $('.faceIcon').val(file.url);
            img = file;
            $('.jcrop-holder').find('img').attr('src', file.url);
            $('#xuwanting').attr('src', file.url);
            $('#crop_preview230').attr('src', file.url);
            $('#crop_preview110').attr('src', file.url);
            $('#crop_preview70').attr('src', file.url);
            $('#crop_preview40').attr('src', file.url);
            $('.zxxWrap').show();
            popBox.upHeadPic(null, Jcrop);
        });
    };

    var Jcrop = function(){

    };

    $(function(){
        $('#saveface').live('click', function(){
            var url = '<?php echo url("ajax/image")?>';
            var x = $('#jcrop_x1').val();
            var y = $('#jcrop_y1').val();
            var width = $('#jcrop_w').val();
            var height = $('#jcrop_h').val();

            //没有裁剪
            if(x == 0) {
                x = 0;
            }
            if(y == 0) {
                y = 0;
            }
            if(width == 0) {
                width = 500;
            }
            if(height == 0) {
                height = 500;
            }
            $.post(url, {name:img.url, x: x, y: y, width: width, height: height}, function ($data) {
                $('#face-img').attr('src', $data);
            });
            $('.zxxWrap').hide();
        })
    })


</script>

<!--弹出窗口 上传头像------------------------------------------------------>
<div class="popBox upHeadPic hide">
    <span class="close">×</span>

    <h3><i class="icon"></i>上传头像</h3>

    <div class="popCont">
        <span class="fileinput-button uploading">
            <span class="id_btn Continue">选择文件</span>
            <?php
            $t1 = new frontend\widgets\xupload\models\XUploadForm;
            /** @var $this BaseController */
            echo  \frontend\widgets\xupload\XUploadSimple::widget( array(
                'url' => \Yii::$app->urlManager->createUrl("upload/header"),
                'model' => $t1,
                'attribute' => 'file',
                'autoUpload' => true,
                'multiple' => false,
                'options' => array(
                    "done" => new \yii\web\JsExpression('done')
                ,
                ),
                'htmlOptions' => array(
                    'id' => 't1',
                )
            ));
            ?>
        </span>

        <div class="zxxWrap hide">
            <hr>
            <h5>裁剪头像</h5>
            <h6>最终头像:</h6>

            <div class="">
                <div class="zxx_main_con">
                    <div class="zxx_test_list pr">
                        <img id="xuwanting" src="" width="500"/>
                    <span id="preview_box230" class="crop_preview230">
                	    <img id="crop_preview230" src=""/>
                    </span>
                    <span id="preview_box110" class="crop_preview110">
                        <img id="crop_preview110" src=""/>
                    </span>
                    <span id="preview_box70" class="crop_preview70">
                        <img id="crop_preview70" src=""/>
                    </span>
                    <span id="preview_box40" class="crop_preview40">
                        <img id="crop_preview40" src=""/>
                    </span>
                    </div>
                </div>
            </div>
            <input type="hidden" id="jcrop_x1">
            <input type="hidden" id="jcrop_y1">
            <input type="hidden" id="jcrop_x2">
            <input type="hidden" id="jcrop_y2">
            <input type="hidden" id="jcrop_w">
            <input type="hidden" id="jcrop_h">

            <div class="btnArea">
                <button class="ok" id="saveface">保存</button>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <button class="cancel">取消</button>
            </div>
        </div>
    </div>
</div>