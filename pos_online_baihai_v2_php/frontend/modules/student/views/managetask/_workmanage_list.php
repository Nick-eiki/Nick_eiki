<?php
/**
 * Created by unizk.
 * User: ysd
 * Date: 2015/4/19
 * Time: 10:02
 */
use common\helper\DateTimeHelper;
use common\models\pos\SeHomeworkRel;
use frontend\components\helper\PinYinHelper;
use frontend\components\helper\ViewHelper;
use frontend\models\dicmodels\SubjectModel;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<ul class="itemList  sup_ul">
    <?php if($list): /* @var SeHomeworkRel $list */?>
    <?php foreach($list as $val){

            $subjectId='';
            $teacherData=$val->homeWorkTeacher;
            if($teacherData){
                $subjectId=$teacherData->subjectId;
            }


            ?>
    <li class="clearfix sup_li <?=PinYinHelper::firstChineseToPin(SubjectModel::model()->getSubjectName($subjectId)) ?>">
        <div class="item_title noBorder sup_l fl">

            <h4>
	            <a href="<?php echo Url::to(['/classes/managetask/details','classId'=>$classId,'relId'=>$val->id]) ?>" class="details"   relId="<?= $val->id?>" title=" <?= Html::encode($teacherData->name); ?>">
                    <?php
                    if($teacherData->getType == '1'){
	                    echo '<i class="course">[电子]</i>';
                    }elseif($teacherData->getType == '0'){
	                    echo '<i class="course">[纸质]</i>';
                    }?>
                    <?= cut_str(Html::encode($teacherData->name),25); ?>
                </a>
            </h4>
            <dl>
	            <?php if(!empty($teacherData->homeworkDescribe)){?>
                <dd>
                    <em>简介：</em>
                    <span class="synopsis"><?= Html::encode($teacherData->homeworkDescribe);?></span>
                </dd>
	            <?php } ?>
                <dd>
                    <em>交作业截止时间：</em>
                    <span style=" color:#889ba8;"><?= date('Y-m-d',DateTimeHelper::timestampDiv1000($val->deadlineTime));?></span>
                </dd>
                <dd class="clearfix schedule">
                    <span class="progress">已答：未答&nbsp;<sub><b style="width: <?php
                            /** @var \common\models\pos\SeHomeworkRel $val */
                            $answeredNum=$val->homeworkAnswerInfoCountCache();
                            $unAnsweredNum=$studentNum-$answeredNum;
                            if($unAnsweredNum==0){
                                $width=1;
                            }else{
                                $width=$answeredNum/$studentNum;
                            }
                            echo  $width*100
                            ?>%"></b><?= $answeredNum;?>:<?= $unAnsweredNum?></sub></span>

                </dd>
            </dl>
        </div>
        <div class="sup_r  fr">
            <div class="sup_box">
                <div>
                    <?php
                       $isUploadAnswer=$val->getHomeworkAnswerInfo()->where(['studentID'=>user()->id, 'isUploadAnswer' => '1'])->exists();
                    if( !$isUploadAnswer){?>
                        <a href="<?php echo Url::to(['/classes/managetask/details','classId'=>$classId,'relId'=>$val->id]) ?>" class="a_button notice w120 "   relId="<?= $val->id?>">写作业</a>
                    <?php }elseif ($isUploadAnswer){?>
                    <em class="w100">已答</em>
                    <?php }?>
                </div>
            </div>

        </div>
        <b class="course_i"></b>
    </li>
    <?php }
    else:
        ViewHelper::emptyView();
    endif;
    ?>
</ul>

    <?php
    if(isset($pages)){
         echo \frontend\components\CLinkPagerExt::widget( array(
               'pagination'=>$pages,
                'updateId' => '#update',
                'maxButtonCount' => 5,
                'showjump'=>true
            )
        );
    }

    ?>
