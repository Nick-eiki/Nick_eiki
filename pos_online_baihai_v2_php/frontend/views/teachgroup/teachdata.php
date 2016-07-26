<?php
/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 2015/7/6
 * Time: 15:49
 */
use frontend\models\dicmodels\FileModel;
use frontend\models\dicmodels\GradeModel;

/* @var $this yii\web\View */  $this->title='教研组—教研资料';
$searchArr = array(
    'gradeid' => app()->request->getParam('gradeid',$gradeid),
    'mattype' => app()->request->getParam('matType',$mattype),
    'groupId'=>$groupId
);
?>


<div class="main_cont">

    <div class="title">
        <h4>教研资料</h4>
        <div class="title_r">
            <span>文件：<?=$allNum;?>个</span><span>阅读：<?=$allReadNum?>次</span>
        </div>
    </div>
    <div class="form_list no_padding_form_list" style="margin:20px 0 20px 0;">
        <div class="row">
            <div class="formR">
                <ul class="resultList testClsList clearfix" >
                    <li class="<?php echo '' == app()->request->getParam('gradeid', $gradeid) ? 'ac' : ''; ?>">
                        <a href="<?php echo url('teachgroup/teach-data', array_merge($searchArr, array('gradeid' => ''))) ?>">全部年级</a>
                    </li>
                    <?php
                    $grade = GradeModel::model()->getWithList(loginUser()->getModel()->department,'');
                    $teaGra = \common\helper\UserInfoHelper::getGradeName(user()->id);
                    foreach($grade as $val){
                     ?>
                        <li class="<?php echo $val['gradeId'] == app()->request->getParam('gradeid', $gradeid) ? 'ac' : ''; ?>">
                            <a href="<?php echo url('teachgroup/teach-data', array_merge($searchArr, array('gradeid' => $val['gradeId']))) ?>"><?= $val['gradeName']?></a>
                        </li>
                    <?php }?>

                </ul>
            </div>
        </div>
        <div class="row">
            <div class="formL">

            </div>
            <div class="formR">
                <ul class="resultList testClsList clearfix" >
                    <li class="<?php echo '' == app()->request->getParam('mattype', $mattype) ? 'ac' : ''; ?>">
                        <a href="<?php echo url('teachgroup/teach-data', array_merge($searchArr, array('mattype' => ''))) ?>">全部类型</a>
                    </li>
                    <?php
                    $file = FileModel::model()->getList();
                    foreach($file as $val){
                        ?>
                        <li class="<?php echo $val->secondCode == app()->request->getParam('mattype', $mattype) ? 'ac' : ''; ?>">
                            <a href="<?php echo url('teachgroup/teach-data', array_merge($searchArr, array('mattype' => $val->secondCode))) ?>"><?= $val->secondCodeValue?></a>
                        </li>
                    <?php }?>

                </ul>
            </div>
        </div>
    </div>
    <div id="teachdata">
        <?php echo $this->render('_teachdata_list', array('model'=>$model,  'groupId'=>$groupId, 'pages' => $pages)) ?>
    </div>

</div>
