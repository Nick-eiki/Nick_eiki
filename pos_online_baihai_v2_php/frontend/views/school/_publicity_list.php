<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 2015/7/8
 * Time: 19:06
 */
use frontend\components\helper\ViewHelper;
use yii\helpers\Html;

?>
<?php  if(!empty($publicityList)){ foreach($publicityList as $v){?>
<dl class="publicList clearfix">
    <dd><h5><a href="<?= url('school/publicity-details', array('schoolId' => app()->request->getParam("schoolId"),"publicityId"=>$v->publicityId)); ?>"><?= Html::encode($v->publicityTitle)?></a>
             <?php if(loginUser()->isTeacher()){?>
            <a href="<?=url('school/update-publicity',array('schoolId'=>app()->request->getParam('schoolId'),'publicityId'=>$v->publicityId))?>"><b></b>
            </a>
            <?php }?>
        </h5></dd>
    <dd><em class="blue_d"><?=$v->userName?></em>  <em class="gray_d"><?=date("Y-m-d H:i",($v->updateTime)/1000)?></em></dd>
    <dd class="publicCont"><?= cut_str(Html::encode($v->publicityContent),50)?></dd>
</dl>
<?php } }else{
    ViewHelper::emptyView();
}?>
<div class="page ">
    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(
            'updateId' => '.publicityList',
           'pagination'=>$pages,
            'maxButtonCount' => 5,
            'showjump'=>true
        )
    );
    ?>
</div>
