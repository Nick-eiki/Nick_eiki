<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-16
 * Time: 下午2:28
 */
use frontend\components\helper\TreeHelper;
use frontend\models\dicmodels\DegreeModel;
use yii\helpers\Url;

/* @var $this yii\web\View */  $this->title='教师--作业管理-组织作业';

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


        $('.choice_finish').live('click',function(){
            if($('.paperLi a').length<1){
                popBox.errorBox('请选择题！');
                return false;
            }
            var urls = [];
            $(".paperLi a").each(function (i) {
                urls.push($(this).text());
            });
           var id=urls.join(',');
           var homeworkid ='<?php echo $homeworkId;?>';
            $.post("<?php echo url('teacher/managetask/get-question-by-id')?>",{homeworkid:homeworkid,id:id},function(data){
                if(data.success){
                    window.onbeforeunload=null;
                    window.location.href = "<?= url('teacher/resources/collect-work-manage'); ?>";
                     }else{
               popBox.errorBox('错误');return false;
                }
            })

        });




//试卷中的题目 fixed

        $('.tree').tree({expandAll:true,operate:false});
         var divTop=$('.paperStructure').offset().top;
         var divW=$('.paperStructure').width();
         var divH=$('.paperStructure').outerHeight()+20;
         var windowScrollTop;
         $(window).scroll(function(){
            windowScrollTop=$(window).scrollTop();
            if(windowScrollTop>=divTop){
                $('.paperStructure').css({'position':'fixed','top':50,'width':divW,'z-index':100});
                $('.paperStructure').next().css({'padding-top':divH+'px'})
            }
            else{
                $('.paperStructure').css({'position':'static'});
                $('.paperStructure').next().css({'padding-top':0})
            }
         });
    });
    window.onbeforeunload=function (){
        event.returnValue = "";
    }
</script>

<!--主体-->
<div class="grid_24 main_r">
    <div class="main_cont tezhagnhaioast_problem">
        <div class="title">
            <h4>组织作业</h4>
        </div>
        <hr>
        <br>

        <div class="form_list type">
            <div class="row ">


                <div class="formR">
                    <ul class="resultList testClsList type">
                        <li data-value="" onclick="getSearchList(this,'type')" class="ac"><a>全部类型</a></li>
                        <?php
                        foreach ($result as $key => $item) {
                            ?>
                            <li data-value="<?= $item->typeId; ?>"
                                onclick="getSearchList(this,'type')"><a><?php echo $item->typeName; ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="row">

                <div class="formR">
                    <ul class="resultList testClsList complexity">
                        <li data-value="" onclick="return getSearchList(this,'com')" class="ac"><a>全部难度</a></li>
                        <?php foreach (DegreeModel::model()->getList() as $v) { ?>
                            <li data-value="<?= $v->secondCode; ?>"
                                onclick="return getSearchList(this,'com')"><a><?php echo $v->secondCodeValue; ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="problem_box clearfix">
                <div class="grid_5 alpha knowledge" style=" width:230px">
                <div class="problem_tree_cont" >
                    <h4>基本章节</h4>
                    <a class="resetting editTreeList"  data-value="" onclick="getSearchList(this,'kid')" >重置章节</a>
	                <div id="problem_tree"  class="problemTreeWrap">
                           <?php  echo TreeHelper::streefun($chapterData,['onclick'=>"return getSearchList(this,'kid');"],'tree pointTree',$homeworkResult->chapterId)?>
                    </div>
                </div>


            </div>
            <div class="problem_r grid_18 omega alpha">
                <div class="problem_r_list">
                    <h5>增加题目<i></i></h5>
                    <ul class="hot" style="display:none;">
                        <li><a class="t_ico" href="javascript:;">增加题目<i></i></a></li>
                        <li class="list this "><a class="" target="_blank" href="<?php echo url('teacher/testpaper/add-topic'); ?>">录入新题</a></li>
                        <li class="list"><a class="" target="_blank" href="<?php echo url('teacher/testpaper/camera-upload-new-topic'); ?>">上传新题</a></li>
                    </ul>
                </div>
                <div class="tab fl">
                    <ul class="tabList clearfix">

                        <li class="select_n"><a href="javascript:;" class="ac" n="0">平台题库</a></li>
	                    <li class="select_n"><a href="javascript:;" class="" n="1">我的题库</a></li>
                        <li class="select_n"><a href="javascript:;" n="2">我收藏的题目</a></li>
                    </ul>
                    <div class="tabCont problem_tab_r">
                        <div class="tabItem">
                            <div class="schResult">
                                <div class="testPaperView pr">
                                    <div class="paperArea">
                                        <div class="schResult">
                                            <div class="paperStructure paperStructureBox clearfix" style="width: 796px;">
                                                <!--new-->
                                                <h4>已选择的题目(点击题目编号预览)：</h4>
                                                <ul class="paperStructure_list clearfix">
                                                    <?php if(!empty($questionArray)){ foreach($questionArray as $v){?>
                                                        <li class="paperLi" alert="<?=$v['questionId']?>">
                                                            <a href="javascript:;"><?=$v['questionId']?></a>
                                                            <em>x</em>
                                                        </li>
                                                    <?php } }?>
                                                    <li class="paperStructure_Btn">

                                                        <button class="btn w80 bg_green choice_finish">选题完毕</button>
                                                    </li>
                                                </ul>
                                                <div class="demoBar hide" >
                                                    <span class="close">关闭预览</span>
                                                    <div id="showqe">
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="update">
                                                <?php echo $this->render('_organize_list', array("topic_list" => $topic_list, "pages" => $pages)); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--主体end-->

<script type="text/javascript">
	$(function(){
		// 0 平台题库  1我的题库 2收藏的题库
		$('.select_n').click(function(){
			var n = $(this).find('.ac').attr('n');
            var type=$(".type .ac").attr("data-value");
            var complexity=$(".complexity .ac").attr("data-value");
            var kid = $('.problem_tree_cont .ac').attr("data-value");
            var homeworkID='<?=app()->request->getQueryParam("homeworkid")?>';
			$.get("<?php echo url('teacher/managetask/new-preview-organize-paper')?>",{n:n,type:type,complexity:complexity,kid:kid,homeworkid:homeworkID},function(data){
				$("#update").html(data);
			})
		})
	});
    var getSearchList = function (obj, t) {
	    var type = $('.testClsList .ac').attr("data-value");
	    var com = $('.complexity .ac').attr("data-value");
	    var kid = $('.problem_tree_cont .ac').attr("data-value");
        var n=$(".tabList").find(".ac").attr("n");

	    switch (t) {
		    case  "type":
			    type = $(obj).attr('data-value');
			    break;
		    case  "com":
			    com = $(obj).attr('data-value');
			    break;
		    case  "kid":
			    kid = $(obj).attr('data-value')
	    }
        if(kid==''){
            $(".subMenu").hide();
            $(".subMenu .ac").removeClass("ac");
            $('.openSubMenu').addClass('closeSubMenu');
        }
	    $.get("<?php  echo app()->request->url;?>", {type: type, complexity: com, kid: kid,n:n}, function (data) {
		    $("#update").html(data);
	    })
    };

	$(function(){
		//组卷按钮
		$('.paper .addBtn').live('click',function(){
			var id=$(this).attr('id');
			var pid=$(this).attr('pid');
			$(this).removeClass('addBtn').addClass('del_btn').text('删除');
			$('.paperStructure_list .paperStructure_Btn').before('<li class="paperLi" alert='+ id +'><a href="javascript:;">'+ id +'</a><em>x</em></li>');
		});
		//删除动作
		$('.paper .del_btn').live('click',function(){
			var id=$(this).attr('id');
			$(this).removeClass('del_btn').addClass('addBtn').text('加入作业');
			var pid=$(this).attr('pid');
			$('.paperStructure_list li').each(function(index, element) {
				if($(this).attr('alert')==id) $(this).remove();
			});
			$('.demoBar').hide();
		});

		//删除动作
		$('.paperStructure_list .paperLi em').live('click',function(){
			var _this=$(this);
			$(this).parents('paperLi').removeClass('this');
			$('.demoBar').hide();
			_this.parents('.paperLi').remove();
			var ala=$(this).parents('li').attr('alert');
			if($('.schResult .editBtn').size()>0){
				$('.schResult .editBtn').each(function(index, element) {
					var it=$(this).attr('id');
					if(ala==it){
						$(this).removeClass('del_btn').addClass('addBtn').text('加入作业');

					}
				});
			}
		});

		//显示题目
		$('.paperStructure_list .paperLi a').live('click',function(){
			$('.paperStructure_list .paperLi').removeClass('this');
			$(this).parent().addClass('this');
			$.get('<?php echo Url::to(['view-pager-by-id']) ?>', {qid: $(this).text()}, function (html) {
				$('#showqe').html(html);
				$('.demoBar').show();
			});
		});

		$('.demoBar .close').bind('click',function(){
			$(this).parent().hide();
			$('.paperStructure_list .paperLi').removeClass('this');
		})

	})
</script>