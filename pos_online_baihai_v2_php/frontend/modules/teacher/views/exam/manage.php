<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-10-15
 * Time: 下午5:10
 */
use frontend\models\dicmodels\ExamTypeModel;

$publicResources = Yii::$app->request->baseUrl;
;
$this->registerJsFile($publicResources . '/pub/js/My97DatePicker/WdatePicker.js');
/* @var $this yii\web\View */  $this->title="考务管理";
?>
<div class="grid_19 main_r">
<div class="main_cont test">
<div class="title">
    <h4>考务管理</h4>

    <div class="title_r">
        <a class=" btn40 bg_green btn w120" href="<?php echo url('teacher/exam/create-exam',array('classID'=>app()->request->getParam('classid')))?>">新建考试</a>
    </div>
</div>
<div class="testArea">
<div class="form_list">
    <div class="row">
        <div class="formL">
            <label>考试类型:</label>
        </div>
        <div class="formR">
            <ul class="resultList ">
                <li type="" class="ac"><a>所有</a></li>
                <?php foreach (ExamTypeModel::model()->getListData() as $key => $value) { ?>
                <li type="<?php echo $key?>"><a><?php echo $value?></a></li>
                <?php }?>
            </ul>
        </div>
    </div>
</div>
<hr>
<br>
<div class="examList ">
<?php echo $this->render("_exam_list",array("examResult"=>$examResult,"pages"=>$pages,"subjectArray"=>$subjectArray))?>
</div>
</div>

</div>
</div>
<script>
    $(".resultList").find("li").click(function(){
        type=$(this).attr("type");
        classID="<?=app()->request->getParam('classid')?>";
        $.get("<?php echo url('teacher/exam/manage')?>",{"type":type,"classid":classID},function(result){
            $(".examList").html(result);
        })
    });
</script>

