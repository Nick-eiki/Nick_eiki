<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/4/20
 * Time: 10:25
 */
/* @var $this yii\web\View */
/* @var $this yii\web\View */  $this->title="答疑管理";

?>


<script>
$(function(){

	$('.myQuestion').click(function(){
		$('#my_question').dialog({
			autoOpen: false,
			width:400,
			modal: true,
			resizable:false,
			buttons: [
				{
					text: "前去检索",

					click: function() {
						if($('#mySchoolPop .text').val()==1){
							$( this ).dialog( "close" );
						}
						else{
							location.href = '<?php echo url('/terrace/answer/index');?>';
						}
					}
				},
				{
					text: "我要提问",

					click: function() {
						if($('#mySchoolPop .text').val()==1){
							$( this ).dialog( "close" );
						}
						else{
							if('<?php echo loginUser()->isStudent();?>'){
								location.href = '<?php echo url('student/answer/add-question',array('studentId'=>app()->request->getParam('studentId','')));?>';
							}else if('<?php echo loginUser()->isTeacher();?>'){
								location.href = '<?php echo url('teacher/answer/add-question',array('teacherId'=>app()->request->getParam('teacherId','')))?>';
							}
						}
					}
				}
			]
		});
		$( "#my_question" ).dialog( "open" );
		return false;
	});

	//点击搜索按钮
	$('#search_word').click(function(){
		var keyWord = $('#searchText').val();
		$.get('<?php echo url("teacher/answer/answer-questions");?>',{keyWord:keyWord},function(data){
			$('.make_testpaper').html(data);
		});
	});
	$('#searchText').placeholder({value:'请输入要提问的问题',ie6Top:10})

})

</script>
<!--top_end-->
<!--主体-->

        <div class="grid_19 main_r">
            <div class="main_cont test answer_questions">
                <div class="title">
                    <h4>我的问题</h4>
                    <div class="title_r clearfix">
                        <span>
                            <input type="text" class="text searchText search_ansewr_text" id="searchText"><button type="button"  class="hideText TextBtn searchBtn " id="search_word">搜索</button>
                        </span>
                        <a href="javascript:" class="a_button titleBtn btn w120 bg_green myQuestion">我要提问</a>
                    </div>
                </div>
				<div class="make_testpaper" >
					<?php echo $this->render('//publicView/answer/_answer_list', array('modelList'=>$modelList,'pages' => $pages));?>
					</div>
            </div>
        </div>
<!--主体end-->

<!--添加作业内容-->


<!--新增加我要提问弹窗-->
<div id="my_question" class="my_question popoBox hide " title="答疑管理">
	<div class="impBox">
		<form>
			<div class="answer_text" style="text-align:center; line-height: 55px;">
				请先看一下是否已有相同问题
			</div>

		</form>
	</div>
</div>