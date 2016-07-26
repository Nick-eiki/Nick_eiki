<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 2015/8/19
 * Time: 15:35
 */
use frontend\components\helper\ViewHelper;

$this->title='积分收入明细';
?>
<div class="grid_19 main_r mainbox_r">
    <div class="main_cont score_library">
        <div class="title">
            <h4>我的积分</h4>
        </div>
        <div class="form_list no_padding_form_list">
            <div class="row">
                <div class="formR">
                    <ul class="resultList">
                        <?php if($user->type==1){?>
                        <li class="ac"><a href="<?=Url(['/teacher/integral/income-details'])?>">收入明细</a></li>
                        <li ><a href="<?=Url(['/teacher/integral/my-ranking'])?>">我的等级</a></li>
                            <li ><a href="<?=Url(['/teacher/integral/integral-exchange'])?>">积分兑换</a></li>
                         <?php }else{?>
                            <li class="ac"><a href="<?=Url(['/student/integral/income-details'])?>">收入明细</a></li>
                            <li ><a href="<?=Url(['/student/integral/my-ranking'])?>">我的等级</a></li>
                            <li ><a href="<?=Url(['/student/integral/integral-exchange'])?>">积分兑换</a></li>
                     <?php } ?>

                    </ul>
                </div>
            </div>
        </div>
        <div class="tab my_pointsbox">
            <div class="title item_title noBorder score_title">
                <h5>累计收入积分:<span class="blue_d"><?=$totalPonits?></span></h5>

                <h5 style="float: right">可用积分:<span class="blue_d"><?=$points?></span></h5>
            </div>
            <div id="update">
                <?php echo $this->render("_income_list", array("pages" => $pages, 'model'=>$model,)); ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('.popBox').dialog({
            autoOpen: false,
            width: 720,
            modal: true,
            resizable: false,
            close: function() { $(this).dialog("close") }
        });
        function placeholder(obj,defText) {
            obj.val(defText)
                .css("color","#ccc")
                .focus(function(){
                    if($(this).val()==defText) {
                        $(this).val("").css("color","#333");
                    }
                }).blur(function(){
                    if($(this).val()=="") {
                        $(this).val(defText).css("color","#ccc");
                    }
                });
        }
        placeholder($("#notice_name"),"请输入课题名称");
        placeholder($(".add_txt"),"请输入课题名称");
        /*积分明细弹框*/
        $('.view_link').click(function() {
            $(".score_rule_box").dialog("open");
        });
        $('#popBox1 .okBtn').click(function() {
            $("#popBox1").dialog("close");
            return false;
        });
    });
</script>