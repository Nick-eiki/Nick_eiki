<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/12/15
 * Time: 14:58
 */

/* @var $this yii\web\View */  $this->title="教师-更多视频";
?>
<script type="text/javascript">
    $(function(){
        $('.collectBtn').bind('click', function () {
            var $_this = $(this);
            var id = $_this.attr('collectID');
            var type =$_this.attr('typeId');
            var action = $_this.attr('action');
            $.post("<?php echo url('teacher/default/add-material')?>", {id: id,type:type,action:action}, function (data) {
                if (data.success) {
                    if (action==1){
                        $_this.attr('action',0).text('取消收藏');
                    }
                    else {
                        $_this.attr('action',1).text('收藏');
                    }
                } else {
                    popBox.alertBox(data.message);

                }
            });
        });


    })



</script>
<!--主体内容开始-->

    <div class="centLeft collection_div">
        <h3>他的视频</h3>
        <hr/>

		<?php echo $this->render('_video_list', array('model'=>$model, 'teacherId' => $teacherId, 'pages'=>$pages)); ?>
    </div>
    <div class="centRight">
        <div class="item Ta_teacher">
            <h4>Ta的老师</h4>
            <a class="more" href="#">更多</a>
            <ul class="teacherList">
                <li>
                    <img src="<?php echo publicResources();?>/images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="<?php echo publicResources();?>/images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="<?php echo publicResources();?>/images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="<?php echo publicResources();?>/images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="<?php echo publicResources();?>/images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="<?php echo publicResources();?>/images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="<?php echo publicResources();?>/images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="<?php echo publicResources();?>/images/user_m.jpg">
                    张三丰
                </li>
                <li>
                    <img src="<?php echo publicResources();?>/images/user_m.jpg">
                    张三丰
                </li>
            </ul>
        </div>
    </div>
<!--主体内容结束-->



<!--弹出框pop--------------------->
