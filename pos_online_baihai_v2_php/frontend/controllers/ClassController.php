<?php

namespace frontend\controllers;

use common\helper\DateTimeHelper;
use common\models\JsonMessage;
use common\models\pos\SeAnswerQuestion;
use common\models\pos\SeClassEvent;
use common\models\pos\SeClassEventPic;
use common\models\pos\SeClassMembers;
use common\models\pos\SeHomeworkAnswerDetailImage;
use common\models\pos\SeHomeworkAnswerImage;
use common\models\pos\SeHomeworkAnswerInfo;
use common\models\pos\SeHomeworkAnswerQuestionAll;
use common\models\pos\SeHomeworkQuestion;
use common\models\pos\SeHomeworkRel;
use common\models\pos\SeHomeworkTeacher;
use common\models\pos\SeQuestionResult;
use common\models\sanhai\ShTestquestion;
use common\models\sanhai\SrMaterial;
use common\services\JfManageService;
use frontend\components\BaseAuthController;
use frontend\components\helper\ImagePathHelper;
use frontend\components\WebDataCache;
use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-9-3
 * Time: 下午5:06
 * BaseAuthController 为登录权限
 * 本班的教师和学生可以修改
 */
class ClassController extends BaseAuthController
{

	public $layout = 'lay_new_class_v2';

	/**
	 *班级主页
	 */
	public function actionIndex($classId)
	{

		$proFirstime = microtime(true);
		$this->layout = '@app/views/layouts/lay_class_index_v2';
		$classModel = $this->getClassModel($classId);
		$pages = new Pagination();
		$pages->validatePage = false;

		$homeworkRelModel = new SeHomeworkRel();
		$answerModel = new SeAnswerQuestion();
		$classEventModel = new SeClassEvent();

		//查询班级首页 数字
//		$classModel = SeClass::find()->where(['classID' => $classId])->one();
		//查询作业 答疑 大事记
        $homeworkInfoRel = $homeworkRelModel->selectOneClassHomework($classId);
        $answerInfo = $answerModel->selectOneClassAnswer($classId);
        $classEventList = $classEventModel->selectClassEventList($classId);

        //$ClassEventQue = clone $seClassEventQuery;
        $classEventInfo = [];
        if(!empty($classEventList)){
            $classEventInfo = $classEventList[0];
        }

        //查询班级大事记
        //$classEventList = $ClassEventQue->limit(10)->all();
		//判断成员是否在该班级中
		$isInClass = loginUser()->getModel()->getInClassInfo($classId);
		\Yii::info('班级首页 ' . (microtime(true) - $proFirstime), 'service');
		return $this->render("newIndex", [
			'classModel' => $classModel,
			'homeworkInfoRel' => $homeworkInfoRel,
			'answerInfo' => $answerInfo,
			'classEventInfo' => $classEventInfo,
			'classEventList' => $classEventList,
			'isInClass' => $isInClass,
		]);
	}

	/**
	 * 班内答疑
	 * 答疑列表 wgl
	 * @param $classId
	 * @return string
	 */
	public function actionAnswerQuestions($classId)
	{
		$proFirstime = microtime();
		$this->getClassModel($classId);
		$pages = new Pagination();
		$pages->validatePage = false;
		$pages->pageSize = 10;
		$keyWord = app()->request->get('keyWord', '');//获取搜索答疑关键词，之前预留口
		$subjectID = app()->request->get('subjectID', '');//获取科目id搜索
		$solvedType = app()->request->get('solved_type', null);
		$answerQuery = SeAnswerQuestion::find()->active()->andWhere(['classID' => $classId]);
		$aqIdQuery = clone $answerQuery;
		//条件为空不搜索
		if (!empty($keyWord)) {
			$answerQuery->andWhere(['like', 'aqName', $keyWord]);
		}
		//条件为空不搜索
		if (!empty($subjectID)) {
			$answerQuery->andWhere(['subjectID' => $subjectID]);
		}
		//已解决
		if ($solvedType == 1) {
			$answerQuery->andWhere(['isSolved' => 1]);
		}
		//未解决
		if ($solvedType == 2) {
			$answerQuery->andWhere(['isSolved' => 0]);
		}

		//查询班级提问总数
		$answerCount = $answerQuery->count();

		$aqIdArr = $aqIdQuery->select('aqID')->asArray()->all();
		//查询回答总数
		$replyListQuery = SeQuestionResult::find()->where(['rel_aqID' => ArrayHelper::getColumn($aqIdArr, 'aqID')]);
		$replyCount = $replyListQuery->count();
		//查询被采纳数
		$replyAdoptCount = $replyListQuery->andWhere(['isUse' => 1])->count();

		//查询排名
		$answerSort = SeAnswerQuestion::findBySql("SELECT creatorID,creatorName,count(aqID) as answerCount from se_answerQuestion where classID=:classID group by creatorID order by count(*) desc limit 10", ['classID' => $classId])->asArray()->all();

		$pages->totalCount = $answerQuery->count();
		$answerList = $answerQuery->orderBy('createTime desc')->offset($pages->getOffset())->limit($pages->getLimit())->all();
		\Yii::info('班级答疑 ' . (microtime() - $proFirstime), 'service');
		if (app()->request->isAjax) {
			//公共页面传数据
			return $this->renderPartial('//publicView/answer/_new_answer_question_list', array('modelList' => $answerList, 'pages' => $pages, 'classId' => $classId));
		}

		return $this->render('answerquestions', array(
			'modelList' => $answerList,
			'answerSort' => $answerSort,
			'pages' => $pages,
			'classId' => $classId,
			'answerCount' => $answerCount,
			"replyCount" => $replyCount,
			"replyAdoptCount" => $replyAdoptCount
		));
	}

	/**
	 * 用于修改SeAnswerQuestion表中字段 isSolved
	 * @param $classId
	 */
	public function actionUpAnQuSolved($classId)
	{
		$isUse = SeQuestionResult::find()->where(['isUse' => 1, 'isDelete' => 0])->select('rel_aqID')->column();
		$upAnswer = SeAnswerQuestion::updateAll(['isSolved' => '1'], ["aqID" => $isUse]);
		var_dump($upAnswer);
	}

	public function actionAnswerStatistics($classId)
	{
		$this->getClassModel($classId);
		$answerQuery = SeAnswerQuestion::find()->active()->andWhere(['classID' => $classId]);

	}


	/**
	 * 班级新鲜事 换一换
	 * @param $classId
	 * @return string
	 */
	public function actionChangeNew($classId)
	{
		$pages = new Pagination();
		$pages->validatePage = false;
		//查询作业

		//查询答疑

		//0：顺序搜索；1：随机搜索

		$homeworkInfoRel = SeHomeworkRel::find()->where(['classID' => $classId])->active()->orderBy('rand()')->one();
		$answerInfo = SeAnswerQuestion::find()->active()->andWhere(['classID' => $classId])->orderBy('rand()')->one();
		$classEventInfo = SeClassEvent::find()->where(['classID' => $classId])->active()->orderBy("rand()")->one();
		//       判断成员是否在该班级中
		$isInClass = loginUser()->getModel()->getInClassInfo($classId);
		return $this->renderPartial('_class_index_something_new', [
			'homeworkInfoRel' => $homeworkInfoRel,
			'answerInfo' => $answerInfo,
			'classEventInfo' => $classEventInfo,
			'classId' => $classId,
			'isInClass' => $isInClass
		]);
	}

	/**
	 * 班级文件列表（新加）
	 */
	public function actionClassFile($classId)
	{
		$proFirstime = microtime();
		$this->getClassModel($classId);
		$pages = new Pagination();
		$pages->validatePage = false;
		$pages->pageSize = 10;

		$mattype = app()->request->getQueryParam('mattype', '');
		$gradeid = app()->request->getQueryParam('gradeid', '');
		$subjectid = app()->request->getQueryParam('subjectid', '');
		$fileName = app()->request->getQueryParam('fileName', '');
		$sortCondition = app()->request->getQueryParam('sortType', 'createTime');

		$materialQuery = SrMaterial::find()->where("id in (SELECT matId from schoolservice.se_shareMaterial  where classID=:classID and isDelete=0)", ['classID' => $classId]);

		if (!empty($mattype)) {
			$materialQuery->andWhere(['matType' => $mattype]);
		}
		if (!empty($gradeid)) {
			$materialQuery->andWhere(['gradeid' => $gradeid]);
		}
		if (!empty($subjectid)) {
			$materialQuery->andWhere(['subjectid' => $subjectid]);
		}
		if (!empty($fileName)) {
			$materialQuery->andWhere(['like', 'name', $fileName]);
		}
		if (!empty($sortCondition)) {
			$materialQuery->orderBy("$sortCondition desc");
		}


		$pages->totalCount = $materialQuery->count();
		$materialList = $materialQuery->offset($pages->getOffset())->limit($pages->getLimit())->all();

		$pages->params = ['mattype' => $mattype,
			'gradeid' => $gradeid,
			'subjectid' => $subjectid,
			'classId' => $classId,

		];
		\Yii::info('班级文件 ' . (microtime() - $proFirstime), 'service');
		if (app()->request->isAjax) {
			return $this->renderPartial('_classfile_list', array(
				'materialList' => $materialList,
				'classId' => $classId,
				'pages' => $pages
			));

		}
		return $this->render('classfile', array(
			'materialList' => $materialList,
			'classId' => $classId,
			'mattype' => $mattype,
			'gradeid' => $gradeid,
			'subjectid' => $subjectid,
			'pages' => $pages,
			'fileName' => $fileName
		));

	}

	/**
	 * 班级大事记
	 * @param $classId
	 * @return string
	 */
	public function actionMemorabilia($classId)
	{
		$proFirstime = microtime();
		$this->layout = '@app/views/layouts/lay_new_class_v2';
		$this->getClassModel($classId);
		$pages = new Pagination();
		$pages->validatePage = false;
		$pages->pageSize = 10;
		$eventModel = new SeClassEvent();
		$eventQuery = $eventModel::find()->where(['isDelete' => 0, 'classID' => $classId]);
		$eventResult = $eventQuery->orderBy('time desc')->limit($pages->getLimit())->offset($pages->getOffset())->all();
		$pages->totalCount = $eventQuery->count();
		\Yii::info('大事记 ' . (microtime() - $proFirstime), 'service');
		return $this->render("newMemorabilia", ['eventModel' => $eventModel, 'eventResult' => $eventResult, 'pages' => $pages, 'classID' => $classId]);
	}

	/**
	 * 大事记相册模式
	 * @param $classId
	 * @return string
	 */
	public function actionMemorabiliaAlbum($classId)
	{
		$this->layout = '@app/views/layouts/lay_new_class_v2';
		$this->getClassModel($classId);
		return $this->render('memorabiliaAlbum', ['classId' => $classId]);
	}

	/**
	 * 添加大事记
	 * @param $classId
	 * @return string
	 * @throws \yii\base\InvalidConfigException
	 */
	public function actionAddMemorabilia($classId)
	{
		$this->layout = '@app/views/layouts/lay_new_class_v2';
		$this->getClassModel($classId);
		$eventModel = new SeClassEvent();
		if ($_POST) {
			$eventData = app()->request->getBodyParams();
			$eventName = $eventData['SeClassEvent']['name'];

			$time = $eventData['SeClassEvent']['time'];

			$briefOfEvent = $eventData['SeClassEvent']['briefOfEvent'];

			$eventModel->eventName = $eventName;
			$eventModel->time = strtotime($time) * 1000;
			$eventModel->createTime = DateTimeHelper::timestampX1000();
			$eventModel->briefOfEvent = $briefOfEvent;
			$eventModel->creatorID = user()->id;
			$eventModel->classID = $classId;
			if ($eventModel->save()) {
				if (array_key_exists('image', $eventData['SeClassEvent'])) {
					$image = $eventData['SeClassEvent']['image'];
					foreach ($image as $v) {
						$eventPicModel = new SeClassEventPic();
						$eventPicModel->picUrl = $v;
						$eventPicModel->eventID = $eventModel->eventID;
						$eventPicModel->createTime = DateTimeHelper::timestampX1000();
						$eventPicModel->save();
					}
				}
				$this->redirect(url::to(['/class/memorabilia', 'classId' => $classId]));
			}
		}
		return $this->render('addMemorabilia', ['eventModel' => $eventModel, 'classId' => $classId]);
	}

	/**
	 * 修改大事记
	 * @param $classId
	 * @return string
	 * @throws \yii\base\InvalidConfigException
	 */
	public function actionModifyMemorabilia($classId)
	{
		$this->layout = '@app/views/layouts/lay_new_class_v2';
		$eventID = app()->request->getQueryParam('eventID');
		$this->getClassModel($classId);
		$eventModel = SeClassEvent::find()->where(['eventID' => $eventID])->one();
		if ($_POST) {
			$eventData = app()->request->getBodyParams();
			$eventName = $eventData['SeClassEvent']['name'];
			$time = $eventData['SeClassEvent']['time'];

			$briefOfEvent = $eventData['SeClassEvent']['briefOfEvent'];
			$eventModel->eventName = $eventName;
			$eventModel->time = strtotime($time) * 1000;
			$eventModel->createTime = DateTimeHelper::timestampX1000();
			$eventModel->briefOfEvent = $briefOfEvent;
			$eventModel->creatorID = user()->id;
			if ($eventModel->save()) {
				$eventPicModel = new SeClassEventPic();
				$eventPicModel->deleteAll(['eventID' => $eventID]);
				if (array_key_exists('image', $eventData['SeClassEvent'])) {
					$image = $eventData['SeClassEvent']['image'];
					foreach ($image as $v) {
						$updatePicModel = new  SeClassEventPic();
						$updatePicModel->picUrl = $v;
						$updatePicModel->eventID = $eventID;
						$updatePicModel->createTime = DateTimeHelper::timestampX1000();
						$updatePicModel->save();

					}
				}
				$this->redirect(url::to(['/class/memorabilia', 'classId' => $classId]));
			}
		}
		return $this->render('modifyMemorabilia', ['eventModel' => $eventModel, 'classId' => $classId]);
	}

	/**
	 * 删除大事记
	 * @return string
	 */
	public function actionDeleteEvent()
	{
		$jsonResult = new JsonMessage();
		$eventID = app()->request->getBodyParam('eventID');
		$result = SeClassEvent::updateAll(['isDelete' => 1], ['eventID' => $eventID]);
		if ($result) {
			$jsonResult->success = true;
		}
		return $this->renderJSON($jsonResult);
	}

	/**
	 * 大事记分页
	 * @return string
	 */
	public function actionGetEventPage()
	{

		$jsonResult = new JsonMessage();
		$pages = new Pagination();
		$pages->validatePage = false;
		$pages->pageSize = 10;
		$classID = app()->request->getQueryParam('classID');
		$eventQuery = SeClassEvent::find()->where(['isDelete' => 0, 'classID' => $classID]);
		$eventResult = $eventQuery->orderBy('time desc')->limit($pages->getLimit())->offset($pages->getOffset())->all();
		$pages->totalCount = $eventQuery->count();
		$dataArray = [];
// 判断是否是该班的班主任
		$isMaster = $this->MasterClassByClass($classID);
		$userID = user()->id;
		foreach ($eventResult as $v) {
			$time = DateTimeHelper::timestampDiv1000($v->time);
			if ($isMaster) {
				$power = true;
			} else {
				if ($userID == $v->creatorID) {
					$power = true;
				} else {
					$power = false;
				}
			}
			array_push($dataArray, ['year' => date('Y', $time), 'month' => date('m', $time), 'day' => date('d', $time), 'cont' => $v->eventName, 'eventID' => $v->eventID, 'power' => $power]);

		}
		$array = ['pageCount' => $pages->getPageCount(), 'currPage' => $pages->getPage() + 2, 'data' => $dataArray];
		$jsonResult->data = $array;

		return $this->renderJSON($jsonResult);
	}

	/**
	 * ajax大事记相册模式分页
	 * @return string
	 */
	public function actionGetAlbumList()
	{

		$jsonResult = new JsonMessage();
		$pages = new Pagination();
		$pages->validatePage = false;
		$pages->pageSize = 10;
		$classID = app()->request->getQueryParam('classID');
		$eventQuery = SeClassEvent::find()->where(['isDelete' => 0, 'classID' => $classID])->innerJoinWith('eventPic')->orderBy('time desc')->select('se_classEvent.eventID,time')->distinct();
		$pages->totalCount = $eventQuery->count();
		$eventResult = $eventQuery->limit($pages->getLimit())->offset($pages->getOffset())->all();
		$dataArray = [];
		foreach ($eventResult as $v) {
			$time = DateTimeHelper::timestampDiv1000($v->time);
			$date = date('Y-m-d', $time);
			if (!isset($dataArray[$date])) {
				$dataArray[$date] = ['year' => date('Y', $time), 'month' => date('m', $time), 'day' => date('d', $time), 'picList' => []];
			}
			$picResult = $v->eventPic;
			foreach ($picResult as $item) {
				array_push($dataArray[$date]['picList'], ['href' => $item->picUrl, 'small_href' => ImagePathHelper::imgThumbnail($item->picUrl, 180, 120)]);
			}
		}
		$picList = array_values($dataArray);


		$data = ['pageCount' => $pages->getPageCount(), 'currPage' => $pages->getPage() + 2, 'list' => $picList];
		$jsonResult->data = $data;

		return $this->renderJSON($jsonResult);
	}

	/**
	 * 大事记详情
	 * @return string
	 */
	public function actionGetEventDetails()
	{
		$eventID = app()->request->getBodyParam('eventID');
		$eventDetail = SeClassEvent::find()->where(['eventID' => $eventID])->one();
		return $this->renderPartial('_event_details', ['eventDetail' => $eventDetail]);
	}

	/**
	 *查询详细事件
	 */
	public function actionSelectByEvent()
	{

		$eventId = app()->request->post('id');

		$checkModel = SeClassEvent::find()->where(['eventID' => $eventId])->active()->one();

		return $this->renderPartial('_save_event', array('model' => $checkModel, 'eventId' => $eventId));


	}

	public function actionSelectById()
	{
		$eventId = app()->request->post('id');

		$checkModel = SeClassEvent::find()->where(['eventID' => $eventId])->active()->one();
		return $this->renderPartial('_one_event_view', array('eventModel' => $checkModel, 'eventId' => $eventId));
	}


	/**
	 *  班级成员管理
	 * @param $classId
	 * @return string
	 */
	public function actionMemberManage($classId)
	{
		$proFirstime = microtime();
		$this->getClassModel($classId);
		$classMembersModel = new SeClassMembers();

		//查询班主任
		$master = $classMembersModel->selectClassAdviser($classId);
		//查询教师列表
		$teacherList =  $classMembersModel->selectClassTeacherList($classId);
		//查询学生列表
		$studentList = $classMembersModel->selectClassStudentList($classId);

		\Yii::info('班级成员 ' . (microtime() - $proFirstime), 'service');
		return $this->render('MemberManage', ['master' => $master, 'teacherList' => $teacherList, 'studentList' => $studentList]);

	}

	/**
	 * 班级教师作业列表
	 * wgl
	 * @param $classId
	 * @return string|\yii\web\Response
	 */
	public function  actionHomework($classId)
	{
		$proFirstime = microtime();
		if (loginUser()->isStudent()) {
			return $this->redirect(Url::to(['student-homework', 'classId' => $classId]));

		}
		$classId = intval($classId);
		$this->getClassModel($classId);
		$pages = new Pagination();
		$pages->validatePage = false;
		$pages->pageSize = 10;

		$subject = app()->request->get('subjectId', null);
		$getType = app()->request->getParam('getType', '');

		$query = SeHomeworkRel::find()->active()->where(['classID' => $classId]);
		//查询作业类型 纸质 or 电子
		if ($getType != '') {
			$query->andWhere('homeworkId in (select id from se_homework_teacher where getType=:getType ) ', [':getType' => $getType]);
		}
		//查询科目
		if ($subject != '') {
			$query->andWhere('homeworkId in (select id from se_homework_teacher where subjectId=:subjectId ) ', [':subjectId' => $subject]);
		}
		$homework = $query->orderBy("createTime desc")->offset($pages->getOffset())->limit($pages->getLimit())->all();

		//查询班级学生数
		$studentMember = WebDataCache::getClassStudentMember($classId);

		$pages->totalCount = $query->count();
		\Yii::info('教师作业列表 ' . (microtime() - $proFirstime), 'service');
		if (app()->request->isAjax) {
			return $this->renderPartial('_homework_list_tch', ['homework' => $homework, 'studentMember' => $studentMember, 'classId' => $classId, 'pages' => $pages]);
		}

		return $this->render('classTchWorkList', ['homework' => $homework, 'studentMember' => $studentMember, 'classId' => $classId, 'pages' => $pages]);
	}

	/**
	 * 新 教师 作业作答情况
	 * @param $classId
	 * @return string
	 * @throws \yii\web\HttpException
	 */
	public function actionWorkDetail($classId)
	{
		$this->getClassModel($classId);
		$classHomeworkId = app()->request->get("classhworkid");
		$type = app()->request->get("type");
		$checkTime = app()->request->get('checkTime');
		$pages = new Pagination();
		$pages->validatePage = false;
		$pages->pageSize = 10;
		//查询rel关系表
		$result = SeHomeworkRel::find()->where(['id' => $classHomeworkId])->one();
		//查询是否存在这条记录
		if (empty($result)) {
			return $this->notFound("该作业已被删除！");
		}
		//截止时间
		$deadlineTime = strtotime(date('Y-m-d 23:59:59', DateTimeHelper::timestampDiv1000($result->deadlineTime))) * 1000;
		//查询详情信息
		$homeWorkTeacher = $result->getHomeWorkTeacher()->one();
		$query = SeHomeworkAnswerInfo::find()->where(['relId' => $classHomeworkId, 'isUploadAnswer' => '1'])->orderBy("uploadTime desc");
		$cloneQuery = clone $query;

		//未批改选项卡
		if ($type == '1' || $type == null) {
			//调用拆分出去的 未交批改的内容页
			return $this->actionNoCorrections($result,$classHomeworkId,$deadlineTime,$query,$checkTime,$homeWorkTeacher,$pages,$classId);
		}
		elseif ($type == '2')
		{
			//调用拆分出去的 未交批改的内容页
			return $this->actionNoSubmitJob($result, $homeWorkTeacher, $classId, $classHomeworkId);
		}
		elseif ($type == '3')
		{
			return $this->actionAlreadyCorrections($result,$classHomeworkId,$deadlineTime,$classId,$cloneQuery,$pages,$checkTime,$homeWorkTeacher);
		}
	}

	/**
	 * 作业作答情况 未批改选项卡内容
	 * @param $result
	 * @param $classHomeworkId
	 * @param $deadlineTime
	 * @param $query
	 * @param $checkTime
	 * @param $homeWorkTeacher
	 * @param $pages
	 * @param $classId
	 * @return string
	 */

	private function actionNoCorrections($result, $classHomeworkId, $deadlineTime, $query, $checkTime, $homeWorkTeacher, $pages, $classId){
		//未批改的作业数
		/** @var \common\models\pos\SeHomeworkRel $result */
		$noCorrections = $result->getHomeworkAnswerInfo()->where(['isCheck' => [0,2], 'isUploadAnswer' => '1'])->count();

		//教师作业 补充内容的语音
		$homeworkRelAudio = $result->audioUrl;

		//查询未批改的
		$queryCount = SeHomeworkAnswerInfo::find()->where(['relId' => $classHomeworkId, 'isUploadAnswer' => '1', 'isCheck' => [0,2]]);
		$cloneQueryCount = clone $queryCount;
		//查询按时提交数
		$onTimeNumber = $queryCount->andWhere(['<', 'uploadTime', $deadlineTime])->count();
		//超时提交
		$overtime = $cloneQueryCount->andWhere(['>', 'uploadTime', $deadlineTime])->count();
		//查询答题信息和全部提交

		if (!app()->request->isAjax) {
			//未批改
			$answer = $query->andWhere(['isCheck' => [0,2], 'isUploadAnswer' => '1'])->offset($pages->getOffset())->limit($pages->getLimit())->all();
			//查询答案相关信息
			if (!empty($query)) {
				$pages->totalCount = $query->count();
			}
			$pages->params = ['type' => '1', 'classId' => $classId, 'classhworkid' => $classHomeworkId];
		}
		if (app()->request->isAjax) {
			//未批改
			$unAnsweredQuery = $query->andWhere(['isCheck' => [0,2]]);
			if ($checkTime == 2) {
				$unAnsweredQuery->andWhere(['<', 'uploadTime', $deadlineTime]);
			}
			if ($checkTime == 3) {
				$unAnsweredQuery->andWhere(['>', 'uploadTime', $deadlineTime]);
			}
			$answer = $unAnsweredQuery->offset($pages->getOffset())->limit($pages->getLimit())->all();
			if (!empty($answer)) {
				$pages->totalCount = intval($unAnsweredQuery->count());
			}
			$pages->params = ['type' => '1', 'classhworkid' => $classHomeworkId, 'classId' => $classId];
			return $this->renderPartial('_tch_work_details_no_corrections_new',
					array(
							'answer' => $answer,
							'page' => $pages,
							'homeworkDetailsTeacher' => $homeWorkTeacher,
							"classId" => $classId
					));
		}
		return $this->render("classTchWorkDetails_new", [
				'noCorrections' => $noCorrections,
				'onTimeNumber' => $onTimeNumber,
				'overtime' => $overtime,
				'answer' => $answer,
				'homeworkDetailsTeacher' => $homeWorkTeacher,
				'page' => $pages,
				"classId" => $classId,
				'classhworkId' => $classHomeworkId,
			'homeworkRelAudio'=>$homeworkRelAudio
		]);

	}

	/**
	 * 作业作答情况 未提交学生列表的 选项卡内容
	 * @param $result
	 * @param $homeWorkTeacher
	 * @param $classId
	 * @param $classHomeworkId
	 * @return string
	 */

	private function actionNoSubmitJob($result, $homeWorkTeacher, $classId, $classHomeworkId)
	{
		/** @var \common\models\pos\SeHomeworkRel $result */
		//教师作业 补充内容的语音
		$homeworkRelAudio = $result->audioUrl;
		//查询已答学生和 数
		$answerNumber = $result->getHomeworkAnswerInfo()->andWhere(['isUploadAnswer' => '1'])->count();
		$answerStuList = $result->getHomeworkAnswerInfo()->andWhere(['isUploadAnswer' => '1'])->all();
//查询班级学生数
		$studentClassQuery = SeClassMembers::find()->where(['classID' => $result->classID, 'identity' => '20403'])->andWhere(['>', 'userID', 0]);
		$studentMemberClone = clone $studentClassQuery;
		$studentList = $studentClassQuery->all();
		$studentMember = $studentMemberClone->count();
		//未答数
		$noStudentMember = $studentMember - $answerNumber;

		return $this->render("classTchWorkDetailsNoSubmitJob", ['homeworkDetailsTeacher' => $homeWorkTeacher, 'answerStuList' => $answerStuList, 'studentList' => $studentList, 'noStudentMember' => $noStudentMember, "classId" => $classId, 'classhworkId' => $classHomeworkId,'homeworkRelAudio'=>$homeworkRelAudio]);
	}

	/**
	 * 作业作答情况 已批改选项卡 内容
	 * @param $result
	 * @param $classHomeworkId
	 * @param $deadlineTime
	 * @param $classId
	 * @param $cloneQuery
	 * @param $pages
	 * @param $checkTime
	 * @param $homeWorkTeacher
	 * @return string
	 */
	private function actionAlreadyCorrections($result,$classHomeworkId,$deadlineTime,$classId,$cloneQuery,$pages,$checkTime,$homeWorkTeacher)
	{
		/** @var \common\models\pos\SeHomeworkRel $result */
		//教师作业 补充内容的语音
		$homeworkRelAudio = $result->audioUrl;
		//查询批改数
		$isCorrections = $result->getHomeworkAnswerInfo()->where(['isCheck' => '1', 'isUploadAnswer' => '1'])->count();
		//查询已批改的
		$markedQueryCount = SeHomeworkAnswerInfo::find()->where(['relId' => $classHomeworkId, 'isCheck' => '1', 'isUploadAnswer' => '1']);
		$cloneMarkedQueryCount = clone $markedQueryCount;
		//查询按时提交数
		$markedOnTimeNumber = $markedQueryCount->andWhere(['<', 'uploadTime', $deadlineTime])->count();
		//超时提交
		$markedOvertime = $cloneMarkedQueryCount->andWhere(['>', 'uploadTime', $deadlineTime])->count();

		if (!app()->request->isAjax) {
			//已批改
			$pagesCorrected = new Pagination();
			$pagesCorrected->pageSize = 10;
			$pagesCorrected->params = ['type' => '3', 'classId' => $classId, 'classhworkid' => $classHomeworkId];
			$answerCorrected = $cloneQuery->andWhere(['isCheck' => '1'])->offset($pages->getOffset())->limit($pages->getLimit())->all();
			if (!empty($answerCorrected)) {
				$pagesCorrected->totalCount = $cloneQuery->count();
			}
		}
		if (app()->request->isAjax) {
//                已批改
			$pagesCorrected = new Pagination();
			$pagesCorrected->validatePage = false;
			$pagesCorrected->pageSize = 10;
			$answeredQuery = $cloneQuery->andWhere(['isCheck' => 1]);
			if ($checkTime == 2) {
				$answeredQuery->andWhere(['<', 'uploadTime', $deadlineTime]);
			}
			if ($checkTime == 3) {
				$answeredQuery->andWhere(['>', 'uploadTime', $deadlineTime]);
			}
			$answerCorrected = $answeredQuery->offset($pagesCorrected->getOffset())->limit($pagesCorrected->getLimit())->all();
			if (!empty($answerCorrected)) {
				$pagesCorrected->totalCount = intval($answeredQuery->count());
			}
			$pagesCorrected->params = ['type' => 3, 'classhworkid' => $classHomeworkId, 'checkTime' => $checkTime, 'classId' => $classId];
			return $this->renderPartial('_tch_work_details_already_corrections_new', array('answerCorrected' => $answerCorrected, 'pagesCorrected' => $pagesCorrected, 'homeworkDetailsTeacher' => $homeWorkTeacher, "classId" => $classId));
		}
		return $this->render("classTchWorkDetailsAlreadyCorrections",
				[
						'markedOvertime' => $markedOvertime,
						'isCorrections' => $isCorrections,
						'markedOnTimeNumber' => $markedOnTimeNumber,
						'answerCorrected' => $answerCorrected,
						'homeworkDetailsTeacher' => $homeWorkTeacher,
						'pagesCorrected' => $pagesCorrected,
						"classId" => $classId,
						'classhworkId' => $classHomeworkId,
						'homeworkRelAudio'=>$homeworkRelAudio
				]);
	}

	/**
	 * 学生作业列表
	 * @param $classId
	 * @return string
	 */
	public function actionStudentHomework($classId)
	{
		$proFirstime = microtime();
		$this->getClassModel($classId);
		$pages = new Pagination();
		$pages->validatePage = false;
		$pages->pageSize = 10;
		$userId = user()->id;
		$subject = app()->request->get('subjectId', null);
		$getType = app()->request->getParam('getType', '');
		$state = app()->request->getParam('state', '');
		$query = SeHomeworkRel::find()->active()->where(['classID' => $classId]);
		//查询作业类型 纸质 or 电子
		if ($getType != '') {
			$query->andWhere('homeworkId in (select id from se_homework_teacher where getType=:getType ) ', [':getType' => $getType]);
		}

		//查询科目
		if ($subject != '') {
			$query->andWhere('homeworkId in (select id from se_homework_teacher where subjectId=:subjectId ) ', [':subjectId' => $subject]);
		}

		//已完成的
		if ($state == 2) {
			$query->andWhere('id in (select relId from se_homeworkAnswerInfo where isUploadAnswer=1 and studentID=:userId)', [':userId' => $userId]);
		}
		//未完成的
		if ($state == 3) {
			$query->andWhere('id not in (select relId from se_homeworkAnswerInfo where studentID=:userId)', [':userId' => $userId]);
		}
		$homework = $query->orderBy("createTime desc")->offset($pages->getOffset())->limit($pages->getLimit())->all();
		//查询班级学生数
		$studentMember = WebDataCache::getClassStudentMember($classId);

		$pages->totalCount = $query->count();

		\Yii::info('学生作业列表 ' . (microtime() - $proFirstime), 'service');
		if (app()->request->isAjax) {
			return $this->renderPartial('_homework_list_stu', ['homework' => $homework, 'studentMember' => $studentMember, 'classId' => $classId, 'pages' => $pages, "state" => $state]);
		}
		return $this->render("classStuWorkList", ['homework' => $homework, 'studentMember' => $studentMember, 'classId' => $classId, 'pages' => $pages, "state" => $state]);
	}

	/**
	 * 纸质作业详情
	 */
	public function actionUpDetails($classId)
	{
		$this->getClassModel($classId);
		$homeworkId = app()->request->getParam("homeworkId", '');

		$homeworkRel = SeHomeworkRel::find()->where(['classID' => $classId, 'homeworkId' => $homeworkId])->select('id,audioUrl')->one();
		if (empty($homeworkRel)) {
			return $this->notFound('', '403');
		}
		//教师作业 补充内容的语音
		$homeworkRelAudio = $homeworkRel->audioUrl;

		$homeworkData = SeHomeworkTeacher::find()->where(['id' => $homeworkId])->one();

		$imageList = $homeworkData->getHomeworkImages()->all();

		return $this->render('updetails', ['homeworkData' => $homeworkData, 'imageList' => $imageList,'homeworkRelAudio'=>$homeworkRelAudio]);

	}

	/**
	 * 电子作业详情
	 */
	public function actionOrganizeDetails($classId)
	{
		$this->getClassModel($classId);
		$homeworkId = app()->request->getParam("homeworkId", '');
		$homeworkRel = SeHomeworkRel::find()->where(['classID' => $classId, 'homeworkId' => $homeworkId])->select('id,audioUrl')->one();
		if (empty($homeworkRel)) {
			return $this->notFound('', '403');
		}
		//教师作业 补充内容的语音
		$homeworkRelAudio = $homeworkRel->audioUrl;
		$homeworkData = SeHomeworkTeacher::find()->where(['id' => $homeworkId])->one();
		//根据homeworkID查询questionid
		$questionList = $homeworkData->getHomeworkQuestion()->select('questionId')->asArray()->all();
		//  查询题目的具体内容
		$homeworkResult = [];
		foreach ($questionList as $v) {
			$oneHomework = ShTestquestion::find()->where(['id' => $v['questionId']])->orderBy('id')->one();
			array_push($homeworkResult, $oneHomework);
		}
		return $this->render('organizedetails', ['homeworkData' => $homeworkData, "homeworkResult" => $homeworkResult,'homeworkRelAudio'=>$homeworkRelAudio]);
	}


	/**
	 * 纸质作业的批改
	 * @param $classId
	 * @return string
	 */
	public function actionCorrectPicHom($classId)
	{
		$this->getClassModel($classId);
		$homeworkAnswerID = app()->request->getQueryParam('homeworkAnswerID');
		$oneAnswerResult = SeHomeworkAnswerInfo::find()->where(['homeworkAnswerID' => $homeworkAnswerID])->one();
//        根据relId查询当前作业所有提交了答案的学生
		$homeworkAnswerResult = SeHomeworkAnswerInfo::find()->where(['relId' => $oneAnswerResult->relId])->all();
//        查询当前学生提交的图片
		$imageResult = SeHomeworkAnswerDetailImage::find()->where(['homeworkAnswerID' => $homeworkAnswerID])->select('imageUrl')->asArray()->all();
		$imageArray = ArrayHelper::getColumn($imageResult, 'imageUrl');
		return $this->render('correctPicHom', [
			'oneAnswerResult' => $oneAnswerResult,
			'homeworkAnswerID' => $homeworkAnswerID,
			'homeworkAnswerResult' => $homeworkAnswerResult,
			'imageArray' => $imageArray,
			'classId' => $classId
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
       if($answerQuery->isCheck==0) {
           $answerQuery->isCheck = '1';
           //                    批改作业增加积分
           $jfHelper=new JfManageService;
           $jfHelper->myAccount("pos-correctWork",user()->id);
       }
        $answerQuery->correctLevel = $correctLevel;
        $answerQuery->checkTime = DateTimeHelper::timestampX1000();
        if ($answerQuery->save(false)) {

            $jsonResult->success = true;
        }
		return $this->renderJson($jsonResult);

	}

	/**
	 * 批改电子作业
	 * @return string
	 */
	public function actionCorrectOrgHom($classId)
	{
		$this->getClassModel($classId);
		$homeworkAnswerID = app()->request->getQueryParam('homeworkAnswerID');
		$oneAnswerResult = SeHomeworkAnswerInfo::find()->where(['homeworkAnswerID' => $homeworkAnswerID])->one();
//        根据relId查询当前作业所有提交了答案的学生
		$homeworkAnswerResult = SeHomeworkAnswerInfo::find()->where(['relId' => $oneAnswerResult->relId,'isUploadAnswer'=>1])->all();

//        查询当前作业当前学生提交的图片
		$answerImageResult = SeHomeworkAnswerImage::find()->where(['homeworkAnswerID' => $homeworkAnswerID])->select('url')->asArray()->all();
		$answerImageArray = ArrayHelper::getColumn($answerImageResult, 'url');

		//        根据relId查询homeworkId
		$homeworkID = SeHomeworkRel::find()->where(['id' => $oneAnswerResult->relId])->one()->homeworkId;
//        根据homeworkID查询题目
		$homeworkResult = SeHomeworkTeacher::find()->where(['id' => $homeworkID])->one();
		$questionArray = $this->findAll($homeworkID);

		return $this->render('correctOrgHom', [
			'homeworkAnswerResult' => $homeworkAnswerResult,
			'questionArray' => $questionArray,
			'homeworkID' => $homeworkID,
			'oneAnswerResult' => $oneAnswerResult,
			'answerImageArray' => $answerImageArray,
			'homeworkAnswerID' => $homeworkAnswerID,
			'homeworkResult' => $homeworkResult,
			'classId' => $classId
		]);
	}

	/**
	 * ajax电子批改作业
	 * @return string
	 */
	public function actionAjaxOrgCorrect()
	{
		$jsonResult = new JsonMessage();
		$questionID = app()->request->getBodyParam('questionID');
		$homeworkAnswerID = app()->request->getBodyParam('homeworkAnswerID');
		$correctResult = app()->request->getBodyParam('correctResult');
		$answerQuestionAllQuery = SeHomeworkAnswerQuestionAll::find()->where(['questionID' => $questionID, 'homeworkAnswerID' => $homeworkAnswerID])->one();
		$answerQuestionAllQuery->correctResult = $correctResult;
		if ($answerQuestionAllQuery->save(false)) {
			$answerQuestionAllQuery->updateMain();
			$jsonResult->success = true;
		}
		return $this->renderJSON($jsonResult);
	}

	/**
	 *批改作业更新主表的批改状态
	 */
	public function actionUpdateHomCorrectLevel()
	{
		$jsonResult = new JsonMessage();
		$homeworkAnswerID = app()->request->getBodyParam('homeworkAnswerID');
//                修改答案主表的isCheck
        $answerQuery = SeHomeworkAnswerInfo::find()->where(['homeworkAnswerID' => $homeworkAnswerID])->one();
     	$answerQuery->updateInfoStatus();
		if ($answerQuery->save(false)) {
			$jsonResult->success = true;
		}
		return $this->renderJSON($jsonResult);

	}

	/**
	 * 客观题，主观题分组
	 * @param $questionResult
	 * @return array
	 */
	public function findAll($homeworkID)
	{
		$questionResult = SeHomeworkQuestion::find()->where(['homeworkId' => $homeworkID])->select('questionId')->orderBy('orderNumber')->asArray()->all();
		$questionArray = array();
		foreach ($questionResult as $v) {
			$partQuestionQuery = ShTestquestion::find()->where(['mainQusId' => $v['questionId']]);
//            判断当前大题是否有小题

			if ($partQuestionQuery->exists()) {
				$partQuestionResult = $partQuestionQuery->select('id')->all();
				foreach ($partQuestionResult as $value) {
					$questionID = $value->id;
					$shTestquestion = ShTestquestion::find()->where(['id' => $questionID])->select('tqtid')->one();
					if(!empty($shTestquestion)){
						if ($shTestquestion->isMajorQuestionCache()) {
							array_push($questionArray, $questionID);
						}
					}
				}
			} else {
				$questionID = $v['questionId'];
				$shTestquestion = ShTestquestion::find()->where(['id' => $questionID])->select('tqtid')->one();
				if(!empty($shTestquestion)) {
					if ($shTestquestion->isMajorQuestionCache()) {
						array_push($questionArray, $questionID);
					}
				}
			}
		}
		return $questionArray;
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


}