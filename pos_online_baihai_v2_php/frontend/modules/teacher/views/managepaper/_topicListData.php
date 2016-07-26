<div class="testPaperView pr">
    <div class="paperArea">
        <?php use frontend\components\helper\LetterHelper;
        use frontend\models\dicmodels\DegreeModel;
        use frontend\services\pos\pos_PersonalInformationService;

        foreach($item->list as $tnum=>$val){ ?>
            <div class="paper">
                <h5>题目<?php echo $tnum+1 ?>:</h5>
                <h6><?php if(!empty($val->year)){ $str = "【".$val->year."年】 ";}else{ $str = '';}echo $str.$val->provenanceName." ".$val->questiontypename;?><a href="<?php echo url('/teacher/testpaper/topic-edit');?>?topic=<?php echo $val->id ?>" class="mini_btn btn">题目修改</a></h6>
                <p><?php echo $val->content ?></p>
                <?php if(!empty($val->childQues)){
                    echo " <ul class='sub_Q_List'>";
                    foreach($val->childQues as $num=>$vals){
						if($val->showTypeId != 3){
							echo "<li><span>小题".($num+1).":</span>".$vals->content;
						}
                        
                       /* if(!empty($vals->childQues)){
                            foreach($vals->childQues as $a=>$b){
                                echo "<p style='margin-left: 20px;'><span>小题".($num+1)."-".($a+1).":</span>".$b->content."</p>";
                            }
                        }
						*/
						if($vals->showTypeId == 1 || $vals->showTypeId == 2){
							$answer = json_decode($vals->answerOption);
							foreach($answer as $k=>$v){
								if  (isset($v->content)){
									echo "<br><label>".LetterHelper::getLetter($k).".".$v->content."</label>&nbsp; &nbsp; &nbsp; &nbsp;";
								}
								
							
							}
							
						}
						
                        echo "</li>";
                    }
                    echo "</ul>";
                }
                if($val->showTypeId == 1 || $val->showTypeId == 2){
                    foreach(json_decode($val->answerOption) as $k=>$v){
						if  (isset($v->content)){
						   echo "<label>". LetterHelper::getLetter($k).". ".$v->content."</label>&nbsp; &nbsp; &nbsp; &nbsp;";
						}
                    }
                }?>

                <div class="btnArea clearfix"> <span class="openAnswerBtn fl">查看答案与解析<i class="open"></i></span>
                    <span class="r_btnArea fr">难度:<em class="ezy" ><?php  echo DegreeModel::model()->getDegreeName($val->complexity)?></em>

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
                        <input type="hidden" value="<?php echo $val->id ?>" class="topid">
                                <a href="javascript:" class="Determine" style="display: none;">确定</a>

                    <i class="editDifficult"></i>

                        &nbsp;&nbsp;&nbsp;录入:<?php $obj = new pos_PersonalInformationService(); $user = $obj->loadUserInfoById($val->operater); echo $user->trueName?></span> </div>
                <div class="answerArea hide">
                    <?php if(!empty($val->childQues)){
                        echo "<p><em>答案:</em>";
						if($val->showTypeId == 3){
								 foreach($val->childQues as $a=>$b) {
                                        echo " <span>".$b->answerContent . "</span>";
                                 }
						}else{
							foreach($val->childQues as $dnum=>$dvals){
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
                        echo "<p><em>解析:</em>".$val->analytical."</p>";
                    }else if($val->showTypeId == 1 || $val->showTypeId == 2){
                        echo "<p><em>答案:</em><span>";
                        $answerContent = explode(",",$val->answerContent);
                        foreach($answerContent as $vbb){
                            echo  LetterHelper::getLetter($vbb)."&nbsp;&nbsp";
                        }
                        echo "</span></p>";
                        echo "<p><em>解析:</em>".$val->analytical."</p>";
                    }else{
                        echo "<p><em>答案:</em><span>".$val->answerContent."</span></p>";
                        echo "<p><em>解析:</em>".$val->analytical."</p>";
                    }

                    ?>
                </div>
            </div>  <hr>
        <?php }?>
    </div>
</div>

    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(
            'pages' => $page,
            'updateId' => '#update',
            'maxButtonCount' => 5
        )
    );
    ?>

<script>
    $('.openAnswerBtn').click(function(){
        $(this).parents('.paperArea').children('.answerArea').toggle();
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
            var url= "<?php echo url('/teacher/managepaper/modify-complexity');?>";
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