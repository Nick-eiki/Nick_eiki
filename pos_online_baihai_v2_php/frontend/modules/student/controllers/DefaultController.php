<?php
namespace frontend\modules\student\controllers;
use common\helper\DateTimeHelper;
use common\models\JsonMessage;
use common\models\pos\SeAnswerQuestion;
use common\models\pos\SeClassMembers;
use common\models\pos\SeQuestionResult;
use common\models\pos\SeSameQuestion;
use common\services\JfManageService;
use common\services\KeyWordsService;
use frontend\components\BaseAuthController;
use frontend\components\WebDataCache;
use frontend\services\apollo\Apollo_MaterialService;
use frontend\services\apollo\Apollo_VideoLessonInfoService;
use frontend\services\BaseService;
use frontend\services\pos\pos_ClassInfoService;
use frontend\services\pos\pos_ClassMembersService;
use frontend\services\pos\pos_FavoriteFolderService;
use frontend\services\pos\pos_HonorManageService;
use frontend\services\pos\pos_PersonalInformationService;
use yii\data\Pagination;


/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-12-1
 * Time: 下午1:19
 */
class DefaultController extends BaseAuthController
{
    public $layout = 'lay_user_home';

    /*
     * 首页
    */
    public function actionIndex($studentId = 0)
    {

        //关于答疑列表的展示
        $this->IsInto($studentId);
        $this->view->params["studentId"]=$studentId;
        $pages = new Pagination();
        $pages->validatePage=false;
        $pages->pageSize = 10;
        $answerQuestion = SeAnswerQuestion::find()->active()->where('creatorID=:CreatorId OR (aqID IN (SELECT rel_aqID FROM se_questionResult WHERE creatorID=:CreatorId))',[':CreatorId' => $studentId]);
        $modelList = $answerQuestion->orderBy('createTime desc')->offset($pages->getOffset())->limit($pages->getLimit())->all();
        $pages->totalCount = $answerQuestion->count();
        //关于答疑总汇的统计
        $useCnt = SeQuestionResult::getUserRelyQuestion($studentId);
        $answerCnt = SeQuestionResult::getUserAnswerQuestion($studentId);
        $askQuesCnt = SeAnswerQuestion::getUserAskQuestion($studentId);
        if ($useCnt == null || $answerCnt == null || $askQuesCnt == null) {
            return $this->notFound();
        }

        return $this->render('new_index', array('modelList' => $modelList, 'studentId' => $studentId, 'pages' => $pages, 'useCnt' => $useCnt, 'answerCnt' => $answerCnt, 'askQuesCnt' => $askQuesCnt));
    }

    /**
     * @param $studentId
     * @throws CHttpException
     */
    public function IsInto($studentId)
    {
        if ($studentId == 0) {
            $studentId = user()->id;
            return $this->redirect(url('student/default/index', ['studentId' => $studentId]));

        }

        $user = loginUser()->getUserInfo($studentId);
        if ($user == null) {
            return $this->notFound();
        }
        if ($user->isTeacher()) {
            return $this->redirect(url('teacher/default/index', ['teacherId' => $studentId]));
        }

//判断当前用户是否有进入所访问页面的权限
        $canIn = new pos_PersonalInformationService();
        $res = $canIn->judgeUserCanIn(user()->id, $studentId, 0);
        if ($res['isUserCanIn'] != 1) {
            return $this->notFound("你没有权限查看",403);
        }
    }

    /**
     * 点击查看更多
     * @throws CException
     */
    public function actionGetPages()
    {
        $pages = new Pagination();
        $pages->validatePage=false;
        $pages->pageSize = 10;
        $userId = app()->request->getQueryParam('userid', '');
        $answerQuestion = SeAnswerQuestion::find()->active()->where('creatorID=:CreatorId OR (aqID IN (SELECT rel_aqID FROM se_questionResult WHERE creatorID=:CreatorId))',[':CreatorId' => $userId]);
        $modelList = $answerQuestion->orderBy('createTime desc')->offset($pages->getOffset())->limit($pages->getLimit())->all();
        $pages->totalCount = $answerQuestion->count();
        if ($modelList != null) {
            return $this->renderPartial('_list_view', array('modelList' => $modelList, 'studentId' => $userId, 'pages' => $pages));
        }
    }

    /**
     *获取班级成员
     */
    public function getClassMember()
    {
        $studentId = app()->request->getQueryParam("studentId");
        $classServer = new pos_ClassMembersService();
        $classInfo = loginUser()->getUserInfo($studentId)->getClassInfo();
        $classID = $classInfo[0]->classID;
        $classResult = $classServer->loadRegisteredMembers($classID, "1", $studentId);
        return $classResult;
    }

    /**
     * 获取班级教师和学生排除自己
     * @return array
     */
    public function getClassMemberAll($studentId){
        $classResult =[];
        if(!empty($studentId)){
            $classInfo = loginUser()->getClassInfo($studentId);
            $classID = $classInfo[0]->classID;
            if(!empty($classInfo)){
                $classResult = SeClassMembers::find()->where(['classID' => $classID])->all();
            }
        }
        return $classResult;
    }

    /**
     *获取手拉手班级
     */
    public function getHandClass()
    {
        $studentID = app()->request->getQueryParam("studentId", "");
        $classID = loginUser()->getUserInfo($studentID)->getClassInfo()[0]->classID;
        $classServer = new pos_ClassInfoService();
        $classResult = $classServer->queryBinderClassByID($classID);
        return $classResult;
    }

    /**
     *查询荣誉
     */
    public function actionSearchHonor()
    {
        $studentId = app()->request->getQueryParam('studentId', '');
        $honorManage = new pos_HonorManageService();
        $result = $honorManage->queryHonor($studentId, '', '50301');
        return $this->renderPartial('_search_honor_view', array('modelHonorList' => $result->honorList,));
    }

    /**
     *添加荣誉
     */
    public function actionAddHonor()
    {

        $honorInfor = app()->request->getQueryParam('name', '');
        $userId = user()->id;
        $honorType = '50301';
        $honorManage = new pos_HonorManageService();
        $result = $honorManage->addHonor($honorInfor, $userId, $honorType);
        $jsonResult = new JsonMessage();
        if ($result->resCode === BaseService::successCode) {
            $jsonResult->success = true;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = '荣誉添加失败!';
            return $this->renderJSON($jsonResult);
        }
    }

    /**
     *修改荣誉
     */
    public function actionEditHonor()
    {
        $honorInfor = app()->request->getQueryParam('name', '');
        $honorID = app()->request->getQueryParam('id', '');
        $honorManage = new pos_HonorManageService();
        $result = $honorManage->honorModify($honorID, $honorInfor);
        $jsonResult = new JsonMessage();
        if ($result->resCode === BaseService::successCode) {
            $jsonResult->success = true;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = '荣誉修改失败!';
            return $this->renderJSON($jsonResult);
        }
    }

    /**
     *删除荣誉
     */
    public function actionDelHonor()
    {
        $honorID = app()->request->getQueryParam('id', '');
        $honorManage = new pos_HonorManageService();
        $result = $honorManage->honorDelete($honorID);
        $jsonResult = new JsonMessage();
        if ($result->resCode === BaseService::successCode) {
            $jsonResult->success = true;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = '荣誉删除失败!';
            return $this->renderJSON($jsonResult);
        }
    }

    /**
     *学生收藏夹
     */
    public function actionCollection($studentId)
    {

        $userId = user()->id;
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 6;

        $student = new pos_FavoriteFolderService();
        if ($studentId == $userId) {
            $type = app()->request->getQueryParam('type', '1,2,3');
            $model = $student->queryFavoriteFolder($studentId, $type, $pages->getPage() + 1, $pages->pageSize, '');
            $pages->params = array('studentId' => $studentId, 'type' => $type);
        } else {
            $type = 3;
            $model = $student->otherQueryFavoriteFolder($studentId, $type, $userId, $pages->getPage() + 1, $pages->pageSize);
        }
        $pages->totalCount = intval($model->countSize);
        if (app()->request->isAjax) {
            return $this->renderPartial('_student_site_view', array('model' => $model->list, 'pages' => $pages, 'studentId' => $studentId));

        }
        if (!empty($model)) {
            return $this->render('collection', array('model' => $model->list, 'pages' => $pages, 'userId' => $userId, 'studentId' => $studentId));
        }
    }

    /**
     *取消收藏
     */
    public function actionDelCollection()
    {
        $id = intval($_POST['collectID']);
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
     *添加收藏
     */
    public function actionAddCollection()
    {
        $add = new pos_FavoriteFolderService();
        $id = app()->request->getQueryParam('collectID', 0);
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
     * 收藏详情
     * @param $id
     */
    public function actionDetail($id)
    {

        $detail = new Apollo_MaterialService();
        $userId = user()->id;
        $model = $detail->getMaterialById($id, '', $userId);
        if (!empty($model)) {
            return $this->render('detail', array('model' => $model));
        } else {
            return $this->notFound();
        }
    }

    /**
     * 视频详情
     * @param $id
     */
    public function actionVideoDetail($id)
    {
        $video = new Apollo_VideoLessonInfoService();
        $userId = user()->id;
        $model = $video->searchVideoDetailById($id, $userId);
        if ($model == null) {
            return $this->notFound();
        }
        return $this->render('videoDetail', array('model' => $model));
    }

    /*
     * 学生个人主页答疑
     * 答疑列表
     */

    /**
     *下载次数
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

    //回答问题

    public function actionAnswerQuestions()
    {
        $user = app()->request->getQueryParam('studentId', '');
        $pages = new Pagination();
        $pages->validatePage=false;
        $pages->pageSize = 2;

        $keyWord = app()->request->getParam('content', '');
        $questionQuery = SeAnswerQuestion::find()->active()->where(['creatorID'=>$user]);
        if(!empty($keyWord)){
            $questionQuery->andWhere(['like','aqName',$keyWord]);
        }
        $pages->totalCount = $questionQuery->count();
        $questionList = $questionQuery->orderBy('createTime desc')->offset($pages->getOffset())->limit($pages->getLimit())->all();

        if (app()->request->isAjax) {
            return $this->renderPartial('_answerquestions_list', array('modelList' => $questionList, 'pages' => $pages));

        }
        return $this->render('answerquestions', array('modelList' => $questionList, 'pages' => $pages));

    }

    public function actionResultQuestion()
    {
        $aqid = app()->request->post('aqid', 0);
        $answer = app()->request->post('answer', '');

        $questionResult = new SeQuestionResult;
        $questionResult->creatorID = user()->id;
        $questionResult->rel_aqID = $aqid;
        $questionResult->resultDetail = KeyWordsService::ReplaceKeyWord($answer);
        $questionResult->createTime = DateTimeHelper::timestampX1000();
        $questionResult->creatorName = WebDataCache::getTrueName(user()->id);
        if ($questionResult->save()) {
//             回复答疑增加积分
            $jfHelper=new JfManageService;
            $jfHelper->myAccount("pos-request",user()->id);
            return $this->renderPartial('_answer_problem',['answer'=>$answer]);
        }
    }

    /**
     *同问问题
     */
    public function actionSameQuestion()
    {
        $jsonResult = new JsonMessage();
        $aqid = app()->request->post('aqid', 0);

        //检查该用户是否同问过
        $selSame = SeSameQuestion::find()->where(['aqID'=>$aqid, 'sameQueUserId'=>user()->id])->one();
        if(!empty($selSame)){
            $jsonResult->success = false;
            $jsonResult->message = "您已同问过该问题！";
            return 	$this->renderJSON($jsonResult);
        };

        $sameQuestion = new SeSameQuestion();
        $sameQuestion->aqID = $aqid;
        $sameQuestion->sameQueUserId = user()->id;
        if ($sameQuestion->save()) {
            $jfHelper=new JfManageService;
            $jfHelper->myAccount("pos-identical",user()->id);
            $jsonResult->success = true;
            $jsonResult->message = "同问成功！";
        } else {
            $jsonResult->success = false;
            $jsonResult->message = '同问失败！';
        }

        return $this->renderJSON($jsonResult);
    }

    /**
     * 采用答案
     */
    public function actionUseTheAnswer()
    {
        $jsonResult = new JsonMessage();
        $resultid = app()->request->post('resultid', 0);

        if(empty($resultid)){
            $jsonResult->success = false;
            $jsonResult->message = '请正确采用！';
        }
        $useAnswer = SeQuestionResult::updateAll(['isUse'=>'1','useTime'=>DateTimeHelper::timestampX1000()],'resultID=:resultid',[':resultid'=>$resultid]);
        if ($useAnswer == 1 ) {
            $jfHelper=new JfManageService;
            $jfHelper->myAccount("pos-accept",user()->id);
            $jsonResult->success = true;
            $jsonResult->message = '采用成功！';
        } else {
            $jsonResult->success = false;
            $jsonResult->message = '采用失败！';
        }
        return $this->renderJSON($jsonResult);
    }
}