<?php
/**
 * Created by unizk.
 * User: ysd
 * Date: 14-12-12
 * Time: 上午10:52
 */
use frontend\components\helper\StringHelper;
use frontend\components\helper\ViewHelper;
use yii\helpers\Html;

?>

<script>
    $(function(){
        /*删除图标显示和隐藏*/
        $('.notice_list li').live('mouseover',function(){
            $(this).children('.crossDelBtn').removeClass('hide');
            $(this).children('.notice_send_btn').addClass('bg_blue_d');
            $(this).addClass('bg_gray_ll');
        });
        $('.notice_list li').live('mouseout',function(){
            $(this).children('.crossDelBtn').addClass('hide');
            $(this).children('.notice_send_btn').removeClass('bg_blue_d');
            $(this).removeClass('bg_gray_ll');
        });
        /*点击查看后字体变化*/
        $('.seebtnJs').live('click',function(){
            $(this).parent('p').removeClass('no_see');
        })

    })

</script>

<ul class="notice_list">
    <?php if($model->list):
        foreach($model->list as $val){ ?>
        <li>
            <p class="font16 <?php if($val->isRead == '0'){ echo 'no_see';}?>">
               <span title="<?=Html::encode($val->messageContent)?>">
                   <?=StringHelper::cutStr(Html::encode($val->messageContent),45)?>
               </span>
            </p>
            <?php if(isset($val->messageType) && $val->messageType != 507009){?>
            <?php if(isset($val->objectID) && $val->objectID != 0){?>
                <a class="btn w140 bg_blue notice_send_btn seebtnJs" href="<?php echo url('teacher/message/is-read',array('messageID'=>$val->messageID,'messageType'=>$val->messageType,'objectID'=>$val->objectID))?>">
                    <?php if($val->messageType == 507003){?>
                        前去批改
                    <?php }elseif($val->messageType == 507004){?>
                        前去批改
                    <?php }elseif($val->messageType == 507403){?>
                        查看答题情况
                    <?php }elseif($val->messageType == 507404){?>
                        去完善
                    <?php }elseif($val->messageType == 507005){?>
                        去查看
                    <?php }?>
                </a>
            <?php }}?>
            <p class="font12 gray_d">发件人：<?php echo $val->sentName;?></p>
            <em class="notice_time gray_d font12"><?php echo $val->sentTime;?></em>
            <em class="crossDelBtn hide" val="<?php echo $val->messageID;?>"></em>
        </li>
    <?php }
    else:
        ViewHelper::emptyView();
    endif; ?>
</ul>

    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(
           'pagination'=>$pages,
            'updateId' => '#notice',
            'maxButtonCount' => 5
        )
    );
    ?>


<script>
    //删除单条消息
    $(function(){
        $(".crossDelBtn").click(function(){
            if (!confirm('确定要删除吗？')) return false;
            var messageID = $(this).attr('val');
            var _this = $(this);
            $.get("<?php echo url("teacher/message/delete-notice");?>",{messageID:messageID},function(data){
                if(data.success){
                    _this.parents('li').remove();
                }else{
                    popBox.alertBox(data.message);
                }
            });
        });
        $('.content').live('click',function(){
            $(this).css('font-weight','normal');
            $(this).siblings('dt').children('h4').find('i').css('background-position','-129px -372px');
        })


    })

</script>
