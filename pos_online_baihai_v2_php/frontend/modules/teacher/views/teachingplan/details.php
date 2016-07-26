<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-15
 * Time: 下午12:58
 */
/* @var $this yii\web\View */  $this->title="教师管理-教学计划-详情页";
$backend_asset = publicResources();
;
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);
?>
<script>
    $(function(){
        $('h3.Signature i').editPlus();
        $('#addPlan').dialog({
            autoOpen: false,
            width:600,
            modal: true,
            resizable:false,
            buttons: [
                {
                    text: "确定",

                    click: function() {
                        if ($('#form_id').validationEngine('validate')) {
                            var planName = $('#planName').val();
                            var gradeID =$('#gradeID').val();
                            var brief =$('#brief').val();
                            var url = $('#url').val();
                             if(url==""){
                                 popBox.alertBox('上传计划不能为空！');
                                 return false;
                             }
                            $.post("<?php echo url("teacher/teachingplan/add-plan");?>",
                                { planName: planName, gradeID: gradeID, brief: brief, url: url}, function (data) {
                                    if (data.success) {
                                        location.href = "<?php echo url('teacher/teachingplan/index');?>";
                                    }else{
                                        popBox.alertBox(data.message);
                                    }
                                });

                        }
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
        /*添加*/
        $('#newplan').click(function(){
            $( "#addPlan" ).dialog( "open" );
            return false;

        });
        /**删除添加的图片*/
        $('.addPicUl li i').live('click',function(){
            $(this).parent().remove();
        })

    });
    addDone= function (e, data) {
        $.each(data.result, function (index, file) {
            if(file.error){
                popBox.alertBox(file.error);
                return false;
            }
            $('#addimage').html('<li class="fl"><img  src="'+file.url +'" alt=""> <i></i></li>  ');
        });
        var urls=[];

        $("li.fl img").each(function(i){
            urls.push(this.src);
        });
        $('#url').val(urls.join(','));

    };
</script>
<div class="currentRight grid_16 push_2 teachingPlan_div teachingPlanDetails_div">
    <div class="crumbs noticeB"><a href="<?php echo url('teacher/teachingplan/index')?>">个人教学计划</a> >> <a>个人教学计划详情</a></div>
    <div class="noticeH clearfix noticeB teachingPlan_title">
        <h3 class="h3L"><?php echo cut_str($teachingSearch->planName,15);?></h3>
        <div class="new_not fr">
            <a href="javascript:;" class="new_examination newPlanJS" id="newplan">新计划</a></div>
    </div>
    <hr>
    <p>   <?php echo cut_str($teachingSearch->brief,300);?></p>
    <div class="read">文本阅读器</div>
</div>
<div id="addPlan" class="popBox addPlan hide" title="创建教学计划">
    <form id="form_id">
        <div class="impBox">


            <ul class="form_list">
                <li>
                    <div class="formL">
                        <label for="name"><i></i>教学计划名称：</label>
                    </div>
                    <div class="formR">
                        <input id="planName" type="text" class="text" data-validation-engine="validate[required]">
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label for="name"><i></i>使用年级：</label>
                    </div>
                    <div class="formR">
                        <?php
                        echo Html::dropDownList('gradeID',
                            '',
                            ArrayHelper::map( GradeModel::model()->getList(),'gradeId','gradeName'));
                        ?>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label for="name"><i></i>描述：</label>
                    </div>
                    <div class="formR">
                        <textarea id="brief" data-validation-engine="validate[required]"
                                  data-prompt-target="target_error" data-prompt-position="inline"
                                  data-errormessage-value-missing="教学计划描述不能为空"></textarea>
                    </div>
                </li>
                <li>
                    <div class="formL">
                        <label for="name"><i></i>教学计划：</label>
                    </div>
                    <div class="formR">
                        <div class="fl" style="font-size:12px; color:#999999;">
                            <p class="addPic btn btnpop">
                                <a href="javascript:" class="id_btn" style="color:#FFF;">上传试卷</a>
                                <?php
                                $t1 = new frontend\widgets\xupload\models\XUploadForm;
                                echo  \frontend\widgets\xupload\XUploadSimple::widget( array(
                                    'url' => Yii::$app->urlManager->createUrl("upload/doc"),
                                    'model' => $t1,
                                    'attribute' => 'file',
                                    'autoUpload' => true,
                                    'multiple' => false,
                                    'options' => array(
                                        "done" =>new \yii\web\JsExpression('addDone')
                                    ),
                                    'htmlOptions' => array(
                                        'id' => 't1',
                                    )
                                ));
                                ?>
                            </p>
                            <em style="margin-left:10px;">文件格式限定为doc,docx或pdf</em> </div>
                        <ul class="add_del addPicUl" id="addimage">
                            <!--                    <li><a href="javascript:;" class="addDel">删除</a></li>-->
                        </ul>
                    </div>
                    <input type="hidden"  id="url"  value=""/>
                </li>
            </ul>
        </div>
    </form>
</div>