<?php
namespace frontend\modules\teacher\controllers;

use common\helper\DateTimeHelper;
use common\models\JsonMessage;
use common\models\pos\SeClassMembers;
use common\models\pos\SeHomeworkAnswerDetailImage;
use common\models\pos\SeHomeworkAnswerImage;
use common\models\pos\SeHomeworkAnswerInfo;
use common\models\pos\SeHomeworkAnswerQuestionAll;
use common\models\pos\SeHomeworkImage;
use common\models\pos\SeHomeworkPlatform;
use common\models\pos\SeHomeworkPlatformMaterials;
use common\models\pos\SeHomeworkPlatformSuggest;
use common\models\pos\SeHomeworkPlatformVideos;
use common\models\pos\SeHomeworkQuestion;
use common\models\pos\SeHomeworkQuestionPlatform;
use common\models\pos\SeHomeworkRel;
use common\models\pos\SeHomeworkTeacher;
use common\models\sanhai\ShTestquestion;
use common\models\sanhai\ShVideolesson;
use common\models\sanhai\SrMaterial;
use common\models\search\Es_testQuestion;
use common\services\JfManageService;
use frontend\components\helper\AreaHelper;
use frontend\components\helper\StringHelper;
use frontend\components\helper\TreeHelper;
use frontend\components\TeacherBaseController;
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\EditionModel;
use frontend\models\dicmodels\KnowledgePointModel;
use frontend\models\dicmodels\SubjectModel;
use frontend\models\PaperForm;
use frontend\modules\teacher\models\MakePaperForm;
use frontend\services\apollo\Apollo_chapterInfoManage;
use frontend\services\apollo\Apollo_QuestionInfoService;
use frontend\services\apollo\Apollo_QuestionTypeService;
use frontend\services\apollo\Apollo_QuestSearchModel;
use frontend\services\apollo\Apollo_QustionManageService;
use frontend\services\BaseService;
use frontend\services\pos\pos_HomeWorkManageService;
use frontend\services\pos\pos_MessageSendByUserService;
use frontend\services\pos\pos_PaperManageService;
use frontend\services\pos\pos_QueFavoriteFolder;
use stdClass;
use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-3
 * Time: 下午6:34
 */
class ManagetaskController extends TeacherBaseController
{
    public $layout = "lay_user";

    /**
     * 获取选择班级弹框
     */
    public function actionGetClassBox()
    {
        $homeworkId = app()->request->post('homeworkid');
        $type = app()->request->getBodyParam('type', 0);
        $SeHomeworkTeacherQuery = SeHomeworkTeacher::find();
        $homeworkTeaOne = null;
        if ($type) {
            $homeworkTeaOne = $SeHomeworkTeacherQuery->where(['homeworkPlatformId' => $homeworkId, 'creator' => user()->id])->one();
        } else {
            $homeworkTeaOne = $SeHomeworkTeacherQuery->where(['id' => $homeworkId])->one();
        }
        if ($type == 0) {
            $getType = $homeworkTeaOne->getType;
        } else {

            $getType = 0;
        }
        $homeworkRelList = SeHomeworkRel::find()->where(['homeworkId' => $homeworkTeaOne->id])->all();
        $homeworkarr = ArrayHelper::getColumn($homeworkRelList, 'classID');
        $classInfo = loginUser()->getClassInfo();
        $unassignClass = [];
        foreach ($classInfo as $key => $val) {
            if (!in_array($val->classID, $homeworkarr)) {
                array_push($unassignClass, $val->classID);
            }
        }
        return $this->renderPartial('_getclassbox', ['homeworkRelList' => $homeworkRelList, 'unassignClass' => $unassignClass, 'homeworkTeaOne' => $homeworkTeaOne, 'getType' => $getType, 'hmwid'=>$homeworkId]);
    }

    /**
     * 获取选择班级弹框
     */
    public function actionGetClassBoxNew()
    {
        $homeworkId = app()->request->post('homeworkid');
        $type = app()->request->getBodyParam('type', 0);
        $SeHomeworkTeacherQuery = SeHomeworkTeacher::find();
        $homeworkTeaOne = null;
        if ($type) {
            $homeworkTeaOne = $SeHomeworkTeacherQuery->where(['homeworkPlatformId' => $homeworkId, 'creator' => user()->id])->one();
        } else {
            $homeworkTeaOne = $SeHomeworkTeacherQuery->where(['id' => $homeworkId])->one();
        }
        if ($type == 0) {
            $getType = $homeworkTeaOne->getType;
        } else {

            $getType = 0;
        }
        $homeworkRelList = SeHomeworkRel::find()->where(['homeworkId' => $homeworkTeaOne->id])->all();
        $homeworkarr = ArrayHelper::getColumn($homeworkRelList, 'classID');
        $classInfo = loginUser()->getClassInfo();
        $unassignClass = [];
        foreach ($classInfo as $key => $val) {
            if (!in_array($val->classID, $homeworkarr)) {
                array_push($unassignClass, $val->classID);
            }
        }

        return $this->renderPartial('_getclassbox_new', ['homeworkRelList' => $homeworkRelList, 'unassignClass' => $unassignClass, 'homeworkTeaOne' => $homeworkTeaOne, 'getType' => $getType]);
    }

    /**
     * 作业分配到班
     */
    public function actionSendHomework()
    {

        $homeworkId = app()->request->post('homeworkId');

        $jsonResult = new JsonMessage();
        if (isset($_POST['isShare']) && $_POST['isShare'] == 1) {
            //共享到平台
            SeHomeworkTeacher::updateAll(['isShare' => '1'], ['creator' => user()->id, 'id' => $homeworkId]);
        }

        if (isset($_POST['TeacherClassForm']) && !empty($_POST['TeacherClassForm'])) {
            //作业分配的到班级

            $code = true;
            foreach ($_POST['TeacherClassForm'] as $val) {
                if (isset($val) && !empty($val['classID']) && !empty($val['deadlineTime'])) {
                    $homeworkRelModel = new SeHomeworkRel();
                    $homeworkRelModel->classID = $val['classID'];
                    $classMembers=SeClassMembers::getClassNumByClassId($val['classID'],SeClassMembers::STUDENT);
                    $homeworkRelModel->memberTotal=$classMembers;
                    $homeworkRelModel->deadlineTime = strtotime($val['deadlineTime']) ? strtotime($val['deadlineTime']) * 1000 : strtotime("-2 day") * 1000;
                    $homeworkRelModel->homeworkId = $homeworkId;
                    $homeworkRelModel->createTime = time() * 1000;
                    $homeworkRelModel->creator = user()->id;

                    $code = $code && $homeworkRelModel->save(false);

                    //发送消息
                    $work = new  pos_MessageSendByUserService();
                    $work->sendMessageByObjectId($homeworkRelModel->id, 507001, user()->id);

                    if ($code) {
                        $jfHelper=new JfManageService();
                        $jfHelper->myAccount("pos-upl-orgWork",user()->id);
                        $jsonResult->success = true;
                        $jsonResult->message = "布置成功";
                    } else {
                        $jsonResult->message = "布置失败";
                    }
                }
            }

        }else{
            $jsonResult->message = "该班级已经布置过作业";
        }
        return $this->renderJSON($jsonResult);
    }

    /**
     * 通知学生（新加）
     */
    public function actionSendMessageByObjectId()
    {
        $homeworkid = app()->request->getParam('homeworkid');
        $userId = user()->id;
        $jsonResult = new JsonMessage();
        $work = new  pos_MessageSendByUserService();

        $verify = $work->sendMessageByObjectId($homeworkid, 507001, $userId);

        if ($verify->resCode = pos_MessageSendByUserService::successCode) {
            $jsonResult->success = true;
        }

        return $this->renderJSON($jsonResult);
    }

    /**
     *组织作业
     */
    public function actionOrganizeWork()
    {
        return $this->render("organizeWork");
    }

    /**
     *筛选作业
     */
    public function actionScreeningWork()
    {

        return $this->render("screeningWork");
    }

    /**
     *新 布置作业第一步
     */
    public function actionNewFixupWork()
    {

        $model = new  SeHomeworkTeacher();

        //获取科目,学段
        $subjectID = loginUser()->getModel()->subjectID;
        $subjectName = SubjectModel::model()->getSubjectName($subjectID);
        $departments = loginUser()->getModel()->department;

        //获取地区信息
        $countryID = loginUser()->getModel()->country;
        $cityID = loginUser()->getModel()->city;
        $provienceID = loginUser()->getModel()->provience;


        if ($model->load(yii::$app->request->post())) {
            $model->version = $_POST['version'];

            $model->provience = $provienceID;
            $model->city = $cityID;
            $model->country = $countryID;

            $model->createTime = time() * 1000;
            $model->subjectId = $subjectID;
            $model->creator = user()->id;
            $model->department = $departments;

            if ($model->save()) {
                if ($_POST["PaperForm"]["workStyle"] == "upWork") {
                    return $this->redirect(url('teacher/managetask/new-update-work', ['homeworkid' => $model->id]));
                } elseif ($_POST["PaperForm"]["workStyle"] == "OrgWork") {
                    return $this->redirect(url('teacher/managetask/new-preview-organize-paper', ['homeworkid' => $model->id]));
                } else {
                    return $this->redirect(url("teacher/resources/collect-work-manage"));
                }
            }

        }
        if ($model->name == null) {
            $model->name = date('Y年m月d日') . $subjectName . "作业";
        }
        return $this->render('new_fixupWork', array("model" => $model, 'subjectid' => $subjectID, 'departments' => $departments));
    }


    /**
     * 作业详情
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionWorkDetails()
    {

        $classHomeworkId = app()->request->getParam("classhworkid", '');
        $type = app()->request->getParam('type');
        $checkTime=app()->request->getParam('checkTime');
        //查询rel关系表
        $result = SeHomeworkRel::find()->where(['id' => $classHomeworkId])->one();
        //查询是否存在这条记录
        if ($result == null) {
            return $this->notFound("该作业已被删除！");
        }
        //查询已答学生和 数
        $answerStuList = $result->getHomeworkAnswerInfo()->all();
        $answerNumber = count($answerStuList);

        //查询详情信息
        $homeWorkTeacher = $result->getHomeWorkTeacher()->one();

        //查询班级学生数
        $studentList = SeClassMembers::find()->where(['classID' => $result->classID, 'identity' => '20403'])->all();
        $studentMember = count($studentList);
        //查询批改数
        $isCorrections = $result->getHomeworkAnswerInfo()->where(['isCheck' => 1])->count();

        //未批改的作业数
        $noCorrections = $result->getHomeworkAnswerInfo()->where(['isCheck' => 0])->count();

        //查询未批改的
        $queryCount = SeHomeworkAnswerInfo::find()->where(['relId' => $classHomeworkId, 'isCheck' => '0', 'isUploadAnswer'=>'1']);
        $cloneQueryCount = clone $queryCount;
        //截止时间
        $deadlineTime = strtotime(date('Y-m-d 23:59:59', DateTimeHelper::timestampDiv1000($result->deadlineTime))) * 1000;
        //查询按时提交数
        $onTimeNumber = $queryCount->andWhere(['<', 'uploadTime', $deadlineTime])->count();
        //超时提交
        $overtime = $cloneQueryCount->andWhere(['>', 'uploadTime', $deadlineTime])->count();

        //查询已批改的
        $markedQueryCount = SeHomeworkAnswerInfo::find()->where(['relId' => $classHomeworkId, 'isCheck' => '1', 'isUploadAnswer'=>'1']);
        $cloneMarkedQueryCount = clone $markedQueryCount;
        //查询按时提交数
        $markedOnTimeNumber = $markedQueryCount->andWhere(['<', 'uploadTime', $deadlineTime])->count();
        //超时提交
        $markedOvertime = $cloneMarkedQueryCount->andWhere(['>', 'uploadTime', $deadlineTime])->count();
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10;
        //查询答题信息和全部提交
        $query = SeHomeworkAnswerInfo::find()->where(['relId' => $classHomeworkId, 'isUploadAnswer'=>'1'])->orderBy("uploadTime desc");
        $cloneQuery = clone $query;
        if (!app()->request->isAjax) {
            //未批改
            $answer = $query->andWhere(['isCheck' => 0])->offset($pages->getOffset())->limit($pages->getLimit())->all();
            //查询答案相关信息
            if (!empty($query)) {
                $pages->totalCount = $query->count();
            }
            $pages->params = ['type' => 1, 'classhworkid' => $classHomeworkId];

            //已批改
            $pagesCorrected = new Pagination();
            $pagesCorrected->pageSize = 10;
            $pagesCorrected->params = ['type' => 3, 'classhworkid' => $classHomeworkId];
            $answerCorrected = $cloneQuery->andWhere(['isCheck' => 1])->offset($pages->getOffset())->limit($pages->getLimit())->all();
            if (!empty($answerCorrected)) {
                $pagesCorrected->totalCount = $cloneQuery->count();
            }
        }
        if (app()->request->isAjax) {
            if ($type == 3) {
//                已批改
                $pagesCorrected = new Pagination();
                $pagesCorrected->validatePage = false;
                $pagesCorrected->pageSize = 10;
                $answeredQuery=$cloneQuery->andWhere(['isCheck'=>1]);
                if($checkTime==2){
                    $answeredQuery->andWhere(['<', 'uploadTime', $deadlineTime]);
                }
                if($checkTime==3){
                    $answeredQuery->andWhere(['>', 'uploadTime', $deadlineTime]);
                }
                $answerCorrected = $answeredQuery->offset($pagesCorrected->getOffset())->limit($pagesCorrected->getLimit())->all();
                if (!empty($answerCorrected)) {
                    $pagesCorrected->totalCount = intval($answeredQuery->count());
                }
                $pagesCorrected->params = ['type' => 3, 'classhworkid' => $classHomeworkId,'checkTime'=>$checkTime];
                return $this->renderPartial('_fixworkList_view', array('answerCorrected' => $answerCorrected, 'pagesCorrected' => $pagesCorrected,'homeWorkTeacher'=>$homeWorkTeacher));
            }
            if ($type == 1) {
                //未批改
                $unAnsweredQuery = $query->andWhere(['isCheck' => 0]);
                if($checkTime==2){
                    $unAnsweredQuery->andWhere(['<', 'uploadTime', $deadlineTime]);
                }
                if($checkTime==3){
                    $unAnsweredQuery->andWhere(['>', 'uploadTime', $deadlineTime]);
                }
                $answer=$unAnsweredQuery->offset($pages->getOffset())->limit($pages->getLimit())->all();
                if (!empty($answer)) {
                    $pages->totalCount = intval($unAnsweredQuery->count());
                }
                $pages->params = ['type' => 1, 'classhworkid' => $classHomeworkId];
                return $this->renderPartial('_workList_view', array('answer' => $answer, 'page' => $pages,'homeWorkTeacher'=>$homeWorkTeacher));
            }


        }
        return $this->render('workDetails',
            array(
                'result' => $result,
                'homeWorkTeacher' => $homeWorkTeacher,
                'answerNumber' => $answerNumber,
                'studentMember' => $studentMember,
                'isCorrections' => $isCorrections,
                'noCorrections' => $noCorrections,
                'homeworkId' => $result->homeworkId,
                'answer' => $answer,
                'page' => $pages,

                'answerCorrected' => $answerCorrected,
                'pagesCorrected' => $pagesCorrected,

                'onTimeNumber' => $onTimeNumber,
                'overtime' => $overtime,
                'markedOnTimeNumber' => $markedOnTimeNumber,
                'markedOvertime' => $markedOvertime,
                'type' => $type,
                'studentList' => $studentList,
                'classhworkid' => $classHomeworkId,
                'answerStuList' => $answerStuList
            )
        );
    }

    /**
     * 查询 未批改的按时提交和未按时提交
     * @return string
     */
    public function actionAnswerList()
    {
        $relId = app()->request->getParam('relId', '');
        $checkTime = app()->request->getParam('checkTime', '');
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10;
//查询rel关系表
        $result = SeHomeworkRel::find()->where(['id' => $relId])->one();
        //查询详情信息
        $homeWorkTeacher = $result->getHomeWorkTeacher()->one();
        $query = SeHomeworkAnswerInfo::find()->where(['relId' => $relId, 'isCheck' => 0]);
        //截止时间
        $deadlineTime = strtotime(date('Y-m-d 23:59:59', DateTimeHelper::timestampDiv1000($result->deadlineTime))) * 1000;
        if ($checkTime == 1 || $checkTime == '') {
            //查询答题信息和全部提交
            $answer = $query->orderBy("uploadTime desc")->offset($pages->getOffset())->limit($pages->getLimit())->all();
            //查询答案相关信息
            $answerQuestionAll = SeHomeworkAnswerQuestionAll::find()->where(['relId' => $relId])->all();
        } elseif ($checkTime == 2) {
            //按时提交
            $answer = $query->andWhere(['<', 'uploadTime', $deadlineTime])->orderBy("uploadTime desc")->offset($pages->getOffset())->limit($pages->getLimit())->all();
            $answerQuestionAll = SeHomeworkAnswerQuestionAll::find()->where(['relId' => $relId])->all();

        } elseif ($checkTime == 3) {
            //超时提交
            $answer = $query->andWhere(['>', 'uploadTime', $deadlineTime])->orderBy("uploadTime desc")->offset($pages->getOffset())->limit($pages->getLimit())->all();
            $answerQuestionAll = SeHomeworkAnswerQuestionAll::find()->where(['relId' => $relId])->all();
        }
        if (!empty($query)) {
            $pages->totalCount = $query->count();
        }
        return $this->renderPartial('_workList_view', array('answer' => $answer, 'answerQuestionAll' => $answerQuestionAll, 'page' => $pages, 'homeWorkTeacher' => $homeWorkTeacher));
    }

    /**
     * 查询 已批改的按时提交和未按时提交
     * @return string
     */
    public function actionMarkedAnswerList()
    {
        $relId = app()->request->getParam('relId', '');
        $checkTime = app()->request->getParam('checkTime', '');
        $pagesCorrected = new Pagination();
        $pagesCorrected->validatePage = false;
        $pagesCorrected->pageSize = 10;
//查询rel关系表
        $result = SeHomeworkRel::find()->where(['id' => $relId])->one();
        //查询详情信息
        $homeWorkTeacher = $result->getHomeWorkTeacher()->one();
        $query = SeHomeworkAnswerInfo::find()->where(['relId' => $relId, 'isCheck' => 1]);
        //截止时间
        $deadlineTime = strtotime(date('Y-m-d 23:59:59', DateTimeHelper::timestampDiv1000($result->deadlineTime))) * 1000;
        if ($checkTime == 1 || $checkTime == '') {
            //查询答题信息和全部提交
            $answerCorrected = $query->orderBy("uploadTime desc")->offset($pagesCorrected->getOffset())->limit($pagesCorrected->getLimit())->all();
            //查询答案相关信息
            $answerQuestionAll = SeHomeworkAnswerQuestionAll::find()->where(['relId' => $relId])->all();
        } elseif ($checkTime == 2) {
            //按时提交
            $answerCorrected = $query->andWhere(['<', 'uploadTime', $deadlineTime])->orderBy("uploadTime desc")->offset($pagesCorrected->getOffset())->limit($pagesCorrected->getLimit())->all();
            $answerQuestionAll = SeHomeworkAnswerQuestionAll::find()->where(['relId' => $relId])->all();

        } elseif ($checkTime == 3) {
            //超时提交
            $answerCorrected = $query->andWhere(['>', 'uploadTime', $deadlineTime])->orderBy("uploadTime desc")->offset($pagesCorrected->getOffset())->limit($pagesCorrected->getLimit())->all();
            $answerQuestionAll = SeHomeworkAnswerQuestionAll::find()->where(['relId' => $relId])->all();
        }
        if (!empty($query)) {
            $pagesCorrected->totalCount = $query->count();
        }
        return $this->renderPartial('_fixworkList_view', array('answerCorrected' => $answerCorrected, 'answerQuestionAll' => $answerQuestionAll, 'pagesCorrected' => $pagesCorrected, 'homeWorkTeacher' => $homeWorkTeacher));
    }

    /**
     *新组织作业
     */
    public function actionNewPreviewOrganizePaper()
    {
        $this->layout = "lay_user_select_question";
        //用于区别 题库类型   0平台题库  1我的题库  2我的收藏
        $n = app()->request->getParam('n', 0);
        if ($n == 0) {
            $userId = null;
        } elseif ($n == 1) {
            $userId = user()->id;
        }
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10;
        $homeworkId = app()->request->getParam('homeworkid', 0);
        $typeId = app()->request->getParam('type', '');
        $complexity = app()->request->getParam('complexity', '');
        $homeworkResult = SeHomeworkTeacher::find()->where(['id' => $homeworkId])->one();
        $kid = app()->request->getParam('kid', $homeworkResult->chapterId);
        $department = app()->request->getParam('department', loginUser()->getModel()->department);
        $subjectID = app()->request->getParam('subjectID', loginUser()->getModel()->subjectID);
        $versionID = $homeworkResult->version;
        $type = new Apollo_QuestionTypeService();
        $result = $type->queryQuesType($department, $subjectID);
        $chapterData = ChapterInfoModel::searchChapterPointToTree($subjectID, $department, $versionID, null, null, null, null);
        if ($n == 2) {
            $material = new pos_QueFavoriteFolder();
            //数据列表
            $topic_list = $material->queryQueFavoriteFolder(user()->id, '', '', '', '', $department, '', $subjectID, '', '', $typeId, '', '', '', $complexity, '', '', '', '', '', $kid, '', $pages->getPage() + 1, $pages->pageSize);
            if (!empty($topic_list)) {
                $pages->totalCount = intval($topic_list->countSize);
            }

        } else {
            $obj = new Apollo_QustionManageService();
            $topic_list = $obj->questionSearch($userId, '', $pages->getPage() + 1, $pages->pageSize, '', '', '', '', '', $department, '', $subjectID, $versionID, '', $typeId, '', '', '', $complexity, '', '', '', '', $kid, user()->id);
            if (!empty($topic_list)) {
                $pages->totalCount = intval($topic_list->countSize);
            }
        }
        if (app()->request->isAjax) {
            return $this->renderPartial('_organize_list', array('topic_list' => $topic_list->list, 'pages' => $pages));

        }
        $questionArray = SeHomeworkQuestion::find()->where(['homeworkId' => $homeworkId])->select('questionId')->asArray()->all();
        return $this->render('newPreviewOrganizePaper', array('department' => $department,
            'homeworkResult' => $homeworkResult,
            'subjectID' => $subjectID,
            'result' => $result,
            'topic_list' => $topic_list->list,
            'pages' => $pages,
            'chapterData' => $chapterData,
            'homeworkId' => $homeworkId,
            'questionArray' => $questionArray));
    }

//显示题目详情
    public function actionViewPagerById()
    {
        $qid = app()->request->getParam('qid', '');
        $questionInfoService = new Apollo_QuestionInfoService();
        $question = $questionInfoService->questionSearchById($qid);
        return $this->renderPartial('_pageProblemView', array('list' => $question->list));
    }

    /**
     *修改作业
     */
    public function actionUpdateWork()
    {
        $model = new PaperForm();
        $personMessage = loginUser()->getModel();
//        获取教材版本
        $editionID = $personMessage->textbookVersion;
        $edition = EditionModel::model()->getEditionName($editionID);
//        获取科目
        foreach (loginUser()->getClassInfo() as $v) {
            if ($v->classID == app()->request->getParam("classID")) {
                $subjectID = $v->subjectNumber;
            }
        }
        $subject = SubjectModel::model()->getSubjectName($subjectID);
//        获取地区信息
        $countryID = $personMessage->country;
        $country = AreaHelper::getAreaName($countryID);
        $cityID = $personMessage->city;
        $city = AreaHelper::getAreaName($cityID);
        $provienceID = $personMessage->provience;
        $provience = AreaHelper::getAreaName($provienceID);
        $manage = new pos_HomeWorkManageService();
        $homeworkID = app()->request->getParam("homeworkID");
        $homeworkDetails = $manage->queryHomework("", "", "", "", "", $homeworkID);
        $personArray = array(
            "edition" => $edition,
            "subject" => $subject,
            "country" => $country,
            "city" => $city,
            "provience" => $provience
        );
        //        知识树的获取
        $department = $personMessage->department;
        $knowledge = new KnowledgePointModel();
        $knowledgePoint = $knowledge->searchKnowledgePointToTree($subjectID, $department);
        if ($_POST) {
            $model->attributes = $_POST["PaperForm"];
            $classID = app()->request->getParam("classID");
            $paperRoute = implode(",", $_POST["PaperForm"]["PaperRoute"]);
            $result = $manage->updateUploadHomework($homeworkID, $classID, $model->paperName, $provienceID, $cityID, $countryID, "", $subjectID, $editionID, $model->knowledgePoint, $model->describe, user()->id, $model->deadlineTime, $paperRoute);
            if ($result->resCode == pos_HomeWorkManageService::successCode) {
                return $this->redirect(url("teacher/managetask/"));
            }
        }
        return $this->render("updateWork", array("personArray" => $personArray, "model" => $model, "homeworkDetails" => $homeworkDetails->list[0], "knowledgePoint" => json_encode($knowledgePoint)));
    }

    /**
     * 上传作业（新加）
     */
    public function actionNewUpdateWork()
    {
        $homeworkId = app()->request->getParam('homeworkid');
        $jsonResult = new JsonMessage();
        $workContent = SeHomeworkTeacher::find()->where(['id' => $homeworkId])->one();
        $homeworkImages = SeHomeworkImage::find()->where(['homeworkId' => $homeworkId])->select('url')->asArray()->all();
        if (isset($_POST['picurls'])) {
            SeHomeworkImage::deleteAll(['homeworkId' => $homeworkId]);
            SeHomeworkTeacher::updateAll(['getType' => 0], ['id' => $homeworkId]);
            $picurls = app()->request->getParam('picurls');
            $isSaveed = true;
            foreach ($picurls as $v) {
                $model = new SeHomeworkImage();
                $model->homeworkId = $homeworkId;
                $model->url = $v;
                if (!$model->save()) {
                    $isSaveed = false;

                    break;
                }

            }
//            $verify = $work->uploadHomeworkImages($homeworkId, $picurls);

            if ($isSaveed) {
                $jfHelper = new JfManageService;
                $jfHelper->myAccount("pos-upl-picWork", user()->id);
                $jsonResult->success = true;
                $jsonResult->message = '上传成功';
            } else {
                $jsonResult->message = '上传失败';
            }
            return $this->renderJSON($jsonResult);
        }

        return $this->render('newupdatework', array('workContent' => $workContent, 'homeworkImages' => $homeworkImages));
    }


    /**
     * 上传作业详细页(新加)
     */
    public function actionNewUpdateWorkDetail()
    {

        $homeworkId = app()->request->getParam('homeworkid');
        $homeworkDetails = SeHomeworkTeacher::find()->where(['id' => $homeworkId])->one();
        //         是否布置给学生
        $isAssignStu = SeHomeworkRel::find()->where(['homeworkId' => $homeworkId])->exists();
        return $this->render('newupdateworkdetail', array('homeworkDetails' => $homeworkDetails, 'homeworkId' => $homeworkId, 'isAssignStu' => $isAssignStu));
    }

    /**
     *组织作业预览页（新加）
     */
    public function actionOrganizeWorkDetailsNew()
    {

        $homeworkID = app()->request->getParam("homeworkid", '');
        $homeworkData = SeHomeworkTeacher::find()->where(['id' => $homeworkID])->one();
        if (empty($homeworkData)) {
            return $this->notFound();
        }
//        根据homeworkID查询questionid
        $questionList = $homeworkData->getHomeworkQuestion()->select('questionId')->asArray()->all();
        if (empty($questionList)) {
            return $this->notFound();
        }
//        查询题目的具体内容
//        $homeworkResult = ShTestquestion::find()->where(['id' => ArrayHelper::getColumn($questionList, 'questionId')])->all();
        $homeworkResult=[];
        foreach($questionList as $v){
            $oneHomework=Es_testQuestion::find()->where(['id'=>$v['questionId']])->one();
            array_push($homeworkResult,$oneHomework);
        }
//         是否布置给学生
        $isAssignStu = SeHomeworkRel::find()->where(['homeworkId' => $homeworkID])->exists();
        return $this->render('organizeWorkdetailsnew', array("homeworkResult" => $homeworkResult, 'homeworkData' => $homeworkData, 'isAssignStu' => $isAssignStu));
    }

    /**
     *上传作业详细页
     */
    public function actionUploadWorkDetails()
    {
        $homeworkID = app()->request->getParam("homeworkID");
        $homework = new pos_HomeWorkManageService();
        $result = $homework->queryHomeworkById($homeworkID);
        $classid = app()->request->getParam('classid', '');
        if ($result == null) {
            return $this->notFound();
        }
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 2;
        $answerResult = $homework->queryHomeworkAllAnswerList($homeworkID, 0, $classid, $pages->currentPage + 1, $pages->pageSize);
        $pages->totalCount = $answerResult->countSize;
        return $this->render("uploadWorkDetails", array("result" => $result, "classID" => app()->request->getParam("classID"), "answerResult" => $answerResult, "pages" => $pages));
    }

    /**
     *上传类型的查看作业批改  2015-4-21 (修改)
     */
    public function actionViewCorrect()
    {
        $homeworkAnswerId = app()->request->getParam('homeworkanswerid', '');

        //查询答案相关信息
        $answerInfo = SeHomeworkAnswerInfo::find()->where(['homeworkAnswerID' => $homeworkAnswerId])->one();
        $relId = $answerInfo->relId;
        //查询纸质的图片

        if ($answerInfo == null) {
            return $this->notFound();
        }
        if ($answerInfo->getType == 1) {
            return $this->viewOrganizeCorrect();
        }
        $answerInfoDetailsImg = $answerInfo->getHomeworkAnswerDetailImage()->all();

        if (!empty($answerInfo)) {
            $query = SeHomeworkRel::find()->where(['id' => $relId])->one();
            $deadlineTime = $query->deadlineTime;
            $result = $query->getHomeWorkTeacher()->one();
        }
        return $this->render("viewCorrect", array('answerInfo' => $answerInfo, 'answerInfoDetailsImg' => $answerInfoDetailsImg, 'result' => $result, 'deadlineTime' => $deadlineTime, 'relId' => $relId, 'homeworkanswerid' => $homeworkAnswerId));
    }


    /**
     *AJAX学生互相判卷
     */
    public function actionStudentCrossCheck()
    {
        $homeworkID = app()->request->getParam("homeworkID");
        $homeworkServer = new pos_HomeWorkManageService();
        $homeworkResult = $homeworkServer->studentCrossCheckTest(user()->id, $homeworkID);
        $jsonResult = new JsonMessage();
        if ($homeworkResult->resCode == BaseService::successCode) {
            $jsonResult->code = 1;
        } else {
            $jsonResult->code = 0;
        }
        $jsonResult->message = $homeworkResult->resMsg;
        return $this->renderJSON($jsonResult);
    }

    /**
     *上传的作业的判卷
     */
    public function actionCorrectPaper()
    {

        // todo
        $homeworkAnswerID = app()->request->getParam("homeworkAnswerID");
        $homework = new pos_HomeWorkManageService();
        if (false) {
            //todo 确定接口 类型
            $this->organizeCorrect();
            return;
        }
        $answerResult = $homework->queryHomeworkAnswerImages("", "", $homeworkAnswerID);
        return $this->render("correctPaper", array("answerResult" => $answerResult));
    }

    /**
     *新教师批改作业
     */
    public function actionNewCorrectPaper()
    {
        // todo
        $homeworkAnswerId = app()->request->getParam('homeworkanswerid', '');

        //查询答案相关信息
        $answerInfo = SeHomeworkAnswerInfo::find()->where(['homeworkAnswerID' => $homeworkAnswerId])->one();
        $query = SeHomeworkRel::find()->where(['id' => $answerInfo->relId])->one();
        //查询题的详情
        $answerTeacher = $query->getHomeWorkTeacher()->one();
        //查询纸质的图片
        $answerInfoDetailsImg = $answerInfo->getHomeworkAnswerDetailImage()->all();

        if ($answerInfo == null) {
            return $this->notFound();
        }

        if (!empty($answerInfo)) {
            if ($answerInfo->getType == 1) {
                return $this->organizeCorrect();
            }
        }

        return $this->render("newCorrectPaper", array('answerInfo' => $answerInfo, 'answerTeacher' => $answerTeacher, 'answerInfoDetailsImg' => $answerInfoDetailsImg));
    }

    /**
     *组织的作业的 (2015-4-21修改)
     */
    public function organizeCorrect()
    {
        $homeworkAnswerID = app()->request->getParam("homeworkanswerid");

        $homeworkServer = new pos_HomeWorkManageService();
        $answerResult = $homeworkServer->queryHomeworkAllAnswerPicList($homeworkAnswerID);
        $answerRel = SeHomeworkRel::find()->where(['id' => $answerResult->relId])->one();
        $homeworkName = $answerRel->getHomeWorkTeacher()->select(['name'])->one();
        return $this->render("organizeCorrect", array("answerResult" => $answerResult, 'homeworkName' => $homeworkName));
    }

    /**
     *AJAX组织的作业保存本页批改
     */
    public function actionHoldOrganizeCorrect()
    {
        $jsonResult = new JsonMessage();
        $homeworkAnswerID = app()->request->getParam("homeworkanswerid");
        $picID = app()->request->getParam("picID");
        $answerID = app()->request->getParam("answerID");
        $checkJson = app()->request->getParam("checkInfoJson");
        $homeworkServer = new pos_HomeWorkManageService();
        $homeworkResult = $homeworkServer->commitCheckInfoForOrgPaper(user()->id, $homeworkAnswerID, $picID, $answerID, $checkJson);
        if ($homeworkResult->resCode == $homeworkServer::successCode) {
            $jsonResult->success = true;
        } else {
            $jsonResult->success = false;
        }
        $jsonResult->message = $homeworkResult->resMsg;
        return $this->renderJSON($jsonResult);
    }

    /**
     *AJAX上传的作业保存本页批改
     */
    public function actionHoldCorrect()
    {
        $checkInfoJson = app()->request->getParam("checkInfoJson");
        $tID = app()->request->getParam("tID");
        $homeworkAnswerID = app()->request->getParam("homeworkAnswerID");
        $homework = new pos_HomeWorkManageService();
        $result = $homework->commitCheckInfo(user()->id, $homeworkAnswerID, $tID, $checkInfoJson);
        $jsonResult = new JsonMessage();
        if ($result->resCode == pos_HomeWorkManageService::successCode) {
            $jsonResult->success = true;
        } else {
            $jsonResult->success = false;
        }
        $jsonResult->message = $result->resMsg;
        return $this->renderJSON($jsonResult);
    }

    /**
     * 都是客观题的批改完成
     */
    public function actionFinishCorrect()
    {
        $homeworkAnswerID = app()->request->getParam("homeworkanswerid");
//        更新SeHomeworkAnswerInfo表的checkTime
        $answerQuery = SeHomeworkAnswerInfo::find()->where(['homeworkAnswerID' => $homeworkAnswerID])->one();
        $answerQuery->isCheck = '1';
        $answerQuery->checkTime=DateTimeHelper::timestampX1000();
        $answerQuery->save(false);
        $homework = new pos_HomeWorkManageService();
        $result = $homework->updateCheckState($homeworkAnswerID, user()->id);
        $jsonResult = new JsonMessage();
        if ($result->resCode == pos_HomeWorkManageService::successCode) {
            $jsonResult->success = true;
        } else {
            $jsonResult->success = false;
        }
        $jsonResult->message = $result->resMsg;
        return $this->renderJSON($jsonResult);
    }

    /**
     *组织作业详细页
     */
    public function actionOrganizeWorkDetails()
    {
        $homeworkID = app()->request->getParam('', '');//'10017';
        $homework = new pos_HomeWorkManageService();
        $result = $homework->queryHomework("", "", "", "", "", $homeworkID);
        $classid = app()->request->getParam('classid', '');

        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 2;
        $answerResult = $homework->queryHomeworkAllAnswerList($homeworkID, 0, $classid, $pages->getPage() + 1, $pages->pageSize);
        $pages->totalCount = $answerResult->countSize;
        return $this->render("organizeWorkDetails", array("result" => $result->list[0], "pages" => $pages, "answerResult" => $answerResult));
    }


    /**
     *  查看组织类型试卷的批改
     */
    public function viewOrganizeCorrect()
    {
        $homeworkAnswerID = app()->request->getParam("homeworkanswerid");
        $homeworkServer = new pos_HomeWorkManageService();
        $homeworkResult = $homeworkServer->queryHomeworkAllAnswerPicList($homeworkAnswerID);
        $relId = $homeworkResult->relId;

        if (!empty($homeworkResult)) {
            $query = SeHomeworkRel::find()->where(['id' => $relId])->one();
            $deadlineTime = $query->deadlineTime;
            $result = $query->getHomeWorkTeacher()->one();
        }

        return $this->render("viewOrganizeCorrect", array("homeworkResult" => $homeworkResult, "result" => $result, 'relId' => $relId, 'deadlineTime' => $deadlineTime, "homeworkanswerid" => $homeworkAnswerID));
    }

    /**
     *上传的作业的试卷预览
     */
    public function actionPreviewPaper()
    {
        //todo
        $homeworkID = app()->request->getParam("homeworkID");
        $homework = new pos_HomeWorkManageService();
        if (true) {
            //todo 确定作业类型 需接口给

            $this->previewOrganizePaper();
            return;
        }

        $result = $homework->queryHomework("", "", "", "", "", $homeworkID);
        return $this->render("previewPaper", array("result" => $result->list[0]));
    }

    /**
     *组织的作业的试卷预览
     */
    public function previewOrganizePaper()
    {
        $homeworkAnswerID = app()->request->getParam("homeworkAnswerID");
        $homeworkServer = new pos_HomeWorkManageService();
        $homeworkResult = $homeworkServer->queryHomeworkAllAnswerPicList($homeworkAnswerID);
        return $this->render("previewOrganizePaper", array("homeworkResult" => $homeworkResult));
    }


    public function actionGetQuestionById()
    {
        $homeworkId = app()->request->getParam('homeworkid', 0);
        $id = app()->request->getParam('id', 0);
        $homework = new pos_HomeWorkManageService();
        $result = $homework->updateQuestionAllNew($homeworkId, $id);
        $jsonResult = new JsonMessage();
        if ($result->resCode == BaseService::successCode) {
            $jsonResult->success = true;
        } else {
            $jsonResult->success = false;
        }
        return $this->renderJSON($jsonResult);


    }


    public function actionHeader()
    {
        $personMessage = loginUser()->getModel();
//        获取教材版本
        $editionID = $personMessage->textbookVersion;
        $edition = EditionModel::model()->getEditionName($editionID);
//        获取科目
        foreach (loginUser()->getClassInfo() as $v) {
            if ($v->classID == app()->request->getParam("classID")) {
                $subjectID = $v->subjectNumber;
            }
        }
        $subject = SubjectModel::model()->getSubjectName($subjectID);
//        获取地区信息
        $countryID = $personMessage->country;
        $country = AreaHelper::getAreaName($countryID);
        $cityID = $personMessage->city;
        $city = AreaHelper::getAreaName($cityID);
        $provienceID = $personMessage->provience;
        $provience = AreaHelper::getAreaName($provienceID);
        $personArray = array(
            "edition" => $edition,
            "subject" => $subject,
            "country" => $country,
            "city" => $city,
            "provience" => $provience
        );
//        知识树的获取
        $department = $personMessage->department;
        $knowledge = new KnowledgePointModel();
        $knowledgePoint = $knowledge->searchKnowledgePointToTree($subjectID, $department);
        $classID = app()->request->getParam("classID");
        $schPaperManageService = new  pos_HomeWorkManageService();
        $makePaperForm = new MakePaperForm();
        $re = $schPaperManageService->createHomeworkHeader(user()->id, $classID);
        $makePaperForm->provience = $re->provience;
        $makePaperForm->city = $re->city;
        $makePaperForm->subject = $re->subjectId;
        $makePaperForm->county = $re->country;
        $makePaperForm->gradeId = $re->gradeId;
        $makePaperForm->version = $re->version;
        $makePaperForm->knowledgePointId = $re->knowledgeId;
        $makePaperForm->author = $re->author;
        $makePaperForm->paperType = $re->homeworkType;
        $makePaperForm->paperDescribe = $re->homeworkDescribe;
        $makePaperForm->paperName = $re->name;
        if (app()->request->isPost) {
            if (isset($_POST['MakePaperForm'])) {
                $makePaperForm->attributes = $_POST['MakePaperForm'];
                if ($makePaperForm->validate()) {

                    $result = $schPaperManageService->updateHomeworkHead($re->homeworkId, $classID,
                        $makePaperForm->paperName, $provienceID,
                        $cityID,
                        $countryID,
                        $makePaperForm->gradeId,
                        $subjectID,
                        $editionID,
                        $makePaperForm->knowledgePointId,
                        $makePaperForm->author,
                        $makePaperForm->paperDescribe,
                        user()->id,
                        $makePaperForm->paperType,
                        $makePaperForm->deadLineTime
                    );
                    if ($result->resCode == $schPaperManageService::successCode) {
                        return $this->redirect(['structure', 'homeworkId' => $re->homeworkId]);
                    }
                } else {


                }
            }

        }
        return $this->render('header', ['model' => $makePaperForm, "personArray" => $personArray, "knowledgePoint" => json_encode($knowledgePoint)]);
    }

    /**
     *保存选中题目
     */
    public function actionStructure()
    {
        $homeworkId = app()->request->getParam("homeworkId");
        $schPaperManageService = new  pos_HomeWorkManageService();
        $re = $schPaperManageService->queryTempHomework($homeworkId);

        if ($re == null) {
            return $this->notFound();
        }

        if (app()->request->isAjax) {
            $jsonResult = new JsonMessage();
            if (isset($_POST['pageMain'])) {
                $pageMain = $_POST['pageMain'];
                $result = $schPaperManageService->updateHomeworkContent($homeworkId,
                    $this->toModelDataJson($pageMain));

                if ($result->resCode == $schPaperManageService::successCode) {
                    $jsonResult->success = true;
                    $jsonResult->data = $homeworkId;
                } else {
                    $jsonResult->message = $result->resMsg;
                }
            }
            return $this->renderJSON($jsonResult);
        }
        return $this->render('structure', ['treejson' => $re->pageMain]);
    }

    /**
     * 选择类型
     */
    public function actionSubject()
    {
        $homeworkId = app()->request->getParam("homeworkId");
        $schPaperManageService = new  pos_HomeWorkManageService();
        $re = $schPaperManageService->queryTempHomework($homeworkId);
        if ($re == null) {
            return $this->notFound();
        }


        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10;


        $questionInfoService = new Apollo_QuestionInfoService();
        $qu = new Apollo_QuestSearchModel();

//        $qu->subjectid = $re->subjectId;
//        $qu->kid = $re->knowledgeId;
//        $qu->gradeid=$re->gradeId;
        $qu->pageSize = $pages->pageSize;
        $qu->currPage = $pages->getPage() + 1;
        //请求接口
        $result = $questionInfoService->questionSearch($qu);


        $pages->totalCount = $result->countSize;
        $list = $result->list;
        if (app()->request->isAjax) {
            $key = app()->request->getParam("key");
            if ($key != null) {
                $qu->name = $key;
                $pages->params['key'] = $key;
                $result = $questionInfoService->questionSearch($qu);


                $pages->totalCount = $result->countSize;
                $list = $result->list;
            }

//            $pages->params['homeworkId'] = $homeworkId;
            return $this->renderPartial("_pageSubjectView", array('list' => $list, 'pages' => $pages));
            return;
        }

        $queryType = $schPaperManageService->queryQuestions($homeworkId, user()->id);
        return $this->render('subject', array('queryType' => $queryType, 'list' => $list, 'pages' => $pages));
    }

    /**
     * 保存选中题目
     */
    public function  actionSaveSubject($homeworkId)
    {
        $jsonMessage = new JsonMessage();
        if (app()->request->isAjax && $_POST['items']) {
            $questions = [];
            foreach ($_POST['items'] as $key => $value) {
                $stdclass = new stdClass();
                $stdclass->typeId = $key;
                $stdclass->questions = [];
                $arrs = StringHelper::splitNoEMPTY($value);
                foreach ($arrs as $v) {
                    array_push($stdclass->questions, ['id' => $v]);
                }
                $questions[] = $stdclass;
            }
            $schPaperManageService = new  pos_HomeWorkManageService();
            $result = $schPaperManageService->updateQuestionAll($homeworkId, user()->id, json_encode($questions));
            if ($result->resCode == pos_PaperManageService::successCode) {
                $jsonMessage->success = true;
            }
        }


        return $this->renderJSON($jsonMessage);
    }

    public function     toZtreeData($jsonModel)
    {
        $jsondata = $jsonModel;
        $pageMain = new stdClass();

        $line = ['id' => 'line', 'name' => '装订线', 'dataid' => 0, 'pId' => 'testPaperHead', 'text' => '', 'checked' => true];
        if (isset($jsondata->line)) {
            $line['checked'] = $jsondata->line->ischecked == 'true';
        }
        $pageMain->paperHead[] = $line;


        $secret_sign = ['id' => 'secret_sign', 'name' => '绝密★启用前', 'pId' => 'testPaperHead', 'dataid' => 0, 'text' => '绝密★启用前', 'checked' => true];
        if (isset($jsondata->secret_sign)) {
            $secret_sign['checked'] = (bool)($jsondata->secret_sign->ischecked) == 'true';
        }
        $pageMain->paperHead[] = $secret_sign;


        $main_title = ['id' => 'main_title', 'name' => '主标题', 'pId' => 'testPaperHead', 'text' => '2013-2014学年度xx学校xx月考卷', 'dataid' => 0, 'checked' => true];
        if (isset($jsondata->main_title)) {
            $main_title['checked'] = $jsondata->main_title->ischecked == 'true';
            $main_title['text'] = $jsondata->main_title->title ? $jsondata->main_title->title : '2013-2014学年度xx学校xx月考卷';
        }
        $pageMain->paperHead[] = $main_title;

        $sub_title = ['id' => 'sub_title', 'name' => '副标题', 'pId' => 'testPaperHead', 'text' => '内部模拟考试', 'dataid' => 0, 'checked' => true];
        if (isset($jsondata->sub_title)) {
            $sub_title['checked'] = $jsondata->sub_title->ischecked == 'true';
        }

        $pageMain->paperHead[] = $sub_title;


        $info = ['id' => 'info', 'name' => '范围/时间', 'pId' => 'testPaperHead', 'text' => '内部模拟考试', 'dataid' => 0, 'checked' => true];
        if (isset($jsondata->info)) {
            $info['checked'] = $jsondata->info->ischecked == 'true';
            $info['text'] = $jsondata->info->title;
        }
        $pageMain->paperHead[] = $info;

        $student_input = ['id' => 'student_input', 'name' => '学生输入', 'pId' => 'testPaperHead', 'dataid' => 0, 'text' => '考试范围：xxx；考试时间：100分钟；命题人：xxx', 'checked' => true];

        if (isset($jsondata->student_input)) {
            $student_input['checked'] = $jsondata->student_input->ischecked == 'true';
            $student_input['text'] = $jsondata->student_input->title;
        }
        $pageMain->paperHead[] = $student_input;

        $pay_attention = ['id' => 'pay_attention', 'name' => '注意事项', 'pId' => 'testPaperHead', 'dataid' => 0, 'text' => '考试范围：xxx；考试时间：100分钟；命题人：xxx', 'checked' => true];


        if (isset($jsondata->pay_attention)) {
            $pay_attention['checked'] = $jsondata->pay_attention->ischecked == 'true';
            $pay_attention['text'] = $jsondata->pay_attention->title;
        }
        $pageMain->paperHead[] = $pay_attention;


        $win_paper_typeone = ['id' => 'win_paper_typeone', 'name' => '第一卷(选择题)', 'pId' => 'testPaperBody', 'dataid' => 0, 'text' => '注释内容', 'open' => true, 'checked' => true];

        if (isset($jsondata->win_paper_typeone)) {
            $win_paper_typeone['checked'] = $jsondata->win_paper_typeone->ischecked == 'true';
            $win_paper_typeone['text'] = $jsondata->win_paper_typeone->title;
            $win_paper_typeone['dataid'] = $jsondata->win_paper_typeone->id;
        }
        $pageMain->paperBody[] = $win_paper_typeone;


        $win_paper_typetwo = ['id' => 'win_paper_typetwo', 'name' => '第二卷(非选择题)', 'pId' => 'testPaperBody', 'dataid' => 0, 'text' => '第二卷(非选择题)', 'open' => true, 'checked' => true];

        if (isset($jsondata->win_paper_typetwo)) {
            $win_paper_typetwo['checked'] = $jsondata->win_paper_typetwo->ischecked == 'true';
            $win_paper_typetwo['text'] = $jsondata->win_paper_typetwo->title;
            $win_paper_typetwo['dataid'] = $jsondata->win_paper_typetwo->id;
        }

        $pageMain->paperBody[] = $win_paper_typetwo;

        if (isset($jsondata->win_paper_typeone->questionTypes)) {
            foreach ($jsondata->win_paper_typeone->questionTypes as $item) {
                $l = ['pId' => 'win_paper_typeone', 'name' => '', 'dataid' => 0, 'text' => '', 'checked' => true];
                $l['id'] = $l['dataid'] = $item->id;
                $l['name'] = $item->title;
                $l['text'] = $item->content;
                $l['checked'] = $item->ischecked == 'true';

                $pageMain->paperBody[] = $l;
            }
        }

        if (isset($jsondata->win_paper_typetwo->questionTypes)) {
            foreach ($jsondata->win_paper_typetwo->questionTypes as $item) {
                $l = ['id' => '', 'pId' => 'win_paper_typetwo', 'name' => '', 'dataid' => 0, 'text' => '', 'checked' => true];
                $l['id'] = $l['dataid'] = $item->id;
                $l['name'] = $item->title;
                $l['text'] = $item->content;
                $l['checked'] = $item->ischecked == 'true';
                $pageMain->paperBody[] = $l;
            }
        }

        return $pageMain;
    }

    public function   toModelDataJson($json)
    {

        //  $json = '{"paperHead":[{"id":"line","name":"装订线","dataid":0,"text":"","checked":true},{"id":"secret_sign","name":"绝密★启用前","dataid":0,"text":"绝密★启用前","checked":true},{"id":"main_title","name":"主标题","text":"2013-2014学年度xx学校xx月考卷","dataid":0,"checked":true},{"id":"sub_title","name":"副标题","text":"内部模拟考试","dataid":0,"checked":true},{"id":"info","name":"范围/时间","text":"","dataid":0,"checked":true},{"id":"student_input","name":"学生输入","dataid":0,"text":"","checked":true},{"id":"pay_attention","name":"注意事项","dataid":0,"text":"","checked":true}],"paperBody":[{"id":"win_paper_typeone","name":"第一卷(选择题)","dataid":10021,"text":"第I卷（选择题）","open":true,"checked":true},{"id":"win_paper_typetwo","name":"第二卷(非选择题)","dataid":10022,"text":"第II卷（非选择题）","open":true,"checked":true},{"pId":"win_paper_typeone","name":"单选题","dataid":"1","text":"","checked":true,"id":"1"},{"id":"2","pId":"win_paper_typetwo","name":"填空题","dataid":"2","text":"","checked":true},{"id":"3","pId":"win_paper_typetwo","name":"计算题","dataid":"3","text":"","checked":true},{"id":"4","pId":"win_paper_typetwo","name":"解答题","dataid":"4","text":"","checked":true},{"id":"5","pId":"win_paper_typetwo","name":"判断题","dataid":"5","text":"","checked":true}]}';
        $jsonArr = json_decode($json, true);


        $pageMain = new stdClass();
        $pageMain->student_input = ['title' => '', 'content' => '', 'ischecked' => 0];
        $pageMain->line = ['title' => '', 'id' => 0, 'ischecked' => 0, 'content' => ''];
        $pageMain->main_title = ['title' => '', 'id' => 0, 'ischecked' => 0, 'content' => ''];
        $pageMain->sub_title = ['title' => '', 'id' => 0, 'ischecked' => 0, 'content' => ''];
        $pageMain->pay_attention = ['title' => '', 'id' => 0, 'ischecked' => 0, 'content' => ''];
        $pageMain->info = ['title' => '', 'id' => 0, 'ischecked' => 0, 'content' => ''];
        $pageMain->secret_sign = ['title' => '', 'id' => 0, 'ischecked' => 0, 'content' => ''];
        $pageMain->performance = ['title' => '', 'id' => 0, 'ischecked' => 0, 'content' => ''];


        if (isset($jsonArr['paperHead'])) {

            $paperHead = $jsonArr['paperHead'];
            $line = from($paperHead)->firstOrDefault(null, function ($v) {
                return $v["id"] == 'line';
            });
            if ($line != null) {
                $pageMain->line['title'] = $line['name'];
                $pageMain->line['ischecked'] = $line['checked'];
                $pageMain->line['content'] = $line['text'];

            }

            $student_input = from($paperHead)->firstOrDefault(null, function ($v) {
                return $v["id"] == 'student_input';
            });
            if ($student_input != null) {

                $pageMain->student_input['title'] = $student_input['name'];
                $pageMain->student_input['ischecked'] = $student_input['checked'];
                $pageMain->student_input['content'] = $student_input['text'];


            }

            $main_title = from($paperHead)->firstOrDefault(null, function ($v) {
                return $v["id"] == 'main_title';
            });

            if ($main_title != null) {
                $pageMain->main_title['title'] = $main_title['name'];
                $pageMain->main_title['ischecked'] = $main_title['checked'];
                $pageMain->main_title['content'] = $main_title['text'];

            }

            $sub_title = from($paperHead)->firstOrDefault(null, function ($v) {
                return $v["id"] == 'sub_title';
            });

            if ($sub_title != null) {
                $pageMain->sub_title['title'] = $sub_title['name'];
                $pageMain->sub_title['ischecked'] = $sub_title['checked'];
                $pageMain->sub_title['content'] = $sub_title['text'];
            }

            $pay_attention = from($paperHead)->firstOrDefault(null, function ($v) {
                return $v["id"] == 'pay_attention';
            });

            if ($pay_attention != null) {
                $pageMain->pay_attention['title'] = $pay_attention['name'];
                $pageMain->pay_attention['ischecked'] = $pay_attention['checked'];
                $pageMain->pay_attention['content'] = $pay_attention['text'];
            }

            $info = from($paperHead)->firstOrDefault(null, function ($v) {
                return $v["id"] == 'info';
            });

            if ($info != null) {
                $pageMain->info['title'] = $info['name'];
                $pageMain->info['ischecked'] = $info['checked'];
                $pageMain->info['content'] = $info['text'];
            }

            $secret_sign = from($paperHead)->firstOrDefault(null, function ($v) {
                return $v["id"] == 'secret_sign';
            });

            if ($info != null) {
                $pageMain->secret_sign['title'] = $secret_sign['name'];
                $pageMain->secret_sign['ischecked'] = $secret_sign['checked'];
                $pageMain->secret_sign['content'] = $secret_sign['text'];
            }


        }

        $pageMain->win_paper_typeone = ['title' => '', 'id' => 0, 'ischecked' => 0, 'content' => '', 'questionTypes' => []];
        $pageMain->win_paper_typetwo = ['title' => '', 'id' => 0, 'ischecked' => 0, 'content' => '', 'questionTypes' => []];

        if (isset($jsonArr['paperBody'])) {
            $paperBody = $jsonArr['paperBody'];
            $win_paper_typeone = from($paperBody)->firstOrDefault(null, function ($v) {
                return $v["id"] == 'win_paper_typeone';
            });

            if ($win_paper_typeone != null) {
                $pageMain->win_paper_typeone['title'] = $win_paper_typeone['name'];
                $pageMain->win_paper_typeone['ischecked'] = $win_paper_typeone['checked'];
                $pageMain->win_paper_typeone['content'] = $win_paper_typeone['text'];
                $pageMain->win_paper_typeone['id'] = $win_paper_typeone['dataid'];


                $win_paper_typeoneList = from($paperBody)->where(
                    function ($v) {
                        return isset($v["pId"]) && $v["pId"] == 'win_paper_typeone';
                    }
                )->toArray();

                foreach ($win_paper_typeoneList as $item) {
                    $o = ['title' => '', 'id' => 0, 'ischecked' => 0, 'content' => ''];
                    $o['title'] = $item['name'];
                    $o['ischecked'] = $item['checked'];
                    $o['content'] = $item['text'];
                    $o['id'] = $item['dataid'];
                    $pageMain->win_paper_typeone["questionTypes"][] = $o;
                }


            }

            $win_paper_typetwo = from($paperBody)->firstOrDefault(null, function ($v) {
                return $v["id"] == 'win_paper_typetwo';
            });

            if ($win_paper_typetwo != null) {
                $pageMain->win_paper_typetwo['title'] = $win_paper_typetwo['name'];
                $pageMain->win_paper_typetwo['ischecked'] = $win_paper_typetwo['checked'];
                $pageMain->win_paper_typetwo['content'] = $win_paper_typetwo['text'];
                $pageMain->win_paper_typetwo['id'] = $win_paper_typetwo['dataid'];
            }

            $win_paper_typetwoList = from($paperBody)->where(
                function ($v) {
                    return isset($v["pId"]) && $v["pId"] == 'win_paper_typetwo';
                }
            )->toArray();

            foreach ($win_paper_typetwoList as $item) {
                $o = ['title' => '', 'id' => 0, 'ischecked' => 0, 'content' => ''];
                $o['title'] = $item['name'];
                $o['ischecked'] = $item['checked'];
                $o['content'] = $item['text'];
                $o['id'] = $item['dataid'];
                $pageMain->win_paper_typetwo["questionTypes"][] = $o;
            }


        }

        return $pageMain;

    }

    /**
     * 作业库
     * @return string
     */
    public function actionLibraryList()
    {
        $this->layout = 'lay_prepare';
        $userInfo = loginUser()->getModel();
        $department = app()->request->getQueryParam('department', $userInfo->department);
        $subject = app()->request->getQueryParam('subjectid', $userInfo->subjectID);
        $edition = app()->request->getQueryParam('edition', $userInfo->textbookVersion);
        $tome = app()->request->getQueryParam('tome');
        $text = app()->request->getQueryParam('text', null);
        $difficulty = app()->request->getQueryParam('difficulty', null);
        $tomeServer = new Apollo_chapterInfoManage();
        $tomeResult = $tomeServer->chapterBaseNodeSearch($subject, $department, $edition, null, null);
        $chapterData = ChapterInfoModel::searchChapterPointToTree($subject, $department, $edition, null, null, null, $tome);
        $treeData = TreeHelper::streefun($chapterData, ['onclick' => "return getSearchList(this);"], "tree pointTree");
        $pages = new Pagination();
        $pages->pageSize = 10;
        $homeworkQuery = SeHomeworkPlatform::find()->active()->level()->andWhere(['department' => $department, 'version' => $edition, 'subjectId' => $subject]);
        if ($difficulty != null) {
            $homeworkQuery->andWhere(['difficulty' => $difficulty]);
        }
        if ($text != null) {
            $homeworkQuery->andWhere(['like', 'name', $text]);
        }
        $pages->totalCount = $homeworkQuery->count();
        $homeworkResult = $homeworkQuery->offset($pages->getOffset())->limit($pages->getLimit())->all();

        if (app()->request->isAjax) {
            $chapter = app()->request->getQueryParam('chapter');
            $homeworkQuery->andWhere(['chapterId' => $chapter]);
            $homeworkResult = $homeworkQuery->offset($pages->getOffset())->limit($pages->getLimit())->all();
            $pages->totalCount = $homeworkQuery->count();
            $pages->params = ['department' => $department, 'edition' => $edition, 'subject' => $subject, 'text' => $text, 'difficulty' => $difficulty];
            return $this->renderPartial('_homework_list', array('homeworkResult' => $homeworkResult, 'pages' => $pages));
        }
        $array = array('department' => $department,
            'subject' => $subject,
            'edition' => $edition,
            'tomeResult' => $tomeResult->cPointList,
            'treeData' => $treeData,
            'homeworkResult' => $homeworkResult,
            'pages' => $pages,
            'difficulty' => $difficulty
        );
        return $this->render('libraryList', $array);
    }

    /**
     *作业库详情
     */
    public function actionLibraryDetails($homeworkID)
    {
        $this->layout = 'lay_prepare';
        $homeworkData = SeHomeworkPlatform::find()->where(['id' => $homeworkID])->select('subjectId,gradeId,version,id,name')->one();
//        根据homeworkID查询questionid
        $questionList = SeHomeworkQuestionPlatform::find()->where(['homeworkId' => $homeworkID])->select('questionId')->asArray()->all();
        if (empty($questionList)) {
            return $this->notFound();
        }
//        查询题目的具体内容
        $homeworkResult = ShTestquestion::find()->where(['id' => ArrayHelper::getColumn($questionList, 'questionId')])->all();
//        判断是否已经加入作业
        $homeworkIsExist = SeHomeworkTeacher::find()->where(['homeworkPlatformId' => $homeworkID, 'sourceType' => 1, 'creator' => user()->id])->exists();
        return $this->render('libraryDetails', ['homeworkData' => $homeworkData,
            'homeworkResult' => $homeworkResult,
            'homeworkIsExist' => $homeworkIsExist,
        ]);
    }

    /**
     * 后台推送的作业的详情页
     * @param $homeworkID
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionPushedLibraryDetails($homeworkID)
    {
        $this->layout = 'platform_blank';
        $homeworkData = SeHomeworkPlatform::find()->where(['id' => $homeworkID])->select('subjectId,gradeId,version,homeworkDescribe,id,name,difficulty')->one();
//        根据homeworkID查询questionid
        $questionList = SeHomeworkQuestionPlatform::find()->where(['homeworkId' => $homeworkID])->select('questionId')->orderBy('orderNumber')->asArray()->all();
        if (empty($questionList)) {
            return $this->notFound();
        }
//        查询题目的具体内容
        $homeworkResult=[];
        foreach($questionList as $v){
             $oneHomework=Es_testQuestion::find()->where(['id' => $v['questionId']])->one();
            array_push($homeworkResult,$oneHomework);
        }
//        判断是否已经加入作业
        $homeworkIsExist = SeHomeworkTeacher::find()->where(['homeworkPlatformId' => $homeworkID, 'creator' => user()->id])->exists();
//        查询作业关联的资料列表
        $materialIdArray = SeHomeworkPlatformMaterials::find()->where(['id' => $homeworkID])->select('materialId')->asArray()->all();
        $materialList = SrMaterial::find()->where(['id' => ArrayHelper::getColumn($materialIdArray, 'materialId')])->all();
//        查询作业关联的视频列表
        $videoIdArray = SeHomeworkPlatformVideos::find()->where(['id' => $homeworkID])->select('videoId')->asArray()->all();
        $videoList = ShVideolesson::find()->where(['lid' => ArrayHelper::getColumn($videoIdArray, 'videoId')])->all();
        return $this->render('pushedLibraryDetails', ['homeworkData' => $homeworkData,
            'homeworkResult' => $homeworkResult,
            'homeworkIsExist' => $homeworkIsExist,
            'materialList' => $materialList,
            'videoList' => $videoList
        ]);
    }

    /**
     * 提出意见
     * @return string
     */
    public function actionAddSuggest()
    {
        $homeworkID = app()->request->getBodyParam('homeworkID');
        $suggestion = app()->request->getBodyParam('suggestion');

        $jsonResult = new JsonMessage();
        $model = new SeHomeworkPlatformSuggest();
        $model->id = $homeworkID;
        $model->comment = $suggestion;
        $model->userID = user()->id;
        $model->createTime = DateTimeHelper::timestampX1000();
        if ($model->save()) {
            $jsonResult->success = true;
        }
        return $this->renderJSON($jsonResult);
    }

    /**
     * @return string
     *    判断是否已经加入作业
     */
    public function actionIsExist()
    {
        $jsonResult = new JsonMessage();
        $homeworkID = app()->request->getBodyParam('homeworkID');
        $homeworkIsExist = SeHomeworkTeacher::find()->where(['homeworkPlatformId' => $homeworkID, 'creator' => user()->id])->exists();
        $jsonResult->success = $homeworkIsExist;
        return $this->renderJSON($jsonResult);
    }

    /**
     * 加入作业
     * @return string
     */
    public function actionLibraryJoinTeacher()
    {
        $userID = user()->id;
        $jsonResult = new JsonMessage();
        $homeworkID = app()->request->getBodyParam('homeworkID');
        //        判断是否已经加入作业
        $homeworkIsExist = SeHomeworkTeacher::find()->where(['homeworkPlatformId' => $homeworkID,  'creator' => $userID])->exists();
        if ($homeworkIsExist) {
            $jsonResult->message = '您已经加入当前作业了';
        } else {
            $jsonResult->success = SeHomeworkTeacher::collectHomework($homeworkID,$userID);
        }
        return $this->renderJSON($jsonResult);
    }

    /**
     * @param $relId
     * @param $studentID
     * @return string
     */
    public function actionNewOrgCorrect($homeworkAnswerID)
    {

        $oneAnswerResult = SeHomeworkAnswerInfo::find()->where(['homeworkAnswerID' => $homeworkAnswerID])->one();
        $this->layout = 'lay_prepare';
//        根据relId查询当前作业所有提交了答案的学生
        $homeworkAnswerResult = SeHomeworkAnswerInfo::find()->where(['relId' => $oneAnswerResult->relId])->all();

//        查询当前作业当前学生提交的图片
        $answerImageResult = SeHomeworkAnswerImage::find()->where(['homeworkAnswerID' => $homeworkAnswerID])->select('url')->asArray()->all();
        $answerImageArray = ArrayHelper::getColumn($answerImageResult, 'url');

        //        根据relId查询homeworkId
        $homeworkID = SeHomeworkRel::find()->where(['id' => $oneAnswerResult->relId])->one()->homeworkId;
//        根据homeworkID查询题目
        $homeworkResult = SeHomeworkTeacher::find()->where(['id' => $homeworkID])->one();
        $questionArray = $this->findAll($homeworkID);
        return $this->render('newOrgCorrect', [
            'homeworkAnswerResult' => $homeworkAnswerResult,
            'questionArray' => $questionArray,
            'homeworkID' => $homeworkID,
            'oneAnswerResult' => $oneAnswerResult,
            'answerImageArray' => $answerImageArray,
            'homeworkAnswerID' => $homeworkAnswerID,
            'homeworkResult' => $homeworkResult
        ]);
    }

    /**
     * 纸质作业的批改页面
     * @param $homeworkAnswerID
     * @return string
     */
    public function actionNewPicCorrect($homeworkAnswerID)
    {
        $this->layout = 'lay_prepare';
        $oneAnswerResult = SeHomeworkAnswerInfo::find()->where(['homeworkAnswerID' => $homeworkAnswerID])->one();
//        根据relId查询当前作业所有提交了答案的学生
        $homeworkAnswerResult = SeHomeworkAnswerInfo::find()->where(['relId' => $oneAnswerResult->relId])->all();
//        查询当前学生提交的图片
        $imageResult = SeHomeworkAnswerDetailImage::find()->where(['homeworkAnswerID' => $homeworkAnswerID])->select('imageUrl')->asArray()->all();
        $imageArray = ArrayHelper::getColumn($imageResult, 'imageUrl');
        return $this->render('newPicCorrect', [
            'oneAnswerResult' => $oneAnswerResult,
            'homeworkAnswerID' => $homeworkAnswerID,
            'homeworkAnswerResult' => $homeworkAnswerResult,
            'imageArray' => $imageArray
        ]);
    }

    /**
     * ajax批改纸质作业
     * @return string
     */
    public function actionAjaxPicCorrect()
    {
        $jsonResult = new JsonMessage();
        $correctLevel = app()->request->getBodyParam('correctLevel');
        $homeworkAnswerID = app()->request->getBodyParam('homeworkAnswerID');
        $answerQuery = SeHomeworkAnswerInfo::find()->where(['homeworkAnswerID' => $homeworkAnswerID])->one();
        $answerQuery->correctLevel = $correctLevel;
        $answerQuery->isCheck = '1';
        $answerQuery->checkTime=DateTimeHelper::timestampX1000();
        if ($answerQuery->save(false)) {
            $jsonResult->success = true;
        }
        return $this->renderJson($jsonResult);

    }

    /**
     *批改作业更新主表的批改状态
     */
    public function actionUpdateHomCorrectLevel()
    {
        $jsonResult=new JsonMessage();
        $homeworkAnswerID = app()->request->getBodyParam('homeworkAnswerID');
        $homeworkServer=new pos_HomeWorkManageService();
        $homeworkResult=$homeworkServer->autoHomeworkCorrectResult($homeworkAnswerID);
//                修改答案主表的isCheck
        $answerInfoQuery = new SeHomeworkAnswerInfo;
        $answerInfoQuery->updateAll(['isCheck' => 1,'checkTime'=>DateTimeHelper::timestampX1000()], ['homeworkAnswerID' => $homeworkAnswerID]);
        if($homeworkResult->resCode==BaseService::successCode){
            $jsonResult->success=true;
        }
        return $this->renderJSON($jsonResult);

    }

    /**
     * ajax电子批改作业
     * @return string
     */
    public function actionNewAjaxCorrect()
    {
        $jsonResult = new JsonMessage();
        $questionID = app()->request->getBodyParam('questionID');
        $homeworkAnswerID = app()->request->getBodyParam('homeworkAnswerID');
        $correctResult = app()->request->getBodyParam('correctResult');
        $answerQuestionAllQuery = SeHomeworkAnswerQuestionAll::find()->where(['questionID' => $questionID, 'homeworkAnswerID' => $homeworkAnswerID])->one();
        $answerQuestionAllQuery->correctResult = $correctResult;
        if ($answerQuestionAllQuery->save()) {
            $answerQuestionAllQuery->updateMain();
            $jsonResult->success = true;

        }
        return $this->renderJSON($jsonResult);
    }

    /**
     * 题目详情弹窗
     * @return string
     */
    public function actionGetQuestionContent()
    {
        $questionID = app()->request->getBodyParam('questionID');
//        查询题目的具体内容
        $questionResult = ShTestquestion::find()->where(['id' => $questionID])->one();
        return $this->renderPartial('question_content', ['questionResult' => $questionResult]);
    }

    /**
     * @param $questionResult
     * @return array
     */
    public function findAll($homeworkID)
    {
        $questionResult = SeHomeworkQuestion::find()->where(['homeworkId' => $homeworkID])->select('questionId')->orderBy('questionId')->asArray()->all();
        $questionArray = array();
        foreach ($questionResult as $v) {
            $partQuestionQuery = ShTestquestion::find()->where(['mainQusId' => $v['questionId']]);
//            判断当前大题是否有小题

            if ($partQuestionQuery->exists()) {
                $partQuestionResult = $partQuestionQuery->select('id')->all();
                foreach ($partQuestionResult as $value) {
                    $questionID = $value->id;
                    $shTestquestion = ShTestquestion::find()->where(['id' => $questionID])->select('tqtid')->one();
                    if ($shTestquestion && $shTestquestion->isMajorQuestionCache()) {
                        array_push($questionArray, $questionID);
                    }

                }
            } else {
                $questionID = $v['questionId'];
                $shTestquestion = ShTestquestion::find()->where(['id' => $questionID])->select('tqtid')->one();
                if ($shTestquestion && $shTestquestion->isMajorQuestionCache()) {
                    array_push($questionArray, $questionID);
                }
            }
        }
        return $questionArray;
    }


}