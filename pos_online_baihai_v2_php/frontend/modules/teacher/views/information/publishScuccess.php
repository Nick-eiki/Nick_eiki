<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/11/18
 * Time: 16:27
 */
/* @var $this yii\web\View */  $this->title="发布成功";
?>
<script>
    $('.publish').live('click',function(){
        location.href = '<?php echo url('/teacher/information/add-information')?>';
    });
    $('.read_list').live('click',function(){
        location.href = '<?php echo url('/teacher/information/information-list')?>';
    })
</script>
<!--主体内容开始-->
            <div class="currentRight grid_16 push_2 make_testpaper">
                <div class="notice information">
                    <div class="noticeH noticeB clearfix">
                        <h3 class="h3L">发布资讯</h3>
                    </div>
					<hr>
                    <div class="add_seek">
                        <?php foreach ($model as $v) { ?>
                        <div class="add_seek_text">
                            <?php
                            if($v->informationType == 50101){
                                echo '学校动态';
                            }elseif($v->informationType == 50102){
                                echo "幼升小";
                            }elseif($v->informationType == 50103){
                                echo "小升初";
                            }elseif($v->informationType == 50104){
                                echo "中考";
                            }elseif($v->informationType == 50105){
                                echo "高考";
                            }
                            ?>  新闻  <?php echo $v->informationTitle; ?>    已经发布成功，等待平台编辑审核......</div>
                        <?php } ?>
                        <div class="add_seek_btn">
                            <button type="button" class="bg_green publish">继续发布</button>
                            <button type="button" class="bg_blue read_list">阅读其他资讯</button>
                        </div>
                    </div>
                </div>
            </div>

<!--主体内容结束-->
