<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 15-4-13
 * Time: 下午3:59
 */
use yii\helpers\Html;

/* @var $this yii\web\View */  $this->title='题目管理-搜索选题';
$seachArr= array(

    'type'=>app()->request->getParam('type'),
    'complexity'=>app()->request->getParam('complexity'),
    'department'=>app()->request->getParam('department',$department),
	'subjectid'=>app()->request->getParam('subjectid',$subjectid),
    'text'=>app()->request->getParam('text')
);
$seachArr2= array(
    'type'=>app()->request->getParam('type'),
    'complexity'=>app()->request->getParam('complexity'),
    'department'=>app()->request->getParam('department',$department),
    'subjectid'=>app()->request->getParam('subjectid',$subjectid),
);


/* @var $this yii\web\View */
?>
<script>


    $(function () {
        //选择课程
        $('.hotWord').click(function () {
            $('.hotWordList').show();
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
        $('.hot .list').hover(function () {
            $(this).addClass('this');
        }, function () {
            $(this).removeClass('this');
        });
        $('#sclName').placeholder({value: '毕加索', ie6Top: 2, ie7Top: 2, top: 10});

    })
</script>
<!--主体-->


<div class="grid_24 main_r">
    <div class="main_cont tezhagnhaioast_problem">
        <div class="title">
            <h4>题目管理</h4>
        </div>
        <?php echo $this->render('//publicView/search/_top_list',array('department'=>$department,'subjectid'=>$subjectid,'homeworkId' =>'')); ?>
        <hr>
        <div class="form_list no_padding_form_list">
            <?php  echo $this->render('_type_listData',array('result'=>$result,'seachArr'=>$seachArr))?>

            <div class="row subTitle_s">
                <?php
                echo Html::beginForm(array_merge([''],$seachArr2),'get') ?>
                <div class="subTitle_r pr">
                    <input type="text" class="text subTitleBar_text" name="text" value="<?php echo $tags ?>">
                   <button type="submit" class="search" id="search">搜索题目</button>
                </div>
                <?php echo Html::endForm() ?>
            </div>

        </div>

        <div class="problem_box clearfix">
            <div class="problem_r">
                <div class="search_top">

                    <p class="font13">共为您找到<em class="font16"><?php echo $page->totalCount >300 ? $page->totalCount * 2 : $page->totalCount; ?></em>道题目</p>

                    <div class="problem_r_list">
                        <h5>增加题目<i></i></h5>
	                    <?php echo $this->render('//publicView/search/_a_link'); ?>
                    </div>
                    <hr>
                </div>

                <div class="tab fl">
                        <div id="update">
                            <?php echo $this->render('_new_topicListData', array('topic_list' => $topic_list, 'page' => $page)); ?>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>