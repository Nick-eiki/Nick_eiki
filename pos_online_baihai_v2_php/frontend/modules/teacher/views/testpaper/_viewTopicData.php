<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-14
 * Time: 上午11:11
 */
use frontend\components\helper\LetterHelper;
use frontend\models\dicmodels\DegreeModel;
use frontend\services\pos\pos_PersonalInformationService;

?>

<div class="testPaperView pr">
    <div class="paperArea">
        <?php if(!empty($item)){?>
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
                    <span class="r_btnArea fr">难度:<em class="ezy" ><?php  echo DegreeModel::model()->getDegreeName($item->complexity)?></em>&nbsp;&nbsp;&nbsp;录入:<?php $obj = new pos_PersonalInformationService(); $user = $obj->loadUserInfoById($item->operater); echo $user->trueName?></span> </div>
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
<script>
   $('.openAnswerBtn').unbind('click').click(function(){
		$(this).children('i').toggleClass('close');
		$(this).parents('.paper').find('.answerArea').toggle();
    })
</script>