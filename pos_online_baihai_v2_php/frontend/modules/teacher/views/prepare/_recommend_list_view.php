<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/6/2
 * Time: 11:42
 */
?>

<?php if (!empty($result)) { ?>
    <div class="">
        <h4>相关资源推荐</h4>
        <hr style="margin-top:10px">
        <ul class="teac_test_paper_list teacprepare_list teacprepare_listMy teacprepareJs clearfix interrelated">
            <!--    <ul class="teac_test_paper_list teacprepare_listcon teacprepare_list teacprepare_listMy teacprepareJs clearfix">-->
            <?php echo $this->render('_platform_list_view', ['result' => $result]); ?>

        </ul>
    </div>
<?php } ?>