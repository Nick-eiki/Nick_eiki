<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2015/5/22
 * Time: 18:20
 */

use common\helper\UserInfoHelper;
use frontend\components\helper\TreeHelper;
use yii\helpers\Url;

$seachArr= array(

    //题的类型
    'type'=>app()->request->getParam('type'),
    //难度
    'complexity'=>app()->request->getParam('complexity'),
    //学部
    'department'=>app()->request->getParam('department',$department),
    'subjectid'=>app()->request->getParam('subjectid',$subjectid),
    //版本
    'version'=> app()->request->getParam('version',loginUser()->getModel()->textbookVersion),
    'gradeId' => app()->request->getParam('gradeId',UserInfoHelper::getUserSubjectId(user()->id)),

);
/* @var $this yii\web\View */
$this->title='题目管理-同步章节选题';


?>

<script>
    $(function(){
        //选择课程
        $('.hotWord').click(function(){$('.hotWordList').show();return false});
        $('.hotWordList').mouseleave(function(){$(this).hide()});
        //
        $('.hotWordList dd').live('click',function(){
            $('.hotWordList dd').removeClass('ac');
            $(this).addClass('ac');
        });
//试卷中的题目 fixed

        $('.tree').tree();
        $('.editTreeList').live('click',function(){
            $('.tree').find('a').removeClass('ac');
        });

      //  $('.tree').tree({expandAll:true,operate:false});


    })
</script>

<!--top_end-->
<!--主体-->

<div class="grid_24 main_r">
    <div class="main_cont tezhagnhaioast_problem chapter_problem">
        <div class="title">
            <h4>题目管理</h4>
        </div>
        <div class="form_list no_padding_form_list">
            <?php echo $this->render('//publicView/search/_top_list',array('department'=>$department,'subjectid'=>$subjectid,'homeworkId' =>'')); ?>
            <hr>

        </div>
        <div class="form_list type">
            <div class="row">
                <div class="formL">
                    <label>版本：</label>
                </div>
                <div class="formR">
                    <ul class="resultList clearfix testClsList">
                        <?php foreach($versionList as $versionVal){
                            if ($department==20201 && $subjectid=10011 && $versionVal->secondCodeValue=='北师大版') {continue;} ?>
                            <li class="<?= $version==$versionVal->secondCode ? 'ac':''; ?>">
                                <a href="<?= Url::to(array_merge([''],$seachArr, ['version'=>$versionVal->secondCode]));?>"><?=$versionVal->secondCodeValue?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="formL">
                    <label>分册：</label>
                </div>
                <div class="formR">
                    <ul class="resultList clearfix testClsList">
                        <li class="<?php echo app()->request->getParam('chapId','')==null ? 'ac':''; ?>">
                            <a href="<?php echo Url::to(array_merge([''],$seachArr, ['chapId'=>null]));?>">全部分册</a>
                        </li>
                        <?php foreach($chapterTomeResult as $key=>$charterVal){  ?>
                            <li class="<?php echo app()->request->getParam('chapId','')==$charterVal->id ? 'ac':''; ?>"><a href="<?php echo Url::to(array_merge([''],$seachArr, ['chapId'=>$charterVal->id]));?>"><?php echo $charterVal->name?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <?php  echo $this->render('_type_listData', ['result'=>$result,'seachArr'=>$seachArr])?>
        </div>
        <div class="problem_box clearfix">
            <div class="grid_5 alpha knowledge" >
                <div class="problem_tree_cont">
                    <h4>章节目录</h4>
                    <a class="resetting" href="<?php echo url('teacher/searchquestions/chapter-questions',array_merge($seachArr,array('kid'=>null)));?>" class="<?php echo app()->request->getparam('kid')==null?"ac":""?> ">重置</a>
                    <div id="problem_tree"  class="problemTreeWrap">
                        <?php  echo TreeHelper::streefun($chapterTree,['onclick'=>"return getSearchList(this,'chapId');"],'tree pointTree')?>
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
                        <li class="select_n"><a href="javascript:;"  class="ac" onclick="return getSearchList(this,'n')" data-value="0">平台题库</a></li>
                        <li class="select_n"><a href="javascript:;" class="" onclick="return getSearchList(this,'n')"  data-value="2">我收藏的题目</a></li>
                    </ul>
                    <div id="update">
                        <?php echo $this->render('_knowledge_point_right', ["questionList" => $questionList, 'result'=>$result, 'pages' => $pages]); ?>
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
        var chapId = $('.problem_tree_cont .ac').attr("data-value");
        switch (t) {
            case  "n":
                n = $(obj).attr('data-value');
                break;
            case  "chapId":
                chapId = $(obj).attr('data-value')
        }
        $.get("<?php  echo app()->request->url;?>", { chapId: chapId,n:n}, function (data) {
            $("#update").html(data);
        })
    };
</script>