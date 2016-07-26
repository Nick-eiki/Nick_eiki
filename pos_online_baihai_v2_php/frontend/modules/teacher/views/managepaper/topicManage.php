<?php

	/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title="题目管理";
	$this->registerJsFile(publicResources() . '/js/My97DatePicker/WdatePicker.js', [ 'position'=>\yii\web\View::POS_HEAD] );

	$backend_asset = publicResources();
	;
	$this->registerJsFile($backend_asset . '/js/jquery.validationEngine-zh_CN.js'.RESOURCES_VER);
	$this->registerJsFile($backend_asset . '/js/jquery.validationEngine.min.js');
	$this->registerJsFile($backend_asset . '/js/register.js'.RESOURCES_VER);


?>

<!--主体内容开始-->

    <div class="currentRight grid_16 push_2 topic_magmt">
        <div class="crumbs"><a href="#">教师</a> &gt;&gt; <a href="#">题目管理</a> &gt;&gt; 题目列表</div>
        <div class="noticeH clearfix">
            <h3 class="h3L">题目管理</h3>
            <div class="new_not fr">
                <a href="<?php echo url("teacher/testpaper/add-topic")?>" class="B_btn120 btn uploadNewtestpaperBtn">录入题目</a> </div>
        </div>
        <hr>
        <div class="searchBar">
            <?php echo Html::beginForm('','get') ?>
                <ul class="form_list">
                    <li>
                        <div class="formL">
                            <label>题目关键字：</label>
                        </div>
                        <div class="formR">
                            <input type="text" class="text" name="text" value="<?php echo $tags ?>">
                            <button type="submit" class="bg_red_d searchBtn" id="search">搜索</button>
                    </li>
                </ul>
            <?php echo Html::endForm() ?>
        </div>
        <div id="update">
        <?php echo $this->render('_topicListData',array('item'=>$item,'page'=>$page));?>
        </div>
    </div>

<!--主体内容结束-->
