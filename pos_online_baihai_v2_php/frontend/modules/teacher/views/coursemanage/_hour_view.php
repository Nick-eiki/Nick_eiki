<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-11-5
 * Time: 下午5:19
 */
use yii\helpers\Html;

?>
<tr>
    <td class="Valign"><span class="chaptTitle">第<?php echo $key;?>节</span> <?php echo  Html::activeHiddenInput($hourItem, "[$key]cNum",array('value'=>$key)) ?></td>
    <td><input type="text" class="text" name="<?php echo Html::getInputName($hourItem,"[$key]cName")?>">

        <div class="treeParent">

            <input type="radio" class="radio pointRadio"  value="0" name="<?php echo Html::getInputName($hourItem,"[$key]type")?>">
            <label>知识点</label>
            &nbsp;&nbsp;&nbsp;
            <input type="radio" class="radio chaptRadio" value="1" name="<?php echo Html::getInputName($hourItem,"[$key]type")?>">
            <label>章节</label>
            <br>
            <button type="button" class="bg_green_l addPointBtn hide"></button>
            <div class="pointArea hide">
                <h6>已选中知识点:</h6>
                <ul class="labelList">
                </ul>
                <input id="val" class="hidVal" type="hidden" name="<?php echo Html::getInputName($hourItem, "[$key]kcid")?>"  value="" />
            </div>
        </div>
    </td>
    <td>
        <button type="button" class="addDocBtn">使用讲义</button>
        <ul class="DocList">
        </ul>
        <?php echo  Html::activeHiddenInput($hourItem, "[$key]teachMaterialID",['class'=>'addHour']); ?>
    </td>
    <td>
        <p class="addPic">
            <a href="javascript:" class="a_button bg_green addVideoBtn">添加视频</a>
            <?php
            $t1 = new frontend\widgets\xupload\models\XUploadForm;

            echo  \frontend\widgets\xupload\XUploadSimple::widget( array(
                'url' => Yii::$app->urlManager->createUrl("upload/video"),
                'model' => $t1,
                'attribute' => 'file',
                'autoUpload' => true,
                'multiple' => false,
                'options' => array(
                    "done" => new \yii\web\JsExpression('done')
                ,
                ),
                'htmlOptions' => array(
                    'id' => 'fileupload',
                )
            ));
            ?>
            <input class="video_url numberVideo" number="<?php echo $key; ?>"  id="ClassHourForm_<?php echo $key; ?>_videoUrl" name="<?php echo Html::getInputName($hourItem, "[$key]videoUrl")?>" type="hidden"/>
        <ul class="videoList<?php echo $key?>">
        </ul>
        </p>
    </td>
    <td class="Valign"><span class="delBtn">删除</span></td>
</tr>
