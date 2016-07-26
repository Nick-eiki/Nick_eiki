<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 2015/9/17
 * Time: 11:56
 */
use frontend\components\helper\VersionHelper;
use frontend\models\dicmodels\LoadTextbookVersionModel;
use frontend\models\dicmodels\SchoolLevelModel;
use frontend\models\dicmodels\SubjectModel;
use yii\helpers\Html;

$this->title='作业库列表';
$searchArr = array(
    'department' => app()->request->getParam('department', $department),
    'subjectid' => app()->request->getParam('subjectid', $subject),
    'text' => app()->request->getParam('text'),
    'edition' => app()->request->getParam('edition', $edition),
    'difficulty'=>app()->request->getParam('difficulty',$difficulty)
);
?>
<script>
    var getSearchList = function (obj) {
        var    chapter=$(obj).attr("data-value");
        var department="<?= $searchArr['department']?>";
        var subjectid="<?= $searchArr['subjectid']?>";
        var text="<?=$searchArr['text']?>";
        var tome="<?=app()->request->getQueryParam('tome','')?>";
        var edition="<?=$searchArr['edition']?>";
        $.get("<?=url('teacher/managetask/library-list')?>",{chapter:chapter,department:department,subjectid:subjectid,text:text,tome:tome,edition:edition},function(result){
            $("#updateHomework").html(result);
        })

    };
</script>
<div class="grid_24 main_r">
    <div class="main_cont tea_prepare">
        <div class="title">
            <h4>作业库</h4>
        </div>
        <div class="form_listBox">
            <div class="subTitle_s">
                <div class="subTitle_r pr">
                    <?php echo Html::beginForm( array_merge([''], $searchArr), 'get') ?>
                    <input type="text" style="width:550px" name="text" class="text subTitleBar_text" id="sclName" value="<?=app()->request->getQueryParam('text')?>">
                    <button type="submit" class="search btn">搜索作业</button>
                    <?php echo Html::endForm()?>
                </div>
            </div>
            <div class="course"> <a href="javascript:;" class="cour_btn hotWord"><?php echo SchoolLevelModel::model()->getSchoolLevelhName($department); ?><?php echo SubjectModel::model()->getSubjectName($subject); ?><i></i></a>
                <div class="course_box hotWordList pop"> <i class="arrow course_box_arrow"></i>
                    <dl class="clearfix">
                        <?php echo $this->render('//publicView/search/_subject_view',array('department'=>'20201','departments'=>$department,'subjectid'=>$subject, ));?>
                    </dl>
                    <dl class="clearfix">
                        <?php echo $this->render('//publicView/search/_subject_view',array('department'=>'20202','departments'=>$department,'subjectid'=>$subject,  ));?>
                    </dl>
                        <dl class="clearfix">
                        <?php echo $this->render('//publicView/search/_subject_view',array('department'=>'20203','departments'=>$department,'subjectid'=>$subject,  ));?>
                    </dl>
                </div>
            </div>
            <div class="form_list no_padding_form_list">
                <div class="row">
                    <div class="formL">
                        <label>版本：</label>
                    </div>
                    <div class="formR" style="width:1035px">
                        <ul class="resultList  clearfix testClsList">


                            <?php
                            $version = VersionHelper::getVersionArr($department, $subject, LoadTextbookVersionModel::model($subject,'',$department)->getListData());
                            foreach ($version as $k => $v) {
                                $ac=false;
                                if(app()->request->getParam('edition')!=null){
                                    if($k == app()->request->getParam('edition')){
                                        $ac=true;
                                    }
                                }else{
                                    if($k==loginUser()->getModel()->textbookVersion){
                                        $ac=true;
                                    }
                                }
                                ?>
                                <li class="
<?php echo  $ac? 'ac' : ''; ?>">
                                    <a href="
<?php echo url('teacher/managetask/library-list', array_merge($searchArr, array('edition' => $k))) ?>">
                                        <?php echo $v; ?></a>

                                </li>
                            <?php } ?>
                            <li class="
<?php echo app()->request->getParam('edition') == '其他' ? 'ac' : ''; ?>">
                                <a href="
<?php echo url('teacher/managetask/library-list', array_merge($searchArr, array('edition' => '其他'))) ?>">
                                    其他</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label>分册：</label>
                    </div>
                    <div class="formR" style="width:1035px">
                        <ul class="resultList  clearfix testClsList">

                            <?php

                            foreach ($tomeResult as $k => $v) {

                                ?>
                                <li class="
<?php echo $v->id == app()->request->getParam('tome') ? 'ac' : '' ?>">
                                    <a href="
<?php echo url('teacher/managetask/library-list', array_merge($searchArr, array('tome' => $v->id))) ?>">
                                        <?php echo $v->name; ?></a>
                                </li>
                            <?php } ?>

                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="formL">
                        <label>难度：</label>
                    </div>
                    <div class="formR" style="width:1035px">
                        <ul class="resultList  clearfix testClsList">

                            <?php
                           $difficultyArray=array('普通','中等','较难');
                            foreach ($difficultyArray as $k => $v) {
                                ?>
                                <li class="
<?php
                                if($k!=null){ echo $k== app()->request->getParam('difficulty') ? 'ac' : '';}
                                ?> ">
                                    <a href="
<?php echo url('teacher/managetask/library-list', array_merge($searchArr, array('difficulty' => $k))) ?>">
                                        <?php echo $v; ?></a>
                                </li>
                            <?php } ?>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="problem_box clearfix tacher_test_paper">
            <div class="grid_5 alpha knowledge">
                <div class="problem_tree_cont">
                    <h4>章节目录</h4>
                    <a href="<?=url('teacher/managetask/library-list',$searchArr)?>" class="resetting">重置</a>
                    <div id="problem_tree" class="problemTreeWrap">
                        <?php echo $treeData?>
                    </div>
                </div>
            </div>
            <div class="problem_r problem_rgt">
                <!--<div class="problem_r_list">
                    <h5>创建试卷<i></i></h5>
                    <ul class="hot" style="display:none;">
                        <li><a class="t_ico" href="javascript:;">创建试卷<i></i></a></li>
                        <li class="list this "><a class="" href="../../index.html">上传试卷</a></li>
                        <li class="list"><a class="" href="../../index.html">组织试卷</a></li>
                    </ul>
                </div>-->

                <div class="tab fl">
                    <ul class="tabList clearfix">
                        <li class="tabListShow"><a href="javascript:;" class="ac">作业库</a></li>
                    </ul>
                    <div class="tabCont teac_test_paper ">
                        <!--作业库-->
                        <div class="tabItem work_tabitem" id="updateHomework">
                        <?php echo $this->render('_homework_list',array('homeworkResult'=>$homeworkResult,'pages'=>$pages))?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        //知识树
        $('.tree').tree({expandAll:true,operate:true});
        $('#sclName').placeholder({value:'作业名称',ie6Top:10});
        $('.hotWord').click(function(){$('.hotWordList').show();return false});
        $('.hotWordList').mouseleave(function(){$(this).hide()});
        //
        $('.hotWordList dd').live('click',function(){
            $('.hotWordList dd').removeClass('ac');
            $(this).addClass('ac');
        });


    })


</script>