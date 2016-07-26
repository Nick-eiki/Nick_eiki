<?php
namespace frontend\modules\terrace\controllers;

use common\models\JsonMessage;
use frontend\components\BaseAuthController;
use frontend\services\apollo\Apollo_MaterialService;
use frontend\services\BaseService;
use frontend\services\pos\pos_FavoriteFolderService;
use yii\data\Pagination;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-11-17
 * Time: 下午3:50
 */
class MaterialController extends BaseAuthController
{
    public $layout = "lay_ku";

    /**
     *平台资料库查询
     */
    public function actionIndex()
    {
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 6;
        $name = app()->request->getQueryParam('name', '');
        $provience = app()->request->getQueryParam('provience', '');
        $city = app()->request->getQueryParam('city', '');
        $country = app()->request->getQueryParam('country', '');
        $type = app()->request->getQueryParam('type', '1,2');
        $grade = app()->request->getQueryParam('grade', '');
        $subject = app()->request->getQueryParam('subject', '');
        $userId = user()->id;
        $pages->params = ['type' => $type, 'name' => $name, 'provience' => $provience, 'city' => $city, 'country' => $country, 'grade' => $grade, 'subject' => $subject];
        $material = new Apollo_MaterialService();
        $model = $material->searchMaterial($name, $type, $provience, $city, $country, $grade, $subject, $userId, $pages->getPage() + 1, $pages->pageSize);
        $pages->totalCount = intval($model->countSize);
        if (app()->request->isAjax) {
            return $this->renderPartial('_list_material', array('model' => $model->list, 'pages' => $pages));
        }
        return $this->render('index', array('model' => $model->list, 'pages' => $pages));
    }

    /**
     * 平台资料详情
     * @param $id
     */
    public function actionDetails($id)
    {
        $material = new Apollo_MaterialService();
        $userId = user()->id;
        $model = $material->getMaterialById($id, '', $userId);
        if ($model == null) {
            return $this->notFound();
        }
        return $this->render('details', array('model' => $model));
    }

    /**
     *添加下载次数
     */
    public function actionGetDownNum()
    {
        $id = app()->request->getQueryParam('id', 0);
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

    /**
     *添加资料收藏 取消收藏
     */
    public function actionAddMaterial()
    {
        $add = new pos_FavoriteFolderService();
        $id = app()->request->getQueryParam('id', 0);
        $userId = user()->id;
        $favoriteType = app()->request->getQueryParam('type', '');
        $action = app()->request->getQueryParam('action', '');
        if ($action == 1) {
            $model = $add->addFavoriteFolder($id, $favoriteType, $userId);
        } else {
            $model = $add->delFavoriteFolderByDtl($id, $favoriteType, $userId);
        }
        $jsonResult = new JsonMessage();
        if ($model->resCode === BaseService::successCode) {
            $jsonResult->success = true;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = '收藏失败！';
            return $this->renderJSON($jsonResult);
        }
    }


    /**
     *删除资料收藏
     */
    public function actionDelMaterial()
    {
        $id = app()->request->getQueryParam('id', 0);
        $favorite = new pos_FavoriteFolderService();
        $model = $favorite->delFavoriteFolder($id);
        $jsonResult = new JsonMessage();
        if ($model->resCode === BaseService::successCode) {
            $jsonResult->success = true;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = '取消收藏失败！';
            return $this->renderJSON($jsonResult);
        }
    }
}