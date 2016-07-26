<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/4/13
 * Time: 17:26
 */
use frontend\components\helper\TreeHelper;

/* @var $this yii\web\View */
$this->title='题目管理-知识点搜索';

$seachArr= array(
	//题的类型
	'type'=>app()->request->getParam('type'),
	//难度
	'complexity'=>app()->request->getParam('complexity'),
	//学部
	'department'=>app()->request->getParam('department',$department),

	'subjectid'=>app()->request->getParam('subjectid',$subjectid),
	//用于区别我的和平台题库 0：平台 1：我的
	'n'=>app()->request->getParam('n', 0),
	//知识点
	'kid'=> app()->request->getParam('kid'),
	'showTypeId' => app()->request->getParam('showTypeId')
);
?>
<script>
	$(function () {
		//选择课程
		$('.hotWord').click(function () {
			$('.hotWordList').show();
			return false
		});
		$('.hotWordList').mouseleave(function () {
			$(this).hide()
		});
		//
		$('.hotWordList dd').live('click', function () {
			$('.hotWordList dd').removeClass('ac');
			$(this).addClass('ac');
		});

		//试卷中的题目 fixed
		$('.tree').tree();
		$('.editTreeList').live('click',function(){
			$('.tree').find('a').removeClass('ac');
		});
		//$('.tree').tree({expandAll:true,operate:false});

	})

</script>
<!--主体-->

<div class="grid_24 main_r">
	<div class="main_cont tezhagnhaioast_problem">
		<div class="title">
			<h4>题目管理</h4>
		</div>
		<?php echo $this->render('//publicView/search/_top_list',array('department'=>$department,'subjectid'=>$subjectid,'homeworkId' =>'')); ?>
		<hr>
		<div class="form_list type">
			<?php  echo $this->render('_type_listData',array('result'=>$result,'seachArr'=>$seachArr))?>
		</div>
		<div class="problem_box clearfix" >
			<div class="grid_5 alpha knowledge" style=" width:230px;">
				<div class="problem_tree_cont">
					<h4>基本知识</h4>
					<a class="resetting" href="<?php echo url('teacher/searchquestions/knowledge-point-questions',array_merge($seachArr,array('kid'=>null)));?>" class="<?php echo app()->request->getparam('kid')==null?"ac":""?> ">重置知识点</a>
					<div id="problem_tree"  class="problemTreeWrap">
						<?php  echo TreeHelper::streefun($knowtree,['onclick'=>"return getSearchList(this,'kid');"],'tree pointTree')?>
					</div>
				</div>
			</div>
			<div class="problem_r grid_18 omega alpha">
				<div class="problem_r_list">
					<h5>增加题目<i></i></h5>
					<?php echo $this->render('//publicView/search/_a_link'); ?>
				</div>
				<div class="tab fl">
					<ul class="tabList clearfix">
						<li class="select_n"><a href="javascript:;" class="ac" onclick="return getSearchList(this,'n')" data-value="0">平台题库</a></li>
						<li class="select_n"><a href="javascript:;" onclick="return getSearchList(this,'n')" data-value="1">我的题库</a></li>
						<li class="select_n"><a href="javascript:;" onclick="return getSearchList(this,'n')" data-value="2">我收藏的题目</a></li>
					</ul>
					<div id="update">
					<?php echo $this->render('_knowledge_point_right', array("questionList" => $questionList, 'result'=>$result, 'pages' => $pages)); ?>
						</div>
				</div>
			</div>
		</div>

	</div>
</div>
<script type="text/javascript">
    $(function() {
        //收藏
        $('.page_fav').live("click", function () {
            var self = $(this);
            var qid = $(this).attr('data-id');
            var url = '<?=url('/teacher/searchquestions/collection-question'); ?>';
            $.post(url, {qid: qid}, function (data) {
                if (data.success) {
                    self.html('<i></i>取消收藏');
                    self.removeClass('page_fav');
                    self.addClass('page_cancel_fav');
                    self.unbind();
                } else {
                    popBox.errorBox(data.message);
                }
            });
        });

        //取消收藏
        $('.page_cancel_fav').live("click", function () {
            var self = $(this);
            var qid = $(this).attr('data-id');
            var url = '<?=url('/teacher/searchquestions/cancel-collection-question'); ?>';
            $.post(url, {qid: qid}, function (data) {
                if (data.success) {
                    self.html('<i></i>收藏');
                    self.removeClass('page_cancel_fav');
                    self.addClass('page_fav');
                    //self.unbind();
                } else {
                    popBox.errorBox(data.message);
                }
            });
        });
    });
	var getSearchList = function (obj, t) {
		// 0 平台题库  1我的题库 2收藏的题库
		var n = $('.select_n .ac').attr('data-value');
		var kid = $('.problem_tree_cont .ac').attr("data-value");
		var isPic = $('.type_list .ac').attr('data-value');
		switch (t) {
			case  "n":
				n = $(obj).attr('data-value');
                isPic = '';
				break;
			case  "kid":
				kid = $(obj).attr('data-value');
				break;
			case  "isPic":
                isPic = $(obj).attr('data-value')
		}
		$.get("<?php  echo app()->request->url;?>", { kid: kid,n:n, isPic:isPic}, function (data) {
			$("#update").html(data);
		})
	};
</script>

