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
use yii\helpers\Url;

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
        });

        //家校联系通知
        $('.read_mark_btn').click(function(){
            var _this = $(this);
            var messageid = $(this).attr('messageid');
            $.post('<?=url("student/message/only-is-read");?>',{messageid:messageid},function(data){
                if(data.success){
                    _this.parent().find('.seebtnJs').css('font-weight','normal');
                    _this.remove()
                }else{
                    popBox.errorBox(data.message);
                }
            });


        });
    })

</script>

<ul class="notice_list">
    <?php if($model->list):
        foreach($model->list as $val){ ?>
        <?php if(isset($val->messageType) && $val->messageType != '507201'){?>
            <li>
                <p class="font16 <?php if($val->isRead == '0'){ echo 'no_see';}?>">
                    <span title="<?=Html::encode($val->messageContent)?>"><?=StringHelper::cutStr(Html::encode($val->messageContent),45)?></span>
                </p>
                    <?php if(isset($val->messageType) && $val->messageType != '507201'){?>
                        <?php if(isset($val->objectID) && $val->objectID != 0){?>
                        <a class="btn w120 bg_blue notice_send_btn seebtnJs" href="<?php echo Url::to(['/classes/managetask/details', 'classId'=>$classId ,'relId' => $val->objectID]);?>">
                            <?php if($val->messageType == 507001){?>
                                前去完成
                            <?php }elseif($val->messageType == 507402){?>
                                前去完成
                            <?php }elseif($val->messageType == 507202){?>
                                查看详情
                            <?php }elseif($val->messageType == 507401){?>
                                前去完成
                            <?php }elseif($val->messageType == 507203){?>
                                查看详情
                            <?php }elseif($val->messageType == 507204){?>
                                查看详情
                            <?php }elseif($val->messageType == 507205){?>
                                查看排名变化
                            <?php }?>
                        </a>
                    <?php }?>
                    <?php }?>
                <p class="font12 gray_d">发件人：<?php echo $val->sentName;?></p>
                <em class="notice_time gray_d font12"><?php echo $val->sentTime;?></em>
                <em class="crossDelBtn hide" val="<?php echo $val->messageID;?>"></em>
            </li>
        <?php }else{?>
            <li>
                <p class="font16 <?php if($val->isRead == '0'){ echo 'no_see';}?>">
                    <a href="#" class="seebtnJs"><?= Html::encode($val->messageTiltle);?></a>
                </p>
                <p class="font12 gray_d">发件人：<?php echo $val->sentName;?></p>
                <p><em>通知内容:</em><?=Html::encode($val->messageContent); ?></p>
                <?php if(!empty($val->url)){ ?>
                    <div class="QA_cont_imgBox">
                        <?php $img = explode(',',$val->url);
                        foreach($img as $v){
                            ?>
                            <a class="fancybox" href="<?php echo publicResources() . $v; ?>" data-fancybox-group="gallery_<?= $val->messageID; ?>">
                            	<img src="<?php echo publicResources().$v;?>" width="160" height="120" alt=""/>
                            </a>
                        <?php     } ?>
                    </div>
                <?php } ?>
                <em class="notice_time gray_d font12"><?php echo $val->sentTime;?></em>
                <em class="crossDelBtn hide" val="<?php echo $val->messageID;?>"></em>
                <?php if($val->isRead == '0'){ ?>
                <a href="javascript:;" class="btn w120 bg_blue notice_send_btn read_mark_btn" messageid="<?php echo $val->messageID;?>">标记为已读</a>
                <?php }?>
            </li>
        <?php }?>
    <?php }

    else:
        ViewHelper::emptyView();
    endif;
    ?>


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
            $.get("<?php echo url("student/message/delete-notice");?>",{messageID:messageID},function(data){
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