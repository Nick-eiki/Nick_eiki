<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-19
 * Time: 上午10:18
 */
?>
<div class="impBox">
    <ul class="form_list">
        <!--弹窗的名称是对应页面里面的名称的-->
        <li>
            <div class="formL">
                <label><i></i>考试名称：</label>
            </div>
            <div class="formR">
                <span><?php echo $result->homeworkName ?></span>
            </div>
        </li>
        <li>
            <div class="formL">
                <label><i></i>考试试卷：</label>
            </div>
            <div class="formR">

                <div class=" clearfix" style="width:400px;">
                    <p class="addPic">
                        <a href="javascript:" class="a_button bg_green btn20 id_btn">上传答案</a>
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
                <ul id="addImage" class="up_img up_img_js clearfix">
                    <?php if($answerList->isUploadAnswer==1){ foreach ($answerList->homeworkAnswerImages as $k => $v) { ?>
                        <li><img width="50" height="48" alt="" src="<?php echo $v->imageUrl ?>">
                            <i></i>
                            <input class="url" type="hidden" value="<?php echo $v->imageUrl ?>">
                        </li>
                    <?php }} ?>
                </ul>
                <div class="c_text"><em>试卷名称.jpg</em> <i>(25.7KB)</i><span class="Progress"><i
                            class="Progress_i"></i></span></div>


            </div>
        </li>


    </ul>
</div>
<script>
    $(function () {
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

    })
</script>