<?php

namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: a
 * Date: 14-6-27
 * Time: 上午10:59
 */

/**
 * Class TeacherService
 */
class pos_TestManageService extends BaseService
{


    /**
     * @return ServiceJsonResult
     */
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/testManage?wsdl");
    }


    /**
     * 创建测试
     * @param $paperId
     * @param $testName
     * @param $testTime
     * @return ServiceJsonResult
     * @throws \Camcima\Exception\InvalidParameterException
     */
    public function   createTest($paperId, $testName, $testTime, $userId, $classId)
    {
        $soapResult = $this->_soapClient->createTest(array("paperId" => $paperId, 'testName' => $testName, 'testTime' => $testTime, 'userId' => $userId, 'classId' => $classId));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr, true);
        $result = $this->mapperJsonResult($json);

        return $result;
    }


    /**
     * 查询测试
     * @param $testId
     * @param $paperId
     * @param string $testName
     * @param string $testTime
     * @return array
     * @throws \Camcima\Exception\InvalidParameterException
     */
    public function  queryTest($testId = '', $paperId = '', $testName = '', $testTime = '', $userId = '', $classId = '', $currPage = 1, $pageSize = 10)
    {
        $soapResult = $this->_soapClient->queryTest(array("testId" => $testId, 'paperId' => $paperId, 'testName' => $testName, 'userId' => $userId, 'classId' => $classId, 'testTime' => $testTime, 'currPage' => $currPage, 'pageSize' => $pageSize));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }


    /**
     * 修改测试
     * @param $testId
     * @param $paperId
     * @param string $testName
     * @param string $testTime
     * @return ServiceJsonResult
     * @throws \Camcima\Exception\InvalidParameterException
     */
    public function  updateTest($testId, $paperId, $testName = '', $testTime = '')
    {
        $soapResult = $this->_soapClient->updateTest(array("testId" => $testId, 'paperId' => $paperId, 'testName' => $testName, 'testTime' => $testTime));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr, true);
        $result = $this->mapperJsonResult($json);
        return $result;
    }


    /**
     * 删除测试
     * @param $testId
     * @return ServiceJsonResult
     * @throws \Camcima\Exception\InvalidParameterException
     */
    public function  deleteTest($testId)
    {
        $soapResult = $this->_soapClient->deleteTest(array("testId" => $testId));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr, true);
        $result = $this->mapperJsonResult($json);

        return $result;
    }

    /**
     * 3.20.8.    查询测验列表（通过学生id）
     * @param $studentID
     * @param $currPage
     * @param $pageSize
     */
    public function queryTestListByStudent($studentID, $currPage, $pageSize)
    {
        $soapResult = $this->_soapClient->queryTestListByStudent(
            array(
                "studentID" => $studentID,
                "currPage" => $currPage,
                "pageSize" => $pageSize
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.20.9.    查询测验详情通过测验id
     * @param $testID
     * @return array
     */
    public function queryTestInfoByID($testID)
    {
        $soapResult = $this->_soapClient->queryTestInfoByID(
            array(
                "examID" => $testID
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.20.14.    查询测验学生的答案
     * @param $studentID
     * @param $testID
     * @param string $testAnswerID
     * @return array
     */
    public function queryTestAnswerImages($studentID, $examSubID, $testAnswerID = "")
    {
        $soapResult = $this->_soapClient->queryTestAnswerImages(
            array(
                "studentID" => $studentID,
                "examSubID" => $examSubID,
                "testAnswerID" => $testAnswerID
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }



    /**
     * 3.20.5.    查询测验答案列表及教师批改
     * @param $testID
     * @param $currPage
     * @param $pageSize
     * @return array
     */
    public function queryTestAllAnswerList($testID, $currPage, $pageSize)
    {
        $soapResult = $this->_soapClient->queryTestAllAnswerList(
            array(
                "examID" => $testID,
                "currPage" => $currPage,
                "pageSize" => $pageSize
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.20.6.    批阅试卷
     * @param $teacherID
     * @param $testAnswerID
     * @param $tID
     * @param $checkInfoJson
     * @return ServiceJsonResult
     */
    public function commitCheckInfo($teacherID, $testAnswerID, $tID, $checkInfoJson)
    {
        $soapResult = $this->_soapClient->commitCheckInfo(
            array(
                "teacherID" => $teacherID,
                "testAnswerID" => $testAnswerID,
                "tID" => $tID,
                "checkInfoJson" => $checkInfoJson,
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;
    }

    /**
     * 3.20.7.    学生答案批改状态修改
     * @param $testAnswerID
     * @param $teacherID
     * @param $isCheck
     * @param $summary
     * @param $testScore
     * @return ServiceJsonResult
     */
    public function updateCheckState($testAnswerID, $teacherID, $isCheck, $summary, $testScore)
    {
        $soapResult = $this->_soapClient->updateCheckState(
            array(
                "teacherID" => $teacherID,
                "testAnswerID" => $testAnswerID,
                "isCheck" => $isCheck,
                "summary" => $summary,
                "testScore" => $testScore
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;
    }

    /**
     * 3.20.18.    制定测验总评
     * @param $testID
     * @param $teacherID
     * @param $kid
     * @param $learningPlan
     * @return ServiceJsonResult
     */
    public function subGeneralSummary($testID, $teacherID, $kid, $learningPlan)
    {
        $soapResult = $this->_soapClient->subGeneralSummary(
            array(
                "testID" => $testID,
                "teacherID" => $teacherID,
                "kid" => $kid,
                "learningPlan" => $learningPlan
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;
    }

    /**
     * 3.20.19.    查询测验总评
     * @param $testID
     * @return array
     */
    public function searchGeneralSummary($testID)
    {
        $soapResult = $this->_soapClient->searchGeneralSummary(
            array(
                "testID" => $testID,

            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.20.20.    查询最高分和最低分
     * @param $testID
     * @return array
     */
    public function searchTheMaxAndMinScore($testID)
    {
        $soapResult = $this->_soapClient->searchTheMaxAndMinScore(
            array(
                "examID" => $testID
            ));

        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.20.21.    查询分数区间人数
     * @param $testID
     * @return array
     */
    public function queryNumberByInterval($testID, $interval)
    {
        $soapResult = $this->_soapClient->queryNumberByInterval(
            array(
                "examID" => $testID,
                "interval" => $interval
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.20.22.    相互判卷
     * @param $teacherID
     * @param $testID
     * @return ServiceJsonResult
     */
    public function studentCrossCheckTest($teacherID, $testID)
    {
        $soapResult = $this->_soapClient->studentCrossCheckTest(
            array(
                "testID" => $testID,
                "teacherID" => $teacherID
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;
    }

    /**
     * 3.20.23.	查询测验详情通过测验id(在线组卷类型使用)
     * @param $testID
     * @param $userID
     * @param $currPage
     * @param $pageSize
     * @return array
     */
    public function queryTestInfoByIDOrgType($testID,$userID="",$currPage="",$pageSize=""){
        $soapResult = $this->_soapClient->queryTestInfoByIDOrgType(
            array(
                "examID" => $testID,
               "userID"=>$userID,
                "currPage"=>$currPage,
                "pageSize"=>$pageSize
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }


    /**
     *3.20.25.	用于批改的答案查询组卷类型
     * @param $testAnswerID
     * @return array
     */
    public function querytestAllAnswerPicList($testAnswerID){
        $soapResult = $this->_soapClient->querytestAllAnswerPicList(
            array(
               "testAnswerID"=>$testAnswerID
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == BaseService::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.20.24.	批阅试卷（组卷类型使用）
     * @param $teacherID
     * @param $testAnswerID
     * @param $answerId
     * @param $picId
     * @param $checkJson
     * @return ServiceJsonResult
     */
    public function commitCheckInfoForOrgPaper($teacherID,$testAnswerID,$answerId,$picId,$checkJson){
        $soapResult = $this->_soapClient->commitCheckInfoForOrgPaper(
            array(
             "teacherID"=>$teacherID,
                "testAnswerID"=>$testAnswerID,
                "answerId"=>$answerId,
                "picId"=>$picId,
                "checkJson"=>$checkJson
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;
    }

    /**
     * 3.42.48.	查询测验成绩列表
     * @param $examID
     * @return array
     */
    public function queryTestAllScoreList($examID){
        $soapResult = $this->_soapClient->queryTestAllScoreList(
            array(
        "examID"=>$examID
            ));
        $result = $this->soapResultToJsonResult($soapResult);
         if($result->resCode==BaseService::successCode){
             return $result->data;
         }
        return array();
    }


}