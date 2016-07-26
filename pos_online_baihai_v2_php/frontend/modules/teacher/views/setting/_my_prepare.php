<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/11
 * Time: 17:54
 */
use frontend\components\helper\ImagePathHelper;

?>
<ul>
    <li>
        <div class="myFile">
            <h4>我的文件</h4>
        </div>
        <?php  foreach($fileResult as $file){?>
            <dl class="clearfix">
                <dt class="fl"><img width="57px" src="<?php echo ImagePathHelper::getFilePic($file->url);?>" alt=""></dt>
                <dd class="">
                    <h4><a target="_blank" href="<?= url('teacher/prepare/view-doc', ['id' => $file->id]); ?>" title="<?=$file->name;?>"><?php echo cut_str($file->name, 13); ?></a></h4>
                </dd>
                <dd style="margin-top: -12px;"> <span class="lesson_plan btn"><?php echo $this->render('_type_view', ['item' => $file, 'type' => '']); ?></span> <span class="time gray_d"><?= $file->createTime?></span></dd>
            </dl>
        <?php }?>
        <!--点击阅读图标跳页面后，返回此页面数量加一-->

    </li>
    <li>
        <div class="myFile">
            <h4>我的收藏</h4>
        </div>
        <?php foreach($favoritesResult as $favorites){?>
            <dl class="clearfix">
                <dt class="fl"><img width="57px" src="<?php echo ImagePathHelper::getFilePic($favorites->url);?>" alt=""></dt>
                <dd class="">
                    <h4><a target="_blank" href="<?= url('teacher/prepare/view-doc', ['id' => $favorites->favoriteId]); ?>"><?=$favorites->headLine?></a></h4>
                </dd>
                <dd style="margin-top: -12px;"> <span class="lesson_plan btn"><?php echo $this->render('_type_view', ['item' => $favorites, 'type' => '1']); ?></span>
                    <span class="time gray_d"><?= $favorites->createTime;?></span>
                </dd>
            </dl>
        <?php }?>
    </li>
</ul>