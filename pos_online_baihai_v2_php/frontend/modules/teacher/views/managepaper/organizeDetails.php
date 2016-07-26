<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-26
 * Time: 下午4:56
 */
use frontend\components\helper\AreaHelper;
use frontend\models\dicmodels\EditionModel;
use frontend\models\dicmodels\GradeModel;
use frontend\models\dicmodels\KnowledgePointModel;
use frontend\models\dicmodels\SubjectModel;

/* @var $this yii\web\View */  $this->title="测验详情";
$publicResources = Yii::$app->request->baseUrl;
;
$this->registerJsFile($publicResources . '/js/ztree/jquery.ztree.all-3.5.min.js');
$this->registerCssFile($publicResources . '/js/ztree/zTreeStyle/zTreeStyle.css'.RESOURCES_VER);
?>
<div class="currentRight grid_16 push_2 up_work_details">
<div class="noticeH clearfix noticeB">
    <h3><?php echo $data->testName?></h3>
    <div class="new_not fr">
        <?php echo $data->isHaveSummary ? '<a href="javascript:;" class="B_btn120 btn update">修改科目总评</a>' : '            <a href="javascript:;" class="B_btn120 btn t_btn_js">填写科目总评</a>
';?>
    </div>

</div>
<hr>
<div class="work_detais_cent">
<h4><?php echo $data->name?></h4>
<ul class="detais_list">
    <li>
        <span>地区：<i><?php echo AreaHelper::getAreaName($data->provience)?> <?php echo AreaHelper::getAreaName($data->city)?> <?php echo AreaHelper::getAreaName($data->country)?></i></span>&nbsp<span>年级：<i><?php echo GradeModel::model()->getGradeName($data->gradeId)?></i></span>&nbsp<span>科目：<i><?php echo SubjectModel::model()->getSubjectName($data->subjectId)?></i></span>&nbsp<span>版本：<i><?php echo EditionModel::model()->getEditionName($data->version)?></i></span>
    </li>
    <li><span>知识点：<i><?php echo  KnowledgePointModel::findKnowledgeStr($data->knowledgeId)?></i></span></li>
    <li><span>试卷简介：<i><?php echo strip_tags($data->paperDescribe)?></i></span></li>
    <li><span>学生成绩：<i><?php foreach($studentAnswer->scoreList as $v){
                    echo $v->studentName.":".intval($v->testScore)."分"."&nbsp";
                }?></i></span></li>
    <li><span>科目总评：<i><?php echo $evaluateResult->summary;
                ?></i></span></li>
</ul>
<div class="details_b" id="details_b">
<div class="species clearfix">

    <p class="number">提交答案学生：<em><?php echo count($answerResult->answerlist)?>人</em></p>
</div>
<?php foreach($answerResult->answerlist as $v){?>
    <div class="answer">
        <div class="answer_list clearfix">
                        <span class="answer_left fl">
                            <input type="checkbox" class="answer_chek">
                            <em><?php echo $v->studentName?>的答案----</em>
                            <em class="pagination">共<?php echo  count($v->resQuestionAnswerList)?>页(<?php echo $v->isCheck?"批改完成":"未批改"?>)</em>
                         </span>
                         <span class="answer_Right fr">
                            <a href="javascript:" class="examine examine_js">&nbsp;&nbsp;查看<i></i></a>

                         </span>
        </div>
        <div class="test_paper test_paper_list" style="display:block;">
            <div class="list_d">答案---客观题：
            <?php foreach($v->objQuestionAnswerList as $key=>$value){
                $order=$key+1;
                 $right=$value->answerRight?"正确":"错误";
                echo  $order.":".$right."&nbsp".intval($value->score)."分"."&nbsp";
            }?>
            </div>
            <ul class="minute clearfix minute_box">
                <?php foreach($v->resQuestionAnswerList as $key=>$value){ if(count($value->picList)>0){if($key<9){?>
                    <li>
                        <span><img  alt="" src="<?php echo publicResources().$value->picList[0]->picUrl?>"></span>

                    </li>
                <?php }elseif($key==9){?>
                    <li class="add">
                        <span class="more_btn02"><img  alt="" src="<?php echo publicResources()?>/images/more.png"></span>

                    </li>
                    <li>
                        <span><img  alt="" src="<?php echo publicResources().$value->picList[0]->picUrl?>"></span>

                    </li>
                <?php }elseif($key>9){?>
                    <li>
                        <span><img  alt="" src="<?php echo publicResources().$value->picList[0]->picUrl?>"></span>

                    </li>

                <?php } } }?>
               <?php  if($v->isCheck){ echo $v->testScore."分";
               }?>
            </ul>

            <?php if(!$v->isCheck){?>
                <p class="btn"><a href="<?php echo url('teacher/managepaper/correct-organize-paper',array('testAnswerID'=>$v->testAnswerID))?>">我要判卷</a></p>
            <?php }else{?>
                <p class="btn"><a href="<?php echo url('teacher/managepaper/view-org-correct',array('testAnswerID'=>$v->testAnswerID))?>">查看批改</a></p>
            <?php }?>
        </div>

    </div>
<?php }?>
</div>
<hr>
<!--    --><?php //if($answerResult->notCheckAnswerNum>1&&$answerResult->isHaveCrossCheck==0){?>
<!--<div class="btn_work_ails">-->
<!--    <input type="checkbox" class="chck" id="check"><button type="button" class="btn btn_color" id="pjjr_js">学生互相判卷</button>-->
<!--</div>-->
<!--    --><?php //}?>
    <?php
     echo \frontend\components\CLinkPagerExt::widget( array(
           'pagination'=>$pages,
            'maxButtonCount' => 5
        )
    );
    ?>
</div>


</div>



<!--互相判卷-->
<div id="sends" class=" popBox mutual_pjjr_pop hide" title="同学互相判卷">
    <div class="impBox">
        <div class="mutual_pjjr_list">您确定允许该试卷</div>
        <div class="mutual_pjjr_list"><h5><?php echo $data->name?></h5></div>
        <div class="mutual_pjjr_list">的没有判卷的学生互换试卷并进行批改吗?</div>

    </div>
</div>
<!--填写科目总评-->
<div id="hear" class="popBox hearBox hide general_comment clearfix" title="填写科目总评">
    <div class="hearLeft">
        <h2>章节树</h2>

        <div class="hear_tree">
            <ul id="treeList" class="clearfix ztree"></ul>
        </div>
    </div>
    <div class="impBox">
        <ul class="form_list">
            <li>
                <div class="formL">
                    <label>班级：</label>
                </div>
                <div class="formR">
                                        <div><?php echo $sectionResult->className?></div>
                </div>
            </li>
            <?php if($maxAndMin->MaxScore!=0&&$maxAndMin->MinScore!=0){?>
            <li>
                <div class="formL">
                    <label>最高-最低分：</label>
                </div>
                <div class="formR">
                    <span class="score">最高分：<em><?php echo $maxAndMin->MaxScore ?>分</em></span>
                    <span class="score">最低分：<em><?php echo $maxAndMin->MinScore ?>分</em></span>
                </div>
            </li>
            <?php }?>
            <li>
                <div class="formL">
                    <label>分数段：</label>
                </div>
                <div class="formR pointarea">
                    <?php foreach ($sectionResult->socreList as $v) { ?>
                        <span class="scorex"><?php echo $v->bottomlimit . "-" . $v->toplimit . "&nbsp共" . $v->num ?>
                            人</span>
                    <?php } ?>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>试卷难点：</label>
                </div>
                <div class="formR">
                    <div id="point_chapt" class="treeParent">

                        <div class="pointArea hide">
                            <input class="hidVal" type="hidden" value="<?php echo $evaluateResult->knowledgePoint ?>">
                            <h5>已选中知识点:</h5>
                            <ul class="labelList">
                                <!--<li>语文</li>
                                <li>造句</li>
                                <li>形容词</li>
                                <li>语文</li>
                                <li>造句</li>
                                <li>形容词</li>-->
                            </ul>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="formL">
                    <label><i></i>学习规划：</label>
                </div>
                <div class="formR">
                    <textarea class="learningPlan"><?php echo $evaluateResult->summary?></textarea>
                </div>
            </li>
        </ul>
    </div>

</div>
<script>
    $(function(){

        function beforeDrag(treeId, treeNodes) {
            for (var i=0,l=treeNodes.length; i<l; i++) {
                if (treeNodes[i].drag === false) {
                    return false;
                }
            }
            return true;
        }
        function beforeDrop(treeId, treeNodes, targetNode, moveType) {
            return targetNode ? targetNode.drop !== false : true;
        }

//删除确认
        function zTreeOnRemove(event, treeId, treeNode) {
            $('.delReason').popBoxShow();
        }

//弹出框,选中节点,在下面添加标签
        function addCheckItem(tId,name){
            $('.clsTreeBox .labelList').append('<li index='+tId+'>'+name+'</li>');
        }

        function zTreeOnCheck(event, treeId, treeNode){
            if(treeNode.checked==true){
                $('.clsTreeBox .chooseLabel').show();
                addCheckItem(treeNode.tId,treeNode.name);
                //$('.clsTreeBox .labelList').append('<li index='+treeNode.tId+'>'+treeNode.name+'</li>');
            }
            else{
                $('.clsTreeBox .labelList li[index='+treeNode.tId+']').remove();
            }
        }



        var newCount = 1;
        function addHoverDom(treeId, treeNode) {
            var sObj = $("#" + treeNode.tId + "_span");
            if (treeNode.editNameFlag || $("#addBtn_"+treeNode.tId).length>0) return;
            var addStr = "<span class='button add' id='addBtn_" + treeNode.tId
                + "' title='add node' onfocus='this.blur();'></span>";
            sObj.after(addStr);
            var btn = $("#addBtn_"+treeNode.tId);
            if (btn) btn.bind("click", function(){
                var zTree = $.fn.zTree.getZTreeObj("knowledgePointTree");
                zTree.addNodes(treeNode, {id:(100 + newCount), pId:treeNode.id, name:"new node" + (newCount++)});
                return false;
            });
        }
        function removeHoverDom(treeId, treeNode) {
            $("#addBtn_"+treeNode.tId).unbind().remove();
        }
        function selectAll() {
            var zTree = $.fn.zTree.getZTreeObj("knowledgePointTree");
            zTree.setting.edit.editNameSelectAll =  $("selectAll").attr("checked");
        }

        /*function setCheck() {
         var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
         isCopy = $("#copy").attr("checked"),
         isMove = $("#move").attr("checked"),
         prev = $("#prev").attr("checked"),
         inner = $("#inner").attr("checked"),
         next = $("#next").attr("checked");
         zTree.setting.edit.drag.isCopy = isCopy;
         zTree.setting.edit.drag.isMove = isMove;
         showCode(1, ['setting.edit.drag.isCopy = ' + isCopy, 'setting.edit.drag.isMove = ' + isMove]);

         zTree.setting.edit.drag.prev = prev;
         zTree.setting.edit.drag.inner = inner;
         zTree.setting.edit.drag.next = next;
         showCode(2, ['setting.edit.drag.prev = ' + prev, 'setting.edit.drag.inner = ' + inner, 'setting.edit.drag.next = ' + next]);
         }
         function showCode(id, str) {
         var code = $("#code" + id);
         code.empty();
         for (var i=0, l=str.length; i<l; i++) {
         code.append("<li>"+str[i]+"</li>");
         }
         }*/

        $(function(){
            //$.fn.zTree.init($("#knowledgePointTree"), setting, zNodes);


            /*
             $("#copy").bind("change", setCheck);
             $("#move").bind("change", setCheck);
             $("#prev").bind("change", setCheck);
             $("#inner").bind("change", setCheck);
             $("#next").bind("change", setCheck);
             */		});


    })



</script>
<script>

    $(function(){
        /*互相判卷*/
        $('#sends').dialog({
            autoOpen: false,
            width:500,
            modal: true,
            resizable:false,
            buttons: [
                {
                    text: "确定",
                    click: function() {
                        var testID="<?php echo app()->request->getParam('testId')?>";
                        $.post("<?php echo url('teacher/managepaper/student-cross-check')?>",{testID:testID},function(result){
                                popBox.alertBox(result.message);
                        });
                        $( this ).dialog( "close" );
                    }
                },
                {
                    text: "取消",
                    click: function() {
                        $( this ).dialog( "close" );
                    }
                }

            ]
        });


        $('#pjjr_js').click(function(){
            $( "#sends" ).dialog( "open" );
            return false;
        });

        /*修改通知*/
        $('.t_btn_js').click(function(){

            $('#hear').dialog({
                autoOpen: false,
                width:600,
                modal: true,
                resizable:false,
                title:"填写科目总评",
                buttons: [
                    {
                        text: "确定",
                        class:"okBtn",
                        click: function() {
                            if($('#mySchoolPop .text').val()==1){
                                $( this ).dialog( "close" );
                            }
                            else{
                                var kid = $(".hidVal").val();
                                var learningPlan = $(".learningPlan").val();
                                <!--                            var testID = "--><?php //echo app()->request->getParam('testId')?><!--";-->
                                var examSubID="<?php echo app()->request->getParam('examSubID')?>";
                                $.post("<?php echo url('teacher/managepaper/subject-evaluate')?>", {"kid": kid, "learningPlan": learningPlan, "examSubID": examSubID}, function (result) {
                                    if (result.code == 1) {
                                        popBox.successBox(result.message);
                                        location.reload();
                                    }
                                    else {
                                        popBox.errorBox(result.message);
                                    }
                                })
                            }
                        }
                    },
                    {
                        text: "取消",
                        class:"cancelBtn",
                        click: function() {
                            $( this ).dialog( "close" );
                        }
                    }
                ]
            });
            $( "#hear" ).dialog( "open" );
            //event.preventDefault();
            return false;
        });
//        修改科目总评
        $('.update').click(function () {

            $('#hear').dialog({
                autoOpen: false,
                width: 700,
                modal: true,
                resizable: false,
                title: "修改科目总评",
                buttons: [
                    {
                        text: "确定",
                        class: "okBtn",
                        click: function () {
                            if ($('#mySchoolPop .text').val() == 1) {
                                $(this).dialog("close");
                            }
                            else {
                                var kid = $(".hidVal").val();
                                var learningPlan = $(".learningPlan").val();
                                <!--                            var testID = "--><?php //echo app()->request->getParam('testId')?><!--";-->
                                var examSubID="<?php echo app()->request->getParam('examSubID')?>";
                                $.post("<?php echo url('teacher/managepaper/subject-evaluate')?>", {"kid": kid, "learningPlan": learningPlan, "examSubID": examSubID}, function (result) {
                                    if (result.code == 1) {
                                        popBox.successBox(result.message);
                                            location.reload();
                                    }
                                    else {
                                        popBox.errorBox(result.message);
                                    }
                                })
                            }
                        }
                    },
                    {
                        text: "取消",
                        class: "cancelBtn",
                        click: function () {
                            $(this).dialog("close");
                        }
                    }
                ]
            });
            $("#hear").dialog("open");
            //event.preventDefault();
            return false;
        });

//知识树1
        var id_arr=[];//后台要的id
        var setting = {
            check:{enable:true,chkboxType:{"Y" : "", "N" : ""} },
            data:{simpleData: {	enable: true} },
            callback: {onCheck:zTreeOnCheck},
            view:{showIcon:false,showLine:false,}
        };

        var zNodes =<?php echo $chapterTree?>;
        var chapter = "<?php echo $evaluateResult->knowledgePoint?>";
        var chapterArray = chapter.split(",");
        $.each(zNodes, function (index, result) {
            if (chapterArray.indexOf(result.id.toString()) > -1) {
                result["checked"] = true;
            }
        });
        $.fn.zTree.init($("#treeList"), setting, zNodes);
        var treeObj = $.fn.zTree.getZTreeObj("treeList");
        var sNodes = treeObj.getCheckedNodes(true);
        $('#hear #point_chapt .pointArea').show();
        for (var i = 0; i < sNodes.length; i++) {
            $('#hear #point_chapt .labelList').append('<li val="' + sNodes[i].id + '"  index="' + sNodes[i].tId + '">' + sNodes[i].name + '</li>');
        }
        //点击树上的checkbox
        function zTreeOnCheck(event, treeId, treeNode){
            if(treeNode.checked==true){
                $('#hear #point_chapt .pointArea').show();
                $('#hear #point_chapt .labelList').append('<li val="'+treeNode.id+'"  index="'+treeNode.tId+'">'+treeNode.name+'</li>');
                id_arr.push(treeNode.id);
                $('#hear #point_chapt .hidVal').val(id_arr);
                return false;
            }
            else{
                $('#hear #point_chapt .labelList li[index='+treeNode.tId+']').remove();
                id_arr.remove(treeNode.id)//base.js中定义的arr方法
                $('#hear #point_chapt .hidVal').val(id_arr);
                return false;
            };
            x
        }


        /*查看下拉*/
        $('.examine_js').toggle(
            function(){
                $(this).parents('.answer_list').siblings('.test_paper').show();
            },
            function(){
                $(this).parents('.answer_list').siblings('.test_paper').hide();
            }
        );
        /******/
        $('.more_btn02').toggle(
            function(){
                $(this).parents('ul').css('height','auto');
            },
            function(){
                $(this).parents('ul').css('height','60px');
            }
        );

        $('.more_btn03').toggle(
            function(){
                $(this).parents('ul').css('height','auto');
            },
            function(){
                $(this).parents('ul').css('height','74px');
            }
        );
        //全选
        var aCh=document.getElementById('details_b').getElementsByTagName('input');
        var oC=document.getElementById('check');
        oC.onclick=function()
        {
            for(var i=0; i<aCh.length; i++)
            {
                aCh[i].checked=oC.checked
            }
        };
        /*移入显示删除按钮*/
        $('.minute li').live('mouseover mouseout',function(event){

            if (event.type == 'mouseover') {
                $(this).children('i').show();
            } else {
                $(this).children('i').hide();
            }
        });
        /*删除按钮*/
        $('.minute li i').live('click',function(){
            $(this).parent().remove();
        })





    })
</script>
<script>
    $(function(){
        var zNodes =[
            { id:1, pId:0, name:"语文",},
            { id:11, pId:1, name:"拼音",},
            { id:111, pId:11, name:"声母"},
            { id:112, pId:11, name:"韵母"},
            { id:113, pId:11, name:"语法"},
            { id:12, pId:1, name:"标点符号"},
            { id:13, pId:1, name:"造句"},
            { id:14, pId:1, name:"语法"},
        ];
        $('#pointBtn').click(function(){
            popBox.pointTree(zNodes,$(this),"知识点");
        })


    })



</script>