<?php
/**
 * Created by PhpStorm.
 * User:  ???
 * Date: 14-10-24
 * Time: 上午10:57
 */
use frontend\components\helper\LetterHelper;
use frontend\components\helper\StringHelper;
use frontend\components\helper\ViewHelper;

?>
<div class="schResult">
    <div class="testPaperView pr">
        <div class="paperArea">
        <?php
        if($model->questionIDList):
            foreach($model->questionIDList as $key=>$val){

        ?>
        <div class="paper"><!--选择题-->
            <h5>题目<?php echo $key+1;?>:</h5>
            <h6><?php if(isset($val->year) && !empty($val->year)){echo '【'.$val->year.'年】';}?> <?php echo $val->provenanceName;?> <?php echo $val->questiontypename;?></h6>
            <p class="q_content"><?php echo StringHelper::htmlPurifier($val->content);?></p>
            <div class="checkArea">

            <?php if($val->answerOption == ''){?>
                <?php
                    $option = array(
                        '0'=>array('id'=>'0','content'=>'A'),
                        '1'=>array('id'=>'1','content'=>'B'),
                        '2'=>array('id'=>'2','content'=>'C'),
                        '3'=>array('id'=>'3','content'=>'D')
                    );
                    if($val->showTypeId == '1'){
                        foreach($option as $option_res){
                            echo '<input type="radio" class="radio" name="aaa">';
                            echo '<label>'.'&nbsp;&nbsp;'.$option_res['content'].'</label>';
                        }
                    }elseif($val->showTypeId == '2'){
                        foreach($option as $option_res){
                            echo '<input type="checkbox" class="checkbox">';
                            echo '<label>'.'&nbsp;&nbsp;'.$option_res['content'].'</label>';
                        }
                    }
                ?>
            <?php }elseif($val->answerOption == null){?>
                <?php
                $option = array(
                    '0'=>array('id'=>'0','content'=>'A'),
                    '1'=>array('id'=>'1','content'=>'B'),
                    '2'=>array('id'=>'2','content'=>'C'),
                    '3'=>array('id'=>'3','content'=>'D')
                );
                if($val->showTypeId == '1'){
                    foreach($option as $option_res){
                        echo '<input type="radio" class="radio" name="aaa">';
                        echo '<label>'.'&nbsp;&nbsp;'.$option_res['content'].'</label>';
                    }
                }elseif($val->showTypeId == '2'){
                    foreach($option as $option_res){
                        echo '<input type="checkbox" class="checkbox">';
                        echo '<label>'.'&nbsp;&nbsp;'.$option_res['content'].'</label>';
                    }
                }
                ?>
            <?php }elseif($val->answerOption == '[]'){?>
                <?php
                $option = array(
                    '0'=>array('id'=>'0','content'=>'A'),
                    '1'=>array('id'=>'1','content'=>'B'),
                    '2'=>array('id'=>'2','content'=>'C'),
                    '3'=>array('id'=>'3','content'=>'D')
                );
                if($val->showTypeId == '1'){
                    foreach($option as $option_res){
                        echo '<input type="radio" class="radio" name="aaa">';
                        echo '<label>'.'&nbsp;&nbsp;'.$option_res['content'].'</label>';
                    }
                }elseif($val->showTypeId == '2'){
                    foreach($option as $option_res){
                        echo '<input type="checkbox" class="checkbox">';
                        echo '<label>'.'&nbsp;&nbsp;'.$option_res['content'].'</label>';
                    }
                }
                ?>
            <?php }else{?>
                <?php
                $option = json_decode($val->answerOption);
                $option=is_array($option)?$option:array();
                if($val->showTypeId == '1'){
                    foreach($option as $option_res){
                        echo '<input type="radio" class="radio" name="aaa">';
                        echo '<label>'.LetterHelper::getLetter($option_res->id).'&nbsp;&nbsp;'.$option_res->content.'</label>';
                    }
                }elseif($val->showTypeId == '2'){
                    foreach($option as $option_res){
                        echo '<input type="checkbox" class="checkbox">';
                        echo '<label>'.LetterHelper::getLetter($option_res->id).'&nbsp;&nbsp;'.$option_res->content.'</label>';
                    }
                } ?>
            <?php }?>

            </div>
            <div class="btnArea clearfix"> <span class="openAnswerBtn fl">查看答案与解析<i class="open"></i></span> <span class="r_btnArea fr">难度:<em><?php if(isset($val->complexityText)){echo $val->complexityText;}?></em>&nbsp;&nbsp;&nbsp;录入:<?php if(isset($val->operaterName)){echo $val->operaterName;}?></span> </div>
            <div class="answerArea hide">
                <p><em>答案:</em><span>
                        <?php
                        if($val->showTypeId == '1'){
                            if($val->answerContent==='0' || $val->answerContent === '1' || $val->answerContent ==='2' || $val->answerContent ==='3'){
                                echo LetterHelper::getLetter($val->answerContent);
                            }else{
                                echo $val->answerContent;
                            }
                        }elseif($val->showTypeId == '2'){
                            $arr = explode(',',$val->answerContent);
                            $array = array();
                            foreach($arr as $opt){
                                $array[] = LetterHelper::getLetter($opt);
                            }
                            $str = implode(',',$array);
                            echo $str;
                        }
                        ?>
                    </span></p>
                <p><em>解析:</em><?php echo StringHelper::htmlPurifier($val->analytical);?></p>
            </div>
        </div>
        <?php }
        else:
            ViewHelper::emptyView();
        endif;
        ?>
    </div>
            </div>
        </div>
        <?php
         echo \frontend\components\CLinkPagerExt::widget( array(
               'pagination'=>$pages,
                'updateId' => '#detailPaperList',
                'maxButtonCount' => 3
            )
        );
        ?>
<script>
    $(function(){
        //查看答案与解析
        $('.openAnswerBtn.fl').click(function(){
            $(this).children('i').toggleClass('close');
            $(this).parents('.paper').find('.answerArea').toggle();
        })
    })
</script>