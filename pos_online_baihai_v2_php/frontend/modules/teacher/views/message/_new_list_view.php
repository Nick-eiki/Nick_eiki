<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-23
 * Time: 下午2:52
 */
use frontend\components\helper\StringHelper;
use frontend\components\helper\ViewHelper;
use yii\helpers\Html;

?>
<ul class="notice_list" id="list">
    <?php if (empty($modelList)) {
        ViewHelper::emptyViewByPage($pages);
    } else {
        foreach ($modelList as $key => $item) {
            if ($item->isSend == 0) {
                ?>
                <li>
                    <h4 class="font16 notice_name"><?=StringHelper::cutStr(Html::encode($item->title),45)?><em class="gray_d">未发送</em></h4>
                    <em class="notice_time gray_d font12"><?php echo $item->creatTime; ?></em>

                    <p><em>接收人:</em>

                    <span title="<?php foreach ($item->receivers as $val) {
                        echo $val->receiverName . '&nbsp;';
                    } ?>">

                    <?php
                    $studentList = array_slice($item->receivers, 0, 11);
                    foreach ($studentList as $key => $v) {
                        ?>
                        <span id="<?php echo $v->receiverId; ?>"><?php echo $v->receiverName; ?></span>
                    <?php } ?>
                        </span>

                    </p>

                    <p><em>通知内容:</em><?php echo Html::encode($item->message); ?></p>
                    <?php if (!empty($item->urls)) { ?>
                        <div class="QA_cont_imgBox">
                            <?php $img = explode(',', $item->urls);
                            foreach ($img as $val) {
                                ?>
                                <span>
                                  <a class="fancybox" href="<?php echo publicResources() . $val; ?>"
                                     data-fancybox-group="gallery_<?= $item->id; ?>">
                                      <img src="<?php echo publicResources() . $val; ?>" width="160" height="120"
                                           alt=""/>
                                  </a>
                            </span>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <em class="crossDelBtn hide" delId='<?php echo $item->id; ?>'></em>
                    <button type="button" class="btn w120 bg_blue notice_send_btn" sendId="<?php echo $item->id; ?>"
                            id="send">发送通知
                    </button>
                </li>
            <?php } else { ?>
                <li>
                    <h4 class="font16 notice_name"><?php echo Html::encode($item->title); ?><em class="gray_d">已发送</em></h4>
                    <em class="notice_time gray_d font12"><?php echo $item->creatTime; ?></em>

                    <p><em>接收人:</em>
                      <span title="<?php foreach ($item->receivers as $val) {
                          echo $val->receiverName . '&nbsp;';
                      } ?>">
                    <?php
                    $studentNum = array_slice($item->receivers, 0, 11);
                    foreach ($studentNum as $key => $v) {
                        ?>
                        <span id="<?php echo $v->receiverId; ?>"><?php echo $v->receiverName; ?></span>
                    <?php } ?>
                          </span>
                        </p>
                    <p><em class="gray_d">已读：<?php echo $item->readedCnt; ?>人</em></p>

                    <p><em>通知内容:</em><?php echo Html::encode($item->message); ?></p>
                    <?php if (!empty($item->urls)) { ?>
                        <div class="QA_cont_imgBox">
                            <?php $img = explode(',', $item->urls);
                            foreach ($img as $val) {
                                ?>
                                <span>
                            <a class="fancybox" href="<?php echo publicResources() . $val; ?>"
                               data-fancybox-group="gallery_<?= $item->id; ?>">
                                <img src="<?php echo publicResources() . $val; ?>" width="160" height="120" alt=""/>
                            </a>
                                </span>
                            <?php } ?>
                        </div>
                    <?php } ?>

                    <em class="crossDelBtn hide" delId='<?php echo $item->id; ?>'></em>
                </li>
            <?php } ?>

        <?php }
    } ?>
</ul>
<div class="page ">
    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(
           'pagination'=>$pages,
            'updateId' => '#listDate',
            'maxButtonCount' => 5
        )
    );
    ?>
</div>