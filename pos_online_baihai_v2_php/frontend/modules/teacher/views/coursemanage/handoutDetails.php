<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/12/4
 * Time: 16:53
 * 讲义详情页
 */
/* @var $this yii\web\View */  $this->title="讲义详情";
?>
<script type="text/javascript">
    $(function(){
        $('.sou_btn_js').bind('click',function(){
            $('.souPosition').show();
        });
        $('#downNum').click(function(){
            var id = $('.handouts').val();
            $.post("<?php echo url('teacher/coursemanage/get-down-num')?>",{id:id},function(data){
                if(data.success){
                    $("#downNum i").html(data.data);
                }else{
                    popBox.alertBox(data.message);
                }
            })
        });

        $('.sou_btn_js').bind('click',function(){
            $('.souPosition').show();
        });
        $('#readNum').click(function(){
            var id = $('.handouts').val();
            $.post("<?php echo url('teacher/coursemanage/get-read-num')?>",{id:id},function(data){
                if(data.success){
                    $("#readNum i").html(data.data);
                }else{
                    popBox.alertBox(data.message);
                }
            })
        })
    })


</script>



<!--主体内容开始-->

    <div class="currentRight grid_16 push_2 stu_Detail_div stu_data">

        <h3><?php echo $model->name; ?></h3>
        <hr/>
        <div class="wd_details">
            <h4><?php echo $model->name; ?></h4>
            <ul class="wd_keywords_list clearfix">
                <li>
                    <p><?php echo $model->subjectname; ?></p>
                </li>
                <li>
                    <p><?php echo $model->gradename; ?></p>
                </li>
                <li>
                    <p><?php echo $model->versionname; ?></p>
                </li>

            </ul>
            <ul class="wd_introduce_list ">
                <?php if(empty($kcidName)){
                        echo '';
                    }else{
                        echo '<li><em>知识点讲解：</em>';
                        foreach($kcidName as $v){
                            echo $v;
                        }
                        echo '</li>';
                    }

                    ?>
                <li><em>讲义详情：</em><?php echo strip_tags($model->matDescribe); ?></li>
            </ul>
            <input type="hidden" class="handouts" value="<?php echo $model->id; ?>">
            <button class="bg_green" id="readNum">阅读教案<span>(<i class="readNum"><?php echo $model->readNum; ?></i>)</span></button>
            <button class="bg_red" id="downNum">下载教案<span>(<i class="downNum"><?php echo $model->downNum; ?></i>)</span></button>
        </div>

    </div>

<!--主体内容结束-->


<!--弹出框pop--------------------->

