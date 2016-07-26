<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/11/17
 * Time: 10:51
 */
?>

<div class="plan_l" id="srchResult">
    <?php  foreach ($data as $v) : ?>
    <div class="list">
        <h4><a href="<?php echo url("/teacher/information/information-detail",array('informationID' => $v->informationID)) ?>" target="_blank">
                [
                <?php
                    echo $v->informationTypeName;
                ?>
                ]
                <?php echo $v->informationTitle; ?>
            </a>
            <a href="<?php echo url("/teacher/information/information-update",array('informationID' => $v->informationID)) ?>" style="color: #00b7ee"><i></i></a></h4>

        <p>
            <span>摘要：</span><?php echo mb_substr(strip_tags($v->informationContent), 0, 70, 'UTF-8'); ?>
        </p>
    </div>
<?php endforeach; ?>
	<?php if(empty($data)):  ?>
	<div class="list">
		没有数据！
		</div>
	<?php endif; ?>
    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(
           'pagination'=>$pages,
            'updateId' => '#srchResult',
            'maxButtonCount' => 5
        )
    );
    ?>

</div>
