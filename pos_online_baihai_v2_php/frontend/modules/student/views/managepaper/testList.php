<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-25
 * Time: 上午9:57
 */
/* @var $this yii\web\View */  $this->title="日常测验";
?>
<div class="currentRight grid_16 push_12 hear">
    <div class="notice routineTest">
        <div class="noticeH clearfix noticeB">
            <h3 class="h3L">日常测验</h3>
        </div>
        <hr>
        <div class="routineTest_main">
            <ul class="routineTest_List">
                <?php foreach($list as $v){ if($v->getType==1){?>
                    <li class="pr">
                        <?php if($v->isUploadAnswer){?>
                            <a href="<?php echo url('student/managepaper/online-answered',array('testID'=>$v->testID))?>">在线测试<em>（<i>
                                        <?php echo  ($v->isCheck? "已批改":"未批改")?>
                                    </i>）</em></a>
                        <?php }else{ ?>
                            <a href="<?php echo url('student/managepaper/begin-answer',array('testID'=>$v->testID))?>">在线测试<em>（<i>
                                        未答
                                    </i>）</em></a>
                        <?php }?>

                        <p><?php echo $v->testName?></p>
                        <p>考试时间：<?php echo $v->testTime?></p>
                    </li>

                <?php }elseif($v->getType==0){?>
                <li class="pr">
                    <a href="<?php echo url('student/managepaper/upload-details',array('testID'=>$v->testID))?>">上传的试卷<em>（<i>
                                <?php echo  !($v->isUploadAnswer)? "未答":(($v->isCheck)? "已批改":"未批改")?>
                    </i>）</em></a>
                    <p><?php echo $v->testName?></p>
                    <p>考试时间：<?php echo $v->testTime?></p>
                </li>
                <?php } }?>
            </ul>

                <?php
                 echo \frontend\components\CLinkPagerExt::widget( array(
                       'pagination'=>$pages,
                        'maxButtonCount' => 5
                    )
                );
                ?>
        </div>

    </div>
</div>