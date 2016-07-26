<?php
/**
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2015/11/20
 * Time: 11:59
 */
use frontend\components\WebDataCache;

?>
<i class="v_r_arrow"></i>
<div class="testPaperView">
<div class="paper">
<?php echo $this->render('//publicView/questionPreview/_itemPreviewType', array('item' => $questionResult)); ?>
<div class="answerArea ">
    <p><em>答案:</em>
        <span><?php echo $this->render('//publicView/questionPreview/_itemProblemAnswer', array('item' => $questionResult)); ?></span>
    </p>
    <?php if(WebDataCache::getShowTypeID($questionResult->tqtid)!= 8){?>
        <p><em>解析:</em>
            <?php echo $questionResult->analytical; ?>
        </p>
    <?php } ?>
</div>
        <?php if($showType ==1 || $showType == 2 ):?>
            <?php if(!empty($student)):?>
                <div class="stu_answer">Ta的答案:
                    <span>
                        <?php
                        if(empty($answerOption) && $answerOption !== '0'){
                            echo "未答";
                        }else {
                            $answerOption = explode(',', $answerOption);
                            foreach ($answerOption as $option) {
                                echo \frontend\components\helper\LetterHelper::getLetter($option);
                            }
                        }
                        ?>
                    </span>
                </div>
            <?php else:?>
                <div class="answerAnaly">答案分析:
                    <?php foreach($allOptions as $key => $v):?>
                        <span><?php
                            if(empty($key) && $key !== 0){
                                echo "未答";
                            }else {
                                $options = explode(',',$key);
                                foreach($options as $option){
                                    echo \frontend\components\helper\LetterHelper::getLetter($option);
                                }
                            }
                            ?><br>
                            <b><?php echo sprintf("%.1f", ($v/$optionCountSum)*100)?>%</b>
                        </span>
                    <?php endforeach;?>
                </div>
            <?php endif;?>
        <?php endif;?>
    </div>
</div>
