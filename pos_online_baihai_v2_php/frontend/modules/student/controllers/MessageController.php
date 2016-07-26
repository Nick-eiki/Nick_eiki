<?php
namespace frontend\modules\student\controllers;
use common\models\JsonMessage;
use frontend\components\StudentBaseController;
use frontend\services\BaseService;
use frontend\services\pos\pos_MessageSentService;
use yii\data\Pagination;

/**
 * Created by PhpStorm.
 * User: wenjianhua
 * Date: 2014/10/14
 * Time: 16:48
 */
class MessageController extends StudentBaseController
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

    /*
     * 通知
     * 我的通知列表
     */
    public function actionNotice()
    {
        $classId = loginUser()->getClassInfo()[0]->classID;
        $messageType = app()->request->getQueryParam('messagetype', '');
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 10;
        $data = new pos_MessageSentService();
        $result = $data->readerQuerySentMessageInfo(user()->id, '507', "507201", $pages->getPage() + 1, $pages->pageSize);

        $pages->totalCount = intval($result->data->countSize);
        if (app()->request->isAjax) {
            return $this->renderPartial('_notice_list', array('model' => $result->data, 'pages' => $pages, "classId"=>$classId));

        }
        return $this->render('notice', array('model' => $result->data, 'pages' => $pages, "classId"=>$classId));
    }

    //系统消息列表
    public function actionSysMsg()
    {
        $classId = loginUser()->getClassInfo()[0]->classID;
        $messageType = app()->request->getQueryParam('messagetype', null);
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 10;
        $data = new pos_MessageSentService();
        $result = $data->readerQuerySentMessageInfo(user()->id, '508', $messageType, $pages->getPage() + 1, $pages->pageSize);

        $pages->totalCount = intval($result->data->countSize);
        if (app()->request->isAjax) {
            return $this->renderPartial('_notice_list', array('model' => $result->data, 'pages' => $pages,"classId"=>$classId));

        }
        return $this->render('sysmsg', array('model' => $result->data, 'pages' => $pages, "classId"=>$classId));
    }

    //我的通知（删除一条消息）
    public function actionDeleteNotice()
    {

        $jsonResult = new JsonMessage();
        $messageID = app()->request->getQueryParam('messageID');
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

    //修改已读
    public function actionOnlyIsRead(){

        $jsonResult = new JsonMessage();
        $messageID = app()->request->post('messageid');
        $data = new pos_MessageSentService();
        $result = $data->isRead($messageID);

        if ($result->resCode == BaseService::successCode) {
            $jsonResult->success = true;
        } else {
            $jsonResult->success = false;
            $jsonResult->message = "标记失败";
        }
        return $this->renderJSON($jsonResult);
    }

    //修改是否已读状态、跳转相应的页面
    public function actionIsRead()
    {
        $messageID = app()->request->getQueryParam('messageID');
        $messageType = app()->request->getQueryParam('messageType');
        $objectID = app()->request->getQueryParam('objectID');

        //修改已读状态
        $data = new pos_MessageSentService();
        $result = $data->isRead($messageID);

        //跳转页面
        switch ($messageType) {
            //通知消息
            case 507201:    //家校联系消息（直接在消息列表显示全部）
                return $this->redirect(url('student/message/notice'));
                break;
            case 507001:    //作业消息
                return $this->redirect(url('student/managetask/details', array('relId' => $objectID)));
                break;
            case 507402:    //考试通知消息
                return $this->redirect(url('student/exam/test-detail', array('examID' => $objectID)));
                break;
            case 507202:    //个人总评消息
                return $this->redirect(url('student/exam/test-detail', array('examID' => $objectID)));
                break;
            case 507401:    //试题推送消息
                $msgId = explode(',', $objectID);
                return $this->redirect(url('student/managepaper/start-answer', array('questionTeamID' => $msgId[0], 'notesID' => $msgId[1])));
                break;


            //系统消息
            case 507203:    //单科(科目)总评消息
                return $this->redirect(url('student/exam/test-detail', array('examID' => $objectID)));
                break;
            case 507204:    //本班总评消息
                return $this->redirect(url('student/exam/test-detail', array('examID' => $objectID)));
                break;
            case 507205:    //各科成绩消息
                return $this->redirect(url('student/exam/test-detail', array('examID' => $objectID)));
                break;

            /*case 507102:    //每周一课消息
                $classInfo = loginUser()->getClassInfo()[0]->classID;
                return $this->redirect(url('classroom/' . $classInfo . '/videoList?id=' . $objectID));
                break;
            case 507103:    //家长会通知消息
                return $this->redirect(url('student/CourseStu/ParentMeeting'));
                break;
            case 507003:    //通知教师批改作业（学生没有）
                break;
            case 507101:    //直播课程消息
                return $this->redirect(url('student/CourseStu/courseDetails',array('courseId'=>$objectID)));
                break;
            case 507301:    //私信消息
                return $this->redirect(url('student/message/messageList'));
                break;*/

            default:
        }


    }

} 