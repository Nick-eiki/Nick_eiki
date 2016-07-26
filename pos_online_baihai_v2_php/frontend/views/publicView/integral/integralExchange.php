<?php
/**
 * Created by wangchunlei
 * User: mahongru
 * Date: 2015/9/11
 * Time: 10:00
 */

use yii\helpers\Url;

$this->title='积分兑换';
?>

<script type="text/javascript">

    $(function(){


        $('.popBox').dialog({
            autoOpen: false,
            width: 720,
            modal: true,
            resizable: false,
            close: function() { $(this).dialog("close") }
        });
        /*去兑换弹框*/
        $('.ex').click(function() {
            _this = $(this);
            var content = _this.parent().parent().attr('data-content');
            var content_id = _this.parent().parent().attr('data-content-id');
            var id =  _this.parent().parent().attr('data-id');
            $('#showContent').html(content);
            $('#showJf').html(content_id);

            $('#goodsInfo').val(id);
            $(".score_rule_box").dialog("open");
        });

        //确定兑换
        $('.okBtn').click(function(){
            var goodsId = $('#goodsInfo').val();
            $.post('<?= Url::to('/ajax/jf-exchange') ?>',{'goodsId':goodsId},function(data){

                if(data.success){
                    popBox.successBox(data.message);
                    location.reload();
                }else{
                    popBox.errorBox(data.message);
                }

            });
        });
    })
</script>
<!--主体-->
            <div class="grid_18 main_r mainbox_r">
                <div class="main_cont score_library">
                    <div class="title">
                        <h4>我的积分</h4>
                    </div>
                    <div class="form_list no_padding_form_list">
                        <div class="row">
                            <div class="formR">
                                <ul class="resultList">
                                    <?php if($user->type==1){?>
                                        <li ><a href="<?=Url::to(['/teacher/integral/income-details'])?>">收入明细</a></li>
                                        <li ><a href="<?=Url::to(['/teacher/integral/my-ranking'])?>">我的等级</a></li>
                                        <li class="ac"><a href="<?=Url(['/teacher/integral/integral-exchange'])?>">积分兑换</a></li>
                                    <?php }else{?>
                                        <li ><a href="<?=Url::to(['/student/integral/income-details'])?>">收入明细</a></li>
                                        <li ><a href="<?=Url::to(['/student/integral/my-ranking'])?>">我的等级</a></li>
                                        <li class="ac"><a href="<?=Url(['/student/integral/integral-exchange'])?>">积分兑换</a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="tab my_pointsbox">
                        <div class="title item_title noBorder score_title">
                        </div>
                        <div class="score_table">
                            <table cellpadding="0" cellspacing="0" border="0"  style=" border-collapse: inherit">
                                <tr>
                                    <th width="148px">序号</th>
                                    <th width="146px">礼品名称</th>
                                    <th width="350px">所需积分</th>
                                </tr>
                                <?php if(!empty($goods)){?>
                                    <?php foreach($goods as $key=>$val){?>
                                        <tr data-content="<?=$val->name?>" data-content-id="<?=$val->points?>" data-id="1">
                                            <td><?=$key+1?></td>
                                            <td><?=$val->name?></td>
                                            <td>
                                                <?=$val->points?>
                                                <a class="ex">去兑换</a>
                                            </td>
                                        </tr>
                                    <?php }?>
                                <?php }?>
                            </table>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
    <!--主体end-->
<!--去兑换弹窗-->
<div class="popBox score_rule_box" title="系统提醒">
    <input  type="hidden" value="" id="goodsInfo"/>
    <div class="popCont">
        <div class="score_table">
        <div class="ex_goods">
            您选择兑换的物品为<span class="res" id="showContent">小米手环</span>，需要消耗<span class="number" id="showJf">5000</span>积分
            。<br>点击下方"确定兑换"后，会有工作人员跟您联系,为您发放礼品
        </div>
        </div>
    </div>
    <div class="popBtnArea exBtn">
        <button type="button" class="okBtn" value="1">确定兑换</button>
        <button type="button" class="cancelBtn">取消</button>
    </div>
</div>