<?php
/**
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2016/7/15
 * Time: 17:54
 */
use yii\helpers\Url;

?>

<div id="verification_result">


        <p class="unconflict">校验完毕,与平台中已有用户账号不冲突,可以添加,点击<a href="javascript:;">下一步</a></p>

    <p class="conflict">校验完毕,与平台已有用户<a href="javascript:;">账号冲突</a>,请检查下列用户是否有你要添加的人员;</p>
    <p class="conflict">1.<a href="javascript:;">如果有</a>请选择对应用户并点击<a href="javascript:;">下一步</a>;</p>
    <p class="conflict">2.<a href="javascript:;">如果没有</a>请选择新建账号并点击<a href="">下一步</a></p>
</div>
<?php  if($studentList){?>
<table id="message">

<tr id="message_header">
    <td class="short"></td>
    <td class="short">姓名</td>
    <td class="short">性别</td>
    <td>手机号</td>
    <td>学校</td>
    <td>班级</td>
    <td>家长手机号</td>
</tr>
<?php foreach($studentList as $v){?>
<tr class="message_main">
    <td class="short"><input type="radio" name="message" class="add_user_accounts" value="<?=$v->userID?>"></td>
    <td class="short"><?php echo $v->trueName?></td>
    <td class="short"><?php echo $v->sex?'女':'男'?></td>
    <td><?php echo $v->bindphone?></td>
    <td><?php echo \common\models\pos\SeSchoolInfo::getOneCache($v->schoolID)->schoolName ?></td>
    <td><?php
           $classDetails=$v->seClassMembers;

        if(count($classDetails)>0){
            echo \frontend\components\WebDataCache::getClassesName($classDetails[0]->classID) ;
        }?></td>
    <td><?php echo $v->phone?></td>
</tr>
<?php } ?>

<tr class="message_main">
    <td class="short"><input type="radio" name="message" id="add_user_accounts"></td>
    <td class="short">新建帐号</td>
    <td class="short"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>
    </table>
<?php }?>
<div class="btn_class">
    <button class="btn alter" id="next" type="button">下一步</button>
    <a href="<?=Url::to(['manage-list','classId'=>$classID])?>" class="btn" id="remove_1">取消</a>
</div>