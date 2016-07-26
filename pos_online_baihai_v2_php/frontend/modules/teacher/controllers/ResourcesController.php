<?php
namespace frontend\modules\teacher\controllers;

use common\models\JsonMessage;
use common\models\pos\SeClassMembers;
use common\models\pos\SeHomeworkRel;
use common\models\pos\SeHomeworkTeacher;
use common\services\JfManageService;
use frontend\components\TeacherBaseController;
use frontend\services\pos\pos_MessageSendByUserService;
use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2016/5/30
 * Time: 10:52
 */
class ResourcesController extends TeacherBaseController
{
	public $layout = "lay_user_new";


	/**
	 * 教师个人中心-我的资源-作业列表-我的收藏
	 * @return string
	 */
	public function actionCollectWorkManage()
	{
		$pages = new Pagination();
		$pages->validatePage = false;
		$pages->pageSize = 10;
		$userInfo = loginUser()->getModel();
		$department = app()->request->get('department', $userInfo->department);
		$subjectId = app()->request->get('subjectId', $userInfo->subjectID);

		$homeworkQuery = SeHomeworkTeacher::find()->where(["department" => $department, "subjectId" => $subjectId])->source_platform(user()->id);

		$homeworkList = $homeworkQuery->orderBy("createTime desc")->offset($pages->getOffset())->limit($pages->getLimit())->all();
		$pages->totalCount = $homeworkQuery->count();
		if (app()->request->isAjax) {
			return $this->renderPartial("_teacher_work_manage_list", ['homeworkList' => $homeworkList, 'pages' => $pages]);
		}
		return $this->render('teacherCollectWorkManage',
			[
				'homeworkList' => $homeworkList,
				'pages' => $pages,
				"department" => $department,
				"subjectId" => $subjectId
			]);
	}

	/**
	 * 教师个人中心-我的资源-作业列表-我的创建
	 * @return string
	 */
	public function actionMyCreateWorkManage()
	{
		$pages = new Pagination();
		$pages->validatePage = false;
		$pages->pageSize = 10;
		$userInfo = loginUser()->getModel();
		$department = app()->request->get('department', $userInfo->department);
		$subjectId = app()->request->get('subjectId', $userInfo->subjectID);
		$type = app()->request->get("type");
		$homeworkQuery = SeHomeworkTeacher::find()->where(["department" => $department, "subjectId" => $subjectId])->source_user(user()->id);
		//纸质和电子
		if (isset($type) && $type != null) {
			$homeworkQuery = $homeworkQuery->andWhere(['getType' => $type]);
		}

		$homeworkList = $homeworkQuery->orderBy("createTime desc")->offset($pages->getOffset())->limit($pages->getLimit())->all();
		$pages->totalCount = $homeworkQuery->count();

		if (app()->request->isAjax) {
			return $this->renderPartial("_teacher_work_manage_list", ['homeworkList' => $homeworkList, 'pages' => $pages]);
		}
		return $this->render("teacherMyCreateWorkManage",
			[
				"department" => $department,
				"subjectId" => $subjectId,
				'homeworkList' => $homeworkList,
				'pages' => $pages
			]);
	}

	/**
	 * 获取选择班级弹框
	 * @return string
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
		return $this->renderPartial('_getclassbox', ['homeworkRelList' => $homeworkRelList, 'unassignClass' => $unassignClass, 'homeworkTeaOne' => $homeworkTeaOne, 'getType' => $getType, 'hmwid' => $homeworkId]);
	}

	/**
	 * 获取选择班级弹框
	 * @return string
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
	 * @return string
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
					$classMembers = SeClassMembers::getClassNumByClassId($val['classID'], SeClassMembers::STUDENT);
					$homeworkRelModel->memberTotal = $classMembers;
					$homeworkRelModel->deadlineTime = strtotime($val['deadlineTime']) ? strtotime($val['deadlineTime']) * 1000 : strtotime("-2 day") * 1000;
					$homeworkRelModel->homeworkId = $homeworkId;
					$homeworkRelModel->createTime = time() * 1000;
					$homeworkRelModel->creator = user()->id;

					$code = $code && $homeworkRelModel->save(false);

					//发送消息
					$work = new  pos_MessageSendByUserService();
					$work->sendMessageByObjectId($homeworkRelModel->id, 507001, user()->id);
				}
			}

			if ($code) {
				$jfHelper = new JfManageService();
				$jfHelper->myAccount("pos-upl-orgWork", user()->id);
				$jsonResult->success = true;
				$jsonResult->message = "布置成功";
			} else {
				$jsonResult->message = "布置失败";
			}
		} else {
			$jsonResult->message = "该班级已经布置过作业";
		}
		return $this->renderJSON($jsonResult);
	}

	/**
	 * 删除布置后的班级作业
	 * @return string
	 */
	public function actionDeleteRel()
	{
		$jsonResult = new JsonMessage();
		$relId = app()->request->post("relId");


		$transaction = Yii::$app->db_school->beginTransaction();
		try {
			$homeworkRel = SeHomeworkRel::find()->where(["id" => $relId, "creator" => user()->id])->one();
			$homeworkAnswerInfo = $homeworkRel->getHomeworkAnswerInfo()->exists();
			if (empty($homeworkRel)) {
				$jsonResult->success = false;
				$jsonResult->message = "删除失败，您删除的作业信息有误，请检查！";
			} elseif (!empty($homeworkAnswerInfo)) {
				$jsonResult->success = false;
				$jsonResult->message = "该作业已有学生作答，不能再删除！";
			} else {
				$deleteHomeworkRel = $homeworkRel->delete();
				if ($deleteHomeworkRel == 1) {
					$jsonResult->success = true;
					$jsonResult->message = "删除成功！";
				} else {
					$jsonResult->success = false;
					$jsonResult->message = "删除失败！";
				}
			}
			$transaction->commit();

		} catch (Exception $e) {
			$transaction->rollBack();
		}
		return $this->renderJSON($jsonResult);

	}

	/**
	 * 用于
	 * 布置作业
	 * 和
	 * 删除已布置到班级的作业 后 刷新单条作业信息
	 * wgl
	 * @return bool|string
	 *
	 */
	public function actionOneWorkContent()
	{
		$id = app()->request->get("hmwid");
		$homeworkQuery = SeHomeworkTeacher::find()->where(['creator' => user()->id, 'id' => $id])->one();
		if (empty($homeworkQuery)) {
			return false;
		}
		return $this->renderPartial("_teacher_work_manage_list_content", ['val' => $homeworkQuery]);
	}
}
