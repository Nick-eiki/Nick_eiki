<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-27
 * Time: 上午11:11
 */
use frontend\models\ExamModel;
use yii\helpers\ArrayHelper;

?>
<form id="form_edit">
    <ul class="form_list">
        <li>
            <div class="formL">
                <label><i></i>短信标题：<input type="hidden" name="HomeContactForm[id]" value="<?php echo $id;?>" ></label>
            </div>
            <div class="formR">
                <input type="text" class="text" name="HomeContactForm[title]" value="<?php echo $model->title;?>" data-validation-engine="validate[required,maxSize[30]]">
            </div>
        </li>
        <li>
            <div class="formL">
                <label><i></i>收件人：</label>
            </div>
            <div class="formR" style="width: 410px;">
                <?php
                 echo Html::dropDownList('HomeContactForm[classId]',
                    $model->classId,
                    ArrayHelper::map($schoolClass, 'classID', 'className'),
                    array("prompt" => "请选择","id"=>"HomeContactForm_classId2",'onchange' => "return getClassIds()",
                        "defaultValue" => false,
                        'data-prompt-target' => "edit_prompt",
                        'data-prompt-position' => "inline",
                        'data-errormessage-value-missing' => "班级不能为空",
                       ));
                ?>
                <span id="edit_prompt"></span>
                <?php
                echo Html::dropDownList('HomeContactForm[scope]',
                    $model->scope,array('部分学生','全部学生'),array("prompt" => "请选择","class"=>"contact_select",'id'=>'student_scope',
                        'data-validation-engine' => 'validate[required]', "defaultValue" => false,
                        'data-prompt-target' => "county_student",
                        'data-prompt-position' => "inline",
                        'data-errormessage-value-missing' => "请选择学生")
                );
                ?>
                <button type="button" class="selectForJs" id="selectForJs">指定人</button>
                <ul class="stu_sel_list clearfix">
                    <?php foreach($model->receivers as $key=>$item){
                        ?>
                    <li>
                       <?php echo $item->stuID;?> <?php echo $item->receiverName?>
                        <i class="close_icon" >
                            <input type="hidden" name="receiver[]" value="<?php echo $item->receiverId;?>">
                        </i>
                    </li>
                    <?php   } ?>
                </ul>
            </div>
        </li>
        <li>
            <div class="formL">
                <label><i></i>收件人身份：</label>
            </div>
            <div class="formR" id="receiverType">
                <?php
                $arrType = explode(',',$model->receiverType);
                echo Html::checkboxList('HomeContactForm[receiverType]',$arrType,
                array('1'=>'学生','2'=>'家长'),array('data-validation-engine' => 'validate[minCheckbox[1]]','data-prompt-position' => "inline",'separator'=>'&nbsp;')
              ); ?>
            </div>
        </li>
        <li>
            <div class="formL">
                <label><i></i>发送方式：</label>
            </div>
            <div class="formR" >
                <?php
                $sendWay = explode(',',$model->sendWay);
                echo Html::checkboxList('HomeContactForm[sendWay]',$sendWay,
                    array('2'=>'站内信','1'=>'短信'),array('data-validation-engine' => 'validate[minCheckbox[1]]','separator'=>'&nbsp;')); ?>
            </div>
        </li>
        <li>
            <div class="formL">
                <label><i></i>相关性：</label>
            </div>
            <div class="formR reference">
                	<span class="radio suggestion">
                    	<input type="radio" value="1" class="" name="HomeContactForm[reference]" <?php echo $model->reference ==1 ? 'checked':'' ?>>
                    	<label>考试反馈</label>
                    </span>
                    <span class="radio expression">
                    	<input type="radio" value="2" class="" name="HomeContactForm[reference]" <?php echo $model->reference ==2 ? 'checked':'' ?>>
                    	<label>日常表现</label>
                    </span>

                    <span class="radio work">
                    	<input type="radio" value="3" class="" name="HomeContactForm[reference]" <?php echo $model->reference ==3 ? 'checked':'' ?>>
                    	<label>通知</label>
                    </span>
                    <span class="radio work">
                    	<input type="radio" value="4" class="" name="HomeContactForm[reference]" <?php echo $model->reference ==4 ? 'checked':'' ?>>
                    	<label>作业</label>
                    </span>

            </div>
        </li>
        <li class="name <?php echo $model->reference ==1 ? '' : 'hide' ?>">
            <div class="formL">
                <label><i></i>短信内容：</label>
            </div>
            <div class="formR">
                <p>考试名称:
                    <?php
                    echo Html::dropDownList('HomeContactForm[examId]', $model->examId,
                        ArrayHelper::map(ExamModel::model()->getDate($model->classId,$userId),'examID','examName'),
                        array( "prompt" => "请选择", "defaultValue" => false,
                            "id" =>'HomeContactForm_examId2',
                            'data-validation-engine' => 'validate[required]',
                            'data-prompt-target' => "edit_examId",
                            'data-prompt-position' => "inline",
                            'data-errormessage-value-missing' => "考试名称不能为空"
                        ));
                    ?>

                </p>
                <span id="edit_examId"></span>
                <p>
                    <input type="checkbox" <?php echo ($model->rankingChg==1)?'checked':'' ?>
                     class="checkbox" value="1" name="HomeContactForm[rankingChg]" id="edit_rankingChg">

                    <label>本班整体名次及变化<span class="red">(最高分<i id="max"><?php echo $maxMinScore ==null? '':$maxMinScore->max;?></i>&nbsp;&nbsp;&nbsp;最低分<i id="min"><?php echo $maxMinScore==null?'':$maxMinScore->min;?></i>)</span></label>
                </p>
                <p>
                    <input type="checkbox" class="checkbox score_levelBtn" <?php echo (!empty($model->ranks))?'checked':'' ?> id="edit_scores">
                    <label>分数段人数分布</label>
                </p>
                <div class="score_level <?php echo(!empty($model->ranks))?'':'hide'  ?>">
                <div class="scoreRows">
                   <?php
                   foreach($model->ranks as $key=>$val){?>
                       <p class="row"><input type="text" class="text"  name="line[<?php echo $key ?>][low]" style="width:30px" value="<?php echo $val->low;?>" readonly="readonly">分&nbsp;&nbsp;至&nbsp;&nbsp;
                           <input type="text" class="text" name="line[<?php echo $key ?>][high]" style="width:30px" value="<?php echo $val->high;?>" readonly="readonly">分
                       </p>
              <?php     }?>

                </div>
                    <div><button type="button" class="btn20 bg_green tmpBtn hide">确定</button>
                        <button type="button" class="bg_green add_score_levelBtn hide">+分数段</button>
                        <button type="button" class="btn20 bg_red_l resetScoreBtn <?php echo(!empty($model->ranks))?'':'hide'  ?>"">全部重置</button>
                    </div>
                </div>
                <p>

                    <input type="checkbox" class="checkbox" name="HomeContactForm[weakPoint]" value="1"  <?php echo ($model->weakPoint==1)?'checked':'' ?> id="edit_weakPoint">
                    <label>知识盲点</label>
                </p>
            </div>
        </li>
        <li class="expression_li <?php echo $model->reference ==2 ? '' : 'hide' ?>">
            <div class="formL">
                <label><i></i>知识盲点:</label>
            </div>
            <div class="formR">
                <div class="treeParent">
                    <?php  echo $this->render('_subject_view',array('result'=>$result->classList,'subjectId'=>$model->subjectId))?>
                    <br>
                    <div class="treeParent">
                        <button id="editBtns" type="button" class="btn <?php echo !empty($model->kids) ? '' : 'hide' ?>  editBtn editKids">知识点</button>
                        <div class="pointArea <?php echo !empty($model->kids) ? '' : 'hide' ?>">
                            <input class="hidVal" type="hidden" value="<?php echo $model->kids; ?>" name="HomeContactForm[kids]">
                            <h6>已选中知识点:</h6>
                            <ul class="labelList">

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="formL">
                <label>补充内容：</label>
            </div>
            <div class="formR">
                <textarea name="HomeContactForm[addContent]"><?php echo $model->addContent;?></textarea>
            </div>
        </li>
        <li>
            <div class="formL">
                <label></label>
            </div>
            <div class="formR">
                <div class="up_pic">
                    <p class="addPic btn"><a href="javascript:" class="id_btn btn">修改图片</a>
                        <input id="ytedit" type="hidden" value="" name="XUploadForm[file]">
                        <input id="editFile" class="file" name="XUploadForm[file]" type="file">
                    </p>
                </div>
            </div>
        </li>
        <li>
            <div class="formL">
                <label></label>
            </div>

            <div class="formR">
                  <ul class="addUl clearfix" id="editimg">
                    <?php
                    if($model->urls){
                        $url = explode(",", $model->urls);
                        foreach ($url as $v) {
                            ?>
                            <li><img src="<?php echo publicResources() . $v; ?>" /> <input type="hidden"  value="<?php echo $v;?>" name="HomeContactForm[urls][]"/><i></i></li>
                                 <?php } }?>
                </ul>

            </div>


        </li>
    </ul>
</form>

<script type="text/javascript">
(function(){
    //+分数段
    var lowest=<?php echo $maxMinScore==null?0:$maxMinScore->min;?>;
    var highest=<?php echo $maxMinScore==null?0:$maxMinScore->max;?>;
    var fullScore=100;
    var tmp='';
    var addsize= $('.score_level p').size();
    $('#HomeContactForm_examId2').change(function(){
        var examId =  $('#HomeContactForm_examId2').val();
        $.post("<?php echo url('teacher/message/scores')?>",{examId:examId},function(data){
            if(data.success){
                $('#min').html(data.data.min);
                $('#max').html(data.data.max);
                lowest = data.data.min;
                highest = data.data.max;
            }else{
                popBox.errorBox(data.message);
            }
        })
    });
    $('.score_levelBtn').click(function(){
        $('.defaultScore').show();
        var examId2 = $('#HomeContactForm_examId2').val();
        if(examId2 ==''){
            popBox.errorBox('请选择考试名称');
            return false;
        }
        if($(this).is(':checked')==true){
            var low=lowest;
            if(highest<fullScore){
                for(var i=0.2; i<highest/fullScore; i+=0.2){
                    $('.defaultScore').prepend('<p><input type="hidden" name="line[' + addsize + '][low]" value="'+low+'">'+low+'分 ---- '+parseInt(fullScore*i)+'分</p><input type="hidden" type="hidden" name="line[' + addsize + '][high]" value="'+parseInt(fullScore*i)+'">');

                    low=parseInt(fullScore*i+1);
                    addsize++;
                }
                $('.defaultScore').prepend('<p><input type="hidden" name="line[' + addsize + '][low]" value="'+low+'">'+low+'分 ---- '+parseInt(highest)+'分<input type="hidden" type="hidden" name="line[' + addsize + '][high]" value="'+parseInt(highest)+'">');
            }
        }
        else{
            $('.defaultScore p,.defaultScore input').remove();
            $('.defaultScore,.score_level').hide();
        }
    });

    $('.restDefaultScoreBtnJS').click(function(){
        $('.defaultScore').hide();
        $('.score_level').show();
        $('.tmpBtn,.resetScoreBtn').show();
        $('.score_level .scoreRows').append('<p class="row"><input type="text" class="text" name="line[' + addsize + '][low]" value="'+lowest+'">分&nbsp;&nbsp;至&nbsp;&nbsp;<input type="text" class="text" name="line[' + addsize + '][high]" value="'+highest+'">分</p>');
    });

    $('.add_score_levelBtn').click(function(){
        $(this).hide();
        $('.score_level').show();
        $('.tmpBtn').show();
        tmp=parseInt($('.score_level .row:last input:last').val());
        isNaN(tmp)==true ? tmp=0 : tmp=tmp+1;
        if(tmp<=highest){
            $('.score_level .scoreRows').append('<p class="row"><input type="text" class="text" name="line[' + addsize + '][low]" value="'+tmp+'">分&nbsp;&nbsp;至&nbsp;&nbsp;<input type="text" class="text" name="line[' + addsize + '][high]">分</p>');
           addsize++;
        }
        else{
            popBox.errorBox('超出最高分');
            $(this).hide();
            $('.tmpBtn').hide();
        }


    });
    $('.tmpBtn').click(function(){
        var bgn=$('.score_level .scoreRows .row:last input:first');
        var end=$('.score_level .scoreRows .row:last input:last');
        var bgnVal=bgn.val();
        var endVal=end.val();
        if (isNaN(bgnVal)){
            popBox.errorBox('请输入数字');
            bgn.val('').focus();
        }
        else if (isNaN(endVal)){
            popBox.errorBox('请输入数字');
            end.val('').focus();
        }
        else if(bgnVal=='' || bgnVal<lowest){
            popBox.errorBox('分数必须大于最低分');
            bgn.val('').focus();
        }
        else if(bgnVal<tmp && $('.score_level .scoreRows .row').size()>1 ){
            popBox.errorBox('数值必须大于上一分数段');
            bgn.val('').focus();
        }
        else if(endVal>highest){
            popBox.errorBox('不能大于最高分');
            end.val('').focus();
        }
        else if(endVal<=bgnVal){
            popBox.errorBox('不能为空,且大于起始分,小于最高分');
            end.val('').focus();
        }

        else{
            $('.tmpBtn').hide();
            $('.add_score_levelBtn').show();
            bgn.attr('readonly',true);
            end.attr('readonly',true);
        }
    });

    $('.score_level .scoreRows .row:last input:first').live('blur',function(){
        if (isNaN($(this).val())){
            popBox.errorBox('请输入数字');
            $(this).val('').focus();
        }

    });
    $('.score_level .scoreRows .row:last input:last').live('blur',function(){
        if (isNaN($(this).val())){
            popBox.errorBox('请输入数字');
            $(this).val('').focus();
        }


    });


    //重置分时段
    $('.score_level .resetScoreBtn').click(function(){
        $('.score_level .row').remove();
        $('.tmpBtn').hide();
        $('.add_score_levelBtn').show();
        tmp=0;
    })

})();

</script>

<script type="text/javascript">
    $('#Edit_msg_parent #selectForJs').live('click',function(){
        $('#stuListBox .checkAll').checkAll($('#stuListBox tbody .checkbox'));
        var classId = $('#HomeContactForm_classId2').val();
        if(classId ==""){
            popBox.alertBox('班级不能为空！');
            return false;
        }
        $.post("<?php echo url('teacher/message/getC-class')?>",{classId:classId},function(data){
            $('#stuListBox').html(data);
            $('.stuListBox .checkAll').checkAll($('.stuListBox tbody .checkbox'));
            $("#stuListBox input").attr("checked", false);
            $(".stu_sel_list.clearfix input").each(function(){
               $("#stuListBox input[value='"+  $(this).val()+"']").attr("checked", true);
           });
            $( "#stuListBox" ).dialog( "open" );
        });
    });
    //显示分数段
    $('.score_levelBtn').click(function(){
        if($(this).attr('checked')=="checked"){
            $('.score_level').show();
        }
        else{
            $('.score_level').hide();
            $('.score_level .scoreRows').empty();
            $('.score_level .tmpBtn').hide();
            $('.score_level .add_score_levelBtn').show();
        }
    });
    $('#form_edit').validationEngine({
        promptPosition:"centerRight",
        maxErrorsPerField:1,
        showOneMessage:true,
        addSuccessCssClassToField:'ok'
    });

    //选择全部学生隐藏指定人按钮
      $('.contact_select').change(function(){
        var    select_change =$('#student_scope').val();
        if(select_change=='1')
        {
            $('.selectForJs').hide();
        }
        else
        {
            $('.selectForJs').show();
        }
    })

</script>
<script>
    $(function () {

        //课时安排-----知识树/章节
        var treeCls;//  true:知识树 false:章节

        function check(btn) {//是否已经选中,通过btn找到相邻元素
            var pa = btn.parent('.treeParent');
            var checkLi = pa.find('li');
            if (checkLi.length > 0) {
                return true;
            }
        }

        function clear(btn) {//清除已经选中,的通过btn找到相邻元素
            var pa = btn.parent('.treeParent');
            pa.find('.pointArea').hide();
            pa.find('.labelList').empty();
            pa.find('.hidVal').val('');
        }

        $('.treeParent .pointRadio').live('click', function () { //知识点radio
            $('#editBtns').show();
            if (check($(this)) && treeCls == true)    return true;
            else clear($(this));
            treeCls = true;
        });

        $('#form_edit .editBtn').live('click', function () {//编辑
            var subjectID =$("#form_edit .treeParent input[name='HomeContactForm[subjectId]']:checked").val();
            var departmentId = $("#form_edit .treeParent input[name='HomeContactForm[subjectId]']:checked").attr('department');
            var _this =$(this);
            $.post("<?php echo url('ajaxteacher/get-knowledge-by-department-id')?>",{subjectID:subjectID,departmentId:departmentId},function(data){
                if(data.success){
                    popBox.pointTree(data.data,_this);
                }
            });

        });


            var subjectID =$("#form_edit .treeParent input[name='HomeContactForm[subjectId]']:checked").val();
            var departmentId = $("#form_edit .treeParent input[name='HomeContactForm[subjectId]']:checked").attr('department');
            $.post("<?php echo url('ajaxteacher/get-knowledge-by-department-id')?>",{subjectID:subjectID,departmentId:departmentId},function(data){
                if(data.success){
                    popBox.pointTree2(data.data,$('#form_edit .editKids'));
                }
            });
    })
</script>
<script>
    $(function(){
        $('.suggestion input').click(function(){
            $('.expression_li').hide();
            $('.name').show();
        });
        $('.expression input').click(function(){
            $('.name').hide();
            $('.expression_li').show();
        });
        $('.work input').click(function(){

            $('.expression_li').hide();
            $('.name').hide();

        })

    })

</script>
<script>

    /**删除添加的图片*/
    $('#Edit_msg_parent .addUl  li i').live('click', function () {
        $(this).parent().remove();
    });

   jQuery('#editFile').fileupload({'acceptFileTypes':/(\.|\/)(gif|jpe?g|png)$/i,'done':editDone,'processfail':function (e, data) {var index = data.index,file = data.files[index]; if (file.error) {alert(file.error);}},'url':'/upload/header','autoUpload':true,'formData':{},'dataType':'json','maxNumberOfFiles':1});
</script>
<script>
    var getClassIds=function(){
        var classId = $('#HomeContactForm_classId2').val();
        $.post("<?php echo url('ajax/get-exam')?>",{id:classId},function(html){
            jQuery("#HomeContactForm_examId2").html(html);
        });
        $.post("<?php echo url('teacher/message/get-subject-data')?>",{id:classId},function(data){
            jQuery("#HomeContactForm_subjectId").html(data);
        })

    }
</script>

