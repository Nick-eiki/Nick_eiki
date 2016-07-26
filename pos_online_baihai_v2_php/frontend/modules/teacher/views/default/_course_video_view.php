<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/12/15
 * Time: 17:21
 */
?>
<ul class="item_subList clearfix item_subVideo">
<?php foreach ($resultCourses as $coursesVal) {
   ?>
    <li>
        <img width="120" height="90"  src="../images/video.png" alt=""/>
        <a href="<?php  echo url('/teacher/default/demand-details',array('teacherId' => $teacherId,'courseID'=>$coursesVal->courseID)) ?>">
                <?php  echo $coursesVal->courseName?>
            </a>
		<?php if(!empty($coursesVal->courseBrief)){ ?>
        <h6>简介:
        <span><?php echo mb_substr(strip_tags($coursesVal->courseBrief), 0, 40, 'utf-8') . '·······'; ?></span>
		</h6>
		<?php } ?>
        <p>
            <a class="a_button bg_blue_l" href="<?php echo url('/teacher/default/demand-details',array('teacherId' => $teacherId,'courseID'=>$coursesVal->courseID)) ?>">预览</a>
            <?php
                if($teacherId !=user()->id){
                if($coursesVal->isCollected == 0  ){
                ?>
                <button type="button"  class="bg_orenge collect_btn" action="1" collectID="<?php echo  $coursesVal->courseID;?>" typeId="3">收藏</button>
            <?php } else { ?>
                <button type="button"  class="bg_gray collect_btn" action="0" collectID="<?php echo  $coursesVal->collectID;?>">取消收藏</button>
            <?php }} ?>
        </p>

    </li>
<?php } ?>
	</ul>
<script type="text/javascript">
    $(function(){
        $('.collect_btn').bind('click', function () {
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
