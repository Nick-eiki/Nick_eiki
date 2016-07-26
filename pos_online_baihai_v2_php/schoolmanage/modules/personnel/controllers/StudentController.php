<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2016/3/2
 * Time: 10:37
 */
namespace schoolmanage\modules\personnel\controllers;

use common\helper\DateTimeHelper;
use common\models\JsonMessage;
use common\models\pos\SeClassMembers;
use common\models\pos\SeUserinfo;
use common\services\ClassChangeService;
use frontend\components\WebDataCache;
use frontend\models\dicmodels\ClassListModel;
use frontend\services\apollo\Apollo_BaseInfoService;
use schoolmanage\components\helper\GradeHelper;
use schoolmanage\components\SchoolManageBaseAuthController;
use Yii;
use yii\data\Pagination;
use yii\db\Exception;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * UserController implements the CRUD actions for SeUserinfo model.
 */
class StudentController extends SchoolManageBaseAuthController
{

	public $layout = "lay_personnel_index";
	public $enableCsrfValidation = false;

	/**
	 * 人员管理 学生管理页面
	 * @return string
	 */
	public function actionIndex()
	{
		$schoolID = $this->schoolId;

		$department = app()->request->get("department");
		$gradeId = app()->request->get("gradeId");
		$classId = app()->request->get("classId");
		$searchWord = app()->request->get("searchWord");
		$pages = new Pagination();
		$pages->validatePage = false;
		$pages->pageSize = 10;

		$schoolData = $this->schoolModel;
		$departmentId = $schoolData->department; //学部id
		$departmentArray = explode(',', $departmentId);
		$lengthOfSchooling = $schoolData->lengthOfSchooling;

		//查询年级列表
		$gradeData=GradeHelper::getGradeByDepartmentAndLengthOfSchooling($department, $lengthOfSchooling);
		$gradeDataList =  ArrayHelper::map($gradeData, 'gradeId', 'gradeName');

		$db = SeUserinfo::getDb();
		$query = new Query();
		$query->select(["userInfo.userID", "userInfo.trueName", "userInfo.department", "userInfo.bindphone", "userInfo.sex", "classMem.classID", "classMem.userID", "classMem.stuID", "classInfo.gradeID"])
			->from("se_userinfo userInfo")
			->join("INNER JOIN", "se_classMembers classMem", "classMem.userID=userInfo.userID")
			->join("INNER JOIN", "se_class classInfo", "classMem.classID=classInfo.classID")
			->where("userInfo.schoolID=$schoolID AND userInfo.type=0");

		if (!empty($searchWord)) {
			$query->andWhere(' userInfo.trueName like :searchWord or userInfo.bindphone like :searchWord ', [":searchWord" => "%$searchWord%"]);
		}
		if (!empty($department)) {
			$query->andWhere(" userInfo.department=:department", [':department' => $department]);
		}
		if (!empty($gradeId)) {
			$query->andWhere(" classInfo.gradeID=:gradeId ", [":gradeId" => $gradeId]);
		}
		if (!empty($classId)) {
			$query->andWhere(" classMem.classID=:classId ", [":classId" => $classId]);
		}

		$pages->totalCount = $query->count('*', $db);

		$numberOfPeople = $query->count('*', $db);

		$query->offset($pages->getOffset())
			->limit($pages->getLimit());

		$userInfo = $query->createCommand($db)->queryAll();
		if (app()->request->isAjax) {
			return $this->renderPartial("_student_list",
				["userInfo" => $userInfo, "schoolId" => $schoolID, "pages" => $pages, "numberOfPeople" => $numberOfPeople]);
		}

		return $this->render("index", [
			"userInfo" => $userInfo,
			"schoolId" => $schoolID,
			"pages" => $pages,
			"departmentArray" => $departmentArray,
			"numberOfPeople" => $numberOfPeople,
			"gradeDataList"=>$gradeDataList
		]);
	}

	/**
	 * 学校无班级学生
	 * @return string
     */
	public function actionNoClassStudents(){

		$schoolID = $this->schoolId;
		$searchWord = app()->request->get("searchWord");
		$pages = new Pagination();
		$pages->validatePage = false;
		$pages->pageSize = 10;

		$db = SeUserinfo::getDb();
		$query = new Query();
		$query->select('f.userID,f.trueName,f.userID,f.trueName,f.department,f.bindphone,f.sex,f.stuID')
			  ->from("(SELECT u.userID,u.trueName,u.department,u.bindphone,u.sex,
	                       c.classID,c.userID AS classUserID,c.stuID
                       FROM se_userinfo u
                       LEFT JOIN se_classMembers c ON c.userID = u.userID
                       WHERE u.schoolID = :schoolID AND u.type = 0 AND u.userID NOT IN (
	                   SELECT userID FROM se_classMembers s JOIN `se_class` ON s.classID = se_class.classID
	                   WHERE  STATUS = 0 AND schoolID = :schoolID)) f");
		if (!empty($searchWord)) {
			$query->andWhere(' f.trueName like :searchWord or f.bindphone like :searchWord ', [":searchWord" => "%$searchWord%"]);
		}
		$query->addParams(['schoolID'=>$schoolID]);
		$pages->totalCount = $query->count('*', $db);
		$numberOfPeople = $query->count('*', $db);
		$query->offset($pages->getOffset())->limit($pages->getLimit());

		$userInfo = $query->createCommand($db)->queryAll();


		if (app()->request->isAjax) {
			return $this->renderPartial("_student_list",
				["userInfo" => $userInfo, "pages" => $pages, "numberOfPeople" => $numberOfPeople]);
		}

		return $this->render("noClassStudent", [
			"userInfo" => $userInfo,
			"pages" => $pages,
			"numberOfPeople" => $numberOfPeople
		]);
	}

	/**
	 * 查询学生的详情
	 * @return bool|string
	 */
	public function actionViewUserInfo()
	{
		$schoolId = $this->schoolId;

		$userId = app()->request->get("userId", null);
		if (empty($userId)) {
			return $this->notFound("用户不能为空！");
		}
		$classMembers = SeClassMembers::getClass($userId);
		if(empty($classMembers)){
			//查询无班级学生信息
			$studentInfo = SeUserinfo::find()->where(['userID' => $userId, 'schoolID' => $schoolId, 'isDelete' => 0])->asArray()->one();
			$studentInfo['classID'] = null;
			$studentInfo['stuID'] = null;
		}else{
			//查询有班级学生信息
			$studentInfo = SeUserinfo::findBySql("select userinfo.userID,userinfo.phoneReg,
         userinfo.trueName, userinfo.department,
         userinfo.bindphone, userinfo.sex,
        classMember.classID, classMember.userID,
        classMember.stuID from se_userinfo userinfo
     INNER JOIN se_classMembers classMember
     ON userinfo.userID=classMember.userID
     WHERE userinfo.schoolID=:schoolId AND userinfo.userID=:userId AND userinfo.type=0",
				[":userId" => $userId,":schoolId"=>$schoolId])->asArray()->one();
		}

		if (empty($studentInfo)) {
			return $this->notFound("未找到该用户");
		}

		//查询家长信息
		$parentsInfo = SeUserinfo::find()->where(["phoneReg" => $studentInfo["bindphone"], "type" => 3])->select('trueName,bindphone')->one();
		return $this->renderPartial("_view_student_info", ["studentInfo" => $studentInfo, "parentsInfo" => $parentsInfo, 'classMembers' => $classMembers]);
	}

	/**
	 * 用于重置密码页面
	 * @return bool|string
	 */
	public function actionAlertPassword()
	{
		$schoolId = $this->schoolId;
		$userId = app()->request->get("userId", null);
		if (empty($userId)) {
			return $this->notFound("用户不能为空！");
		}
		//查询该用户是否在该校
		$userInfo = SeUserinfo::find()->where(["schoolID" => $schoolId, "userID" => $userId, "type" => 0])->select("userID,trueName")->one();

		if (empty($userInfo)) {
			return $this->notFound("未找到该用户");
		}
		return $this->renderPartial("_reset_passwd_view", ["userInfo" => $userInfo]);
	}

	/**
	 * 重置密码
	 * @return string
	 */
	public function actionUpdatePassword()
	{
		$jsonResult = new JsonMessage();

		$schoolId = $this->schoolId;
		$userId = app()->request->get("userId", null);

		//查询该用户是否在该校
		$userInfo = SeUserinfo::find()->where(["schoolID" => $schoolId, "userID" => $userId])->select("userID")->one();

		if (empty($userId)) {
			$jsonResult->success = false;
			$jsonResult->message = "请正确修改！";
		} elseif (empty($userInfo)) {
			$jsonResult->success = false;
			$jsonResult->message = "无该生，请检查！";
		} else {
			$updatePwd = SeUserinfo::updateAll(["passWd" => strtoupper(md5(123456)), "updateTime" => DateTimeHelper::timestampX1000()],
				"userID=:userId and schoolID=:schoolId and type=:type",
				[":userId" => $userId, ":schoolId" => $schoolId, ":type" => 0]);

			if ($updatePwd == 1) {
				$jsonResult->success = true;
				$jsonResult->message = "密码重置成功！";
			} else {
				$jsonResult->success = false;
				$jsonResult->message = "修改失败！";
			}
		}
		return $this->renderJSON($jsonResult);
	}

	/**
	 * 点击修改 获取 用户相关信息
	 * @return string
	 * @throws \yii\web\HttpException
	 */
	public function actionUpdateStuInfoView()
	{
		$schoolId = $this->schoolId;
		$userId = app()->request->get("userId", null);
		if (empty($userId)) {
			return $this->notFound("用户不能为空！");
		}
		$classMembers = SeClassMembers::getClass($userId);
		if(empty($classMembers)){
			//查询无班级学生信息
			$stuInfo = SeUserinfo::find()->where(['userID' => $userId, 'schoolID' => $schoolId, 'isDelete' => 0])->asArray()->one();
			$stuInfo['classID'] = null;
			$stuInfo['stuID'] = null;
		}else{
			$stuInfo = SeUserinfo::findBySql("select userinfo.userID, userinfo.trueName, userinfo.department,
 userinfo.bindphone, userinfo.sex, classMember.classID, classMember.userID, classMember.stuID
 from se_userinfo userinfo
 INNER JOIN se_classMembers classMember
 ON userinfo.userID=classMember.userID
 WHERE userinfo.schoolID=:schoolId
 AND userinfo.userID=:userId",
				[":userId" => $userId,":schoolId"=>$schoolId])->asArray()->one();
		}

		if (empty($stuInfo)) {
			return $this->notFound("未找到该用户");
		}

		//查询家长信息
		$parentsInfo = SeUserinfo::find()->where(["phoneReg" => $stuInfo["bindphone"], "type" => 3])->select('trueName,bindphone')->one();

		return $this->renderPartial("_edit_student_info", ["stuInfo" => $stuInfo, "parentsInfo" => $parentsInfo,'classMembers' => $classMembers]);
	}

	/**
	 * 修改用户
	 * @return string
	 */
	public function actionUpdateUserInfo()
	{
		$jsonResult = new JsonMessage();
		$schoolId = $this->schoolId;
		$userId = app()->request->post("userId");
		$parentsId = app()->request->post("parentsId");
		$stuNumber = app()->request->post("stuNumber");
		$stuName = app()->request->post("stuName");
		$stuSex = app()->request->post("stuSex");
		$parentsName = app()->request->post("parentsName");

		$transaction = Yii::$app->db_school->beginTransaction();
		//查询该用户是否在该校
		$checkUserInfo = SeUserinfo::find()->where(["schoolID" => $schoolId, "userID" => $userId, "type" => 0])->one();
		//查询家长信息
		$checkParentsInfo = SeUserinfo::find()->andWhere(["userID" => $parentsId, "type" => "3", "phoneReg" => $checkUserInfo["bindphone"]])->one();
		//查询班级关系表，查询该生是否在该班
		$checkClassMemUser = SeClassMembers::find()->where(["userID" => $userId])->one();

		if (empty($userId)) {
			$jsonResult->success = false;
			$jsonResult->message = "请正确修改！";
			return $this->renderJSON($jsonResult);
		} elseif (empty($checkUserInfo)) {
			$jsonResult->success = false;
			$jsonResult->message = "无该生，请检查！";
			return $this->renderJSON($jsonResult);
		} elseif (empty($checkParentsInfo) && !empty($parentsId)) {
			$jsonResult->success = false;
			$jsonResult->message = "无该家长，请检查！";
			return $this->renderJSON($jsonResult);
		}elseif (empty($stuName)) {
			$jsonResult->success = false;
			$jsonResult->message = "用户名不能为空！";
			return $this->renderJSON($jsonResult);
		} elseif (mb_strlen($stuName) < 2) {
			$jsonResult->success = false;
			$jsonResult->message = "用户名至少2个字！";
			return $this->renderJSON($jsonResult);
		} elseif (empty($stuNumber) && $stuNumber !=0) {
			$jsonResult->success = false;
			$jsonResult->message = "学号不能为空！";
			return $this->renderJSON($jsonResult);
		}  elseif (mb_strlen($parentsName) < 2 && !empty($parentsName)) {
			$jsonResult->success = false;
			$jsonResult->message = "家长名至少2个字符！";
			return $this->renderJSON($jsonResult);
		}

		try {
			if (empty($checkClassMemUser)) {
				$checkUserInfo->trueName = $stuName;
				$checkUserInfo->sex = $stuSex;
				$checkUserInfo->parentsName = $parentsName;
			}else{
				$checkUserInfo->trueName = $stuName;
				$checkUserInfo->sex = $stuSex;
				$checkUserInfo->parentsName = $parentsName;
				$checkClassMemUser->stuID = $stuNumber;
				$checkClassMemUser->memName = $stuName;
				$checkClassMemUser->save(false);
			}

			if (!empty($checkParentsInfo)) {
				$checkParentsInfo->trueName = $parentsName;
				$checkParentsInfo->save(false);
			}
			$checkUserInfo->save(false);
			$transaction->commit();
			$jsonResult->success = true;
			$jsonResult->message = "修改成功！";

		} catch (Exception $e) {
			$transaction->rollBack();
			$jsonResult->message = '修改失败';
		}
		return $this->renderJSON($jsonResult);

	}

	/**
	 * 获取用户相关信息 用于修改学生信息 刷新单条
	 * @return string
	 * @throws \yii\web\HttpException
	 */
	public function actionStudentOneDetail()
	{
		$schoolId = $this->schoolId;
		$userId = app()->request->get("userId", null);
		if (empty($userId)) {
			return $this->notFound("用户不能为空！");
		}


		$stuInfo = SeUserinfo::findBySql(
			'select userinfo.userID,userinfo.trueName,userinfo.bindphone,userinfo.department,userinfo.sex,classMember.classID,classMember.stuID
			from se_userinfo userinfo
			INNER JOIN se_classMembers classMember
			ON userinfo.userID=classMember.userID
			WHERE userinfo.schoolID=:schoolId AND userinfo.userID=:userId',
			[":userId" => $userId,":schoolId"=>$schoolId])->asArray()->one();


		if (empty($stuInfo)) {
			return $this->notFound("未找到该用户");
		}

		return $this->renderPartial("_student_list_detail", ["item" => $stuInfo]);
	}
	/**
	 * 根据学部获取年级
	 * @param $id
	 */
	public function actionGetGradeData($id)
	{
		echo Html::tag("option", "请选择", array("value" => ""));
		$schoolData = $this->schoolModel;

		if (empty($id)) {
			$id = $schoolData->department; //学部id
		}

		$lengthOfSchooling = $schoolData->lengthOfSchooling;
		$obj = new Apollo_BaseInfoService();
		$data = $obj->loadGrade($id, $lengthOfSchooling);

		foreach ($data as $item) {
			echo Html::tag('option', Html::encode($item->gradeName), array('value' => $item->gradeId));
		}
	}

	/**
	 * 根据年级获取学校的班级
	 * @param integer  $id 年级id
	 * @param bool|true $prompt
	 * @param null $department
	 */

	public function actionGetClassData($id, $prompt = true, $department = null)
	{
		$schoolID = $this->schoolId;
		if ($prompt) {
			echo Html::tag("option", "请选择", ["value" => ""]);
		}

		if (empty($id)) {
			return ;
		}
		$data = ClassListModel::model($schoolID, $id, $department)->getListData();
		foreach ($data as $key => $item) {
			echo Html::tag('option', Html::encode($item), array('value' => $key));
		}
	}

	/**
	 * 调班学生的信息
	 * @return string
     */
	public function actionStudentInfo(){
		$jsonResult = new JsonMessage();
		$schoolId = $this->schoolId;
		$userId = app()->request->post('userID');
		if (empty($userId)) {
			return $this->notFound("用户不能为空！");
		}
		//查询该用户的原有信息
		$classMembers = SeClassMembers::getClass($userId);

		if (empty($classMembers)) {
			//无班级学生信息
			$userInfo = SeUserinfo::getOne($userId,$schoolId);
			$trueName = $userInfo->trueName;

			//无班级学生当前所在学部名称
			$department = WebDataCache::getClassDepartment($userInfo->department);

			$departmentList = [];
			$schoolData = $this->schoolModel;
			$departmentId = $schoolData->department; //学部id
			$departmentArray = explode(',', $departmentId);
			foreach($departmentArray as $departmentId){
				$departmentName = WebDataCache::getClassDepartment($departmentId);
				$departmentList[$departmentId] = $departmentName;
			}

			//查询该学生所在学段的所有年级
			if(empty($userInfo->department)){
				$departmentId = 20201;
			}else{
				$departmentId = $userInfo->department;
			}
			$schoolData = $this->schoolModel;
			$lengthOfSchooling = $schoolData->lengthOfSchooling;

			$gradeData = GradeHelper::getGradeByDepartmentAndLengthOfSchooling($departmentId, $lengthOfSchooling);
			$gradeList = ArrayHelper::map($gradeData, 'gradeId', 'gradeName');

			//查询默认年级的所有班级
			$gradeId = key($gradeList);
			$classList = [];
			$classes = ClassListModel::model($schoolId)->getData($schoolId, $gradeId, $departmentId);
			foreach ($classes as $class) {
				$classList[$class->classID] = $class->className;
			}
			$resultArr = [
				'trueName' => $trueName,
				'department' =>$department ,
				'departmentList' => $departmentList,
				'gradeList' => $gradeList,
				'classList' => $classList
			];
		}else {
			$gradeName = [];
			$className = [];
			$class = $classMembers->getSeClass()->one();
			$department = WebDataCache::getClassDepartment($class->department);
			$gradeName[$class->gradeID] = WebDataCache::getGradeName($class->gradeID);
			$className[$class->classID] = $class->className;

			//查询该学生所在学校的学段
			$departmentList = [];
			$schoolData = $this->schoolModel;
			$departmentId = $schoolData->department; //学部id
			$departmentArray = explode(',', $departmentId);
			foreach ($departmentArray as $departmentId) {
				$departmentName = WebDataCache::getClassDepartment($departmentId);
				$departmentList[$departmentId] = $departmentName;
			}

			//查询该学生所在学段的所有年级
			$departmentId = $class->department;
			$schoolData = $this->schoolModel;
			$lengthOfSchooling = $schoolData->lengthOfSchooling;

			$gradeData = GradeHelper::getGradeByDepartmentAndLengthOfSchooling($departmentId, $lengthOfSchooling);
			$gradeList = ArrayHelper::map($gradeData, 'gradeId', 'gradeName');

			//查询该学生所在年级的所有班级
			$departmentId = $class->department;
			$gradeId = $class->gradeID;

			$classList = [];
			$classes = ClassListModel::model($schoolId)->getData($schoolId, $gradeId, $departmentId);
			foreach ($classes as $class) {
				$classList[$class->classID] = $class->className;
			}

			$resultArr = [
				'trueName' => $classMembers->memName,
				'department' => $department,
				'gradeID' => $gradeName,
				'className' => $className,
				'departmentList' => $departmentList,
				'gradeList' => $gradeList,
				'classList' => $classList
			];
		}

		if($resultArr){
			$jsonResult->success = true;
			$jsonResult->data = $resultArr;
		}else{
			$jsonResult->success = false;
			$jsonResult->message = '未找到该用户';
		}

		return $this->renderJSON($jsonResult);
	}


	/**
	 * 根据下拉列表所选学部查询年级和班级（学生调班）
	 * @return string
     */
	public function actionGetGrade(){
		$jsonResult = new JsonMessage();

		$schoolId = $this->schoolId;
		$departmentId = app()->request->post('department');

		$schoolData = $this->schoolModel;
		$lengthOfSchooling = $schoolData->lengthOfSchooling;

		//根据学部查询年级
		$gradeData=GradeHelper::getGradeByDepartmentAndLengthOfSchooling($departmentId, $lengthOfSchooling);
		$gradeList =  ArrayHelper::map($gradeData, 'gradeId', 'gradeName');

		//根据所选学部和默认选择的年级查询班级
		$gradeId = key($gradeList);
		$classList = [];
		$classes = ClassListModel::model($schoolId)->getData($schoolId,$gradeId,$departmentId);

		foreach($classes as $class){
			$classList[$class->classID] = $class->className;
		}

		$resultArr = [
			'gradeList' => $gradeList,
			'classList' => $classList,
			'firstGradeId' => $gradeId
		];
		if($resultArr){
			$jsonResult->success = true;
			$jsonResult->data = $resultArr;
		}else{
			$jsonResult->success = false;
			$jsonResult->message = '该学段数据为空，请重新选择学段';
		}
		return $this->renderJSON($jsonResult);
	}

	/**
	 * 根据下拉列表所选学段年级查询班级（学生调班）
	 * @return string
     */
	public function actionGetClasses(){
		$jsonResult = new JsonMessage();

		$schoolId = $this->schoolId;
		$departmentId = app()->request->post('department');
		$gradeId = app()->request->post('gradeId');

		//根据学段和年级查询班级
		$classList = [];
		$classes = ClassListModel::model($schoolId)->getData($schoolId,$gradeId,$departmentId);
		foreach($classes as $class){
			$classList[$class->classID] = $class->className;
		}
		if($classList){
			$jsonResult->success = true;
			$jsonResult->data = $classList;
		}else{
			$jsonResult->success = false;
			$jsonResult->message = '该年级没有班级，请重新选择年级';
		}

		return $this->renderJSON($jsonResult);
	}


	/**
	 * 学生调班的操作
	 * @return JsonMessage
     */
	public function actionStudentClassModify(){
		$jsonResult = new JsonMessage();

		$department = app()->request->post('department');
		$schoolId = $this->schoolId;
		$classId = app()->request->post('classId');
		$userId = app()->request->post('userId');

		//验证该人和所要调的班级是否在该学校
		$this->getSchoolUser($userId);
		$this->getSchoolClassModel($classId);

		//调用接口执行操作
		$ClassChangeServiceModel = new ClassChangeService();
		$result = $ClassChangeServiceModel->ChangeStudentClass($schoolId,$department,$classId,$userId);
		if($result){
			$jsonResult->success = true;
			$jsonResult->message = '班级调动成功';
		}else{
			$jsonResult->success = false;
			$jsonResult->message = '班级调动失败';
		}
		return $this->renderJSON($jsonResult);
	}

	/**
	 * 学生离校
	 * @return JsonMessage
     */
	public function actionStudentLeaveSchool(){
		$jsonResult = new JsonMessage();

		$userId = Yii::$app->request->post('userId');
		$schoolId = $this->schoolId;

		//验证该生是否在该校
		$this->getSchoolUser($userId);

		//调用接口执行操作
		$ClassChangeServiceModel = new ClassChangeService();
		$result = $ClassChangeServiceModel->DelUserSchool($userId,$schoolId);
		if($result){
			$jsonResult->success = true;
			$jsonResult->message = '离校设置成功';
		}else{
			$jsonResult->success = false;
			$jsonResult->message = '离校设置失败';
		}
		return $this->renderJSON($jsonResult);
	}
}