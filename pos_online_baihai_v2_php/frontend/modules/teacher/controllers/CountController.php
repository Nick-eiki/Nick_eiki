<?php
namespace frontend\modules\teacher\controllers;
use common\models\JsonMessage;
use frontend\components\TeacherBaseController;
use frontend\services\BaseService;
use frontend\services\pos\pos_ClassMembersService;
use frontend\services\pos\pos_ExamService;
use frontend\services\pos\pos_HomeWorkManageService;
use frontend\services\pos\pos_SchlHomMsgService;
use frontend\services\pos\pos_StudentScoreCountService;
use frontend\services\pos\pos_SuperManageService;
use Yii;
use yii\data\Pagination;

/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-12-4
 * Time: 下午3:21
 */
class CountController extends TeacherBaseController
{
    public $layout = 'lay_user';

    /**
     *班级成绩变动
     */
    public function actionClassScoreChange()
    {
        $classID = app()->request->getParam("classID");
        $classResult = loginUser()->getModel()->getClassInfo();
//        班级ID和班级名称的获取
        $classArray = array();
        foreach ($classResult as $v) {
            array_push($classArray, array("classID" => $v->classID, "className" => $v->className));
        }
        $superServer = new pos_SuperManageService();
        $superResult = $superServer->classScoreStastic($classID);
//        考试名称数组的获取
        $examNameArray = array();
        foreach ($superResult->list as $v) {
            array_push($examNameArray, $v->examName);
        }
//        总分平均分数组的获取
        $avgTotalScoreArray = array();
        foreach ($superResult->list as $v) {
            array_push($avgTotalScoreArray, $v->avgTotalScore ?: "");
        }
//        总分最高分数组的获取
        $maxTotalScoreArray = array();
        foreach ($superResult->list as $v) {
            array_push($maxTotalScoreArray, $v->maxTotalScore ?: "");
        }
//        总分最低分数组的获取
        $minTotalScoreArray = array();
        foreach ($superResult->list as $v) {
            array_push($minTotalScoreArray, $v->minTotalScore ?: "");
        }
//        考试名称和考试ID的获取
        $examArray = array();
        foreach ($superResult->list as $v) {
            array_push($examArray, array("examID" => $v->examID, "examName" => $v->examName));
        }
      return  $this->render("classScoreChange", array("classArray" => $classArray,
            "examName" => json_encode($examNameArray),
            "avgTotalScore" => json_encode($avgTotalScoreArray),
            "maxTotalScore" => json_encode($maxTotalScoreArray),
            "minTotalScore" => json_encode($minTotalScoreArray),
            "examArray" => $examArray,
        ));
    }

    /**
     *总分分数段片段
     */
    public function actionTotalScale()
    {
        $superServer = new pos_SuperManageService();
        $examID = app()->request->getParam("examID");
        $examServer = new pos_ExamService();
        $examResult = $examServer->queryExamById($examID, user()->id);
//        通过考试ID查询科目ID
        $subjectArray = array();
        foreach ($examResult->data->examSubList as $v) {
            array_push($subjectArray, array("subjectID" => $v->subjectID, "subjectName" => $v->subjectName));
        }
//        总分分数段学生分布
        $totalScoreArray = array(
            array(
                "low" => "100",
                "high" => "200"
            ),
            array(
                "low" => "201",
                "high" => "300",
            ),
            array(
                "low" => "301",
                "high" => "400",
            ),
            array(
                "low" => "401",
                "high" => "1100",
            )
        );
        $totalScoreNameArray = array();
        foreach ($totalScoreArray as $v) {
            array_push($totalScoreNameArray, $v["low"] . "到" . $v["high"]);
        }
        $totalScoreJson = json_encode($totalScoreArray);
        $totalSection = $superServer->classScoreTotalPeo($examID, $totalScoreJson);
        $totalSectionArray = array();
        foreach ($totalSection->list as $v) {
            array_push($totalSectionArray, array("value" => $v->peos, "name" => $v->low . "到" . $v->high));
        };
        $totalSectionJson = json_encode($totalSectionArray);

        return $this->renderPartial("total_scale", array("totalScoreName" => isset($totalScoreNameArray) ? json_encode($totalScoreNameArray) : json_encode(array()),
            "totalSectionJson" => isset($totalSectionJson) ? $totalSectionJson : json_encode(array()),
            "subjectArray" => isset($subjectArray) ? $subjectArray : array(),
        ));
    }

    /**
     *单科成绩片段
     */
    public function actionSubjectScale()
    {
        $superServer = new pos_SuperManageService();
        $subjectID = app()->request->getParam("subjectID");
        $examID = app()->request->getParam("examID");
        $subScoreArray = array(
            array(
                "low" => "0",
                "high" => "70"
            ),
            array(
                "low" => "71",
                "high" => "80",
            ),
            array(
                "low" => "81",
                "high" => "90",
            ),
            array(
                "low" => "91",
                "high" => "100",
            )
        );
        $subScoreJson = json_encode($subScoreArray);
        $subScoreNameArray = array();
        foreach ($subScoreArray as $v) {
            array_push($subScoreNameArray, $v["low"] . "到" . $v["high"]);
        }
        $subScoreNameJson = json_encode($subScoreNameArray);
        $subSection = $superServer->classScoreSubPeo($examID, $subjectID, $subScoreJson);
        $subSectionArray = array();
        foreach ($subSection->list[0]->peoList as $v) {
            array_push($subSectionArray, array("value" => $v->peos, "name" => $v->low . "到" . $v->high));
        }
        $subSectionJson = json_encode($subSectionArray);
        return $this->renderPartial("subject_scale", array(
            "subSectionJson" => isset($subSectionJson) ? $subSectionJson : json_encode(array()),
            "subScoreName" => isset($subScoreNameArray) ? $subScoreNameJson : json_encode(array())
        ));
    }

    /**
     *测验的成绩分布
     */
    public function actionTestScale()
    {
        $superServer = new pos_SuperManageService();
//        测验列表的获取
        $testList = $superServer->allOfTeacher(user()->id);
        $testArray = $testList->examList;
        $testID = app()->request->getParam("testID");
        $sectionArray = array(
            array(
                "low" => "0",
                "high" => "60",
            ),
            array(
                "low" => "61",
                "high" => "80",
            ),
            array(
                "low" => "81",
                "high" => "100",
            ),
        );
        $sectionNameArray = array();
        foreach ($sectionArray as $v) {
            array_push($sectionNameArray, $v["low"] . "到" . $v["high"]);
        }
        $sectionJson = json_encode($sectionArray);
        $scoreResult = $superServer->testScorePeo($testID, $sectionJson);
        $scoreDataArray = array();
        foreach ($scoreResult->list as $v) {
            array_push($scoreDataArray, array("value" => $v->peos, "name" => $v->low . "到" . $v->high));
        }
        return    $this->render("testScale", array("testArray" => $testArray

        ));
    }

    /**
     *获取测验的成绩分布的片段
     */
    public function actionGetTestScale()
    {
        $superServer = new pos_SuperManageService();
        $testID = app()->request->getParam("testID");
        $sectionArray = array(
            array(
                "low" => "0",
                "high" => "100",
            ),
            array(
                "low" => "101",
                "high" => "200",
            ),
            array(
                "low" => "201",
                "high" => "300",
            ),
        );
        $sectionNameArray = array();
        foreach ($sectionArray as $v) {
            array_push($sectionNameArray, $v["low"] . "到" . $v["high"]);
        }
        $sectionJson = json_encode($sectionArray);
        $scoreResult = $superServer->testScorePeo($testID, $sectionJson);
        $scoreDataArray = array();
        foreach ($scoreResult->list as $v) {
            array_push($scoreDataArray, array("value" => $v->peos, "name" => $v->low . "到" . $v->high));
        }
        return $this->renderPartial("get_test_scale", array("sectionNameJson" => json_encode($sectionNameArray),
            "scoreDataJson" => json_encode($scoreDataArray)));
    }

    /**
     *作业完成比例
     */
    public function actionTaskCompleteScale()
    {
        $superServer = new pos_SuperManageService();
        $taskResult = $superServer->teaHomeworkStasticCmp(user()->id);
//        横轴作业名称个获取
        $scoreNameArray = array();
        foreach ($taskResult->list as $key => $v) {
            array_push($scoreNameArray, "(" . ($key + 1) . ")" . ($v->name ?: ""));
        }
//        纵轴完成作业比例的获取
        $scoreDataArray = array();
        foreach ($taskResult->list as $v) {
            array_push($scoreDataArray, ($v->rate ?: 0));
        }


      return  $this->render("taskCompleteScale", array("scoreNameJson" => $scoreNameArray,
            "scoreDataJson" => $scoreDataArray));
    }

    /**
     *个人统计
     */
    public function actionPersonalStatics()
    {
        $classID = app()->request->getParam("classID");
        $classServer = new pos_ClassMembersService();
        $classResult = $classServer->loadRegisteredMembers($classID, 1);
        return $this->render("newPersonalStatics", array("classResult" => $classResult));
    }

    /**
     *AJAX作业完成度
     */
    public function actionAllTaskRate()
    {
        $userID = app()->request->getParam("userID");
        $superServer = new pos_SuperManageService();
        $result = $superServer->stuHomeworkStasticCmp($userID);
        $dataArray = array(array("value" => $result->completeCnt, "name" => "已完成"), array("value" => ($result->allCnt - $result->completeCnt), "name" => "未完成"));
        return $this->renderPartial("_all_task_rate", array("data" => json_encode($dataArray)));
    }

    /**
     *AJAX单科作业完成度
     */
    public function actionSubjectTaskRate()
    {
        $userID = app()->request->getParam("userID");
        $server = new pos_StudentScoreCountService();
        $result = $server->allSubjectFinishedPer($userID);
        $subjectArray = array();
        foreach ($result->list as $v) {
            array_push($subjectArray, $v->subName);
        }
        $dataArray = array();
        foreach ($result->list as $v) {
            array_push($dataArray, $v->rate);
        }
        return $this->renderPartial("_subject_task_rate", array("subject" => json_encode($subjectArray), "data" => json_encode($dataArray)));
    }

    /**
     *AJAX成绩变动
     */
    public function actionScoreChange()
    {
        $jsonMessage = new JsonMessage();
        $subjectID = app()->request->getParam("subjectID");
        $userID = app()->request->getParam("userID");
        $classID=app()->request->getParam("classID");
        $year=app()->request->getBodyParam('year',3);
        $server = new pos_StudentScoreCountService();
        //           获取当前班级考试总分和单科满分的最高分
        $examServer=new pos_ExamService();
        $examResult=$examServer->getAllExamHighFull($classID,$subjectID);
        $fullScore=$examResult->fullScore;
        $thisTime=date('Y-m-d',time());
        if($year==1){
            $pastTime=date('Y-m-d',time()-60*60*24*30*6);
        }elseif($year==2){
            $pastTime=date('Y-m-d',time()-60*60*24*30*12);
        }elseif($year==3){
            $pastTime = date('Y-m-d', time() - 60 * 60 * 24 * 30 * 12 * 3);
        }
        if ($subjectID == null) {
            $result = $server->StudentTotalScore($userID, $pastTime, $thisTime);
            if($result->listSize!=0) {
                $jsonMessage->success = true;
                $subjectArray = array("总分");
                $examArray = array();
                $examNameArray=array();
                foreach ($result->list as $k=>$v) {
                    array_push($examArray, $k+1);
                    array_push($examNameArray,$v->examName);
                }

                $dataArray = array();
                foreach ($result->list as $v) {
                    array_push($dataArray, $v->totalScore);
                }
                $jsonMessage->data =  $this->renderPartial("_score_change", array("subject" => json_encode($subjectArray),
                    "exam" => json_encode($examArray),
                    'examNameArray'=>$examNameArray,
                    "data" => json_encode($dataArray),
                    "subjectID" => $subjectID,
                    "fullScore" => $fullScore,
                    'year'=>$year
                ), true);
            }else{
                $jsonMessage->message="对不起，当前学生的所有考试都没有成绩";
            }
        } else {
            $result = $server->SubjectScoreChange($userID, $subjectID, $pastTime, $thisTime);
            if (!empty($result->list)) {
                $jsonMessage->success = true;
                $subjectArray = array($result->list[0]->subjectName);
                $examArray = array();
                $examNameArray=array();
                foreach ($result->list as $k=>$v) {
                    array_push($examArray,$k+1);
                    array_push($examNameArray, $v->examName);
                }
                $dataArray = array();
                foreach ($result->list as $v) {
                    array_push($dataArray, $v->personalScore == null ? 0 : $v->personalScore);
                }
                $jsonMessage->data =  $this->renderPartial("_score_change", array("subject" => json_encode($subjectArray),
                    "exam" => json_encode($examArray),
                    'examNameArray'=>$examNameArray,
                    "data" => json_encode($dataArray),
                    "subjectID" => $subjectID,
                    "fullScore"=>$fullScore,
                    'year'=>$year
                ), true);
            } else {
                $jsonMessage->message = "没有关于这个科目的考试";
            }
        }
        return $this->renderJSON($jsonMessage);
    }

    /**
     *AJAX考试成绩曲线
     */
    public function actionExamScoreCurve()
    {  $jsonResult=new JsonMessage();
        $classID = app()->request->getParam("classID");
        $server = new pos_SuperManageService();
        $subjectID = app()->request->getParam("subjectID");
        $year=app()->request->getBodyParam('year',3);
        $thisTime=date('Y-m-d',time());
        if($year==1){
            $pastTime=date('Y-m-d',time()-60*60*24*30*6);
        }elseif($year==2){
            $pastTime=date('Y-m-d',time()-60*60*24*30*12);
        }elseif($year==3){
            $pastTime = date('Y-m-d', time() - 60 * 60 * 24 * 30 * 12 * 3);
        }
        if ($subjectID == null) {
            $result = $server->everyExamTotalScoreCha($classID, "", "", $pastTime,$thisTime);
        } else {
            $result = $server->everyExamSubScoreCha($classID, $subjectID, $pastTime,$thisTime);
        }
        if($result->listSize!=0) {
            $jsonResult->success=true;
            $maxScoreArray = array();
            foreach ($result->list as $v) {
                array_push($maxScoreArray, intval($v->maxScore));
            }

            $minScoreArray = array();
            foreach ($result->list as $v) {
                array_push($minScoreArray, intval($v->minScore));
            }
            $avgScoreArray = array();
            foreach ($result->list as $v) {
                array_push($avgScoreArray, intval($v->avgScore));
            }
            $examArray = array();
            $examNameArray=array();
            foreach ($result->list as $k=>$v) {
                array_push($examArray, $k+1);
                array_push($examNameArray, $v->examName);
            }
//           获取当前班级考试总分和单科满分的最高分
            $examServer=new pos_ExamService();
            $examResult=$examServer->getAllExamHighFull($classID,$subjectID);
            $fullScore=$examResult->fullScore;
            $jsonResult->data=  $this->renderPartial("_exam_score_curve"
                , array("maxScore" => json_encode($maxScoreArray),
                    "minScore" => json_encode($minScoreArray),
                    "avgScore" => json_encode($avgScoreArray),
                    "exam" => json_encode($examArray),
                    "subjectID"=>$subjectID,
                    "fullScore"=>intval($fullScore),
                    'examNameArray'=>$examNameArray,
                    'year'=>$year
                ),true
            );
        }else{
            $jsonResult->message="没有该科目的考试";
        }
        return $this->renderJSON($jsonResult);
    }

    /**
     *AJAX排名变动
     */
    public function actionRankingChange()
    {
        $jsonMessage = new JsonMessage();
        $classServer=new pos_ClassMembersService();
//        根据班级人数确定折线图y轴坐标长度
        $classID=app()->request->getParam("classID");
        $classResult=$classServer->classMemCount($classID);
        $studentNum=$classResult["studentNum"];
        if($studentNum==1){
            $yNum=6;
        }else{
            $yNum=(floor(($studentNum-2)/5)+1)*5+1;
        }
        $server = new pos_StudentScoreCountService();
        $userID = app()->request->getParam("userID");
        $subjectID = app()->request->getParam("subjectID");
        $year=app()->request->getBodyParam('year',3);
        $thisTime=date('Y-m-d',time());
        if($year==1){
            $pastTime=date('Y-m-d',time()-60*60*24*30*6);
        }elseif($year==2){
            $pastTime=date('Y-m-d',time()-60*60*24*30*12);
        }elseif($year==3){
            $pastTime = date('Y-m-d', time() - 60 * 60 * 24 * 30 * 12 * 3);
        }
        if ($subjectID == null) {

            $result = $server->StudentScoreRanking($userID, $pastTime, $thisTime);
            if($result->listSize!=0) {
                $jsonMessage->success = true;
//            获取考试次数
                $xNum = $result->listSize;
                $subjectArray = array("总排名");
                $examArray = array();
                $examNameArray=array();
                foreach ($result->list as $k=>$v) {
                    array_push($examArray, $k+1);
                    array_push($examNameArray,$v->examName);
                }
                $dataArray = array();
                foreach ($result->list as $v) {
                    array_push($dataArray, -$v->ranking);
                }
                $jsonMessage->data =  $this->renderPartial("_ranking_change", array("subject" => json_encode($subjectArray),
                        "subjectID" => $subjectID,
                        'year'=>$year,
                        'examNameArray'=>$examNameArray,
                        "exam" => json_encode($examArray),
                        "data" => json_encode($dataArray),
                        "yNum" => $yNum,
                        "xNum" => $xNum
                    ), true
                );
            }else{
                $jsonMessage->message="对不起，当前学生还没有成绩";
            }
        } else {
            $result = $server->SubjectScoreChange($userID, $subjectID, $pastTime, $thisTime);
            if (!empty($result->list)) {
                //            获取考试次数
                $xNum=$result->listSize;
                $jsonMessage->success = true;
                $subjectArray = array($result->list[0]->subjectName);
                $examArray = array();
                $examNameArray=array();
                foreach ($result->list as $k=>$v) {
                    array_push($examArray, $k+1);
                    array_push($examNameArray,$v->examName);
                }
                $dataArray = array();
                foreach ($result->list as $v) {
                    array_push($dataArray, $v->ranking==null?"":-$v->ranking);
                }
                $jsonMessage->data =  $this->renderPartial("_ranking_change", array("subject" => json_encode($subjectArray),
                        "subjectID" => $subjectID,
                        'year'=>$year,
                        'examNameArray'=>$examNameArray,
                        "exam" => json_encode($examArray),
                        "data" => json_encode($dataArray),
                        "yNum"=>$yNum,
                        "xNum"=>$xNum
                    ), true
                );
            } else {
                $jsonMessage->message = "没有关于这个科目的考试";
            }
        }
        return $this->renderJSON($jsonMessage);

    }
    /**
     *AJAX考试三率变化曲线
     */
    public function actionThreeChance(){
        $jsonMessage=new JsonMessage();
        $year=app()->request->getBodyParam('year',3);
        $server=new pos_SuperManageService();
        $classID=app()->request->getParam("classID");
        $subjectID=app()->request->getParam("subjectID");
        $thisTime=date('Y-m-d',time());
        if($year==1){
            $pastTime=date('Y-m-d',time()-60*60*24*30*6);
        }elseif($year==2){
            $pastTime=date('Y-m-d',time()-60*60*24*30*12);
        }elseif($year==3){
            $pastTime = date('Y-m-d', time() - 60 * 60 * 24 * 30 * 12 * 3);
        }
        $result = $server->examThreeScoreStat($classID, $subjectID,$pastTime,$thisTime);
        if($result->listSize!=0){
            $jsonMessage->success=true;
//        高分率
            $highRateArray = array();
            foreach ($result->list as $v) {
                array_push($highRateArray, $v->highRate*100);
            }
//        低分率
            $lowRateArray = array();
            foreach ($result->list as $v) {
                array_push($lowRateArray, $v->lowRate*100);
            }
//        及格率
            $passRateArray = array();
            foreach ($result->list as $v) {
                array_push($passRateArray, $v->passRate*100);
            }
//        考试名称
            $examNameArray = array();
            $examArray=array();
            foreach ($result->list as $k=>$v) {
                array_push($examNameArray, $v->examName);
                array_push($examArray, $k+1);
            }
            $jsonMessage->data=  $this->renderPartial("_three_chance",array(
                "exam"=>json_encode($examArray),
                'examNameArray'=>$examNameArray,
                "highRate"=>json_encode($highRateArray),
                "lowRate"=>json_encode($lowRateArray),
                "passRate"=>json_encode($passRateArray),
                "subjectID"=>$subjectID,
                'year'=>$year
            ),true);
        }else{
            if($subjectID==null){
                $jsonMessage->message="对不起，当前学生的所有考试还没有成绩";
            }else {
                $jsonMessage->message = "没有该科目的考试";
            }
        }
        return $this->renderJSON($jsonMessage);

    }

    /**
     *班级统计
     */
    public function actionClassStatics()
    {
        $classID = app()->request->getParam("classID");
        $server = new pos_SuperManageService();
        $result = $server->everyExamTotalScoreCha($classID, "", "", "1010-01-01", "2019-01-01");
        $examArray = array();
        foreach ($result->list as $v) {
            $examArray[$v->examID] = $v->examName;
        }
        return  $this->render("currentClassStatics", array("examArray" => $examArray));
    }

    /**
     *AJAX班级作业完成程度
     */
    public function actionClassComRate()
    {
        $classID = app()->request->getParam("classID");
        $server = new pos_SuperManageService();
        $result = $server->homeworkComRate($classID, "1900-01-01", "2015-09-01");
        $dataArray = array(array("value" => $result->completeCnt, "name" => "已完成"), array("value" => ($result->allCnt - $result->completeCnt), "name" => "未完成"));
        return $this->renderPartial("_class_com_rate", array("data" => json_encode($dataArray)));
    }

    /**
     *AJAX单科作业完成程度
     */
    public function actionClassHomSubRate()
    {
        $classID = app()->request->getParam("classID");
        $server = new pos_SuperManageService();
        $result = $server->hkSubComRate($classID, "1900-01-01", "2018-10-10");
        $subjectArray = array();
        foreach ($result->list as $v) {
            array_push($subjectArray, $v->subjectName);
        }
        $dataArray = array();
        foreach ($result->list as $v) {
            array_push($dataArray, $v->rate);
        }
        return $this->renderPartial("_class_hom_sub_rate", array("subject" => json_encode($subjectArray), "data" => json_encode($dataArray)));
    }




    /**
     *AJAX获取考试下面对应科目
     */
    public function actionExamSubject()
    {
        $examID = app()->request->getParam("examID");
        $server = new pos_SuperManageService();
        $result = $server->examSubAvgScore($examID);
        $subjectArray = array();
        foreach ($result->list as $v) {
            $subjectArray[$v->subjectID] = $v->subjectName;
        }
         return $this->renderPartial("exam_subject", array("subjectArray" => $subjectArray));
    }

    /**
     *AJAX考试成绩分布
     */
    public function actionExamScoreDis()
    {
        $server = new pos_SuperManageService();
        $examID = app()->request->getParam("examID");
        $subjectID = app()->request->getParam("subjectID");
        $scoreArray = array(array("low" => 0, "high" => 50), array("low" => 51, "high" => 80), array("low" => 81, "high" => 1111));
        if ($subjectID == null) {
            $result = $server->classScoreTotalPeo($examID, json_encode($scoreArray));
            $sectionArray = array();
            foreach ($result->list as $v) {
                array_push($sectionArray, $v->low . "到" . $v->high);
            }
            $numberArray = array();
            foreach ($result->list as $v) {
                array_push($numberArray, array("value" => $v->peos, "name" => $v->low . "到" . $v->high));
            }
        } else {
            $result = $server->classScoreSubPeo($examID, $subjectID, json_encode($scoreArray));
            $sectionArray = array();
            foreach ($result->list[0]->peoList as $v) {
                array_push($sectionArray, $v->low . "到" . $v->high);
            }
            $numberArray = array();
            foreach ($result->list[0]->peoList as $v) {
                array_push($numberArray, array("value" => $v->peos, "name" => $v->low . "到" . $v->high));
            }
        }
        return $this->renderPartial("exam_score_dis", array("section" => json_encode($sectionArray), "number" => json_encode($numberArray)));
    }
    /**
     *藤条棍
     */
    public function actionNewBearChild(){
        $week = date('w');
        $thisMonday =  date('Y-m-d', strtotime('+'. 1-$week.'days'));
        $today=date("Y-m-d",time());
        $lastSunday=date("Y-m-d",(strtotime('+'. 1-$week.'days')-3600*24));
        $lastMonday=date("Y-m-d",(strtotime('+'. 1-$week.'days')-3600*24*7));
        return   $this->render("newBearChild",array("thisMonday"=>$thisMonday,
                 "today"=>$today,
            "lastSunday"=>$lastSunday,
            "lastMonday"=>$lastMonday
        ));
    }

    /**
     * @throws CException
     * 获取作业列表
     */
    public function actionGetHomwList(){
        //        作业详情
        $homeworkServer = new pos_HomeWorkManageService();
        $beginTime=app()->request->getParam("beginTime");
        $endTime=app()->request->getParam("endTime");
        $classID = app()->request->getParam("classID");
        $subjectID = app()->request->getParam("subjectID");
        $subject = $subjectID;
        $orderBy = app()->request->getParam("orderBy");
        $type = null;
        if ($subjectID == 'all') {
            $subject = null;
            $type = 2;
        } elseif ($subjectID != 'all' && $subjectID != null) {
            $type = 1;
        }
        $homeworkResult = $homeworkServer->stasClsHwkNotdone($classID, $type, $subject, $orderBy,$beginTime,$endTime);

            return $this->renderPartial("bear_homework", array("homeworkResult" => $homeworkResult,
                "classID" => $classID,
                "subject" => $subject,
                "orderBy" => $orderBy,
                "endTime"=>$endTime
            ));
    }

    /**
     *获取考试列表
     */
    public function actionGetExamList()
    {
        $classID = app()->request->getParam("classID");
        $examName=app()->request->getParam("examName");
        $examServer = new pos_ExamService();
        $pages = new Pagination();$pages->validatePage=false;
        $pages->route = 'teacher/count/get-exam-list';
        $pages->pageSize = 9;
        $examResult = $examServer->queryExamList($classID, user()->id, "", $pages->getPage() + 1, $pages->pageSize,$examName);
        $pages->totalCount = $examResult->data->countSize;
        $pages->params["classID"]=$classID;
        $pages->params["examName"]=$examName;
        return $this->renderPartial("_exam_list", array("examResult" => $examResult, "pages" => $pages));
    }

    /**
     *获取考试排名变化列表
     */
    public function actionGetChangeList()
    {
        $subjectID = app()->request->getParam("subjectID");
        $orderType = 1;
        $ascDesc = app()->request->getParam("ascDesc");
        if ($subjectID == "all" || $subjectID == null) {
            $orderType = 2;
        }
        if($subjectID==null){
            $ascDesc=1;
        }
        if($subjectID=="ranking"){
            $orderType=3;
        }


        $examID = app()->request->getParam("examID");
        $examServer = new pos_ExamService();
        $examResult = $examServer->examRankingStas($examID, $orderType, $subjectID, $ascDesc);
        return $this->renderPartial("_change_list", array("examResult" => $examResult, "examID" => $examID, "ascDesc" => $ascDesc, "subjectID" => $subjectID));
    }

    /**
     *手动发送消息告家长
     */
    public function actionSendMsgToParents(){
        $students=app()->request->getParam("students");
        $server=new pos_HomeWorkManageService();
        $jsonResult=new JsonMessage();
        $result=$server->sendHwkNotdoneMsg($students,user()->id);
        if($result->resCode==BaseService::successCode){
            $jsonResult->success=true;
        }
        $jsonResult->message=$result->resMsg;
        return $this->renderJSON($jsonResult);
    }

	/**
    * 消息发送历史
    */

	public function actionMessageHistory()
	{

		//获取当前日期
		//本周时间
		$week = date('w');

		$begin =  date('Y.m.d', strtotime('+'. 1-$week.'days'));
		//$end = date('Y.m.d', strtotime('+'. 7-$week.'days'));
		$end = date('Y.m.d',time());
		//上周时间
		$lastEnd=date("Y.m.d",(strtotime("last monday")-3600*24));
		$lastBegin=date("Y.m.d",(strtotime("last monday")-3600*24*7));

		 return $this->render('messageHistory', array( 'lastBegin'=>$lastBegin, 'lastEnd'=>$lastEnd, 'begin'=>$begin, 'end'=>$end));
	}

	/**
	 * @throws CException
	 * 每天
	 */
	public function actionGetMessageDayList(){
		$classId = app()->request->getParam('classId');
		$model=new pos_SchlHomMsgService();

		$week = date('w');
		$beginDate =  date('Y-m-d', strtotime('+'. 1-$week.'days'));
		$endDate = date('Y-m-d', strtotime('+'. 7-$week.'days'));

		$result = $model->queryMsgHwkNotDone($classId, "", "", "" , $beginDate, $endDate);
		return $this->renderPartial('_message_day',array('result'=>$result));

	}

	/**
	 * @throws CException
	 * 一周
	 */
	public function actionGetMessageWeekList(){
		$classId = app()->request->getParam('classId');
		$model=new pos_SchlHomMsgService();

		$week = date('w');
		$beginDate =  date('Y-m-d', strtotime('+'. 1-$week.'days'));
		$endDate = date('Y-m-d', strtotime('+'. 7-$week.'days'));

		$result = $model->queryMsgHwkNotDone($classId, "", "", "" , $beginDate, $endDate);
		return $this->renderPartial('_message_week',array('result'=>$result));

	}

	/**
	 * @throws CException
	 * 每天
	 */
	public function actionGetMessageLastDayList(){
		$classId = app()->request->getParam('classId');
		$model=new pos_SchlHomMsgService();

		$lastMonday=date("Y-m-d",(strtotime("last monday")-3600*24*7));
		$lastSunday=date("Y-m-d",(strtotime("last monday")-3600*24));
		$lastResult = $model->queryMsgHwkNotDone($classId, null, null, null,  $lastMonday, $lastSunday);
		return $this->renderPartial('_message_last_day',array('lastResult'=>$lastResult));

	}

	/**
	 * @throws CException
	 * 一周
	 */
	public function actionGetMessageLastWeekList(){
		$classId = app()->request->getParam('classId');
		$model=new pos_SchlHomMsgService();

		$lastSunday=date("Y-m-d",(strtotime("last monday")-3600*24));
		$lastMonday=date("Y-m-d",(strtotime("last monday")-3600*24*7));

		$lastResult = $model->queryMsgHwkNotDone($classId, null, null, null,  $lastMonday, $lastSunday);

		return $this->renderPartial('_message_last_week',array('lastResult'=>$lastResult));

	}
}