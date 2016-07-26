<?php
/**
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2015/9/18
 * Time: 10:10
 */
use frontend\components\WebDataCache;
use yii\helpers\Url;

$this->title='作业详情';
$publicResources = Yii::$app->request->baseUrl;
$this->registerJsFile($publicResources . '/pub/js/My97DatePicker/WdatePicker.js');
?>
<!--top_end-->
<!--主体-->
<div class="cont24">
    <div class="grid24 main">
        <div class="grid_19 main_r main_datels">
            <div class="main_cont online_answer testPaperView">
                <div class="title"> <a href="<?=url::to('library-list')?>" class="txtBtn backBtn"></a>
                    <h4> 作业预览</h4>
                    <div class="title_r">
<!--                         <a href="teacher_organization_work.html" class="w120 btn40 bg_blue a_button">组织作业</a>-->
                        <?php if($homeworkIsExist){?>
                            <button type="button"  class="btn40 bg_blue disableBtn isAssigned">放入作业</button>
                        <?php }else{?>
                            <button type="button" id="upbtnBox" class="btn40 bg_blue">放入作业</button>
                        <?php }?>
                        <button type="button" id="upbtn" class="btn40 bg_blue">布置给学生</button>
                    </div>
                </div>
                <h4><?php echo $homeworkData->name;?></h4>
                <div class="organ_class clearfix"><span class="fr"><?php echo WebDataCache::getGradeName($homeworkData->gradeId)."&nbsp".\frontend\models\dicmodels\SubjectModel::model()->getSubjectName($homeworkData->subjectId)."&nbsp".\frontend\models\dicmodels\EditionModel::model()->getEditionName($homeworkData->version)?> </span></div>
                <?php foreach($homeworkResult as $item){?>

                    <div class="paper">
                        <?php echo $this->render('//publicView/libraryTask/_itemPreviewType', array('item' => $item,'homeworkData'=>$homeworkData)); ?>
                        <div class="btnArea clearfix"><span class="openAnswerBtn fl">查看答案与解析<i class="open"></i></span> <span
                                class="r_btnArea fr">难度:<em><?php  WebDataCache::getDictionaryName($item->complexity)?></em>&nbsp;&nbsp;&nbsp;录入:平台</span>
                        </div>
                        <div class="answerArea hide">
                            <p><em>答案:</em>
                                <span><?php echo $this->render('//publicView/libraryTask/_itemProblemAnswer', array('item' => $item)); ?></span>
                            </p>
                            <?php if(WebDataCache::getShowTypeID($item->tqtid)!= 8){?>
                                <p><em>解析:</em>
                                    <?php echo $item->analytical; ?>
                                </p>
                            <?php } ?>
                        </div>
                    </div>
                    <hr>
                <?php }?>
            </div>

        </div>




    </div>
</div>
</div>
<!--主体end-->
<!--布置给学生弹出层-->
<div id="popBox1" class="popBox popBox_hand hide" title="选择班级">

</div>
<!--提交作业弹出层-->
<div id="popBox2" class="popBox popBox_hand hide" title="系统提示">
    <div class="popCont">
        <div class="">
            <form>
                <div class="form_list">
                    <div class="row work_row clearfix">
                        <div class="formL formL_face work_face">
                            <label class="face_pic"><img src="<?=publicResources_new()?>/images/face_pic.png" alt=""></label>
                        </div>
                        <div class="formR formR_text">
                            已成功加入到您的作业中，<br>
                            您可以在您的作业列表中查看该作业，布置给您的学生。
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    //初始化弹窗
    $(function() {
        $('#popBox1').dialog({
            autoOpen: false,
            width: 720,
            modal: true,
            resizable: false

        });
        $('#popBox2').dialog({
            autoOpen: false,
            width: 720,
            modal: true,
            resizable: false,
            buttons: [{
                text: "确定",
                click: function() {
                    $(this).dialog("close");
                }
            }

            ]
        });
        function placeholder(obj, defText) {
            obj.val(defText)
                .css("color", "#ccc")
                .focus(function() {
                    if ($(this).val() == defText) {
                        $(this).val("").css("color", "#333");
                    }
                }).blur(function() {
                    if ($(this).val() == "") {
                        $(this).val(defText).css("color", "#ccc");
                    }
                });
        }
        /*布置给学生弹窗*/
        $('#upbtn').click(function() {
            var homeworkIsExist="<?=$homeworkIsExist?>";
            var homeworkId="<?=app()->request->getQueryParam('homeworkID')?>";
            $.post('<?=url("/teacher/managetask/is-exist")?>',{homeworkID:homeworkId},function(result){
                if(result.success){
                    var type=1;
                    $.post('<?=url('/teacher/managetask/get-class-box');?>',{homeworkid:homeworkId,type:1},function(data){
                        $('#popBox1').html(data);
                        $( "#popBox1" ).dialog( "open" );
                    });
                }else{
                    popBox.confirmBox("你还没有放置作业，是否现在放置？",function(){

                        var url='<?=url("teacher/managetask/library-join-teacher")?>';
                        $.post(url,{homeworkID:homeworkId},function(result){
                            $.post('<?=url('/teacher/managetask/get-class-box');?>',{homeworkid:homeworkId,type:1},function(data){
                                $('#popBox1').html(data);
                                $( "#popBox1" ).dialog( "open" );
                            });
                        })
                    })
                }
            });


//            $("#popBox1").dialog("open");
        });
        /*放入作业弹窗*/
        $('#upbtnBox').click(function() {
            var homeworkID="<?=app()->request->getQueryParam('homeworkID')?>";
            var url='<?=url("teacher/managetask/library-join-teacher")?>';
            $.post(url,{homeworkID:homeworkID},function(result){
                if(result.success){
                    $("#popBox2").dialog("open");
                }else{
                    popBox.errorBox(result.message);
                }
            })

        });
    });
    $(function(){
        $('.textareaBox').speak('请输入您的反馈意见',300);
        $(".myclass_table dt").click(function(){
            $(this).parent().toggleClass("ac");
        })
    });
    //查看答案与解析
    $('.openAnswerBtn.fl').click(function(){
        $(this).children('i').toggleClass('close');
        $(this).parents('.paper').find('.answerArea').toggle();
    });
    $(".isAssigned").click(function(){
        popBox.errorBox('当前作业已经被加入了');
    })
</script>