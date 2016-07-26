<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-15
 * Time: 上午11:40
 */
use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */  $this->title="答题结果";
?>
<div class="currentRight grid_16 push_2">
<!--<div class="crumbs noticeB"> <a href="#">试卷管理</a> >> <a href="#">试卷预览</a></div>-->

    <div class="testPaperView pr">
        <div class="paperArea">
            <div class="finish">
                <h3>试卷名称试卷名称试卷名称试卷名称试卷名称试卷名称试卷名称</h3>

                <p><span>组卷人：<?php echo loginUser()->getUserInfo($homeworkResult->creator)->getTrueName();?></span><span>时间：<?php echo $homeworkResult->uploadTime?></span></p>
                <hr>
            </div>

            <div class="finish_t" style="position:relative;">
                <h4><?php echo AreaHelper::getAreaName($homeworkResult->provience)."&nbsp". AreaHelper::getAreaName($homeworkResult->city)."&nbsp".AreaHelper::getAreaName($homeworkResult->country)."&nbsp".$homeworkResult->gradename."&nbsp".$homeworkResult->subjectname."&nbsp".$homeworkResult->versionname?> </h4>
                <span class="z">自主测评/非自主测评试卷</span>
                <?php if($homeworkResult->otherHomeworkAnswerID!=""){?>
                    <div class="title">您收到一份<i class="i_name i_name_js"><?php echo $homeworkResult->otherStudentName?></i>的答案， <?php if($homeworkResult->otherIsCheck==0){
                            ?> <a href="<?php echo url('student/managetask/correct-org-paper',array('homeworkAnswerID'=>$homeworkResult->otherHomeworkAnswerID))?>">去批改</a>
                        <?php }else{?>
                        <a href="<?php echo url('student/managetask/view-org-correct',array('homeworkAnswerID'=>$homeworkResult->otherHomeworkAnswerID))?>">查看批改</a><?php } ?>
                    </div>
                <?php }?>
                <ul class="ul_list">
                    <li><span>1、</span>考察知识点：<?php KnowledgePointModel::findKnowledgeStr($homeworkResult->knowledgeId)?></li>
                    <li><span>2、</span>本试卷共包含<?php echo $homeworkResult->questionListSize?>道题目，其中
                        <?php
                          $array=array();
                        foreach($homeworkResult->qeustionTypeNumList as $v){
                                array_push($array,$v->questiontypename.$v->cnum."道");
                        } echo implode($array,",")?>
                       </li>
                    <li><span>4、</span>答题时间控制在 40 分钟内，其中选择题必须在线回答，填空题与应用题提交手写答案</li>

                </ul>
                <span class="fraction" style="font-size: 22px;"><?php echo $homeworkResult->isCheck?"已批改":"未批改"?></span>
                <!--这里显示的是停止答题的时间-->

            </div>
        <div class="paperArea">

            <?php foreach ($homeworkResult->questionList as $key => $item) {
                echo $this->render('//publicView/online/_recombinationItemProblem', array('item' => $item,"homeworkAnswerID"=>$homeworkResult->homeworkAnswerID));
            } ?>
        </div>
    </div>
        <!--翻页-->
            <?php
             echo \frontend\components\CLinkPagerExt::widget( array(
                   'pagination'=>$pages,
                    'maxButtonCount' => 5
                )
            );
            ?>
</div>
    <!--阅卷完成-->
    <div class="popBox modify clearfix" title="批改">
        <h5>您收到一份<a href="javascript:" class="name"><?php echo $homeworkResult->otherStudentName?></a>的答案，请问是否批改？</h5>
    </div>
    <script>
        $('.openAnswerBtn').click(function () {
            $(this).parents('.paper').children('.answerArea').toggle();
        });
        $(function(){
            $('.completeJS').click(function(){
                /*上传试卷*/
            });


            $('.modify').dialog({
                autoOpen: false,
                width:600,
                modal: true,
                resizable:false,
                buttons: [
                    {
                        text: "马上批改",

                        click: function() {
                            window.open("<?php echo url('student/managetask/correct-org-paper',array('homeworkAnswerID'=>$homeworkResult->otherHomeworkAnswerID))?>")

                        }
                    },
                    {
                        text: "取消",

                        click: function() {
                            $( this ).dialog( "close" );
                            var name= $('.name').text();
                            $('.title').show();
                            var name_i =$('.i_name_js').text(name);


                        }
                    }

                ]
            });

            if("<?php echo  $homeworkResult->otherHomeworkAnswerID ?>"!=""&&"<?php echo $homeworkResult->otherIsCheck?>"=="0"){
                $( ".modify" ).dialog( "open" );
            }
            //event.preventDefault();
            return false;




        })

    </script>

    <script>
    $('.openAnswerBtn').click(function () {
        $(this).parents('.paper').children('.answerArea').toggle();
    });
</script>
