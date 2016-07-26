<?php
/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 14-11-19
 * Time: 下午5:21
 */
use frontend\models\dicmodels\KnowledgePointModel;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */  $this->title="开始答题";
$this->registerJsFile(publicResources_new() . '/js/My97DatePicker/WdatePicker.js'.RESOURCES_VER, [ 'position'=>\yii\web\View::POS_HEAD] );

$backend_asset = publicResources_new();
;
$this->registerJsFile($backend_asset . "/js/jquery.validationEngine-zh_CN.js".RESOURCES_VER);
$this->registerJsFile($backend_asset . "/js/jquery.validationEngine.min.js".RESOURCES_VER);
$this->registerJsFile($backend_asset . "/js/register.js".RESOURCES_VER);
$this->registerJsFile($backend_asset . "/js/ztree/jquery.ztree.all-3.5.min.js".RESOURCES_VER);
$this->registerJsFile($backend_asset . "/js/ztree/zTreeStyle/zTreeStyle.css".RESOURCES_VER);
?>
<script>

    $(function () {

        /*删除按钮*/
        $('.minute li i').live('click', function () {
            $(this).parent().remove();
        });

        //完成答题
        $('.completeJS').click(function () {

            /*上传试卷*/
            $('#complete_all').dialog({
                autoOpen: false,
                width: 600,
                modal: true,
                resizable: false,
                buttons: [
                    {
                        text: "确定",
                        click: function () {

                            //处理答题结果
                            var qus = [];
                            $('#startAnswerList dl[tab]').each(function (index) {
                                var questionID = $(this).attr('val');
                                pid = $(this).attr('tab');
                                var value = [];
                                $("#" + pid + " input").each(function () {
                                    if ($(this).attr('tpid') == 1) {
                                        if ($(this).attr("checked")) {
                                            value.push($(this).val());
                                        }
                                    }
                                    if ($(this).attr('tpid') == 2) {
                                        if ($(this).attr("checked")) {
                                            value.push($(this).val());
                                        }
                                    }
                                });

                                var obj = {"questionId": questionID, "answer": value };
                                qus.push(obj);
                            });

                            $.post('<?php echo Url::to(['answer-question','questionTeamID'=>app()->request->getQueryParam('questionTeamID'),'notesID'=>app()->request->getQueryParam('notesID')]) ?>',
                                {qus:qus}, function (result) {
                                if (result.success) {
                                    window.location.href = "<?php  echo Url::to(["finish-answer",'questionTeamID'=>app()->request->getQueryParam('questionTeamID'),'notesID'=>app()->request->getQueryParam('notesID')])?>";
                                } else {
                                    popBox.errorBox('保存失败');
                                }
                                return false;
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

            $("#complete_all").dialog("open");
            return false;
        })
    })
</script>

<div class="grid_19 main_r">
    <div class="main_cont online_answer">
        <div class="title">
            <a href="<?=url('/student/managepaper/topic-push'); ?>" class="txtBtn backBtn"></a>
            <h4>练一练</h4>
        </div>
        <!--<p>
            <span>组织时间：<?php /*echo $model->createTime; */?></span>
        </p>-->


    <div class="work_detais_cent">
        <!--<p class="right_title"><em><?php /*echo $model->gradename; */?></em><em><?php /*echo $model->subjectname; */?></em><em>人教版</em><span><em>自定义标签：</em><?php /*echo $model->labelName; */?></span>
        </p>-->
	    <h4 style="font-size: 18px;"><?= Html::encode($model->questionTeamName);?></h4>
        <ul class="ul_list">
            <li><span>1、</span>考察知识点：
                <?php
                if (isset($model->connetID)) {
                    foreach (KnowledgePointModel::findKnowledge($model->connetID) as $key => $item) {
                        echo '<span>' . $item->name . '&nbsp;&nbsp;</span>';
                    }
                } ?></li>
            <li><span>2、</span>本试卷共包含<?php echo $model->countSize;?>道题目，其中单选<?php echo $model->single;?>题</li><!--,多选<?php //echo $model->multi;?>题-->
        </ul>
        <div class="testPaperView">
            <div class="paperArea">

                    <div id="startAnswerList">
                        <?php echo $this->render('_startanswer_list', array('model' => $model, 'pages' => $pages)); ?>
                    </div>
                    <p class="btnD">
                        <button type="button" class="bg_blue btn btn_js completeJS w140">完成答题</button>
                    </p>

            </div>
        </div>
    </div>
    </div>
</div>

<!--完成答题/完成所有-->
<div id="complete_all" class=" popBox complete_all hide" title="完成答题">
    <div class="impBox">

    </div>
</div>



<!--主体内容结束-->

<script type="text/javascript">
    //判断答了多少道题
    $(function(){
        var lenght_t= $('#startAnswerList').find('.title_tye').length;
        var chk= $('.title_tye input').attr("checked");
        $('.title_tye input:radio').click(function(){

            if(this.checked==true)
            {
                $(this).parents('.title_tye').children('h5').addClass('ctit');
            }
            else{
                $(this).parents('.title_tye').children('h5').removeClass('ctit');
            }
        });

        var check_text=$('.hide').val();

        $('.title_tye input:checkbox').change(function(){
            var chek2 =$(this).parents('.title_tye').find('input:checkbox:checked').length;
            if(chek2==0)
            {
                $(this).parents('.title_tye').children('h5').removeClass('ctit2')
            }else{
                $(this).parents('.title_tye').children('h5').addClass('ctit2')
            }
        });

        $('.completeJS').click(function(){
            var com = $('#startAnswerList .ctit').length;
            var com2=$('#startAnswerList .ctit2').length;

            var sum=com+com2;

            if(lenght_t == sum){
                $('.impBox').html('<p>您的题目已全部回答完毕，是否提交？</p>');
            }else if(lenght_t > sum){
                $('.impBox').html('<p>您的答题情况：本试卷共计<span id="s1"> 5 </span>道小题，您共完成<em id="em"> 2 </em>道题。</p>');
                $('#s1').text('').append(lenght_t);
                $('#em').text('').append(sum);
            }
        })
    })
</script>



