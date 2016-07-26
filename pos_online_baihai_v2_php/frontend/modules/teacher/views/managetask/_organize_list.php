<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-16
 * Time: 下午4:41
 */
?>
<div class="schResult">
    <?php
    if(!empty($topic_list)){
        foreach($topic_list as $key=>$item){
            echo $this->render('//publicView/paper/_zy_ItemProblem',['item'=>$item]);
        }
    }else{ ?>
        <div class="no_search <?php echo !empty($topic_list) ? 'hide' :'';?>">
            <p class="font14">很抱歉，当前条件下没有您需要的试题。</p>
            <p class="font14">您可以尝试换一种搜索方式。</p>
            <p class="font14">我们正在加速更新，敬请期待！</p>
            <p class="font14">同时期待您</p>
            <span><a href="<?php echo url('teacher/testpaper/add-topic')?>" class="a_button w100 bg_blue" target="_blank">贡献新题</a></span>
        </div>
    <?php  }
    ?>
</div>
<?php
 echo \frontend\components\CLinkPagerNormalExt::widget( array(
        'firstPageLabel'=>false,
        'lastPageLabel'=>false,
       'pagination'=>$pages,
        'updateId' => '#update',
        'maxButtonCount' => 8
    )
);
?>


<script>
	$('.paperStructure_list li[alert]').each(function () {
		$id = $(this).attr('alert');
		$('.paper button[id=' + $id + ']').each(function () {
			$(this).removeClass('addBtn').addClass('del_btn').text('删除');
		});
	});
	$('.openAnswerBtn').toggle(function(){
		$(this).parents('.paper').find('.answerArea').show();
	},function(){
		$(this).parents('.paper').find('.answerArea').hide();
	})

</script>

