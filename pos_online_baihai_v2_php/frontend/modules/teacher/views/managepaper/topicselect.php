<?php
/**
 * Created by PhpStorm.
 * User: unizk
 * Date: 14-11-15
 * Time: 下午4:55
 */

use frontend\components\helper\TreeHelper;
use frontend\models\dicmodels\DegreeModel;

/* @var $this yii\web\View */  $this->title="筛选题目";
?>

<script>

    $(function () {

        //知识点定位

        $('.paperStructureBox ').itemFixed({fixTop:50,fixWidth:808,fix_zIndex:90,margin_left:0,scroll_top:50});

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


        $('.finish_btn').live('click',function(){
            if($('.paperLi a').length<1){
                popBox.errorBox('请选择题！');
                return false;
            }
            var urls = [];
            $(".paperLi a").each(function (i) {
                urls.push($(this).text());
            });
            var id=urls.join(',');
            var questionTeamID ='<?php echo $questionTeamID;?>';
            $.post("<?php echo url('teacher/managepaper/add-question-to-team')?>",{questionTeamID:questionTeamID,id:id},function(data){
                if(data.success){
                    window.onbeforeunload=null;
                    window.location.href = "<?php echo  url('teacher/managepaper/topic-push'); ?>";
                }else{
                    popBox.errorBox('错误');
                    return false;
                }
            })

        });


        $('.tree').tree();
        $('.editTreeList').live('click',function(){
            $('.tree').find('a').removeClass('ac');
        });

        //平台搜索
        $('.seaQuestion').click(function(){
            var questionTeamID ='<?php echo $questionTeamID;?>';
            var identity = $(this).attr('data-id');
            var url = '<?=url('/teacher/managepaper/topicselect');?>';
            $.get(url,{questionTeamID:questionTeamID,identity:identity},function(data){
                $('#update').html(data);
            })
        });

    });
    window.onbeforeunload=function (){
        event.returnValue = "";
    }
</script>

<div class="grid_24 main_r">
<div class="main_cont tezhagnhaioast_problem">
<div class="title">
    <h4>题目筛选</h4>
</div>

<hr>
<br>
<div class="form_list type">
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

    <div class="grid_5 alpha knowledge" style=" width:230px;">
        <div class="problem_tree_cont" >
        <h4>基本知识</h4>
        <a class="resetting editTreeList"  data-value="" onclick="getSearchList(this,'kid')" >重置知识点</a>
	        <div id="problem_tree"  class="problemTreeWrap">
            <?php  echo TreeHelper::streefun($knowledgePoint,['onclick'=>"return getSearchList(this,'kid');"],'tree pointTree')?>
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
            <li><a href="javascript:;" class="seaQuestion ac" data-id="0">平台题库</a></li>
            <li><a href="javascript:;" class="seaQuestion" data-id="1">我的题库</a></li>
            <li><a href="javascript:;" class="seaQuestion" data-id="2">我收藏的题目</a></li>
        </ul>
        <div class="tabCont problem_tab_r">

            <div class="tabItem">
                <div class="schResult">
                    <div class="testPaperView pr">
                        <div class="paperArea">
                            <div class="schResult">
                                <div class="paperStructure paperStructureBox clearfix" style="width: 808px;">
                                    <!--new-->
                                    <h4>已选择的题目(点击题目编号预览)：</h4>
                                    <ul class="paperStructure_list clearfix">
                                        <?php
                                            if(!empty($res)){
                                                foreach($res as $li){
                                        ?>
                                                    <li class="paperLi" alert="<?= $li->questionId?>"><a href="javascript:;"><?= $li->questionId?></a><em>x</em></li>
                                        <?php  } }?>

                                        <li class="paperStructure_Btn">
                                            <button class="btn w80 bg_green finish_btn">选题完毕</button>
                                        </li>
                                    </ul>
                                    <div class="demoBar hide" >
                                        <span class="close">关闭预览</span>
                                        <div id="showqe">
                                        </div>
                                    </div>
                                </div>
                                <div id="update">
                                    <?php echo $this->render('_topicselect_list', array('res'=>$res,"topic_list" => $topic_list, "pages" => $pages)); ?>
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

<script type="text/javascript">
    var getSearchList = function (obj, t) {
        var com = $('.complexity .ac').attr("data-value");
        var kid = $('.problem_tree_cont .ac').attr("data-value");
        switch (t) {
            case  "com":
                com = $(obj).attr('data-value');
                break;
            case  "kid":
                kid = $(obj).attr('data-value')
        }
        $.get("<?php  echo app()->request->url;?>", { complexity: com,kid:kid}, function (data) {
            $("#update").html(data);
        })
    }

</script>







