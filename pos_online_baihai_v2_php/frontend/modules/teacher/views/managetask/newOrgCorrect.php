<?php
/**
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2015/11/19
 * Time: 13:34
 */
use common\models\pos\SeHomeworkAnswerInfo;
use common\models\pos\SeHomeworkAnswerQuestionAll;
use frontend\components\WebDataCache;
use yii\helpers\Url;

$this->title = '批改作业';
?>
<!--主体-->
<div class="cont24">
    <div class="grid24 main">
        <div class="grid_24 main_r">
            <div class="main_cont">
                <div class="title">
                        <a class="txtBtn backBtn" href="<?=url::to(['/teacher/managetask/work-details','classhworkid'=>$oneAnswerResult->relId])?>"></a>
                    <h4>作业批改</h4>
                </div>
                <div class="correct_workcon">
                    <div class="tab">
                        <ul class="tabList clearfix">
                            <!--<li class="tabListShow"><a href="teacher_correcting_people.html" class="ac">按人批改</a></li>-->
                            <!--                            <li class="tabListShow"><a href="teacher_correcting_questions.html">按题批改</a></li>-->
                        </ul>
                        <!--按人批改-->
                        <div class="tabCont correctcon" style="border: none;padding:0;">
                            <div class="tabItem">
                                <div id="userList" class="left_correct">
                                    <?php
                                    /* @var  SeHomeworkAnswerInfo[] $homeworkAnswerResult */
                                    foreach ($homeworkAnswerResult as $k => $v) {
                                        $answerAllQuery = SeHomeworkAnswerQuestionAll::find()->where(['homeworkAnswerID' => $v->homeworkAnswerID])->andWhere(['>','correctResult',0]);
                                           $subjectArray=[];
                                         foreach($answerAllQuery->all() as $v1){
                                             $questionID=$v1->questionID;
                                             if(\common\helper\QuestionInfoHelper::Info($questionID)->isMajorQuestionCache()){
                                                 array_push($subjectArray,$questionID);
                                             }
                                         }
                                        ?>
                                        <dl class="<?= $v->homeworkAnswerID == $homeworkAnswerID ? 'cur' : '' ?>"
                                            homeworkAnswerID="<?= $v->homeworkAnswerID ?>"
                                            studentID="<?= $v->studentID ?>">
                                            <a href="<?= url::to(['new-org-correct', 'homeworkAnswerID'=>$v->homeworkAnswerID]) ?>">
                                                <dt><img onerror="userDefImg(this);" width='50px' height='50px'
                                                         src="<?= WebDataCache::getFaceIcon($v->studentID) ?>"></dt>
                                                <dd>

                                                    <p><?= WebDataCache::getTrueName($v->studentID) ?></p>
                                                    <em class="approved">已批:<span><?= count($subjectArray) ?></span>/<b><?= count($questionArray) ?></b></em>
                                            </a>
                                            </dd>
                                        </dl>

                                    <?php } ?>
                                </div>
                                <div class="right_correct">
                                    <div class="cor_questions">
                                        <ul id="q_list">
                                            <?php foreach ($questionArray as $k => $v) {
                                                $answerAllResult = SeHomeworkAnswerQuestionAll::find()->where(['homeworkAnswerID' => $oneAnswerResult->homeworkAnswerID, 'questionID' => $v])->one();
                                                if ($answerAllResult != null) {
                                                    $correctResult = $answerAllResult->correctResult;
                                                } else {
                                                    $correctResult = 0;
                                                }
                                                switch ($correctResult) {
                                                    case 0:
                                                        $pic = '';
                                                        break;
                                                    case 1:
                                                        $pic = 'wrong_btn';
                                                        break;
                                                    case 2:
                                                        $pic = 'half_btn';
                                                        break;
                                                    case 3:
                                                        $pic = 'check_btn';
                                                        break;
                                                    default:
                                                        $pic='';
                                                }
                                                if ($k == 0) {
                                                    ?>
                                                    <li class="act" questionID="<?= $v ?>">题<?= $homeworkResult->getQuestionNo($v) ?><i
                                                            class="<?= $pic ?>"></i></li>
                                                <?php } else { ?>
                                                    <li questionID="<?= $v ?>">题<?= $homeworkResult->getQuestionNo($v) ?><i class="<?= $pic ?>"></i>
                                                    </li>
                                                <?php }
                                            } ?>

                                        </ul>
                                    </div>
                                    <div class="work_btnmark">
                                        <div class="correctPaper">
                                            <div id="correctPaperSlide1" class="correctPaperSlide">
                                                <?php if(!empty($answerImageArray)){?>
                                                <div class="testPaperWrap mc pr">

                                                    <ul class="testPaperSlideList slid">
                                                        <?php foreach ($answerImageArray as $v) { ?>
                                                            <li><img src="<?= $v ?>" alt=""/></li>
                                                        <?php } ?>
                                                    </ul>
                                                    <a href="javascript:;" id="prevBtn" class="correctPaperPrev">上一页</a>
                                                    <a href="javascript:;" id="nextBtn" class="correctPaperNext">下一页</a>

                                                </div>
                                                <?php }else{?>
                                                    <div class="empty">
                                                    <img src="/pub/images/unAnswered.png">
                                                    </div>
                                                <?php }?>

                                            </div>
                                        </div>
                                        <div id="slidClip1" class="slidClip"></div>
                                        <div class="original">
                                            <span class="btn_txt">原题</span>

                                            <div class="exhibition ">

                                            </div>
                                        </div>
                                        <div class="cor_btn">
                                            <a href="javascript:;" class="check"></a>
                                            <a href="javascript:;" class="half"></a>
                                            <a href="javascript:;" class="wrong"></a>
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
<script>
    $(function () {
        //判卷
        imgArr =<?=json_encode($answerImageArray)?>;
        $('#correctPaperSlide1').testpaperSlider({ClipArr: imgArr, slidClip: "#slidClip1"});
        //左侧点击状态
        $(".left_correct dl").click(function () {
            $(this).addClass("cur").siblings().removeClass("cur");
            $(".original").removeClass("show_original");
        });
        //右侧点击状态
        $("#q_list li").click(function () {
            $(this).addClass("act").siblings().removeClass("act");
            $(".original").removeClass("show_original");
        });
        (function () {
            var q_lis = $("#q_list li");
            var ul_w = 0;
            q_lis.each(function (index, element) {
                ul_w += $(this).outerWidth(true);
            });
            $("#q_list").width(ul_w + 20);
            //批改状态

            $('.cor_btn a').click(function () {
                var num = $("#q_list li").size();
                var index = 0;
                var cls_name = $(this).attr('class');

                function chk(cls_name) {
                    index = $("#q_list .act").index() + 1;
                    $("#q_list .act i").removeClass().addClass(cls_name + "_btn");
                    $("#q_list .act").removeClass('act').next().addClass('act');

                }
                var homeworkAnswerID = $(".cur").attr("homeworkAnswerID");
                var questionID = $(".act").attr('questionID');
                if(questionID!=null) {
                    var num=$("#q_list li").size();
                    var index=0;
                    var cls_name=$(this).attr('class');
                    function chk(cls_name){
                        index=$("#q_list .act").index()+1;
                        $("#q_list .act i").removeClass().addClass(cls_name+"_btn");
                        $("#q_list .act").removeClass('act').next().addClass('act');

                    }
                    function update(homeworkAnswerID,correctResult,questionID){
                        $.post("<?=url::to('/teacher/managetask/new-ajax-correct')?>", {
                            homeworkAnswerID: homeworkAnswerID,
                            correctResult: correctResult,
                            questionID: questionID
                        }, function (result) {
                            if (result.success) {
                               switch (correctResult){
                                   case 1:
                                       chk("wrong");
                                       break;
                                   case 2:
                                       chk("half");
                                       break;
                                   case 3:
                                       chk("check");
                                       break;
                               }
                                var topic_num=$("#q_list i[class$=btn]").size();
                                $('#userList .cur span').text(topic_num);

                                if(topic_num==num) popBox.alertBox('是否确定全部题目已批改完成？',
                                    function(){
                                        $.post('<?=url::to(["/teacher/managetask/update-hom-correct-level"])?>',{homeworkAnswerID:homeworkAnswerID},function(result){
                                            if(result.success==false){
                                                popBox.errorBox('更新失败');
                                            }
                                        })
                                    },
                                    function(){}
                                );
                            }
                        });
                    }
                    switch (cls_name) {
                        case "check":
                            correctResult = 3;
                            update(homeworkAnswerID,correctResult,questionID);
                            break;
                        case "half":
                            correctResult = 2;
                            update(homeworkAnswerID,correctResult,questionID);
                            break;
                        case "wrong":
                            correctResult = 1;
                            update(homeworkAnswerID,correctResult,questionID);
                            break;
                    }




                }else{
                    popBox.errorBox("请选择题目");
                }
//                var topic_num = $("#q_list i[class$=btn]").size();
//
//                $('#userList .cur span').text(topic_num);
//                if (index == num) popBox.errorBox('已全部批改完成!');
            });
        })();
        //原题弹窗
        $(".original").click(function () {
            var questionID = $('.act').attr('questionID');
            if(questionID!=null){
            if ($(this).hasClass('show_original')) {
               // $(".exhibition").toggle();
            } else {
                $.post('<?=url::to("get-question-content")?>', {questionID: questionID}, function (result) {
                    $(".exhibition").html(result);
                });
            }
            $(this).toggleClass("show_original");
            }else{
                popBox.errorBox('请选择题目');
            }
        })



    })


</script>
