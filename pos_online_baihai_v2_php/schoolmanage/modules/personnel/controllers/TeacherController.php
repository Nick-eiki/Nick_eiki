<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2016/3/2
 * Time: 11:44
 */
namespace schoolmanage\modules\personnel\controllers;
use common\helper\DateTimeHelper;
use common\models\JsonMessage;
use common\models\pos\SeClass;
use common\models\pos\SeUserinfo;
use common\services\ClassChangeService;
use frontend\models\dicmodels\ClassListModel;
use frontend\models\dicmodels\LoadSubjectModel;
use frontend\models\dicmodels\LoadTextbookVersionModel;
use schoolmanage\components\helper\GradeHelper;
use schoolmanage\components\SchoolManageBaseAuthController;
use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * UserController implements the CRUD actions for SeUserinfo model.
 */
class TeacherController extends SchoolManageBaseAuthController
{

	public $layout = "lay_personnel_index";
	public $enableCsrfValidation = false;

	/**
	 * 人员管理 教师管理
	 * @return string
	 */
	public function actionIndex()
	{

		$schoolId = $this->schoolId;
		$department = app()->request->get("department");
		$subjectId = app()->request->get("subjectId");

		$searchWord = app()->request->post("searchWord");

		$pages = new Pagination();
		$pages->validatePage = false;
		$pages->pageSize = 10;
		$schoolData = $this->schoolModel;
		$departmentId = $schoolData->department; //学部id
		$departmentArray = explode(',', $departmentId);

		$teacherQuery = SeUserinfo::find()->where(["schoolID"=>$schoolId, "type"=>1]);

		//搜用户名 he 搜手机
		if(!empty($searchWord)){
			$teacherQuery->andWhere(['or', ["like","trueName",$searchWord],  ["like","bindphone",$searchWord]]);
		}

		//搜学部
		if (!empty($department)) {
			$teacherQuery->andWhere(["department"=>$department]);
		}
		//搜学科
		if (!empty($subjectId)) {
			$teacherQuery->andWhere(["subjectID"=>$subjectId]);
		}

		$pages->totalCount = $teacherQuery->count();
		$userInfo = $teacherQuery->orderBy('createTime desc')->offset($pages->getOffset())->limit($pages->getLimit())->select("userID,trueName,department,sex,subjectID,bindphone,phoneReg")->all();

		$numberOfPeople = $teacherQuery->count();

		if (app()->request->isAjax) {
			return $this->renderPartial("_teacher_list", ["userInfo" => $userInfo, "pages" => $pages,"numberOfPeople" => $numberOfPeople]);
		}

		return $this->render("index",[
				"userInfo" => $userInfo,
				"pages" => $pages,
				"departmentArray" => $departmentArray,
				"numberOfPeople" => $numberOfPeople
		]);
	}

	/**
	 * 用于重置密码页面
	 * @return bool|string
	 */
	public function actionAlertPassword()
	{
		$schoolId = $this->schoolId;
		$userId = app()->request->get("userId");
		if(empty($userId)){
			return $this->notFound("用户不能为空！");
		}
		//查询该用户是否在该校
		$userInfo = SeUserinfo::find()->where(["schoolID"=>$schoolId, "userID"=>$userId, "type"=>1])->select("userID,trueName")->one();
		if(empty($userInfo)){
			return $this->notFound("未找到该用户");
		}
		return $this->renderAjax("_reset_passwd_view", ["userInfo"=>$userInfo]);
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
		$userInfo = SeUserinfo::find()->where(["schoolID"=>$schoolId, "userID"=>$userId, "type"=>1])->select("userID")->one();

		if(empty($userId)){
			$jsonResult->success = false;
			$jsonResult->message = "请正确修改！";
		} elseif (empty($userInfo)) {
			$jsonResult->success = false;
			$jsonResult->message = "无该教师，请检查！";
		} else {
			$updatePwd = SeUserinfo::updateAll(["passWd"=>strtoupper(md5(123456)), "updateTime"=>DateTimeHelper::timestampX1000()], "userID=:userId and schoolID=:schoolId and type=:type", [":userId"=>$userId,":schoolId"=>$schoolId,":type"=>1]);

			if($updatePwd == 1) {
				$jsonResult->success = true;
				$jsonResult->message = "修改成功！";
			} else {
				$jsonResult->success = false;
				$jsonResult->message = "修改失败！";
			}
		}
		return $this->renderJSON($jsonResult);
	}

	/**
	 * 查询教师的详情
	 * @return bool|string
	 */
	public function actionViewUserInfo()
	{
		$schoolId = $this->schoolId;

		$userId = app()->request->get("userId");
		if(empty($userId)){
			return $this->notFound("用户不能为空！");
		}

		$teaInfo = SeUserinfo::find()->where(["userID"=>$userId, "schoolID"=>$schoolId, "type"=>1])->select("userID,trueName,department,sex,subjectID,bindphone,textbookVersion")->one();

		//获取班级
		$classMem = $teaInfo->getSeClassMembers()->select('classID')->all();

		//获取教研组
		$groupMem = $teaInfo->getSeGroupMembers()->select('groupID')->all();

		if(empty($teaInfo)){
			return $this->notFound("未找到该用户");
		}
		return $this->renderPartial("_view_teacher_info", ["teaInfo"=>$teaInfo, "classMem"=>$classMem, "groupMem"=>$groupMem]);
	}

	/**
	 * 点击修改 获取 用户相关信息
	 * @return string
	 * @throws \yii\web\HttpException
	 */
	public function actionUpdateTeaInfoView()
	{
		$schoolId = $this->schoolId;

		$userId = app()->request->get("userId");
		if(empty($userId)){
			return $this->notFound("用户不能为空！");
		}

		$schoolData = $this->schoolModel;
		$departmentId = $schoolData->department; //学部id
		$departmentArray = explode(',', $departmentId);
//查询教师信息
		$teaInfo = SeUserinfo::find()->where(["userID"=>$userId, "schoolID"=>$schoolId, "type"=>1])->select("userID,trueName,department,sex,subjectID,bindphone,textbookVersion")->one();

		//获取班级
		$classMem = $teaInfo->getSeClassMembers()->select('classID')->all();
		//获取教研组
		$groupMem = $teaInfo->getSeGroupMembers()->select('groupID')->all();

		if(empty($teaInfo)){
			return $this->notFound("未找到该用户");
		}
		return $this->renderAjax("_edit_teacher_info", ["teaInfo"=>$teaInfo, "departmentArray"=>$departmentArray, "classMem"=>$classMem, "groupMem"=>$groupMem]);
	}

	/**
	 * 修改用户信息
	 * @return string
	 */
	public function actionUpdateUserInfo()
	{
		$jsonResult = new JsonMessage();

		$schoolId = $this->schoolId;
		$userId = app()->request->post("userId");
		$teaName = app()->request->post("teaName");
		$teaSex = app()->request->post("teaSex");
		$version = app()->request->post("version");
		$subject = app()->request->post("subject");
		$userInfoQuery = SeUserinfo::find()->where(["schoolID"=>$schoolId]);

		//查询该用户是否在该校
		$checkUserInfo = $userInfoQuery->andWhere(["userID"=>$userId, "type"=>1])->one();

		if(empty($userId)){
			$jsonResult->success = false;
			$jsonResult->message = "请正确修改！";
		} elseif(empty($checkUserInfo)) {
			$jsonResult->success = false;
			$jsonResult->message = "该校无此教师，请检查！";
		}elseif(empty($teaName)){
			$jsonResult->success = false;
			$jsonResult->message = "用户名不能为空！";
		}elseif(mb_strlen($teaName)<2){
			$jsonResult->success = false;
			$jsonResult->message = "用户名至少2个字！";
		}elseif(empty($subject)){
			$jsonResult->success = false;
			$jsonResult->message = "请选择学科！";
		} else {

			$checkUserInfo->trueName = $teaName;
			$checkUserInfo->sex = $teaSex;
			$checkUserInfo->subjectID = $subject;
			$checkUserInfo->textbookVersion = $version;

			if($checkUserInfo->save(false) ){
				$jsonResult->success = true;
				$jsonResult->message = "修改成功！";
			}else{
				$jsonResult->success = false;
				$jsonResult->message = "修改失败！";
			}
		}
		return $this->renderJSON($jsonResult);
	}

	/**
	 * 查询单条用户信息  用于修改信息后刷新单条记录
	 * @return string
	 * @throws \yii\web\HttpException
	 */
	public function actionTeacherOneDetail(){
		$schoolId = $this->schoolId;

		$userId = app()->request->get("userId");
		if(empty($userId)){
			return $this->notFound("用户不能为空！");
		}

		//查询教师信息
		$teaInfo = SeUserinfo::findBySql("select userinfo.userID, userinfo.trueName, userinfo.department, userinfo.sex, userinfo.subjectID, userinfo.bindphone, userinfo.phoneReg, classMember.classID
 											from se_userinfo userinfo
 											INNER JOIN se_classMembers classMember
 											ON userinfo.userID=classMember.userID

 											WHERE userinfo.schoolID=:schoolId
 											AND userinfo.userID=:userId
 											AND userinfo.type=1",[":userId"=>$userId,":schoolId"=>$schoolId])->asArray()->one();
		if(empty($teaInfo)){
			return $this->notFound("未找到该用户");
		}
		return $this->renderPartial("_teacher_list_detail", ["item"=>$teaInfo]);
	}


	/**
	 *根据学部获取科目
	 * @param $department
	 */
	public function actionGetSubject($department)
	{
		echo Html::tag('option', '请选择', array('value' => ''));
		if (empty($department)) {
			return;
		}
		$data = LoadSubjectModel::model()->getData($department, 1);
			foreach ($data as $item) {
			echo Html::tag('option', Html::encode($item->secondCodeValue), array('value' => $item->secondCode));
		}
	}

	/**
	 * 根据科目查询版本
	 * @param $subject
	 */
	public function actionGetVersion($subject,$prompt=true,$grade=null)
	{

		if($prompt){
			echo Html::tag('option', '请选择', array('value' => ''));
		}
		if (empty($subject)) {
			return;
		}
		$data = LoadTextbookVersionModel::model($subject,$grade)->getListData();
		foreach ($data as $key => $item) {
			echo Html::tag('option', Html::encode($item), array('value' => $key));
		}
	}

	/**
	 * 根据 学段 科目查询版本
	 * @param $subject
	 */
	public function actionGetVersions($subject, $department)
	{

		echo Html::tag('option', '请选择', array('value' => ''));
		if (empty($subject) || empty($department)) {
			return;
		}
		$data = LoadTextbookVersionModel::model($subject, null, $department)->getListData();
		foreach ($data as $key => $item) {
			echo Html::tag('option', Html::encode($item), array('value' => $key));
		}
	}

	/**
	 * 老师移除学校
     */
	public function actionKickedOutSchool()
	{
		$jsonResult = new JsonMessage();
		$schoolId = $this->schoolId;
		$userId = app()->request->post("userId");
		if(empty($userId)){
			$jsonResult->message = '用户名不能为空';
			return $this->renderJSON($jsonResult);
		}
		$this->getSchoolUser($userId);
		$classChangeHelperModel=new ClassChangeService();
		$jsonResult = $classChangeHelperModel->DelUserSchool($userId,$schoolId);
		return $this->renderJSON($jsonResult);

	}


	/**
	 * 查询学部下的年级
	 * @return string
     */
	public function actionGetGrade()
	{
		$departmentId = app()->request->post('departmentId');
		$jsonResult = new JsonMessage();
		//学制
		$schoolData = $this->schoolModel;
		$lengthOfSchooling = $schoolData->lengthOfSchooling;

		//根据学部和学制获取相对的年级列表
		$gradeModel = GradeHelper::getGradeByDepartmentAndLengthOfSchooling($departmentId,$lengthOfSchooling,1);
		$gradeDataList =  ArrayHelper::map($gradeModel, 'gradeId', 'gradeName');
		$jsonResult->data = $gradeDataList;
		return $this->renderJSON($jsonResult);
	}

	/**
	 * 老师教的班级及班级名
	 * @return string
     */
	public function actionTeachingClasses()
	{
		$jsonResult = new JsonMessage();
		$userId = app()->request->post('userId');
		if(empty($userId)){
			$jsonResult->message = '用户名不能为空';
			return $this->renderJSON($jsonResult);
		}

		//老师教的班级
		$teachingClasses = SeClass::getClasses($userId);
		$classesDataList =  ArrayHelper::map($teachingClasses, 'classID', 'className');
		$jsonResult->success = true;
		$jsonResult->data = $classesDataList;
		return $this->renderJSON($jsonResult);
	}

	/**
	 * 获取年级下面的班级
	 * @return string
     */
	public function actionGetClasses()
	{
		$jsonResult = new JsonMessage();
		$schoolId = $this->schoolId;
		$departmentId = app()->request->post('departmentId');
		$gradeId = app()->request->post('gradeId',null);    //获取年级

		//获取相应年级下面相对应的班级
		$classesList=ClassListModel::model($schoolId, $gradeId, $departmentId)->getListData();
		$jsonResult->success = true;
		$jsonResult->data = $classesList;
		return $this->renderJSON($jsonResult);
	}



	/**
	 * 老师班级的修改
     */
	public function actionTeacherClassModify()
	{
		$jsonResult = new JsonMessage();
		$classIdList = app()->request->post('classIdList');
		$userId = app()->request->post('userId');
		if(empty($userId)){
			$jsonResult->message = '用户名不能为空';
			return $this->renderJSON($jsonResult);
		}
		if(empty($classIdList)){
			$jsonResult->message = '班级不能为空';
			return $this->renderJSON($jsonResult);
		}

		$this->getSchoolUser($userId);
		//去重
		$classIdList = array_unique($classIdList);
		$seClassModelList = $this->getSchoolClassModels($classIdList);
		$classIdListAuth =ArrayHelper::getColumn($seClassModelList,'classID');
		$classList = implode(',',$classIdListAuth);
		$classChangeHelperModel=new ClassChangeService();
		$jsonResult= $classChangeHelperModel ->TeacherChangeClass($userId,$classList);
		return $this->renderJSON($jsonResult);
	}
}