<?php
namespace frontend\modules\student\controllers;
use common\models\JsonMessage;
use frontend\components\StudentBaseController;
use frontend\services\BaseService;
use frontend\services\pos\pos_ExamService;
use frontend\services\pos\pos_QuestionTeamAnswerService;
use frontend\services\pos\pos_TestManageService;
use stdClass;
use yii\data\Pagination;

/**
 * Created by ysd
 * User: Administrator
 * Date: 14-11-18
 * Time: 下午6:30
 */
class ManagepaperController extends StudentBaseController
{
    public $layout = "lay_user";

	/*
	 * 题目推送
	 * 题目推送列表
	 */
	public function actionTopicPush()
	{
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 10;
        $obj = new pos_QuestionTeamAnswerService();
        $topic_list = $obj->searchQuestionTeam(user()->id,'','','','',$pages->getPage() + 1,$pages->pageSize);
        $pages->totalCount = intval($topic_list->data->countSize);
        if (app()->request->isAjax) {
            return $this->renderPartial('_topicpush_list', array('model'=>$topic_list->data->list, 'pages' => $pages));

        }
        return $this->render('topicpush',array('model'=>$topic_list->data->list,'pages'=>$pages));
	}

    //开始答题、题目详情页
    public function actionStartAnswer(){

        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 1000;
        $questionTeamID = app()->request->getQueryParam('questionTeamID', '');
        $notesID = app()->request->getQueryParam('notesID', '');
        $obj = new  pos_QuestionTeamAnswerService();
        $res = $obj->searchQuestionTeamById($questionTeamID,$notesID,user()->id,$pages->getPage() + 1,$pages->pageSize);
        $pages->totalCount = intval($res->data->countSize);

        if (app()->request->isAjax) {
            return $this->renderPartial('_startanswer_list', array('model'=>$res->data,'pages'=>$pages));

        }
        return $this->render("startanswer",array('model'=>$res->data,'pages'=>$pages));
    }

    //答题操作
    public function actionAnswerQuestion(){
        $jsonResult = new JsonMessage();
        $questionTeamID = app()->request->get('questionTeamID', '');
        $notesID = app()->request->get('notesID', '');

        if (app()->request->isAjax && isset($_POST['qus']) ) {

             $items=[];
             $callback=function($id,$v){
             $stdclass= new stdClass();
                 $stdclass->questionId=$id;
                 $stdclass->answer=$v;
                 return $stdclass;
             };

            foreach($_POST['qus'] as $value){
                if(isset($value['answer'])){
                    $items[]= $callback($value['questionId'], implode(',',$value['answer']));
                }else{
                    $items[]= $callback($value['questionId'], '');
                }
            }
            $res_json = json_encode($items);

            $obj = new  pos_QuestionTeamAnswerService();

            $res = $obj->answerQuestionTeam($questionTeamID,$notesID,user()->id,$res_json);

            if ($res->resCode == BaseService::successCode) {
                $jsonResult->success = true;
            } else {
                $jsonResult->success = false;
            }
            return $this->renderJSON($jsonResult);
        }
    }

    //完成答题
    public function actionFinishAnswer(){
        $questionTeamID = app()->request->getQueryParam('questionTeamID', '');
        $notesID = app()->request->getQueryParam('notesID', '');
        $obj = new  pos_QuestionTeamAnswerService();
        $res = $obj->searchQuestionTeamById($questionTeamID,$notesID,user()->id,'','');

        return $this->render("finishanswer",array('model'=>$res->data));
    }

    /**
     *测验列表
     */
    public function actionTestList()
    {
        $test = new pos_TestManageService();
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 10;
        $list = $test->queryTestListByStudent(user()->id, $pages->getPage() + 1, $pages->pageSize);
        $pages->totalCount = $list->countSize;
        return $this->render("testList", array("list" => $list->list, "pages" => $pages));
    }

    /**
     *上传试卷详细页
     */
    public function actionUploadDetails()
    {
        $examID = app()->request->getQueryParam("examID");
        $test = new pos_TestManageService();
//        查询测验的试卷详情
        $testResult = $test->queryTestInfoByID($examID);
        $examServer=new pos_ExamService();
        $examSubID=$testResult->examSubID;
//        获取科目总评
        $evaluateResult=$examServer->searchSubjectEvaluate($examSubID);
//        查询测验的答案详情
        $answerResult = $test->queryTestAnswerImages(user()->id, $examID);
        return $this->render("uploadDetails", array("testResult" => $testResult,
            "answerResult" => $answerResult,
            "evaluateResult"=>$evaluateResult
        ));
    }

    /**
     *获取上传答案弹窗的内容
     */
    public function actionUploadAnswerContent()
    {
        $examID = app()->request->getQueryParam("examID");
        $test = new pos_TestManageService();
        $testResult = $test->queryTestInfoByID($examID);
        $answerResult = $test->queryTestAnswerImages(user()->id, $examID);
        $this->layout = '@app/views/layouts/blank';;
        return $this->render("_upload_answer_content", array("testResult" => $testResult, "answerResult" => $answerResult));
    }

    /**
     *AJAX上传答案
     */
    public function actionUploadPaper()
    {
        $jsonResult = new JsonMessage();
        $examID = app()->request->getBodyParam("examid");
        $imageList = app()->request->getBodyParam("imageurl");
        $test = new pos_ExamService();
        $result = $test->upload_test_AnswerImageUrl(user()->id, $examID, $imageList);
        if ($result->resCode == BaseService::successCode) {
            $jsonResult->success =true;
        }
        $jsonResult->message = $result->resMsg;
        return $this->renderJSON($jsonResult);
    }
    /**
     *测验开始答题
     */
    public function actionBeginAnswer()
    {
        $examID = app()->request->getQueryParam("examID");
        $test = new pos_TestManageService();
//        查询测验的试卷详情
        $testResult = $test->queryTestInfoByID($examID);
        return $this->render("beginAnswer",array("testResult"=>$testResult));
    }


    /**
     *学生批阅老师发给自己的其他学生上传的试卷
     */
    public function actionCorrectPaper(){
        $testAnswerID = app()->request->getQueryParam("testAnswerID");
        $testServer = new pos_TestManageService();
        $testResult = $testServer->queryTestAnswerImages("", "", $testAnswerID);
        return $this->render("correctPaper",array("testResult"=>$testResult));
    }

    /**
     *学生批阅老师发给自己的其他学生组织的试卷
     */
    public function actionCorrectOrgPaper(){
       $testAnswerID=app()->request->getQueryParam("otherTestAnswerID");
        $testServer=new pos_TestManageService();
        $testResult=$testServer->querytestAllAnswerPicList($testAnswerID);
        return $this->render("correctOrgPaper",array("testResult"=>$testResult));
    }
    /**
     *AJAX保存本页批改
     */
    public function actionHoldCorrect()
    {
        $testAnswerID = app()->request->getQueryParam("testAnswerID");
        $tID = app()->request->getQueryParam("tID");
        $checkInfoJson = app()->request->getQueryParam("checkInfoJson");
        $jsonResult = new JsonMessage();
        $testServer = new pos_TestManageService();
        $testResult = $testServer->commitCheckInfo(user()->id, $testAnswerID, $tID, $checkInfoJson);
        if ($testResult->resCode == BaseService::successCode) {
            $jsonResult->code = 1;
        } else {
            $jsonResult->code = 0;
        }
        $jsonResult->message = $testResult->resMsg;
        return $this->renderJSON($jsonResult);
    }

    /**
     *AJAX上传的试卷批改完成
     */
    public function actionFinishCorrect()
    {
        $testAnswerID = app()->request->getQueryParam("testAnswerID");
        $testScore = app()->request->getQueryParam("testScore");
        $jsonResult = new JsonMessage();
        $testServer = new pos_TestManageService();
        $testResult = $testServer->updateCheckState($testAnswerID, user()->id, 1, "", $testScore);
        if ($testResult->resCode == BaseService::successCode) {
            $jsonResult->code = 1;
            $jsonResult->data = $testResult->data;
        } else {
            $jsonResult->code = 0;
            $jsonResult->message = $testResult->resMsg;
        }

        return $this->renderJSON($jsonResult);

    }

    /**
     *组织试卷类型的保存本页批改
     */
    public function actionHoldOrgCorrect(){
        $testAnswerID=app()->request->getQueryParam("testAnswerID");
        $picID=app()->request->getQueryParam("picID");
        $answerID=app()->request->getQueryParam("answerID");
        $checkInfoJson=app()->request->getQueryParam("checkInfoJson");
        $testServer=new pos_TestManageService();
        $testResult=$testServer->commitCheckInfoForOrgPaper(user()->id,$testAnswerID,$answerID,$picID,$checkInfoJson);
        $jsonResult=new JsonMessage();
        if($testResult->resCode=BaseService::successCode){
            $jsonResult->code==1;
        }else{
            $jsonResult->code=0;
        }
        $jsonResult->message=$testResult->resMsg;
        return $this->renderJSON($jsonResult);
    }


}