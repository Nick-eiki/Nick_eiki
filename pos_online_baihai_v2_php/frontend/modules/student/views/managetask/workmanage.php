<?php
/**
 * Created by PhpStorm.
 * User: unizk
 * Date: 2015/4/18
 * Time: 11:01
 */
use frontend\models\dicmodels\SubjectModel;

/* @var $this yii\web\View */  $this->title='作业列表';
?>
<script>
    $(function(){

        $('.sup_ul li').hover(function(){

            $(this).addClass('this');
        },function(){
            $(this).removeClass('this');
        });

        //科目标签搜索
        $('.resultList  li').bind('click',function(){

            var type= $(this).attr('type');
            $.get('<?= url('/student/managetask/work-manage');?>',{type:type,'classid':'<?= app()->request->getQueryParam('classid'); ?>'},function(data){
                $('#update').html(data);
            })
        })
    })

</script>

<div class="grid_19 main_r">
    <div class="main_cont">
        <div class="title">
            <h4>作业管理</h4>
            <!-- <div class="title_r">

                 <button type="button" class="btn40 w120 bg_green">布置作业</button>
             </div>-->
        </div>
        <div class="form_list no_padding_form_list">
            <div class="row">
                <div class="fl">
                    <ul class="resultList " >
                        <li class="ac"><a href="javascript:;">所有</a></li>
                        <?php
                            $subject = SubjectModel::getSubjectByDepartmentCache(loginUser()->getModel(false)->department,1);
                            foreach($subject as $val){
                        ?>
                        <li type="<?=$val->secondCode; ?>"><a href="javascript:;"><?=$val->secondCodeValue; ?></a></li>
                        <?php }?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="sup_box">
            <div id="update">
              <?php
                  echo $this->render("_workmanage_list", array("pages" => $pages, "list" => $list, 'studentNum' => $studentNum,'classId'=>$classId));


              ?>
            </div>
        </div>



    </div>
</div>

<!--主体end-->
