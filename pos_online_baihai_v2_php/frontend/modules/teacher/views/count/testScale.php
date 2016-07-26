<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-12-5
 * Time: 下午3:15
 */
use yii\helpers\ArrayHelper;

$publicResources = Yii::$app->request->baseUrl;
;
$this->registerJsFile($publicResources . '/js/echarts/echarts.js');
$this->registerJsFile($publicResources . '/js/echarts/html5shiv.min.js');
$this->registerJsFile($publicResources . '/js/echarts/respond.min.js');
/* @var $this yii\web\View */  $this->title="测验成绩分布";
?>
<style>
    .echarts {
        width: 100%;
        height: 300px;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #ccc
    }

    .
</style>
<script>
    // 路径配置
    require.config({
        paths: {echarts: '<?php echo publicResources()?>'+'/js/echarts'}
    });
    </script>
<div class="currentRight grid_16 push_2 topic_input">
    <hr>

    <div>
        <em>考试：</em>
        <?php
        echo Html::dropDownList("",app()->request->getParam('testID','')
            ,
            ArrayHelper::map($testArray, 'examId', 'examName'),
            array(
                "prompt"=>"请选择",
                "id" => "testID"
            ));
        ?>
    </div>
    <div id="echarts03" class="echarts"></div>
</div>
<script>
    $("#testID").change(function(){
       var testID=$(this).val();
        $.getScript("<?php echo url('teacher/count/get-test-scale')?>?testID="+testID,function(result){
        });
    })
</script>

