<?php
/**
 * Created by PhpStorm.
 * User: lsl
 * Date: 2015/7/7
 * Time: 12:00
 */
use frontend\components\WebDataCache;

/* @var $this yii\web\View */
$this->title='教研组—组内成员';
?>
<script>
    $(function() {

    })
</script>

<div class="main_cont">
    <div class="title">
        <h4>教研组人员管理</h4>
        <div class="title_r">
            <span class="gray_d">成员: <?= $count?>人</span>
        </div>
    </div>
    <div class="mem_item mem_teacher">
        <div class="title item_title noBorder">
            <h4>
                组长</h4>
            <div class="title_r">
            </div>
        </div>
        <ul class="mem_teacher_list clearfix">
            <div class="empty hide">
                <i></i>啥也没有
            </div>
            <?php foreach($master as $val){?>
            <li>
                <img width="50px" height="50px" data-type="header" onerror="userDefImg(this);"  src="<?= WebDataCache::getFaceIcon($val->teacherID)?>">
                <p class="teacher_info">
                    <span><a href="<?= url('teacher/default/index',  ['teacherId' => $val->teacherID]) ?>" title="<?php echo WebDataCache::getTrueName($val->teacherID)?>">
                            <?php echo WebDataCache::getTrueName($val->teacherID)?></a></span>
                    <span><?php echo \common\helper\UserInfoHelper::getUserSubject($val->teacherID)?></span>
                    <!--<span>班主任</span>-->
                    <?php if($val->teacherID != user()->id){?>
                    <em title="删除" class="opBtn delInfoBtn delMaster" data-id="<?=$val->teacherID ?>">删除</em>
                    <?php }?>
                </p>
            </li>
            <?php }?>

        </ul>
    </div>
    <div class="mem_item mem_student">
        <div class="mem_item mem_student">
            <div class="title item_title noBorder">
                <h4>组员</h4>
                <div class="title_r">
                </div>
            </div>
            <table class="mem_student_table">
                <thead>
                <tr>
                    <th style="width: 80px">
                        姓名
                    </th>
                    <th>
                        年级
                    </th>
                    <th style="width: 160px">
                        操作
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($member as $v){?>
                <tr>
                    <td>
                        <a href="<?= url('teacher/default/index',  ['teacherId' => $v->teacherID]) ?>" class="oldVal stu_name" title="<?php echo \common\helper\UserInfoHelper::getUserName($v->teacherID)?>">
                            <?php echo WebDataCache::getTrueName($v->teacherID)?>
                        </a>
                    </td>
                    <td>
                        <span class="oldVal stu_number"><?php echo \common\helper\UserInfoHelper::getGradeName($v->teacherID)?></span>
                    </td>

                    <td class="edit">
                        <?php if($v->teacherID != user()->id){?>
                        <em title="删除" class="opBtn delInfoBtn delMember" data-id="<?=$v->teacherID ?>"> 删除</em>
                        <?php }?>
                    </td>
                </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
        <hr class="dashde">
    </div>
</div>

<script type="text/javascript">
    $(function(){
        //删除操作
        $('.delMaster,.delMember').click(function(){
            _this = $(this);
            popBox.confirmBox('确定要删除吗？', function () {
                var url = '<?php echo url('teachgroup/delete-group-member');?>';
                var groupid = '<?= app()->request->getParam('groupId','')?>';
                var userid = _this.attr('data-id');
                $.post(url,{groupid:groupid,userid:userid},function(data){
                    if(data.success){
                        popBox.successBox(data.message);
                        location.reload();
                    }else{
                        popBox.errorBox(data.message);
                    }

                })
            })
        })
    })
</script>