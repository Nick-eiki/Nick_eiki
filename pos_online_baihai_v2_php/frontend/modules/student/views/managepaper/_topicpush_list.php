<?php
/**
 * Created by ysd
 * User: unizk
 * Date: 14-11-18
 * Time: 下午6:26
 */
use frontend\components\helper\ViewHelper;
use frontend\models\dicmodels\KnowledgePointModel;
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title="题目推送列表";
?>
<div class="titlePush_main">
    <ul class="titlePush_List">
        <?php
        if($model):
        foreach($model as $val){
            ?>
            <li class="pr clearfix">
                <h5>[<?php echo $val->gradename;?> <?php echo $val->subjectname;?>]
               <a href="<?php
                if($val->isAnswered == 0){
                    echo url('student/managepaper/start-answer',array('questionTeamID'=>$val->questionTeamID,'notesID'=>$val->notesID));
                }else if($val->isAnswered == 1){
                    echo url('student/managepaper/finish-answer',array('questionTeamID'=>$val->questionTeamID,'notesID'=>$val->notesID));
                }
                ?>" class="title_p"><?php echo Html::encode($val->questionTeamName);?>
                    <?php if($val->isAnswered == '1'){?>
                         <em class="gray_d">回答完毕</em>
                    <?php }else{ ?>
                        <em class="orenge">未回答</em>
                  <?php   }?>
                </a>
                </h5>
                <p>知识点：<em><?php
                        $res = KnowledgePointModel::findKnowledge( $val->connetID);
                        foreach($res as $value){
                            echo $value->name.'&nbsp&nbsp';
                        }
                        ?></em></p>
                <div class="clearfix titlePush_more">
                    <div class="content fl gray_d">
                        <em>收到时间：</em>
                        <b><?php echo $val->notesTime;?></b>
                    </div>
                </div>
                <div class="check_div">
                    <?php if($val->isAnswered == '0'){?>
                        <a class="rearrangeBtn a_button w100 bg_blue_l"
                       href="<?php echo url("student/managepaper/start-answer",array('questionTeamID'=>$val->questionTeamID,'notesID'=>$val->notesID));?>">答题</a>
                    <?php }?>
                </div>


            </li>
        <?php }
        else:
            ViewHelper::emptyView();
        endif;
        ?>

    </ul>

        <?php
         echo \frontend\components\CLinkPagerExt::widget( array(
               'pagination'=>$pages,
                'updateId' => '#questionTeam',
                'maxButtonCount' => 3
            )
        );
        ?>
</div>

<!--主体内容结束-->