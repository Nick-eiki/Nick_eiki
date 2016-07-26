<?php
/**
 * Created by wenjianhua
 * User: wenjianhua
 * Date: 14-9-19
 * Time: 上午9:58
 */
use frontend\services\pos\pos_PaperManageService;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */  $this->title="测验管理";
$this->registerJsFile(publicResources_new() . '/js/My97DatePicker/WdatePicker.js', [ 'position'=>\yii\web\View::POS_HEAD] );

$backend_asset = publicResources_new();
;
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);

$classes = ArrayHelper::map(loginUser()->getClassInfo(), 'classID', 'className');
?>
<script>
    $(function(){
        /*创建测验*/
        $('#adminPop').dialog({
            autoOpen: false,
            width:550,
            modal: true,
            resizable:false,
            buttons: [
                {
                    text: "确定",
                    click: function() {
                        $('#form1').submit();
                    }
                },
                {
                    text: "取消",
                    click: function() {
                        $( this ).dialog( "close" );
                    }
                }

            ]
        });
        $('.admin_js').click(function(){
            $("#paperId option:first").prop("selected","selected");
            $( "#adminPop" ).dialog( "open" );
            return false;
        });




        /*其他试卷*/

        $('#paperId').live('change',function(){

            if($(this).val()=='000')
            {
                $( "#adminPop" ).dialog( "close" );
                $('#other').dialog("open")
            }
        });


        $('#other').dialog({
            autoOpen: false,
            width:550,
            modal: true,
            resizable:false,
            buttons: [
                {
                    text: "上传试卷",
                    click: function() {
                        $( this ).dialog( "close" );
                        window.open('<?php echo url("teacher/managepaper/upload-paper")?>')

                    }
                },
                {
                    text: "在线组卷",
                    click: function() {
                        $( this ).dialog( "close" );
                        window.open('<?php echo url('teacher/makepaper/paper-header')?>');
                    }
                },
                {
                    text: "取消",
                    click: function() {
                        $( this ).dialog( "close" );
                    }
                }

            ]
        });
    });

    function search(obj){
        var url = '<?php echo app()->request->url?>';
        var classId = $(obj).val();
        $.post(url, {classId: classId}, function(result){
            $('#srchResult').replaceWith(result);
        })
    }
</script>
<div class="currentRight grid_16 push_2 uploadpaper_div">
    <div class="noticeH clearfix noticeB uploadedPaper_title">
        <h3 class="h3L">所有测验</h3>
        <div class="new_not fr">
            我的班级
            <?php echo Html::dropDownList('class', '', $classes, array(
                'prompt' => '请选择', "defaultValue" => false,
                'onchange' => 'search(this)'
            ))?>
            <a href="javascript:" class="new_examination admin_js">创建测验</a>
        </div>
    </div>
    <hr>
    <?php echo $this->render('_testListData', array('data' => $data, 'pages' => $pages))?>
</div>

<!--创建测验--------------------->
<div id="adminPop" class=" popBox adminPop hide" title="创建测验">
    <div class="impBox">
        <form action="<?php echo url('teacher/managepaper/uploadtest')?>" id="form1" method="post">
        <ul class="form_list">
            <li>
                <div class="formL">
                    <label><i></i>选择试卷：</label>
                </div>
                <div class="formR">
                    <?php $paperServer = new pos_PaperManageService();
                    $result = $paperServer->queryPaper(user()->id)->list;
                    $papers = ArrayHelper::map($result, 'paperId', 'name');
                    $papers['000'] = '其他';
                    echo Html::dropDownList('paperId', '', $papers, array(
                        'prompt' => '请选择', "defaultValue" => false,
                        'data-validation-engine' => "validate[required]",
                    ))
                    ?>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>测验名称：</label>
                </div>
                <div class="formR">
                    <input type="text" class="text" name="testName" data-validation-engine="validate[required,maxSize[30]]">
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>所属班级：</label>
                </div>
                <div class="formR">
                    <?php echo Html::dropDownList('classId', '', $classes, array('prompt' => '请选择', "defaultValue" => false))?>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>考试时间：</label>
                </div>
                <div class="formR">
                    <input type="text" class="wdate" name="testTime" onclick="WdatePicker({dateFmt:'yyyy-MM-dd H:m:s',minDate:'today'});" data-validation-engine="validate[required]" data-errormessage-value-missing="时间不能为空">
                </div>
            </li>
        </ul>
        </form>
    </div>
</div>

<!--使用其他试卷弹窗--------------------->
<div id="other" class=" popBox other hide" title="使用其他试卷">
    <div class="impBox">
        <p>由于试卷不存在，请先组织新的试卷</p>
    </div>
</div>