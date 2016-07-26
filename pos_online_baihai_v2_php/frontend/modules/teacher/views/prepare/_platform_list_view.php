<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/6/3
 * Time: 10:32
 */
use common\models\sanhai\SrMaterial;
use yii\helpers\Html;

?>

    <script>
        $(function () {
            $('.collect').unbind().bind('click', function () {

                var $_this = $(this).children('.file_collect');
                var id = $_this.attr('collectID');
                var type = $_this.attr('typeId');
                var action = $_this.attr('action');
                var pushOne = $_this.parents('li').find('.time').children('em');

                $.post("<?php echo url('teacher/prepare/add-material')?>", {
                    id: id,
                    type: type,
                    action: action
                }, function (data) {
                    if (data.success) {
                        if (action == 1) {
                            var increase = parseInt(pushOne.text()) + 1;
                            pushOne.html(increase);
                            $_this.prev('i').addClass('ac');
                            $_this.attr('action', 0).text('取消收藏');
                        }
                        else {
                            var reduction = parseInt(pushOne.text()) - 1;
                            pushOne.html(reduction);
                            $_this.prev('i').removeClass('ac');
                            $_this.attr('action', 1).text('收藏');

                        }

                    } else {
                        popBox.alertBox(data.message);

                    }
                });
            });
        })
    </script>
<?php
foreach ($result as $item) {
    ?>
    <li class="fl clearfix" data-value="<?php echo $item->id; ?>">
        <dl class="clearfix">
            <?php echo $this->render('_type_img_view', ['item' => $item]); ?>

            <dd class="fl">
                <h4 title="<?php echo $item->name; ?>"><a target="_blank"
                                                          href="<?= url('teacher/prepare/view-doc', ['id' => $item->id]); ?>"><?php echo Html::encode($item->name); ?></a>
                </h4>
            </dd>
            <dd class="fl">

                    <span class="lesson_plan">
                      <?php echo $this->render('_type_view', ['item' => $item, 'type' => '']); ?>

                    </span>
                <?php
                /* @var  $item SrMaterial */
                ?>
                <span class="time"
                      style="margin-right:12px;"><em><?php $collectNum = $item->getCollectNum()->where(['isDelete' => 0])->count();
                        echo $collectNum ?></em>人已收藏</span>
                <span class="number"><?php echo $item->readNum; ?>人已阅读</span>
            </dd>

        </dl>
        <!--点击阅读图标跳页面后，返回此页面数量加一-->
        <div class="teac_r fr">
            <?php $isCollected = $item->getCollectNum()->where(['creatorID' => user()->id, 'isDelete' => 0])->exists();
            if (!$isCollected) { ?>
                <span class="collect">
                    <i class="c"></i>
                    <em class="file_collect" action="1" collectID="<?php echo $item->id; ?>"
                        typeId="<?php echo $item->matType; ?>">收藏</em>
               </span>
            <?php } else { ?>
                <span class="collect">
                    <i class="c ac"></i><em class="file_collect" action="0" collectID="<?php echo $item->id; ?>"
                                            typeId="<?php echo $item->matType; ?>">取消收藏</em>
               </span>
            <?php } ?>

            <a target="_blank" href="<?= url('teacher/prepare/view-doc', ['id' => $item->id]); ?>"> <span class="read">
                        <i class="y"></i>
                        <em>阅读</em>
                </span></a>
        </div>

    </li>
<?php } ?>