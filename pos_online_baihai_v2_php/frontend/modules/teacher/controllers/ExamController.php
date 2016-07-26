<?php
namespace frontend\modules\teacher\controllers;
use common\models\JsonMessage;
use frontend\components\TeacherBaseController;
use frontend\models\dicmodels\KnowledgePointModel;
use frontend\models\dicmodels\SubjectModel;
use frontend\models\ExamForm;
use frontend\services\BaseService;
use frontend\services\pos\pos_ClassInfoService;
use frontend\services\pos\pos_ClassMembersService;
use frontend\services\pos\pos_ExamService;
use frontend\services\pos\pos_PaperManageService;
use frontend\services\pos\pos_PersonalInformationService;
use frontend\services\pos\pos_SuperManageService;
use Yii;
use yii\data\Pagination;
use yii\helpers\Html;

/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-10-15
 * Time: 下午1:15
 */
class ExamController extends TeacherBaseController
{
    public $layout = "lay_user";
//    public $enableCsrfValidation = false;
    /**
     *考试管理
     */
    public function actionManage()
    {
                $classID = app()->request->getParam("classid");
        //        根据学部获取科目
        $department = loginUser()->getModel()->department;
        $subjectArray = SubjectModel::model()->getSubjectByDepartment($department);
        $examServer = new pos_ExamService();
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 10;
        if (app()->request->isAjax) {
            $type = app()->request->getParam("type");
            $examResult = $examServer->queryExamList($classID, user()->id, $type, $pages->getPage() + 1, $pages->pageSize);
            $pages->totalCount = $examResult->data->countSize;
            $pages->params["type"] = $type;
            $pages->params["classid"] = $classID;
            return $this->renderPartial("_exam_list", array("subjectArray" => $subjectArray, "examResult" => $examResult, "pages" => $pages));

        } else {
            $examResult = $examServer->queryExamList($classID, user()->id, "", $pages->getPage() + 1, $pages->pageSize);
            $pages->totalCount = $examResult->data->countSize;
            return $this->render("manage", array("subjectArray" => $subjectArray, "examResult" => $examResult, "pages" => $pages));
        }

    }

    /**
     *AJAX获取设置科目和时间的弹出框
     */
    public function actionGetSubPop()
    {
        $examID = app()->request->getParam("examID");
        $examServer = new pos_ExamService();
        $examResult = $examServer->queryExamById($examID, user()->id);
        $curSubArray = array();
        foreach ($examResult->data->examSubList as $v) {
            array_push($curSubArray, array("subjectName" => $v->subjectName, "id" => $v->subjectID, "subScore" => $v->subScore,'isHaveScore'=>$v->isHaveScore));
        }
        //        根据学部获取科目
        $department = loginUser()->getModel()->department;
        $subjectArray = SubjectModel::model()->getSubjectByDepartment($department,'');
        $allSubArray = array();
        foreach ($subjectArray as $v) {
            array_push($allSubArray, array("subjectName" => $v->subjectName, "id" => $v->subjectId, "subScore" => "0"));
        };
        return $this->renderPartial("_sub_pop", array("allSub" => json_encode($allSubArray),
            "curSub" => json_encode($curSubArray),
            "examID" => $examID,
            "examTime"=>$examResult->data->examTime
        ));
    }

    /**
     *設置考試時間和科目
     */
    public function actionMasterSetSub()
    {
        $examID = app()->request->getParam("examID");
        $examTime = app()->request->getParam("examTime");
        $subjectList = app()->request->getParam("subjectList");
        $jsonResult = new JsonMessage();
        $server = new pos_ExamService();
        $result = $server->masterSetSub($examID, user()->id, $subjectList, $examTime);
        $jsonResult->message = $result->resMsg;
        return $this->renderJSON($jsonResult);
    }

    /**
     *科目详细
     */
    public function actionSubjectDetails()
    {
        $paperServer = new pos_PaperManageService();
        $examSubID = app()->request->getParam("examSubID");
        $examServer = new pos_ExamService();
        $examResult = $examServer->searchExamPaper($examSubID);
//        判断当前老师是不是当前考试所在班级的班主任
        $classID = $examResult->data->classID;
        $classArray = loginUser()->getClassInfo();
        $isMaster = false;
        foreach ($classArray as $v) {
            if ($v->classID == $classID) {
                if ($v->identity == 20401) {
                    $isMaster = true;
                }
            }
        }

        $superServer = new pos_SuperManageService();
        $examID = $examResult->data->examID;
//        判断当前老师是否是当前考试的任课老师
        $subjectID = $examResult->data->subjectID;
        $teacherSubjectID = loginUser()->getModel()->subjectID;
        $isTheTeacher = $subjectID == $teacherSubjectID ? true : false;
//        文综理综的试卷所有人都可以批改
        $whetherCorrect=false;
        if($subjectID=="10028"||$subjectID=="10027"){
              $whetherCorrect=true;
        }
//        获取试卷列表
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 12;
        $paperResult = $paperServer->queryPaper("", $pages->getPage() + 1, $pages->pageSize, "", "", "", "", "", "", "", $subjectID);
        $pages->totalCount = $paperResult->countSize;
//        获取单科总分
        $subScore = $examResult->data->subScore;
        //        获取统计数据
        $scoreArray = array(array("low" => 0, "high" => $subScore * 0.4), array("low" => ($subScore * 0.4 + 1), "high" => $subScore * 0.6), array("low" => ($subScore * 0.6 + 1), "high" => $subScore * 0.8), array("low" => ($subScore * 0.8 + 1), "high" => $subScore));
        $superResult = $superServer->classScoreSubPeo($examID, $subjectID, json_encode($scoreArray));
        $sectionArray = array();
        foreach ($superResult->list[0]->peoList as $v) {
            array_push($sectionArray, $v->low . "到" . $v->high);
        }
        $numberArray = array();
        foreach ($superResult->list[0]->peoList as $v) {
            array_push($numberArray, array("value" => $v->peos, "name" => $v->low . "到" . $v->high));
        }
//        获取科目总评
        $subEvaResult = $examServer->searchSubjectEvaluate($examSubID);
//        获取当前使用的试卷
        $curPaperResult = $examServer->queryPaperByIDOrgType($examSubID);
        //        获取最高分和最低分
        $minAndMax = $examServer->searchTheMaxAndMinScoreSub($examSubID);
        //        获取分数段
        $intervalArray = array("data" => array(array("bottomlimit"=>0,"toplimit"=>$subScore*0.4),array("bottomlimit" => $subScore*0.4+1, "toplimit" => $subScore*0.6), array("bottomlimit" => $subScore*0.6+1, "toplimit" => $subScore*0.8), array("bottomlimit" => $subScore*0.8+1, "toplimit" => $subScore)));
        $interval = json_encode($intervalArray);
        $scoreSection = $examServer->queryNumberByInterval($examSubID, user()->id, $interval);
        $department = loginUser()->getModel()->department;
        $knowledge = KnowledgePointModel::searchAllKnowledgePoint($subjectID, $department);
        $knowledgeArray = array();
        foreach ($knowledge as $v) {
            if($v->pId==0){
                array_push($knowledgeArray, array("id" => $v->id, "pId" => $v->pId, "name" => $v->name,"nocheck"=>1));
            }else {
                array_push($knowledgeArray, array("id" => $v->id, "pId" => $v->pId, "name" => $v->name,"nocheck"=>""));
            }
        }
//        学生提交的答案
        $stuPages=new Pagination();
        $stuPages->pageSize=10;
        $answerList = $examServer->queryTestAllAnswerList($examSubID,$classID,$stuPages->getPage()+1,$stuPages->pageSize);
        $stuPages->totalCount=$answerList->countSize;
//        判断是否至少有一个学生有成绩
        $isLogScore=false;
        foreach($answerList->answerlist as $v){
            if($v->stuSubScore!=""){
                $isLogScore=true;
            }
        }
        if (app()->request->isAjax) {
            $examSubID=app()->request->getParam("examSubID");
            $pages = new Pagination();$pages->validatePage=false;
            $replace = app()->request->getParam("replace");
            if($replace!=null) {
                $name = app()->request->getParam("name");
                $pages->pageSize = 12;
                $paperResult = $paperServer->queryPaper(user()->id, $pages->getPage() + 1, $pages->pageSize, "", "", $name, "", "", "", "", $subjectID);
                $pages->totalCount = $paperResult->countSize;
                $pages->params["replace"] = $replace;
                $pages->params["name"] = $name;
                $pages->params["examSubID"] = $examSubID;
                return $this->renderPartial("paper_list", array("paperResult" => $paperResult,
                    "pages" => $pages));
            }else{
                $pages->pageSize=10;
                $answerList=$examServer->queryTestAllAnswerList($examSubID,$classID,$pages->getPage() + 1,$pages->pageSize);
                $pages->totalCount=$answerList->countSize;
                return $this->renderPartial("_student_answer_list",array("answerList"=>$answerList,
                    "pages"=>$pages,
                    "isTheTeacher" => $isTheTeacher,
                    "isLogScore"=>$isLogScore,
                    "subScore" => $subScore
                ));
            }
            return;
        }
        return $this->render("subjectDetails", array("examResult" => $examResult->data,
            "minAndMax" => $minAndMax,
            "scoreSection" => $scoreSection->data,
            "section" => json_encode($sectionArray),
            "number" => json_encode($numberArray),
            "paperResult" => $paperResult,
            "pages" => $pages,
            "knowledge" => json_encode($knowledgeArray),
            "subEvaResult" => $subEvaResult,
            "curPaperResult" => $curPaperResult,
            "answerList" => $answerList,
            "stuPages"=>$stuPages,
            "subScore" => $subScore,
            "isMaster" => $isMaster,
            "isTheTeacher" => $isTheTeacher,
            "isLogScore"=>$isLogScore,
            "allPeos"=>$superResult->list[0]->allPeos,
            "whetherCorrect"=>$whetherCorrect
        ));
    }

    /**
     *填写和修改科目总评
     */
    public function actionWriteSubEva()
    {
        $jsonResult = new JsonMessage();
        $examSubID = app()->request->getParam("examSubID");
        $knowledgePoint = app()->request->getParam("knowledgePoint");
        $summary = app()->request->getParam("summary");
        $examServer = new pos_ExamService();
        $examResult = $examServer->subjectSummary($examSubID, user()->id, $knowledgePoint, $summary);
        $jsonResult->message = $examResult->resMsg;
        return $this->renderJSON($jsonResult);
    }

    /**
     *上传并使用试卷
     */
    public function actionUplAndUsePaper()
    {
        $examSubID = app()->request->getParam("examSubID");
        $examServer = new pos_ExamService();
        $examResult = $examServer->searchExamPaper($examSubID);
//        判断当前老师是不是当前考试所在班级的班主任
        $classID = $examResult->data->classID;
        $classArray = loginUser()->getClassInfo();
        $isMaster = false;
        foreach ($classArray as $v) {
            if ($v->classID == $classID) {
                if ($v->identity == 20401) {
                    $isMaster = true;
                }
            }
        }
//        判断当前考试的科目是否是当前老师的教授科目
        $subjectID = $examResult->data->subjectID;
        $userSubjectID=loginUser()->getModel()->subjectID;
        $isSubjectTeacher=false;
        if($subjectID==$userSubjectID){
            $isSubjectTeacher=true;
        }
//        判断是否有权限上传并使用试卷
        $isHavePower=true;
        if($subjectID!="10028"&&$subjectID!="10027"){
            if($isMaster||$isSubjectTeacher){
                $isHavePower=true;
            }else{
                $isHavePower=false;
            }
        }elseif($subjectID=="10028"){
            if($isMaster||($userSubjectID=="10016"||$userSubjectID=="10017"||$userSubjectID=="10018")){
                $isHavePower=true;
            }else{
                $isHavePower=false;
            }
        }elseif($subjectID=="10027"){
            if($isMaster||($userSubjectID=="10013"||$userSubjectID=="10014"||$userSubjectID=="10015")){
                $isHavePower=true;
            }else{
                $isHavePower=false;
            }
        }
        $JsonResult = new JsonMessage();
        if($isHavePower) {
            $name = app()->request->getParam("name");
            $subjectName = $examResult->data->subjectName;
            $classID = $examResult->data->classID;
            $user = loginUser()->getModel();
            $provience = $user->provience;
            $city = $user->city;
            $country = $user->country;
            $classServer = new pos_ClassInfoService();
            $classResult = $classServer->searchClassInfoById($classID);
            $gradeID = $classResult->gradeID;
            if ($name == null) {
                $examName = $examResult->data->examName;
                $name = $examName . $subjectName . "试卷";
            }
            $paperServer = new pos_PaperManageService();
            $imageUrls = app()->request->getParam("imageUrls");
            $paperResult = $paperServer->uploadPaper($name, $provience, $city, $country, $gradeID, $subjectID, "20601", "", "", user()->id, $imageUrls);
            $paperID = $paperResult->data->paperId;
            $examResult = $examServer->manaExamPaper($examSubID, user()->id, $paperID);
            if ($examResult->resCode = BaseService::successCode) {
                $JsonResult->success = true;
            }
            $JsonResult->message = $examResult->resMsg;
        }else{
              $JsonResult->message="对不起，您没有权限";
        }
        return $this->renderJSON($JsonResult);
    }

    /**
     *修改并使用试卷
     */
    public function actionUpdAndUsePaper()
    {
        $paperId = app()->request->getParam("paperID");
        $examSubID = app()->request->getParam("examSubID");
        $examServer = new pos_ExamService();
        $examResult = $examServer->searchExamPaper($examSubID);
//        判断当前老师是不是当前考试所在班级的班主任
        $classID = $examResult->data->classID;
        $classArray = loginUser()->getClassInfo();
        $isMaster = false;
        foreach ($classArray as $v) {
            if ($v->classID == $classID) {
                if ($v->identity == 20401) {
                    $isMaster = true;
                }
            }
        }
//        判断当前考试的科目是否是当前老师的教授科目
        $subjectID = $examResult->data->subjectID;
        $userSubjectID=loginUser()->getModel()->subjectID;
        $isSubjectTeacher=false;
        if($subjectID==$userSubjectID){
            $isSubjectTeacher=true;
        }
        $JsonResult = new JsonMessage();
        if($isMaster||$isSubjectTeacher) {

//        $subjectID=$examResult->data->subjectID;
//        $subjectName=$examResult->data->subjectName;
//        $examName=$examResult->data->examName;
            $paperServer = new pos_PaperManageService();
            $queryResult = $paperServer->queryPaper("", "", "", "", $paperId);
            $provience = $queryResult->list[0]->provience;
            $city = $queryResult->list[0]->city;
            $country = $queryResult->list[0]->country;
            $gradeID = $queryResult->list[0]->gradeId;
            $subjectID = $queryResult->list[0]->subjectId;
            $versionID = $queryResult->list[0]->version;
            $knowledgeId = $queryResult->list[0]->knowledgeId;
            $paperDescribe = $queryResult->list[0]->paperDescribe;
            $imageUrls = app()->request->getParam("imageUrls");
            $name = $queryResult->list[0]->name;
            $paperResult = $paperServer->updateUploadPaper($paperId, $name, $provience, $city, $country, $gradeID, $subjectID, $versionID, $knowledgeId, $paperDescribe, user()->id, $imageUrls);
//        $examResult=$examServer->manaExamPaper($examSubID,user()->id,$paperId);
//        $JsonResult->message=$examResult->resMsg;
            if ($paperResult->resCode == BaseService::successCode) {
                $JsonResult->success = true;
            }
            $JsonResult->message = $paperResult->resMsg;
        }else{
            $JsonResult->message="对不起，您没有权限";
        }
        return $this->renderJSON($JsonResult);
    }

    /**
     *判断试卷是否被使用
     */
    public function actionPaperIfUsed()
    {
        $paperID = app()->request->getParam("paperID");
        $examSubID = app()->request->getParam("examSubID");
        $paperServer = new pos_PaperManageService();
        $result = $paperServer->queryPaperUsedByOtherExamSub($paperID, $examSubID);
        $jsonResult = new JsonMessage();
        $jsonResult->code = $result->data->used;
        return $this->renderJSON($jsonResult);
    }

    /**
     *使用试卷
     */
    public function actionUsePaper()
    {
        $examSubID = app()->request->getParam("examSubID");
        $paperID = app()->request->getParam("paperID");
        $jsonResult = new JsonMessage();
        $examServer = new pos_ExamService();
        $examResult = $examServer->manaExamPaper($examSubID, user()->id, $paperID);
        if($examResult->resCode==BaseService::successCode){
            $jsonResult->success=true;
        }
        $jsonResult->message = $examResult->resMsg;
        return $this->renderJSON($jsonResult);
    }

    /**
     *班级总评
     */
    public function actionOverAllAppraise()
    {
        $examID = app()->request->getParam("examID");
        $examServer = new pos_ExamService();
        $examResult = $examServer->queryExamById($examID, user()->id);
        $studentResult = $examServer->teacherQueryStuExamInfoList($examID);
//        判断是否所有的学生已经被评价了
        $isAllHavePerEvaluate=true;
        foreach($studentResult->data->examScoresList as $v){
            if($v->isHavePerEvaluate==0){
                $isAllHavePerEvaluate=false;
            }
        }
//        判断是否需要集体发送成绩和评价
        $isSendAll=false;
        foreach($studentResult->data->examScoresList as $v){
            if($v->isHavePerEvaluate==1&&$v->isSendMsg==0){
                $isSendAll=true;
            }
        }
        $classEvaResult = $examServer->searchClassEvaluate($examID);
//        获取总分最高分和最低分
        $minAndMax = $examServer->searchTheMaxAndMinScoreExam($examID);
//        获取考试总分
        $fullResult = $examServer->queryExamFullScore($examID);
        $fullScore = $fullResult->fullScore;
        //        获取分数区间
        $intervalArray = array(
            array("bottomlimit" => "0", "toplimit" => $fullScore * 0.4),
            array("bottomlimit" => ($fullScore * 0.4 + 1), "toplimit" => $fullScore * 0.6),
            array("bottomlimit" => ($fullScore * 0.6 + 1), "toplimit" => $fullScore * 0.8),
            array("bottomlimit" => ($fullScore * 0.8 + 1), "toplimit" => $fullScore),
        );
        $scoreSection = $examServer->queryNumberByIntervalExam($examID, $intervalArray);
//        统计数据分析获取
        $superServer = new pos_SuperManageService();
        $array = array(array("low" => 0, "high" => $fullScore * 0.4), array("low" => ($fullScore * 0.4 + 1), "high" => $fullScore * 0.6), array("low" => ($fullScore * 0.6 + 1), "high" => $fullScore * 0.8), array("low" => ($fullScore * 0.8 + 1), "high" => $fullScore));
        $superResult = $superServer->classScoreTotalPeo($examID, json_encode($array));
        $sectionArray = array();
        foreach ($superResult->list as $v) {
            array_push($sectionArray, $v->low . "到" . $v->high);
        }
        $dataArray = array();
        foreach ($superResult->list as $v) {
            array_push($dataArray, array("value" => $v->peos, "name" => $v->low . "到" . $v->high));
        }
        $noSendArray=array();
        foreach($studentResult->data->examScoresList as $v){
                array_push($noSendArray,$v->studentID);
        }

        return $this->render("overAllAppraise", array("studentResult" => $studentResult->data,
            "classEvaResult" => $classEvaResult->data,
            "scoreSection" => $scoreSection,
            "minAndMax" => $minAndMax,
            "section" => json_encode($sectionArray),
            "data" => json_encode($dataArray),
            "examResult" => $examResult->data,
            "noSendArray"=>$noSendArray,
            "isSendAll"=>$isSendAll,
            "isAllHavePerEvaluate"=>$isAllHavePerEvaluate,
            "superResult"=>$superResult,
            "fullScore"=>$fullScore
        ));
    }

    /**
     * 本班总评和科目总评分数区间人数的展示
     * @throws CException
     */
    public function actionGetStudentList(){
        $jsonResult=new JsonMessage();
        $examServer=new pos_ExamService();
        $toplimit=app()->request->getParam("topLimit");
        $bottomlimit=app()->request->getParam("bottomLimit");
        $examID=app()->request->getParam("examID");
        $examSubID=app()->request->getParam("examSubID");
        if($examID!=null) {
            $examResult = $examServer->queryStudentByIntervalExam($examID, $bottomlimit, $toplimit);
        }else{
            $examResult=$examServer->queryStudentByIntervalSub($examSubID, $bottomlimit, $toplimit);
        }
        if($examResult->userListSize>0){
            $jsonResult->success=true;
            $jsonResult->data= $this->renderPartial("interval_student_list",array("studentList"=>$examResult->userList),true);
        }else{

            $jsonResult->message="当前分数段没有学生";
        }

        return $this->renderJSON($jsonResult);
    }
    /**
     * @throws CException
     * 调整分数区间获取分数段信息
     */
    public function actionChangeClassStatics(){
        $examID=app()->request->getParam("examID");
        $lowRate=app()->request->getParam("lowRate");
        $highRate=app()->request->getParam("highRate");
        //        获取考试总分
        $examServer=new pos_ExamService();
        $fullResult = $examServer->queryExamFullScore($examID);
        $fullScore = $fullResult->fullScore;
//        统计数据分析获取
        $superServer = new pos_SuperManageService();
        if($lowRate>0&&$highRate<$fullScore) {
            $array = array(array("low" => 0, "high" =>  $lowRate-1  ), array("low" => $lowRate , "high" =>  $highRate ), array("low" =>   $highRate +1, "high" => $fullScore));
        }else if($lowRate==0&&$highRate<$fullScore){
            $array = array( array("low" => 0, "high" =>  $highRate ), array("low" =>  $highRate +1, "high" => $fullScore));
        }elseif($lowRate>0&&$highRate==$fullScore){
            $array = array( array("low" => 0, "high" =>   $lowRate-1 ), array("low" =>   $lowRate, "high" => $fullScore));
        }
        $superResult = $superServer->classScoreTotalPeo($examID, json_encode($array));
        $sectionArray = array();
        foreach ($superResult->list as $v) {
            array_push($sectionArray, $v->low . "到" . $v->high);
        }
        $dataArray = array();
        foreach ($superResult->list as $v) {
            array_push($dataArray, array("value" => $v->peos, "name" => $v->low . "到" . $v->high));
        }
        return $this->renderPartial("_change_class_statics",array("section" => json_encode($sectionArray),
            "data" => json_encode($dataArray)));
    }

    /**
     * 获取未录入成绩的学生列表
     * @throws CException
     */
    public function actionGetUnscoredStu()
    {
        $examSubID = app()->request->getParam("examSubID");
        $examServer = new pos_ExamService();
//        获取未录入成绩的学生列表
        $examResult = $examServer->queryExamSubNoScoreStu($examSubID);
        $paperResult=$examServer->searchExamPaper($examSubID);
        $fullScore=$paperResult->data->subScore;
        $jsonMessage = new JsonMessage();
        if ($examResult->noScoreStuListSize > 0) {
            $jsonMessage->data =  $this->renderPartial("unscored_stu_list", array('examResult' => $examResult->noScoreStuList,
                "examSubID" => $examSubID,"fullScore"=>$fullScore
            ), true);
            $jsonMessage->success=true;
        }
          return $this->renderJSON($jsonMessage);
    }

    /**
     *录入单个学生成绩
     */
    public function actionLogStuScore()
    {
        $score = app()->request->getParam('score','');
        $studentID = app()->request->getParam('studentID','');
        $arr =array('stuSubScore'=>$score,'studentID'=>$studentID);
        $examSubID = app()->request->getParam('examSubID','');
        $scoreList=json_encode(array('data'=>[$arr]));
        $jsonResult = new JsonMessage();
        $examServer = new pos_ExamService();
        $examResult = $examServer->loggingStudentScore(user()->id, $examSubID, $scoreList);
        if ($examResult->resCode === BaseService::successCode) {
            $jsonResult->success = true;
        } else {
            $jsonResult->success = false;
            $jsonResult->message = $examResult->resMsg;
        }
        return $this->renderJSON($jsonResult);
    }

    /**
     *录入多个学生成绩
     */
    public function actionLogStusScore()
    {
        $jsonResult = new JsonMessage();
        $examSubID = app()->request->getParam("examSubID");
        $scoreList = app()->request->getParam("scoreList");
        $examServer = new pos_ExamService();
        $examResult = $examServer->loggingStudentScore(user()->id, $examSubID, $scoreList);
        $jsonResult->message = $examResult->resMsg;
        return $this->renderJSON($jsonResult);
    }

    /**
     *填写个人评价
     */
    public function actionWritePerEva()
    {
        $jsonResult = new JsonMessage();
        $examServer = new pos_ExamService();
        $evaluate = app()->request->getParam("evaluate");
        $examID = app()->request->getParam("examID");
        $studentID = app()->request->getParam("studentID");
        $result = $examServer->writeStudentEvaluate($examID, $studentID, $evaluate, user()->id);

	    if ($result->resCode === BaseService::successCode) {
		    $jsonResult->success = true;
	    } else {
		    $jsonResult->success = false;
		    $jsonResult->message = $result->resMsg;
	    }
        return $this->renderJSON($jsonResult);
    }

    /**
     *查询学生个人评价
     */
    public function actionShowPerEva()
    {
        $jsonResult = new JsonMessage();
        $studentID = app()->request->getParam("studentID");
        $examID = app()->request->getParam("examID");
        $examServer = new pos_ExamService();
        $result = $examServer->searchStudentEvaluate($examID, $studentID);
        $jsonResult->data = Html::encode($result->data->evaluate);
        return $this->renderJSON($jsonResult);
    }

    /**
     *填写和修改班级总评
     */
    public function actionWriteClassEva()
    {
        $jsonResult = new JsonMessage();
        $examID = app()->request->getParam("examID");
        $learnSituation = app()->request->getParam("learnSituation");
        $commonPro = app()->request->getParam("commonPro");
        $improveAdvise = app()->request->getParam("improveAdvise");
        $examServer = new pos_ExamService();
        $result = $examServer->writeClassEvaluate($examID, $learnSituation, $commonPro, $improveAdvise);
        $jsonResult->message = $result->resMsg;
        return $this->renderJSON($jsonResult);
    }

    /**
     *上传类型的试卷预览
     */
    public function actionPaperPreview()
    {
        $paperID = app()->request->getParam("paperID");
        $paperService = new pos_PaperManageService();
        $paperResult = $paperService->queryPaperById($paperID, "", "");
        $getType = $paperResult->getType;
        if ($getType == 1) {
            return   $this->newDigitalPreview();

        }
        $server = new pos_ExamService();
        $result = $server->queryPaperByIDOrgType("", $paperID);

        return $this->render("paperPreview", array("result" => $result));
    }

    /**
     *电子的试卷预览
     */
    public function digitalPreview()
    {
        $this->layout="lay_prepare";
        $paperID = app()->request->getParam("paperID");
        $examServer = new pos_ExamService();
        $result = $examServer->queryPaperByIDOrgType("", $paperID);
        return $this->render("newDigitalPreview", array("result" => $result));
    }
    /**
     *新的电子试卷预览
     */
    public function NewDigitalPreview(){
        $this->layout="lay_prepare";
        $paperID = app()->request->getParam("paperID");
        $server=new pos_PaperManageService();
        $result=$server->queryMakerPaperById($paperID);
        return $this->render("newDigitalPreview", array("result" => $result));
    }

    /**
     *新建考试
     */
    public function actionCreateExam()
    {
        $classId = app()->request->getParam("classID");
//        获取当前老师教授的科目
        $classArray = loginUser()->getClassInfo();
        foreach ($classArray as $v) {
            if ($classId == $v->classID) {
                $subjectId = $v->subjectNumber;
            }
        }
//        根据学部获取科目
        $department = loginUser()->getModel()->department;
        $subjectArray = SubjectModel::model()->getSubjectByDepartment($department,'');
        $examModel = new ExamForm();
        if ($_POST) {
            $examModel->load(Yii::$app->request->post());
            $server = new pos_ExamService();
            $subjectListArray = array();
            foreach ($_POST["ExamForm"]["subjectList"] as $v) {
                if (isset($v["subject"])) {
                    array_push($subjectListArray, array("subjectID" => $v["subject"], "subScore" => $v["score"]));
                }
            }
            $result = $server->creatExamByMaster(user()->id, $classId, $examModel->examName, "", "", $examModel->type, json_encode(array("data" => $subjectListArray)), "", $examModel->examTime);
            if ($result->resCode == BaseService::successCode && $result->data->subjectNum == 1 && $result->data->subjectID == $subjectId) {
                return $this->redirect(url("teacher/exam/success", array("classID" => $classId, "examSubID" => $result->data->examSubID)));
            } elseif ($result->resCode != BaseService::successCode) {

            } else {
                return $this->redirect(url("teacher/exam/manage", array("classid" => $classId)));
            }
        }
        return $this->render("createExam", array("subjectID" => $subjectId, "subjectArray" => $subjectArray, "examModel" => $examModel,'classId'=>$classId));
    }

    /**
     *考试创建成功
     */
    public function actionSuccess()
    {
        return $this->render("success");
    }

    /**
     *AJAX上传试卷
     */
    public function actionUploadPaper()
    {
        $examSubID = app()->request->getParam('examSubID');
//        $imgUrl = app()->request->getParam('imgUrl');
        $paperId = app()->request->getParam("paperId");
        $exam = new pos_ExamService();
        $result = $exam->manaExamPaper($examSubID, user()->id, $paperId);
        $jsonResult = new JsonMessage();
        if ($result->resCode == pos_ExamService::successCode) {

            $jsonResult->success = true;
        } else {
            $jsonResult->success = false;
        }
        $jsonResult->message = $result->resMsg;
        return $this->renderJSON($jsonResult);
    }

    /**
     *AJAX修改试卷
     */
    public function actionUpdatePaper()
    {
        $jsonResult = new JsonMessage();
        $examSubID = app()->request->getParam('examSubID');
        $imgUrl = app()->request->getParam('imageUrl');
        $exam = new pos_ExamService();
        $result = $exam->teacherModifyPaper($examSubID, user()->id, "", "", $imgUrl, "");
        if ($result->resCode == pos_ExamService::successCode) {
            $jsonResult->success = true;
        } else {
            $jsonResult->success = false;
        }
        $jsonResult->message = $result->resMsg;
        return $this->renderJSON($jsonResult);
    }

    /**
     *录入学生成绩列表学生列表查询
     */
    public function actionSearchClassMembers()
    {
        $classID = app()->request->getParam("classID");
        $examSubID = app()->request->getParam("examSubID");
        $class = new pos_ClassMembersService();
        $result = $class->loadRegisteredMembers($classID, 1);
        $examServer = new pos_ExamService();
        $examResult = $examServer->searchExamPaper($examSubID);
        return $this->renderPartial("_entering_score", array("classMembers" => $result, "examSubID" => $examSubID, "data" => $examResult->data));
    }

    /**
     *上传试卷内容查询
     */
    public function actionUploadPaperContent()
    {
//        $examSubID = app()->request->getParam("examSubID");

        $subjectID = app()->request->getParam("subjectID");
        $paperServer = new pos_PaperManageService();
        $paperResult = $paperServer->queryPaper("", "", "", "", "", "", "", "", "", "", $subjectID);
        $paperArray = array();
        foreach ($paperResult->list as $v) {
            array_push($paperArray, array("paperId" => $v->paperId, "paperName" => $v->name));
        }
        array_push($paperArray, array("paperId" => "0", "paperName" => "其他试卷"));
        $this->layout = '@app/views/layouts/blank';
        return $this->render("_upload_paper_content", array("paperArray" => $paperArray));
    }

    /**
     *AJAX录入学生成绩
     */
    public function actionScoreEntry()
    {
        $examSubID = $_GET["examSubID"];
        $getArray = $_GET["entry"];
        $entryArray["data"] = $getArray;
        $entryJson = json_encode($entryArray);
        $exam = new pos_ExamService();
        $result = $exam->loggingStudentScore(user()->id, $examSubID, $entryJson);
        $jsonResult = new JsonMessage();
        if ($result->resCode == BaseService::successCode) {
            $jsonResult->code = 1;
            $jsonResult->message = $result->resMsg;
        } else {
            $jsonResult->code = 0;
            $jsonResult->message = "请填写成绩";
        }


        return $this->renderJSON($jsonResult);
    }

    /**
     *更新成绩
     */
    public function actionUpdateScore()
    {
        $studentID = $_GET["studentID"];
        $examSubID = $_GET["examSubID"];
        $personalScore = $_GET["data"];
        $exam = new pos_ExamService();
        $entryArray = array("data" => array(array("studentID" => $studentID, "personalScore" => $personalScore)));
        $entryJson = json_encode($entryArray);
        $result = $exam->loggingStudentScore(user()->id, $examSubID, $entryJson);
        $jsonResult = new JsonMessage();
        if ($result->resCode == pos_ExamService::successCode) {
            $jsonResult->success = true;
        } else {
            $jsonResult->success = false;
        }
        return $this->renderJSON($jsonResult);
    }

    /**
     *通过ajAX查询考试名字
     */
    public function actionSelectExamName()
    {

        $examID = app()->request->getParam('examID');
        $exam = new pos_ExamService();
        $result = $exam->queryExamById($examID, user()->id);
        $examName = $result->data->examName;
        return $this->renderPartial("_select_exam_name", array("examName" => $examName, "examID" => $examID));

    }


    /**
     *修改考试名字
     */
    public function actionChangeExamName()
    {
        $examID = app()->request->getParam('examID');
        $newExamName = app()->request->getParam('newExamName');
        $exam = new pos_ExamService();
        $result = $exam->changeExamNameByMaster(user()->id, $examID, $newExamName);
        $jsonResult = new JsonMessage();
        if ($result->resCode == pos_ExamService::successCode) {
            $jsonResult->code = 1;
        } else {
            $jsonResult->code = 0;
        }
        $jsonResult->message = $result->resMsg;
        return $this->renderJSON($jsonResult);
    }

    /**
     *查看成绩
     */
    public function actionStudentResult()
    {
        $classID = $_GET["classID"];
        $examSubID = $_GET["examSubID"];
        $exam = new pos_ExamService();
        $result = $exam->searchExamPaper($examSubID);
        $studentAnswer = $exam->teacherQueryAnswerList($examSubID, 0, "", "");
//        获取最高分和最低分
        $minAndMax = $exam->querySubjectMAXandMIN($examSubID, user()->id);
//        获取分数段
        $intervalArray = array("data" => array(array("bottomlimit" => "60", "toplimit" => "70"), array("bottomlimit" => "70", "toplimit" => "80"), array("bottomlimit" => "80", "toplimit" => "90")));
        $interval = json_encode($intervalArray);
        $scoreSection = $exam->queryNumberByInterval($examSubID, user()->id, $interval);
//        查询科目总评
        $evaluationResult = $exam->searchSubjectEvaluate($examSubID);
//        学部的获取
        $classServer = new pos_ClassInfoService();
        $classResult = $classServer->searchClassInfoById($classID);
        $department = $classResult->department;
        $subjectID = app()->request->getParam("subjectID");
//        知识树的获取
        $knowledgePoint = KnowledgePointModel::searchAllKnowledgePoint($subjectID, $department);
        $knowledgePointJson = json_encode($knowledgePoint);
        return $this->render("studentResult", array("scoreSection" => $scoreSection, "evaluationResult" => $evaluationResult, "minAndMax" => $minAndMax, "examSubID" => $examSubID, "data" => $result->data, "studentAnswer" => $studentAnswer, "knowledgePointJson" => $knowledgePointJson));
    }

    /**
     *AJAX获取科目总评分页
     */
    public function actionGetStudentPage()
    {
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 1;
        $examSubID = app()->request->getParam('examSubID');
        $exam = new pos_ExamService();
        $result = $exam->teacherQueryAnswerList($examSubID, 0, $pages->getPage() + 1, $pages->pageSize);
        $pages->totalCount = $result->data->countSize;
        return $this->renderPartial("_student_list", array("studentAnswer" => $result, "pages" => $pages));
    }

    /**
     *填写科目总评
     */
    public function actionSubjectEvaluation()
    {
        $examSubID = app()->request->getParam('examSubID');
        $studyPlan = app()->request->getParam('studyPlan');
        $knowledgePoint = app()->request->getParam('knowledgePoint');
        $jsonResult = new JsonMessage();
        $exam = new pos_ExamService();
        $result = $exam->subjectSummary($examSubID, user()->id, $knowledgePoint, $studyPlan);
        if ($result->resCode == pos_ExamService::successCode) {
            $jsonResult->code = 1;
            $jsonResult->message = $result->resMsg;
        } else {
            $jsonResult->code = 0;
            $jsonResult->message = $result->resMsg;
        }
        return $this->renderJSON($jsonResult);
    }

    /**
     *组织类型的试卷预览
     */
    public function actionOrgPreview()
    {
        $examSubID = app()->request->getParam("examSubID");
        $testServer = new pos_ExamService();
        $result = $testServer->queryPaperByIDOrgType($examSubID);
        return $this->render("orgPreview", array("result" => $result));
    }

    /**
     *答案预览
     */
    public function actionAnswerPreview()
    {
        $answerID = app()->request->getParam("answerID");
        $examServer = new pos_ExamService();
        $answerResult = $examServer->teacherQueryAnswerInfo($answerID);
        return $this->render("answerPreview", array("answerResult" => $answerResult));
    }

    /**
     *学期总评
     */
    public function actionTermAppraise()
    {
        $exam = new pos_ExamService();

        $examID = $_GET["examID"];
        $classEvaluate = $exam->searchClassEvaluate($examID);
        $studentResult = $exam->teacherQueryStuExamInfoList($examID, "", "", "");
//        获取分数区间
        $intervalArray = array(array("bottomlimit" => "200", "toplimit" => "220"), array("bottomlimit" => "221", "toplimit" => "250"), array("bottomlimit" => "250", "toplimit" => "11110"));
        $interval = json_encode($intervalArray);
        $scoreSection = $exam->queryExamNum(user()->id, $examID, $interval);
//        获取总分最高分和最低分
        $minAndMax = $exam->queryExamMAXandMIN(user()->id, $examID);
//        判断当前用户在这个班级中是否是班主任
        $classID = $minAndMax->data->classID;
        $person = new pos_PersonalInformationService();
        $result = $person->loadUserInfoById(user()->id);
        $identity = false;
        foreach ($result->userClass as $v) {
            if ($v["classID"] == $classID && $v["identity"] == "20401") {
                $identity = true;
            }
        }

        return $this->render("termAppraise", array("minAndMax" => $minAndMax, "scoreSection" => $scoreSection,
            "studentResult" => $studentResult, "classEvaluate" => $classEvaluate,
            "examID" => $examID, "identity" => $identity));
    }

    /**
     *班主任评级班级
     */
    public function actionClassEvaluate()
    {
        $examID = app()->request->getParam('examID');
        $learnSituation = app()->request->getParam('learnSituation');
        $commonPro = app()->request->getParam('commonPro');
        $improveAdvise = app()->request->getParam('improveAdvise');
        $exam = new pos_ExamService();
        $result = $exam->writeClassEvaluate($examID, $learnSituation, $commonPro, $improveAdvise);
        $jsonResult = new JsonMessage();
        if ($result->resCode == pos_ExamService::successCode) {
            $jsonResult->code = 1;
        } else {
            $jsonResult->code = 0;
        }
        $jsonResult->message = $result->resMsg;
        return $this->renderJSON($jsonResult);
    }

    /**
     *教师评价学生
     */
    public function actionStudentEvaluate()
    {
        $examID = app()->request->getParam('examID');
        $studentID = app()->request->getParam('studentID');
        $evaluate = app()->request->getParam('evaluate');
        $exam = new pos_ExamService();
        $result = $exam->writeStudentEvaluate($examID, $studentID, $evaluate, user()->id);
        $jsonResult = new JsonMessage();
        if ($result->resCode == pos_ExamService::successCode) {
            $jsonResult->code = 1;
        } else {
            $jsonResult->code = 0;
        }
        $jsonResult->message = $result->resMsg;
        return $this->renderJSON($jsonResult);
    }

    /**
     *查询学生评价
     */
    public function actionSearchEvaluate()
    {
        $studentID = app()->request->getParam("studentID");
        $examID = app()->request->getParam("examID");
        $exam = new  pos_ExamService();
        $result = $exam->searchStudentEvaluate($examID, $studentID, "");
        $jsonResult = new JsonMessage();
        $jsonResult->data = $result->data->evaluate;
        return $this->renderJSON($jsonResult);
    }

    /**
     *上传类型的判卷
     */
    public function actionCorrectPaper()
    {
        $testAnswerID = app()->request->getParam("testAnswerID");
        $examServer = new pos_ExamService();
//        当前试卷改为批改中的状态
        $examChecking = $examServer->teacherChecking(user()->id, $testAnswerID);
        if ($examChecking->resCode == BaseService::successCode) {
            $examResult=$examServer->queryExamFullScore("","",$testAnswerID);
            $testResult = $examServer->queryTestAnswerImages(null, null, $testAnswerID);
            $checkData=$examServer->answerCheckStatus($testAnswerID);
            $isCheck=$checkData->isCheck;
            $paperService = new pos_PaperManageService();
            $paperResult = $paperService->queryPaperById("", $testAnswerID, "");
            $getType = $paperResult->getType;
            if ($getType == 1) {
               return  $this->correctOrganizePaper($isCheck);

            }
             return $this->render("correctPaper", array("answerResult" => $testResult,"fullScore"=>$examResult->fullScore,"isCheck"=>$isCheck));
        }
    }

    /**
     * 组织类型测验的批改
     */
    public function correctOrganizePaper($isCheck)
    {
        $testAnswerID = app()->request->getParam("testAnswerID");
        $testServer = new pos_ExamService();
        $testResult = $testServer->querytestAllAnswerPicList($testAnswerID);
//        获取主观题分数
        $scoreResult=$testServer->queryPaperScore($testAnswerID);
        $resScore=$scoreResult->resQuestionScore;
        return $this->render("newOrgPaper", array("testResult" => $testResult,"resScore"=>$resScore,"isCheck"=>$isCheck));
    }


    /**
     * 上传类型的查看批改
     */
    public function actionViewCorrect()
    {

        $testServer = new pos_ExamService();
        $testAnswerID = app()->request->getParam("testAnswerID");
        $paperService = new pos_PaperManageService();
        $paperResult = $paperService->queryPaperById("", $testAnswerID, "");
        $getType = $paperResult->getType;
        if ($getType) {
            return   $this->viewOrgCorrect();

        }
        $testResult = $testServer->queryTestAnswerImages("", "", $testAnswerID);
        return $this->render("viewCorrect", array("testResult" => $testResult));
    }

    /**
     *组织类型的查看批改
     */
    public function viewOrgCorrect()
    {
        $testAnswerID = app()->request->getParam("testAnswerID");
        $testServer = new pos_ExamService();
        $testResult = $testServer->querytestAllAnswerPicList($testAnswerID);
        return $this->render("viewOrgCorrect", array("testResult" => $testResult));
    }

    /**
     *AJAX上传类型保存本页批改
     */
    public function actionHoldCorrect()
    {
        $testAnswerID = app()->request->getParam("testAnswerID");
        $tID = app()->request->getParam("tID");
        $checkInfoJson = app()->request->getParam("checkInfoJson");
        $jsonResult = new JsonMessage();
        $testServer = new pos_ExamService();
        $testResult = $testServer->commitCheckInfo(user()->id, $testAnswerID, $tID, $checkInfoJson);
        if ($testResult->resCode == BaseService::successCode) {
            $jsonResult->success = true;
        }
        $jsonResult->message = $testResult->resMsg;
        return $this->renderJSON($jsonResult);
    }


    /**
     *组织试卷类型的保存本页批改
     */
    public function actionHoldOrganizeCorrect()
    {
        $testAnswerID = app()->request->getParam("testAnswerID");
        $picID = app()->request->getParam("picID");
        $answerID = app()->request->getParam("answerID");
        $checkInfoJson = app()->request->getParam("checkInfoJson");
        $testServer = new pos_ExamService();
        $testResult = $testServer->commitCheckInfoForOrgPaper(user()->id, $testAnswerID, $answerID, $picID, $checkInfoJson);
        $jsonResult = new JsonMessage();
        if ($testResult->resCode = BaseService::successCode) {
            $jsonResult->code == 1;
        } else {
            $jsonResult->code = 0;
        }
        $jsonResult->message = $testResult->resMsg;
        return $this->renderJSON($jsonResult);
    }

    /**
     *AJAX批改完成
     */
    public function actionFinishCorrect()
    {
        $testAnswerID = app()->request->getParam("testAnswerID");
        $testScore = app()->request->getParam("score");
        $jsonResult = new JsonMessage();
        $testServer = new pos_ExamService();
        $testResult = $testServer->updateCheckState($testAnswerID, user()->id, 1, $testScore);
        if ($testResult->resCode == BaseService::successCode) {
            $jsonResult->success = true;
        }
        $jsonResult->data = $testResult->data;
        $jsonResult->message = $testResult->resMsg;

        return $this->renderJSON($jsonResult);

    }


}