<?php
/**
 * Created by unizk.
 * User: ysd
 * Date: 2015/4/22
 * Time: 17:08
 */
/* @var $this yii\web\View */  $this->title='题目统计结果';
$backend_asset = publicResources_new();
;
$this->registerJsFile($backend_asset . "/js/echarts/echarts-all.js");

?>
<script type="text/javascript">
    $(function(){
        $('.resultList.testClsList li').click(function(){
            var notesID = $(this).attr('notesID');
            var questionTeamID = $(this).attr('questionTeamID');
            $.get('<?= url('teacher/managepaper/topic-push-result'); ?>',{questionTeamID:questionTeamID,notesID:notesID},function(data){
                $('#topicpushlist').html(data);
            });
        });
    })

</script>


<div class="grid_19 main_r">
    <div class="main_cont test">
        <div class="title">
            <!--<a href="<?/*= url('teacher/managepaper/detailpaper',array('questionTeamID'=>app()->request->getParam('questionTeamID'))); */?>" class="txtBtn backBtn"></a>-->
            <a href="javascript:history.go(-1);" class="txtBtn backBtn"></a>
            <h4>统计结果</h4>
        </div>
        <div class="testArea">
            <div class="form_list">
                <div class="row">
                    <div class="formL">

                    </div>
                    <div class="formR">
                        <ul class="resultList testClsList clearfix" >
                            <li notesID=""><a href="javascript:;">全部结果:</a></li>
                            <?php
                                foreach($model->list as $key=>$val){
                                    if($key == 0){
                                        echo '<li questionTeamID="'.$val->questionTeamID.'" notesID="'.$val->notesID.'" class="ac"><a href="javascript:;">'.$val->notesTime.'</a></li>';
                                    }else{
                                        echo '<li questionTeamID="'.$val->questionTeamID.'" notesID="'.$val->notesID.'" ><a href="javascript:;">'.$val->notesTime.'</a></li>';
                                    }

                                }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="fruit"><span>共为您找到<em><?=$model->answerCnt; ?></em>份答卷</span></div>
            <hr>
            <div id="topicpushlist">
                <?php
                    echo $this->render('_topicpushresult_list', array('model' => $model,'obj'=>$obj, 'pages' => $pages));
                ?>
            </div>
        </div>
    </div>
</div>

<!--主体end-->