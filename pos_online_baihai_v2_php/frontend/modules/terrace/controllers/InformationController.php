<?php
/**
 * Created by PhpStorm.
 * User: ling
 * Date: 2014/11/14
 * Time: 16:28
 */
namespace frontend\modules\terrace\controllers;

use common\models\JsonMessage;
use frontend\components\BaseAuthController;
use frontend\models\AddinfoForm;
use frontend\services\BaseService;
use frontend\services\pos\pos_EduInformationService;
use yii\data\Pagination;

class InformationController extends BaseAuthController
{
    public $layout = "lay_ku";

    /*
     * 资讯列表页
     */
    public function actionInformationList()
    {
        $userId = user()->id;
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 10;
        $getType = app()->request->getQueryParam('getType', '');
        $pages->params = array('getType' => $getType);
        $dataBag = new AddinfoForm();
        $material = new pos_EduInformationService();
        $result = $material->queryEducInformation('', '', $getType, '', '', '', '', '', $pages->getPage() + 1, $pages->pageSize);
        $pages->totalCount = intval($result->countSize);
        if (app()->request->isAjax) {
            return $this->renderPartial('_informationList', array('data' => $result->list, 'pages' => $pages));
        }
        return $this->render('informationList', array('data' => $result->list, 'dataBag' => $dataBag, 'pages' => $pages));
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
                return $this->redirect(url('teacher/information/publish-scuccess', array('informationId' => $informationId)));
            }
        }

        return $this->render('addInformation', array('model' => $dataBag));
    }

    /**
     * 资讯详情
     */

    public function actionInformationDetail()
    {
        $informationId = app()->request->getQueryParam('informationID', 0);
        $type = 50401;
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 5;
        $material = new pos_EduInformationService();
        $result = $material->queryEducInformationByid($informationId);

        if (empty($result->data->informationList)) {
            return $this->notFound();
        }
        //根据资讯id查询上一条记录详情（旧记录 下一篇）
        $upPage = $material->queryPreviousPageByid($informationId, '');
        //根据资讯id查询下一条记录详情（新纪录 上一篇）
        $nextPage = $material->queryNextPageByid($informationId, '');

        //评论列表
        $replyList = $material->searchCommentInformation($informationId, $type, $pages->getPage() + 1, $pages->pageSize);
        $pages->totalCount = intval($replyList->countSize);

        if (app()->request->isAjax) {
            return $this->renderPartial('_information_comment_view', array(
                'data' => $replyList->list,
                'pages' => $pages));
            return;
        }
        return $this->render('informationDetail', array('model' => $result->data->informationList, 'data' => $replyList->list, 'pages' => $pages, 'upPage' => $upPage->data, 'nextPage' => $nextPage->data,));
    }


    /**
     * 添加评论
     */
    public function actionReplyInformation()
    {
        $commentContent = app()->request->getQueryParam('comment', 0);
        $informationId = app()->request->getQueryParam('informationId', 0);
        $informationName = $_POST['informationName'];
        $commentUserID = user()->id;
        $commentType = 50401;
        $material = new pos_EduInformationService();
        $result = $material->commentAdd($commentContent, $informationId, $commentUserID, $commentType, $informationName);
        $jsonResult = new JsonMessage();
        if ($result->resCode == BaseService::successCode) {
            $jsonResult->success = true;
            $jsonResult->message = '添加成功！';
        } else {
            $jsonResult->success = false;
            $jsonResult->message = '添加失败！';
        }
        return $this->renderJSON($jsonResult);
    }

    /**
     *对评论进行回复
     */
    public function actionReplayComment()
    {
        $commentId = app()->request->getQueryParam("commentId", 0);
        $replyContent = app()->request->getQueryParam("replyContent", 0);
        $replayTargetUserID = app()->request->getQueryParam("targetUserId", 0);
        $replayType = 50401;
        $replayUserId = user()->id;
        $material = new pos_EduInformationService();
        $result = $material->replyAdd($commentId, $replyContent, $replayUserId, $replayTargetUserID, $replayType);
        $jsonResult = new JsonMessage();
        if ($result->resCode == BaseService::successCode) {
            $jsonResult->success = true;
            $jsonResult->message = '添加成功！';

        } else {
            $jsonResult->success = false;
            $jsonResult->message = '添加失败！';
        }
        return $this->renderJSON($jsonResult);
    }

    /**
     * 删除评论
     */
    public function actionDeleteComment()
    {
        $commentId = app()->request->getQueryParam("commentId", 0);
        $material = new pos_EduInformationService();
        $result = $material->commentDelete($commentId);
        $jsonResult = new JsonMessage();
        if ($result->rosCode == BaseService::successCode) {
            $jsonResult->success = true;
        } else {
            $jsonResult->success = false;
            $jsonResult->message = '删除失败！';
        }
        return $this->renderJSON($jsonResult);
    }

    //举报评论
    public function actionReportComment()
    {
        $commentId = app()->request->getQueryParam('commentId', 0);
        $material = new pos_EduInformationService();
        $result = $material->commentReport($commentId);
        $jsonResult = new JsonMessage();
        if ($result->rosCode == BaseService::successCode) {
            $jsonResult->success = true;
            $jsonResult->message = "举报成功！";
        } else {
            $jsonResult->success = false;
            $jsonResult->message = "举报失败！";
        }
        return $this->renderJSON($jsonResult);
    }

    //删除回复
    public function actionDeleteReplay()
    {
        $replayId = app()->request->getQueryParam("replayId", 0);

        $material = new pos_EduInformationService();
        $result = $material->replyDelete($replayId);
        $jsonResult = new JsonMessage();
        if ($result->rosCode == BaseService::successCode) {
            $jsonResult->success = true;
        } else {
            $jsonResult->success = false;
            $jsonResult->message = '删除失败！';
        }
        return $this->renderJSON($jsonResult);
    }

    //举报回复
    public function actionReportReplay()
    {
        $replayId = app()->request->getQueryParam('replayId', 0);
        $material = new pos_EduInformationService();
        $result = $material->replayReport($replayId);
        $jsonResult = new JsonMessage();
        if ($result->rosCode == BaseService::successCode) {
            $jsonResult->success = true;
            $jsonResult->message = "举报成功！";
        } else {
            $jsonResult->success = false;
            $jsonResult->message = "举报失败！";
        }
        return $this->renderJSON($jsonResult);
    }

    //对回复进行回复
    public function actionPReplay()
    {
        $preplayId = app()->request->getQueryParam('preplayId', 0);
        $commentId = app()->request->getQueryParam("commentId", 0);
        $targetUers = app()->request->getQueryParam("targetUers", 0);
        $replayContent = app()->request->getQueryParam("replayContent", 0);
        $replayType = 50401;
        $replayUserId = user()->id;
        $material = new pos_EduInformationService();
        $result = $material->preplyAdd($preplayId, $replayContent, $commentId, $replayUserId, $targetUers, $replayType);

        $jsonResult = new JsonMessage();
        if ($result->resCode == BaseService::successCode) {
            $jsonResult->success = true;
            $jsonResult->message = '添加成功！';

        } else {
            $jsonResult->success = false;
            $jsonResult->message = '添加失败！';
        }
        return $this->renderJSON($jsonResult);
    }
}