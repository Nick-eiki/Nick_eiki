<?php
namespace frontend\modules\student\controllers;
use common\models\JsonMessage;
use frontend\components\StudentBaseController;
use frontend\models\dicmodels\ExamTypeModel;
use frontend\models\dicmodels\KnowledgePointModel;
use frontend\services\pos\pos_ExamService;
use frontend\services\pos\pos_PaperManageService;
use frontend\services\pos\pos_SuperManageService;
use frontend\services\pos\pos_TestManageService;
use Yii;
use yii\data\Pagination;

/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-10-30
 * Time: 下午5:44
 */
class ExamController extends StudentBaseController
{
    public $layout = "lay_user";


    /**
     *考试管理
     */
    public function actionManage()
    {
        $type = Yii::$app->request->getParam('type', '');
        $classID = Yii::$app->request->getParam('classid','');
        $exam = new pos_ExamService();
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 10;
        $examResult = $exam->queryExamListByStudent($classID, user()->id, $pages->getPage() + 1, $pages->pageSize, $type);
        $pages->totalCount = $examResult->countSize;
        if (app()->request->isAjax) {
            return $this->renderPartial('_exam_list', ["examList" => $examResult->examList, "pages" => $pages]);

        }

        $examTypeArr = ExamTypeModel::model()->getListData();

        return $this->render("manage", ["examList" => $examResult->examList, "pages" => $pages, 'examTypeArr' => $examTypeArr]);
    }

    /**
     *上传类型的试卷预览
     */
    public function actionPaperPreview()
    {
        $paperID = app()->request->getQueryParam("paperID");
        $server = new pos_ExamService();
        $result = $server->queryPaperByIDOrgType("", $paperID);
        return $this->render("paperPreview", array("result" => $result));
    }

    /**
     *上传试卷内容
     */
    public function actionPaperContent()
    {
        $examSubID = app()->request->getQueryParam("examSubID");
        $exam = new pos_ExamService();
        $result = $exam->searchExamPaperByStudent($examSubID, user()->id);
        $this->layout = '@app/views/layouts/blank';
        return $this->render("_paper_content", array("data" => $result->data));
    }

    /**
     *学生上传试卷
     */
    public function actionUploadPaper()
    {
        // 图片 url 逗号 分隔
        $url = app()->request->getBodyParam("url");
        //学科考试ID
        $examSubID = app()->request->getBodyParam("examsubid");
        $exam = new pos_ExamService();
        $result = $exam->uploadPaperImg(user()->id, $examSubID, $url);
        $jsonResult = new JsonMessage();
        if ($result->resCode == pos_ExamService::successCode) {
            $jsonResult->success=true;
        }
        $jsonResult->message = $result->resMsg;
        return $this->renderJSON($jsonResult);
    }

    /**
     *   考试详情页
     */
    public function actionTestDetail()
    {
        $examID = $_GET["examID"];
        $exam = new pos_ExamService();
         $pos_SuperManageService = new    pos_SuperManageService();
        $fullResult=$exam->queryExamFullScore($examID);
        $fullScore=$fullResult->fullScore;
        $scopreTotal = $pos_SuperManageService->classScoreTotalPeo($examID, json_encode([["low" => 0, "high" => $fullScore*0.4], ["low" =>($fullScore*0.4+1), "high" => $fullScore*0.6], ["low" => ($fullScore*0.6+1), "high" => $fullScore*0.8], ["low" => ($fullScore*0.8+1), "high" => $fullScore]]));
//       查询本次考试的本班总评
        $evaluate = $exam->searchClassEvaluate($examID);
        //      查询考试的最高分和最低分

        $minAndMax = $exam->searchTheMaxAndMinScoreExam($examID);
//       查询考试的分数区间
        $intervalArray = [["bottomlimit" => "100", "toplimit" => "500"], ["bottomlimit" => "501", "toplimit" => "800"], ["bottomlimit" => "801", "toplimit" => "9000"]];
        $interval = json_encode($intervalArray);
        $scoreSection = $exam->queryNumberByIntervalExam($examID, $intervalArray);
//        查询本次考试各个科目的分数
        $score = $exam->searchStudentScore($examID, user()->id);
//      查询学生评价
        $studentEvaluate = $exam->searchStudentEvaluate($examID, user()->id, "");
//        查询本次考试的各个科目及试卷信息
        $subjectList = $exam->queryExamByIdByStudent($examID, user()->id);
        foreach ($subjectList->examSubList  as $v) {
            $intervalArray = array("data" => array(array("bottomlimit" => "70", "toplimit" => "80"), array("bottomlimit" => "81", "toplimit" => "90"), array("bottomlimit" => "91", "toplimit" => "100")));
            $interval = json_encode($intervalArray);
//        把各个科目的分数区间放到各个科目数组中
            $subjectScoreSection = $exam->queryNumberByInterval($v->examSubID, user()->id, $interval);
            $v->subjectScoreSection = $subjectScoreSection->data->socreList;
//         把科目总评拼到科目数组中
            $subjectEvaluate = $exam->searchSubjectEvaluate($v->examSubID);
            $v->subjectEvaluate = $subjectEvaluate;
        }
//        考试的成绩变动
       $rankChange=$exam->getRankChange($examID,user()->id);
        return $this->render("testDetail",
            ["score" => $score->data,
            "evaluate" => $evaluate->data,
            "minAndMax" => $minAndMax,
            "scoreSection" => $scoreSection,
            "studentEvaluate" => $studentEvaluate->data,
            "scopreTotal"=>$scopreTotal,
            "subjectList" => $subjectList,
            "rankChange"=>$rankChange]);
    }

    /**
     *上传类型的学生试卷预览
     */
    public function actionStudentPaperPreview()
    {
        $examSubID = app()->request->getQueryParam("examSubID");
        $exam = new pos_ExamService();
        $result = $exam->searchExamPaper($examSubID);
        return $this->render("studentPaperPreview", array("result" => $result->data));
    }

    /**
     *组织类型的试卷预览
     */
    public function actionOrgPreview()
    {
        $examSubID = app()->request->getQueryParam("examSubID");
        $testServer = new pos_ExamService();
        $result = $testServer->queryPaperByIDOrgType($examSubID);
        return $this->render("orgPreview", array("result" => $result));
    }


    /**
     *查看老师试卷信息
     */
    public function actionTeacherPaperPreview()
    {
        $examSubID = app()->request->getQueryParam("examSubID");
        $exam = new pos_ExamService();
        $result = $exam->searchExamPaper($examSubID);
        return $this->render("teacherPaperPreview", array("result" => $result->data));
    }
    /**
     *上传类型的查看批改
     */
    public function actionViewCorrect()
    {
        $testAnswerID=app()->request->getQueryParam("testAnswerID");
        $testServer = new pos_ExamService();
        $paperService=new pos_PaperManageService();
        $paperResult=$paperService->queryPaperById("",$testAnswerID,"");
        $getType=$paperResult->getType;
        if($getType)
        {
         return   $this->viewOrgCorrect();

        }
        $testResult = $testServer->queryTestAnswerImages("", "", $testAnswerID);
        return $this->render("viewCorrect", array("testResult" => $testResult));
    }

    /**
     *组织类型的查看批改
     */
    public function viewOrgCorrect(){
        $testAnswerID=app()->request->getQueryParam("testAnswerID");
        $testServer=new pos_ExamService();
        $testResult=$testServer->querytestAllAnswerPicList($testAnswerID);
        return $this->render("viewOrgCorrect",array("testResult"=>$testResult));
    }

    /**
     *在线答题 试卷
     */
    public function actionOnlineAnswering()
    {
        if(true){
           //答题完毕
          return   $this->actionOnlineAnswered();

        }
        $examID=app()->request->getQueryParam("examID");
        $testServer=new pos_TestManageService();
        $testResult=$testServer->queryTestInfoByIDOrgType($examID);
        return $this->render("onlineAnswering",array("testResult"=>$testResult));
    }
    /**
     *在线答题完毕
     */
    public function actionOnlineAnswered()
    {
        $examSubID=app()->request->getQueryParam("examSubID");
        //      科目总评的获取
        $examServer=new pos_ExamService();
        $evaluateResult = $examServer->searchSubjectEvaluate($examSubID);
        $pages=new Pagination();
        $pages->pageSize=10;
        $testResult=$examServer->queryTestInfoByIDOrgType($examSubID,user()->id,$pages->getPage()+1,$pages->pageSize);
        $pages->totalCount=$testResult->countSize;
        //     获取最高分和最低分

        $maxAndMin = $examServer->searchTheMaxAndMinScoreSub($examSubID);
//        分数段的获取
        $sectionArray = array("data" => array(
            array("bottomlimit" => "61", "toplimit" => "70"),
            array("bottomlimit" => "71", "toplimit" => "90"),
            array("bottomlimit" => "91", "toplimit" => "100")
        ));
        $sectionJson = json_encode($sectionArray);
        $sectionResult = $examServer->queryNumberByIntervalSub($examSubID, $sectionJson);
        return $this->render("onlineAnswered",array("testResult"=>$testResult,"pages"=>$pages,"evaluateResult"=>$evaluateResult,"maxAndMin"=>$maxAndMin,"sectionResult"=>$sectionResult));
    }
    /**
     *试卷预览
     */
    public function actionUploadPreview()
    {
        $examSubID = app()->request->getQueryParam("examSubID");
        $test = new pos_ExamService();
        $testResult = $test->searchExamPaper($examSubID);
        return $this->render("uploadPreview", array("testResult" => $testResult->data));
    }

    /**
     * 新 在线答题 试卷 wgl
     */
    public function actionOnLineAnswers(){
        $examSubID= app()->request->getQueryParam('examSubID');
        $testServer=new pos_ExamService();
        $result = $testServer->queryTestInfoByIDOrgType($examSubID, '', '' ,999);
        //查询知识点
        $know = new KnowledgePointModel;
        $kcidName = $know->findKnowledge($result->knowledgeId);
        return $this->render('newOnlineAnswering', array("testResult"=>$result, 'kcidName'=>$kcidName));
    }

    /**
     *组织的作业完成答题
     */
    public function actionFinishUpload(){
        $examSubID=app()->request->getBodyParam("examSubID");
        $answerList=app()->request->getBodyParam("answerList");
        $jsonResult=new JsonMessage();
        $testServer=new pos_ExamService();
        $testResult=$testServer->uploadTestAnswerQuestion(user()->id,$examSubID,$answerList);
        if($testResult->resCode==$testServer::successCode){
            $jsonResult->code=1;
        }else{
            $jsonResult->code=0;
        }
        $jsonResult->message=$testResult->resMsg;
        return $this->renderJSON($jsonResult);
    }
}