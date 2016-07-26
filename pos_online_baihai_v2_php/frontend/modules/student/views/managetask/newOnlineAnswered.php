
<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/4/18
 * Time: 14:19
 */
use common\models\sanhai\ShTestquestion;
use frontend\components\helper\LetterHelper;

$this->registerCssFile(publicResources_new() . '/js/fancyBox/jquery.fancybox.css' . RESOURCES_VER);
$this->registerJsFile(publicResources_new() . "/js/fancyBox/jquery.fancybox.js" . RESOURCES_VER, ['position' => \yii\web\View::POS_HEAD]);
/* @var $this yii\web\View */  $this->title="答题结果";
?>
<script>
    $(function(){


        $(".fancybox").fancybox();
        $("img.lazy").lazyload({
            effect: "fadeIn"
        });

        $('.openAnswerBtn').unbind('click').click(function(){
            $(this).children('i').toggleClass('close');
            $(this).parents('.paper').find('.answerArea').toggle();
        });
    })
</script>

<!--主体-->
<div class="grid_19 main_r">
    <div class="main_cont online_answer">
        <div class="title">
            <a href="javascript:;" class="txtBtn backBtn" onclick="window.history.go(-1);"></a>
            <h4>答题结果</h4>
            <div class="title_r"><span>交作业期限：<?php echo date("Y-m-d", \common\helper\DateTimeHelper::timestampDiv1000($deadlineTime)) ?></span></div>
        </div>
        <div class="work_detais_cent" style="position:relative;">
            <h4><?php echo $homeworkResult->name; ?></h4>
<!--            <span class="z"  style="top:50px;">--><?php //echo AreaHelper::getAreaName($homeworkResult->provience) . '&nbsp;&nbsp' . AreaHelper::getAreaName($homeworkResult->city) . '&nbsp;&nbsp' . AreaHelper::getAreaName($homeworkResult->country) . '&nbsp;&nbsp' .
//                    GradeModel::model()->getGradeName($homeworkResult->gradeId) . '&nbsp;&nbsp' .
//                    \frontend\models\dicmodels\SubjectModel::model()->getSubjectName($homeworkResult->subjectId) . '&nbsp;&nbsp' .
//                    \frontend\models\dicmodels\EditionModel::model()->getEditionName($homeworkResult->version) ?><!--</span>-->

            <div class="testPaperView">
                <div class="paperArea">
                    <?php
                    foreach ($homeworkQuestion as $key => $item) {
                        $questionInfo = \common\helper\QuestionInfoHelper::Info($item->questionId);
                        if(!empty($questionInfo)){
                            $showType = \common\helper\QuestionInfoHelper::getQuestionShowtype($questionInfo->tqtid);
                            $questionInfo->showType = $showType;
                        }
                        echo $this->render('//publicView/homeworkAnswer/_new_item_answer_type', array('item' => $questionInfo, 'number' => $key + 1, 'isAnswered'=>$isAnswered,'homeworkResult'=>$homeworkResult));


                    }
                    ?>
                </div>
                <div class="upLoad_answerBar">
                    <h5>答题区</h5>
                    <div class="digitalFile">
                        <?php if(!empty($objective)){?>
                            <h6>客观题</h6>

                            <p><?php
                                foreach($objective as $key=>$obj){
                                    $answerOption = '';
                                    if(empty($obj->answerOption) && $obj->answerOption!=='0' ){$answerOption =  '未答';}else{$answerOption =  LetterHelper::getLetter($obj->answerOption);}
                                    $result=ShTestquestion::find()->where(['id'=>$obj->questionID])->one()->getQuestionShowType()==9?LetterHelper::rightOrWrong($obj->answerOption):$answerOption;
                                    ?>
                                    <span class="correct"><?=$key.'.'.$result?><i class="<?php if($obj->correctResult == 1){echo 'wrong';}elseif($obj->correctResult == 3){echo 'right';}else{echo '';}?>"></i></span>
                                <?php      }
                                ?>

                            </p>
                        <?php } if(!empty($subjective)){?>

                            <h6>主观题</h6>
                            <p><?php
                                foreach($subjective as $key=>$sub){
                                    $correctResult = '';
                                    if($sub->correctResult==0){
                                        echo '<span class="correct">'.$key.'&nbsp未批改</span>,';
                                    }else {
                                        if ($sub->correctResult == 1) {
                                            $correctResult = 'wrong';
                                        }
                                        if ($sub->correctResult == 2) {
                                            $correctResult = 'halfWrong';
                                        }

                                        if ($sub->correctResult == 3) {
                                            $correctResult = 'right';
                                        }
                                        echo '<span class="correct">'.$key.'<i class="'.$correctResult.'"></i></span>,';
                                    }

                                }
                                ?></p>
                        <?php }?>
                        <br>

                        <ul class="up_test_list clearfix up_img">
                            <?php
                            $images =$picAnswer;
                            if(isset($images) && !empty($images)){
                                foreach($images as $val){
                                    ?>
                                    <li>
                                        <a class="fancybox" href="<?=$val->url; ?>" data-fancybox-group="gallery_133813">
                                            <img src="<?=$val->url; ?>" data-original="<?=$val->url; ?>"alt="" style="display: inline;" class="lazy"></a>
                                    </li>
                                <?php  }}?>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!--主体end-->
