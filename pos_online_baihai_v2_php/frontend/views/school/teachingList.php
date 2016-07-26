<?php
/**
 * Created by unizk.
 * User: ysd
 * Date: 14-9-12
 * Time: 上午10:38
 */
/* @var $this yii\web\View */  $this->title='学校-教研组';
?>

<div class="main_cont teaching_group">
    <div class="title">
        <h4>本校教师管理</h4>
        <div class="title_r clearfix">
            <div class="subTitle_r fl">
                <input id="searchText" type="text" class="text" style=" width:216px; height:12px;">
                <button type="button" class="hideText TextBtn  searchBtn ">搜索</button>
            </div>
        </div>
    </div>
    <div class="teaching_group_cont">
        <div id="teaching">
            <?php echo $this->render('_teaching_list_view', array('modelList' => $modelList, 'pages' => $pages)) ?>
        </div>
    </div>
</div>