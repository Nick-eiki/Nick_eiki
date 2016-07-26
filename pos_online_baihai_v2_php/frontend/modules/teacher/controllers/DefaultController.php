<?php
namespace frontend\modules\teacher\controllers;
use common\helper\DateTimeHelper;
use common\models\JsonMessage;
use common\models\pos\SeFavoriteFolder;
use common\models\sanhai\SrMaterial;
use frontend\components\BaseAuthController;
use frontend\models\AddinfoForm;
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\dicmodels\KnowledgePointModel;
use frontend\services\apollo\Apollo_MaterialService;
use frontend\services\apollo\Apollo_VideoLessonInfoService;
use frontend\services\BaseService;
use frontend\services\pos\pos_AnswerQuestionManagerService;
use frontend\services\pos\pos_ClassMembersService;
use frontend\services\pos\pos_CourseService;
use frontend\services\pos\pos_EduInformationService;
use frontend\services\pos\pos_FavoriteFolderService;
use frontend\services\pos\pos_HonorManageService;
use frontend\services\pos\pos_PersonalInformationService;
use frontend\services\pos\pos_TeacherMaterialService;
use frontend\services\pos\pos_TeachingGroupService;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;


/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-12-1
 * Time: 下午2:30
 */
class DefaultController extends BaseAuthController
{
    public $layout = 'lay_user_home';

	/**
	 * 教师首页 xin
	 * @param $teacherId
	 */
	public function actionIndex($teacherId=0)
	{
        $proFirstime = microtime();
		$this->isInto($teacherId);
		$pages = new Pagination();$pages->validatePage=false;
		$pages->pageSize = 10;

		$type = app()->request->getParam('type', '');
		if($type == 0 || $type== ''){
			$type = null;
		}
		//老师答疑个数
		$answer = new pos_AnswerQuestionManagerService();
		$answerResult = $answer->stasticUserQues($teacherId);

		//查询文件
		$teacherFile = SrMaterial::find()->where(['creator'=>$teacherId]);

		$teacherFile->andFilterWhere(['matType'=>$type]);

		if($teacherId == user()->id){
			$teacherFileList = $teacherFile->orderBy("createTime desc")->offset($pages->getOffset())->limit($pages->getLimit())->all();
		}elseif($teacherId != user()->id){
			$teacherFileList = $teacherFile->andWhere(['access'=> 1])->orderBy("createTime desc")->offset($pages->getOffset())->limit($pages->getLimit())->all();
		}
		$pages->totalCount = $teacherFile->count();

		$listType = 1;
        \Yii::info('教师空间 '.(microtime()-$proFirstime),'service');
		if (app()->request->isAjax) {
			return $this->renderPartial('_teacher_file_list', array('result' => $teacherFileList, 'teacherId'=>$teacherId, 'listType'=>$listType, 'pages' => $pages));

		}
		 return $this->render('index', array('result' => $teacherFileList, 'teacherId'=>$teacherId, 'listType'=>$listType, 'answerResult'=>$answerResult, 'pages' => $pages));
	}

	/**
	 * @param $teacherId
	 * @throws CException
	 * 教师主页 收藏列表
	 */
	public function actionCollectList($teacherId)
	{
		$this->isInto($teacherId);
		$pages = new Pagination();$pages->validatePage=false;
		$pages->pageSize = 10;
		$type = app()->request->getParam('type', '');

		if($type == 0 || $type == ''){
			$type = null;
		}
		//老师答疑个数
		$answer = new pos_AnswerQuestionManagerService();
		$answerResult = $answer->stasticUserQues($teacherId);

		//查询收藏数据
		$seFavoriteFolderList = SeFavoriteFolder::find()->where(['creatorID'=>$teacherId,'isDelete'=>0])->select('favoriteId')->all();
	    $arr = ArrayHelper::getColumn($seFavoriteFolderList,'favoriteId');

		$teacherQuery = SrMaterial::find()->where(['id'=>$arr]);

		$pages->totalCount = $teacherQuery->count();
		$result = $teacherQuery->andFilterWhere(['matType'=>$type])->offset($pages->getOffset())->limit($pages->getLimit())->all();
		$listType = 2;
		if (app()->request->isAjax) {
			return $this->renderPartial('_teacher_file_list', array('result' => $result, 'teacherId'=>$teacherId, 'listType'=>$listType, 'pages' => $pages));

		}
		return $this->render('indexCollectList', array('result' => $result, 'listType'=>$listType , 'teacherId'=>$teacherId, 'answerResult'=>$answerResult, 'pages' => $pages));
	}

	/**
	 * @throws CException
	 * 教师文档详情页
	 */
	public function actionDocumentDetails($teacherId){
		$this->isInto($teacherId);
		$id = app()->request->getParam('id');
		$model = new Apollo_MaterialService();
		//老师答疑个数
		$answer = new pos_AnswerQuestionManagerService();
		$answerResult = $answer->stasticUserQues($teacherId);
		//文檔详情
		$result = $model->queryMaterial($id, '', '', '', '', '', '', '', '', '', '', '', '', '', '', user()->id, '', '', '', '', '','', '' );
		 return $this->render('documentDetails', array('result' => $result->list, 'answerResult'=>$answerResult, 'teacherId'=>$teacherId ));
	}

	public function actionViewdoc($id){
		$model = new Apollo_MaterialService();
		$result = $model->getMaterialById($id,'','');
		return $this->render('viewdoc',['result'=>$result,'id'=>$id]);
	}

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

    /**
     *获取教研组成员
     */
    public function getGroupNumber($groupID)
    {
        $groupServer = new pos_TeachingGroupService();
        $groupResult = $groupServer->searchMemberFromGroup($groupID, "");
        return $groupResult->data;
    }

    /**
     *AJAX获取班级成员
     *
     */
    public function actionGetClassMember()
    {
	    $teacherId = app()->request->getParam('teacherId','');
        $classID = app()->request->getParam("classID",'');
        $classServer = new pos_ClassMembersService();
        $classResult = $classServer->loadRegisteredMembers($classID, '' , $teacherId);
        return $this->renderPartial("_class_member", array("classResult" => $classResult, 'classId'=>$classID,'teacherId'=>$teacherId));
    }

    /**
     *查询荣誉
     */
    public function actionSearchHonor()
    {
        $teacherId = app()->request->getParam('teacherId', '');
        $type = '50302';//50302为教师类型
        $honorManage = new pos_HonorManageService();
        $result = $honorManage->queryHonor($teacherId, '', $type);
        return $this->renderPartial('_search_honor_view', array('modelHonorList' => $result->honorList,));
    }

    /**
     *添加荣誉
     */
    public function actionAddHonor()
    {

        $honorInfor = app()->request->getParam('name', '');
        $userId = user()->id;
        $honorType = '50302';//50302为教师类型
        $honorManage = new pos_HonorManageService();
        $result = $honorManage->addHonor($honorInfor, $userId, $honorType);
        $jsonResult = new JsonMessage();
        if ($result->resCode === BaseService::successCode) {
            $jsonResult->data = $result->data->honorID;
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
        $honorInfor = app()->request->getParam('name', '');
        $honorID = app()->request->getParam('id', '');
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
        $honorID = app()->request->getParam('id', '');
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
     *收藏夹列表
     */
    public function actionCollection($teacherId)
    {
        $userId = user()->id;
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 5;
        $type = app()->request->getParam('type', '1,2,3');
        $pages->params = array('teacherId' => $teacherId, 'type' => $type);
        $teacher = new pos_FavoriteFolderService();
        if ($teacherId == $userId) {
            $model = $teacher->queryFavoriteFolder($teacherId, $type, $pages->getPage() + 1, $pages->pageSize, '');
        } else {
            $model = $teacher->otherQueryFavoriteFolder($teacherId, $type, $userId, $pages->getPage() + 1, $pages->pageSize);
        }
        $pages->totalCount = intval($model->countSize);
        if (app()->request->isAjax) {
            return $this->renderPartial('_site_view', array('model' => $model->list, 'pages' => $pages, 'teacherId' => $teacherId));

        }

        return $this->render('collection', array('model' => $model->list, 'pages' => $pages, 'teacherId' => $teacherId));
    }

    /**
     * 教案讲义详情
     * @param $id
     */
    public function actionDetail($id, $teacherId)
    {
        $detail = new Apollo_MaterialService();
        $userId = user()->id;
        $model = $detail->getMaterialById($id,'', $userId);
        if($model==null){
            return $this->notFound();
        }
            return $this->render('detail', array('model' => $model, 'teacherId' => $teacherId));
    }

    /**
     * 视频详情
     * @param $id
     */
    public function actionVideoDetail($id, $teacherId)
    {
        $video = new Apollo_VideoLessonInfoService();
        $model = $video->videoLessonSearch($id);
        if(empty($model->videoLessonList)){
            return $this->notFound();
        }
            return $this->render('videoDetail', array('model' => $model->videoLessonList[0], 'teacherId' => $teacherId));
    }


    /**
     * 点播课程详情页
     */
    public function actionDemandDetails($teacherId)
    {
        $courseId = app()->request->getParam('courseID', 0);
        $material = new pos_CourseService();
        $result = $material->querydibbleCourseDetailInfo($courseId);
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
        return $this->render('demandDetails',array('model'=>$result,'kcidName'=>$kcidName, 'teacherId' => $teacherId));
    }


    /**
     * 高：2014.10.15
     *资料袋列表
     */
    public function actionDataList($teacherId)
    {
        $userId = user()->id;
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 6;
        $type = 1;
        $material = new pos_TeacherMaterialService();
        if ($userId == $teacherId) {
            $materialList = $material->queryTeacherMaterial($type, '', '', '', $teacherId, '', '', '', $pages->getPage() + 1, $pages->pageSize, '');
        } else {
            $materialList = $material->otherQueryTeacherMaterial($teacherId, $type, '', '', '', $userId, $pages->getPage() + 1, $pages->pageSize);
        }
        $pages->totalCount = intval($materialList->countSize);
        return $this->render('dataList', array('material' => $materialList->list, 'pages' => $pages, 'teacherId' => $teacherId, 'userId' => $userId));
    }

    /**
     *获取资料袋
     */
    public function actionGetDataBag()
    {
        $id = app()->request->getParam('id', '');
        $getData = new pos_TeacherMaterialService();
        $model = $getData->getTeacherMaterial($id);

        return $this->renderPartial('_dataBag_view', array('id' => $id, 'model' => $model));

    }

    /**
     *添加资料袋
     */
    public function actionAddDataBag()
    {
        $type = 1;
        $name = app()->request->getParam('name', '');
        $departmentMemLimit = app()->request->getParam('departmentMemLimit', 0);
        $stuLimit = app()->request->getParam('stuLimit', 0);
        $groupMemberLimit = app()->request->getParam('groupMemberLimit', 0);
        $teacherId = user()->id;
        $material = new pos_TeacherMaterialService();
        $teacherMaterial = $material->createTeacherMaterial($name, $type, $teacherId, $stuLimit, $groupMemberLimit, $departmentMemLimit, '');
        $jsonResult = new JsonMessage();
        if ($teacherMaterial->resCode == BaseService::successCode) {
            $jsonResult->success = true;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = "添加失败！";
            return $this->renderJSON($jsonResult);
        }
    }


    /**
     *修改资料袋
     */
    public function actionEditDataBag()
    {
        $jsonResult = new JsonMessage();
        $teacherId = user()->id;
        $id = app()->request->getParam('id', '');
        $name = app()->request->getParam('name', '');
        $departmentMemLimit = app()->request->getParam('department', 0);
        $student = app()->request->getParam('student', 0);
        $group = app()->request->getParam('group', 0);
        $editData = new pos_TeacherMaterialService();
        $materialType = 1;
        $saveModel = $editData->updateTeacherMaterial($id, $teacherId, $name, $student, $group, $departmentMemLimit, $materialType, '');
        if ($saveModel->resCode == BaseService::successCode) {
            $jsonResult->success = true;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = '修改失败!';
            return $this->renderJSON($jsonResult);
        }
    }


    /**
     * 根据资料id获取列表
     * @param $id
     */
    public function actionDetailsList($id, $teacherId)
    {
        $userId = user()->id;
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 2;
        $type = app()->request->getParam('type', '');
        $pages->params = array('id' => $id, 'teacherId' => $teacherId, 'type' => $type);
        $typeName = 1;
        $getData = new pos_TeacherMaterialService();
        $model = $getData->queryTeacherMaterialDetail($id, "", "", $type, $pages->getPage() + 1, $pages->pageSize, "");
        $pages->totalCount = intval($model->countSize);
        if (app()->request->isAjax) {
            return $this->renderPartial('_folder_list_view', array('model' => $model->list, 'pages' => $pages, 'teacherId' => $teacherId, 'userId' => $userId));

        }

        if (!empty($model)) {
            return $this->render('detailsList', array('model' => $model->list, 'pages' => $pages, 'id' => $id, 'typeName' => $typeName, 'teacherId' => $teacherId, 'userId' => $userId));
        }
    }

    /**
     *添加收藏或取消收藏
     */
    public function actionAddMaterial()
    {
	    $jsonResult = new JsonMessage();

        $id = app()->request->getParam('id', 0);
        $userId = user()->id;
        $favoriteType = app()->request->getParam('type', '');
        $action = app()->request->getParam('action', '');
        if ($action == 1) {

	        $addFavor = new SeFavoriteFolder();
	        $addFavor->favoriteId = $id;
	        $addFavor->favoriteType = $favoriteType;
	        $addFavor->creatorID = $userId;
	        $addFavor->createTime = DateTimeHelper::timestampX1000();
	        if ($addFavor->save()) {
		        $jsonResult->success = true;
		        $jsonResult->message = "收藏成功！";
	        } else {
		        $jsonResult->success = false;
		        $jsonResult->message = '收藏失败！';
	        }
        } else {
	        $delFavorite = SeFavoriteFolder::deleteAll(['favoriteId'=>$id , 'creatorID'=>$userId, 'favoriteType'=>$favoriteType]);
	        if ($delFavorite == 1) {
		        $jsonResult->success = true;
		        $jsonResult->message = '取消收藏成功！';
	        } else {
		        $jsonResult->success = false;
		        $jsonResult->message = '取消收藏失败！';
	        }
        }
	    return $this->renderJSON($jsonResult);


    }
    /**
     * 添加收藏
     */
    public function actionMaterial()
    {
        $userId = user()->id;
        $id = app()->request->getParam('id', 0);
        $favoriteType = app()->request->getParam('type', '');
        $favorite = new pos_FavoriteFolderService();

        $model = $favorite->addFavoriteFolder($id, $favoriteType, $userId);
        $jsonResult = new JsonMessage();
        if ($model->resCode === BaseService::successCode) {
            $jsonResult->success = true;
            $jsonResult->message = '收藏成功！';
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = '收藏失败！';
            return $this->renderJSON($jsonResult);
        }
    }

    /**
     *删除收藏
     */
    public function actionDelMaterial()
    {
        $id = app()->request->getParam('id', 0);
	    $favoriteType = app()->request->getParam('type', '');
	    $userId = user()->id;

	    $delFavorite = SeFavoriteFolder::deleteAll(['favoriteId'=>$id , 'creatorID'=>$userId, 'favoriteType'=>$favoriteType]);

        $jsonResult = new JsonMessage();
        if ($delFavorite == 1) {
            $jsonResult->success = true;
	        $jsonResult->message = '取消收藏成功！';
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = '取消收藏失败！';
            return $this->renderJSON($jsonResult);
        }
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
     * 别人查看教师资料库的详细页
     * @param $id
     */
    public function actionDataDetail($id, $teacherId)
    {
        $detail = new Apollo_MaterialService();
        $userId = user()->id;
        $model = $detail->getMaterialById($id,'', $userId);
        if($model==null){
            return $this->notFound();
        }
            return $this->render('dataDetail', array('model' => $model, 'teacherId' => $teacherId));

    }

    /*
     * 教师个人主页答疑
     * 答疑列表
     */
    public function actionAnswerQuestions()
    {
        $user = app()->request->getParam('teacherId', '');
        $material = new pos_AnswerQuestionManagerService();
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 5;
        $content = app()->request->getParam('content', '');
        $modelList = $material->searchQuestionInfoList($user, $content, '', '', '', $pages->getPage() + 1, $pages->pageSize);
        $pages->totalCount = intval($modelList->data->countSize);

        if (app()->request->isAjax) {
            return $this->renderPartial('_answerquestions_list', array('modelList' => $modelList->data->qeustionList, 'pages' => $pages));

        }
        return $this->render('answerquestions', array('modelList' => $modelList->data->qeustionList, 'pages' => $pages));


    }

    //回答问
    public function actionResultQuestion()
    {

        $material = new pos_AnswerQuestionManagerService();
        $aqid = app()->request->getParam('aqid', 0);
        $answer = app()->request->getParam('answer', 0);
        $modelList = $material->resultQuestion($aqid, user()->id, $answer);
        $jsonResult = new JsonMessage();
        if ($modelList->resCode == BaseService::successCode) {
            $jsonResult->success = true;
        } else {
            $jsonResult->success = false;
            $jsonResult->message = "添加失败！";
        }
        return $this->renderJSON($jsonResult);
    }

    //同问问题
    public function actionSameQuestion()
    {
        $material = new pos_AnswerQuestionManagerService();
        $aqid = app()->request->getParam('aqid', 0);
        $samequestion = $material->SameQuestion($aqid, user()->id);
        $jsonResult = new JsonMessage();
        if ($samequestion->resCode == BaseService::successCode) {
            $jsonResult->success = true;
        } elseif ($samequestion->resCode == "100001") {
            $jsonResult->message = "您已同问过该问题!";
        } else {
            $jsonResult->success = false;
            $jsonResult->message = "添加失败！";
        }
        return $this->renderJSON($jsonResult);
    }

    //采用答案
    public function actionUseTheAnswer()
    {
        $material = new pos_AnswerQuestionManagerService();
        $resultid = app()->request->getParam('resultid', 0);
        $modelList = $material->UseTheAnswer($resultid);
        $jsonResult = new JsonMessage();
        if ($modelList->resCode == BaseService::successCode) {
            $jsonResult->success = true;
        } else {
            $jsonResult->message = $modelList->resMsg;
        }
        return $this->renderJSON($jsonResult);
    }


    /**
     * 更多视频
     */

    public function actionVideoList($teacherId)
    {
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 5;
        $userId = user()->id;
        $material = new pos_CourseService();
        $result = $material->querydibbleCourse($userId, '', '', '', '', '', 0,'', $teacherId, $pages->getPage() + 1, $pages->pageSize);
		$pages->totalCount = intval($result->countSize);
		if (app()->request->isAjax) {
			return $this->renderPartial('_video_list', array(
				'model' => $result->courseList,
				'teacherId'=>$teacherId,
				'pages' => $pages
			));

		}

        return $this->render('videoList', array('model' => $result->courseList, 'teacherId'=>$teacherId, 'pages' => $pages));
    }

	/**
	 * @param $teacherId
	 * @return \yii\web\Response
	 * @throws CHttpException
	 */
    public function isInto($teacherId)
    {
        $user = loginUser()->getUserInfo($teacherId);
        if ($user == null) {
            return $this->notFound();
        }
        if ($user->isStudent()) {
            return $this->redirect(url('student/default/index', ['studentId' => $teacherId]));
        }
        //判断当前用户是否有进入所访问页面的权限
        $canIn = new pos_PersonalInformationService();
        $res = $canIn->judgeUserCanIn(user()->id, $teacherId, 1);
        if ($res['isUserCanIn'] != 1) {
            return $this->notFound("你没有权限查看",403);
        }
    }
}