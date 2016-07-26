<?php
namespace frontend\modules\teacher\controllers;

use common\models\JsonMessage;
use common\models\pos\SeHomeworkRel;
use frontend\components\TeacherBaseController;
use frontend\modules\teacher\models\HomeContactForm;
use frontend\services\BaseService;
use frontend\services\pos\pos_ClassMembersService;
use frontend\services\pos\pos_MessageSentService;
use frontend\services\pos\pos_SchlHomMsgService;
use frontend\services\pos\pos_SchoolTeacherService;
use yii\data\Pagination;

/**
 * Created by PhpStorm.
 * User: wenjianhua
 * Date: 2014/10/14
 * Time: 16:48
 */
class MessageController extends TeacherBaseController
{

    public function actions()
    {
        //私信部分
        return ['message-list' => [
            'class' => 'frontend\controllers\message_box\MessageListAction'
        ],
            'view-message' => [
                'class' => 'frontend\controllers\message_box\ViewMessageAction'
            ]
        ];
    }

    public $layout = "lay_user";


    public function  actionIndex()
    {
        return $this->redirect(['notice']);
    }

    /**
     *家校联系
     */
    public function actionMsgContact()
    {
        $proFirstime = microtime();
        $type = app()->request->getParam('type', '');
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10;
        $userId = user()->id;

        $model = new pos_SchlHomMsgService();
        $modelList = $model->querySchlHomMsg('', $userId, $type, 1 + $pages->getPage(), $pages->pageSize, '');
        $pages->totalCount = intval($modelList->countSize);
        if (app()->request->isAjax) {
            return $this->renderPartial('_new_list_view', array('modelList' => $modelList->list, 'pages' => $pages));
        }
        $pages->params = ['type' => $type];

        \Yii::info('教师通知 '.(microtime()-$proFirstime),'service');
        return $this->render("msgContact", array('modelList' => $modelList->list, 'pages' => $pages));
    }

    /**
     *高：2014.10.24
     * 添加信息
     */
    public function actionAddContact()
    {
        $jsonResult = new JsonMessage();
        $userId = user()->id;
        $contact = new HomeContactForm();
        if (isset($_POST['HomeContactForm'])) {
            $contact->attributes = $_POST['HomeContactForm'];
            $url = '';
            if (!empty($contact->urls)) {
                $url = implode(',', $contact->urls);
            }
            $receiverType = implode(',', $contact->receiverType);
            $sendWay = implode(',', $contact->sendWay);
            $arr = array();
            $student = array();
            $rankJson = null;
            $receiverJson = null;
            if (!empty($_POST['line'])) {
                foreach ($_POST['line'] as $v) {
                    array_push($arr, $v);
                }
                $rankJson = json_encode(array("ranks" => $arr));
            }
            if (!empty($_POST['receiver'])) {
                foreach ($_POST['receiver'] as $item) {
                    array_push($student, ["userId" => $item]);
                }

                $receiverJson = json_encode($student);

            }
            if ($contact->scope == 0 && $receiverJson == "") {
                $jsonResult->message = '请选择学生';
                return $this->renderJSON($jsonResult);
            }
            $createSchlHomMsg = new pos_SchlHomMsgService();
            $addmodel = $createSchlHomMsg->createSchlHomMsg($contact->title, $contact->classId, $contact->scope, $contact->examId, $receiverJson, $receiverType, $sendWay, $contact->rankingChg, $rankJson, $contact->weakPoint, $contact->addContent, $userId, $contact->reference, $contact->subjectId, $contact->kids, $url, '');
            if ($addmodel->resCode == BaseService::successCode) {
                if ($_POST['sendType'] == 'send') {
                    $result = $this->sendHomMsg($addmodel->data->messageId);
                    if (!isset($result)) {
                        $jsonResult->success = false;
                        return $this->renderJSON($jsonResult);
                        return;
                    }
                }
                $jsonResult->success = true;
                return $this->renderJSON($jsonResult);
            } else {
                $jsonResult->success = false;
                $jsonResult->message = '添加失败';
                return $this->renderJSON($jsonResult);
            }
        }
    }

    /**
     * 高：2014.10.14
     * 获取班级和班级全部同学
     */
    public function actionGetClass()
    {
        $classId = app()->request->getParam('classId', 0);
        $class = new pos_ClassMembersService();
        $loadModel = $class->loadRegisteredMembers($classId, 1, '');
        return $this->renderPartial('_getClass_view', array('loadModel' => $loadModel));
    }

    public function actionNewGetClass()
    {
        $classId = app()->request->getParam('classId', 0);
        $class = new pos_ClassMembersService();
        $loadModel = $class->loadRegisteredMembers($classId, 1, '');
        return $this->renderPartial('_new_getClass_view', array('loadModel' => $loadModel));
    }

    /**
     *高：2014.10.28
     * 查询教师的班级
     */
    public function actionGetHomMsg()
    {
        $id = app()->request->getParam('id', 0);
        $userId = user()->id;
        $homMsg = new pos_SchlHomMsgService();
        $homMsgSearch = $homMsg->seachHomMsg($id);
        if ($homMsgSearch !== null) {
            $teacher = new pos_SchoolTeacherService();
            $schoolClass = $teacher->searchTeacherClass($userId);
            $teacherClass = new pos_SchoolTeacherService();
            $result = $teacherClass->teacherClass($userId, $homMsgSearch->classId);
            $data = new pos_SchlHomMsgService();
            $maxMinScore = $data->getMaxMinScore($homMsgSearch->examId);
            if ($maxMinScore != null) {
                if ($maxMinScore->min == null || $maxMinScore->max == null) {
                    $maxMinScore->min = 10;
                    $maxMinScore->max = 98;
                }
            }
            if (!empty($schoolClass)) {
                $this->layout = '@app/views/layouts/blank';;
                return $this->render('_edit_message_view', array('model' => $homMsgSearch, 'userId' => $userId, 'schoolClass' => $schoolClass->classList, 'id' => $id, 'result' => $result, 'maxMinScore' => $maxMinScore));
            }
        }
    }

    /**
     * 高：2014.10.28
     * 修改短信信息
     *
     */
    public function actionEditContact()
    {
        $jsonResult = new JsonMessage();
        $contact = new HomeContactForm();
        if (isset($_POST['HomeContactForm'])) {
            $contact->attributes = $_POST['HomeContactForm'];
            $newUrl = '';
            if (!empty($contact->urls)) {
                $newUrl = implode(',', $contact->urls);
            }
            $receiverType = implode(',', $contact->receiverType);
            $sendWay = implode(',', $contact->sendWay);
            $arr = array();
            $student = array();
            $rankJson = null;
            $receiverJson = null;
            if (!empty($_POST['line'])) {
                foreach ($_POST['line'] as $v) {
                    array_push($arr, $v);
                }
                $rankJson = json_encode(array("ranks" => $arr));
            }

            if (!empty($_POST['receiver'])) {
                foreach ($_POST['receiver'] as $item) {
                    array_push($student, ["userId" => $item]);
                }
                $receiverJson = json_encode($student);
            }

            if ($contact->scope == 0 && $receiverJson == "") {
                $jsonResult->message = '请选择学生';
                return $this->renderJSON($jsonResult);
            }

            if ($contact->reference == 1) {
                $contact->subjectId = '';
                $contact->kids = '';
            } elseif ($contact->reference == 2) {
                $contact->examId = '';
                $contact->weakPoint = '';
                $contact->rankingChg = '';
                $rankJson = null;

            } elseif ($contact->reference == 3 || $contact->reference == 4) {
                $contact->examId = '';
                $contact->weakPoint = '';
                $contact->rankingChg = '';
                $rankJson = null;
                $contact->subjectId = '';
                $contact->kids = '';
            }

            $schlHomMsg = new pos_SchlHomMsgService();
            $editmodel = $schlHomMsg->updateSchlHomMsg($contact->id, $contact->title, $contact->examId, $contact->classId, $contact->scope, $receiverJson, $receiverType, $sendWay, $contact->rankingChg, $rankJson, $contact->weakPoint, $contact->addContent, $contact->reference, $contact->subjectId, $contact->kids, $newUrl, '');

            if ($editmodel->resCode == BaseService::successCode) {
                $jsonResult->success = true;
                return $this->renderJSON($jsonResult);
            } else {
                $jsonResult->success = false;
                $jsonResult->message = '修改失败';
                return $this->renderJSON($jsonResult);
            }
        }
    }

    /**
     *高：2014.10.28
     * 删除短信
     */
    public function actionDelHomMsg()
    {
        $id = app()->request->getParam('id', 0);
        $schlHomMsg = new pos_SchlHomMsgService();
        $del = $schlHomMsg->delSchlHomMsg($id);
        $jsonResult = new JsonMessage();
        if ($del->resCode == BaseService::successCode) {
            $jsonResult->success = true;
            return $this->renderJSON($jsonResult);
        } else {
            $jsonResult->success = false;
            $jsonResult->message = '删除失败';
            return $this->renderJSON($jsonResult);
        }
    }

    /**
     *高：2014.10.28
     * 发送信息
     */
    public function actionSendHomMsg()
    {
        $id = app()->request->getParam('id', 0);

        $jsonResult = new JsonMessage();
        $result = $this->sendHomMsg($id);
        if (isset($result)) {
            $jsonResult->success = true;
        } else {
            $jsonResult->success = false;
            $jsonResult->data = '发送错误';
        }
        return $this->renderJSON($jsonResult);

    }

    /**
     *高：2014.10.28
     * 发送信息
     */
    protected function sendHomMsg($id)
    {
        $schlHomMsg = new pos_SchlHomMsgService();
        $send = $schlHomMsg->sendSchlHomMsg($id);
        if ($send == null) {
            return false;
        }
        return true;
    }

    /**
     * 系统消息列表
     * @return string
     */
    public function actionNotice()
    {
        $proFirstime = microtime();
        $messageType = app()->request->getParam('messagetype', '');
        $pages = new Pagination();
        $pages->validatePage = false;
        $pages->pageSize = 10;
        $data = new pos_MessageSentService();
        $result = $data->readerQuerySentMessageInfo(user()->id, 508, $messageType, $pages->getPage() + 1, $pages->pageSize);

        $pages->totalCount = intval($result->data->countSize);

        \Yii::info('教师系统消息 '.(microtime()-$proFirstime),'service');
        if (app()->request->isAjax) {
            return $this->renderPartial('_notice_list', array('model' => $result->data, 'pages' => $pages));

        }
        return $this->render('notice', array('model' => $result->data, 'pages' => $pages));
    }

    //我的通知（删除一条消息）
    public function actionDeleteNotice()
    {

        $jsonResult = new JsonMessage();
        $messageID = app()->request->getParam('messageID');
        $data = new pos_MessageSentService();
        $result = $data->readerMessageDelet($messageID);
        if ($result->resCode == BaseService::successCode) {
            $jsonResult->success = true;
            $jsonResult->data = $result->data;
        } else {
            $jsonResult->success = false;
            $jsonResult->message = "删除失败";
        }
        return $this->renderJSON($jsonResult);

    }

    //修改是否已读状态、跳转相应的页面
    public function actionIsRead()
    {
        $messageID = app()->request->getParam('messageID');
        $messageType = app()->request->getParam('messageType');
        $objectID = app()->request->getParam('objectID');

        //修改已读状态
        $data = new pos_MessageSentService();
        $result = $data->isRead($messageID);

        //跳转页面
        switch ($messageType) {
            case 507003: //通知教师批改作业（学生提交作业）
                $classInfo = SeHomeworkRel::find()->where(['id'=>$objectID])->one();
                if($classInfo){
                    $classId = $classInfo->classID;
                }else{
                    $this->notFound("该作业已被删除！");
                }
                return $this->redirect(url('/class/work-detail', array('classhworkid' => $objectID,'classId'=>$classId)));
                break;
            case 507004: //通知教师判卷（提交考试答案）
                return $this->redirect(url('teacher/exam/subject-details', array('examSubID' => $objectID)));
                break;
            case 507403: //试题推送消息
                return $this->redirect(url('teacher/managepaper/topic-push-result', array('questionTeamID' => $objectID)));
                break;
            case 507404: //完善试卷
                return $this->redirect(url('teacher/exam/subject-details', array('examSubID' => $objectID)));
                break;
            case 507005: //查看推送作业
                return $this->redirect(url('teacher/managetask/pushed-library-details', array('homeworkID' => $objectID)));
                break;

            /*case 507203: //单科总评消息(无)
                break;
            case 507001: //作业消息（教师没有）
                break;
            case 507002: //测验消息(已删除)
                break;
            case 507003: //通知教师批改作业
                return $this->redirect(url('teacher/managetask/uploadworkdetails', array('homeworkID' => $objectID)));
                break;
            case 507101: //直播课程消息
                return $this->redirect(url('teacher/coursemanage/courseDetails', array('courseId' => $objectID)));
                break;
            case 507102: //每周一课消息
                $classInfo = user()->getClassInfo()[0]->classID;
                return $this->redirect(url('classroom/' . $classInfo . '/videoList?id=' . $objectID));
                break;
            case 507103: //家长会通知消息
                return $this->redirect(url('teacher/coursemanage/parentmeeting'));
                break;
            case 507201: //家校联系消息（无）
                break;
            case 507202: //个人总评消息(无)
                break;
            case 507301: //私信消息
                return $this->redirect(url('teacher/message/messageList'));
                break;
            case 507402: //考试通知消息
                return $this->redirect(url('teacher/exam/manage'));
                break;
            */
            default:
        }


    }

    /**
     *根据班级id和用户id 查询科目
     */
    public function actionGetSubjectData()
    {
        $classId = app()->request->getParam('id', '');
        $userId = user()->id;
        $teacherClass = new pos_SchoolTeacherService();
        $result = $teacherClass->teacherClass($userId, $classId);
        if (!empty($result->classList)) {
            return $this->renderPartial('_subject_view', array('result' => $result->classList, 'subjectId' => ''));

        }
    }

    /**
     *请求弹窗
     */
    public function actionNewOpenDiaiog()
    {
        $schoolClassArr = [];
        $userId = user()->id;
        $teacher = new pos_SchoolTeacherService();
        $schoolClass = $teacher->searchTeacherClass($userId, '');
        foreach ($schoolClass->classList as $key => $val) {
            if ($val->identity == '20401' || $val->identity == '20402') {
                $schoolClassArr[] = $val;
            }
        }
        return $this->renderPartial('_new_openDialog_view', array('schoolClass' => $schoolClassArr));
    }

    /**
     *查询最高分和最低分
     */
    public function actionScores()
    {
        $jsonResult = new JsonMessage();
        $examId = app()->request->getParam('examId', 0);
        $data = new pos_SchlHomMsgService();
        $result = $data->getMaxMinScore($examId);
        if ($result !== null) {
            if ($result->min == null || $result->max == null) {
                $result->min = 10;
                $result->max = 98;
            }
            $jsonResult->success = true;
            $jsonResult->data = $result;
        } else {
            $jsonResult->success = false;
            $jsonResult->message = "請求失败";
        }

        return $this->renderJSON($jsonResult);

    }

}