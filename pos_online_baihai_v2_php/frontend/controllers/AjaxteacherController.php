<?php

namespace frontend\controllers;

use common\models\JsonMessage;
use frontend\components\TeacherBaseController;
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\GradeModel;
use frontend\models\dicmodels\KnowledgePointModel;
use frontend\services\BaseService;
use frontend\services\pos\pos_MessageSendByUserService;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: a
 * Date: 14-6-24
 * Time: 下午2:45
 */
class AjaxteacherController extends TeacherBaseController
{

    /**
     *请求知识树
     */
    public function actionGetKnowledge()
    {

        $jsonResult = new JsonMessage();
        $jsonResult->data = [];
        $subjectID = app()->request->post('subjectID', null);
        $grade = app()->request->post('grade', null);
        if ($subjectID == null || $grade == null) {
            return $this->renderJSON($jsonResult);
        }

        $knowledgePoint = KnowledgePointModel::searchKnowledgePointGradeToTree($subjectID, $grade);
        if (!empty($knowledgePoint)) {
            $jsonResult->success = true;
            $jsonResult->data = $knowledgePoint;

        } else {
            $jsonResult->success = true;
        }
        return $this->renderJSON($jsonResult);
    }

    public function actionGetKnowledgeByDepartmentId()
    {

        $jsonResult = new JsonMessage();
        $jsonResult->data = [];
        $subjectID = app()->request->getQueryParam('subjectID', null);
        $departmentId = app()->request->getQueryParam('departmentId', null);
        if ($subjectID == null || $departmentId == null) {
            return $this->renderJSON($jsonResult);
        }

        $knowledgePoint = KnowledgePointModel::searchKnowledgePointToTree($subjectID, $departmentId);
        if (!empty($knowledgePoint)) {
            $jsonResult->success = true;
            $jsonResult->data = $knowledgePoint;

        } else {
            $jsonResult->success = true;
        }
        return $this->renderJSON($jsonResult);
    }

    /**
     *请求章节树
     */
    public function actionGetChapter()
    {
        $jsonResult = new JsonMessage();
        $subjectID = app()->request->getQueryParam('subjectID', null);
        $materials = app()->request->getQueryParam('materials', null);
        $grade = app()->request->getQueryParam('grade', null);
        $chapter = ChapterInfoModel::searchChapterPointToTree($subjectID, '', $materials, '', $grade);
        if (!empty($chapter)) {
            $jsonResult->success = true;
            $jsonResult->data = $chapter;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = "查询失败";
            $jsonResult->data = [];
            return $this->renderJSON($jsonResult);
        }
    }

    /**
     *根据学籍获取年级
     */
    public function actionGetGrade()
    {
        $schoolLevel = app()->request->getQueryParam('schoolLevel', 0);
        $data = GradeModel::model()->getWithList($schoolLevel, '');
        $data = ArrayHelper::map($data, 'gradeId', 'gradeName');
        return $this->renderJSON($data);
    }

    /**
     *手动发送通知
     */
    public function actionSendMessage()
    {
        $jsonResult = new JsonMessage();
        $objectId = app()->request->getBodyParam("objectId");
        $messageType = app()->request->getBodyParam("messageType");
        $studentID = app()->request->getBodyParam("studentID");
        $server = new pos_MessageSendByUserService();
        $messageResult = $server->sendMessageByObjectId($objectId, $messageType, user()->id, $studentID);
        if ($messageResult->resCode == BaseService::successCode) {
            $jsonResult->code = 1;
        } else {
            $jsonResult->code = 0;
        }
        $jsonResult->message = $messageResult->resMsg;
        return $this->renderJSON($jsonResult);
    }

} 