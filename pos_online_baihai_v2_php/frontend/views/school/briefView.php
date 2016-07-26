<?php  /** @var $this Controller */
use frontend\models\dicmodels\SchoolLevelModel;

/* @var $this yii\web\View */  $this->title="招生简章详情";
$schoolModel=$this->params['schoolModel'];
?>
<div class="main_c clearfix class_listi" style="padding-bottom:50px;">
    <h4>招生简章详情</h4>
    <?php if(loginUser()->isTeacher()){ ?>
        <a href="<?php echo url('school/brief-update', array('id' => $data->briefID, 'schoolId' => $schoolModel->schoolID))?>" class="B_btn120 addBtn" style="margin-left:16px;">修&nbsp;&nbsp;改</a>
   <?php  }?>

    <hr>
    <div class="class_list_min">

        <div class="sc-txt">
            <h3><?php echo SchoolLevelModel::model()->getSchoolLevelhName($data->department)?>&nbsp;&nbsp;<?php echo $data->briefName?></h3>
            <p class="teacher"><?php echo $data->nameOfCreator?><em><?php echo $data->createTime?></em></p>
            <?php echo $data->detailOfBrief?>
        </div>
        <p class="pege">
            
            <span class="pege_left">
                <em>上一篇:</em>
                <?php if(empty($nextPage)){ ?>
                    <a href="#">无</a>
                <?php }else{ ?>
                    <a href="<?php echo url('school/brief-view',array('id'=>$nextPage->briefID,'schoolId'=>$nextPage->schoolID)) ?>"><?php echo $nextPage->departmentName; ?> <?php echo $nextPage->briefName; ?></a>
                <?php } ?>

            </span>
            <span class="pege_right">
                <em>下一篇:</em>
                <?php if(empty($upPage)){ ?>
                    <a href="#">无</a>
            <?php }else{ ?>
                <a href="<?php echo url('school/brief-view',array('id'=>$upPage->briefID,'schoolId'=>$upPage->schoolID)) ?>"><?php echo $upPage->departmentName; ?> <?php echo $upPage->briefName; ?></a>
            <?php } ?>
            </span>
        </p>
    </div>
</div>