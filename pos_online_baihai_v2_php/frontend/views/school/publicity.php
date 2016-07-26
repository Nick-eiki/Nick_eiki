<?php
/**
 * Created by PHPstorm.
 * User: mahongru
 * Date: 15-7-7
 * Time: 下午18:30
 */
/* @var $this yii\web\View */  $this->title='学校-学校公示';
?>
				<div class="main_cont">
					<div class="title">
						<h4>学校公示</h4>
						<div class="title_r">
                            <?php if(loginUser()->isTeacher()){?>
							<a href="<?= url('school/new-publicity', array('schoolId' => $schoolId)); ?>" class="btn w150 bg_green btn40">添加公示</a>
                            <?php }?>
							<!--判断当前用户是谁，我要提问按钮便链接到当前用户个人管理中心的提问页面-->
						</div>
					</div>
					<ul class="resultList clearfix publicTabList">
						<li class="ac" publicityType="1"><a href="javascript:;">校园公告</a>
						</li>
						<li  publicityType="2"><a href="javascript:;">校园生活</a>
						</li>
						<li  publicityType="3"><a href="javascript:;">校园新闻</a>
						</li>
						<li  publicityType="4"><a href="javascript:;">教育综合</a>
						</li>
						<li  publicityType="5"><a href="javascript:;">招生动态</a>
						</li>
						<li  publicityType="6"><a href="javascript:;">荣誉墙</a>
						</li>
					</ul>
                    <div class="publicityList">

					<?php echo $this->render("_publicity_list",array("pages"=>$pages,"publicityList"=>$publicityList));?>
					</div>

			</div>
<script>
    $(".publicTabList").find("li").click(function(){
        var  publicityType=$(this).attr("publicityType");
        $.post("<?=url('school/publicity',array('schoolId'=>app()->request->getParam('schoolId')))?>",{publicityType:publicityType},function(result){
            $(".publicityList").html(result);
        })
    })
</script>


