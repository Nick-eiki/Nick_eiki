
<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-3
 * Time: 下午3:34
 */
use frontend\components\CHtmlExt;
use yii\helpers\ArrayHelper;

?>

<form id="form_id">

    <div class="popCont">
        <div class="new_tch_group">
            <div class="form_list">
                <div class="row">
                    <div class="formL">
                        <label>通知标题：</label>
                    </div>
                    <div class="formR" style="position: relative">
                        <input  id="text_gray" type="text" class="text" name="HomeContactForm[title]"
                               data-validation-engine="validate[required,maxSize[30]]" data-prompt-position = "inline" data-prompt-target = "title_prompt">
                        <span id="title_prompt" class="errorTxt" style="left: 386px;"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label>收件人：</label>
                    </div>
                    <div class="formR" style="position: relative">
                            <span class="selectWrap big_sel">
                                <?= CHtmlExt::dropDownListCustomize("HomeContactForm[classId]", "", ArrayHelper::map($schoolClass, 'classID', 'className'),
                                    [
                                        "id" => 'HomeContactForm_classId',

                                        'data-validation-engine' => 'validate[required]',
                                        'data-prompt-target' => "class_prompt",
                                        'data-prompt-position' => "inline",
                                        'data-errormessage-value-missing' => "班级不能为空"
                                    ]
                                );
                                ?>
                                <span id="class_prompt" class="errorTxt" style="left: 386px;"></span>
                            </span>

                            <span class="selectWrap big_sel">
                            <?php echo CHtmlExt::dropDownListCustomize('HomeContactForm[scope]', '', ['部分学生', '全部学生'],
                                [
                                    "defaultValue" => true,
//                                    "prompt" => "全部学生",
                                    'class' => "popSelect"
                                ]
                            );
                            ?>
                            </span>
                        <button type="button" class="w100 bg_green choose_stu_Js btn40 selectForJs">选择学生</button>
                        <ul class="choose_stu_list" id="choose_stu_list">
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="formL" style="position: relative">
                        <label>收件人身份：</label>
                    </div>
                    <div class="formR personal">
                        <input type="checkbox" class="hide chked" id="ch1" name="HomeContactForm[receiverType][]"
                               value="1" data-validation-engine="validate[minCheckbox[1]]" data-prompt-position = "inline" data-prompt-target = "receiverType">
                        <label class="chkLabel stu" for="ch1">学生</label>
                        <input type="checkbox" class="hide chked" id="ch2" name="HomeContactForm[receiverType][]"
                               value="2" data-validation-engine="validate[minCheckbox[1]]" data-prompt-position = "inline" data-prompt-target = "receiverType">
                        <label class="chkLabel " for="ch2">家长</label>
                        <span id="receiverType" class="errorTxt" style="left: 190px;top:10px;"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label>发送方式：</label>
                    </div>
                    <div class="formR personal">
                        <input type="checkbox" class="hide chked" id="ch3" name="HomeContactForm[sendWay][]" value="2"
                               data-validation-engine="validate[minCheckbox[1]]" data-prompt-position = "inline" data-prompt-target = "minCheckbox">
                        <label class="chkLabel stuZ" title="发送站内消息" for="ch3">站内信</label>
<!--                        <input type="checkbox" class="hide chked" id="ch4" name="HomeContactForm[sendWay][]" value="1"-->
<!--                               data-validation-engine="validate[minCheckbox[1]]" data-prompt-position = "inline" data-prompt-target = "minCheckbox">-->
<!--                        <label class="chkLabel" title="将短信发送到您的手机" for="ch4">短信</label>-->
                        <span id="minCheckbox" class="errorTxt" style="left: 80px;top:10px;"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label>通知内容：</label>
                    </div>
                    <div class="formR" style="position: relative">
                        <textarea style="width: 370px;" class="textarea" name="HomeContactForm[addContent]"
                                  id="content"  data-validation-engine="validate[required,maxSize[300]]" data-prompt-position = "inline" data-prompt-target = "content_prompt"></textarea>
                        <span id="content_prompt" class="errorTxt" style="left: 386px;top:70px;"></span>
                    </div>
                </div>


                <div class="row">
                    <div class="formL">
                        <label></label>
                    </div>
                    <div class="formR personal">

                        <span class="addPic">
                            <button type="button" class="bg_green btn w140 btn40 up_pic_Js">添加图片</button>

                            <input id="ytt1" type="hidden" value="" name="XUploadForm[file]">
                            <input id="t1" class="file" name="XUploadForm[file]" multiple="multiple" type="file">
                        </span>
                        <ul class="up_test_list clearfix" id="addimage">

                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="popBtnArea">
        <button type="button" class="okBtn deposit" btn_id="send">立即发送</button>
        <button type="button" class="cancelBtn deposit" btn_id="preservation">保存</button>
        <button type="button" class="cancelBtn BtnCancel">取消</button>
    </div>
</form>


<script>
    jQuery('#t1').fileupload({'acceptFileTypes': /(\.|\/)(gif|jpe?g|png)$/i, 'done': addDone, 'processfail': function (e, data) {
        var index = data.index, file = data.files[index];
        if (file.error) {
            alert(file.error);
        }
    }, 'url': '/upload/paper','multiple': true,'autoUpload': true, 'formData': {}, 'dataType': 'json', 'maxNumberOfFiles': 1});


    $(function () {
        $('#text_gray').placeholder({value:'请输入标题名称'} );
        $('#content').placeholder({value:'请输入通知内容',top:25} );

        $('.stu').click(function () {
            if($('.stu').hasClass('chkLabel_ac'))
            {
                $('#ch3').attr("checked",false);
                $('.stuZ').removeClass('chkLabel_ac');
            }else{
                $('#ch3').attr("checked",true);
                $('.stuZ').addClass('chkLabel_ac');
            }
        });


//添加指定人到列表


//删除已选学生
        $('.stu_sel_list li i').live('click', function () {
            $(this).parent().remove();
        });

        $('#pushNotice .selectForJs').click(function () {
//            $('.stuListBox .checkAll').checkAll($('#stuListBox tbody .checkbox'));
            var classId = $('#HomeContactForm_classId').val();
            if (classId == "") {
                popBox.errorBox('班级不能为空！');
                return false;
            }
            $.post("<?php echo url('teacher/message/new-get-class')?>", {classId: classId}, function (data) {
                $('#chooseStu').html(data);
                var selList=$('#choose_stu_list li');
                var _len = selList.length;
                if(_len>0){
                    $('#chooseStu li').removeClass('ac');
                    for(var i=0; i<_len; i++){
                        var _userId = selList.eq(i).attr('data_user');
                        $( '#chooseStu li[data_user="' + _userId + '"]').addClass('ac');
                    }
                }
                $("#chooseStu").dialog("open");
                return false;
            })
        });

        //选择全部学生隐藏指定人按钮
        $('.contact_select').change(function () {
            var select_change = $('#HomeContactForm_scope').val();
            if (select_change == '1') {
                $('.selectForJs').hide();
            }
            else {
                $('.selectForJs').show();
            }
        });
        //删除添加的指定人
        $('.btn_js').live('click', function () {
            $(this).parent('p').remove();
        });
        var weakPoint = $("#rankingChg input[name='HomeContactForm[rankingChg]']:checked");
        $('#form_id').validationEngine({
            promptPosition: "centerRight",
            maxErrorsPerField: 1,
            showOneMessage: true,
            addSuccessCssClassToField: 'ok',
            validateNonVisibleFields: true
        })

    })


</script>

<script>
    $(function () {



        $('.suggestion input').click(function () {
            $('.expression_li').hide();
            $('.name').show();
        });
        $('.expression input').click(function () {
            $('.name').hide();
            $('.expression_li').show();
        });
        $('.work input').click(function () {

            $('.expression_li').hide();
            $('.name').hide();

        });
        $('.addUl li i').hide();

        $('.addUl li').mouseover(function () {
            $(this).children('i').show();
        });
        $('.addUl li').mouseout(function () {
            $(this).children('i').hide();
        });
        $('.addUl li i').live('click', function () {
            $(this).parent('li').remove();
        });
        //全部学生
        $('.popSelect').change(function () {

            if ($(this).val() == 1) {
                $('.choose_stu_Js').hide();
                $('#choose_stu_list').children('li').remove();
            }
            else {
                $('.choose_stu_Js').show();
            }

        });
        //回显选中的名单
        $('#okBtn').live('click',function(){
                 var _selLi = $('#multi_resultList .ac');
            var html = '';
            for(var i=0, _len=_selLi.length; i<_len; i++){
                var _curEl = _selLi.eq(i);
                html += '<li data_user="' + _curEl.attr('data_user') + '" class="multiLi" ><p>'+ _selLi.eq(i).html()  +'</p><span class="delBtn"><input type="hidden" name="receiver[]" value="' +_curEl.attr('data_user') + '"></span></li>';
            }
            $('#choose_stu_list').html(html);
        });



    });
   //2015-4-3 新添加功能


</script>
