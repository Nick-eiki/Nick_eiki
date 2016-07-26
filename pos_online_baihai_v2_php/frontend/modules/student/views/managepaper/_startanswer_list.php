<?php
/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 14-11-25
 * Time: 下午5:21
 */
use frontend\components\helper\LetterHelper;
use frontend\components\helper\StringHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


?>

<?php foreach($model->questionList as $key=>$val){ ?>
<div class="paper">
    <input type="hidden" id="answerID" name="answer[<?php echo  $val->questionID;?>]" value="" >
    <dl class="title_tye" val="<?php echo $val->questionID;?>" tab="Q_<?php echo $val->questionID;?>">
        <h5>题目<?php echo $key+1;?></h5>
        <h6>[<?php if(isset($val->year) && !empty($val->year)){echo $val->year.'年&nbsp;';}?><?php if(isset($val->provenanceName) && !empty($val->provenanceName)){echo $val->provenanceName.'&nbsp;';}?>选择题]</h6>
        <p><?php echo StringHelper::htmlPurifier($val->content);?></p>
            <?php if($val->answerOption == ''){?>
                <ul class="sub_Q_List" id="Q_<?php echo $val->questionID;?>">
                    <?php
                    $showTypeId = $val->showTypeId;
                    $op_list = array(
                        '0'=>array('id'=>'0','content'=>'A'),
                        '1'=>array('id'=>'1','content'=>'B'),
                        '2'=>array('id'=>'2','content'=>'C'),
                        '3'=>array('id'=>'3','content'=>'D')
                    );
                    ?>
                    <?php
                    if($showTypeId == '1'){
                        echo '<li><div class="checkArea">'.Html::radioList("answer[$val->questionID]",'',ArrayHelper::map($op_list,'id','content'),
                            ['class'=>"radio alternative",'encode'=>false,'itemOptions'=>[
                                'qid'=>$val->questionID,'tpid'=>$val->showTypeId],'separator'=>'&nbsp;']).'</div></li>';
                    }elseif($showTypeId == '2'){
                        echo '<li><div class="checkArea">'.Html::checkboxList("answer[$val->questionID]",'',ArrayHelper::map($op_list,'id','content'),
                            ['class'=>"radio alternative",'encode'=>false,'qid'=>$val->questionID,'tpid'=>$val->showTypeId,'separator'=>'&nbsp;']).'</div></li>';
                    }
                    ?>
                </ul>
            <?php }elseif($val->answerOption == null){?>
                <ul class="sub_Q_List" id="Q_<?php echo $val->questionID;?>">
                    <?php
                    $showTypeId = $val->showTypeId;
                    $op_list = array(
                        '0'=>array('id'=>'0','content'=>'A'),
                        '1'=>array('id'=>'1','content'=>'B'),
                        '2'=>array('id'=>'2','content'=>'C'),
                        '3'=>array('id'=>'3','content'=>'D')
                    );
                    ?>
                    <?php
                    if($showTypeId == '1'){
                        echo '<li><div class="checkArea">'.Html::radioList("answer[$val->questionID]",'',ArrayHelper::map($op_list,'id','content'),
                                ['class'=>"radio alternative",'encode'=>false,'itemOptions'=>[
                                    'qid'=>$val->questionID,'tpid'=>$val->showTypeId],'separator'=>'&nbsp;']).'</div></li>';
                    }elseif($showTypeId == '2'){
                        echo '<li><div class="checkArea">'.Html::CheckBoxList("answer[$val->questionID]",'',ArrayHelper::map($op_list,'id','content'),
                                ['class'=>"radio alternative",'encode'=>false,'qid'=>$val->questionID,'tpid'=>$val->showTypeId,'separator'=>'&nbsp;']).'</div></li>';
                    }
                    ?>
                </ul>
            <?php }elseif($val->answerOption == '[]'){?>
                <ul class="sub_Q_List" id="Q_<?php echo $val->questionID;?>">
                    <?php
                    $showTypeId = $val->showTypeId;
                    $op_list = array(
                        '0'=>array('id'=>'0','content'=>'A'),
                        '1'=>array('id'=>'1','content'=>'B'),
                        '2'=>array('id'=>'2','content'=>'C'),
                        '3'=>array('id'=>'3','content'=>'D')
                    );
                    ?>
                    <?php
                    if($showTypeId == '1'){
                        echo '<li><div class="checkArea">'.Html::radioList("answer[$val->questionID]",'',ArrayHelper::map($op_list,'id','content'),
                                ['class'=>"radio alternative",'encode'=>false,'itemOptions'=>[
                            'qid'=>$val->questionID,'tpid'=>$val->showTypeId]
                                ,'separator'=>'&nbsp;']).'</div></li>';
                    }elseif($showTypeId == '2'){
                        echo '<li><div class="checkArea">'.Html::checkBoxList("answer[$val->questionID]",'',ArrayHelper::map($op_list,'id','content'),
                                ['class'=>"radio alternative",'encode'=>false,'qid'=>$val->questionID,'tpid'=>$val->showTypeId,'separator'=>'&nbsp;']).'</div></li>';
                    }
                    ?>
                </ul>
            <?php }else{?>
                <ul class="sub_Q_List" id="Q_<?php echo $val->questionID;?>">
                    <?php
                    $op_list = json_decode($val->answerOption);
                    $op_list=is_array($op_list)?$op_list:array();
                    $showTypeId = $val->showTypeId;
                    foreach($op_list as $option){
                        $option->content= '<em>'.LetterHelper::getLetter($option->id).'&nbsp;&nbsp;</em>'.strip_tags($option->content).'&nbsp;&nbsp;';
                    }
                    ?>

                    <?php
                    if($showTypeId == '1'){
                        echo '<li><div class="checkArea">'.Html::radioList("answer[$val->questionID]",'',ArrayHelper::map($op_list,'id','content'),
                                ['class'=>"radio alternative",'encode'=>false,'itemOptions'=>[
                                    'qid'=>$val->questionID,'tpid'=>$val->showTypeId]
                                    ,'separator'=>'&nbsp;']).'</div></li>';
                    }elseif($showTypeId == '2'){
                        echo '<li><div class="checkArea">'.Html::checkBoxList("answer[$val->questionID]",'',ArrayHelper::map($op_list,'id','content'),
                                ['class'=>"radio alternative",'encode'=>false,'qid'=>$val->questionID,'tpid'=>$val->showTypeId,'separator'=>'&nbsp;']).'</div></li>';
                    }
                    ?>
                </ul>
            <?php }?>
</dl>


</div>
<?php } ?>


    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(
           'pagination'=>$pages,
            'updateId' => '#startAnswerList',
            'maxButtonCount' => 3
        )
    );
    ?>





