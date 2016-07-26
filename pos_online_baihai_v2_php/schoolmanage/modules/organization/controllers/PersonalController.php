<?php
namespace schoolmanage\modules\organization\controllers;

use common\models\JsonMessage;
use common\models\pos\SeClass;
use common\models\pos\SeClassMembers;
use common\models\pos\SeUserinfo;
use common\services\ClassChangeService;
use schoolmanage\components\SchoolManageBaseAuthController;
use Yii;

/**
 * Created by wangchunlei.
 * User: Administrator
 * Date: 2016/7/13
 * Time: 13:54
 */
class PersonalController extends SchoolManageBaseAuthController
{
    public $layout = "lay_organization_index";
    public $enableCsrfValidation = false;

    /**
     * 组织管理的教师和学生列表
     * @return string
     */
    public function actionManageList()
    {
        $classID =app()->request->getQueryParam('classId');
        $this->getSchoolClassModel($classID);
        $teacherList = SeClassMembers::find()->where(['classId' => $classID, 'identity' => ['20402', '20401']])->all();
        $studentList = SeClassMembers::find()->where(['classId' => $classID, 'identity' => '20403'])->all();

        return $this->render("manageList", ['teacherList' => $teacherList,
            'studentList' => $studentList,
            'classID' => $classID
        ]);
    }

    /**
     * 搜索老师列表
     * @return string
     */
    public function actionGetTeachers()
    {

        $jsonResult = new JsonMessage();
        $keywords = app()->request->getBodyParam('keywords');
        if ($keywords != null) {

            $userResult = SeUserinfo::find()->where(['phoneReg' => $keywords])->orWhere(['trueName' => $keywords])->orWhere(['phone' => $keywords])->andWhere(['type' => 1,'schoolID'=>$this->schoolId])->all();

            if ($userResult) {
                $jsonResult->data = $this->renderPartial('_teacher_list', ['userResult' => $userResult]);
                $jsonResult->success = true;
            }
        }
        return $this->renderJSON($jsonResult);
    }

    /**
     * 添加老师到班级
     * @return string
     */
    public function actionAddTeacherToClass()
    {
        $jsonResult = new JsonMessage();

        $classID = app()->request->getBodyParam('classID');

        $classModel = $this->getSchoolClassModel($classID);

        if($classModel instanceof JsonMessage){
            return $classModel;
        }
        $userID = app()->request->getBodyParam('userID');

       $userModel =  $this->getSchoolUserModel($userID);

        if($userModel instanceof JsonMessage){

            return $userModel;
        }
        $service = new ClassChangeService();

        $result = $service->AddTeacher($userID,$classID);

        $jsonResult->message = $result->message;

        $jsonResult->success = $result->success;

        return $this->renderJSON($jsonResult);

    }

    /**
     *改变学生和老师班内职务
     */
    public function actionChangeIdentity()
    {


        $jsonResult = new JsonMessage();

        $userID = app()->request->getBodyParam('userID');

        $userModel = $this->getSchoolUserModel($userID);

        if($userModel instanceof JsonMessage){
           return $userModel;
        }
        $classID = app()->request->getBodyParam('classID');

        $classModel =  $this->getSchoolClassModel($classID);

           if($classModel instanceof JsonMessage){
               return $classModel;
           }
        $identity=app()->request->getBodyParam('identity');

        $service = new ClassChangeService();

        $result = $service->ChangeIdentity($userID,$classID,$identity);

        $jsonResult->message = $result->message;

        $jsonResult->success = $result->success;

        return $this->renderJSON($jsonResult);


    }

    /**
     *教师和学生移除班级
     */
    public function actionLeaveClass(){
        $jsonResult =  new JsonMessage();

            $classID=app()->request->getBodyParam('classID');

       $classModel = $this->getSchoolClassModel($classID);

        if($classModel instanceof JsonMessage){
            return $classModel;
        }
            $userID=app()->request->getBodyParam('userID');

       $userModel = $this->getSchoolUserModel($userID);

        if($userModel instanceof JsonMessage){

            return $userModel;
        }
          $service = new ClassChangeService();

        $result = $service->DelUserClass($userID,$classID);

        $jsonResult->success = $result->success;

        $jsonResult->message = $result->message;

        return $this->renderJSON($jsonResult);

    }

    /**
     * 添加学生
     * @return string
     */
    public function actionAddStudent(){
                $classID=app()->request->getQueryParam('classID');

       $classModel =  $this->getSchoolClassModel($classID);

        if($classModel instanceof JsonMessage){
            return $classModel;
        }

        return $this->render('addStudent',['classID'=>$classID]);

    }

    /**
     * 检索出学生列表
     * @return string
     */
    public function actionQueryStudents(){

        $classID=app()->request->getBodyParam('classID');

        $phone=app()->request->getBodyParam('phone');


        $userInfoQuery=SeUserinfo::find()->where(['bindphone'=>$phone,'type'=>'0']);


        $studentList=$userInfoQuery->all();
            return $this->renderPartial('stu_list',['studentList'=>$studentList,'classID'=>$classID]);
    }

    /**
     * 新建账号时候弹出的学生信息
     * @return string
     */
    public function actionGetStuHtml(){

        $trueName = app()->request->getBodyParam('trueName');

        $bindphone = app()->request->getBodyParam('bindphone');

        $phoneReg = $trueName.$bindphone;

        $userIsExisted = SeUserinfo::find()->where(['phoneReg'=>$phoneReg])->exists();

        if($userIsExisted){

            $phoneReg = $trueName.rand(1,99).$bindphone;
        }
        return $this->renderPartial('stu_html',['trueName'=>$trueName,

                                                'bindphone'=>$bindphone,

                                                'phoneReg'=>$phoneReg
        ]);
    }

    /**
     * 修改学生信息时候弹出的学生信息
     * @return string
     */
    public function actionGetStuDetails(){

        $userID = app()->request->getBodyParam('userID');

        $userDetails = SeUserinfo::find()->where(['userID'=>$userID])->one();

        return $this->renderPartial('stu_details',['userDetails'=>$userDetails]);
    }

    /**
     * 添加和修改学生并且把学生拉到当前班级
     * @return string
     */
    public function actionMoveStuToClass(){

        $jsonResult = new JsonMessage();

        $classID=app()->request->getBodyParam('classID');

        $classModel = $this->getSchoolClassModel($classID);

        if($classModel instanceof JsonMessage){
            return $classModel;
        }

        $type=app()->request->getBodyParam('type');

        $stuID=app()->request->getBodyParam('stuID');

        $trueName = app()->request->getBodyParam('trueName');

        $bindphone=app()->request->getBodyParam('bindphone');

        $sex=app()->request->getBodyParam('sex');

        $phoneReg=app()->request->getBodyParam('phoneReg');

        $parentsName=app()->request->getBodyParam('parentsName');

        $phone = app()->request->getBodyParam('phone');

        $department = null;

        $classDetails =SeClass::find()->where(['classID'=>$classID])->select('department')->one();

        if($classDetails){

            $department = $classDetails->department;

        }

        $service = new ClassChangeService();

        $result =$type?$service->AddStudent($this->schoolId,$classID,$stuID,$trueName,$phone,$sex,$phoneReg,$parentsName,$bindphone,$department):$service->ModifyStudent($this->schoolId,$classID,$stuID,$trueName,$phone,$sex,$phoneReg,$parentsName,$bindphone,$department);

        $jsonResult->success = $result->success;

        $jsonResult->message = $result->message;

        return $this->renderJSON($jsonResult);
    }
}