<?php
/**
 * Created by ysd
 * User: unizk
 * Date: 14-11-18
 * Time: 下午6:26
 */
/* @var $this yii\web\View */
$this->title="题目推送";
?>
<div class="grid_19 main_r">
    <div class="main_cont online_answer">
      <div class="title">
          <h4>收到的题目</h4>
      </div>
      <hr>
          	<div  id="questionTeam">
                <?php echo $this->render('_topicpush_list',array('model'=>$model, 'pages' => $pages));?>
            </div>
    </div>
</div>

<!--主体内容结束-->