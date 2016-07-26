<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 14-11-4
 * Time: 下午5:12
 */


/* @var MakepaperController $this */
/* @var  Pagination $pages */
?>
<div class="schResult">
    <div class="testPaperView pr">
        <div class="paperArea">

            <?php foreach ($list as $key => $item) {
                echo $this->render('_itemProblem', array('item' => $item));
            } ?>
        </div>
    </div>
</div>
<?php
     echo \frontend\components\CLinkPagerExt::widget( array(
           'pagination'=>$pages,
            'updateId' => '#uploadId',
            'maxButtonCount' => 10
        )
    );
    ?>
<script type="text/javascript">
    $('#searchcount').html('共有题目<?php echo  $pages->getItemCount() ?>道题如下:');
    searchkey = '<?php echo isset($pages->  params['key'])?$pages->  params['key']:''?>';
    if (searchkey.length > 0) {
        $('#showSearchKey').show();
        $('#searchKey').html(searchkey);
    } else {
        $('#showSearchKey').hide();
    }

</script>

