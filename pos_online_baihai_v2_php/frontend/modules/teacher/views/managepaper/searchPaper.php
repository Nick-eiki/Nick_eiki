<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-15
 * Time: 上午10:34
 */
use frontend\models\dicmodels\GradeModel;
use frontend\models\dicmodels\SubjectModel;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = '教师--试卷管理-列表';
$searchArr = array(
    'gradeId' => app()->request->getParam('gradeId', $gradeData),
    'subjectId' => app()->request->getParam('subjectId', $subjects),
//    'edition' => app()->request->getParam('edition',$editions),
    'getType' => app()->request->getParam('getType'),
    'orderType' => app()->request->getParam('orderType')
);
?>
<script>

    $(function () {
        //选择课程
        $('.hotWord').live('click', function () {
            $('.hotWordList').show()
        });
        $('.hotWordList').mouseleave(function () {
            $(this).hide()
        });
        //
        $('.hotWordList dd').live('click', function () {
            $('.hotWordList dd').removeClass('ac');
            $(this).addClass('ac');
        });
        //增加题目
        $('.problem_r_list').click(function () {
            $('.hot').show();
        });

        $('.hot').mouseleave(function () {
            $(this).hide()
        });
        $('.teac_test_paper_list li').live('mouseover', function () {
            $(this).children('span').show();
            $(this).addClass('this');
        });

        $('.teac_test_paper_list li').live('mouseout', function () {
            $(this).removeClass('this');
            $(this).children('span').hide();
        });


        //知识树


    })
</script>

<!--主体-->
<div class="grid_19 main_r">
    <div class="main_cont tezhagnhaioast_problem">
        <div class="title">
            <h4>试卷管理</h4>
        </div>
        <div class="form_list no_padding_form_list">
            <div class="row">
                <div class="formL">
                    <label>年级：</label>
                </div>
                <div class="formR">
                    <ul class="resultList  clearfix testClsList">
                        <?php
                        $department = loginUser()->getModel()->department;
                        $grade = GradeModel::model()->getData($department, '');
                        foreach ($grade as $key => $item) {
                            ?>
                            <li class="<?php echo app()->request->getParam('gradeId', $gradeData) == $item['gradeId'] ? 'ac' : ''; ?>">
                                <a href="<?= Url::to(array_merge([''], $searchArr, ['gradeId' => $item['gradeId']])) ?>"><?php echo $item['gradeName']; ?></a>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="formL">
                    <label>学科：</label>
                </div>
                <div class="formR">
                    <ul class="resultList  clearfix testClsList">
                        <?php $subject = SubjectModel::model()->getSubByGrade(app()->request->getParam('gradeId', $gradeData));

                        foreach ($subject as $v) {
                            ?>
                            <li class="<?php echo app()->request->getParam('subjectId', $subjects) == $v->secondCode ? 'ac' : ''; ?>">
                                <a
                                    href="<?= Url::to(array_merge([''], $searchArr, ['subjectId' => $v->secondCode])) ?>"><?php echo $v->secondCodeValue; ?></a>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>


        </div>

        <div class="problem_box clearfix tacher_test_paper">

            <div class="problem_r">
                <div class="problem_r_list">
                    <h5>创建试卷<i></i></h5>
                    <ul class="hot" style="display:none;">
                        <li><a class="t_ico" href="javascript:;">创建试卷<i></i></a></li>
                        <li class="list this "><a class="" href="<?php echo url('teacher/managepaper/upload-paper') ?>">上传试卷</a>
                        </li>
                        <li class="list"><a class="" href="<?php echo url('teacher/makepaper') ?>">组织试卷</a></li>
                    </ul>
                </div>

                <div class="tab fl">
                    <ul class="tabList clearfix">
                        <li><a href="javascript:;" class="ac">我的试卷</a></li>
                    </ul>
                    <div class="tabCont teac_test_paper">

                        <div class="tabItem">
                            <div class="form_list">
                                <div class="row">

                                    <div class="formR">
                                        <ul class="resultList  clearfix testClsList">
                                            <li class="<?php echo app()->request->getParam('getType') == '' ? 'ac' : '' ?>">
                                                <a href="<?= Url::to(array_merge([''], $searchArr, ['getType' => ''])) ?>">所有试卷</a>
                                            </li>
                                            <li class="<?php echo app()->request->getParam('getType') == '0' ? 'ac' : '' ?>">
                                                <a href="<?= Url::to(array_merge([''], $searchArr, ['getType' => '0'])) ?>">纸质试卷</a>
                                            </li>
                                            <li class="<?php echo app()->request->getParam('getType') == '1' ? 'ac' : '' ?>">
                                                <a href="<?= Url::to(array_merge([''], $searchArr, ['getType' => '1'])) ?>">电子试卷</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="test_paper_sort clearfix">
                                <p class="fl font14">
                                    <span>排序：&nbsp;&nbsp;发布时间</span>
                                    <?php if (app()->request->getParam("orderType") == "2") { ?>
                                        <a href="<?= Url::to(array_merge([''], $searchArr, ['orderType' => '1'])) ?>">
                                            <em class="up ac"></em>
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?= Url::to(array_merge([''], $searchArr, ['orderType' => '2'])) ?>">
                                            <em class="down ac"></em></a>
                                    <?php } ?>
                                </p>

                            </div>
                            <div id="update">
                                <?php echo $this->render('_new_paperList', array('data' => $data, 'pages' => $pages)); ?>
                            </div>
                        </div>
                        <div class="tabItem hide">
                        </div>
                        <div class="tabItem hide">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--主体end-->