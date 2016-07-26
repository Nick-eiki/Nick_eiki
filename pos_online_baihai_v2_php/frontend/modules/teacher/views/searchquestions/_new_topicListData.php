<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-13
 * Time: 下午4:34
 */
use common\models\search\Es_testQuestion;

?>
<script>
    //下拉解析
    $(function(){
        $('.openAnswerBtn').toggle(function(){
            $(this).parents('.btnArea').siblings('.answerArea').show();
        },function(){
            $(this).parents('.btnArea').siblings('.answerArea').hide();
        });
    });
    //收藏
    $('.page_fav').live("click",function(){
        var self = $(this);
        var qid = $(this).attr('data-id');
        var url = '<?=url('/teacher/searchquestions/collection-question'); ?>';
        $.post(url ,{qid:qid},function(data){
            if(data.success){
                self.html('<i></i>取消收藏');
                self.removeClass('page_fav');
                self.addClass('page_cancel_fav');
                self.unbind();
            }else{
                popBox.errorBox(data.message);
            }
        });
    });

    //取消收藏
    $('.page_cancel_fav').live("click",function(){
        var self = $(this);
        var qid = $(this).attr('data-id');
        var url = '<?=url('/teacher/searchquestions/cancel-collection-question'); ?>';
        $.post(url ,{qid:qid},function(data){
            if(data.success){
                self.html('<i></i>收藏');
                self.removeClass('page_cancel_fav');
                self.addClass('page_fav');
                //self.unbind();
            }else{
                popBox.errorBox(data.message);
            }
        });
    });
</script>

<div class="schResult">
    <?php
    /* @var $topic_list  Es_testQuestion */
 if(!empty($topic_list)){
     foreach($topic_list as $key=>$item){
         echo $this->render('//publicView/elasticSearch/_showItemProblem',['item'=>$item]);
     }
 }else{ ?>
     <div class="no_search <?php echo !empty($topic_list) ? 'hide' :'';?>">
         <p class="font14">很抱歉，当前条件下没有您需要的试题。</p>
         <p class="font14">您可以尝试换一种搜索方式。</p>
         <p class="font14">我们正在加速更新，敬请期待！</p>
         <p class="font14">同时期待您</p>
         <a href="<?php echo url('teacher/testpaper/add-topic')?>" class="a_button w140 bg_blue">贡献新的题目</a>
     </div>
<?php  }
    ?>


</div>

<?php
 echo \frontend\components\CLinkPagerNormalExt::widget( array(
        'firstPageLabel'=>false,
        'lastPageLabel'=>false,
        'pagination' => $page,
        'updateId' => '#update',
        'maxButtonCount' => 8
    )
);
?>
