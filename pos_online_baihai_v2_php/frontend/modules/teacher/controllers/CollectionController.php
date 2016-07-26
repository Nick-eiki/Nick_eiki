<?php
namespace frontend\modules\teacher\controllers;
use common\models\JsonMessage;
use frontend\components\TeacherBaseController;
use frontend\services\apollo\Apollo_MaterialService;
use frontend\services\apollo\Apollo_VideoLessonInfoService;
use frontend\services\BaseService;
use frontend\services\pos\pos_FavoriteFolderService;
use yii\data\Pagination;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-20
 * Time: 下午1:59
 */
class CollectionController extends TeacherBaseController
{
//    public $layout = 'lay_collection';
    public $layout = "lay_user";

    /**
     *高：2014.10.20
     * 教师收藏夹列表（这个列表是给其他人看的到时候移走）
     */
    public function actionCollectionList()
    {
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 10;
        $userId = user()->id;
        $type = app()->request->getParam('type', '1,2');
        $student = new pos_FavoriteFolderService();
        $model = $student->queryFavoriteFolder($userId, $type, $pages->getPage() + 1, $pages->pageSize, '');

        $pages->totalCount = intval($model->countSize);
        if (app()->request->isAjax) {
            return $this->renderPartial('_collection_list_view', array('model' => $model->list, 'pages' => $pages));

        }
        $this->render('collectionList', array('model' => $model->list, 'pages' => $pages));

    }

    /**
     *高：2014.20.21
     * 教师收藏夹删除
     */
    public function actionDelCollection()
    {
        $id = intval($_POST['id']);
        $collect = new pos_FavoriteFolderService();
        $delId = $collect->delFavoriteFolder($id);
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
     * 收藏详情
     * @param $id
     */
    public function actionLessonPlanDetail($id)
    {
        $detail = new Apollo_MaterialService();
        $userId = user()->id;
        $model = $detail->getMaterialById($id, '', $userId);
        if ($model == null) {
            return $this->notFound();
        }
      return   $this->render('lessonPlanDetail', array('model' => $model));


    }

    /**
     * 收藏视频详情
     * @param $id
     */
    public function actionVideoDetail($id)
    {
        $video = new Apollo_VideoLessonInfoService();
        $model = $video->videoLessonSearch($id);
        if (empty($model->videoLessonList)) {
            return $this->notFound();
        }
        $this->render('videoDetail', array('model' => $model->videoLessonList[0]));
    }

    /**
     *收藏讲义
     */
    public function actionHandout()
    {
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 2;
        $userId = user()->id;
        $type = 2;
        $student = new pos_FavoriteFolderService();
        $model = $student->queryFavoriteFolder($userId, $type, $pages->getPage() + 1, $pages->pageSize, '');
        $pages->totalCount = intval($model->countSize);
        return $this->render('handout', array('model' => $model->list, 'pages' => $pages));
    }

    /**
     *我收藏的ppt
     */
    public function actionFile()
    {
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 2;
        $userId = user()->id;
        $type = 5;
        $student = new pos_FavoriteFolderService();
        $model = $student->queryFavoriteFolder($userId, $type, $pages->getPage() + 1, $pages->pageSize, '');
        $pages->totalCount = intval($model->countSize);
      return   $this->render('file', array('model' => $model->list, 'pages' => $pages));
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
     return    $this->render('videoFavorites', array('model' => $model->list, 'pages' => $pages));
    }

    /**
     *收藏教案
     */
    public function actionLessonPlan()
    {
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 5;
        $userId = user()->id;
        $type = 1;
        $student = new pos_FavoriteFolderService();
        $model = $student->queryFavoriteFolder($userId, $type, $pages->getPage() + 1, $pages->pageSize, '');
        $pages->totalCount = intval($model->countSize);
        $this->render('lessonPlan', array('model' => $model->list, 'pages' => $pages));
    }

    /**
     *添加下载次数
     */
    public function actionGetDownNum()
    {
        $id = app()->request->getParam('id', 0);
        $readNum = new Apollo_MaterialService();
        $model = $readNum->increaseDownNum($id, '');
        $jsonResult = new JsonMessage();
        if ($model->resCode == BaseService::successCode) {
            $jsonResult->success = true;
            $jsonResult->data = $model->data->downNum;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = "添加失败！";
            return $this->renderJSON($jsonResult);
        }
    }
}