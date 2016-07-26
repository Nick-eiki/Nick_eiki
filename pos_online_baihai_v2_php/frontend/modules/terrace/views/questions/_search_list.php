<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-11-25
 * Time: 上午11:12
 */
?>
<script>
    //下拉解析
    $(function(){
        $('.openAnswerBtn').toggle(function(){
            $(this).parents('.btnArea').siblings('.answerArea').show();
        },function(){
            $(this).parents('.btnArea').siblings('.answerArea').hide();
        });

        $('.searchcount').html('共有题目<?php echo  $pages->getItemCount() ?>道题如下:');
        searchkey = '<?php echo isset($pages->params['tags'])?$pages->params['tags']:''?>';
        if (searchkey.length > 0) {
            $('.showSearchKey').show();
            $('.searchKey').html(searchkey);
        } else {
            $('.showSearchKey').hide();
        }

    })

</script>

<div class="schResult">
    <?php foreach($list as $key=>$item){
   echo $this->render('//publicView/paper/_showItemProblem',['item'=>$item]);
    }?>


</div>
    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(
           'pagination'=>$pages,
            'updateId' => '#update',
            'maxButtonCount' => 9
        )
    );
    ?>