<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-9-12
 * Time: 下午4:43
 */
use frontend\models\dicmodels\SchoolLevelModel;
use frontend\models\dicmodels\SubjectModel;
use frontend\models\dicmodels\TeachingResearchDutyModel;
use frontend\models\IdentityModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var CController $this */
/* @var $this yii\web\View */  $this->title='学校-教师列表';
//给用户加权限

?>
<?php $this->beginBlock('head_html') ?>
<script type="text/javascript">

    $(function () {

        //修改身份
        $('#popBox').dialog({
            autoOpen: false,
            width: 500,
            height: 240,
            modal: true,
            resizable: false,
            close: function () {
                $(this).dialog("close")
            }
        });

        //修改身份
        $('#groupbox').dialog({
            autoOpen: false,
            width: 500,
            height: 240,
            modal: true,
            resizable: false,
            close: function () {
                $(this).dialog("close")
            }
        });

        $('.pop_sch_t').live('click', function () {
            $this = $(this);
            if ($this.attr('data-type') == 'class') {

                var classid = $this.attr('data-user-class');
                var userid = $this.attr('data-user-id');
                $.post('<?=url('school/teacher-class-info') ?>', {userid: userid, classid: classid},
                    function (data) {
                        $('#teacher_in_class').html(data.data.html);
                        $('#identity').val(data.data.identity);
                        $("#popBox").dialog("open");

                        $("#popBox .okBtn").unbind().click(function () {

                            $.post('<?=url('school/upteacherclass') ?>', {
                                userid: userid,
                                classid: classid,
                                identity: $('#identity').val()
                            }, function (data) {
                                if (data.success) {
                                    $this.parent().find('b').html($('#identity').find("option:selected").text());
                                    $("#popBox").dialog("close");
                                } else {
                                    popBox.errorBox('修改失败');
                                }
                            });
                        });

                    });

            } else if ($this.attr('data-type') == 'group') {
                var groupid = $this.attr('data-group-id');
                var userid = $this.attr('data-user-id');

                $.post('<?=url('school/teachergroupinfo') ?>', {userid: userid, groupid: groupid}, function (data) {
                    $('#teacher_in_group').html(data.data.html);
                    $('#duty').val(data.data.duty);
                    $("#groupbox").dialog("open");
                    $("#groupbox .okBtn").unbind().click(function () {
                        $.post('<?=url('school/up-teachergroup') ?>', {
                            userid: userid,
                            groupid: groupid,
                            duty: $('#duty').val()
                        }, function (data) {
                            if (data.success) {
                                $this.parent().find('b').html($('#duty').find("option:selected").text());
                                $("#groupbox").dialog("close");
                            } else {
                                popBox.errorBox('修改失败');
                            }


                        });
                    });
                });
            }
            return false;
        });

        $("#searchTeacher").click(function () {
            var schoolLevel = $("#schoolLevel").val();
            var grade = $("#grade").val();
            var className = $("#className").val();
            var subject = $("#subject").val();
            var teachinggroup = '';
            var teacherName = $('input[name=teacherName]').val();
            $.post("<?php echo app()->request->url;?>", {
                schoolLevel: schoolLevel,
                grade: grade,
                className: className,
                subject: subject,
                teachinggroup: teachinggroup,
                teacherName: teacherName
            }, function (data) {
                $('#teacherList').html(data);
            })
        });
	    $('.teacher_link').click(function(){
		    var teacherId = $(this).attr('teacher');
		    var url = "/School/isOn";
		    $result=true;
		    $.ajax({
			    type:'post',
			    url:url,
			    async:false,
			    data: {
				    teacherId:teacherId
			    },
			    dataType:"json",
			    success:function(data){
				    if(!data.success){
					    popBox.errorBox('不是同组织成员，您没有权限访问！');
					    $result=false;
				    }
			    }
		    });
		    return $result;
	    });
        /*弹窗初始化*/
        $('.popBox').dialog({
            autoOpen: false,
            width: 480,
            modal: true,
            resizable: false,
            close: function() {
                $(this).dialog("close")
            }
        });
        /*导入教师名单弹窗*/
        $('.pushBtnJs').click(function() {
            $("#pushNotice").dialog("open");
            return false;
        });
        /*系统提示弹窗*/
//        $('.name_list_btn').click(function() {
//            $("#pushNotice").dialog("close");
//            $("#system_hints").dialog("open");
//            return false;
//        })
        /*系统弹窗关闭*/
        $('.okBtn').click(function() {
            $("#system_hints").dialog("close");
        });
        done= function (e, data) {
            $.each(data.result, function (index, file) {
                if(file.error){
                    popBox.alertBox(file.error);
                    return false;
                }
                var schoolID=<?=app()->request->getParam("schoolId")?>;
                $.post("<?=url('school/upload-teacher-list')?>",{uploadfile:file.url,schoolID:schoolID},function(result){
                   if(result.success){
                       $("#pushNotice").dialog("close");
            $("#system_hints").dialog("open");
                   }
                })
            });



        };
	    //显示更多任课老师
	    $('.moreClsBtn').click(function(){
		    $('.cls_teacher').show();
		    return false;
	    });
	    $(document).click(function() {
		    $('.cls_teacher').hide();
	    })
    })
    ;
</script>
<?php $this->endBlock('head_html') ?>

<?php $this->beginBlock('foot_html') ?>
<div id="popBox" class="popBox popBox_school_teacher hide" title="修改身份">
    <!--完成答题-->
    <div class="impBox">
        <p id="teacher_in_class"></p>

        <p>
            <?php echo Html::dropDownList('identity', '', IdentityModel::getTeacherIdentity()); ?>
        </p>
    </div>
    <div class="popBtnArea">
        <button type="button" class="okBtn">确定</button>
        <button type="button" class="cancelBtn">取消</button>
    </div>
</div>

<div id="groupbox" class="popBox popBox_school_teacher hide" title="修改身份">
    <!--完成答题-->
    <div class="impBox">
        <p id="teacher_in_group"><em class="teacher_name">教师一</em> 在 <em class="class_name">101班（第54班）</em> 的身份为：</p>

        <p>
            <?php echo Html::dropDownList('duty', '', TeachingResearchDutyModel::model()->getListData()); ?>
        </p>
    </div>

    <div class="popBtnArea">
        <button type="button" class="okBtn">确定</button>
        <button type="button" class="cancelBtn">取消</button>
    </div>
</div>
<?php $this->endBlock('foot_html') ?>


<!--主体内容开始-->

<div class="main_cont">

    <div class="title">
        <h4>本校教师管理</h4>
    </div>

    <div class="scholl_class_cont scholl_teacher_cont">
        <div class="scholl_class_contTop">
            <div class="class_contList clearfix">
                <label>学段：</label>
                    <span class="selectWrap big_sel" style="width:110px">
                    <?php
                    echo frontend\components\CHtmlExt::dropDownListCustomize('schoolLevel', '',
                        ArrayHelper::map(SchoolLevelModel::model()->getDataList(), 'secondCode', 'secondCodeValue'),
                        array('prompt' => '请选择'));
                    ?>
                    </span>&nbsp;&nbsp;<label>学科：</label>
                <span class="selectWrap big_sel" style="width:110px">
                    <?php
                    echo frontend\components\CHtmlExt::dropDownListCustomize('subject',
                        '',
                        SubjectModel::model()->getList(),
                        ["prompt" => "请选择"]);
                    ?>
                        </span>&nbsp;&nbsp;
	            <input type="text" class="text text_Width" name="teacherName" placeholder="请输入教师名称搜索..."/>
	            <button type="button" value="" class="big_searchBtn" id="searchTeacher">确定</button>
                <?php if(loginUser()->isTeacher()){ ?>
                <span><a href="" class="fr blue_d pushBtnJs">导入名单</a></span>
                <?php } ?>
            </div>
        </div>
        <div class="scholl_class_cont_middle" id="teacherList">
            <?php echo $this->render('_teacher_list', array('teacherList' => $teacherList, 'pages' => $pages, 'schoolId' => $schoolId)) ?>
        </div>

    </div>
</div>

<!--主体内容结束-->
<!--导入教师 名单弹窗-->
<div class="popBox hide pushNotice" title="导入教师名单" id="pushNotice">
    <div class="popCont">
        <div class="new_tch_group">
            <p>步骤一：使用Excel编辑名单。</p>
            <p class="download"><a href="<?=url('template/module.xlsx')?>" class="blue mar_left">点击下载Excel模版</a>
            </p>

            <p>步骤二：上传名单，完成添加。</p>
            <a href="javascript:;" class="btn bg_green w120 uploadFileBtn">上传名单
            <?php
            $t1 = new frontend\widgets\xupload\models\XUploadForm;
            echo  \frontend\widgets\xupload\XUploadSimple::widget( array(
                'url' => \Yii::$app->urlManager->createUrl("upload/template"),
                'model' => $t1,
                'attribute' => 'file',
                'autoUpload' => true,
                'multiple' => false,
                    'options' => array(
                'acceptFileTypes' => new \yii\web\JsExpression('/(\.|\/)(xlsx)$/i'),
                "done" => new \yii\web\JsExpression('done'),
                "processfail" => new \yii\web\JsExpression('function (e, data) {var index = data.index,file = data.files[index]; if (file.error) {alert(file.error);}}')

            ),
                'htmlOptions' => array(
                    'id' => 't1',
                )
            ));
            ?>
            </a>
        </div>
    </div>
</div>
<!--系统提示-->
<div class="popBox hide system_hints" title="系统提示" id="system_hints">
    <div class="popCont upload">
        <p class="ac"><i class="success"></i>名单已上传成功，系统正在处理，请耐心等待。</p>
        <p class="hide"><i class="failure"></i> 上传失败</p>
    </div>
    <div class="popBtnArea">
        <button type="button" class="okBtn">确定</button>
    </div>
</div>