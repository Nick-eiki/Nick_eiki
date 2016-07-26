<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-2
 * Time: 下午5:00
 */
use frontend\components\helper\ImagePathHelper;

/* @var $this yii\web\View */  $this->title="老师试卷内容";
?>
<div class="currentRight grid_16 push_2">
    <div class="noticeH clearfix noticeB uploadedPaper_title">
        <h3 class="h3L"><?php echo $result->subjectName ?>试卷详情</h3>
        <hr style="margin-top:10px;">
        </div>
        <div class="up_details">
            <h4><?php echo $result->examName ?></h4>

            <div class="subject">
                <span>科目：<?php echo $result->subjectName ?></span>
                <span>任课教师：唐僧</span>
            </div>
            <div class="up_details_t">
                <h6>试卷内容：</h6>
                <!--<a href="javascript:;" class="direction prev">上一个</a>
                <a href="javascript:;" class="direction after">下一个</a>-->
                <div class="ul_list_box">
                    <ul class="clearfix deta_list" id="deta_list">
                        <?php $imageArray= ImagePathHelper::getPicUrlArray($result->imageUrls);
                        foreach ($imageArray as $k => $v) {
                            if ($k == 0) {
                                ?>
                                <li class="active"><img src="<?php echo publicResources() . $v ?>"></li>
                            <?php } else { ?>
                                <li><img src="<?php echo publicResources() . $v ?>"></li>
                            <?php }
                        } ?>

                    </ul>


                </div>
                <a href="javascript:" class="direction pre" id="prevBtn"></a>
                <a href="javascript:;" class="direction next" id="nextBtn"></a>

                <div class="paper_pic_box">
                    <ol class="ol_list slide">
                        <?php foreach ($imageArray as $k => $v) {
                            if ($k == 0) { ?>
                                <li style="display: block"><img
                                        src="<?php echo publicResources() . $v ?>" alt=""></li>
                            <?php } else { ?>
                                <li><img
                                        src="<?php echo publicResources() . $v ?>" alt=""></li>
                            <?php }
                        } ?>
                    </ol>


                </div>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        var aLi = $('#deta_list li');
        var now = 0;

        function set() {
            $('.slide').cycle({
                fx: "fade",
                speed: 1000,
                timeout: 0,
                next: "#nextBtn",
                prev: "#prevBtn"
            });


            var next = $('#nextBtn');
            var prev = $('#prevBtn');
            next.bind('click', function () {
                now--;
                if (now == -1) {
                    now = aLi.length - 1;
                }
                move();
            });
            prev.bind('click', function () {
                now++;
                if (now == aLi.length) {
                    now = 0;

                }
                move();
            })
        }

        set();

        function move() {
            for (var i = 0; i < aLi.length; i++) {
                aLi[i].className = '';

            }
            aLi[now].className = 'active';


        }
    });


</script>