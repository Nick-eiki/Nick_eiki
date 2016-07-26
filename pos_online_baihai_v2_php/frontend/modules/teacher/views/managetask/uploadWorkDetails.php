<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-4
 * Time: 下午3:31
 */
use frontend\components\helper\AreaHelper;
use frontend\components\helper\ImagePathHelper;
use frontend\models\dicmodels\KnowledgePointModel;

/* @var $this yii\web\View */  $this->title="上传作业详情";
?>
<div class="currentRight grid_16 push_2 up_work_details">
<div class="noticeH clearfix noticeB">
    <h3><?php echo $result->name ?></h3>
</div>
<hr>
<div class="work_detais_cent">
    <h4><?php echo $result->name ?></h4>
    <ul class="detais_list">
        <li>
            <span>地区：<i><?php echo AreaHelper::getAreaName($result->provience) . "&nbsp" . AreaHelper::getAreaName($result->city) . "&nbsp" . AreaHelper::getAreaName($result->country) ?></i></span><span>年级：<i><?php echo $result->gradename ?></i></span><span>科目：<i><?php echo $result->subjectname ?></i></span><span>版本：<i><?php echo $result->versionname ?></i></span>
        </li>
        <li><span>知识点：<i><?php $knowledge = new KnowledgePointModel();
                    echo $knowledge::findKnowledgeStr($result->knowledgeId) ?></i></span></li>
        <li><span>作业简介：<i><?php echo $result->homeworkDescribe ?></i></span></li>
    </ul>
    <div class="details_b" id="details_b">
        <div class="species clearfix">
            <p>作业内容：</p>

            <div class="species_Right">
                <ul class="minute minute70 clearfix ">
                    <?php foreach (ImagePathHelper::getPicUrlArray($result->images) as $k => $v) {
                        if ($k == 0) {
                            ?>
                            <li>
                                <span>
                                	<img alt="" src="<?php echo publicResources() . $v ?>">
                                    <em><a href="<?php echo url('teacher/managetask/preview-paper', array('homeworkID' => app()->request->getParam('homeworkID'))) ?>">作业预览</a></em>
                                </span>

                            </li>
                        <?php } elseif ($k > 0 && $k < 7) { ?>
                            <li>
                                <span><img alt="" src="<?php echo publicResources() . $v ?>"></span>

                            </li>
                        <?php } elseif ($k == 7) { ?>
                            <li class="add">
                        <span class="more_btn03"><img alt=""
                                                      src="<?php echo publicResources() ?>/images/more.png"></span>

                            </li>
                            <li>
                                <span><img alt="" src="<?php echo publicResources() ?>/images/12.png"></span>

                            </li>
                        <?php } else { ?>
                            <li>
                                <span><img alt="" src="<?php echo publicResources() . $v ?>"></span>

                            </li>
                        <?php
                        }
                    } ?>
                </ul>
            </div>
            <p class="number">提交答案学生：<em><?php echo count($answerResult->answerlist)?>人</em></p>
        </div>
        <?php foreach ($answerResult->answerlist as $v) {
     ?>
                <div class="answer">
                    <div class="answer_list clearfix">
                        <span class="answer_left fl">
                            <input type="checkbox" class="answer_chek">
                            <em><?php echo $v->studentName ?>的答案----</em>
                            <em class="pagination">共<?php echo count($v->homeworkCheckInfoS) ?>页(<?php echo $v->isCheck==0?"未批改":"已批改"?>)</em>
                         </span>
                         <span class="answer_Right fr">
                            <a href="javascript:" class="examine examine_js">&nbsp;&nbsp;查看<i></i></a>

                         </span>
                    </div>
                    <div class="test_paper test_paper_list" style="display:block;">
                        <div class="list_d">答案</div>
                        <ul class="minute clearfix minute_box">
                            <?php foreach ($v->homeworkCheckInfoS as $key => $value) {
                                if ($key < 9) {
                                    ?>
                                    <li>
                                        <span><img alt=""
                                                   src="<?php echo publicResources() . $value->imageUrl ?>"></span>

                                    </li>
                                <?php } elseif ($key == 9) { ?>
                                    <li class="add">
                                        <span class="more_btn02"><img alt=""
                                                                      src="<?php echo publicResources() ?>/images/more.png"></span>

                                    </li>
                                    <li>
                                        <span><img alt=""
                                                   src="<?php echo publicResources() ?>/images/picture.png"></span>

                                    </li>
                                <?php } elseif ($key > 9) { ?>
                                    <li>
                                        <span><img alt=""
                                                   src="<?php echo publicResources() . $value->imageUrl ?>"></span>

                                    </li>
                                <?php }
                            } ?>
                        </ul>
                        <p class="btn">
                          <?php if($v->isCheck==0){?>
                            <a
                                href="<?php echo url('teacher/managetask/correct-paper', array('homeworkAnswerID' => $v->homeworkAnswerID, 'homeworkID' => app()->request->getParam('homeworkID'), 'classID' => app()->request->getParam('classID'))) ?>">我要批改</a>
                            <?php }elseif($v->isCheck==1){?>
                              <a
                                  href="<?php echo url('teacher/managetask/view-correct', array('homeworkAnswerID' => $v->homeworkAnswerID, 'homeworkID' => app()->request->getParam('homeworkID'), 'classID' => app()->request->getParam('classID'))) ?>">查看批改</a>
                            <?php }?>
                        </p>
                    </div>

                </div>

            <?php
        } ?>


    </div>
    <hr>
    <?php if($answerResult->notCheckAnswerNum>1&&$answerResult->isHaveCrossCheck==0){?>
    <div class="btn_work_ails">
        <input type="checkbox" class="chck" id="check">
        <button type="button" class="bg_red_l" id="pjjr_js">学生互相批改</button>
    </div>
    <?php }?>
    <!--翻页-->
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
        <div class="mutual_pjjr_list"><h5><?php echo $result->name?></h5></div>
        <div class="mutual_pjjr_list">的没有判卷的学生互换试卷并进行批改吗?</div>

    </div>
</div>
<script>
    $(function () {

        function beforeDrag(treeId, treeNodes) {
            for (var i = 0, l = treeNodes.length; i < l; i++) {
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
        function addCheckItem(tId, name) {
            $('.clsTreeBox .labelList').append('<li index=' + tId + '>' + name + '</li>');
        }

        function zTreeOnCheck(event, treeId, treeNode) {
            if (treeNode.checked == true) {
                $('.clsTreeBox .chooseLabel').show();
                addCheckItem(treeNode.tId, treeNode.name);
                //$('.clsTreeBox .labelList').append('<li index='+treeNode.tId+'>'+treeNode.name+'</li>');
            }
            else {
                $('.clsTreeBox .labelList li[index=' + treeNode.tId + ']').remove();
            }
        }


        var newCount = 1;

        function addHoverDom(treeId, treeNode) {
            var sObj = $("#" + treeNode.tId + "_span");
            if (treeNode.editNameFlag || $("#addBtn_" + treeNode.tId).length > 0) return;
            var addStr = "<span class='button add' id='addBtn_" + treeNode.tId
                + "' title='add node' onfocus='this.blur();'></span>";
            sObj.after(addStr);
            var btn = $("#addBtn_" + treeNode.tId);
            if (btn) btn.bind("click", function () {
                var zTree = $.fn.zTree.getZTreeObj("knowledgePointTree");
                zTree.addNodes(treeNode, {id: (100 + newCount), pId: treeNode.id, name: "new node" + (newCount++)});
                return false;
            });
        }
        function removeHoverDom(treeId, treeNode) {
            $("#addBtn_" + treeNode.tId).unbind().remove();
        }
        function selectAll() {
            var zTree = $.fn.zTree.getZTreeObj("knowledgePointTree");
            zTree.setting.edit.editNameSelectAll = $("selectAll").attr("checked");
        }
    })
</script>
<script>

    $(function () {
        /*互相判卷*/
        $('#sends').dialog({
            autoOpen: false,
            width: 500,
            modal: true,
            resizable: false,
            buttons: [
                {
                    text: "确定",
                    click: function () {
                        var homeworkID="<?php echo app()->request->getParam('homeworkID')?>";
                        $.post("<?php echo url('teacher/managetask/student-cross-check')?>",{"homeworkID":homeworkID},function(result){
                            if(result.code==1){
                                popBox.alertBox(result.message);
                            }else{
                                popBox.alertBox(result.message);
                            }
                        });
                        $(this).dialog("close");
                    }
                },
                {
                    text: "取消",
                    click: function () {
                        $(this).dialog("close");
                    }
                }

            ]
        });
        /*指定阅卷人*/
        $('#read').dialog({
            autoOpen: false,
            width: 500,
            modal: true,
            resizable: false,
            buttons: [
                {
                    text: "确定",
                    click: function () {
                        $(this).dialog("close");
                    }
                },
                {
                    text: "取消",
                    click: function () {
                        $(this).dialog("close");
                    }
                }

            ]
        });
        /*选择老师*/
        $('#select_ter').dialog({
            autoOpen: false,
            width: 500,
            modal: true,
            resizable: false,
            buttons: [
                {
                    text: "确定",
                    click: function () {
                        $(this).dialog("close");
                    }
                },
                {
                    text: "取消",
                    click: function () {
                        $(this).dialog("close");
                    }
                }

            ]
        });

        $('#pjjr_js').click(function () {
            $("#sends").dialog("open");
            return false;
        });
        $('#appoint').click(function () {
            $("#read").dialog("open");
            return false;
        });

        $('#TeacherBtn').click(function () {
            $("#select_ter").dialog("open");
            return false;
        });


        /*查看下拉*/
        $('.examine_js').toggle(
            function () {
                $(this).parents('.answer_list').siblings('.test_paper').show();
            },
            function () {
                $(this).parents('.answer_list').siblings('.test_paper').hide();
            }
        );
        /******/
        $('.more_btn02').toggle(
            function () {
                $(this).parents('ul').css('height', 'auto');
            },
            function () {
                $(this).parents('ul').css('height', '60px');
            }
        );

        $('.more_btn03').toggle(
            function () {
                $(this).parents('ul').css('height', 'auto');
            },
            function () {
                $(this).parents('ul').css('height', '74px');
            }
        );
        //全选
        var aCh = document.getElementById('details_b').getElementsByTagName('input');
        var oC = document.getElementById('check');
        oC.onclick = function () {
            for (var i = 0; i < aCh.length; i++) {
                aCh[i].checked = oC.checked
            }
        };
        /*移入显示删除按钮*/
        $('.minute li').live('mouseover mouseout', function (event) {

            if (event.type == 'mouseover') {
                $(this).children('i').show();
            } else {
                $(this).children('i').hide();
            }
        });

//选择老师

        $('.chooseTeacherBox li').hover(
            function () {
                $(this).children('i').show()
            },
            function () {
                $(this).children('i').hide();
                $(this).children('.choose').show()
            }
        );

        $('.chooseTeacherBox li').toggle(
            function () {
                $(".chooseTeacherBar").show();
                $(this).children('i').addClass('choose');
                $(this).clone().removeAttr('id').attr('class', $(this).attr('id')).appendTo('.chooseTeacherBar ul');
            },
            function () {
                $(this).children('i').removeClass('choose');
                $(".chooseTeacherBar ." + $(this).attr('id') + "").remove();
            }
        )


    })
</script>