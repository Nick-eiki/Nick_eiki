<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/11
 * Time: 10:49
 */
use frontend\components\WebDataCache;

/** @var common\models\search\Es_testQuestion $item */
/* @var $this \yii\web\View */


?>

<div class="quest" data-content-id="<?= $item->id ?>">
    <div class="sUI_pannel quest_title <?php if($item->isNewQuestion()){echo 'news';} ?>">
        <div class="pannel_l">
                <span class="Q_t_info"><em>试题编号：<?php echo $item->id ?></em><em><?= WebDataCache::getDictionaryName($item->tqtid) ?></em>
                    <?php if ($item->year != null) { ?>
                        <em><?php echo $item->year ?>年</em>
                    <?php } ?>
                    <em class="Q_difficulty">难度：<i
                            class="<?= \frontend\models\dicmodels\DegreeModel::model()->getIcon($item->complexity) ?>"></i></em></span>
        </div>
        <div class="pannel_r"><span><a class="correction" href="javascript:;">纠错</a></span><span><a class="fav"
                                                                                                    href="javascript:;"><i></i>
                    收藏</a></span><span><a href="javascript:;" class="join_basket_btn"></a></span></div>
    </div>

  <?php echo  $this->render('_itemPreviewDetail',['item'=>$item]) ?>

</div>

