<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-10-31
 * Time: 上午11:59
 */
?>
<div class="impBox">
    <ul class="form_list">

        <li>
            <div class="formL">
                <label for="name"><i></i>考试名称：</label>
            </div>
            <div class="formR">
                <span><?php echo $data->examName ?></span>
            </div>
        </li>
        <li>
            <div class="formL">
                <label for="name"><i></i>考试科目：</label>
            </div>
            <div class="formR">
                <span><?php echo $data->subjectName ?></span>
            </div>
        </li>
        <li>
            <div class="formL">
                <label for="name"><i></i>考试试卷：</label>
            </div>
            <div class="formR">

                <div class=" clearfix" style="width:400px;">
                    <p class="addPic">
                        <a href="javascript:" class="a_button id_btn">上传答案</a>
                        <?php
                        $t2 = new frontend\widgets\xupload\models\XUploadForm;
                        echo  \frontend\widgets\xupload\XUploadSimple::widget( array(
                            'url' => Yii::$app->urlManager->createUrl("upload/header"),
                            'model' => $t2,
                            'attribute' => 'file',
                            'autoUpload' => true,
                            'multiple' => true,
                            'options' => array(
								'acceptFileTypes' => new \yii\web\JsExpression('/(\.|\/)(jpg|png|jpeg)$/i'),
                                "done" => new \yii\web\JsExpression('done'),
                                "processfail" => new \yii\web\JsExpression('function (e, data) {var index = data.index,file = data.files[index]; if (file.error) {alert(file.error);}}')

                            ),
                            'htmlOptions' => array(
                                'id' => 't2',
                            )
                        ));
                        ?>

                    </p>
                    <em class="em_text">文件格式限定为jpg,png</em>

                </div>
                <div class="c_text"><em>试卷名称.jpg</em> <i>(25.7KB)</i><span class="Progress"><i
                            class="Progress_i"></i></span></div>
                <ul id="addImage" class="addPicUl">
                    <?php  if (!empty($data->imageUrls)) {
                        $imageArray = explode(",", $data->imageUrls);

                        foreach ($imageArray as $v) {
                            ?>

                            <li><img src="<?php echo $v ?>" height="48"
                                     width="50" alt=""><i></i>
                                <input type="hidden" class="url" value="<?php echo $v ?>">
                            </li>
                        <?php }
                    } ?>
                </ul>


            </div>
        </li>


    </ul>
</div>
<script>
    $('.addPicUl li').live('mouseover', function () {
        $(this).children('i').show();
    });
    $('.addPicUl li i').live('click', function () {
        $(this).parent('li').remove();

    });
    done = function (e, data) {

        $.each(data.result, function (index, file) {
            if (!file.error) {
                $('#addImage').append('<li><img   src="' + file.url + '" alt="" height="48" width="50"><i></i><input class="url" type="hidden" value="' + file.url + '" /></li>  ');
            }
            else {
                alert(file.error);
            }

        });

    }

</script>