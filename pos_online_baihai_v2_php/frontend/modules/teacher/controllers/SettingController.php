<?php
namespace frontend\modules\teacher\controllers;

use common\models\JsonMessage;
use common\services\JfManageService;
use frontend\components\TeacherBaseController;
use frontend\models\dicmodels\LoadGradeModel;
use frontend\models\EditEmailForm;
use frontend\models\EditPasswordForm;
use frontend\services\apollo\Apollo_MaterialService;
use frontend\services\pos\pos_FavoriteFolderService;
use frontend\services\pos\pos_HomeWorkManageService;
use frontend\services\pos\pos_MessageSentService;
use frontend\services\pos\pos_PersonalInformationService;
use yii\data\Pagination;

/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-9-23
 * Time: 上午9:52
 */
class SettingController extends TeacherBaseController
{

    public $layout = "lay_user";
    //public $layout = "lay_user_new";

    public function   actionIndex()
    {

        return $this->actionPersonalCenter();

    }

    /**
     *
     * @return array
     */
    private function searchType()
    {
        $userInfo = loginUser()->getModel();
        $department = app()->request->getParam('department', $userInfo->department);
        $subject = app()->request->getParam('subjectid', $userInfo->subjectID);
        $text = app()->request->getParam('text', '');
        $type = app()->request->getParam('type', '');
        $yourType = app()->request->getParam('yourtype', 1);
        $edition = app()->request->getParam('edition', '');
        $gradeId = empty(LoadGradeModel::model()->getData($userInfo->schoolID, $department)[0]->gradeId) ? '' : LoadGradeModel::model()->getData($userInfo->schoolID, $department)[0]->gradeId;
        $grade = app()->request->getParam('grade', '');
        $hotTime = app()->request->getParam('timeorder');
        $timeorder = '';
        $hotorder = '';
        switch ($hotTime) {
            case 1:
                $timeorder = 1;
                break;
            case 2:
                $timeorder = 2;
                break;
            case 3:
                $hotorder = 1;
                break;
            case 4:
                $hotorder = 2;
                break;
            default:
                $timeorder = 2;
        }
        $userId = user()->id;
        return array($userInfo, $text, $type, $yourType, $timeorder, $hotorder, $userId, $department, $subject, $edition, $grade);
    }

    /**
     * @param $text
     * @param $type
     * @param $yourType
     * @param $timeorder
     * @param $hotorder
     * @param $userId
     * @return array
     */
    private function yourMaterial($text, $type, $yourType, $timeorder, $hotorder, $userId, $department, $subject, $edition, $grade)
    {
        $userPages = new Pagination();
        $userPages->pageSize = 3;
        $userPages->params = ['text' => $text, 'type' => $type, 'yourtype' => $yourType, 'timeorder' => $timeorder, 'hotorder' => $hotorder, 'department' => $department, 'subjectid' => $subject, 'edition' => $edition, 'grade' => $grade];
        $model = new Apollo_MaterialService();
        $userResult = $model->getSearchMaterial($text, $type, $grade, $subject, $edition, $userId, '', '', $department, 0, $timeorder, $hotorder, $userPages->getPage() + 1, $userPages->pageSize);
        $userPages->totalCount = intval($userResult->countSize);
        return array($userPages, $model, $userResult);
    }

    /**
     * @param $text
     * @param $type
     * @param $yourType
     * @param $timeorder
     * @param $hotorder
     * @param $userId
     * @return array
     */
    private function collectionMaterial($text, $type, $yourType, $timeorder, $hotorder, $userId, $department, $subject, $edition, $grade)
    {
        $collectionPages = new Pagination();
        $collectionPages->pageSize = 3;
        $collectionPages->params = ['text' => $text, 'type' => $type, 'yourtype' => $yourType, 'timeorder' => $timeorder, 'hotorder' => $hotorder, 'department' => $department, 'subjectid' => $subject, 'edition' => $edition, 'grade' => $grade];
        $collection = new pos_FavoriteFolderService();
        $favorites = $collection->queryFavoriteFolder($userId, $type, $collectionPages->getPage() + 1, $collectionPages->pageSize);
        $collectionPages->totalCount = intval($favorites->countSize);
        return array($collectionPages, $collection, $favorites);
    }

    /**
     * 个人中心的作业部分刷新
     */
    public function actionHomework()
    {
        //教师作业列表
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 3;
        $classInfo = loginUser()->getClassInfo();
        $classID = app()->request->getParam("classid", $classInfo[0]->classID);
        $homework = new pos_HomeWorkManageService();
        $homeworkResult = $homework->queryHomework('', $classID, user()->id, $pages->getPage() + 1, $pages->pageSize);
        return $this->renderPartial('_task_view', array('homeworkResult' => $homeworkResult->list));
    }

    public function actionPersonalCenter()
    {
        $this->layout = "lay_user_new";
        $userId = user()->id;

        //获取用户所在学校
        $userModel = loginUser();
        $schoolId = $userModel->schoolID;
        $schoolModel = $this->getSchoolModel($schoolId);
        //获取用户所在班级
        $classArr = $userModel->getClassInfo();

        //获取用户所在教研组
        $teaGroup = $userModel->getGroupInfo();

        //总积分和可用积分和今日积分
        $jfManageHelperModel = new JfManageService();
        $userScore = $jfManageHelperModel->UserScore($userId);
        $points = $userScore->points;
        $totalPonits = $userScore->totalPoints;
        $todayPonits = $jfManageHelperModel->UserDayScore($userId);
        $gradePonits = $jfManageHelperModel->JfGrade($userId);
        return $this->render('teacher_home', [
            'classArr' => $classArr,
            'teaGroup' => $teaGroup,
            'schoolModel' => $schoolModel,
            'points' => $points,
            'totalPoints' => $totalPonits,
            'todayPoints' => $todayPonits,
            'gradePonits'=>$gradePonits
        ]);
    }


    public function actionGetMessages()
    {
        $userId = app()->request->get('userId');

        //系统消息3条
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 3;
        $data = new pos_MessageSentService();
        $sysResult = $data->readerQuerySentMessageInfo($userId, 508, '', $pages->getPage() + 1, $pages->pageSize);

        return $this->renderPartial('teacher_message', ['sysResult' => $sysResult->data->list]);


    }




    //搜索用户所在的班级
    public function actionUserClass()
    {
        $userClass = loginUser()->getClassInfo();

        return $this->renderPartial('_class_list', array('userClass' => $userClass));
    }

    /**
     *修改邮箱
     */
    public function actionSetEmail()
    {
        $this->layout = "lay_user_info";
        $model = new EditEmailForm();
        if ($_POST) {
            $model->attributes = $_POST["EditEmailForm"];
            $afterEmail = $model->afterEmail;
            $emailUrl = explode("@", $afterEmail);
            $emailUrl = $emailUrl[1];
            $model->email = loginUser()->getEmail();
            if ($model->validate()) {
                //发送邮箱验证
                $this->sendActiveEmail($model->afterEmail);
                Yii::$app->user->logout();
                return $this->redirect("http://mail." . $emailUrl);
            }

        }
        return $this->render("setEmail", array("model" => $model));
    }


    /**
     *修改密码
     */
    public function actionChangePassword()
    {
        $model = new EditPasswordForm();
        if ($_POST) {
            $model->attributes = $_POST['EditPasswordForm'];
            $model->userId = user()->id;
            if ($model->validate()) {
                // Yii::$app->user->logout();
                // return $this->redirect(Yii::$app->homeUrl);
                app()->getSession()->setFlash('success', '密码修改成功！');
                return $this->redirect(['change-password']);
            }
        }
        return $this->render("//publicView/setting/changePassword", array("model" => $model));
    }

    /**
     *修改头像
     */

    public function actionSetHeadPic()
    {
        return $this->render("//publicView/setting/setHeadPic");
    }

    /**
     * AJAX修改头像
     */
    public function actionUpdateHeadPic()
    {
        $student = new pos_PersonalInformationService();
        $result = $student->updateHeadImg(user()->id, $_POST["headImgUrl"]);
        loginUser()->getModel(false);
        $jsonResult = new JsonMessage();
        $jsonResult->success = $result;
        return $this->renderJSON($jsonResult);
    }


}