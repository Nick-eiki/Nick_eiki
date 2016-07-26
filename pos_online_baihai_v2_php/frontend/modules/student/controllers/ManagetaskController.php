<?php
namespace frontend\modules\student\controllers;

use common\helper\DateTimeHelper;
use common\helper\QuestionInfoHelper;
use common\models\JsonMessage;
use common\models\pos\SeHomeworkAnswerDetailImage;
use common\models\pos\SeHomeworkAnswerImage;
use common\models\pos\SeHomeworkAnswerInfo;
use common\models\pos\SeHomeworkAnswerQuestionAll;
use common\models\pos\SeHomeworkAnswerQuestionPic;
use common\models\pos\SeHomeworkQuestion;
use common\models\pos\SeHomeworkRel;
use common\models\pos\SeHomeworkTeacher;
use common\models\sanhai\ShTestquestion;
use common\services\JfManageService;
use frontend\components\StudentBaseController;
use frontend\components\WebDataCache;
use frontend\services\pos\pos_HomeWorkManageService;
use Yii;
use yii\data\Pagination;
use yii\db\Exception;

/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-11-18
 * Time: 上午10:08
 */
class ManagetaskController extends StudentBaseController
{
    public $layout = "lay_user";

    /**
     * 作业列表（新加）
     */
    public function actionWorkManage()
    {

        $classId = app()->request->getQueryParam('classid');
        $subjectId = app()->request->getQueryParam('type', '');
        $pages = new Pagination();
        $pages->pageSize = 10;
        $homeworkQuery = SeHomeworkRel::find()->innerJoinWith('homeWorkTeacher')->where(['classID' => $classId]);
        $studentNum = WebDataCache::getClassStudentMember($classId);
        if ($subjectId) {
            $homeworkQuery->andWhere(['se_homework_teacher.subjectId' => $subjectId]);
        }
        $pages->totalCount = $homeworkQuery->count();
        $list = $homeworkQuery->orderBy('deadlineTime desc')->offset($pages->getOffset())->limit($pages->getLimit())->all();
        if (app()->request->isAjax) {
            return $this->renderPartial("_workmanage_list", array('pages' => $pages, 'list' => $list, 'studentNum' => $studentNum,'classId'=>$classId));
        }

        return $this->render('workmanage', array('pages' => $pages, 'list' => $list, 'studentNum' => $studentNum,'classId'=>$classId));
    }

    /**
     *作业详情
     */
    public function actionDetails()
    {
        $relId = app()->request->getQueryParam("relId");
        $relHomework = SeHomeworkRel::find()->where(['id' => $relId])->select('homeworkId,deadlineTime')->one();
        if(empty($relHomework)){
            return $this->notFound("该作业已被删除！");
        }
        $homeworkID = $relHomework->homeworkId;
        $getType = $relHomework->homeWorkTeacher->getType;
        //        查询学生答案
        $answerInfo = SeHomeworkAnswerInfo::find()->where(['relId' => $relId, 'studentID' => user()->id])->select('isCheck,homeworkAnswerID,correctLevel,isUploadAnswer')->one();
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
//        判断作业类型并且判断电子作业是否批改
        if ($getType == 1) {

            if ($isUploadAnswer) {
                return $this->newOnlineAnswered();
            } elseif ($isUploadAnswer == 0) {
                return $this->newOnlineAnswering($relHomework);
            }
        }
//        判断是否上传了答案
        $isUploadAnswer = SeHomeworkAnswerInfo::find()->where(['relId' => $relId, 'studentID' => user()->id])->exists();

        $homeworkServer = new pos_HomeWorkManageService();
        $questionResult = $homeworkServer->queryHomeworkAnswerQuestion(user()->id, $relId, "");
        $homeworkData = SeHomeworkTeacher::find()->where(['id' => $homeworkID])->one();
        if (empty($homeworkData)) {
            $this->notFound();
        }


        return $this->render('details', ['homeworkData' => $homeworkData,
            'questionResult' => $questionResult,
            'isUploadAnswer' => $isUploadAnswer,
            'answerImageArray' => $answerImageArray,
            "isCheck" => $isCheck,
            'answerInfo' => $answerInfo
        ]);
    }

    /**
     *  作业答题
     */
    public function newOnlineAnswering($homeworkRel)
    {
        $relId = app()->request->getQueryParam("relId");
        //作业推送到班级
        if (empty($homeworkRel)) {
            return $this->notFound('', 403);
        }

        //是否已经答过此作业
        $isAnswered = SeHomeworkAnswerInfo::find()->where(['relId' => $relId, 'studentID' => user()->id])->one();

        //作业信息
        $homeworkId = $homeworkRel->homeworkId;
        $homeworkResult = SeHomeworkTeacher::find()->where(['id' => $homeworkId])->one();
        //作业下的题目
        $homeworkQuestionList = $homeworkResult->getHomeworkQuestion()->all();

//        主观题，客观题
        $subjective = [];
        $objective = [];
        foreach ($homeworkQuestionList as $v) {
//            判断有没有小题
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
                if ($questionResult->isMajorQuestionCache()) {
                    array_push($subjective, $questionResult->id);
                } else {
                    array_push($objective, $questionResult->id);
                }
            }

        }
        return $this->render("newOnlineAnswers", array("homeworkResult" => $homeworkResult,
            'deadlineTime' => $homeworkRel->deadlineTime,
            'homeworkQuestion' => $homeworkQuestionList,
            'subjective' => $subjective,
            'objective' => $objective,
            'isAnswered' => $isAnswered
        ));
    }


    /**
     *组织类型的判卷
     */
    public function actionCorrectOrgPaper()
    {
        $homeworkAnswerID = app()->request->getQueryParam("homeworkAnswerID");
        $homeworkServer = new pos_HomeWorkManageService();
        $answerResult = $homeworkServer->queryHomeworkAllAnswerPicList($homeworkAnswerID);
        return $this->render("correctOrgPaper", array("answerResult" => $answerResult));
    }


    /**
     *AJAX组织的作业保存本页批改
     */
    public function actionHoldOrganizeCorrect()
    {
        $jsonResult = new JsonMessage();
        $homeworkAnswerID = app()->request->getQueryParam("homeworkAnswerID");
        $picID = app()->request->getQueryParam("picID");
        $answerID = app()->request->getQueryParam("answerID");
        $checkJson = app()->request->getQueryParam("checkInfoJson");
        $homeworkServer = new pos_HomeWorkManageService();
        $homeworkResult = $homeworkServer->commitCheckInfoForOrgPaper(user()->id, $homeworkAnswerID, $picID, $answerID, $checkJson);
        if ($homeworkResult->resCode == $homeworkServer::successCode) {
            $jsonResult->code = 1;
        } else {
            $jsonResult->code = 0;
        }
        $jsonResult->message = $homeworkResult->resMsg;
        return $this->renderJSON($jsonResult);
    }

    /**
     *上传类型的查看作业批改  2015-4-21 (修改)
     */
    public function actionViewCorrect()
    {
        $homeworkAnswerId = app()->request->getQueryParam('homeworkAnswerID', '');

        $answerInfo = SeHomeworkAnswerInfo::find()->where(['homeworkAnswerID' => $homeworkAnswerId])->one();
        $getType = $answerInfo->getType;
        if ($answerInfo == null) {
            return $this->notFound();
        }
        if ($getType == 1) {
            return $this->viewOrgCorrect();
        }
        $answerData = SeHomeworkAnswerDetailImage::find()->where(['homeworkAnswerID' => $homeworkAnswerId])->all();
        $relId = $answerInfo->relId;
        $query = SeHomeworkRel::find()->where(['id' => $relId])->one();
        $deadlineTime = $query->deadlineTime;
        $result = $query->getHomeWorkTeacher()->one();
        return $this->render("viewCorrect", array('result' => $result, 'deadlineTime' => $deadlineTime,
            'answerData' => $answerData
        ));
    }

    /**
     *查看组织类型试卷的批改
     */
    public function viewOrgCorrect()
    {
        $homeworkAnswerID = app()->request->getQueryParam("homeworkAnswerID");
        $homeworkServer = new pos_HomeWorkManageService();
        $answerResult = SeHomeworkAnswerQuestionPic::find()->where(['homeworkAnswerID' => $homeworkAnswerID])->all();
//        根据homeworkAnswerID获取homeworkID
        $answerInfo = SeHomeworkAnswerInfo::find()->where(['homeworkAnswerID' => $homeworkAnswerID])->select('relId')->one();
        $relId = $answerInfo->relId;
        $relInfo = SeHomeworkRel::find()->where(['id' => $relId])->select('homeworkId')->one();
        $homeworkId = $relInfo->homeworkId;
        $result = $homeworkServer->queryHomeworkById($homeworkId);
        $homeworkData = SeHomeworkTeacher::find()->where(['id' => $homeworkId])->one();
        return $this->render("viewOrgCorrect", array(
            "result" => $result,
            'answerResult' => $answerResult,
            'homeworkData' => $homeworkData
        ));

    }

    /**
     *  查看组织类型试卷的批改
     */
    public function viewOrganizeCorrect()
    {
        $homeworkAnswerID = app()->request->getQueryParam("homeworkanswerid");
        $homeworkServer = new pos_HomeWorkManageService();
        $homeworkResult = $homeworkServer->queryHomeworkAllAnswerPicList($homeworkAnswerID);
        if (!empty($homeworkResult)) {
            $result = $homeworkServer->queryHomeworkById($homeworkResult->homeworkId);
        }
        return $this->render("viewOrganizeCorrect", array("homeworkResult" => $homeworkResult, "result" => $result));
    }



    /**
     * 答题完毕 新
     */

    public function newOnlineAnswered()
    {

        $relId = app()->request->getQueryParam("relId");
        //是否已经答过此作业
        $isAnswered = SeHomeworkAnswerInfo::find()->where(['relId' => $relId, 'studentID' => user()->id])->one();
        //作业推送到班级
        $homeworkRel = SeHomeworkRel::find()->where(['id' => $relId])->one();
        $homeworkID = $homeworkRel->homeworkId;
        //作业信息
        $homeworkResult = SeHomeworkTeacher::find()->where(['id' => $homeworkID])->one();
        //作业下的题目
        $homeworkQuestionList = SeHomeworkQuestion::find()->where(['homeworkId' => $homeworkID])->orderBy('orderNumber')->all();

        //图片答案
        $picAnswer = $isAnswered->getHomeworkAnswerImage()->all();
//        题目ID展示
        $homeworkQuestionIdResult = SeHomeworkAnswerQuestionAll::find()->where(['studentID' => user()->id, 'relId' => $relId])->orderBy('questionId')->all();
        //主观题、客观题
        $subjective = [];
        $objective = [];


        foreach ($homeworkQuestionIdResult as $v) {
            $questionInfo = \common\helper\QuestionInfoHelper::Info($v->questionID);
            $no = $homeworkResult->getQuestionNo($v->questionID);
            if ($questionInfo->isMajorQuestionCache()) {
                $subjective[$no] = $v;
            } else {
                $objective[$no] = $v;
            }
        }
        ksort($subjective);
        ksort($objective);
        return $this->render("newOnlineAnswered",
            ["homeworkResult" => $homeworkResult,
                'deadlineTime' => $homeworkRel->deadlineTime,
                'homeworkQuestion' => $homeworkQuestionList,
                'picAnswer' => $picAnswer,
                'subjective' => $subjective,
                'objective' => $objective,
                'isAnswered' => $isAnswered
            ]);
    }


    /**
     *  答题中 作业的
     */
    public function actionOnlineAnswering()
    {
        $homeworkID = app()->request->getQueryParam("homeworkID");
        if (false) {
            //答题完毕
            $this->onlineAnswered();
        }
        $homeworkServer = new pos_HomeWorkManageService();
        $homeworkResult = $homeworkServer->queryHomeworkInfoByIDOrgType($homeworkID);
        return $this->render("onlineAnswering", array("homeworkResult" => $homeworkResult));
    }

    public function onlineAnswered()
    {
        $homeworkID = app()->request->getQueryParam("homeworkID");
        $homeworkServer = new pos_HomeWorkManageService();
        $pages = new Pagination();
        $pages->pageSize = 10;
        $homeworkResult = $homeworkServer->queryHomeworkInfoByIDOrgType($homeworkID, user()->id, "", "");
        return $this->render("onlineAnswered", array("homeworkResult" => $homeworkResult, "pages" => $pages));
    }

    /**
     *AJAX完成作业
     */
    public function actionFinishAnswer()
    {
        $jsonResult = new JsonMessage();
        $relId = Yii::$app->request->get('relId');
        if ($_POST) {

            $questionAnswer = $_POST['answer'];           //客观题答案
            $pics = app()->request->getBodyParam('picurls');               //主观题答案

            $transaction = Yii::$app->db_school->beginTransaction();
            try {
                //学生作答表
                $homeworkAnswerInfo = SeHomeworkAnswerInfo::find()->where(['relId' => $relId, 'studentId' => user()->id])->one();
                if ($homeworkAnswerInfo) {
                    $jsonResult->message = '您已答过了,不能重复答题';
                    return $this->renderJSON($jsonResult);
                }
                $homeworkAnswerInfo = new SeHomeworkAnswerInfo();
                $homeworkAnswerInfo->relId = $relId;
                $homeworkAnswerInfo->getType = 1;
                $homeworkAnswerInfo->studentID = user()->id;
                $homeworkAnswerInfo->uploadTime = DateTimeHelper::timestampX1000();
                $homeworkAnswerInfo->save(false);
                $homeworkAnswerID = $homeworkAnswerInfo->homeworkAnswerID;

                //生成答题卡
                $homeworkAnswerInfo->makeDtk();

                //保存答案
                foreach ($questionAnswer as $questionId => $answer) {

                    if (isset($answer['item'])) {
                        //有小题
                        foreach ($answer['item'] as $childQuestionId => $childAnswer) {

                            $homeworkAnswerAll = SeHomeworkAnswerQuestionAll::find()->where(['questionID' => $childQuestionId, 'homeworkAnswerID' => $homeworkAnswerID])->one();
                            if ($homeworkAnswerAll) {

                                if (is_array($childAnswer)) {
                                    $childAnswer = implode(',', $childAnswer);
                                }
                                $homeworkAnswerAll->answerOption = $childAnswer;
                                $homeworkAnswerAll->save(false);
                            }

                        }
                    } else {
                        //无小题
                        $homeworkAnswerAll = SeHomeworkAnswerQuestionAll::find()->where(['questionID' => $questionId, 'homeworkAnswerID' => $homeworkAnswerID])->one();
                        if ($homeworkAnswerAll) {
                            if (is_array($answer)) {
                                $answer = implode(',', $answer);
                            }
                            $homeworkAnswerAll->answerOption = $answer;
                            $homeworkAnswerAll->save(false);
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
     * 开始答题
     */
    public function actionOnlineBegin()
    {
        $homeworkId = app()->request->getQueryParam("homeworkID");
        $homeworkServer = new pos_HomeWorkManageService();
        $homeworkResult = $homeworkServer->queryHomeworkInfoByIDOrgType($homeworkId);

        return $this->render("onlineBegin", array("homeworkResult" => $homeworkResult));
    }


    /**
     *上传试卷详情页
     */
    public function actionUploadDetails()
    {
        $homeworkID = app()->request->getQueryParam("homeworkID");
        $homework = new pos_HomeWorkManageService();
        $result = $homework->queryHomeworkInfoByID($homeworkID);
        $answerList = $homework->queryHomeworkAnswerImages(user()->id, $homeworkID);
        return $this->render("uploadDetails", array("result" => $result, "answerList" => $answerList));
    }

    /**
     *上传的作业的判卷
     */
    public function actionCorrectPaper()
    {
        $homeworkAnswerID = app()->request->getQueryParam("homeworkAnswerID");
        $homework = new pos_HomeWorkManageService();
        $answerResult = $homework->queryHomeworkAnswerImages("", "", $homeworkAnswerID);
        return $this->render("correctPaper", array("answerResult" => $answerResult));
    }

    /**
     * 批改完成
     */
    public function actionFinishCorrect()
    {
        $homeworkAnswerID = app()->request->getQueryParam("homeworkAnswerID");
        $homework = new pos_HomeWorkManageService();
        $result = $homework->updateCheckState($homeworkAnswerID, user()->id);
        $jsonResult = new JsonMessage();
        if ($result->resCode == pos_HomeWorkManageService::successCode) {
            $jsonResult->code = 1;
        } else {
            $jsonResult->code = 0;
        }
        $jsonResult->message = $result->resMsg;
        return $this->renderJSON($jsonResult);
    }

    /**
     *AJAX上传的作业保存本页批改
     */
    public function actionHoldCorrect()
    {
        $checkInfoJson = app()->request->getQueryParam("checkInfoJson");
        $tID = app()->request->getQueryParam("tID");
        $homeworkAnswerID = app()->request->getQueryParam("homeworkAnswerID");
        $homework = new pos_HomeWorkManageService();
        $result = $homework->commitCheckInfo(user()->id, $homeworkAnswerID, $tID, $checkInfoJson);
        $jsonResult = new JsonMessage();
        if ($result->resCode == pos_HomeWorkManageService::successCode) {
            $jsonResult->code = 1;
        } else {
            $jsonResult->code = 0;
        }
        $jsonResult->message = $result->resMsg;
        return $this->renderJSON($jsonResult);
    }


    /**
     *上传答案内容的获取
     */
    public function actionUploadAnswerContent()
    {
        $homeworkID = app()->request->getBodyParam("homeworkID");
        $homework = new pos_HomeWorkManageService();
        $result = $homework->queryHomeworkInfoByID($homeworkID);
        $answerList = $homework->queryHomeworkAnswerImages(user()->id, $homeworkID);
        $this->layout = '@app/views/layouts/blank';
        return $this->render("_upload_answer_content", array("result" => $result, "answerList" => $answerList));
    }

    /**
     *AJAX上传答案
     */
    public function actionUploadAnswer()
    {
        $relId = app()->request->getBodyParam("relId");
        $image = app()->request->getBodyParam("image");
        $jsonResult = new JsonMessage();
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
            $jsonResult->success = true;
            $jsonResult->message = '成功';
        } else {
            $jsonResult->message = '失败';
        }
        return $this->renderJSON($jsonResult);
    }

    /**
     * 上传试卷预览
     */
    public function actionUploadPreview()
    {
        $homeworkID = app()->request->getQueryParam("homeworkID");
        $homework = new pos_HomeWorkManageService();
        $result = $homework->queryHomework("", "", "", "", "", $homeworkID);
        return $this->render("uploadPreview", array('result' => $result->list[0]));
    }

}