<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-15
 * Time: 上午11:02
 */
/* @var $this yii\web\View */  $this->title="学生-日常作业";
?>
<div class="currentRight grid_16 push_2 hear">
    <div class="notice routineTest">
        <div class="noticeH clearfix noticeB">
            <h3 class="h3L">日常作业</h3>
        </div>
        <hr>
        <div class="routineTest_main">
            <ul class="routineTest_List">
                <?php foreach ($list->list as $v) {
                    if ($v->getType == 0) {
                        ?>
                        <li class="pr">
                            <a href="<?php echo url('student/managetask/upload-details', array('homeworkID' => $v->homeworkId)) ?>"><?php echo $v->homeworkName ?></a>


                            <p>上传的作业<em>
                                    （<i><?php if ($v->isUploadAnswer == 0) {
                                            echo "未答";
                                        } elseif ($v->isUploadAnswer == 1 && $v->isCheck == 0) {
                                            echo "未批改";
                                        } elseif ($v->isCheck == 1) {
                                            echo "已批改";
                                        }?></i>）</em></p>
                            <p>交作业截止时间：<?php echo $v->deadlineTime ?></p>
                        </li>
                    <?php
                    }elseif($v->getType==1){?>
                        <li class="pr">
                            <?php if($v->isUploadAnswer==0){?>
                                <a href="<?php echo url('student/managetask/online-begin',array('homeworkID'=>$v->homeworkId))?>"><?php echo    $v->homeworkName ?>
                                           </a>
                            <?php }elseif($v->isUploadAnswer==1){?>
                                <a href="<?php echo url('student/managetask/online-answered',array('homeworkID'=>$v->homeworkId))?>">
                                         <?php echo    $v->homeworkName ?> </a>
                    <?php }?>


                    <p>在线作业<em>（<i><?php
                                if($v->isUploadAnswer==0){
                                echo "未答";
                                }elseif($v->isUploadAnswer==1){
                                    if ($v->isCheck == 0) {
                                        echo "未批改";
                                    } elseif ($v->isCheck == 1) {
                                        echo "已批改";
                                    }
                                     }?>
                            </i>）</em></p>

                    <p>交作业截止时间：<?php echo $v->deadlineTime?></p>
                </li>
                <?php    }
                } ?>


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