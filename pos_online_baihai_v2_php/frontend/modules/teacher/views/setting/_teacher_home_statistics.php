<?php
//创建的作业数
use common\models\pos\SeFavoriteMaterial;
use common\models\pos\SeHomeworkTeacher;
use common\models\pos\SeQuestionFavoriteFolderNew;
use common\models\sanhai\SrMaterial;
use frontend\services\pos\pos_PaperManageService;
use yii\data\Pagination;
//自己创建的作业数
$creatHomeworkNum = SeHomeworkTeacher::getCreateHomeworkNum($userId);

//收藏的平台作业数
$collectHomeworkNum = SeHomeworkTeacher::getCollectHomeworkNum($userId);

//获取收藏的题目
$favoriteTitleNum = SeQuestionFavoriteFolderNew::getfavoriteQuestionNum($userId);

//获取收藏的课件数
$favoriteFileNum = SeFavoriteMaterial::favoriteFileNum($userId);

//获取创建的课件数
$createFileNum = SrMaterial::getCreateFileCount($userId);

//获取创建的试卷的数量
$pages = new Pagination();
$pagerServer = new pos_PaperManageService();
$result = $pagerServer->searchPapeer($userId, $pages->getPage() + 1,$pages->pageSize,'', '','','','');
$createTestNum = intval($result->countSize);

?>

<ul>
  <li class="home_hmwk">
    <a href="<?= url('teacher/resources/collect-work-manage');?>"><i></i>作业</a>
    <div><p>我收藏的：</p><span><?php echo $collectHomeworkNum;?></span></div>
    <div><p>我创建的：</p><span><?php echo $creatHomeworkNum;?></span></div>
  </li>
  <li class="home_quest">
    <a href="<?= url('teacher/question/index');?>"><i></i>题目</a>
    <div><p>我收藏的：</p><span><?php echo $favoriteTitleNum;?></span></div>
  </li>
  <li class="home_course">
    <a href="<?= url('teacher/favoritematerial/index');?>"><i></i>课件</a>
    <div><p>我收藏的：</p><span><?php echo $favoriteFileNum;?></span></div>
    <div><p>我的上传：</p><span><?php echo $createFileNum;?></span></div>
  </li>
  <li class="home_test noBorder">
    <a href="<?= url('teacher/managepaper/index');?>"><i></i>试卷</a>
    <div><p>我创建的：</p><span><?php echo $createTestNum;?></span></div>
  </li>
</ul>
