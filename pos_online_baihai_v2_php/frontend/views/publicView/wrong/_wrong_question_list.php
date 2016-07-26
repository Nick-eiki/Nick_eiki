<?php
/**
 * Created by PhpStorm.
 * User: aaa
 * Date: 2016/2/22
 * Time: 11:06
 */
use frontend\components\helper\ViewHelper;
use frontend\components\WebDataCache;

?>
    <script type="text/javascript">
        $(function(){
            //删除

            $('.openAnswerBtn').toggle(function(){
                $(this).parents('.paper').find('.answerArea').show();
            },function(){
                $(this).parents('.paper').find('.answerArea').hide();
            })
        })

    </script>
    <div class="testPaperView pr">
        <div class="paperArea">
            <?php
                if(empty($wrongQuestion)) {
                    ViewHelper::emptyView();
                }else{
                foreach($wrongQuestion as $key=>$item) :
                    ?>
                    <?php if(WebDataCache::getShowTypeID($item->question->tqtid) == 8){
                        $url = url('/teacher/testpaper/modify-camera-upload-new-topic',array('id'=>$item->question->id));
                    }else{
                        $url = url('/teacher/testpaper/modify-topic',array('id'=>$item->question->id));
                    }?>
                    <div class="paper">

                        <?php echo $this->render('//publicView/elasticSearch/_itemProblemType', array('item' => $item->question)); ?>
                        <div class="btnArea clearfix"><span class="openAnswerBtn fl">查看答案与解析<i
                                    class="open"></i></span>
                        </div>

                        <div class="answerArea hide">
                            <p><em>答案:</em>
                                <span><?php echo $this->render('//publicView/elasticSearch/_itemProblemAnswer', array('item' => $item->question)); ?></span>
                            </p>
                            <?php if(WebDataCache::getShowTypeID($item->question->tqtid)!=8){?>
                                <p><em>解析:</em>
                                    <?php echo $item->question->analytical; ?>
                                </p>
                            <?php } ?>
                        </div>
                    </div>
                    <hr>
                <?php endforeach; }?>
        </div>
    </div>

<?php
echo \frontend\components\CLinkPagerNormalExt::widget( array(
        'firstPageLabel'=>false,
        'lastPageLabel'=>false,
        'pagination' => $pages,
        'maxButtonCount' => 10
    )
);
?>