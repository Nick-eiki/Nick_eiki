<?php
namespace frontend\controllers;

use common\models\JsonMessage;
use common\models\pos\SeUserControl;
use common\services\sms\SendSmsHelper;
use frontend\components\BaseController;
use frontend\components\WebDataCache;
use frontend\models\dicmodels\TeachingResearchDutyModel;
use frontend\models\LoginForm;
use frontend\models\RegisterForm;
use frontend\models\StudentUserForm;
use frontend\models\TeacherClassForm;
use frontend\models\TeacherGroupForm;
use frontend\models\TeacherUserForm;
use frontend\services\pos\pos_ClassInfoService;
use frontend\services\pos\pos_ClassMembersService;
use frontend\services\pos\pos_PersonalInformationService;
use frontend\services\pos\pos_SchoolInfoService;
use frontend\services\pos\pos_TeachingGroupService;
use frontend\services\pos\pos_UserRegisterService;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;


/**
 *
 * Created by PhpStorm.
 * User: unizk
 * Date: 14-6-20
 * Time: 下午5:01
 */
class RegisterController extends BaseController
{

    public $layout = '@app/views/layouts/n_lay_register';


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
//                    [
//                        'allow' => false,
//                        'actions'=>['student-find-group'],
//                        'roles' => ['@'],
//                        'denyCallback' => function ($rule, $action) {
//                          return   !loginUser()->isStudent();
//
//                        }
//                    ],
//                    [
//                        'allow' => false,
//                        'actions'=>['teacher-find-group'],
//                        'roles' => ['@'],
//                        'denyCallback' => function ($rule, $action) {
//                           return  !loginUser()->isStudent();
//
//                        }
//                    ],
                    [

                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['?'],
                    ],


                ],
            ]
        ];
    }

    /**
     *学生或者老师注册
     */
    public function actionIndex()
    {
        if ($this->isLogin()) {
            return $this->redirect('/register/sms-verification');
        }
        $registerForm = new RegisterForm();

        if (isset($_POST['RegisterForm'])) {

            $registerForm->attributes = $_POST['RegisterForm'];
            if ($registerForm->validate()) {
                $teacher = new  pos_UserRegisterService();
                $user = $teacher->registerAccountPhone($registerForm->username, $registerForm->passwd, $registerForm->type, $registerForm->trueName, $registerForm->mobile, '');

                if ($user->resCode == pos_UserRegisterService::successCode) {

                    //login
                    $loginModel = new LoginForm();
                    $loginModel->userName = $registerForm->username;
                    $loginModel->passwd = $registerForm->passwd;
                    $loginModel->login();

                    return $this->redirect(url('register/sms-verification'));
                }

            }
        }
        return $this->render('index', array('model' => $registerForm));

    }

    //用户填写短信验证码
    public function actionSmsVerification()
    {

        $phoneReg = loginUser()->getModel(false)->phoneReg;

        $verify_num = app()->request->post("verify_num", '');

        //catch
        $cachekey = 'sendphoneMessage_' . $phoneReg;
        $times = \Yii::$app->cache->get($cachekey);

        if ($verify_num && !empty($verify_num)) {
            $jsonResult = new JsonMessage();

            $register = new  pos_UserRegisterService();
            $verify = $register->activatePhone($phoneReg, $verify_num);

            if ($verify->resCode == pos_UserRegisterService::successCode) {
                $userType = loginUser()->getModel()->type;
                $jsonResult->success = true;
                $jsonResult->data = $userType;
                return $this->renderJSON($jsonResult);
            } elseif ($verify->resCode == '100001') {
                $jsonResult->message = '该手机号码已经被激活，无需重复';
                return $this->renderJSON($jsonResult);
            } else {
                $jsonResult->message = '验证码错误';
                return $this->renderJSON($jsonResult);
            }
        }

        return $this->render('smsverification', array('times' => $times));

    }

    //发送验证码的数据库操作
    public function SendCode($RegPhone)
    {

        $code = '';
        for ($i = 0; $i < 6; $i++) {
            $code .= mt_rand(0, 9);
        }

        $UserModel = SeUserControl::find()->where(['phoneReg' => $RegPhone])->one();
        if ($UserModel == null) {
            $UserModel = new SeUserControl();
            $UserModel->userID = user()->id;
            $UserModel->phoneReg = $RegPhone;
        }

        $UserModel->activateMailCode = $code;
        $UserModel->generateCodeTime = time() * 1000;

        if ($UserModel->save()) {
            $model = new SendSmsHelper();
            return $model->send_activeCode($RegPhone, $code);
        }
        return false;

    }

    //发送验证码
    /**
     *
     */
    public function actionGetActiviteTolkenPhone()
    {

        $userinfo = loginUser();

        $phoneReg = $userinfo->phoneReg;

        $jsonResult = new JsonMessage();

        //60s catch
        $cachekey = 'sendphoneMessage_' . $phoneReg;
        $times = \Yii::$app->cache->get($cachekey);
        if ($times === false) {
            if ($userinfo->status1 == 1) {
                $jsonResult->message = '该手机号码已经被激活，无需重复';
            } else {
                $result = $this->SendCode($phoneReg);
                if ($result) {
                    $jsonResult->success = true;
                    $jsonResult->message = '发送成功';
                    $times = time() + 60;
                    Yii::$app->cache->set($cachekey, $times, 60);
                } else {
                    $jsonResult->success = false;
                    $jsonResult->message = '由于网络等原因，短信发送失败';
                }

            }

        }
        return $this->renderJSON($jsonResult);
    }

    /**
     * 修改注册手机号
     */
    public function actionEditRegistPhone()
    {

        $jsonResult = new JsonMessage();
        $phone = new  pos_UserRegisterService();
        $phoneReg = loginUser()->getModel()->phoneReg;
        $verify_num = app()->request->post("verify_num", '');
        $oldPhoneNum = app()->request->post("oldphonenum");

        if (isset($_POST['verify_num'])) {
            //clear catch
            $cachekey = 'sendphoneMessage_' . $oldPhoneNum;
            Yii::$app->cache->delete($cachekey);

            $json = $phone->editRegistPhone(user()->id, $verify_num);

            if ($json->resCode == '000000') {
                $jsonResult->success = true;
                $jsonResult->message = '修改成功';
            } elseif ($json->resCode == '100001') {
                $jsonResult->message = '此手机号已被占用';
            } else {
                $jsonResult->message = '修改失败,请重新操作';
            }
            return $this->renderJSON($jsonResult);
        }

        return $this->render('editregistphone', array('phoneReg' => $phoneReg));
    }


    /**
     *  老师或者学生注册邮箱激活
     */
    public function actionSensitize()
    {
        if ($this->isLogin() || !isset(app()->request->cookies['reg_email'])) {
            return $this->redirect('/');
        }
        $email = Yii::$app->request->cookies['reg_email']; //"278747224@qq.com";

        $emailUrl = explode('@', $email);
        $emailUrl = $emailUrl[1];
        return $this->render('sensitize', array(
            'email' => $email,
            'emailUrl' => $emailUrl
        ));
    }


    /**
     * 重发邮件
     * @internal param $email
     */
    public function actionResend()
    {
        $email = $_POST['email'];
        $this->sendActiveEmail($email);
        $jsonResult = new JsonMessage();
        $jsonResult->success = true;
        $jsonResult->message = '发送成功';
        return $this->renderJSON($jsonResult);
    }

    /**
     *  重置邮箱
     */
    public function actionResetEmail()

    {
        $id = app()->request->cookies['reg_userId'];
        $lastEmail = app()->request->cookies['reg_email'];
        $jsonResult = new JsonMessage();
        $newEmail = $_POST['newEmail'];
        $passWd = $_POST['passWd'];
        //判断邮箱格式
        $email = "/^[a-zA-Z0-9][a-zA-Z0-9._-]*\@[a-zA-Z0-9]+\.[a-zA-Z0-9\.]+$/A";
        if (!preg_match($email, $newEmail)) {

            $jsonResult->success = false;
            $jsonResult->message = "电子邮件不合法";
            return $this->renderJSON($jsonResult);
        }
        //判断邮箱是否存在
        $userInfo = new pos_UserRegisterService();
        if ($userInfo->emailIsExist($newEmail)) {

            $jsonResult->success = false;
            $jsonResult->message = '邮箱已存在';
            return $this->renderJSON($jsonResult);
        } else {
            $updateEmail = $userInfo->updateEmail($lastEmail, $newEmail, $passWd);
            if ($updateEmail->resCode == pos_UserRegisterService::successCode) {
                $this->sendActiveEmail($newEmail);
                $this->saveCookie($newEmail, $id);
                $jsonResult->success = true;
                $jsonResult->message = '邮箱设置成功';
                return $this->renderJSON($jsonResult);
            } else {
                $jsonResult->success = false;
                $jsonResult->message = $updateEmail->resMsg;
                return $this->renderJSON($jsonResult);
            }

        }
    }


    /**
     *新学生完善信息
     */
    public function actionStudentFindGroup()
    {
        $userInfo = loginUser()->getModel();
        $studentModel = new StudentUserForm();
        $studentModel->department = $userInfo->department;
        $studentModel->schoolId = $userInfo->schoolID;
        $studentModel->userID = $userInfo->userID;
        $studentModel->schoolName = WebDataCache::getSchoolName($userInfo->schoolID);;
        $studentModel->trueName = $userInfo->trueName;
        $studentModel->phone = $userInfo->phone;
        $studentModel->textbookVersion = $userInfo->textbookVersion;

        $items = $userInfo->getUserClassGroup();
        if (count($items) > 0) {
            $studentModel->classMemID = $items[0]->ID;
            $studentModel->classId = $items[0]->classID;
            $studentModel->className = WebDataCache::getClassesName($items[0]->classID);
            $studentModel->identity = $items[0]->identity;
            $studentModel->job = $items[0]->job;
            $studentModel->stuID = $items[0]->stuID;
        }


        if (isset($_POST['StudentUserForm'])) {
            $studentModel->attributes = $_POST['StudentUserForm'];

            if ($studentModel->validate()) {
                $schUserRegisterService = new pos_UserRegisterService();
                $result = $schUserRegisterService->editStudentUserInfoByModel($studentModel);
                if ($result->resCode == pos_UserRegisterService::successCode) {
                    $this->userRedirectClassHome();
                    return $this->redirect('/');
                } else {
                    Yii::$app->getSession()->setFlash('error', $result->resMsg);
                    return $this->refresh();
                }

            }
        }
        return $this->render('studentFindGroup', array('model' => $studentModel));
    }


    /**
     *新教师完善信息
     */
    public function actionTeacherFindGroup()
    {

        $teacherModel = new TeacherUserForm();
        $userModel = loginUser()->getModel();
        $teacherModel->city = $userModel->city;
        $teacherModel->county = $userModel->country;
        $teacherModel->provience = $userModel->provience;
        $teacherModel->department = $userModel->department;
        $teacherModel->schoolId = $userModel->schoolID;
        $teacherModel->userID = $userModel->userID;
        $teacherModel->schoolName = WebDataCache::getSchoolName($userModel->schoolID);
        $teacherModel->trueName = $userModel->trueName;
        $teacherModel->phone = $userModel->phone;
        $teacherModel->textbookVersion = $userModel->textbookVersion;
        $teacherModel->schoolIdentity = $userModel->schooliden;
        $teacherModel->subjectID = $userModel->subjectID;
        $teacherModel->userID = user()->id;


        $teacherClassList = array();
        $teacherGroup = new  TeacherGroupForm();
        if (!isset($_POST['TeacherUserForm'])) {
            $teacherClassLst = loginUser()->getModel()->getUserClassGroup();
            foreach ($teacherClassLst as $item) {
                $t = new  TeacherClassForm();
                $t->classID = $item->classID;
                $t->className = WebDataCache::getClassesName($item->classID);
                $t->identity = $item->identity;
                $teacherClassList[] = $t;
            }

            $groupMember = \common\models\pos\SeGroupMembers::find()->where(['teacherID' => user()->id])->one();

            if ($groupMember != null) {
                $teacherGroup->groupID = $groupMember->groupID;
                $teacherGroup->identity = $groupMember->identity;
                $teacherGroup->groupName = WebDataCache::getTeachingGroupName($groupMember->groupID);
                $teacherGroup->identityName = TeachingResearchDutyModel::model()->getSchoolLevelhName($groupMember->identity);
            }

        }


        if (isset($_POST['TeacherUserForm'])) {
            $valid = true;
            $teacherModel->attributes = $_POST['TeacherUserForm'];
            $valid = $valid && $teacherModel->validate();
            if (!$valid) {

            }
            if (isset($_POST['TeacherGroupForm'])) {
                $teacherGroup = new TeacherGroupForm();
                $teacherGroup->attributes = $_POST['TeacherGroupForm'];
                $validGroup = $teacherGroup->validate();
                if (!$validGroup) {

                    $teacherGroup->getErrors();
                }
                $valid = $valid && $validGroup;
            }

            if (isset($_POST['TeacherClassForm'])) {
                $teacherClassItems = $_POST['TeacherClassForm'];
                foreach ($teacherClassItems as $i => $item) {
                    $tClassForm = new TeacherClassForm();
                    $tClassForm->attributes = $teacherClassItems[$i];
                    $teacherClassList[$i] = $tClassForm;
                    $validClass = $tClassForm->validate();
                    if (!$validClass) {

                    }

                    $valid = $valid && $validClass;
                }
            }

            if ($valid) {
                $schUserRegisterService = new pos_UserRegisterService();
                $result = $schUserRegisterService->editTeacherUserInfoByModel($teacherModel, $teacherClassList, $teacherGroup);
                if ($result->resCode == pos_UserRegisterService::successCode) {

                    $classList = loginUser()->getModel(false)->getUserClassGroup();
                    if (count($classList) > 0) {
                        return $this->redirect(url("class/index", ['classId' => $classList[0]->classID]));

                    } else
                        return $this->redirect('/');
                } else {
                    Yii::$app->getSession()->setFlash('error', $result->resMsg);
                    return $this->refresh();

                }
            }
        }

        if (empty($teacherClassList)) {
            $teacherClassList[] = new TeacherClassForm();
        } else {
            array_values($teacherClassList);
        }


        return $this->render('teacherFindGroup', ['model' => $teacherModel, 'teacherClassList' => $teacherClassList, 'teacherGroup' => $teacherGroup]);
    }


    /**
     *  邮箱激活页面
     */
    public
    function actionEmailActive()
    {

        $email = app()->request->getQueryParam('email');
        $guid = app()->request->getQueryParam('guid');
        $register = new pos_UserRegisterService();
        $result = $register->activateMail($email, $guid);
        if ($result->resCode == pos_UserRegisterService::successCode) {
            return $this->redirect(url('register/email-success', array('type' => 1, 'email' => $email)));
        } else {
            return $this->redirect(url('register/email-success', array('type' => 2, 'email' => '')));
        }

    }

    /**
     * 邮箱激活失败页面
     */
    public function actionEmailSuccess($type, $email)
    {
        return $this->render('emailSuccess', array('type' => $type, 'email' => $email));
    }

    /**
     * 发送邮件
     * @param $email
     */
    public
    function sendActiveEmail($email)
    {
        //生成邮箱激活码
        $register = new pos_UserRegisterService();
        $result = $register->getActiviteTolken($email);
        $activateMailCode = $result->data->activateMailCode;
        $activeUrl = 'http://' . $_SERVER['HTTP_HOST'] . url('register/email-active') . '?guid=' . $activateMailCode . '&email=' . $email;
        $message = new YiiMailMessage;
        $message->view = "active_mail";
        $message->setBody(array("email" => $email, 'activeUrl' => $activeUrl), 'text/html');
        $message->subject = '三海用户注册邮箱激活';
        $message->addTo($email);
        $message->from = app()->params['adminEmail'];
        //发邮件
        app()->mail->send($message);
    }

    /**
     *新查询学校信息
     */
    public function actionSearchSchool()
    {
        $name = app()->request->getQueryParam('name', '');
        $department = app()->request->getQueryParam('department');
        if (empty($department)) {
            return $this->renderPartial('_reg_showMessage', ['err' => true, 'message' => '请选择学段']);

        }
        return $this->renderPartial('newSearchSchool', ['department' => $department]);
    }

    /**
     *查询学校数据源
     */
    public function actionNewSearchSchoolInfo()
    {
        $name = Yii::$app->request->getQueryParam('name', '');
        $department = Yii::$app->request->getQueryParam('department');
        $provience = Yii::$app->request->getQueryParam('provience', '');
        $city = app()->request->getQueryParam('city', '');
        $county = app()->request->getQueryParam('county', '');
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10000;
        $schoolInfoService = new pos_SchoolInfoService();
        $pageInfo = $schoolInfoService->searchSchoolInfoByPage($name, $pages->getPage() + 1, $pages->pageSize, $department, "", "", $provience, $city, $county);

        $pageList = $pageInfo->schoolList;
        $pages->totalCount = $pageInfo->countSize;
        $pages->params['name'] = $name;
        $pages->params['department'] = $department;
        return $this->renderPartial('newSearchSchoolInfo', ['pageList' => $pageList, 'pages' => $pages]);
    }
    public function actionSearchSchoolInfo()
    {
        $name = Yii::$app->request->getQueryParam('name', '');
        $department = Yii::$app->request->getQueryParam('department');
        $provience = Yii::$app->request->getQueryParam('provience', '');
        $city = app()->request->getQueryParam('city', '');
        $county = app()->request->getQueryParam('county', '');
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10000;
        $schoolInfoService = new pos_SchoolInfoService();
        $pageInfo = $schoolInfoService->searchSchoolInfoByPage($name, $pages->getPage() + 1, $pages->pageSize, $department, "", "", $provience, $city, $county);

        $pageList = $pageInfo->schoolList;
        $pages->totalCount = $pageInfo->countSize;
        $pages->params['name'] = $name;
        $pages->params['department'] = $department;
        return $this->renderPartial('newSearchSchoolInfo', ['pageList' => $pageList, 'pages' => $pages]);
    }

    /**
     * 查询学校
     */
    public
    function  actionSearchClasses()
    {
        // $name = app()->request->getQueryParam('name', '');
        $department = app()->request->getQueryParam('department', '');
        $schoolId = app()->request->getQueryParam('schoolId', '');
        $gradeID = app()->request->getQueryParam('gradeID', '');
        if (empty($department)) {
            return $this->renderPartial('_reg_showMessage', ['err' => true, 'message' => '请选择学段']);

        }
        if (empty($schoolId)) {
            return $this->renderPartial('_reg_showMessage', ['err' => true, 'message' => '请选择学校']);

        }


        $baseInfoService = new pos_SchoolInfoService();

        $gradeList = $baseInfoService->loadGradeByschool($schoolId, $department);


        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 9;
        $schoolInfoService = new pos_ClassInfoService();
        $pageInfo = $schoolInfoService->searchClassInfoByPage($schoolId, $department, $gradeID, $pages->getPage() + 1, $pages->pageSize);
        $pageList = $pageInfo->classList;
        $pages->totalCount = $pageInfo->countSize;
        $pages->params['schoolId'] = $schoolId;
        $pages->params['gradeID'] = $gradeID;
        $pages->params['department'] = $department;
        return $this->renderPartial('searchClasses', ['pageList' => $pageList, 'pages' => $pages, 'gradeList' => $gradeList]);

    }

    /**
     *列出年级信息
     */
    public function actionNewSearchClasses()
    {
        $department = app()->request->post('department', '');
        $schoolId = app()->request->post('schoolId', '');
        $baseInfoService = new pos_SchoolInfoService();
        $gradeList = $baseInfoService->loadGradeByschool($schoolId, $department);

        return $this->renderPartial('newSearchClasses', ['gradeList' => $gradeList, 'department' => $department, 'schoolId' => $schoolId]);
    }

    /**
     *列出班级信息
     */
    public function actionNewSearchClassesInfo()
    {
        $department = app()->request->post('department', '');
        $schoolId = app()->request->post('schoolId', '');
        $gradeID = app()->request->post('gradeID', '');

        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 1000000;
        $schoolInfoService = new pos_ClassInfoService();
        $pageInfo = $schoolInfoService->searchClassInfoByPage($schoolId, $department, $gradeID, $pages->getPage() + 1, $pages->pageSize);
        $pageList = $pageInfo->classList;
        $pages->totalCount = $pageInfo->countSize;
        return $this->renderPartial('newSearchClassesInfo', ['pageList' => $pageList]);


    }

    /**
     * 查询学号
     */
    public
    function  actionSearchNumbers()
    {
        $json = new JsonMessage();
        $name = loginUser()->getModel(false)->trueName;
        $classId = app()->request->getQueryParam('classId', '');
        $classMembersService = new pos_ClassMembersService();
        $memberList = $classMembersService->loadNoRegMembers($classId, $name);
        if ($memberList) {
            $json->success = true;
            $json->data = $this->renderPartial('searchNumbers', ['memberList' => $memberList]);
        }
        return $this->renderJSON($json);
    }


    /**
     * 添加学校
     */

    public function   actionAddSchool()
    {
        $json = new JsonMessage();
        $name=  app()->request->getBodyParam('name','');
        $departmentArr = app()->request->getBodyParam('department', array());
        $provience = app()->request->getBodyParam('provience', '');
        $city = app()->request->getBodyParam('city', '');
        $county = app()->request->getBodyParam('county', '');
        $lengthOfSchooling = app()->request->getBodyParam('lengthOfSchooling', '');
        $schoolInfoService = new pos_SchoolInfoService();
        $result = $schoolInfoService->addSchoolInfo($name, implode(',', array_unique($departmentArr)), $lengthOfSchooling, '', $provience, $city, $county);

        if ($result->resCode == pos_SchoolInfoService::successCode) {
            $json->success = true;
            $json->data = $result->data->schoolID . '|' . $name;
        } else {
            $json->message = $result->resMsg;
        }
        return $this->renderJSON($json);

    }


    public function   actionAddClassInfo()
    {
        $json = new JsonMessage();
        $schoolId = app()->request->getBodyParam('schoolId', '');

        $department = app()->request->getBodyParam('department', '');

        $classNumber = app()->request->getBodyParam('classNumber', '');
        $joinYear = app()->request->getBodyParam('joinYear', '');
        $className = app()->request->getBodyParam('classesName', '');
        if ($className == '') {
            $className = $joinYear . '年' . $classNumber . '班';
        }

        $schoolInfoService = new pos_ClassInfoService();
        $userId = user()->id;
        $result = $schoolInfoService->addClassInfo($userId, $schoolId, $department, $joinYear, $classNumber, $className, '');
        if ($result->resCode == pos_ClassInfoService::successCode) {
            $json->success = true;
            $json->data = $result->data->classID . '|' . $className;
        } else {
            $json->message = $result->resMsg;
        }
        return $this->renderJSON($json);

    }

    /**
     * 查询教研组
     */
    public function  actionSearchTeachingGroup()
    {

        $department = app()->request->getQueryParam('department', '');
        $schoolId = app()->request->getQueryParam('schoolId', '');
        $subjectID = app()->request->getQueryParam('subjectID', '');

        if (empty($department)) {
            return $this->renderPartial('_reg_showMessage', ['err' => true, 'message' => '请选择学段']);

        }
        if (empty($schoolId)) {
            return $this->renderPartial('_reg_showMessage', ['err' => true, 'message' => '请选择学校']);

        }

        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 9;
        $teachingGroupService = new   pos_TeachingGroupService();

        $pageInfo = $teachingGroupService->searchTeachingGroupByPage($schoolId, $department, $subjectID, $pages->getPage() + 1, $pages->pageSize);
        $pageList = $pageInfo->teachingGroupList;

        $pages->totalCount = $pageInfo->countSize;

        $pages->params['schoolId'] = $schoolId;
        $pages->params['department'] = $department;
        $pages->params['subjectID'] = $subjectID;

        return $this->renderPartial('searchTeachingGroup', ['pages' => $pages, 'pageList' => $pageList]);


    }

    public function actionNewSearchTeachingGroup()
    {

        $department = app()->request->post('department', '');
        $schoolId = app()->request->post('schoolId', '');
        $subjectID = app()->request->post('subjectID', '');
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 9;
        $teachingGroupService = new   pos_TeachingGroupService();

        $pageInfo = $teachingGroupService->searchTeachingGroupByPage($schoolId, $department, $subjectID, $pages->getPage() + 1, $pages->pageSize);

        $pageList = $pageInfo->teachingGroupList;

        $pages->totalCount = $pageInfo->countSize;

        $pages->params['schoolId'] = $schoolId;
        $pages->params['department'] = $department;
        $pages->params['subjectID'] = $subjectID;

        return $this->renderPartial('newSearchTeachingGroup', ['subjectID' => $subjectID, 'pageList' => $pageList, 'pages' => $pages]);
    }

    /**
     *添加教研组
     */
    public function actionAddTeachingGroup()
    {
        $groupName = app()->request->getBodyParam("groupName");
        $schoolID = app()->request->getBodyParam("schoolId");
        $subjectID = app()->request->getBodyParam("subjectID");
        $department = app()->request->getBodyParam('department');

        $group = new pos_TeachingGroupService();
        $result = $group->addTeachingGroup($schoolID, $groupName, "", $subjectID, $department, "", "", "");
        $jsonResult = new JsonMessage();
        if ($result->resCode == pos_TeachingGroupService::successCode) {
            $jsonResult->success = true;
            $jsonResult->data = $result->data->schoolID . "|" . $groupName;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->code = $result->resCode;
            $jsonResult->message = $result->resMsg;
            return $this->renderJSON($jsonResult);
        }
    }

    //删除绑定关系
    public function  actionDelRelationship()
    {


        $service = new pos_PersonalInformationService();
        $obj = $service->deleteOldRelation(user()->id);
        loginUser()->getModel(false);
        if ($obj) {
            if (loginUser()->isStudent()) {
                return $this->redirect(url('register/student-find-group'));
            } elseif (loginUser()->isTeacher()) {
                return $this->redirect(url('register/teacher-find-group'));
            }
        } else {
            return $this->redirect('/');
        }
    }

    /**
     *查询班级下的学生 todo(这里接口（程序）还有点问题，回头跟进一下)
     */
    public function actionMyStuNumPop()
    {
        $jsonResult = new JsonMessage();
        $classId = app()->request->getQueryParam('classId', null);
        $classMember = new pos_ClassMembersService();
        $data = $classMember->loadRegisteredMembers($classId);
        $arrData = '';
        foreach ($data as $key => $item) {
            if ($item->identity == '20403') {
                $arrData += 1;
            }
        }
        if ($arrData >= 1) {
            $jsonResult->success = true;
            $jsonResult->data = $data;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            return $this->renderJSON($jsonResult);
        }
    }

    /**
     *同意服务协议
     */
    public function actionAgreement()
    {
        return $this->render('agreement');
    }


}
