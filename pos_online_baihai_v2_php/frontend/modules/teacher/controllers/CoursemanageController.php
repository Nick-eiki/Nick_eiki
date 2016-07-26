<?php
namespace frontend\modules\teacher\controllers;
use common\models\JsonMessage;
use frontend\components\TeacherBaseController;
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\KnowledgePointModel;
use frontend\models\NewCourseForm;
use frontend\modules\teacher\models\CreateParMetForm;
use frontend\modules\teacher\models\ModifyParMetForm;
use frontend\modules\teacher\models\UpdateCourseForm;
use frontend\services\apollo\Apollo_MaterialService;
use frontend\services\BaseService;
use frontend\services\pos\pos_ClassSectionSummaryService;
use frontend\services\pos\pos_CourseService;
use frontend\services\pos\pos_EduInformationService;
use frontend\services\pos\pos_FavoriteFolderService;
use frontend\services\pos\pos_InternetParMeetingService;
use frontend\services\pos\pos_LiveCourseService;
use frontend\services\pos\pos_SchoolTeacherService;
use yii\data\Pagination;

/**
 * Created by PhpStorm.
 * User: unizk
 * Date: 14-10-30
 * Time: 下午3:43
 */
class CoursemanageController extends TeacherBaseController
{
    public $layout = 'lay_user';
    public function actionIndex(){
       return $this->render('index');
    }

	/*
	 * 课程管理 ->列表
	 * wgl
	 */
	public function actionCourseManage()
	{
        $classId = loginUser()->getModel()->getNewClassInfo()[0]->classID;
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 5;
        $material = new pos_LiveCourseService();
        $result = $material->searchLiveCourse('', $classId, '', '', $pages->getPage() + 1, $pages->pageSize, "");
        $pages->totalCount = intval($result->countSize);
		if(app()->request->isAjax){
			return $this->renderPartial('_course_list',array(
				'model'=>$result->courseList,
				'pages'=>$pages
			));

		}

		 return $this->render('courseManage', array('model' => $result->courseList, 'pages' => $pages));
	}

	/**
	 * 直播课程->创建课程
     * wgl
	 */
	public function actionNewCourse()
    {
        $userId = user()->id;
        $dataBag = new NewCourseForm();

        if (isset($_POST['NewCourseForm'])) {
            $dataBag->attributes = $_POST['NewCourseForm'];
            //检测是否上传图片
            if(isset($_POST['picurls'])){
                $dataBag->url = implode(',',$_POST['picurls']);
            } else {
                $dataBag->url = '';
            }
            $material = new pos_LiveCourseService();
            $result = $material->createLiveInfo($userId,$dataBag->courseName, $dataBag->filesID, $dataBag->connetID, $dataBag->handoutID, $dataBag->beginTime, $dataBag->finishTime, $dataBag->url, strip_tags($dataBag->courseBrief), $dataBag->classId, $dataBag->subjectID,  $userId, $dataBag->versionID, $dataBag->gradeID);
             if ($result->resCode == pos_LiveCourseService::successCode) {
                 return $this->redirect(url('teacher/coursemanage/course-manage'));
             }
        }
		 return $this->render('newCourse',array('model' => $dataBag));
	}
    /*
     * 课程详情页
     * wgl 14-11-13
     */
    public function actionCourseDetails(){
        $courseId = app()->request->getParam('courseId', 0);
        $type = 50402;
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 3;
        //评论列表
        $commentMaterial = new pos_EduInformationService();
        $commentList = $commentMaterial->searchCommentInformation($courseId, $type, $pages->getPage() + 1, $pages->pageSize);
        $pages->totalCount = intval($commentList->countSize);
        //课程详情
        $material = new pos_LiveCourseService();
        $result = $material->searchLiveCourseByID($courseId,user()->id);
		if($result->resCode == 100001){
			return $this->notFound();
		}
        //查询讲义名称
        $handout = new Apollo_MaterialService();
        $handoutName = $handout->getMaterialById($result->data->handoutID,'','');
$kcidName = '';
        if($result->data->connectID == 1){
            //章节
            $chapt = new ChapterInfoModel();
            $kcidName = $chapt->findChapter($result->data->filesID);
        }
        elseif($result->data->connectID == 0)
        {
            //知识点
            $know = new KnowledgePointModel();
            $kcidName = $know->findKnowledge($result->data->filesID);
        }
		if (app()->request->isAjax) {
			return $this->renderPartial('_comment_list', array(
				'commentList' => $commentList->list,
				'pages' => $pages));
			return;
		}

       return $this->render('courseDetails',array('model' => $result->data, 'kcidName' => $kcidName, 'handoutName'=>$handoutName, 'commentList' => $commentList->list,'pages' => $pages));
    }

    /**
     * 教师收藏讲义
     * wgl 14-12-5
     */
    public function actionCollect()
    {
        $favoriteId = app()->request->getParam('favoriteId',0);
        $favoriteType = 2;
        $userId = user()->id;
        $material = new pos_FavoriteFolderService();
        $result = $material->addFavoriteFolder($favoriteId,$favoriteType,$userId);
        $jsonResult = new JsonMessage();
        if($result->resCode == BaseService::successCode)
        {
            $jsonResult->success = true;
            $jsonResult->message = '收藏成功！';

        }else{
            $jsonResult->success = false;
            $jsonResult->message = '收藏失败！';
        }
        return $this->renderJSON($jsonResult);
    }
    /**
     * 教师取消收藏讲义
     * wgl 14-12-5
     */
    public function actionUndoCollect()
    {
        $collectID = app()->request->getParam('collect',0);

        $material = new pos_FavoriteFolderService();
        $result = $material->delFavoriteFolder($collectID);
        $jsonResult = new JsonMessage();
        if($result->resCode == BaseService::successCode)
        {
            $jsonResult->success = true;
            $jsonResult->message = '取消成功！';

        }else{
            $jsonResult->success = false;
            $jsonResult->message = '取消失败！';
        }
        return $this->renderJSON($jsonResult);
    }

    /**
     * 修改课程
     * wgl 14-12-10
     */
    public function actionUpdateCourse()
    {

        $creatorId = user()->id;
        $courseId = app()->request->getParam('courseId', 0);
        $material = new pos_LiveCourseService();
        $result = $material->searchLiveCourseByID($courseId,'');
		if($result->resCode == 100001){
			return $this->notFound();
		}
        //查询讲义名称
        $handout = new Apollo_MaterialService();
        $handoutName = $handout->getMaterialById($result->data->handoutID,'','');
        $dataBag = new UpdateCourseForm();
        $dataBag->courseID = $result->data->courseID;
        $dataBag->courseName = $result->data->courseName;
        $dataBag->filesID = $result->data->filesID;
        $dataBag->connetID = $result->data->connectID;
        $dataBag->handoutID = $result->data->handoutID;
        $dataBag->beginTime = $result->data->beginTime;
        $dataBag->finishTime = $result->data->finishTime;
        $dataBag->url = $result->data->url;
        $dataBag->courseBrief = $result->data->courseBrief;
        $dataBag->classId = $result->data->classId;
        $dataBag->subjectID = $result->data->subjectID;
        $dataBag->teacherID = $result->data->teacherID;
        $dataBag->versionID = $result->data->versionID;
        $dataBag->gradeID = $result->data->gradeID;

        //检测是否上传图片
        if(isset($_POST['picurls'])) {
            $dataBag->url = implode(',', $_POST['picurls']);
        }
        if (isset($_POST['UpdateCourseForm'])) {
            $dataBag->attributes = $_POST['UpdateCourseForm'];
            if($dataBag->validate()) {
                $updateInfo = $material->modifyLiveInfo( $dataBag->courseID, $creatorId, $dataBag->courseName, $dataBag->filesID, $dataBag->connetID, $dataBag->handoutID, $dataBag->beginTime, $dataBag->finishTime, $dataBag->url, $dataBag->courseBrief, $dataBag->classId, $dataBag->subjectID, $dataBag->teacherID, $dataBag->versionID, $dataBag->gradeID
                );
                if($updateInfo->resCode == pos_LiveCourseService::successCode)
                {
                    return $this->redirect(url('teacher/coursemanage/course-details',array('courseId'=>$courseId)));
                }
            }
        }
       return $this->render('updateCourse', array('model' => $dataBag,'handoutName'=>$handoutName));
    }

    /**
     *获取讲义列表
     * wgl 14-11-13
     */
    public function actionGetDoc()
    {
        $gradeId = app()->request->getParam("gradeId", '');
        $materials = app()->request->getParam("materials", '');
        $subjectId = app()->request->getParam("subjectId", '');
        $userId = user()->id;
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 4;
        $getData = new Apollo_MaterialService();
        $model = $getData->queryMaterial('','','2','','','',$gradeId,$subjectId,$materials,'','','','','','',$userId,'','','','','',$pages->getPage()+1, $pages->pageSize);
        $pages->totalCount = intval($model->countSize);
        return $this->renderPartial('_getDoc_view', array('model' => $model->list, 'pages' => $pages));
    }

    /**
     * 添加评论
     * wgl 14-12-3
     */
    public function actionReplyInformation()
    {
        $commentContent = app()->request->getParam('comment', 0);
        $informationId  = app()->request->getParam('informationId', 0);
        $informationName = $_POST['informationName'];
        $commentUserID = user()->id;
        $commentType = 50402;
        $material = new pos_EduInformationService();
        $result = $material->commentAdd($commentContent, $informationId, $commentUserID, $commentType, $informationName);
        $jsonResult = new JsonMessage();
        if($result->resCode == BaseService::successCode)
        {
            $jsonResult->success = true;
            $jsonResult->message = '添加成功！';
        }else{
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
        $commentId = app()->request->getParam("commentId", 0);
        $replyContent = app()->request->getParam("replyContent", 0);
        $replayTargetUserID = app()->request->getParam("targetUserId", 0);
        $replayType = app()->request->getParam("commentType", 0);
        $replayUserId = user()->id;
        $material = new pos_EduInformationService();
        $result = $material->replyAdd( $commentId, $replyContent, $replayUserId,$replayTargetUserID,$replayType);
        $jsonResult = new JsonMessage();
        if($result->resCode == BaseService::successCode)
        {
            $jsonResult->success = true;
            $jsonResult->message = '添加成功！';

        }else{
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
        $commentId = app()->request->getParam("commentId", 0);
        $material = new pos_EduInformationService();
        $result = $material->commentDelete($commentId);

        $jsonResult = new JsonMessage();
        if($result->rosCode == BaseService::successCode)
        {
            $jsonResult->success = true;
        }else{
            $jsonResult->success = false;
            $jsonResult->message = '删除失败！';
        }
        return $this->renderJSON($jsonResult);
    }

    //举报评论
    public function actionReportComment()
    {
        $commentId = app()->request->getParam('commentId', 0);
        $material = new pos_EduInformationService();
        $result = $material->commentReport($commentId);
        $jsonResult = new JsonMessage();
        if($result->rosCode == BaseService::successCode)
        {
            $jsonResult->success = true;
            $jsonResult->message = "举报成功！";
        }else{
            $jsonResult->success = false;
            $jsonResult->message = "举报失败！";
        }
        return $this->renderJSON($jsonResult);
    }
    //删除回复
    public function actionDeleteReplay()
    {
        $replayId = app()->request->getParam("replayId", 0);

        $material = new pos_EduInformationService();
        $result = $material->replyDelete($replayId);
        $jsonResult = new JsonMessage();
        if($result->rosCode == BaseService::successCode)
        {
            $jsonResult->success = true;
        }else{
            $jsonResult->success = false;
            $jsonResult->message = '删除失败！';
        }
        return $this->renderJSON($jsonResult);
    }

    //举报回复
    public function actionReportReplay()
    {
        $replayId = app()->request->getParam('replayId', 0);
        $material = new pos_EduInformationService();
        $result = $material->replayReport($replayId);
        $jsonResult = new JsonMessage();
        if($result->rosCode == BaseService::successCode)
        {
            $jsonResult->success = true;
            $jsonResult->message = "举报成功！";
        }else{
            $jsonResult->success = false;
            $jsonResult->message = "举报失败！";
        }
        return $this->renderJSON($jsonResult);
    }

    //对回复进行回复
    public function actionPReplay()
    {
        $preplayId = app()->request->getParam('preplayId', 0);
        $commentId = app()->request->getParam("commentId", 0);
        $targetUers = app()->request->getParam("targetUers", 0);
        $replayContent = app()->request->getParam("replayContent", 0);
        $replayType = app()->request->getParam("commentType", 0);
        $replayUserId = user()->id;
        $material = new pos_EduInformationService();
        $result = $material->preplyAdd($preplayId, $replayContent, $commentId, $replayUserId,  $targetUers, $replayType);

        $jsonResult = new JsonMessage();
        if($result->resCode == BaseService::successCode)
        {
            $jsonResult->success = true;
            $jsonResult->message = '添加成功！';

        }else{
            $jsonResult->success = false;
            $jsonResult->message = '添加失败！';
        }
        return $this->renderJSON($jsonResult);
    }

    /**
     * 我的评论
     */
    public function actionMyComment()
    {
        $commentUserId = user()->id;
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 3;
        $material = new pos_EduInformationService();
        $commentList = $material->searchCommentInformationByUse($commentUserId,$pages->getPage()+1, $pages->pageSize);
        $pages->totalCount = intval($commentList->countSize);
       return $this->render('myComment',array('commentList'=>$commentList->list, 'pages' => $pages));
    }

    /**
     *直播课程 获取讲义列表
     * wgl 14-2-4
     */
    public function actionGetHandouts()
    {
        $gradeId = app()->request->getParam("gId", '');
        $materials = app()->request->getParam("materials", '');
        $subjectId = app()->request->getParam("subId", '');
        $userId = user()->id;
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 4;
        $getData = new Apollo_MaterialService();
        $model = $getData->queryMaterial('','','2','','','',$gradeId,$subjectId,$materials,'','','','','','',$userId,'','','','','',$pages->getPage()+1, $pages->pageSize);
        $pages->totalCount = intval($model->countSize);
        return $this->renderPartial('_handouts_view', array('model' => $model->list, 'pages' => $pages));



    }


    /**
     * 上传讲议
     * wgl 14-12-4
     *
     */
    public function actionUploadHandouts()
    {
        $handoutId = app()->request->getParam('handoutId', 0);
        $courseId = app()->request->getParam("courseId", 0);
        $creatorID = user()->id;
        $material = new pos_LiveCourseService();
        $result = $material->uploadHandoutToLiveCourse($courseId, $handoutId,$creatorID);
        $jsonResult = new JsonMessage();
        if($result->resCode == BaseService::successCode)
        {
            $jsonResult->success = true;
            $jsonResult->message = '添加成功！';

        }else{
            $jsonResult->success = false;
            $jsonResult->message = '添加失败！';
            return false;
        }
        return $this->renderJSON($jsonResult);
    }

    /**
     * 直播课程 讲义详情页
     * wgl 14-12-4
     */
    public function actionHandoutDetails()
    {
        $handoutId = app()->request->getParam('handoutId', 0);
        $material = new Apollo_MaterialService();
        $result = $material->getMaterialById($handoutId,'','');

        if(isset($result->contentType)){
            $kcidName = '';

        }else{
            if($result->contentType == 2){
                //章节
                if(isset($result->chapKids)){
                    $chapt = new ChapterInfoModel();
                    $kcidName = $chapt->findChapter($result->chapKids);
                }
            }
            elseif($result->contentType == 1)
            {
                //知识点
                if(isset($result->chapKids)){
                    $chapt = new ChapterInfoModel();
                    $kcidName = $chapt->findChapter($result->chapKids);
                }
            }
        }
       return $this->render('handoutDetails',array('model'=>$result,'kcidName'=>$kcidName ));
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

    /**
     *添加阅读次数
     */
    public function actionGetReadNum()
    {
        $id = app()->request->getParam('id', 0);
        $readNum = new Apollo_MaterialService();
        $model = $readNum->increaseReadNum($id, '');
        $jsonResult = new JsonMessage();
        if ($model->resCode == BaseService::successCode) {
            $jsonResult->success = true;
            $jsonResult->data = $model->data->readNum;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = "添加失败！";
            return $this->renderJSON($jsonResult);
        }
    }

   /**
    * 家长会管理
    * 家长会列表
    */
    public function actionParentmeeting(){
        $classid = app()->request->getParam('classId','');
        $material = new pos_InternetParMeetingService();
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 10;
        $modelList = $material->searchIntParMeetingList(user()->id,$classid,'','','','',$pages->getPage() + 1, $pages->pageSize);
        $pages->totalCount = intval($modelList->data->countSize);
        if (!empty($classid))
        {$pages->params['classId']=$classid;}
        $dataBag = new CreateParMetForm();
        $dataBag2 = new ModifyParMetForm();
       return $this->render('parentmeeting',array('models'=>$dataBag2,'model'=>$dataBag,'modelList'=>$modelList->data->meetingList,'pages' => $pages));
    }

    //开设家长会
    public function actionCreateparentmeeting(){

        $jsonResult = new JsonMessage();
        $dataBag = new CreateParMetForm();
        if(isset($_POST['CreateParMetForm'])){
            $dataBag->attributes=$_POST['CreateParMetForm'];
            if  ($dataBag->validate())
            {
                $material = new pos_InternetParMeetingService();
                $modelList = $material->createIntParMeeting(user()->id,$dataBag->classid,$dataBag->meetingname,$dataBag->content,$dataBag->time1,$dataBag->time2);
                if ($modelList->resCode == BaseService::successCode) {
                    $jsonResult->success = true;
                } else {
                    $jsonResult->message = $modelList->resMsg;
                }
            }
        }
        return $this->renderJSON($jsonResult);
    }

    //修改家长会
    public function actionModifyIntParMeeting(){
        $jsonResult = new JsonMessage();
        $dataBag = new ModifyParMetForm();
        if(isset($_POST['ModifyParMetForm'])){
            $dataBag->attributes=$_POST['ModifyParMetForm'];
            if  ($dataBag->validate())
            {
                $material = new pos_InternetParMeetingService();
                $modelList = $material->modifyIntParMeeting($dataBag->meeid,$dataBag->classid,$dataBag->meetingname,$dataBag->content,$dataBag->time1,$dataBag->time2);
                if ($modelList->resCode == BaseService::successCode) {
                    $jsonResult->success = true;
                } else {
                    $jsonResult->message = $modelList->resMsg;
                }
            }
        }
        return $this->renderJSON($jsonResult);
    }

    //ajax查询会议详情
    public function actionSearchIntParMeetingByID(){
        $meeid = $_POST["meeid"];
        $announcement = new pos_InternetParMeetingService();
        $result = $announcement->searchIntParMeetingByID($meeid);
        $jsonResult = new JsonMessage();
        $jsonResult->data = $result;
        return $this->renderJSON($jsonResult);
    }

    //家长会回放/详情
    public function actionCourseback(){
        $meetingid = app()->request->getParam('meetingid', 0);
        $material = new pos_InternetParMeetingService();
        $modelList = $material->searchIntParMeetingByID($meetingid);
       return $this->render('courseback',array('modelList'=>$modelList->data));
    }

    /**
     * 点播课程 列表页
     *  wgl
     */
    public function actionDemand(){

        $userId = user()->id;
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 5;
        $material = new pos_CourseService();
        $result = $material->querydibbleCourse($userId, '','','','','','0','', $userId, $pages->getPage() + 1, $pages->pageSize);
        $pages->totalCount = intval($result->countSize);
		if(app()->request->isAjax){
			return $this->renderPartial('_demand_list', array(
				'model' => $result->courseList,
				'pages' => $pages
			));
			return;
		}
       return $this->render('demand',array('model' => $result->courseList, 'pages' => $pages));
    }

    /**
     * 点播课程详情页
     */
    public function actionDemandDetails()
    {
        $courseId = app()->request->getParam('courseID', 0);
        $material = new pos_CourseService();
        $result = $material->querydibbleCourseDetailInfo($courseId);
		if(empty($result)){
			return $this->notFound();
		}
        $kcidName ='';
        foreach ($result->courseHourList as $courseHourVal) {
           if($courseHourVal->type == 1){
               //章节
               $chapt = new ChapterInfoModel();
               $kcidName = $chapt->findChapter($courseHourVal->kcid);
           }elseif($courseHourVal->type == 0){
               //知识点
               $know = new KnowledgePointModel();
               $kcidName = $know->findKnowledge($courseHourVal->kcid);
           }
        }
       return $this->render('demandDetails',array('model'=>$result,'kcidName'=>$kcidName));
    }

//    //点播课程 -上传视频
//    //wgl 14-11-24
//    public function actionUploadDemandVideo(){
//        $schoolModel = user()->getSchoolId();
//        $hourList = array();
//        $dataBag = new NewDemandForm();
//        $dataBag->parseUserInfo(user()->getModel());
//
//        $teacherClassList = array();
//
//        if (!isset($_POST['TeacherUserForm'])) {
//            $teacherClassList = user()->getModel()->getUserClassGroup();
//
//        }
//
//        if (isset($_POST['NewDemandForm'])) {
//            if(isset($_POST['stuLimit'])){
//                return true;
//            }else{
//                $dataBag->stuLimit = 0;
//            }
//            if(isset($_POST['groupMemberLimit'])){
//                return true;
//            }else{
//                $dataBag->groupMemberLimit = 0;
//            }
//            if(isset($_POST['allMemLimit'])){
//                return true;
//            }else{
//                $dataBag->allMemLimit = 0;
//            }
//            $valid = true;
//            $dataBag->attributes = $_POST['NewDemandForm'];
//            $valid = $valid && $dataBag->validate();
//            $lessonInfo=null;
//
//            if (isset($_POST['TeacherClassForm'])) {
//                $teacherClassItems = $_POST['TeacherClassForm'];
//                foreach ($teacherClassItems as $i => $item) {
//                    $tClassForm = new TeacherClassForm();
//                    $tClassForm->attributes = $teacherClassItems[$i];
//                    $teacherClassList[$i] = $tClassForm;
//                    $valid = $valid && $tClassForm->validate();
//                }
//            }
//
//            if (isset($_POST['ClassHourForm'])) {
//                $classHourItems = $_POST['ClassHourForm'];
//                foreach ($classHourItems as $key => $item) {
//                    $tHourForm = new ClassHourForm();
//                    $tHourForm->attributes = $classHourItems[$key];
//                    $hourList[$key] = $tHourForm;
//                    $valid = $valid && $tHourForm->validate();
//                }
//                $arr=array_values($hourList);
//                $lessonInfo= json_encode(['data'=>$arr]);
//            }
//            if ($valid) {
//                $videoModel = new pos_CourseService();
//                $result = $videoModel->createdibbleCourse(
//                    $dataBag->type, $dataBag->gradeID, $dataBag->classID[0]['subjectNumber'], $dataBag->version, $dataBag->courseName, $dataBag->courseBrif, $dataBag->teacherID, $dataBag->classID[0]['classID'], $dataBag->stuLimit, $dataBag->groupMemberLimit, $dataBag->allMemLimit, 0, $dataBag->provience, $dataBag->city, $dataBag->country, $dataBag->creatorID, 0, 0, 0, 0, $dataBag->isShare, '', $lessonInfo
//                );
//                if ($result->resCode == pos_CourseService::successCode) {
//                    return $this->redirect(url('teacher/coursemanage/demand'));
//                }
//            }
//
//        }
//        if (empty($hourList)) {
//            $hourList[] = new ClassHourForm();
//        } else {
//            array_values($hourList);
//        }
//        if (empty($teacherClassList)) {
//            $teacherClassList[] = new TeacherClassForm();
//        } else {
//            array_values($teacherClassList);
//        }
//
//       return $this->render('uploadDemandVideo',array('model' => $dataBag, 'schoolModel' => $schoolModel, 'hourList'=>$hourList, 'teacherClassList' => $teacherClassList));
//    }

    /**
     * 获取学校所有教师
     *wgl：2014.11.25
     */
    public function actionGetTeacher()
    {

        $schoolId = app()->request->getParam('schoolId', 0);
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 5;
        $school = new pos_SchoolTeacherService();
        $model = $school->queryAllTeacherBySchoolID($schoolId, $pages->getPage() + 1, $pages->pageSize);
        $pages->totalCount = intval($model->countSize);
        $pages->params['schoolId'] = $schoolId;
        return $this->renderPartial('_getTeacher_view', array('model' => $model->teacherList, 'pages' => $pages));
    }



    //添加总结课程
	 public function actionAddCourse(){
		$classId = $_POST['classId'];
		$subjectID = $_POST['subjectID'];
		$summarizeName = $_POST['summarizeName'];
		$beginTime = $_POST['startTime'];
		$finishTime = $_POST['endTime'];
		$classAtmosphere = $_POST['fenwei'];
		$studyPlan = $_POST['studyPlan'];
		$knowledgepoint = $_POST['knowledgepoint'];
		$creatorID = $_POST['creatorId'];
		$pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 2;
		$obj = new pos_ClassSectionSummaryService();
		$obj_fetch = $obj->addSectionSummary($classId,$subjectID,$summarizeName,$beginTime,$finishTime,$classAtmosphere,$studyPlan,$knowledgepoint,$creatorID,$pages->getPage()+1, $pages->pageSize);
		$jsonResult = new JsonMessage();
		if ($obj_fetch->resCode === BaseService::successCode) {
			$jsonResult->success = true;
			return $this->renderJSON($jsonResult);
		} else {
			$jsonResult->success = false;
			$jsonResult->message="添加失败！";
			return $this->renderJSON($jsonResult);
		}
    }
    //修改总结课程
    public function actionEditCourse(){
    	$summarizeID = $_POST['summarizeID'];
    	$classId = $_POST['classId'];
    	$subjectID = $_POST['subjectID'];
    	$summarizeName = $_POST['summarizeName'];
    	$beginTime = $_POST['startTime'];
    	$finishTime = $_POST['endTime'];
    	$classAtmosphere = $_POST['fenwei'];
    	$studyPlan = $_POST['studyPlan'];
    	$knowledgepoint = $_POST['knowledgepoint'];

    	$obj = new pos_ClassSectionSummaryService();
    	$obj_fetch = $obj->modifySectionSummary($summarizeID,$classId,$subjectID,$summarizeName,$beginTime,$finishTime,$classAtmosphere,$studyPlan,$knowledgepoint);
   		$jsonResult = new JsonMessage();
			if ($obj_fetch->resCode === BaseService::successCode) {
				$jsonResult->success = true;
				return $this->renderJSON($jsonResult);
			} else {
				$jsonResult->success = false;
				$jsonResult->message="修改失败！";
				return $this->renderJSON($jsonResult);
			}
    }
    //查看详情
    public function actionShowCourseOne(){
    	$summarizeID = $_POST['summarizeID'];
    	$obj = new pos_ClassSectionSummaryService();
    	$obj_fetch = $obj->searchSectionSummaryByID($summarizeID);
    	
    }
}