<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/13
 * Time: 14:36
 */
use common\models\sanhai\ShTestquestion;
use frontend\components\BaseController;
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\EditionModel;
use yii\helpers\Html;

/** @var $this yii\web\View */
$this->title = '作业答题';
$this->blocks['requireModule']='app/classes/stu_hmwk_do_homework';
?>
<div class="main col1200 clearfix stu_do_homwork" id="requireModule" rel="app/classes/stu_hmwk_do_homework">
    <div class="container homework_title">
        <a href="javascript:history.back(-1);" class="btn bg_gray icoBtn_back return_btn"><i></i>返回</a>
        <h4 title="<?= Html::encode($homeworkData->name) ?>"><?= Html::encode($homeworkData->name) ?></h4>
    </div>
    <div class="container homwork_info">
        <div class="pd25">
            <?php if (!empty($homeworkData->version)) { ?><p>
                <em>版本：</em><?= EditionModel::model()->getEditionName($homeworkData->version); ?></p><?php } ?>
            <?php if (!empty($homeworkData->chapterId)) { ?><p>
                <em>章节：</em><?php echo ChapterInfoModel::findChapterStr($homeworkData->chapterId); ?></p><?php } ?>
            <?php if (isset($homeworkData->difficulty) && $homeworkData->difficulty >= 0) { ?><p><em>难度：</em><b
                    class="<?php if ($homeworkData->difficulty == 1) {
                        echo 'mid';
                    } elseif ($homeworkData->difficulty == 2) {
                        echo 'hard';
                    } ?>"></b></p><?php } ?>
            <?php if (!empty($homeworkData->homeworkDescribe)) { ?><p>
                <em>简介：</em><?= Html::encode($homeworkData->homeworkDescribe); ?></p><?php } ?>
            <?php echo $this->render("//publicView/classes/_teacher_homework_rel_audio",[ 'homeworkRelAudio' => $homeworkRelAudio]); ?>
        </div>
    </div>
<form id="form-homework"
      action="<?= \yii\helpers\Url::to(['finish-answer','relId'=>app()->request->get('relId')])?>">

    <!-- 答题卡-->
    <div id="answer_card" class="container answer_card">
        <div class="answer_card_border">
            <h4 class="cont_title"><i class="t_ico_answer_card"></i>作业答题卡</h4>
            <a id="open_cardBtn" href="javascript:;" class="open_cardBtn">展开<i></i></a>

            <div class="answer_card_cont">
                <div class="pd25">
                    <div class="answer_ele">
                        <div class="sUI_pannel sub_title">
                            <div class="pannel_l"> 客观题</div>
                            <div class="pannel_r"><span><i class="done"></i>已答</span><span><i></i>未答</span><span><i
                                        class="uncheck"></i>未批</span><span><i class="wrong"></i>答错</span><span><i
                                        class="correct"></i>答对</span><span><i class="half"></i>半对</span></div>
                        </div>
                        <div id="ele_list" class="ele_list">
                            <?php
                            if(empty($objective)){
                                echo '该作业无客观题';
                            }else{
                                foreach($objective as $obj){
                                    echo '<em id="'.$obj.'_clip">'.$homeworkData->getQuestionNo($obj).'</em>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="answer_paper">
                        <div class="sUI_pannel sub_title">
                            <div class="pannel_l"> 主观题<span><?php if(!empty($subjective)){?>未批改完，请在批改完毕后查看<?php }?></span></div>
                        </div>
                        <div id="paper_list" class="paper_list">
                            <?php
                            if(empty($subjective)){
                                echo '该作业无主观题';
                            }else{
                                foreach($subjective as $sub){
                                    echo '<em id="5_clip" class="clip_img">'.$homeworkData->getQuestionNo($sub).'</em>';
                                }
                            ?>
                        </div>
                        <div class="upImgFile">
                            <ul class="clearfix picList">
                                <li class="uploadFile"><a href="javascript:;" class="uploadFileBtn">
                                        <i></i>
                                        还可以添加<span>20</span>张图片
                                        <?php
                                        $t1 = new frontend\widgets\xupload\models\XUploadForm;
                                        /** @var $this BaseController */
                                        echo  \frontend\widgets\xupload\XUploadRequire::widget( array(
                                            'url' => Yii::$app->urlManager->createUrl("upload/pic"),
                                            'model' => $t1,
                                            'attribute' => 'file',
                                            'autoUpload' => true,
                                            'multiple' => false,
                                            'options' => array(
                                                'acceptFileTypes' => new \yii\web\JsExpression('/(\.|\/)(jpg|png|jpeg)$/i'),
                                                'maxFileSize' => 4*1024*1024,
                                                "done" => new \yii\web\JsExpression('done'),
                                                "processfail" => new \yii\web\JsExpression('function (e, data) {var index = data.index,file = data.files[index]; if (file.error) {alert(file.error);}}')
                                            ),
                                            'htmlOptions' => array(
                                                'id' => 'fileupload',
                                            )
                                        ));
                                        ?>
                                    </a></li>
                            </ul>
                            <?php }?>
                            <?php if(!empty($objective) || !empty($subjective)){?>
                            <div class="popBtnArea tc">
                                <button type="button" class="btn40 bg_blue okBtn" style="width: 120px" id="finishHomework">交作业</button>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- 作业区-->
    <div class="container no_bg testpaperArea">
        <div class="testPaper">
            <?php
            foreach ($homeworkQuestion as $key => $item) {
                $questionInfo = ShTestquestion::findOne($item->questionId);
                if (empty($questionInfo)) continue;
                    echo $this->render('//publicView/new_homeworkAnswer/_item_answer_type',
                        array('item' => $questionInfo, 'number' => $key + 1, 'isAnswered' => $isAnswered,'homeworkData'=>$homeworkData));
            }
            ?>
        </div>
    </div>
</form>

</div>


<script>
    done = function(e, data) {
        $.each(data.result, function (index, file) {
            if(file.error){
                require(['popBox'],function(popBox){
                    popBox.errorBox(file.error);
                });
                return ;
            }
            var liSize=$('.upImgFile').find('li').size();
            if(liSize>=21){
                require(['popBox'],function(popBox){
                    popBox.errorBox('最多传20张图片');
                });
                return false;
            }
            $('<li><input type="hidden" name="picurls[]" value="' + file.url + '" /><img src="'+file.url+'" alt=""><span class="delBtn"></span></li>').insertBefore('.uploadFile');

        });
        require(['app/classes/classes_memorabilia_modify'],function(classes_modify){
            classes_modify.leftPicCal();
        });

    };


</script>