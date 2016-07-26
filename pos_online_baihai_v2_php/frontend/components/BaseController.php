<?php
namespace frontend\components;

use common\controller\YiiController;
use common\models\pos\SeClassMembers;
use common\models\pos\SeGroupMembers;
use common\models\pos\SeSchoolInfo;
use frontend\components\helper\ImagePathHelper;
use frontend\services\pos\pos_PersonalInformationService;
use Yii;
use yii\helpers\Url;


/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class BaseController extends YiiController
{
    /**
     * @var null
     */
    protected $classModel = null;
    protected $schoolModel = null;
    protected $teachingGroupModel = null;

    /**
     * 通过id获取班级信息
     * @param $classId
     * @return null
     */
    public function getClassModel($classId)
    {
        $view = Yii::$app->view;

        if ($classId == null) {
            return $this->notFound();
        }

        if ($this->classModel != null) {
            $view->params['classModel'] = $this->classModel;
            return $view->params['classModel'];

        }

        //判断当前用户是否有进入所访问页面的权限
        $cache=Yii::$app->cache;
        $key = WebDataKey::USER_IS_IN_CLASS_KEY . $classId.'||'.user()->id;
        $data=$cache->get($key);
        if($data===false){
            $data=SeClassMembers::find()->where(['classID'=>$classId,'userID'=>user()->id])->one();
        }
       if($data==null){
           return $this->notFound('你没有权限查看该班级',403);
       }
        $model = \common\models\pos\SeClass::find()->where(['classID' => $classId])->one();

        if ($model != null) {
            $view->params['classModel'] = $model;
            return $this->classModel = $model;


        } else {

            return $this->notFound();
        }

    }

    /**
     * 通过id获取学校信息
     * @param $schoolId
     * @return SeSchoolInfo|null
     */
    public function getSchoolModel($schoolId)
    {

        $view = Yii::$app->view;

        if ($schoolId == null) {
            return $this->notFound();
        }

        if ($this->schoolModel != null) {
            $view->params['schoolModel'] = $this->schoolModel;
            return $this->schoolModel;
        }

        $model=  SeSchoolInfo::find()->where(['schoolID'=>$schoolId])->one();

        if ($model != null) {

            $view->params['schoolModel'] = $model;
            return $this->schoolModel = $model;
        } else {
            return $this->notFound();
        }
    }

    /**
     * 通过id获取教研组信息
     * @param $groupId
     * @return mixed|ServiceJsonResult
     */
    public function getTeachingGroupModel($groupId)
    {

        $view = Yii::$app->view;
        if ($groupId == null) {
            return $this->notFound();
        }
        //判断当前用户是否有进入所访问页面的权限
        $cache=Yii::$app->cache;
        $key = WebDataKey::USER_IS_IN_GROUP_KEY . $groupId.'||'.user()->id;
        $data=$cache->get($key);
        if($data===false){
            $data=SeGroupMembers::find()->where(['groupID'=>$groupId,'teacherID'=>user()->id])->one();
        }
        if($data==null){
            return $this->notFound('你没有权限查看该教研组',403);
        }
        if ($this->teachingGroupModel != null) {
            $view->params['teachingGroup'] = $this->teachingGroupModel;
            return $this->teachingGroupModel;
        }

        $model = \common\models\pos\SeTeachingGroup::findOne($groupId);


        if ($model != null) {
            $view->params['teachingGroup'] = $model;
            return $this->teachingGroupModel = $model;
        } else {
            return $this->notFound();
        }
    }

    /**
     * 跑到个设置主页
     */
    public function  redirectSetHome()
    {
        return $this->redirect($this->getSetHoneUrl());

    }

    /**
     * 返回个人设置主页url
     * @return string
     */
    public function   getSetHoneUrl()
    {
        if ($this->isLogin()) {
            if (loginUser()->isStudent()) {
                return Url::to('/student/setting/index');
            }
            if (loginUser()->isTeacher()) {
                return Url::to('/teacher/setting/index');
            }
        }
        return "/";
    }

    /**
     * 返回个人设置主页url
     * @return string
     */
    public function   getManageHoneUrl()
    {
        if ($this->isLogin()) {
            if (loginUser()->isStudent()) {
                return Url::to('/student/message/notice');
            }
            if (loginUser()->isTeacher()) {
                return Url::to('/teacher/message/notice');
            }
        }
        return "/";

    }


    /*
     * 获取头像
     */
    public function getUserHeader($userId)
    {
        $personalInformationService = new pos_PersonalInformationService($userId);
        return $personalInformationService->checkHeadImg($userId);
    }

    /**
     * 获取头像
     * @param $userId
     * @return string
     */
    public function getUserHeaderImage($userId)
    {
        return ImagePathHelper::getImage($this->getUserHeader($userId));
    }


    /**
     *跳转到找组织页面
     */
    public function  userRedirectFindGroup()
    {

        if ($this->isLogin()) {
            $userInfo = loginUser()->getModel();

            if ($userInfo->status1 == 0) {

                return $this->redirect(url('register/sms-verification'));
            }

            $classes = $userInfo->getClassInfo();
            switch ($userInfo->type) {
                case 0:
                    if (empty($classes)) {
                        return $this->redirect(['register/student-find-group']);

                    }
                    break;
                case 1:
                    if (empty($classes)) {
                        return $this->redirect(['register/teacher-find-group']);
                    }
                    break;
            }
        }

    }

    /**
     * 跳到班级首页
     */
    public function  userRedirectClassHome()
    {
        if ($this->isLogin()) {


            $classList = loginUser()->getModel(false)->getUserClassGroup();

            if (count($classList) > 0) {

                $classId = $classList[0]->classID;
                if (loginUser()->isTeacher()) {

                    $model = from($classList)->firstOrDefault(null, function ($v) {
                        return $v->identity == '20401';
                    });
                    if ($model != null) {
                        $classId = $model->classID;
                    }

                }

                return $this->redirect(["class/index", 'classId' => $classId]);
            }


        }
    }

    /**
     * 用户缺省跳转页
     */
    public function  userDefaultRedirectUrl()
    {

        $this->userRedirectFindGroup();
        $this->userRedirectClassHome();

    }

    /**
     * 是否是班主任
     * 返回班主任的班级信息
     * @return mixed
     */
    public function  MasterClass()
    {

        $classList = loginUser()->getModel(false)->getUserClassGroup();
        $model = from($classList)->firstOrDefault(null, function ($v) {
            return $v->identity == '20401';
        });
        return $model;
    }

    /**
     * 是否是班主任
     * 返回班主任的班级信息
     * @param $classid
     * @return mixed
     */
    public function  MasterClassByClass($classid)
    {

        $classList = loginUser()->getModel(false)->getUserClassGroup();
        $model = from($classList)->firstOrDefault(null, function ($v) use ($classid) {
            return $v->identity == '20401' && $v->classID == $classid;
        });
        return $model;
    }


}
