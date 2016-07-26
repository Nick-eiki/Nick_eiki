<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/2
 * Time: 18:57
 */
use common\helper\DateTimeHelper;
use common\models\pos\SeFavoriteMaterial;
use common\models\pos\SeFavoriteMaterialGroup;
use common\models\sanhai\SrMaterial;
use frontend\components\helper\ImagePathHelper;
use frontend\components\helper\ViewHelper;
use frontend\components\WebDataCache;
use yii\helpers\Html;
use yii\helpers\Url;

$groupInfo = SeFavoriteMaterialGroup::getGroupInfo($groupId, user()->id);
$groupMaterialCount = SeFavoriteMaterial::getGroupMaterialCount($groupId, user()->id);
?>


<div class="tch_question container col910 omega no_bg">
    <div class="container" style="margin-top:0">
        <div class="sUI_pannel collections">
            <div class="pannel_l"><a href="javascript:;" data-sel-item><?php
                    if (!empty($groupId) && $groupInfo) {
                        echo $groupInfo->groupName;
                    } else {
                        echo '我的收藏';
                    }?></a><span><?php echo empty($groupMaterialCount) ? '0份' : $groupMaterialCount . '份' ?></span>
            </div>
            <?php if (!empty($groupId) && $groupInfo->groupType != 0) { ?>
                <div class="pannel_r"><a href="javascript:;" class="modify" data-sel-item>修改组名</a><span>|</span><a
                        href="javascript:;" class="delGroup" data-sel-item>删除该组</a></div>
            <?php } ?>
        </div>
        <?php echo $this->render('_index_type_select', ['department' => $department,
            'subjectId' => $subjectId,
            'groupType' => $groupType, 'matType' => $matType, 'groupId' => $groupId]); ?>
    </div>

    <div class="container manipulate" style="margin-bottom:-18px">
        <div class="gray_bg">
            <input class="allCheck" type="checkbox"/>
            <span>已选中<em id="questionNum"></em>个课件</span>

            <div class="btn_box">
                <a href="javascript:;" class="move"><i></i>移动到<b></b></a>

                <div class="move_to" style="display:none">
                    <div></div>
                    <ul>
                        <?php if(isset($defaultGroupId) && !empty($defaultGroupId) && ($groupId!=$defaultGroupId)){?>
                        <li><a class="ellipsis" href="javascript:;" groupId="<?= $defaultGroupId ?>">我的收藏</a>
                        <?php }?>
                        </li>

                        <?php foreach ($collectGroupList as $value) {
                            if ($value->groupId != $groupId) {
                                ?>

                                <li title="<?= Html::encode($value->groupName) ?>"><a class="ellipsis"
                                                                                      href="javascript:;"
                                                                                      groupId="<?= $value->groupId ?>"><?= Html::encode($value->groupName) ?></a>
                                </li>
                            <?php
                            }
                        } ?>
                    </ul>
                </div>
                <a href="javascript:;" class="remove"><i></i>删除</a>
            </div>
        </div>
    </div>

    <div class="container classify no_bg">
        <div class="no_bg">
            <ul class="sUI_dialog_list cls_rList clearfix">
                <?php if (!empty($favoriteMaterialList)) {
                    $materialIdArr = [];
                    $collectIdArr = [];
                    /** @var $favoriteMaterial common\models\pos\SeFavoriteMaterial; */
                    foreach ($favoriteMaterialList as $favoriteMaterial) {
                        $materialIdArr[] = $favoriteMaterial->favoriteId;
                        $collectIdArr[$favoriteMaterial->favoriteId] = $favoriteMaterial->collectId;
                    }
                    $materialList = SrMaterial::getMaterialListInfo($materialIdArr, $collectIdArr);

                    /** @var $material common\models\sanhai\SrMaterial; */
                    foreach ($materialList as $collectId => $material) {
                        ?>
                        <li collectId="<?php if (isset($collectId)) {
                            echo $collectId;
                        } ?>">
                            <div class="cls_lf_list">
                                <input class="oneCheck" type="checkbox"/>
                                <span
                                    class="file_cls <?php echo ImagePathHelper::getNewFilePic($material->url); ?>"></span>

                                <div class="sUI_pannel sUI_pannel_min">

                                    <h5><a target="_blank" title="<?php echo Html::encode($material->name); ?>"
                                           href="<?= Url::to(['/ajax/file-details', 'id' => $material->id, 'url' => $material->url]) ?>"><?php echo Html::encode($material->name); ?></a>
                                    </h5>
                                </div>
                                <div class="sUI_pannel in_troduces">
                                <span><?php if (!empty($material->creator)) {
                                        echo WebDataCache::getTrueName($material->creator);
                                        echo "&nbsp;";
                                    } else {
                                        echo '系统';
                                        echo "&nbsp;&nbsp;";
                                    } ?><?php if (!empty($material->createTime)) {
                                        echo date('Y-m-d H:i', DateTimeHelper::timestampDiv1000($material->createTime));
                                        echo "&nbsp;&nbsp;";
                                    } ?><?php echo $groupType == 0 ? '共享' : '上传' ?></span>
                                </div>
                            </div>
                            <div class="cls_rg_list">
                                <a class="read addReadNum" target="_blank"
                                   href="<?= Url::to(['/ajax/file-details', 'id' => $material->id, 'url' => $material->url]) ?>">
                                    阅读(<span class="readNum"><?= $material->readNum ?></span>)</a>

                                <a class="fav" href="javascript:;"
                                   data-id="<?= $material->id ?>" data-type="<?= $material->matType ?>"
                                   data-department="<?= $department ?>" data-subjectId="<?= $subjectId ?>"
                                   data-url="<?php echo Url::to('/ajax/collect'); ?>" data-groupType="<?= $groupType ?>"
                                   data-groupId="<?= $groupId ?>" data-default-groupId="<?php echo !empty($defaultGroupId)?$defaultGroupId:null?>"
                                   data-url-cancel="<?php echo Url::to('/ajax/cancel-collect') ?>">
                                    <span class="collection">收藏</span>
                                    (<span class="collectionNum"><?= $material->favoriteNum; ?></span>)</a>

                                <a class="download" target="_blank"
                                   href="<?php echo Url::to(['/ajax/new-download-file', 'id' => $material->id]); ?>">下载(<?= $material->downNum ?>
                                    )</a>
                                <?php if ($material->access == 1) { ?>
                                    <a class="share" href="javascript:;" data_id="<?php echo $material->id ?>">共享</a>
                                <?php } ?>
                            </div>
                        </li>
                    <?php
                    }
                } else {
                    ViewHelper::emptyViewByPage($pages, $message = '此处暂无内容');
                } ?>
            </ul>
        </div>
    </div>

</div>


<?php
echo \frontend\components\CLinkPagerExt::widget(array(
        'pagination' => $pages,
        'updateId' => '#material_list',
        'maxButtonCount' => 5,
        'showjump' => true
    )
);
?>

<script type="text/javascript">
    $(function () {

//页面加载判断课件是否已收藏
        var materialIdArray = [];
        $('.sUI_dialog_list .fav').each(function (index, el) {
            var materialId = $(el).attr('data-id');
            materialIdArray.push(materialId);
        });
        $.post("/ajax/file-is-collected", {materialIdArray: materialIdArray}, function (result) {
            var materialIdArray = result.data;

            $('.sUI_dialog_list .fav').each(function (index, el) {
                if ($.inArray($(el).attr('data-id'), materialIdArray) > -1) {
                    $(el).addClass('cur');
                    $(el).find('.collection').html('取消收藏');
                }
            });
        });
    })
    $.fn.extend({
        checkAll: function (arr) {
            return this.each(function () {
                var _this = $(this);
                var num = 0;
                _this.click(function () {
                    if (_this.is(":checked")) {
                        arr.prop("checked", true);
                        num = arr.length;
                    } else {
                        arr.prop("checked", false);
                        num = 0;
                    }
                });
                arr.each(function () {
                    $(this).click(function () {
                        if ($(this).is(":checked")) {
                            num++;
                        } else {
                            num--;
                        }
                        if (num == arr.length) {
                            _this.prop("checked", true);
                        } else {
                            _this.prop("checked", false);
                        }
                    })
                })
            })
        }
    });
    $(".allCheck").checkAll($(".oneCheck"));


    //统计选中多少道
    $('#questionNum').html(0);
    $('.allCheck').click(function () {
        var questionNum = $('.oneCheck:checked').length;
        $('#questionNum').html(questionNum);
    });
    $('.oneCheck').click(function () {
        var questionNum = $('.oneCheck:checked').length;
        $('#questionNum').html(questionNum);
    });

</script>


