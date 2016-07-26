<?php
namespace console\controllers;
use common\services\HomeworkPushService;
use common\models\pos\SeClassMembers;
use common\models\pos\SeHomeworkAnswerInfo;
use common\models\pos\SeHomeworkRel;
use mithun\queue\controllers\WorkerController;
use yii\helpers\Console;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/15
 * Time: 11:22
 */
  class QueueHomeworkUrgeController extends  WorkerController{
      /**
       * 队列名称
       */
      const  QUEUE_TYPE = 'queue_urge_home_work';

      /**
       *监听队列是否有催作业消息过来
       */
      public function actionListen(){
         for($i=0;$i<5;$i++) {
             $queue = \yii::$app->queue;
             //弹出消息
             $message = $queue->pop(self::QUEUE_TYPE);

             if ($message !== false) {
                 $id = $message['body'];
                 $this->stdout("id:" . $id . "\r\n", Console::FG_YELLOW);
                 try {
                     $this->sendMsgToStudent($id);
//                     删除消息
                     $queue->delete($message);
                 } catch (\Exception $e) {

                 }
             }
         }
      }
      public function actionPushTest($id){
          $queue = \yii::$app->queue;
          $queue->push($id, self::QUEUE_TYPE);
      }

      /**
       * @param $id
       * 给未交作业的学生发消息
       */
      public function sendMsgToStudent($id)
      {
          $homeworkResult = SeHomeworkRel::find()->where(['id' => $id])->one();
          if ($homeworkResult != null) {
              $classID = $homeworkResult->classID;
              $homeworkName = $homeworkResult->homeWorkTeacher->name;
              $answerResult = SeHomeworkAnswerInfo::find()->where(['relId' => $id])->all();
              $answeredMemberArray = [];
              foreach ($answerResult as $v) {
                  array_push($answeredMemberArray, $v->studentID);
              }
              $memberResult = SeClassMembers::find()->where(['classID' => $classID, 'identity' => '20403'])->andWhere(['not in', 'userID', $answeredMemberArray])->all();
              $memberArray = [];
              foreach ($memberResult as $v) {
                  array_push($memberArray, $v->userID);
              }
              $pushUserId = 100;
              $mainType = '508';//系统消息
              $messageType = '507001'; //推送作业类型
              $obejectID = $id;
              $messageContent = '老师催你交作业啦！《' . $homeworkName . '》';
              $homeworkPush = new  HomeworkPushService();
              foreach ($memberArray as $v) {
                  $homeworkPush->Push($pushUserId, $v, $mainType, $messageType, $obejectID, '', $messageContent);
              }
          }
      }

  }