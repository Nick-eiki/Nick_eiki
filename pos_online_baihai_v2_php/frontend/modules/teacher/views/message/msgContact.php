<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-22
 * Time: 下午3:11
 */
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
$this->registerCssFile(publicResources_new() . '/js/fancyBox/jquery.fancybox.css');
$this->registerJsFile(publicResources_new() . "/js/fancyBox/jquery.fancybox.js", [ 'position'=>\yii\web\View::POS_HEAD] );
$backend_asset = publicResources_new();
;
$this->registerCssFile($backend_asset . '/js/jqueryfileupload/jquery.fileupload.css');
// The basic File Upload plugin
$this->registerJsFile($backend_asset . '/js/jqueryfileupload/jquery.fileupload.js', ['position'=>View::POS_END]);
//The Iframe Transport is required for browsers without support for XHR file uploads
$this->registerJsFile($backend_asset . '/js/jqueryfileupload/jquery.iframe-transport.js',['position'=>View::POS_END]);
/* @var $this yii\web\View */  $this->title='教师--通知';
?>
<script>
    $(function () {
        $(".fancybox").fancybox();
        /*删除图标显示和隐藏*/
        $('.notice_list li').live('mouseover', function () {
            $(this).children('.crossDelBtn').removeClass('hide');
            $(this).children('.notice_send_btn').addClass('bg_blue_d');
            $(this).addClass('bg_gray_ll');
        });
        $('.notice_list li').live('mouseout', function () {
            $(this).children('.crossDelBtn').addClass('hide');
            $(this).children('.notice_send_btn').removeClass('bg_blue_d');
            $(this).removeClass('bg_gray_ll');
        });
        $('.resultList li').bind('click', function () {
            $(this).siblings('li').removeClass('ac');
            $(this).addClass('ac');
            var type = $(this).attr('type');
            $.post('<?php echo app()->request->url;?>', {type: type}, function (data) {
                $('#listDate').html(data);
            })
        })

    })

</script>

<script>

    reloadurl='<?php echo Url::to(''); ?>';

    $(function () {
        $('#chooseStu').dialog({
            autoOpen: false,
            width: 700,
            modal: true,
            resizable: false,
            close: function () {
                $(this).dialog("close")
            }
        });
//联系家长/编辑短信
        $('#pushNotice').dialog({
            autoOpen: false,
            width: 700,
            modal: true,
            resizable: false
        });
        $('.pushBtnJs').click(function () {
            $.post("<?php echo url('teacher/message/new-open-diaiog')?>", {}, function (data) {
                $('#pushNotice').html(data);
                $("#pushNotice").dialog("open");
            })
        });
        $('.deposit').live('click', function () {
            var sendType = $(this).attr('btn_id');

            var img_num = $('#addimage li').length;
            if(img_num > 6){
                popBox.errorBox('最多上传6张图片');
            }else{
                if ($('#form_id').validationEngine('validate')) {

                    $addFrom = $('#form_id');
                    $.post("<?php echo url('teacher/message/add-contact')?>", $addFrom.serialize() + '&' + $.param({sendType: sendType}), function (data) {
                        if (data.success) {
                            location.href='<?= Url::to('msg-contact')?>';
                        } else {
                            popBox.errorBox(data.message);
                        }
                    })
                }
            }

        });
//删除短信
        $('.crossDelBtn').live('click', function () {

            if (confirm("确定删除吗？？？")) {
              var   _this = $(this);
                var id = _this.attr('delId');
                $.post("<?php echo url('teacher/message/del-hom-msg')?>", {id: id}, function (data) {
                    if (data.success) {
                        _this.parents('li').remove();
                    } else {
                        popBox.errorBox(data.message);
                    }
                })
            }


        });
        //发送短信
        $('#send').live('click', function () {
            _this = $(this);
            var id = _this.attr('sendId');
            $.post("<?php echo url('teacher/message/send-hom-msg')?>", {id: id}, function (html) {
                if (html.success) {
                    popBox.successBox('发送成功！');
                    location.href=reloadurl;
                } else {
                    popBox.errorBox(html.message);
                }
            })
        });
        //关闭弹窗
        $('.chooseStu .popBtnArea .okBtn').live('click', function () {

            $('.sut_list .chkLabel_ac').each(function (index, element) {
                var su_this = $(this).text();
                var val = $(this).attr('for');
                $('.choose_stu_list').append('<li><p>' + su_this + '</p> <input type="hidden" name="receiver[]" value="' + val + '"><span class="delBtn"></span></li>');
            });
            $(".chooseStu").dialog("close");
            return false;
        });


        //取消操作
        $('.BtnCancel').die().live('click',function(){
              $(this).parents('.popBox').dialog( "close" );
        })
    });
    addDone = function (e, data) {
        $.each(data.result, function (index, file) {
            if(file.error){
                popBox.errorBox("图片过大");
             return false;
            }

            $('#addimage').append('<li><img  src="' + file.url + '" alt=""  /><input type="hidden"  value="' + file.url + '" name="HomeContactForm[urls][]"/><span class="delBtn"></span></li>  ');
        });
    };

</script>

<!--top_end-->
<!--主体-->

<div class="grid_19 main_r">
    <div class="main_cont notice">
        <div class="title">
            <h4>通知</h4>

            <div class="title_r"><a class="btn btn40 bg_green w120 pushBtnJs" style="color:white !important">发布通知</a>
            </div>
        </div>
        <ul class="resultList  clearfix">
            <li type="" class="ac"><a>全部通知</a></li>
            <li type="1"><a>已发送</a></li>
            <li type="0"><a>未发送</a></li>
        </ul>
        <div id="listDate">
            <?php echo $this->render('_new_list_view', array('modelList' => $modelList, 'pages' => $pages)); ?>

        </div>
    </div>
</div>

<!--发布通知-->
<div class="popBox hide pushNotice" title="发布通知" id="pushNotice">

</div>

<!--选择学生-->
<div class="popBox hide chooseStu" title="发布通知" id="chooseStu">

</div>


<!--主体end-->


<script>
    var getClassId = function () {
        $('#student li').remove();
        var classId = $('#HomeContactForm_classId').val();
        $.post("<?php echo url('ajax/get-exam')?>", {id: classId}, function (html) {
            jQuery("#HomeContactForm_examId").html(html);
        });
        $.post("<?php echo url('teacher/message/get-subject-data')?>", {id: classId}, function (data) {
            jQuery("#HomeContactForm_subjectId").html(data);
        })

    }

</script>

<?php if(app()->request->getParam("show",'')=='sendwin') { ?>
    <script type="text/javascript">
        $(function () {
            setTimeout(function () {
                $('.pushBtnJs').click();
            }, 1000);
        });
    </script>
<?php } ?>
