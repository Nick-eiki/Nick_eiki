<?php
/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 15-4-16
 * Time: 下午4:41
 */
?>
<div class="schResult">
    <?php
    if(!empty($topic_list)){
        foreach($topic_list as $key=>$item){
            echo $this->render('//publicView/paper/_topicpush_ItemProblem',['item'=>$item]);
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
if(isset($pages)){

     echo \frontend\components\CLinkPagerNormalExt::widget( array(
            'firstPageLabel'=>false,
            'lastPageLabel'=>false,
           'pagination'=>$pages,
            'updateId' => '#update',
            'maxButtonCount' => 8
        )
    );
}

?>

<script>



    $('.openAnswerBtn').toggle(function(){
        $(this).parents('.paper').find('.answerArea').show();

    },function(){
        $(this).parents('.paper').find('.answerArea').hide();

    });
    function recover(){//判断题目是否为选中
        var recoverBtn=$('.schResult .addBtn');
        $('.paperStructure_list .paperLi').each(function(index, element) {
            for(var i=0; i<recoverBtn.length; i++){
                if($(this).attr('alert')==recoverBtn.eq(i).attr('id')){
                   // recoverBtn.eq(i).addClass('disableBtn').attr('disabled',true);
                }
                else{
                  //  recoverBtn.eq(i).removeClass('disableBtn').attr('disabled',false);
                }
            }
        });
    }
    recover();


    $(function(){
        //组卷按钮
        $('.paper .addBtn').die().live('click',function(){
            var id=$(this).attr('id');
            var pid=$(this).attr('pid');
            $(this).removeClass('addBtn').addClass('del_btn').text('删除');
            $('.paperStructure_list .paperStructure_Btn').before('<li class="paperLi" alert='+ id +'><a href="javascript:;">'+ id +'</a><em>x</em></li>');
        });
        //删除动作
        $('.paper .del_btn').die().live('click',function(){
            var id=$(this).attr('id');
            $(this).removeClass('del_btn').addClass('addBtn').text('组卷');
            var pid=$(this).attr('pid');
            $('.paperStructure_list li').each(function(index, element) {
                if($(this).attr('alert')==id) $(this).remove();
            });
            $('.demoBar').hide();
        });

        //删除动作
        $('.paperStructure_list .paperLi em').die().live('click',function(){
            var _this=$(this);
            $(this).parents('paperLi').removeClass('this');
            $('.demoBar').hide();
            _this.parents('.paperLi').remove();
            var ala=$(this).parents('li').attr('alert');
            if($('.schResult .editBtn').size()>0){
                $('.schResult .editBtn').each(function(index, element) {
                    var it=$(this).attr('id');
                    if(ala==it){
                        $(this).removeClass('del_btn').addClass('addBtn').text('组卷');

                    }
                });
            }
        });

        //显示题目
        $('.paperStructure_list .paperLi a').live('click',function(){
            $('.paperStructure_list .paperLi').removeClass('this');
            $(this).parent().addClass('this');
            $.get('<?php echo \yii\helpers\Url::to(['view-pager-by-id']) ?>', {qid: $(this).text()}, function (html) {
                $('#showqe').html(html);
                $('.demoBar').show();
            });
        });

        $('.demoBar .close').bind('click',function(){
            $(this).parent().hide();
            $('.paperStructure_list .paperLi').removeClass('this');
        });

        $('.paperStructure_list  li[alert]').each(function () {
            $id = $(this).attr('alert');
            $('.paper button[id=' + $id + ']').each(function () {
                $(this).removeClass('addBtn').addClass('del_btn').text('删除');
            });
        });
    })



</script>

