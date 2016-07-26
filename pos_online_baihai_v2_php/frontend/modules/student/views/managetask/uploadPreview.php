<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-15
 * Time: 上午11:27
 */
use frontend\components\helper\ImagePathHelper;

/* @var $this yii\web\View */  $this->title="作业预览";
?>
<div class="currentRight grid_16 push_2">
    <div class="noticeH clearfix noticeB uploadedPaper_title">
        <h3 class="h3L"><?php echo $result->name ?></h3>
    </div>
    <hr>
    <div class="up_details">
        <h4><?php echo $result->name ?></h4>
        <ul class="up_details_list">
            <li class="clearfix"><p>
                    地区：<span><?php echo AreaHelper::getAreaName($result->provience) . "&nbsp" . AreaHelper::getAreaName($result->city) . "&nbsp" . AreaHelper::getAreaName($result->country) ?></span>
                </p>

                <p>年级：<span><?php echo $result->gradename ?></span></p>

                <p>科目：<span><?php echo $result->subjectname ?></span></p>

                <p>版本：<span><?php echo $result->versionname ?></span></p></li>
            <li class="clearfix"><p>知识点：<span><?php $knowledge = new KnowledgePointModel();
                        echo $knowledge::findKnowledgeStr($result->knowledgeId) ?></span></p></li>
            <li class="clearfix"><p>作业简介：<span><?php echo $result->homeworkDescribe ?></span></p>
            </li>
        </ul>
        <div class="up_details_t">
            <h6>作业内容：</h6>
            <!--<a href="javascript:;" class="direction prev">上一个</a>
            <a href="javascript:;" class="direction after">下一个</a>-->
            <div class="ul_list_box">
                <ul class="clearfix deta_list" id="deta_list">
                    <?php foreach (ImagePathHelper::getPicUrlArray($result->images) as $k => $v) {
                        if ($k == 0) {
                            ?>
                            <li class="active"><img src="<?php echo publicResources() . $v ?>"></li>
                        <?php } else { ?>
                            <li><img src="<?php echo publicResources() . $v ?>" alt=""></li>
                        <?php
                        }
                    } ?>
                </ul>


            </div>
            <a href="javascrpit:;" class="direction pre" id="prevBtn"></a>
            <a href="javascrpit:;" class="direction next" id="nextBtn"></a>

            <div class="paper_pic_box">
                <ol class="ol_list slide">
                    <?php foreach (ImagePathHelper::getPicUrlArray($result->images) as $k => $v) {
                        if ($k == 0) {
                            ?>
                            <li style="opacity:1;filter:alpha(opacity=100);"><img
                                    src="<?php echo publicResources() . $v ?>" alt=""></li>
                        <?php } else { ?>
                            <li><img src="<?php echo publicResources() . $v ?>" alt=""></li>
                        <?php
                        }
                    } ?>
                </ol>
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