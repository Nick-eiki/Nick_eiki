<?php
/**
 * Created by unizk.
 * User: ysd
 * Date: 2015/4/21
 * Time: 13:37
 */

?>
<?php
/**
 * Created by unizk.
 * User: ysd
 * Date: 14-12-11
 * Time: 下午6:59
 */
/* @var $this yii\web\View */  $this->title="系统消息";
?>
<script type="text/javascript">
    $(function(){
        $('.resultList li').click(function(){
            var messageType = $(this).attr('messageType');
            $.get('<?php echo url("student/message/sys-msg");?>',{messagetype:messageType},function(data){
                $('#notice').html(data);
            });
        });

    })

</script>
<div class="grid_19 main_r">
    <div class="main_cont notice">
        <div class="title">
            <h4>系统消息</h4>
        </div>
        <ul class="resultList  clearfix noborder" >
            <li messageType="" class="ac"><a href="javascript:;">全部消息</a></li>
            <li messageType="507009"><a href="javascript:;">平台消息</a></li>
            <li messageType="507001"><a href="javascript:;">作业</a></li>
        </ul>
        <div id="notice">
            <?php echo $this->render('_notice_list',array('model'=>$model,'pages' => $pages, "classId"=>$classId));?>
        </div>
    </div>
</div>

<!--主体end-->
