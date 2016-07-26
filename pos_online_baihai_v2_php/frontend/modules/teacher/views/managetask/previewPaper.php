<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-4
 * Time: 下午3:40
 */
use frontend\components\helper\ImagePathHelper;
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */  $this->title="试卷预览";
?>
<script type="text/javascript">
	$(function() {
//		imgArr=["../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg","../../images/testPaper.jpg"]
//		$('.correctPaperSlide').testpaperSlider({ClipArr:imgArr});

		imgArr=[<?php foreach(ImagePathHelper::getPicUrlArray($result->images) as $val){?> "<?php echo $val; ?>",<?php } ?>];
		$('.correctPaperSlide').testpaperSlider({ClipArr:imgArr});

	})
</script>
<!--主体-->

		<div class="grid_19 main_r">
			<div class="main_cont test_class_overall_appraisal">
				<div class="title"> <a href="#" class="txtBtn backBtn"></a>
					<h4><?php echo $result->name ?></h4>
					<div class="title_r">
						<span>交作业截至时间：<?php echo date("Y-m-d",strtotime($result->deadlineTime)); ?></span>
						<button type="button" class="btn40 bg_blue">上传作业</button>
					</div>
				</div>
				<div class="correctPaper">
					<h5><?php echo $result->name ?></h5>
					<ul class="up_details_list">
						<li class="clearfix">

							<p>年级：<span><?php echo $result->gradename ?></span></p>
							<p>科目：<span><?php echo $result->subjectname ?></span></p>
							<p>版本：<span><?php echo $result->versionname ?></span></p>
						</li>
						<li class="clearfix">
							<p>知识点：<span>
									<?php
										$knowledge = new KnowledgePointModel();
										echo $knowledge::findKnowledgeStr($result->knowledgeId)
									?>
								</span></p>
						</li
						<li class="clearfix">
							<p>作业简介：<span><?php echo $result->homeworkDescribe ?></span></p>
						</li>
					</ul>
					<div class="slidClip"></div>
					<div class="correctPaperSlide">
						<div class="testPaperWrap mc pr">
							<ul class="testPaperSlideList slid">
								<?php
								foreach (ImagePathHelper::getPicUrlArray($result->images) as $k => $v) {

									if ($k == 0) {
										?>
										<li class="active"><img src="<?php echo publicResources() . $v ?>" width="830" height="608"  alt="" /></li>
									<?php } else { ?>
										<li><img src="<?php echo publicResources() . $v ?>" width="830" height="608"  alt="" /></li>
									<?php
									}
								} ?>
							</ul>
							<a href="javascript:;" id="prevBtn" class="correctPaperPrev">上一页</a> <a href="javascript:;" id="nextBtn" class="correctPaperNext">下一页</a> </div>
						<div class="sliderBtnBar"></div>
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