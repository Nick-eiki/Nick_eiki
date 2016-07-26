<?php
namespace frontend\modules\student\controllers;
use common\models\JsonMessage;
use frontend\components\StudentBaseController;
use frontend\services\apollo\Apollo_MaterialService;
use frontend\services\apollo\Apollo_VideoLessonInfoService;
use frontend\services\BaseService;
use frontend\services\pos\pos_FavoriteFolderService;
use yii\data\Pagination;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-20
 * Time: 下午2:15
 */
class CollectionController extends StudentBaseController{
    public $layout = "lay_user";

    /**
     *高：2014.10.20
     * 学生收藏列表
     */
    public function actionCollectionList(){
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 10;
        $userId = user()->id;
        $student = new pos_FavoriteFolderService();
        $type = "1,2,3";
        $model = $student->queryFavoriteFolder($userId,$type,$pages->getPage() + 1, $pages->pageSize,'');
        $pages->totalCount = intval($model->countSize);
        if(!empty($model)){
            return $this->render('collectionList',array('model'=>$model->list,'pages'=>$pages));
        }

    }

    /**
     *高：2014.10.20
     * 学生取消收藏
     */
    public function actionDelCollection(){
      $id = intval($_POST['collectID']);
        $collect = new pos_FavoriteFolderService();
        $delId=$collect->delFavoriteFolder($id);
        $jsonResult = new JsonMessage();
        if ($delId->resCode === BaseService::successCode) {
            $jsonResult->success = true;
            $jsonResult->message = '删除成功！';
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = '删除失败！';
            return $this->renderJSON($jsonResult);
        }
    }

    /**
     * 学生查询详情
     * @param $id
     */
    public function actionDetail($id){
        $detail = new Apollo_MaterialService();
        $userId=user()->id;
        $model = $detail->getMaterialById($id,'',$userId);
        if($model==null){
            return $this->notFound();
        }

        if(!empty($model)){
            return $this->render('detail', array('model' => $model));
        }
    }

    /**
     * 学生查询视频详情
     * @param $id
     */
    public function actionVideoDetail($id){
        $video = new Apollo_VideoLessonInfoService();
        $model = $video->videoLessonSearch($id);
		if(empty($model->videoLessonList)){
            return $this->notFound();
        }
        return $this->render('videoDetail', array('model' => $model->videoLessonList[0]));
    }

    /**
     *收藏讲义
     */
    public function actionHandout()
    {
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 5;
        $userId = user()->id;
        $type = 2;
        $student = new pos_FavoriteFolderService();
        $model = $student->queryFavoriteFolder($userId, $type, $pages->getPage() + 1, $pages->pageSize, '');
        $pages->totalCount = intval($model->countSize);
        return $this->render('handout', array('model' => $model->list, 'pages' => $pages));
    }

    /**
     *收藏视频
     */
    public function actionVideoFavorites()
    {
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 5;
        $userId = user()->id;
        $type = 3;
        $student = new pos_FavoriteFolderService();
        $model = $student->queryFavoriteFolder($userId, $type, $pages->getPage() + 1, $pages->pageSize, '');
        $pages->totalCount = intval($model->countSize);
        return $this->render('videoFavorites',array('model' => $model->list, 'pages' => $pages));
    }

    /**
     *下载次数
     */
    public function actionGetDownNum(){
        $id = app()->request->getQueryParam('id',0);
        $readNum =new Apollo_MaterialService();
        $model=$readNum->increaseDownNum($id,'');
        $jsonResult = new JsonMessage();
        if ($model->resCode == BaseService::successCode) {
            $jsonResult->success = true;
            $jsonResult->data =$model->data->downNum;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = "添加失败！";
            return $this->renderJSON($jsonResult);
        }
    }
}