<?php
/**
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2016/1/20
 * Time: 14:48
 */
use frontend\models\dicmodels\DegreeModel;
use yii\helpers\Url;

?>
<div class="form_l tl"><a data-sel-item onclick="return getContent(this)"  class="<?php echo app()->request->getParam('difficulty', '') == null ? 'sel_ac' : ''; ?>" href="<?= Url::to(array_merge([''], $searchArr, ['difficulty' => null])); ?>">全部难度</a></div>
<div class="form_r">
    <ul>
        <?php  foreach(DegreeModel::model()->getListData() as $k=>$v){

            ?>
            <li><a  onclick="return getContent(this)" class="<?php echo app()->request->getParam('difficulty', '') == $k ? 'sel_ac' : ''; ?>" data-sel-item href="<?= Url::to(array_merge([''], $searchArr, ['difficulty' => $k])); ?>"><?php echo $v; ?><i class="dif_state <?=DegreeModel::model()->getIcon($k)?>"></i></a></li>
        <?php }
        ?>
    </ul>
</div>