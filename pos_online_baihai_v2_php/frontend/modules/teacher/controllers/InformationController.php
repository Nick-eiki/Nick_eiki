<?php
namespace frontend\modules\teacher\controllers;
use frontend\components\BaseAuthController;
use frontend\models\AddinfoForm;
use frontend\modules\teacher\models\updateInformationForm;
use frontend\services\BaseService;
use frontend\services\pos\pos_EduInformationService;
use yii\data\Pagination;

/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/11/14
 * Time: 16:28
 */

class InformationController extends BaseAuthController
{
    public $layout = "lay_user";
    /*
     * 资讯列表页
     */
    public function actionInformationList()
    {
        $userId = user()->id;
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 5;
        $getType = app()->request->getParam('getType', '');
        $pages->params = array('getType' => $getType);
        $dataBag = new AddinfoForm();
        $material = new pos_EduInformationService();
        $result = $material->queryEducInformation('', '', $getType, '', '', $userId, '', '',  $pages->getPage() + 1, $pages->pageSize);
        $pages->totalCount = intval($result->countSize);
        if (app()->request->isAjax) {
            return $this->renderPartial('_informationList', array('data' => $result->list, 'pages' => $pages));
        }
        return $this->render('informationList', array('data' => $result->list, 'dataBag'=>$dataBag, 'pages' => $pages));
    }


    /*
     * 发布资讯
     */

    public function actionAddInformation()
    {
        $userId = user()->id;
        $dataBag = new AddinfoForm();
        if (isset($_POST['AddinfoForm'])) {
            $dataBag->attributes = $_POST['AddinfoForm'];
            $material = new pos_EduInformationService();
            $result = $material->addEduInformation($dataBag->informationTitle, $dataBag->informationType, $dataBag->informationContent, $dataBag->informationKeyWord, $userId);
            $informationId = $result->data->honorID;
            if ($result->resCode == pos_EduInformationService::successCode) {
                 return $this->redirect(url('teacher/information/publish-scuccess',array('informationId'=>$informationId)));
            }
        }
            return $this->render('addInformation', array('model' => $dataBag));
    }



    /**
     * 资讯详情
     */

    public function actionInformationDetail()
    {

        $informationId = app()->request->getParam('informationID', '');
        $type = 50401;
        $material = new pos_EduInformationService();
        $result = $material->queryEducInformationByid($informationId);
		if(empty($result->data->informationList)){
			return $this->notFound();
		}
        //评论列表
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 3;
        $replyList = $material->searchCommentInformation($informationId, $type, $pages->getPage() + 1, $pages->pageSize);
        $pages->totalCount = intval($replyList->countSize);

        //根据资讯id查询上一条记录详情（旧记录 下一篇）
        $upPage = $material->queryPreviousPageByid($informationId,user()->id);
        //根据资讯id查询下一条记录详情（新纪录 上一篇）
        $nextPage = $material->queryNextPageByid($informationId,user()->id);

        if (app()->request->isAjax) {
            return $this->renderPartial('_information_comment_view', array(
                'data' => $replyList->list,
                'pages' => $pages));

        }
        return $this->render('informationDetail',array('model'=>$result->data->informationList, 'data' => $replyList->list, 'pages' => $pages, 'upPage'=>$upPage->data, 'nextPage'=>$nextPage->data,));
    }

    /**
     * 修改咨询
     */
    public function actionInformationUpdate()
    {
        $informationId = app()->request->getParam('informationID', 0);
        $userId = user()->id;
        $material = new pos_EduInformationService();
        $result = $material->queryEducInformationByid($informationId);
        $dataBag = new updateInformationForm();
        $dataBag->informationID    = $result->data->informationList[0]->informationID;
        $dataBag->informationTitle = $result->data->informationList[0]->informationTitle;
        $dataBag->informationType = $result->data->informationList[0]->informationType;
        $dataBag->informationContent = $result->data->informationList[0]->informationContent;
        $dataBag->informationKeyWord = $result->data->informationList[0]->informationKeyWord;
        if (isset($_POST['updateInformationForm'])) {
            $dataBag->attributes = $_POST['updateInformationForm'];
            if($dataBag->validate()) {
                $updateInformation = $material->modifyEducInformation($informationId, $dataBag->informationTitle, $dataBag->informationType, $dataBag->informationContent, $dataBag->informationKeyWord,  $userId);
                if($updateInformation->resCode == BaseService::successCode)
                {
                    return $this->redirect(url('teacher/information/publish-scuccess',array('updateInformationId'=>$informationId)));
                }
            }
        }

        return $this->render('informationUpdate', array('model' => $dataBag));
    }

    /**
     * 成功验证页面
     */
    public function actionPublishScuccess()
    {
        $informationId = app()->request->getParam('informationId', 0);
        $updateInformationId = app()->request->getParam('updateInformationId', 0);

        $material = new pos_EduInformationService();
        if($informationId == 0){
            $result = $material->queryEducInformationByid($updateInformationId);
        }elseif($updateInformationId == 0){
            $result = $material->queryEducInformationByid($informationId);
        }
        return $this->render('publishScuccess',array('model'=>$result->data->informationList));
    }
}