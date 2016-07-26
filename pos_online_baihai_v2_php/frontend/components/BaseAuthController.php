<?php

namespace frontend\components;

use common\behaviors\BehaviorsTracking;
use frontend\services\pos\pos_PersonalInformationService;
use yii\filters\AccessControl;

/**
 *  学校权限基类 is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class BaseAuthController extends BaseController
{


    public function filterAccessControl()
    {
        if ($this->isLogin()) {
            $userInfo = loginUser()->getModel();

            if ($userInfo->status1 == 0) {

                return $this->redirect(url('register/sms-verification'));
            }

            switch ($userInfo->type) {

                case 0:
                    if (!isset($userInfo->schoolID) || empty($userInfo->schoolID)) {
                        return $this->redirect(url( 'register/student-find-group'));

                    }
                    break;
                case 1:
                    if (!isset($userInfo->schoolID) || empty($userInfo->schoolID)) {
                        return $this->redirect(url( 'register/teacher-find-group'));
                    }
                    break;
            }
        }


    }


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
//                    [
//                        'allow' => false,
//                        'roles' => ['*'],
//                    ],
                    [

                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $this->filterAccessControl();
                            return true;
                        }
                    ],
                ],
            ],
            'behaviors' => [
                'class' => BehaviorsTracking::className()
            ]
        ];
    }


    /**
     * 判断其他老师和当前用户是否在同一个教研组
     * @param $otherUserID
     * @return array
     */
    public function isSameGroup($otherUserID)
    {
        $personServer = new pos_PersonalInformationService();
        $personResult = $personServer->querySameGroupByTwoUser(user()->id, $otherUserID);
        if ($personResult->data["groupListSize"] > 0) {
            return $personResult->data["groupList"];
        } else {
            return array();
        }
    }

    /**
     * 判断其他老师和当前用户是否在同一个班级
     * @param $otherUserID
     * @return array
     */
    public function isSameClass($otherUserID)
    {
        $personServer = new pos_PersonalInformationService();
        $personResult = $personServer->querySameClassByTwoUser(user()->id, $otherUserID);
        if ($personResult->data["classListSize"] > 0) {
            return $personResult->data["classList"];
        }
        return array();

    }


}