<?php
namespace frontend\modules\teacher\controllers;
use common\models\JsonMessage;
use frontend\components\TeacherBaseController;
use frontend\models\dicmodels\DegreeModel;
use frontend\modules\teacher\models\CameraAddPaperForm;
use frontend\services\apollo\Apollo_QuestionInfoService;
use frontend\services\apollo\Apollo_QuestionTypeService;
use frontend\services\apollo\Apollo_QustionManageService;
use frontend\services\BaseService;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\helpers\Url;


class TestpaperController extends TeacherBaseController
{
    public $layout = "lay_user";

    /**
     * 题目管理
     */

    public function actionTopicManage()
    {
        $tags = app()->request->getParam('text', '');
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 10;
        $uid = user()->id;
        $obj = new Apollo_QustionManageService();
        $topic_list = $obj->questionSearch($uid, $tags, $pages->getPage() + 1, $pages->pageSize);
        if (!empty($topic_list)) {
            $pages->totalCount = intval($topic_list->countSize);
        }
        $pages->params = ['text' => $tags];
        if (app()->request->isAjax) {
            return $this->renderPartial('_topicListData', array('item' => $topic_list, 'page' => $pages));

        }
        return $this->render('topicManage', array("item" => $topic_list, "page" => $pages, "tags" => $tags));
    }

    public function actionTopicEdit()
    {
        $topId = $_GET['topic'];
        $userId = user()->id;
        $obj = new Apollo_QustionManageService();
        $reslut = $obj->questionSearch($userId, '', '', '', $topId);
        $reslut->list[0]->operation = 2;
        return $this->render('addtopic', array('data' => $reslut->list[0]));
    }

    //修改题目难度
    public function actionModifyComplexity()
    {
        $userId = user()->id;
        $topId = $_POST['tid'];
        $complexity = $_POST['val'];
        $obj = new Apollo_QustionManageService();
        $result = $obj->modifyQuestionSomeInfo($topId, $userId, $complexity);
        $result->data = DegreeModel::model()->getDegreeName($complexity);
        return $this->renderJSON($result);
    }

    //录入题目
    public function actionAddTopic()
    {
        $user = user()->id;
        $questionInfo = new Apollo_QustionManageService();
        $reslut = $questionInfo->createTempQuestion($user);
        $reslut->operation = 1;
        return $this->render('addtopic', array('data' => $reslut));
    }

    //保存题目头部
    public function actionSaveQuestionHead()
    {

        $id = $_POST['questionID'];
        $questionPrice = $_POST['questionPrice'];
        $provience = $_POST['provience'];
        $city = $_POST['city'];
        $country = $_POST['county'];
        $gradeid = $_POST['gradeID'];
        $subjectid = $_POST['subjectID'];
        $versionid = $_POST['versionID'];
        $source = $_POST['source'];//考试分类
        $year = $_POST['year'];
        $from = $_POST['from'];
        $nandu = $_POST['nandu'];
        $queslevel = $_POST['queslevel'];
        $capacity = $_POST['capacity'];
        $tags = $_POST['tags'];
        $typeId = $_POST['tqtid'];
        $kid = $_POST['kid'];
        $name = '';
        $content = $_POST['name'];
        $textcontent = '';
        $questionInfo = new Apollo_QustionManageService();
	    $result = $questionInfo->saveQuestionHead($id, $provience, $city, $country, $gradeid, $subjectid, $versionid, $kid, $typeId, $source, $year,
            $nandu, $capacity, $tags, $name, $questionPrice, $queslevel, $from, $content, $textcontent);
        return $this->renderJSON($id);
    }

    //保存题目内容
    public function  actionSaveQuesContent()
    {
        if ($_POST) {

            $id = $_POST['id'];
            $analytical = $_POST['analytical'];

            $answerOptionJson = app()->request->post('answerOptionJson','');
            $answerContent =  app()->request->post('answerContent','');
            $childQuesJson = app()->request->post('childQuesJson','');
            $saveType = app()->request->post('saveType', '');
            $obj = new Apollo_QustionManageService();
            $result = $obj->saveQuestionContent($id, $answerOptionJson, $answerContent, $analytical, $childQuesJson, $saveType);
            $jsonResult = new JsonMessage();
            if ($result->resCode == BaseService::successCode) {
                $jsonResult->success = true;
            } else {
                $jsonResult->success = false;
            }
            return $this->renderJSON($jsonResult);
        }
        $topic = $_GET['question'];
        $operation = $_GET['operation'];
        $userId = user()->id;
        $questionInfo = new Apollo_QustionManageService();
        if ($operation == 1) {
            $info = $questionInfo->queryTempQuesById($topic);
            $info->answerOptionJson = [];
        } else {
            $info = $questionInfo->questionSearchByid($userId, $topic);
            $info->answerOptionJson = json_decode($info->answerOption);
            if (!empty($info->childQues)) {
                foreach ($info->childQues as $key => $val) {
                    $info->childQues[$key]->answerOptionJson = json_decode($val->answerOption);
                    $info->childQues[$key]->quesType = $val->showTypeId;
                    if (!empty($val->childQues)) {
                        foreach ($val->childQues as $keys => $vals) {

                            $info->childQues[$key]->childQuesJson = $val->childQues;
                            $info->childQues[$key]->childQuesJson[$keys]->quesType = $vals->showTypeId;

                        }
                    }

                }
            }
        }

        $child = new Apollo_QuestionTypeService();
        $data = $child->queryQuesTypeSubs($info->tqtid);
        $childType = '';
        if (empty($data)) {
            $childType = Html::tag('option', Html::encode($info->questiontypename), array('value' => $info->tqtid, 'showTypeId' => $info->showTypeId));
        } else {
            foreach ($data as $item) {
                $childType .= Html::tag('option', Html::encode($item->typeName), array('value' => $item->typeId, 'showTypeId' => $item->showTypeId));
            }
        }
        return $this->render("addtopicmain", array('info' => $info, 'childType' => $childType));
    }

    // 录入完成跳转页
    public function actionTopicFinish()
    {
        $status = $_GET['status'];
        return $this->render("topicfinish", array('status' => $status));
    }

    //预览
    public function actionViewTopic()
    {
        $topId = $_POST['id'];
        $userId = user()->id;
        $obj = new Apollo_QustionManageService();
        $result = $obj->queryTempQuesById($topId);
        return $this->renderPartial('_viewTopicData', array('item' => $result));
    }

    //拍照录题
    public function actionCameraUploadNewTopic(){

        $model = new CameraAddPaperForm();
        $getModel = loginUser()->getModel(false);


        $model->provience = $getModel->provience;
        $model->city = $getModel->city;
        $model->country = $getModel->country;
        $model->gradeid = $getModel->getUserInfoInClass()[0]['gradeID'];
        $model->subjectid = $getModel->subjectID;
        $model->versionid = $getModel->textbookVersion;

        if(isset($_POST['CameraAddPaperForm'])){
            $picurls = $_POST['picurls'];
            $_POST['CameraAddPaperForm']['content'] = implode(',',$picurls);
            if(isset($_POST['imgurls'])){
                $imgurls = $_POST['imgurls'];
                $_POST['CameraAddPaperForm']['answerContent'] = implode(',',$imgurls);
            }

            $model->attributes = $_POST['CameraAddPaperForm'];
            if ($model->validate()) {
                $obj = new Apollo_QuestionInfoService();
                $createQt = $obj->questionPicAdd($model,'','',user()->id);
                if ($createQt->resCode == BaseService::successCode) {
                    return $this->redirect(Url::to('/teacher/searchquestions/knowledge-point-questions'));
                }
            }
        }

        return $this->render('camerauploadnewtopic',array('model'=>$model));
    }

    //拍照录题修改
    public function actionModifyCameraUploadNewTopic(){

        $model = new CameraAddPaperForm();

        $id = app()->request->getParam('id','');
        $questionInfo = new Apollo_QustionManageService();
        $result = $questionInfo->questionSearchByid(user()->id,$id);

        $model->provience = $result->provience;
        $model->city = $result->city;
        $model->country = $result->country;
        $model->gradeid = $result->gradeid;
        $model->subjectid = $result->subjectid;
        $model->versionid = $result->versionid;
        $model->complexity = $result->complexity;
        $model->content = $result->content;
        $model->answerContent = $result->answerContent;

        if(isset($_POST['CameraAddPaperForm'])){
            $picurls = $_POST['picurls'];
            $_POST['CameraAddPaperForm']['content'] = implode(',',$picurls);
            if(isset($_POST['imgurls'])){
                $imgurls = $_POST['imgurls'];
                $_POST['CameraAddPaperForm']['answerContent'] = implode(',',$imgurls);
            }

            $model->attributes = $_POST['CameraAddPaperForm'];

            if ($model->validate()) {
                $obj = new Apollo_QuestionInfoService();
                $createQt = $obj->questionPicUpdate($model,$id,'','',user()->id);
                if ($createQt->resCode == BaseService::successCode) {
                    return $this->redirect(url('teacher/searchquestions/knowledge-point-questions'));
                }
            }
        }
        return $this->render('modifycamerauploadnewtopic',array('model' => $model));
    }

    //修改题目录入，第一步
    public function actionModifyTopic(){

        $id = app()->request->getParam('id','');
        $questionInfo = new Apollo_QustionManageService();
        $result = $questionInfo->questionSearchByid(user()->id,$id);
        $result->operation = 1;


        return $this->render('modifytopic' ,array('data' => $result));
    }

    //修改题目内容，第二步
    public function  actionModifyQuesContent()
    {
        if ($_POST) {

            $id = $_POST['id'];
            $analytical = $_POST['analytical'];

            $answerOptionJson = app()->request->post('answerOptionJson','');
            $answerContent =  app()->request->post('answerContent','');
            $childQuesJson = app()->request->post('childQuesJson','');
            $saveType = app()->request->post('saveType', '');
            $obj = new Apollo_QustionManageService();
            $result = $obj->saveQuestionContent($id, $answerOptionJson, $answerContent, $analytical, $childQuesJson, $saveType);
            $jsonResult = new JsonMessage();
            if ($result->resCode == BaseService::successCode) {
                $jsonResult->success = true;
            } else {
                $jsonResult->success = false;
            }
            return $this->renderJSON($jsonResult);
        }
        $topic = $_GET['question'];
        $operation = $_GET['operation'];
        $userId = user()->id;
        $questionInfo = new Apollo_QustionManageService();
        $info = $questionInfo->questionSearchByid($userId, $topic);
        $info->answerOptionJson = json_decode($info->answerOption);
        if (!empty($info->childQues)) {
            foreach ($info->childQues as $key => $val) {
                $info->childQues[$key]->answerOptionJson = json_decode($val->answerOption);
                $info->childQues[$key]->quesType = $val->showTypeId;
                if (!empty($val->childQues)) {
                    foreach ($val->childQues as $keys => $vals) {

                        $info->childQues[$key]->childQuesJson = $val->childQues;
                        $info->childQues[$key]->childQuesJson[$keys]->quesType = $vals->showTypeId;

                    }
                }

            }
        }

        $child = new Apollo_QuestionTypeService();
        $data = $child->queryQuesTypeSubs($info->tqtid);
        $childType = '';
        if (empty($data)) {
            $childType = Html::tag('option', Html::encode($info->questiontypename), array('value' => $info->tqtid, 'showTypeId' => $info->showTypeId));
        } else {
            foreach ($data as $item) {
                $childType .= Html::tag('option', Html::encode($item->typeName), array('value' => $item->typeId, 'showTypeId' => $item->showTypeId));
            }
        }
        return $this->render("modifytopicmain", array('info' => $info, 'childType' => $childType));
    }

}