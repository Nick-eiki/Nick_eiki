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
            <input class="pointRadio" type="radio" name="<?php echo Html::getInputName($hourItem,"[$key]type")?>" value="0">
            <label>知识点</label>
            &nbsp;&nbsp<input class="chaptRadio" type="radio" name="<?php echo Html::getInputName($hourItem,"[$key]type")?>" value="1">
            <label>章节</label><br>
            <button type="button" class="bg_green_l addPointBtn hide editBtn"></button>
            <div class="pointArea hide">
<!--                <input class="hidVal" type="hidden" value="">-->
                <?php echo  Html::activeHiddenInput($hourItem, "[$key]kcid") ?>
                <h6>已选中知识点:</h6>
                <ul class="labelList"></ul>
            </div>
        </div>
    </td>
    <td>
        <button type="button" class="bg_green addDocBtn" docID="<?php echo $id; ?>">使用讲义</button>
        <ul class="DocList">

            <?php echo  Html::activeHiddenInput($hourItem, "[$key]teachMaterialID") ?>
        </ul>
    </td>
    <td>
        <button type="button" class="addVideoBtn">上传视频</button>
        <ul class="videoList">
            <?php echo  Html::activeHiddenInput($hourItem, "[$key]videoUrl") ?>
        </ul>
    </td>
    <td class="Valign"><span class="delBtn">删除</span></td>
</tr>