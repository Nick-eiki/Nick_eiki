<?php
/**
 * Created by unizk.
 * User: ysd
 * Date: 2015/5/1
 * Time: 10:20
 */
use common\helper\DateTimeHelper;
use common\models\pos\SeShareMaterial;
use frontend\components\helper\ImagePathHelper;
use frontend\components\helper\ViewHelper;
use frontend\components\WebDataCache;
use yii\helpers\Url;

if (empty($materialList)) {
    echo ViewHelper::emptyView("无数据！");
} else {
    ?>

    <ul class="sUI_dialog_list cls_rList clearfix">
        <?php foreach ($materialList as $material) { ?>
            <li>
                <div class="cls_lf_list">
                    <span class="file_cls <?php echo ImagePathHelper::getNewFilePic($material->url); ?>"></span>

                    <div class="sUI_pannel sUI_pannel_min">
                        <h5><a class="addReadNum" target="_blank"
                               href="<?= Url::to(['/ajax/file-details', 'id' => $material->id, 'url' => $material->url, 'classId' => $classId]) ?>">
                                <?= \yii\helpers\Html::encode($material->name) ?>
                            </a>
                        </h5>
                    </div>
                    <div class="sUI_pannel in_troduces">
                        <span><?php if (!empty($material->creator)) {
                                echo WebDataCache::getTrueName($material->creator);
                            } else {
                                echo '系统';
                            } ?> <?php
                            if (null !=($shareMaterail = SeShareMaterial::getShareMaterialInfo($material->id))) {
                                echo date('Y-m-d H:i', DateTimeHelper::timestampDiv1000($shareMaterail->createTime));
                            } ?>  共享</span>
                    </div>
                </div>
                <div class="cls_rg_list">
                    <a class="read addReadNum" target="_blank"
                       href="<?= Url::to(['/ajax/file-details', 'id' => $material->id, 'url' => $material->url, 'classId' => $classId]) ?>">
                        <i></i><br>阅读(<span class="readNum"><?= $material->readNum ?></span>)</a>

                    <a class="fav" href="javascript:;"
                       data-id="<?= $material->id ?>" data-type="<?= $material->matType ?>"
                       data-url="<?php echo Url::to('/ajax/collect'); ?>"
                       data-url-cancel="<?php echo Url::to('/ajax/cancel-collect') ?>"><i></i><br>
                        <span class="collection">收藏</span>
                        (<span class="collectionNum"><?= $material->favoriteNum; ?></span>)</a>

                    <a class="download" target="_blank"
                       href="<?php echo Url::to(['/ajax/new-download-file', 'id' => $material->id]); ?>"><i></i><br>下载(<?= $material->downNum ?>
                        )</a>
                </div>
            </li>
        <?php } ?>
    </ul>
<?php } ?>
<?php
echo \frontend\components\CLinkPagerExt::widget(array(
        'pagination' => $pages,
        'updateId' => '#classFile',
        'maxButtonCount' => 5,
        'showjump' => true
    )
);
?>
<script type="text/javascript">
    $(function(){

//页面加载判断课件是否已收藏
        var materialIdArray=[];
        $('.sUI_dialog_list .fav').each(function(index,el){
            var materialId=$(el).attr('data-id');
            materialIdArray.push(materialId);
        });
        $.post("/ajax/file-is-collected",{materialIdArray:materialIdArray},function(result){
            var materialIdArray=result.data;

            $('.sUI_dialog_list .fav').each(function(index,el){
                if($.inArray($(el).attr('data-id'),materialIdArray)>-1){
                    $(el).addClass('cur');
                    $(el).find('.collection').html('取消收藏');
                }
            });
        });
    })
</script>

