<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/4/28
 * Time: 13:23
 */

/* @var $this yii\web\View */
use frontend\components\helper\AreaHelper;
use frontend\components\helper\ViewHelper;

$isMaster = $this->context->MasterClass();    //班主任所在的班级id

?>

<div id="srchResult">
	<ol class="hand_List">
		<?php foreach ($model as $v): ?>

			<li class="">
				<dl class="clearfix">
					<dt class="fl">
                            <?php
                                $isInClass = loginUser()->getInClassInfo($v->classID);
                                if($isInClass ===false){
                            ?>
							<img  class="imageLink" conSta="<?php echo $v->conSta;?>" linkClassId="<?php echo $v->classID?>" src="<?=publicResources_new() . "/images/class.png";?>" alt="" />
                            <h4  class="classLink" conSta="<?php echo $v->conSta;?>" linkClassId="<?php echo $v->classID?>"><a href="javascript:;"><?php echo $v->className; ?></a></h4>
                            <?php }else{?>
                                    <a href="<?php echo url("class/index",array('classId'=>$v->classID))?>"><img src="<?=publicResources_new() . "/images/class.png";?>" alt="" /></a>
                                    <h4><a href="<?php echo url("class/index",array('classId'=>$v->classID))?>"><?php echo $v->className; ?></a></h4>
                            <?php }?>
					</dt>
					<dd class="title"><h5>班主任：<?php echo $v->classChargeName; ?></h5></dd>
					<dd class="slo">
						<span>学校：<?php echo $v->schoolName; ?></span>
						<span>地区：<?php echo AreaHelper::getAreaName($v->provience).'&nbsp'. AreaHelper::getAreaName($v->city).'&nbsp'.AreaHelper::getAreaName($v->country) ;?></span>
					</dd>
					<dd>
                        <em>任课教师：</em>
						<br>
                        <?php $num = 0;?>
                        <p class="cls_and_teacher">
						<?php
						if (isset($v->teacherMap)):
							foreach ($v->teacherMap as $v2):
                                $num++;
								?>
								<span>[<?php echo $v2->subjectName ?>]&nbsp;&nbsp;<a href="javascript:;" title="<?php echo $v2->trueName?>"><?php echo cut_str($v2->trueName,7) ?></a></span>
							<?php
							endforeach;
						endif;
						?>
                        </p>
                        <?php if($num > 5){?>
                            <a class="moreClsBtn" href="javascript:;">▼</a>
                        <?php }?>
					</dd>
					<dd>
						<span>学生<em><?php echo $v->classStuMember ?></em>人</span>
						<span>精品课程<em><?php echo $v->boutiqueCourseNum ?></em>门</span>
						<span>讲义<em><?php echo $v->draftCourseNum ?></em>篇</span>
						<span>教案<em><?php echo $v->caseCourseNum ?></em>篇</span>
					</dd>
				</dl>

			</li>
		<?php endforeach; ?>
		<?php if (empty($model)): ?>
			<li class="this">
			<?php ViewHelper::emptyView(); ?>
			</li>
		<?php endif; ?>
	</ol>

		<?php
		 echo \frontend\components\CLinkPagerExt::widget( array(
				'pagination' => $pages,
				'updateId' => '#srchResult',
				'maxButtonCount' => 5
			)
		);
		?>

</div>

<script type="text/javascript">
    $(function () {

        //班级链接的判断
        $('.classLink,.imageLink').click(function(){
                var conSta = $(this).attr('conSta');
                var linkClassId = $(this).attr('linkClassId');
                if(conSta == 1){
                    location.href = '<?php echo url("class/index")?>'+'?classId='+linkClassId;
                }else{
                    popBox.errorBox('还不是手拉手班级，您没有权限访问');
                }
        });

        //显示更多任课老师
        (function(){
            var _this;
            $('.moreClsBtn').click(function(){
                _this=$(this);
                //$('.cls_and_teacher').removeClass('showClass');
                $(this).prevAll('p').addClass('showClass');
                return false;
            });
            $(document).click(function(){
                $('.cls_and_teacher').removeClass('showClass');
            })
        })();

        //申请弹框
        $('#applyForJs').dialog({
            autoOpen: false,
            width: 500,
            height: 240,
            modal: true,
            resizable: false,
            buttons: [
                {
                    text: "确定",
                    click: function () {

                        var url = '<?php echo url("ajax/apply-shoulashou")?>';
                        var classIdA = '<?php if(isset($isMaster->classID)){echo $isMaster->classID;}?>';
                        var classIdB = _this.attr('app_classid');
                        var self = this;
                        $.post(url, {classIdA: classIdA, classIdB: classIdB}, function (result) {
                            if (result.success == true) {
                                $(self).dialog("close");
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "取消",
                    click: function () {
                        $(this).dialog("close");
                    }
                }

            ]
        });

        //处理申请弹框
        $('#receiveForJs').dialog({
            autoOpen: false,
            width: 500,
            height: 400,
            modal: true,
            resizable: false,
            buttons: [
                {
                    text: "确定",
                    click: function () {

                        var url = '<?php echo url("ajax/handle-shoulashou")?>';
                        var classId = '<?php if(isset($isMaster->classID)){echo $isMaster->classID;}?>';
                        var connectId = _self.attr('rec_classid');
                        var msgCode = $("input[name='response_code']:checked").val();
                        var reason = $('.response_reason').val();
                        var self = this;
                        $.post(url, {
                            classId: classId,
                            connectId: connectId,
                            msgCode: msgCode,
                            reason: reason
                        }, function (result) {
                            if (result.success == true) {
                                $(self).dialog("close");
                                location.reload();

                            }
                        });
                    }
                },
                {
                    text: "取消",
                    click: function () {
                        $(this).dialog("close");
                    }
                }

            ]
        });

        //申请手拉手班级
        $('.applyForJs').click(function () {
            _this = $(this);
            var schoolName = _this.attr('schoolName');
            var className = _this.attr('className');
            var provience = _this.attr('provience');
            var city = _this.attr('city');
            var content = provience + ' ' + city + ' ' + schoolName;
            $("#apply_area").text(content);
            $("#apply_class").text(className);
            $("#applyForJs").dialog("open");
            return false;
        });

        //处理向我申请的手拉手班级
        $('.receiveForJs').click(function () {

            _self = $(this);
            var schoolName = _self.attr('schoolName');
            var className = _self.attr('className');
            var provience = _self.attr('provience');
            var city = _self.attr('city');
            var content = provience + ' ' + city + ' ' + schoolName;
            $("#receive_area").text(content);
            $("#receive_class").text(className);
            $("#receiveForJs").dialog("open");
            return false;

        });

        $('.free').click(function(){
            var $self = $(this);
            var id = $self.attr('bid');
            var classId = $self.attr('classId');
            popBox.confirmBox("是否确定解除手拉手关系", function () {
                var url = "<?php echo url('class/cancel-binder')?>";
                $.post(url, {'id': id, 'classId': classId}, function (msg) {
                    if(msg.success){
                        location.reload();
                    }
                });
            });
        });

        $('.hand_List li').live('mouseover',function(){
            $(this).addClass('this');
        });

        $('.hand_List li').live('mouseout',function(){
            $(this).removeClass('this');
        });

    });

</script>


