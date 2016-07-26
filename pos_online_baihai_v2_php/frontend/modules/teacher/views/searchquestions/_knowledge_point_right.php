<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/4/14
 * Time: 16:47
 */
use common\models\sanhai\ShTestquestion;
use frontend\components\WebDataCache;
use yii\helpers\Url;

?>
<script type="text/javascript">
    $(function(){
        //删除
        $('.page_del').click(function(){
           var  _this = $(this);
            popBox.confirmBox('确定要删除吗？', function () {
                var qid = _this.attr('data-id');
                var url = '<?=url('/teacher/searchquestions/del-question'); ?>';
                $.post(url, {qid: qid}, function (data) {
                    if (data.success) {
                        popBox.successBox(data.message);
                        location.href = '<?php echo  Url::to('/teacher/searchquestions/knowledge-point-questions'); ?>';
                    } else {
                        popBox.errorBox(data.message);
                    }
                });
            })
        });

        $('.openAnswerBtn').toggle(function(){
            $(this).parents('.paper').find('.answerArea').show();
        },function(){
            $(this).parents('.paper').find('.answerArea').hide();
        })
    })

</script>
<div class="tabCont problem_tab_r">
	<div class="tabItem">
		<div class="schResult">
			<?php if(app()->request->getParam('n') == 1){?>
			<div class="test_paper_sort clearfix">
				<ul class="resultList type_list" >
					<li>
						<a class="<?php echo $isPic==null ?"ac":""?>" onclick="return getSearchList(this,'isPic')" data-value="">全部</a>
					</li>
					<li>
						<a class="<?php echo $isPic==='0' ?"ac":""?>" onclick="return getSearchList(this,'isPic')" data-value="0">录入的题目</a>
					</li>
					<li>
						<a class="<?php echo $isPic==1 ?"ac":""?>" onclick="return getSearchList(this,'isPic')" data-value="1">上传的题目</a>
					</li>
				</ul>
			</div>
			<?php }?>
			<div class="testPaperView pr">
				<div class="paperArea">
					<?php
                      /*  @var ShTestquestion[]  $questionList    */
                    foreach($questionList as $key=>$item) : ?>
                        <?php if(WebDataCache::getShowTypeID($item->tqtid) == 8){
                            $url = url('/teacher/testpaper/modify-camera-upload-new-topic',array('id'=>$item->id));
                        }else{
                            $url = url('/teacher/testpaper/modify-topic',array('id'=>$item->id));
                        }?>
						<div class="paper">

                            <div class="paper_r">
                                <?php if(app()->request->getParam('n') == 1){
                                        if($item->status == 0){
                                    ?>
                                    <a href="<?= $url?>" class="page_modify"><i></i>修改</a>
                                    <a href="javascript:;" class="page_del" data-id="<?=$item->id ?>"><i></i>删除</a>
                                <?php }}?>
                                <?php if(app()->request->getParam('n') == 0){?>
                                    <?php
                                    if(!$item->isCollected){?>
                                        <a href="javascript:;" onclick="popBox.errorCorrect_topic(<?= $item->id ?>)" class="error_correction">我要纠错</a>
                                        <a href="javascript:;" class="page_fav"  data-id="<?=$item->id ?>"><i></i>收藏</a>
                                    <?php }elseif($item->isCollected){?>
                                        <a href="javascript:;" onclick="popBox.errorCorrect_topic(<?= $item->id ?>)" class="error_correction">我要纠错</a>
                                        <a href="javascript:;" class="page_cancel_fav" data-id="<?=$item->id ?>"><i></i>取消收藏</a>
                                    <?php }?>
                                <?php }?>
                                <?php if(app()->request->getParam('n') == 2){?>
                                    <a href="javascript:;" onclick="popBox.errorCorrect_topic(<?= $item->id ?>)" class="error_correction">我要纠错</a>
                                    <a href="javascript:;" class="page_cancel_fav" data-id="<?=$item->id ?>"><i></i>取消收藏</a>
                                <?php }?>
                            </div>

							<?php echo $this->render('//publicView/elasticSearch/_itemProblemType', array('item' => $item)); ?>
							<div class="btnArea clearfix"><span class="openAnswerBtn fl">查看答案与解析<i
										class="open"></i></span> <span
									class="r_btnArea fr">难度:<em><?php echo WebDataCache::getDictionaryName($item->complexity) ?></em>&nbsp;&nbsp;&nbsp;</span>
							</div>

							<div class="answerArea hide">
								<p><em>答案:</em>
									<span><?php echo $this->render('//publicView/elasticSearch/_itemProblemAnswer', array('item' => $item)); ?></span>
								</p>
								<?php if(WebDataCache::getShowTypeID($item->tqtid)!=8){?>
								<p><em>解析:</em>
									<?php echo $item->analytical; ?>
								</p>
								<?php } ?>
							</div>
						</div>
						<hr>
					<?php endforeach; ?>
					<?php if(empty($questionList)): ?>
<!--					<div class="paper">-->
						<div class="no_search <?php echo !empty($topic_list) ? 'hide' :'';?>">
                            <p class="font14">很抱歉，当前条件下没有您需要的试题。</p>
                            <p class="font14">您可以尝试换一种搜索方式。</p>
                            <p class="font14">我们正在加速更新，敬请期待！</p>
							<p class="font14">同时期待您</p>
							<a href="<?php echo url('teacher/testpaper/add-topic')?>" class="a_button w140 bg_blue">贡献新的题目</a>
						</div>
<!--					</div>-->

					<?php endif; ?>
				</div>
			</div>

				<?php
				 echo \frontend\components\CLinkPagerNormalExt::widget( array(
						'firstPageLabel'=>false,
						'lastPageLabel'=>false,
						'pagination' => $pages,
						'updateId' => '#update',
						'maxButtonCount' => 8
					)
				);
				?>

		</div>

	</div>
	<div class="tabItem hide">

	</div>
	<div class="tabItem hide">
		<div class="paper">
			<!--没有数据！-->
		</div>
	</div>

</div>