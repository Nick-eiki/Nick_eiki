<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2015/4/17
 * Time: 20:34
 */
use common\models\sanhai\ShTestquestion;
use frontend\components\WebDataKey;

/* @var $this yii\web\View */  $this->title="在线答题";

$this->registerCssFile(publicResources_new() . '/js/fancyBox/jquery.fancybox.css'.RESOURCES_VER);
$this->registerJsFile(publicResources_new() . "/js/fancyBox/jquery.fancybox.js".RESOURCES_VER,[ 'position'=>\yii\web\View::POS_HEAD]);

?>

<script type="text/javascript">
    $(function(){
        $('.btn_js').click(function () {

            /*上传试卷*/
            $('#dati').dialog({
                autoOpen: false,
                width: 600,
                modal: true,
                resizable: false,
                buttons: [
                    {
                        text: "确定",

                        click: function () {

                            $form = $("#form-homework");
                            $.post($form.attr('action'), $form.serialize(),function(data){
                                if(data.success){
                                    popBox.successBox(data.message);
                                    location.href = '<?= \yii\helpers\Url::to(['details','relId'=>app()->request->get('relId')])?>';
                                }
                            });

                        }
                    },
                    {
                        text: "取消",

                        click: function () {
                            $(this).dialog("close");
                        }
                    }

                ]
            });
            $("#dati").dialog("open");
            return false;
        });


    })


</script>

<!--主体-->
<div class="grid_19 main_r">
    <div class="main_cont online_answer">
        <div class="title">
            <a href="javascript:;" class="txtBtn backBtn" onclick="window.history.go(-1);"></a>
            <h4>在线答题</h4>
            <div class="title_r"><span>交作业期限：<?php echo date("Y-m-d", \common\helper\DateTimeHelper::timestampDiv1000($deadlineTime)) ?></span></div>
        </div>
        <div class="work_detais_cent" style="position:relative;">
            <h4><?php echo $homeworkResult->name; ?></h4>
<!--            <span class="z" style="top:50px;">-->
<!--	            --><?php //echo AreaHelper::getAreaName($homeworkResult->provience) . '&nbsp;&nbsp' . AreaHelper::getAreaName($homeworkResult->city) . '&nbsp;&nbsp' . AreaHelper::getAreaName($homeworkResult->country) . '&nbsp;&nbsp' .
//                    GradeModel::model()->getGradeName($homeworkResult->gradeId) . '&nbsp;&nbsp' .
//                    \frontend\models\dicmodels\SubjectModel::model()->getSubjectName($homeworkResult->subjectId) . '&nbsp;&nbsp' .
//                    \frontend\models\dicmodels\EditionModel::model()->getEditionName($homeworkResult->version) ?><!--</span>-->

<form id="form-homework" action="<?= \yii\helpers\Url::to(['finish-answer','relId'=>app()->request->get('relId')])?>">
            <div class="testPaperView">
                <div class="paperArea">
                    <?php

                            foreach ($homeworkQuestion as $key => $item) {
                                $questionInfo = ShTestquestion::findOne($item->questionId);
                                if (empty($questionInfo)) continue;
                    if ($this->beginCache(WebDataKey::WEB_STUDENT_ANSWERING_QUESTION_LIST_KEY . $item->questionId, ['duration' => 3600])) {
                        echo $this->render('//publicView/homeworkAnswer/_new_item_answer_type', array('item' => $questionInfo, 'number' => $key + 1, 'isAnswered' => $isAnswered, 'homeworkResult' => $homeworkResult));
                        $this->endCache();
                    }
                            }
                    ?>
                </div>
                <?php ?>
                <div class="upLoad_answerBar">

                    <h5>答题区</h5>
                    <?php if(!empty($objective)){?>
                    <h6>客观题</h6>

                    <p id="keguan_answer"><?php
                        foreach($objective as $obj){
                            echo '<span>'.$homeworkResult->getQuestionNo($obj).',</span>';
                        }
                        ?></p>
                    <?php }?>
                    <?php if(!empty($subjective)){?>
                    <h6>主观题</h6>
                    <p><?php
                        foreach($subjective as $sub){

                            echo '<span>'.$homeworkResult->getQuestionNo($sub).',</span>';
                        }
                        ?></p>
                    <?php }?>
                    <br>
                    <?php if(!empty($subjective)){?>
                    <div class="imgFile">
                        <ul class="up_test_list clearfix up_img">
                            <li class="more">
                                <?php
                                $t1 = new frontend\widgets\xupload\models\XUploadForm;
                                /** @var $this BaseController */
                                echo  \frontend\widgets\xupload\XUploadSimple::widget( array(
                                    'url' => Yii::$app->urlManager->createUrl("upload/pic"),
                                    'model' => $t1,
                                    'attribute' => 'file',
                                    'autoUpload' => true,
                                    'multiple' => true,
                                    'options' => array(
                                        'acceptFileTypes' => new \yii\web\JsExpression('/(\.|\/)(jpg|png|jpeg)$/i'),
                                        'maxFileSize' => 2*1024*1024,
                                        "done" => new \yii\web\JsExpression('done'),
                                        "processfail" => new \yii\web\JsExpression('function (e, data) {var index = data.index,file = data.files[index]; if (file.error) {alert(file.error);}}')
                                    ),
                                    'htmlOptions' => array(
                                        'id' => 'fileupload',
                                    )
                                ));
                                ?>
                            </li>
                            <input class="paperRoute" name="content" type="hidden"/>
                        </ul>
                        <button type="button" class="bg_blue w120 btn40 modifyBtn hide">上传答案</button>
                        <button type="button" class="bg_blue w120 btn40 finishBtn hide">上传完毕</button>
                    </div>
                 <?php }?>
                </div>
                <div class="btnD">
                    <button class=" bg_blue btn btn_js w120" id="finish">完成答题</button>
                </div>
            </div>
</form>
        </div>
    </div>
</div>

<!--主体end-->

<!--答题弹窗-->
<!--注意---后台自己去判断是否答题完毕在显示相应的的提示-->
<div id="dati" class="popBox dati hide" title="完成答题">
	<!--完成答题-->
	<div class="impBox">
		<p style="margin-top:20px; text-align:center; margin-bottom:30px;">您确定提交作业吗？</p>

		<p class="hide">您的答题情况：本试卷共计 <i class="b">5</i> 道小题，您共完成 <i class="r">2</i> 道题</p>
	</div>
</div>


<script>
    $(function(){
        //图片可拖动
        $('.imgFile .up_test_list').sortable();
    })
</script>
<script>
    k=0;
    done = function(e, data) {

        $.each(data.result, function (index, file) {
            k++;
            if(file.error){
                popBox.errorBox(file.error);
                return ;
            }
            var url = $('.paperRoute').val();
            if (url.length == 0) {
                $('.paperRoute').val(file.url);
            } else {
                $('.paperRoute').val(url + ',' + file.url);
            }
            $('<li><input type="hidden" name="picurls[]" value="' + file.url + '" /> <img src="' + file.url + '" alt=""><span class="delBtn"></span></li>').insertBefore('.more');

        });
    };
    var topicArr=[];
    $('.paper').each(function(index, element) {
        var _this=$(this);
        _this.attr('data-index',index);
        var isSub=_this.find('.paper').size();
        var zhuguan=_this.find('.checkArea').size();
        _this.addClass('paper_topic');
        topicArr[index]="空";
        if(zhuguan==0){//是否为主观题
            _this.addClass('zhuguan');
            topicArr[index]="zhuguan";
        }
        else if(isSub){//是否有小题
            _this.removeClass('paper_topic');
            topicArr[index]="hasSub";
        }
        else if(isSub || !zhuguan){
            return true;// 如果有小题,本题跳过,直接排列小题
        }
    });
    $('.paper_topic').each(function(index, element) {
        $(this).attr('data-index',index);
    });

    function setOnwser(){
        var html='';
        for(var i=0; i<topicArr.length; i++){
            if(topicArr[i]=='hasSub') topicArr.splice(i, 1);
            if(topicArr[i]=='zhuguan'){

            }
            else{
                html+='<span>题'+(i+1)+': '+topicArr[i]+'</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            }
        }
        $('#keguan_answer').empty().html(html);
    }
    setOnwser();
    $('.paper input').click(function(){
        var pa=$(this).parents('.paper_topic');
        var topicIndex=pa.attr('data-index');
        var input_a=[];
        pa.find('input').each(function(index, element) {
            $(this).is(':checked') && input_a.push($(this).next().text());
        });
        topicArr[topicIndex]=input_a;
        setOnwser();
    })
</script>
