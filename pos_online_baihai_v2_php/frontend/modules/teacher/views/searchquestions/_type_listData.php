<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-14
 * Time: 下午6:29
 */
use frontend\models\dicmodels\DegreeModel;
use yii\helpers\Url;

?>
<div class="row ">
    <div class="formL">
        <label>题型：</label>
    </div>
    <div class="formR">
        <ul class="resultList testClsList ">
            <li class="<?php echo app()->request->getParam('type', '') == null ? 'ac' : ''; ?>">
                <a href="<?= Url::to(array_merge([''], $seachArr, ['type' => null])); ?>">全部题型</a>
            </li>
            <?php foreach ($result as $key => $item) { ?>
                <li class="<?php echo app()->request->getParam('type', '') == $item->typeId ? 'ac' : ''; ?>">
                    <a href="<?= Url::to(array_merge([''], $seachArr, ['type' => $item->typeId])); ?>"><?php echo $item->typeName; ?></a>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>
<div class="row">
    <div class="formL">
        <label>难度：</label>
    </div>
    <div class="formR">
        <ul class="resultList testClsList">
            <li class="<?php echo app()->request->getParam('complexity', '') == null ? 'ac' : ''; ?>">
                <a href="<?= Url::to(array_merge([''], $seachArr, ['complexity' => null])); ?>">全部难度</a>
            </li>
            <?php foreach (DegreeModel::model()->getList() as $v) { ?>
                <li class="<?php echo app()->request->getParam('complexity', '') == $v->secondCode ? 'ac' : ''; ?>">

                    <a href="<?= Url::to(array_merge([''], $seachArr, ['complexity' => $v->secondCode])); ?>"><?php echo $v->secondCodeValue; ?></a>

                </li>
            <?php } ?>
        </ul>
    </div>
</div>