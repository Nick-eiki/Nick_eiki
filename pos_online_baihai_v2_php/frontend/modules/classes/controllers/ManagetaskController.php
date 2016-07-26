<?php
namespace frontend\modules\classes\controllers;

use common\helper\DateTimeHelper;
use common\helper\QuestionInfoHelper;
use common\models\JsonMessage;
use common\models\pos\SeHomeworkAnswerDetailImage;
use common\models\pos\SeHomeworkAnswerImage;
use common\models\pos\SeHomeworkAnswerInfo;
use common\models\pos\SeHomeworkAnswerQuestionAll;
use common\models\pos\SeHomeworkQuestion;
use common\models\pos\SeHomeworkRel;
use common\models\pos\SeHomeworkTeacher;
use common\models\sanhai\ShTestquestion;
use common\services\JfManageService;
use frontend\components\ClassesBaseController;
use frontend\components\WebDataKey;
use frontend\services\pos\pos_HomeWorkManageService;
use Yii;
use yii\db\Exception;

/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-18
 * Time: 上午10:08
 */
class ManagetaskController extends ClassesBaseController
{
    public $layout = '@app/views/layouts/lay_new_class_v2';

    /**
     *作业详情
     */
    public function actionDetails($classId, $relId)
    {
        $this->getClassModel($classId);
        $proFirstime = microtime();
        $relHomework = SeHomeworkRel::find()->where(['id' => $relId])->one();
        if(empty($relHomework)){
            return $this->notFound("该作业已被删除！");
        }
        $homeworkID = $relHomework->homeworkId;
        $getType = $relHomework->homeWorkTeacher->getType;  //1：电子 ，0：纸质

        //作业的基本信息
        $homeworkData = SeHomeworkTeacher::find()->where(['id' => $homeworkID])->one();
        if (empty($homeworkData)) {
            $this->notFound("该作业已被删除！");
        }

        //查询学生答案
        $answerInfo = SeHomeworkAnswerInfo::find()->where(['relId' => $relId, 'studentID' => user()->id])->one();
        if (!empty($answerInfo)) {
            $homeworkAnswerID = $answerInfo->homeworkAnswerID;
            $isCheck = $answerInfo->isCheck;
            $isUploadAnswer = $answerInfo->isUploadAnswer;
            $answerImageArray = SeHomeworkAnswerDetailImage::find()->where(['homeworkAnswerID' => $homeworkAnswerID])->select('imageUrl')->column();
        } else {
            $isCheck = 0;
            $isUploadAnswer = 0;
            $answerImageArray = array();
        }

        //性能监测
        \Yii::info('写作业 '.(microtime()-$proFirstime),'service');

        //判断作业类型并且判断电子作业是否批改
        if ($getType == 1) {
            if ($isUploadAnswer) {
                return $this->eleAnswered($answerInfo,$relHomework,$homeworkData);
            } elseif ($isUploadAnswer == 0) {
                return $this->eleAnswering($relHomework, $homeworkData, $answerInfo);
            }
        } elseif ($getType == 0) {
            return $this->paperAnswering($homeworkData, $isUploadAnswer, $answerImageArray, $isCheck, $answerInfo,$relHomework);
        }

    }

    /**
     * 学生答题（电子作业）
     */
    public function eleAnswering($homeworkRel, $homeworkData,$answerInfo)
    {
        $relId = app()->request->getQueryParam("relId");
        $userId = user()->id;
        //作业推送到班级
        if (empty($homeworkRel)) {
            return $this->notFound('', 403);
        }

        //是否已经答过此作业
        $isAnswered = false;

        //作业下的题目
        $homeworkQuestionList = $homeworkData->getHomeworkQuestion()->all();
        //教师作业 补充内容的语音
        $homeworkRelAudio = $homeworkRel->audioUrl;
        //主观题，客观题
        $subjective = [];
        $objective = [];
        foreach ($homeworkQuestionList as $v) {
        //判断有没有小题
            $questionQuery = ShTestquestion::find()->where(['mainQusId' => $v->questionId]);
            $isMajorQuestion = $questionQuery->exists();
            if ($isMajorQuestion) {
                $questionResult = $questionQuery->all();
                foreach ($questionResult as $value) {
                    if ($value->isMajorQuestionCache()) {
                        array_push($subjective, $value->id);
                    } else {
                        array_push($objective, $value->id);
                    }
                }
            } else {
                $questionResult = QuestionInfoHelper::InfoCache($v->questionId);
                if(isset($questionResult)){
                    if ($questionResult->isMajorQuestionCache()) {
                        array_push($subjective, $questionResult->id);
                    } else {
                        array_push($objective, $questionResult->id);
                    }
                }
            }
        }

        //生成答题卡
        if (!$answerInfo) {
            $result = SeHomeworkAnswerInfo::createAnswerSheet($relId , $userId);
            if($result == false){return $this->notFound('答题卡创建失败');}
        }

        return $this->render("eleanswering", array(
            'homeworkData' => $homeworkData,
            'deadlineTime' => $homeworkRel->deadlineTime,
            'homeworkQuestion' => $homeworkQuestionList,
            'subjective' => $subjective,
            'objective' => $objective,
            'isAnswered' => $isAnswered,
            'homeworkRelAudio'=>$homeworkRelAudio
        ));

    }

    /**
     *AJAX完成作业(电子作业)
     */
    public function actionFinishAnswer()
    {

        $jsonResult = new JsonMessage();
        $relId = Yii::$app->request->get('relId');

        if (isset($_POST)) {

            $questionAnswer = app()->request->post('answer', '');           //客观题答案
            $pics = app()->request->getBodyParam('picurls');               //主观题答案

            $transaction = Yii::$app->db_school->beginTransaction();
            try {
                //学生作答表
                $homeworkAnswerInfo = SeHomeworkAnswerInfo::find()->where(['relId' => $relId, 'studentId' => user()->id ])->one();
                if ($homeworkAnswerInfo->isUploadAnswer == 1) {
                    $jsonResult->message = '您已答过了,不能重复答题';
                    return $this->renderJSON($jsonResult);
                }else{
                    $homeworkAnswerInfo->uploadTime = DateTimeHelper::timestampX1000();
                    $homeworkAnswerInfo->isUploadAnswer = 1;
                    $homeworkAnswerInfo->save(false);
                }
                $homeworkAnswerID = $homeworkAnswerInfo->homeworkAnswerID;

                //保存答案
                if (!empty($questionAnswer)) {

                    $homeworkAnswerAllList = SeHomeworkAnswerQuestionAll::find()->where(['homeworkAnswerID' => $homeworkAnswerID])->all();
                    foreach ($questionAnswer as $questionId => $answer) {
                        if (isset($answer['item'])) {   //有小题
                            foreach ($answer['item'] as $childQuestionId => $childAnswer) {
                                foreach ($homeworkAnswerAllList as $homeworkAnswerAll) {
                                    if ($childQuestionId == $homeworkAnswerAll->questionID) {
                                        if (is_array($childAnswer)) {
                                            $childAnswer = implode(',', $childAnswer);
                                        }
                                        $homeworkAnswerAll->answerOption = $childAnswer;
                                        $homeworkAnswerAll->save(false);
                                    }
                                }
                            }
                        } else {    //无小题
                            foreach ($homeworkAnswerAllList as $homeworkAnswerAll) {
                                if ($questionId == $homeworkAnswerAll->questionID) {
                                    if (is_array($answer)) {
                                        $answer = implode(',', $answer);
                                    }
                                    $homeworkAnswerAll->answerOption = $answer;
                                    $homeworkAnswerAll->save(false);
                                }
                            }
                        }
                    }

                }

                //作业回答图片子表（主观题）
                if ($pics != null) {
                    foreach ($pics as $pic) {
                        $homeworkAnswerImage = new SeHomeworkAnswerImage();
                        $homeworkAnswerImage->homeworkAnswerID = $homeworkAnswerInfo->homeworkAnswerID;
                        $homeworkAnswerImage->createTime = DateTimeHelper::timestampX1000();
                        $homeworkAnswerImage->url = $pic;
                        $homeworkAnswerImage->save(false);
                    }
                }

                $transaction->commit();
                $homeworkService = new pos_HomeWorkManageService();
                $homeworkService->autoHomeworkCorrectResult($homeworkAnswerID);
                $jsonResult->success = true;
                $jsonResult->message = '答题成功';

            } catch (Exception $e) {
                $transaction->rollBack();
                $jsonResult->message = '答题失败';
            }
        }
        return $this->renderJSON($jsonResult);
    }

    /**
     * 答题完毕 新
     */

    public function eleAnswered($isAnswered,$homeworkRel,$homeworkData)
    {

        $relId = app()->request->getQueryParam("relId");
        $homeworkID = $homeworkRel->homeworkId;
        //作业下的题目
        $homeworkQuestionList = SeHomeworkQuestion::find()->where(['homeworkId' => $homeworkID])->orderBy('orderNumber')->all();
        //教师作业 补充内容的语音
        $homeworkRelAudio = $homeworkRel->audioUrl;
        //答题卡中的语音
        $hworkAnCorrectAudio = $isAnswered->getHomeworkAnswerCorrectAudio()->all();
        //图片答案
        $picAnswer = $isAnswered->getHomeworkAnswerImage()->all();

        //题目ID展示
        $homeworkQuestionIdResult = SeHomeworkAnswerQuestionAll::find()->where(['studentID' => user()->id, 'relId' => $relId])->orderBy('questionId')->all();
        //主观题、客观题
        $subjective = [];
        $objective = [];
        $objectiveAnswer = [];

        foreach ($homeworkQuestionIdResult as $v) {
            $questionInfo = \common\helper\QuestionInfoHelper::InfoCache($v->questionID);
            if(isset($questionInfo)) {
                $no = $homeworkData->getQuestionNo($v->questionID);
                if ($questionInfo->isMajorQuestionCache()) {
                    $subjective[$no] = $v;
                } else {
                    $objective[$no] = $v;
                    $objectiveAnswer[$v->questionID] = $v;
                }
            }
        }
        ksort($subjective);
        ksort($objective);

        //统计
        $homeworkQuestionCorrectCount = SeHomeworkAnswerQuestionAll::find()->where(['studentID' => user()->id, 'relId' => $relId, 'correctResult' => 3])->count();

        /**
         * 梯队
         */
        $teamData = $this->teamShow($isAnswered , $homeworkData , $homeworkID);

        return $this->render("eleanswered",
            ["homeworkData" => $homeworkData,
                'deadlineTime' => $homeworkRel->deadlineTime,
                'homeworkQuestion' => $homeworkQuestionList,
                'picAnswer' => $picAnswer,
                'subjective' => $subjective,
                'objective' => $objective,
                'isAnswered' => $isAnswered,
                'objectiveAnswer' => $objectiveAnswer,
                'homeworkQuestionCorrectCount' => $homeworkQuestionCorrectCount,
                'homeworkQuestionIdResult' => $homeworkQuestionIdResult,
                'finishTotalCount'=>$teamData['finishTotalCount'],
                'overCount'=>$teamData['overCount'],
                'teamNum'=>$teamData['teamNum'],
                'homeworkRelAudio'=>$homeworkRelAudio,
                'hworkAnCorrectAudio' => $hworkAnCorrectAudio

            ]);
    }

    //梯队展示
    public function teamShow($isAnswered , $homeworkData , $homeworkID){

        $cache = Yii::$app->cache;
        $key = WebDataKey::HOMEWORK_ANSWER_TEAMDATA_SHOW . $homeworkID . '_' . user()->id;
        $data = $cache->get($key);

        if ($data === false) {
            if($isAnswered->isCheck){

                //当前用户所在的梯队
                $nowTeamNum = intval($isAnswered->correctRate);
                if($nowTeamNum == 100){
                    $teamNum =  1;
                }elseif($nowTeamNum >= 80 && $nowTeamNum < 100){
                    $teamNum =  2;
                }elseif($nowTeamNum >= 60 && $nowTeamNum < 80){
                    $teamNum =  3;
                }elseif($nowTeamNum >= 40 && $nowTeamNum < 60){
                    $teamNum =  4;
                }else{
                    $teamNum =  5;
                }

                // 全国学生答题总人数:$finishTotalCount ；当前学生前面的学生数:$overCount   $homeworkData->homeworkPlatformId > 0则来源于平台
                $finishTotalCount = 0;
                $overCount = 0;
                if($homeworkData->homeworkPlatformId > 0){
                    $homeworkTeacher = SeHomeworkTeacher::getPlatformHomeworkTeacherNum($homeworkData->homeworkPlatformId);

                    foreach($homeworkTeacher as $platform){
                        $homeworkRelAll = SeHomeworkRel::getRelData($platform->id);
                        foreach($homeworkRelAll as $rel){
                            $finishCount = SeHomeworkAnswerInfo::getFinishHomeworkTotalNum($rel->id);
                            $finishTotalCount += $finishCount;

                            $over = SeHomeworkAnswerInfo::getFinishHomeworkOverNum($rel->id,$nowTeamNum);
                            $overCount += $over;
                        }
                    }
                }else{
                    $homeworkRelAll = SeHomeworkRel::getRelData($homeworkID);
                    foreach($homeworkRelAll as $rel){
                        $finishCount = SeHomeworkAnswerInfo::getFinishHomeworkTotalNum($rel->id);
                        $finishTotalCount += $finishCount;

                        $over = SeHomeworkAnswerInfo::getFinishHomeworkOverNum($rel->id,$nowTeamNum);
                        $overCount += $over;
                    }

                }

                $teamData = [];
                $teamData['finishTotalCount'] = $finishTotalCount;          //全国答题总人数
                $teamData['overCount'] = $overCount;                        //前面的人数(正确率比当前用户高的)
                $teamData['teamNum'] = $teamNum;                            //当前所在的梯队
                $data = $teamData;
                $cache->set($key, $data, 600);

            }else {
                $teamData = [];
                $teamData['finishTotalCount'] = 0;
                $teamData['overCount'] = 0;
                $teamData['teamNum'] = 5;
                $data = $teamData;
            }

        }
        return $data;
    }

    /**
     * 学生答题（纸质作业）
     */
    public function paperAnswering($homeworkData,$isUploadedAnswer, $answerImageArray, $isCheck, $answerInfo,$homeworkRel)
    {
        //教师作业 补充内容的语音
        //答题卡中的语音
        //
        $homeworkRelAudio = $homeworkRel->audioUrl;
        return $this->render('paperanswering', ['homeworkData' => $homeworkData,
            'isUploadedAnswer' => $isUploadedAnswer,
            'answerImageArray' => $answerImageArray,
            "isCheck" => $isCheck,
            'answerInfo' => $answerInfo,
            'homeworkRelAudio' => $homeworkRelAudio,
        ]);
    }

    /**
     *纸质版AJAX上传答案
     */
    public function actionUploadAnswer()
    {
        $relId = app()->request->getBodyParam("relId");
        $image = app()->request->getBodyParam("image");
        $jsonResult = new JsonMessage();

        try {
            $transaction = Yii::$app->db_school->beginTransaction();
//        先判断是上传还是修改作业
        $answerInfoModel = SeHomeworkAnswerInfo::find()->where(['relId' => $relId, 'studentID' => user()->id])->one();
        if ($answerInfoModel == null) {
            $answerInfoModel = new SeHomeworkAnswerInfo();
            $answerInfoModel->relId = $relId;
            $answerInfoModel->getType = '0';
            $answerInfoModel->studentID = user()->id;
            $answerInfoModel->uploadTime = DateTimeHelper::timestampX1000();
            $answerInfoModel->save(false);

        }

            $homeworkAnswerID = $answerInfoModel->homeworkAnswerID;
            SeHomeworkAnswerDetailImage::deleteAll(['homeworkAnswerID' => $homeworkAnswerID]);

            $isSaved = true;
            foreach ($image as $v) {
                $imageModel = new SeHomeworkAnswerDetailImage();
                $imageModel->homeworkAnswerID = $homeworkAnswerID;
                $imageModel->imageUrl = $v;
                if (!$imageModel->save(false)) {
                    $isSaved = false;
                    break;
                }
            }
            if ($isSaved) {
                $jfHelper = new JfManageService;
                $jfHelper->myAccount("pos-finish-work", user()->id);
            }
            $transaction->commit();
            $jsonResult->success = true;
            $jsonResult->message = '答题成功';
        } catch (Exception $e) {
            $transaction->rollBack();
            $jsonResult->message = '答题失败';
        }

        return $this->renderJSON($jsonResult);
    }
}