<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/11/17
 * Time: 10:51
 */
?>

<div class="plan_l" id="srchResult">
    <?php  foreach ($data as $v) { ?>
    <div class="list oneNews">
        <h4><a href="<?php echo url("/ku/information/information-detail",array('informationID' => $v->informationID)) ?>">
                [
                <?php
                    echo $v->informationTypeName;
                ?>
                ]
                <?php echo $v->informationTitle; ?>
            </a></h4>

        <p>
            <span>摘要：</span><?php echo mb_substr(strip_tags($v->informationContent), 0, 70, 'UTF-8'); ?>
        </p>
    </div>
<?php } ?>
    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(
           'pagination'=>$pages,
            'updateId' => '#srchResult',
            'maxButtonCount' => 10
        )
    );
    ?>

</div>