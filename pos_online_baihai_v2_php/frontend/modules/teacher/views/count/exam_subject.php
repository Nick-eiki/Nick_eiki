<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-4-11
 * Time: 下午2:58
 */
use yii\helpers\Html;

echo Html::dropDownList("",""
    ,
    $subjectArray,
    array(
        "prompt" => "请选择",
        "id" => "subject"
    ));
?>
<script>
    $("#subject").change(function(){
        examID=$("#exam").val();
        subjectID=$(this).val();
        $("#echarts03").addClass("echarts");
        $.getScript("<?php echo url('teacher/count/exam-score-dis')?>?examID="+examID+"&subjectID="+subjectID,function(result){

        })
    })
</script>
