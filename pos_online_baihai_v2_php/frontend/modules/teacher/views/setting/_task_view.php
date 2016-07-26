<?php
/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 2015/6/25
 * Time: 11:25
 */
use common\helper\DateTimeHelper;
use frontend\components\helper\ViewHelper;
use frontend\components\WebDataCache;
use yii\helpers\Html;

?>
<script type="text/javascript">
    $(function(){
        //添加作业内容
        $('#popBox').dialog({
            autoOpen: false,
            width:500,
            modal: true,
            resizable:false,
            close:function(){$(this).dialog("close")}
        });

        $('#popBox1').dialog({
            autoOpen: false,
            width:720,
            modal: true,
            resizable:false,
            close:function(){$(this).dialog("close")}
        });



        $('.notice').click(function(){
            var _this = $(this);

            var homeworkId = _this.attr('data-id');
            var name = _this.attr('data-content');
            var getType = _this.attr('data-type');

            if( getType =='' ){

                $( "#popBox" ).dialog( "open" );
                $('#name').text(name);
                $('#upUrl').attr('href',"<?= url('/teacher/managetask/new-update-work');?>"+"?homeworkid="+homeworkId);
                $('#orgUrl').attr('href',"<?= url('/teacher/managetask/new-preview-organize-paper');?>"+"?homeworkid="+homeworkId);

            }else{
                $.post('<?=url('/teacher/managetask/get-class-box');?>',{homeworkid:homeworkId},function(data){
                    $('#getClassBox').html(data);
                    $( "#popBox1" ).dialog( "open" );
                });

            }

        })

    })
</script>
<ul class="itemList sup_ul">
    <?php
    /** @var common\models\pos\SeHomeworkTeacher[] $homeworkList */
    if(empty($homeworkList)){
	    ViewHelper::emptyView();
    }else{
    foreach ($homeworkList as $val) {

    ?>
    <li class="clearfix sup_li add_list">
        <div class="item_title noBorder sup_l fl">
            <h4><a href="<?= $val->getType==1? url('teacher/managetask/organize-work-details-new',['homeworkid'=>$val->id]):url('teacher/managetask/new-update-work-detail',['homeworkid'=>$val->id]);?>"><?php if($val->getType == '1'){echo '<i>[电子]</i>';}elseif($val->getType == '0'){echo '<i>[纸质]</i>';}?><?= Html::encode($val->name); ?></a></h4>
            <dl>
                <dd class="work_reccon">
                    <em>布置记录：</em>
                    <span>
                        <?php  foreach($val->getHomeworkRel()->all() as $item) { ?>
                            <?=WebDataCache::getClassesName($item->classID)?>（截止时间:<?=date("Y-m-d",DateTimeHelper::timestampDiv1000($item->deadlineTime)) ?>）<br>

                        <?php } ?>

                    </span>
                </dd>

            </dl>
        </div>
        <div class="sup_r  fr layou_btn">
            <div class="sup_box">
                <div>
                    <span class="a_button notice w120" data-type="<?= $val->getType?>" data-content="<?= $val->name;?>"  data-id="<?= $val->id;?>">布置作业</span>
                </div>
            </div>

        </div>

    </li>
    <?php }}?>
</ul>

<!--添加作业内容-->
<div id="popBox" class="popBox hide " title="添加作业内容">
    <div class="popCont add_work">
        <div class="conet" id="name">
        </div>
        <div class="conet">
            需要您添加一些作业内容
        </div>
    </div>
    <div class="popBtnArea">
        <a id="upUrl" href="" class="okBtn a_button w80">上传作业</a>
        <a id="orgUrl" href="" class="cancelBtn a_button w80">组织作业</a>
    </div>
</div>

<!--布置作业弹出层-->
<div id="popBox1" class="popBox popBox_hand hide" title="选择班级">
    <div id="getClassBox">

    </div>
</div>

