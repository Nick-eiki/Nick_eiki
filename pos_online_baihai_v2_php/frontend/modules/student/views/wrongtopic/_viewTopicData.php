<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-10-31
 * Time: 上午11:59
 */
/** @var $this CController */
use frontend\components\CLinkPagerExt;
use frontend\components\helper\LetterHelper;
use frontend\models\dicmodels\DegreeModel;
use frontend\services\pos\pos_PersonalInformationService;

?>
<div class="testPaperView pr">
    <div class="paperArea">
        <?php if(!empty($item)){ ?>
            <div class="paper">
                <h6><?php if(!empty($item->year)){ $str = "【".$item->year."年】 ";}else{ $str = '';}echo $str.$item->provenanceName." ".$item->questiontypename;?></h6>
                <p><?php echo $item->content ?></p>
                <?php if(!empty($item->childQues)){
                    echo " <ul class='sub_Q_List'>";
                    foreach($item->childQues as $num=>$items){
                        if($item->showTypeId != 3){
                            echo "<li><span>小题".($num+1).":</span>".$items->content;
                        }

                        /* if(!empty($items->childQues)){
                             foreach($items->childQues as $a=>$b){
                                 echo "<p style='margin-left: 20px;'><span>小题".($num+1)."-".($a+1).":</span>".$b->content."</p>";
                             }
                         }
                         */
                        if($items->showTypeId == 1 || $items->showTypeId == 2){
                            $answer = json_decode($items->answerOption);
                            foreach($answer as $k=>$v){
                                if  (isset($v->content)){
                                    echo "<br><label>".LetterHelper::getLetter($k).".".preg_replace("/<(?!\/?IMG)[^<>]*>/is","",$v->content)."</label>&nbsp; &nbsp; &nbsp; &nbsp;";
                                }


                            }

                        }

                        echo "</li>";
                    }
                    echo "</ul>";
                }
                if($item->showTypeId == 1 || $item->showTypeId == 2){
                    foreach(json_decode($item->answerOption) as $k=>$v){
                        if  (isset($v->content)){
                            echo "<label>". LetterHelper::getLetter($k).". ".preg_replace("/<(?!\/?IMG)[^<>]*>/is","",$v->content)."</label>&nbsp; &nbsp; &nbsp; &nbsp;";
                        }
                    }
                }?>

                <div class="btnArea clearfix"> <span class="openAnswerBtn fl">查看答案与解析<i class="open"></i></span>
                    <span class="r_btnArea fr">难度:<em class="ezy" ><?php  echo DegreeModel::model()->getDegreeName($item->complexity)?></em>

                        <?php
                        echo Html::dropDownList('norm',  '',
                            DegreeModel::model()->getListData(),
                            array(
                                "defaultValue" => false, "prompt" => "请选择 ",
                                'data-validation-engine' => 'validate[required]',
                                'class'=>'sele',
                                'style'=>"display: none;"
                            ));
                        ?>
                        <input type="hidden" value="<?php echo $item->id ?>" class="topid">
                                <a href="javascript:" class="gray hide">确定</a>

                    <i class="editDifficult"></i>

                        &nbsp;&nbsp;&nbsp;录入:<?php $obj = new pos_PersonalInformationService(); $user = $obj->loadUserInfoById($item->operater); echo $user->trueName?></span> </div>
                <div class="answerArea hide">
                    <?php if(!empty($item->childQues)){
                        echo "<p><em>答案:</em>";
                        if($item->showTypeId == 3){
                            foreach($item->childQues as $a=>$b) {
                                echo " <span>".$b->answerContent . "</span>";
                            }
                        }else{
                            foreach($item->childQues as $dnum=>$dvals){
                                if($dvals->showTypeId == 1 || $dvals->showTypeId == 2){
                                    $CanswerContent = explode(",",$dvals->answerContent);
                                    echo "<p>小题".($dnum+1).":  <span>";
                                    foreach($CanswerContent as $vb1){
                                        echo LetterHelper::getLetter($vb1)." &nbsp; &nbsp;";
                                    }
                                    echo "</span></p>";
                                }else{

                                    if(!empty($dvals->childQues)){
                                        echo "<p>小题" . ($dnum + 1).":";
                                        foreach($dvals->childQues as $a=>$b) {
                                            echo " <span>".$b->answerContent . "</span>";
                                        }
                                        echo "</p>";
                                    }else{
                                        if($dvals->showTypeId == 1){
                                            echo "<p>小题".($dnum+1).":  <span>".LetterHelper::getLetter($dvals->answerContent)."   </span></p>";
                                        }elseif($dvals->showTypeId == 2){
                                            $answerContentv = explode(",",$dvals->answerContent);
                                            echo "<p>小题".($dnum+1).":  <span>";
                                            foreach($answerContentv as $vbb){
                                                echo LetterHelper::getLetter($vbb);
                                            }
                                            echo "   </span></p>";
                                        }else{
                                            echo "<p>小题".($dnum+1).":  <span>".$dvals->answerContent."   </span></p>";
                                        }

                                    }
                                }

                            }
                        }

                        echo "</p>";
                        echo "<p><em>解析:</em>".$item->analytical."</p>";
                    }else if($item->showTypeId == 1 || $item->showTypeId == 2){
                        echo "<p><em>答案:</em><span>";
                        $answerContent = explode(",",$item->answerContent);
                        foreach($answerContent as $vbb){
                            echo  LetterHelper::getLetter($vbb)."&nbsp;&nbsp";
                        }
                        echo "</span></p>";
                        echo "<p><em>解析:</em>".$item->analytical."</p>";
                    }else{
                        echo "<p><em>答案:</em><span>".$item->answerContent."</span></p>";
                        echo "<p><em>解析:</em>".$item->analytical."</p>";
                    }

                    ?>
                </div>
            </div>  <hr>
        <?php }else{ echo "无数据";}?>
    </div>
</div>
<div class="page minipage">
    <?php
    if(isset($page)){
         echo CLinkPagerExt::widget( array(
                'pages' => $page,
                'updateId' => '#update',
                'htmlOptions' => array('class' => 'page minipage'),
                'maxButtonCount' => 5
            )
        );
    }

    ?>
</div>
<script>

    $('.openAnswerBtn').unbind('click').click(function(){
        $(this).children('i').toggleClass('close');
        $(this).parents('.paper').find('.answerArea').toggle();
    });
    $(function(){


        $('.Determine').live('mouseover',function(){
            $(this).css('color','#F00')
        });
        $('.Determine').live('mouseout',function(){
            $(this).css('color','#000')
        });
        $('.editDifficult').live('click',function(){
            $(this).siblings('em').hide();
            $(this).siblings('.sele').show();
            $(this).siblings('a').show();
        });
        $('.Determine').live('click',function(){
            var $this = $(this);
            var url= "<?php echo url('/student/wrongtopic/modify-complexity');?>";
            var tid= $(this).prev('.topid').val();
            var val =$(this).siblings('.sele').val();
            if(val == '') return false;
            $.post(url,{'tid':tid,'val':val},function(msg){
                $this.siblings('.ezy').html(msg.data);
            });
            $(this).hide();
            $(this).siblings('.sele').hide();
            $(this).siblings('em').show();
        })
    })
</script>