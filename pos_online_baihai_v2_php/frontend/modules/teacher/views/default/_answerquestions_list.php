<?php
/**  */
use frontend\components\helper\ImagePathHelper;
use frontend\components\WebDataCache;
use frontend\services\pos\pos_AnswerQuestionManagerService;

?>
<div class="answer">
    <?php


    $n = 0;
    foreach($modelList as $val){
        ?>
        <dl class="answer_list">
            <dt><em>问题<i><?php echo ++$n; ?></i>:</em><span><?php echo $val->aqName; ?></span></dt>
            <dd>
                <?php
                echo  $val->aqDetail;
                ?>
            </dd>
            <dd>
                <div class="answer_pic">
                    <?php
                    //分离图片
                    $img = ImagePathHelper::getRegPic($val->aqDetail);
                    foreach($img as $k=>$imgSrc) {
                        ?>
                        <span><img width="70" height="70" src="<?php  echo $imgSrc; ?>" alt=""></span>
                    <?php  } ?>
                </div>
            </dd>
            <dd>
                <dl class="answer_head clearfix">
                    <dt>来自：</dt>
                    <dd>
                        <span><img data-type="header" width="40px" height="40px" src="<?php echo publicResources() . WebDataCache::getFaceIcon($val->creatorID);?>" onerror="userDefImg(this);" alt="" title="<?php echo $val->creatorName; ?>"></span>
                        <?php foreach($val->samelist as $samelist){?>
                            <span><img data-type="header"  onerror="userDefImg(this);" width="40px" height="40px" src="<?php echo publicResources() . WebDataCache::getFaceIcon($samelist->userID);?>" alt="" title="<?php echo $samelist->userName;?>"></span>
                        <?php }?>
                    </dd>
                    <dd class="time">时间：<span><?php echo $val->createTime;?></span></dd>
                </dl>
            </dd>
            <dd class="add_text">
                <p><i>采用答案：</i><span id="showuseanswer"><?php
                        if($val->usedNum){
                            echo $val->resultDetail;
                        }else{
                            echo '无';
                        }
                        ?></span></p>
            </dd>
            <dd class="answer_btn">
                <a href="javascript:" class="a_button bg_red_l red_btn_js">解答</a>
                <a href="javascript:;" class="a_button bg_red_l red_btn_0js add_answer">答案(<i><?php echo $val->resutltNumber;?></i>)</a>
                <a href="javascript:" class="a_button bg_green_l q_add" val="<?php echo $val->aqID;?>" user="<?php echo $val->creatorID;?>">同问(<i class="samequestion" id="<?php echo $val->aqID;?>"><?php echo $val->sameQueNumber;?></i>)</a>
                <?php if($val->creatorID == user()->id){?>
                        <a href="<?php echo url('teacher/answer/update-question',array('aqId'=>$val->aqID))  ?>" class="mini_btn green_btn">补充问题</a>
                <?php }?>
            </dd>
            <dd class="pop_up">
                <div class="pop_upD pop_up_js">
                    <em class="emF"></em>
                    <textarea class="textarea textarea_js<?php echo $val->aqID;?>"></textarea>
                    <p>
                        <a href="javascript:;" class="a_button red_btn_js2" val="<?php echo $val->aqID;?>">回答</a>
                        <a href="javascript:" class="a_button bg_gray red_gray_js">取消</a>
                    </p>
                </div>
                <div class="pop_upD pop_upD_js hide">
                    <em class="emQ"></em>
                    <div class="textarea answe_box2js">
                        <?php
                        $material = new pos_AnswerQuestionManagerService();
                        $modelList = $material->SearchQuestionByID($val->aqID);
                        $res = $modelList->data->otherResultList;
                        $msg = $modelList->data->useResultList;
                        $i = 0;
                        foreach($res as $value){
                            ?>
                            <!--已有的答案-->
                            <div class="list list_js clearfix">
                                <span class="list_q">回答<i><?php echo ++$i;?></i>：</span>
                                <span class="list_d"><?php echo $value->resultDetail;?></span>
                                <?php if($val->creatorID == user()->id){  if(empty($msg)){ ?>
                                    <a href="javascript:;" class="mini_btn btn_c"  val="<?php echo $value->resultID;?>">采用</a>
                                <?php }}?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </dd>
        </dl>
    <?php }?>

        <?php
         echo \frontend\components\CLinkPagerExt::widget( array(

               'pagination'=>$pages,
                'updateId' => '#answerquestions',

                'maxButtonCount' => 5
            )
        );
        ?>
</div>