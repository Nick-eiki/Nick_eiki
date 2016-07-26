<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-2-3
 * Time: 下午12:03
 */
 /* @var $this yii\web\View */  $this->title="答案预览";
?>
<div class="currentRight grid_16 push_2 test_correctPaper">
    <div class="noticeH clearfix">
        <h3 class="h3L">查看答案详情</h3>
    </div>
    <hr>
    <div class="correctPaper">
                <h5><?php echo $answerResult->studentName."的答案"?></h5>

        <div class="correctPaperSlide">
            <div class="testPaperWrap mc">
                <ul class="testPaperSlideList slid">
                    <?php   $answerArray=explode(",",$answerResult->ansImageUrl); foreach ($answerArray as $v) { ?>
                        <li><img src="<?php echo publicResources().$v ?>" width="830" height="508" alt=""/>

                        </li>
                    <?php } ?>
                </ul>
            </div>
            <a href="javascript:" id="prevBtn" class="correctPaperPrev">上一页</a> <a href="javascript:" id="nextBtn"
                                                                                    class="correctPaperNext">下一页</a>

            <div class="slideBtn"></div>

        </div>
    </div>
</div>
<script>
    $(function () {
        $('.slid').cycle({
            fx: "scrollLeft",
            pager: '.slideBtn',
            speed: 1000,
            timeout: 0,
            next: "#nextBtn",
            prev: "#prevBtn"
        });
    })
</script>