<?php
namespace frontend\modules\teacher\controllers;
use common\models\JsonMessage;
use frontend\components\TeacherBaseController;
use frontend\models\dicmodels\ChapterInfoModel;
use frontend\models\PaperForm;
use frontend\services\BaseService;
use frontend\services\pos\pos_ExamService;
use frontend\services\pos\pos_PaperManageService;
use frontend\services\pos\pos_SchoolTeacherService;
use frontend\services\pos\pos_TestManageService;
use yii\data\Pagination;

/**
 * Created by yangjie
 * User: Administrator
 * Date: 14-10-16
 * Time: 下午16:27
 */
class ManagepaperController extends TeacherBaseController
{
    public $layout = "lay_user";

    /**
     * 设置试卷结构
     */
    public function actionIndex()
    {
        $proFirstime = microtime();
		$userId = user()->id;
		$grade='';
		$teacherClass = new pos_SchoolTeacherService();
		$teacherData = $teacherClass->searchTeacherClass($userId);
		foreach($teacherData->classList as $item){
			$grade =$item->gradeID;

		}
//		$edition = app()->request->getParam('edition',user()->getModel()->textbookVersion);
		$subjectId = app()->request->getParam('subjectId', loginUser()->getModel()->subjectID);
		$gradeId = app()->request->getParam('gradeId',$grade);
		$getType =app()->request->getParam('getType', '');
		$orderType =app()->request->getParam('orderType','');

		$pages = new Pagination();$pages->validatePage=false;
		$pages->pageSize = 10;
		$pagerServer = new pos_PaperManageService();
		$result = $pagerServer->searchPapeer($userId, $pages->getPage() + 1,$pages->pageSize,$getType, $gradeId,$subjectId,'',$orderType);

		$pages->totalCount = intval($result->countSize);
        \Yii::info('教师试卷管理 '.(microtime()-$proFirstime),'service');
		if (app()->request->isAjax) {
			return $this->renderPartial('_new_paperList', array('data' => $result->list, 'pages' => $pages));

		}

		return $this->render('searchPaper',array('data' => $result->list, 'pages' => $pages,'subjects'=>$subjectId,'gradeData'=>$grade));
    }




    //试卷详情
    public function actionPaperDetail(){

        $paperId = app()->request->getParam('paperId', '');
        $getType = app()->request->getParam('getType', '');

        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 10;

        $pagerServer = new pos_PaperManageService();
        $result = $pagerServer->queryPaper(user()->id, null, null, $getType, $paperId);

        if(empty($result->list)){
            return $this->notFound();
        }else{
            $pages->totalCount = intval($result->countSize);

            if($getType == 0){          //上传
                return $this->render('uppaperdetail',array('result'=>$result->list[0]));
            }elseif($getType == 1){     //组卷
                return $this->render('orgpaperdetail',array('result'=>$result->list[0]));
            }
        }


    }


    /**
     *  上传试卷
     */
    public function  actionUploadPaper()
    {
        $model = new PaperForm();
        $model->provience = loginUser()->getModel()->provience;
        $model->city = loginUser()->getModel()->city;
        $model->county = loginUser()->getModel()->country;
		$model->gradeID = loginUser()->getModel()->getUserInfoInClass()[0]['gradeID'];
		$model->subjectID = loginUser()->getModel()->subjectID;
		$model->versionID = loginUser()->getModel()->textbookVersion;

        if (isset($_POST['PaperForm'])) {
            $model->attributes = $_POST['PaperForm'];
            //处理图片
            $arr = $_POST['picurls'];
            foreach ($arr as $k => $v) {
                $tmp['images'][]['url'] = $v;
            }
            $model->paperRoute = json_encode($tmp);
            $paperServer = new pos_PaperManageService();
            $result = $paperServer->UploadPaper($model->paperName, $model->provience, $model->city, $model->county, $model->gradeID, $model->subjectID, $model->versionID, $model->knowledgePoint, $model->summary, user()->id, $model->paperRoute, 0, 0);
            if ($result->resCode == BaseService::successCode) {
                return $this->redirect(url('teacher/managepaper'));
            }
        }
        return $this->render('uploadPaper', array('model' => $model));
    }

    /**
     *  删除试卷
     */
    public function actionDeletePaper()
    {
        $paperId = app()->request->getParam('paperId');

        $paperServer = new pos_PaperManageService();
        $result = $paperServer->deletePaper($paperId);

        $jsonResult = new JsonMessage();
        if ($result->resCode == BaseService::successCode) {
            $jsonResult->success = true;
        } else {
            $jsonResult->success = false;
        }
        return $this->renderJSON($jsonResult);
    }

    /**
     *  测验列表
     */
    public function actionTestList()
    {
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize =10;

        $classId = app()->request->getParam('classId', '');
        $pages->params = array('classId' => $classId);

        $test = new pos_TestManageService();
        $result = $test->queryTest('', '', '', '', user()->id, $classId, $pages->getPage() + 1, $pages->pageSize);
        $pages->totalCount = intval($result->countSize);
        if (app()->request->isAjax) {
            return $this->renderPartial('_testListData', array('data' => $result->list, 'pages' => $pages));
            return;
        }
        return $this->render('testList', array('data' => $result->list, 'pages' => $pages));
    }

    /**
     * 上传的试卷的测验详情
     */
    public function actionViewTest($testId)
    {
        $examSubID=app()->request->getParam("examSubID");
        $testServer = new pos_TestManageService();
        $result = $testServer->queryTestInfoByID($testId);
        if (empty($result))     return $this->notFound();
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 2;
        $answerResult = $testServer->queryTestAllAnswerList($testId, $pages->currentPage + 1, $pages->pageSize);
//         章节树的获取
        $version = $result->version;
        $gradeID = $result->gradeId;
        $subjectID = $result->subjectId;
        $chapterTree = ChapterInfoModel::searchChapterPointToTree($subjectID, "", $version, "", $gradeID);
//      科目总评的获取
        $examSrever=new pos_ExamService();
        $evaluateResult = $examSrever->searchSubjectEvaluate($examSubID);
//        获取最高分和最低分

        $maxAndMin = $testServer->searchTheMaxAndMinScore($testId);
//        分数段的获取
        $sectionArray = array("data" => array(
            array("bottomlimit" => "61", "toplimit" => "70"),
            array("bottomlimit" => "71", "toplimit" => "90"),
            array("bottomlimit" => "91", "toplimit" => "100")
        ));
        $sectionJson = json_encode($sectionArray);
        $sectionResult = $testServer->queryNumberByInterval($testId, $sectionJson);
        $studentAnswer=$testServer->queryTestAllScoreList($testId);
        return $this->render('viewTest', array('data' => $result,
            "answerResult" => $answerResult,
            "pages" => $pages,
            "chapterTree" => json_encode($chapterTree),
            "evaluateResult" => $evaluateResult,
            "maxAndMin" => $maxAndMin,
            "sectionResult" => $sectionResult,
            "studentAnswer"=>$studentAnswer));
    }
    /**
     *组织的试卷的测验详情
     */
    public function actionOrganizeDetails($examID)
    {
        $examSubID=app()->request->getParam("examSubID");
        $testServer = new pos_TestManageService();
        $result = $testServer->queryTestInfoByIDOrgType($examID,user()->id,"","");
        if (empty($result)) return $this->notFound();
        $pages = new Pagination();$pages->validatePage=false;
        $pages->pageSize = 2;
        $answerResult = $testServer->queryTestAllAnswerList($examID, $pages->currentPage + 1, $pages->pageSize);
       $pages->totalCount=$answerResult->countSize;
        //         章节树的获取
        $version = $result->version;
        $gradeID = $result->gradeId;
        $subjectID = $result->subjectId;
        $chapterTree = ChapterInfoModel::searchChapterPointToTree($subjectID, "", $version, "", $gradeID);
//      科目总评的获取
        $examSrever=new pos_ExamService();
        $evaluateResult = $examSrever->searchSubjectEvaluate($examSubID);
//        获取最高分和最低分

        $maxAndMin = $testServer->searchTheMaxAndMinScore($examID);
//        分数段的获取
        $sectionArray = array("data" => array(
            array("bottomlimit" => "61", "toplimit" => "70"),
            array("bottomlimit" => "71", "toplimit" => "90"),
            array("bottomlimit" => "91", "toplimit" => "100")
        ));
        $sectionJson = json_encode($sectionArray);
        $sectionResult = $testServer->queryNumberByInterval($examID, $sectionJson);
        $studentAnswer=$testServer->queryTestAllScoreList($examID);
        return $this->render("organizeDetails", array('data' => $result,
            "answerResult" => $answerResult,
            "pages" => $pages,
            "chapterTree" => json_encode($chapterTree),
            "evaluateResult" => $evaluateResult,
            "maxAndMin" => $maxAndMin,
            "sectionResult" => $sectionResult,
        "studentAnswer"=>$studentAnswer));
    }

    /**
     *AJAX科目总评
     */
    public function actionSubjectEvaluate()
    {
        $learningPlan = app()->request->getParam("learningPlan");
        $kid = app()->request->getParam("kid");
//        $testID = app()->request->getParam("testID");
        $examSubID=app()->request->getParam("examSubID");
        $examServer = new pos_ExamService();
        $testResult = $examServer->subjectSummary($examSubID, user()->id, $kid, $learningPlan);
        $jsonResult = new JsonMessage();
        if ($testResult->resCode == BaseService::successCode) {
            $jsonResult->code = 1;
        } else {
            $jsonResult->code = 0;
        }
        $jsonResult->message = $testResult->resMsg;
        return $this->renderJSON($jsonResult);
    }

    /**
     *学生互相判卷
     */
    public function actionStudentCrossCheck()
    {
        $testID = app()->request->getParam("testID");
        $jsonResult = new JsonMessage();
        $testServer = new pos_TestManageService();
        $testResult = $testServer->studentCrossCheckTest(user()->id, $testID);
        $jsonResult->message = $testResult->resMsg;
        return $this->renderJSON($jsonResult);
    }
    /**
     *组卷类型的试卷预览
     */
    public function actionOrgPreview(){
        $examID=app()->request->getParam("examID");
        $testServer=new pos_TestManageService();
        $result=$testServer->queryTestInfoByIDOrgType($examID);
        return $this->render("orgPreview",array("result"=>$result));
    }

    /**
     *  上传测验
     */
    public function actionUploadTest()
    {
        $paperId = app()->request->getParam('paperId', '');
        $testName = app()->request->getParam('testName', '');
        $testTime = app()->request->getParam('testTime', '');
        $classId = app()->request->getParam('classId', '');

        $testServer = new pos_TestManageService();
        $testServer->createTest($paperId, $testName, $testTime, user()->id, $classId);

        return $this->redirect('testList');
    }




}