<?php
/**
 * Created by PhpStorm.
 * User: liquan
 * Date: 2014/11/19
 * Time: 13:43
 */

//49  209	题型显示	1	单选题	1
//50	209	题型显示	2	多选题	1
//51	209	题型显示	3	填空题	1
//52	209	题型显示	4	问答题	1
//53	209	题型显示	5	应用题	1
//96	209	题型显示	7	阅读理解	1
//95	209	题型显示	6	完形填空	1
use frontend\components\helper\LetterHelper;
use frontend\widgets\xupload\XUploadSimple;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
$this->title="重答";
$this->registerJsFile(publicResources() . '/js/My97DatePicker/WdatePicker.js'.RESOURCES_VER, [ 'position'=>\yii\web\View::POS_HEAD] );

$backend_asset = publicResources();
;
$this->registerJsFile($backend_asset . 'js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);
$this->registerCssFile(publicResources_new() . '/js/fancyBox/jquery.fancybox.css'.RESOURCES_VER);
$this->registerJsFile(publicResources_new() . '/js/fancyBox/jquery.fancybox.js'.RESOURCES_VER,[ 'position'=>\yii\web\View::POS_HEAD] );
?>
<script>
    $(function(){
	    $(".fancybox").fancybox();
        $('.finishBtn').click(function(){
            if($("#t_type").val() == 1){
                var answer = $(":radio[name='answer']:checked").val();
                if(!answer) {
                    alert("请选择答案"); return false;
                }
            }else if($("#t_type").val() == 2){
                var answer="";
                $(".checkbox").each(function() {
                    if ($(this).attr("checked")) {
                        answer += $(this).val() + ",";
                    }
                });
                var answer = answer.substring(0,answer.length-1);
                if(!answer) {
                    alert("请选择答案"); return false;
                }
            }else{
                var status = 1;
                $(".paper").children('li').each(function(index,el){
                    var child_type = $(el).find(".xiao_type").val();
                    if(child_type == 1){
                        var answer = $(el).find(":radio[name^='ImgUrl']:checked").val();
                        if(!answer) status = 0;
                    }else if(child_type == 2){
                        var answer="";
                        $(el).find(".xiao_checkbox:checked").each(function() {
                                answer += $(this).val() + ",";
                        });
                        var answer = answer.substring(0,answer.length-1);
                        if(!answer)  status = 0;
                    }else{
                        $(".addImage").each(function(index2,el2) {
                            if($(el2).find('li').length == 0){
                                status = 0;
                            }
                        });
                    }
                });
                if(status == 0){
                    alert("请上传答案");
                    return false;
                }
            }
        })
    })
</script>
<div class="grid_19 main_r">
	<div class="main_cont mistake_detail">
        <div class="noticeH clearfix noticeB">
            <h3 class="h3L">重答</h3>
        </div>
        <hr>
        <?php $form =ActiveForm::begin( [
            'enableClientScript' => false,
            'id' => 'form_id',
        ])?>
        <div class="testPaperView pr">
            <div class="paperArea">
                <div class="paper">
                    <?php foreach($topic->list as $val){ ?>
                        <h5>题目1:</h5>
                        <input type="hidden" name="subId" value="<?php echo $val->id ?>" >
                        <input type="hidden" name="topicType" id="t_type" value="<?php echo $val->showTypeId ?>" >
                        <h6><?php echo "【".$val->year."年】 ".$val->provenanceName." ".$val->questiontypename;?></h6>
                        <p><?php echo $val->content ?></p>
                        <?php if($val->showTypeId == 1){ // 单选
                            $select = array(
                                '0'=>array('id'=>'0','content'=>'A'),
                                '1'=>array('id'=>'1','content'=>'B'),
                                '2'=>array('id'=>'2','content'=>'C'),
                                '3'=>array('id'=>'3','content'=>'D')
                            );
                            if($val->answerOption == ''){
                                foreach($select as $k=>$v){
                                    echo "<input type='radio' name='answer' class='radio' value='".$v['id']."' /><label>".strip_tags($v['content'])."</label><br />";
                                }
                            }elseif($val->answerOption == null){
                                foreach($select as $k=>$v){
                                    echo "<input type='radio' name='answer' class='radio' value='".$v['id']."' /><label>".strip_tags($v['content'])."</label><br />";
                                }
                            }elseif($val->answerOption == '[]'){
                                foreach($select as $k=>$v){
                                    echo "<input type='radio' name='answer' class='radio' value='".$v['id']."' /><label>".strip_tags($v['content'])."</label><br />";
                                }
                            }else{
                                foreach(json_decode($val->answerOption) as $k=>$v){
                                    echo "<input type='radio' name='answer' class='radio' value='".$v->id."' /><label>". LetterHelper::getLetter($k).".  ".strip_tags($v->content)."</label>";
                                }
                            }
                        ?>

                        <?php } else if($val->showTypeId == 2){ // 多选
                            foreach(json_decode($val->answerOption) as $k=>$v){
                                echo "<input type='checkbox' name='answer[]' class='checkbox' value='".$v->id."' /><label>". LetterHelper::getLetter($k).".  ".$v->content."</label>";
                            }?>

                        <?php }else{
                            if(!empty($val->childQues)){  //多个小题
                                foreach($val->childQues as $chilkey=>$chilval){ ?>
                                    <li> <span>小题 <?php echo ($chilkey+1); ?>:</span><?php echo $chilval->content ?>
                                    <input type="hidden" value="<?php echo $chilval->showTypeId ?>" class="xiao_type">
                                    <?php if($chilval->showTypeId == 1) {
                                        echo "<br>";
                                        foreach (json_decode($chilval->answerOption) as $k => $v) {
                                            echo "<input type='radio' name='ImgUrl[".$chilval->id."][radio][]' class='xiao_radio' value='" . $v->id . "' /><label>" . LetterHelper::getLetter($k) . ".  " . $v->content . "</label>";
                                        }
                                    }else if($chilval->showTypeId == 2){
                                        echo "<br>";
                                        foreach(json_decode($chilval->answerOption) as $k=>$v){
                                            echo "<input type='checkbox' name='ImgUrl[".$chilval->id."][checkbox][]' class='xiao_checkbox' value='".$v->id."' /><label>". LetterHelper::getLetter($k).".  ".$v->content."</label>";
                                        }

                                    } else{ ?>
                                        <input type="hidden" value="<?php echo $chilval->id ?>" class="xid">
                                        <p class="addPic">
                                            <a href="javascript:;" class="mini_btn2 w80 bg_blue a_button">上传答案</a>
                                            <?php
                                            $t2 = new frontend\widgets\xupload\models\XUploadForm;
                                            echo  \frontend\widgets\xupload\XUploadSimple::widget( array(
                                                'url' => Yii::$app->urlManager->createUrl("upload/header"),
                                                'model' => $t2,
                                                'attribute' => 'file',
                                                'autoUpload' => true,
                                                'multiple' => true,
                                                'options' => array(
													'acceptFileTypes' => new \yii\web\JsExpression('/(\.|\/)(jpg|png|jpeg)$/i'),
                                                    "done" => new \yii\web\JsExpression('done'),
                                                    "processfail" => new \yii\web\JsExpression('function (e, data) {var index = data.index,file = data.files[index]; if (file.error) {alert(file.error);}}')
                                                ),
                                                'htmlOptions' => array(
                                                    'id' => 't'.$chilval->id,
                                                )
                                            ));?>
                                        </p> <dl class="userAnswerList clearfix">
                                            <dt>您上传的答案:</dt>
                                            <dd class="addImage">
                                                <ul> </ul>
                                            </dd>
                                        </dl>
                                        </li>
                                    <?php }}} else{ // 单个答案  ?>
                                <li>
                                    <input type="hidden" value="<?php echo $val->id ?>" class="xid">
                                    <p class="addPic">
                                        <a href="javascript:;" class="mini_btn2 w80 bg_blue a_button">上传答案</a>
                                        <?php
                                        $t2 = new frontend\widgets\xupload\models\XUploadForm;
                                        echo  XUploadSimple::widget( array(
                                            'url' => Yii::$app->urlManager->createUrl("upload/header"),
                                            'model' => $t2,
                                            'attribute' => 'file',
                                            'autoUpload' => true,
                                            'multiple' => true,
                                            'options' => array(
                                                'acceptFileTypes' => new \yii\web\JsExpression('/(\.|\/)(jpg|png|jpeg)$/i'),
                                                "done" => new \yii\web\JsExpression('done'),
                                                "processfail" => new \yii\web\JsExpression('function (e, data) {var index = data.index,file = data.files[index]; if (file.error) {alert(file.error);}}')
                                            ),
                                            'htmlOptions' => array(
                                                'id' => 't'.$val->id,
                                            )
                                        ));
                                        ?>
                                    </p>
                                    <dl class="userAnswerList clearfix">
                                        <dt>您上传的答案:</dt>
                                        <dd class="addImage">
                                            <ul> </ul>
                                        </dd>
                                    </dl></li>
                            <?php } } }?>
                </div>
            </div>
            <div class="conserve">
                <button type="submit" class="bg_red_d w120 finishBtn">答题完毕</button>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<!--主体内容结束-->
<script>
    done = function (e, data) {
        $.each(data.result, function (index, file) {
            if (!file.error) {
                $(e.target).parents('li').find('.addImage').append('<li style=" position: relative; float:left;margin-right: 10px;"><a class="fancybox"  href="'+file.url + '" ><img src="' + file.url + '" alt="" height="90" width="120"></a><i style=""  class="delBtn"></i><input class="url" name="ImgUrl['+  $(e.target).parents('li').find('.xid').val()  +'][img][]" type="hidden" value="' + file.url + '" /></li>  ');
            }
            else {
                alert(file.error);
            }
        });
    }
</script>