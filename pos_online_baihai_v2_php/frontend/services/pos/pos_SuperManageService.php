<?php
namespace frontend\services\pos;
use ArrayObject;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-12-4
 * Time: 下午3:37
 */
class pos_SuperManageService extends BaseService{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/superManage?wsdl");
    }

    /**
     * 3.38.1.	班级成绩变动
     * @param $classID
     * @return array
     */
    public function classScoreStastic($classID){
        $soapResult = $this->_soapClient->classScoreStastic(
            array(
                "classId"=>$classID
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.38.2.	班级考试成绩总分分布
     * @param $examID
     * @param $scoreJson
     * @return array
     */
    public function classScoreTotalPeo($examID,$scoreJson){
       $soapResult = $this->_soapClient->classScoreTotalPeo(
           array(
              "examId"=>$examID,
               "scoreJson"=>$scoreJson
           )
       );
       $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
       $json = json_decode($jsonStr);
       $result = $this->mapperJsonResult($json);
       if ($result->resCode == self::successCode) {
           return $result->data;
       }
       return array();
   }

    /**
     * 3.38.3.	班级考试单科成绩分布
     * @param $examID
     * @param $subjectID
     * @param $scoreJson
     * @return array
     */
    public function classScoreSubPeo($examID,$subjectID,$scoreJson){
        $soapResult = $this->_soapClient->classScoreSubPeo(
            array(
                "examId"=>$examID,
                "subjectId"=>$subjectID,
                "scoreJson"=>$scoreJson
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.38.4.	单个学生成绩
     * @param $stuID
     * @param $subjectID
     * @return array
     */
    public function stuScoreStastic($stuID,$subjectID=""){
        $soapResult = $this->_soapClient->stuScoreStastic(
            array(
                "stuId"=>$stuID,
                "subjectId"=>$subjectID,
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.38.5.	单个学生的测验
     * @param $stuID
     * @return array
     */
    public function stuTestScore($stuID){
        $soapResult = $this->_soapClient->stuTestScore(
            array(
                "stuId"=>$stuID,
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.38.6.	老师的测验、考试、作业
     * @param $teacherID
     * @return array
     */
    public function allOfTeacher($teacherID){
        $soapResult = $this->_soapClient->allOfTeacher(
            array(
               "teacherId"=>$teacherID
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.38.7.	测验成绩分布
     * @param $testID
     * @param $scoreJson
     * @return array
     */
    public function testScorePeo($testID,$scoreJson){
        $soapResult = $this->_soapClient->testScorePeo(
            array(
               "testId"=>$testID,
                "scoreJson"=>$scoreJson
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.38.8.	单个学生作业完成统计
     * @param $stuID
     * @return array
     */
    public function stuHomeworkStasticCmp($stuID){
        $soapResult = $this->_soapClient->stuHomeworkStasticCmp(
            array(
               "stuId"=>$stuID
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);

        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return null;
    }

    /**
     * 1.1.1.	单个学生作业正确统计
     * @param $stuID
     * @param $homeworkID
     * @return array
     */
    public function stuHomeworkStasticRit($stuID,$homeworkID=""){
        $soapResult = $this->_soapClient->stuHomeworkStasticRit(
            array(
                "stuId"=>$stuID,
                "homeworkId"=>$homeworkID
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.38.10.	老师的作业统计
     * @param $teaID
     * @return array
     */
    public function teaHomeworkStasticCmp($teaID){
        $soapResult = $this->_soapClient->teaHomeworkStasticCmp(
            array(
               "teaId"=>$teaID
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }
    /**
     * 1.1.2.	  本班各科目作业总体完成度比例（3）
     * @param $classID
     * @param $timeFrom
     * @param $timeTo
     * @return array
     * {"data":{
    “list”：[
    “subjectID”:”科目id”
    “subjectName”:”科目名称”  X
    “allCnt”:科目作业*成员总数
    “completeCnt”: 科目完成个数
    “rate”: 完成比例  Y
    ]
    },
    "resCode":"000000",
    "resMsg":"发送成功"
    }

     */
    public function hkSubComRate($classID,$timeFrom,$timeTo){
        $soapResult = $this->_soapClient->hkSubComRate(
            array(
               "classID"=>$classID,
                "timeFrom"=>$timeFrom,
                "timeTo"=>$timeTo
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 1.1.3.	  单科目考试教师利用程度（4 待修改）
     * @param $teacherID
     * @param $subjectID
     * @param $timeFrom
     * @param $timeTo
     * @return array
     * {"data":{
    “list”：[
    “examID”：考试id
    “examName”:”考试名称”
    “allCnt”:科目作业*成员总数
    “completeCnt”: 科目完成个数
    “rate”: 完成比例  Y
    ]
    },
    "resCode":"000000",
    "resMsg":"发送成功"
    }

     */
    public function oneSubTeaUseRate($teacherID,$subjectID,$timeFrom,$timeTo){
        $soapResult = $this->_soapClient->oneSubTeaUseRate(
            array(
                "teacherID"=>$teacherID,
                "subjectID"=>$subjectID,
                "timeFrom"=>$timeFrom,
                "timeTo"=>$timeTo
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     *  1.1.4.	 学生考试利用程度（5）
     * @param $classID
     * @param $timeFrom
     * @param $timeTo
     * @return array
     * 	成功	返回的JSON：
    {"data":{

    “allCnt”:所有考试*成员总数
    “completeCnt”: 完成试卷录入个数
    “rate”: 完成比例
    },
    "resCode":"000000",
    "resMsg":"发送成功"
    }


     */
    public function examStuUseRate($classID,$timeFrom,$timeTo){
        $soapResult = $this->_soapClient->examStuUseRate(
            array(
               "classID"=>$classID,
                "timeFrom"=>$timeFrom,
                "timeTo"=>$timeTo
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 1.1.5.	 历次考试单科成绩变动表（6）
     * @param $classID
     * @param $subjectID
     * @param $timeFrom
     * @param $timeTo
     * @return array
     * {"data":{

    “list”：[
    “examID”：考试id
    “examName”:”考试名称”
    “maxScore”:”最高分”
    “minSocre”：“最低分”
    “avgScore”：“平均分”

    ]
    },
    "resCode":"000000",
    "resMsg":"发送成功"
    }

     */
    public function everyExamSubScoreCha($classID,$subjectID,$timeFrom,$timeTo){
        $soapResult = $this->_soapClient->everyExamSubScoreCha(
            array(
                "classID"=>$classID,
                "subjectID"=>$subjectID,
                "timeFrom"=>$timeFrom,
                "timeTo"=>$timeTo
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 1.1.6.	历次考试、总成绩变化曲线（7 待定）
     * @param $classID
     * @param $subjectID
     * @param $type
     * @param $timeFrom
     * @param $timeTo
     * @return array
     * {"data":{

    “list”：[
    “examID”：考试id
    “examName”:”考试名称”
    “maxScore”:”最高分”
    “minSocre”：“最低分”
    “aveScore”：“平均分”

    ]
    },
    "resCode":"000000",
    "resMsg":"发送成功"
    }

     */
    public function everyExamTotalScoreCha($classID,$subjectID,$type,$timeFrom,$timeTo){
        $soapResult = $this->_soapClient->everyExamTotalScoreCha(
            array(
                "classID"=>$classID,
                "subjectID"=>$subjectID,
                "type"=>$type,
                "timeFrom"=>$timeFrom,
                "timeTo"=>$timeTo
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 1.1.7.	 本班答疑（8）
     * @param $classID
     * @param $timeFrom
     * @param $timeTo
     * @return array
     * {"data":{

    “allCnt”:问题总数
    “completeCnt”: 答案总数
    “rate”: 完成比例
    },
    "resCode":"000000",
    "resMsg":"发送成功"
    }

     */
    public function classAnsQue($classID,$timeFrom,$timeTo){
        $soapResult = $this->_soapClient->classAnsQue(
            array(
                "classID"=>$classID,

                "timeFrom"=>$timeFrom,
                "timeTo"=>$timeTo
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 1.1.8.	每次考试（综合类）：平均成绩中各科目的平均成绩
     * @param $examID
     * @return array
     * {
    "data": {
    "listSize": 7,
    "list": [
    {
    "examSubID": 101286,
    "subjectID": "10010",//科目id
    "subjectName": "语文",//科目
    "avgScore": 91.3//平均分
    },
    {
    "examSubID": 101287,
    "subjectID": "10011",
    "subjectName": "数学",
    "avgScore": 84
    },
    {
    "examSubID": 101288,
    "subjectID": "10012",
    "subjectName": "英语",
    "avgScore": 95
    },
    {
    "examSubID": 101289,
    "subjectID": "10013",
    "subjectName": "生物",
    "avgScore": 90.3
    },
    {
    "examSubID": 101290,
    "subjectID": "10014",
    "subjectName": "物理",
    "avgScore": 79
    },
    {
    "examSubID": 101291,
    "subjectID": "10015",
    "subjectName": "化学",
    "avgScore": 93.3
    },
    {
    "examSubID": 101292,
    "subjectID": "10016",
    "subjectName": "地理",
    "avgScore": 69.6
    }
    ]
    },
    "resCode": "000000",
    "resMsg": "成功"
    }

     */
    public function examSubAvgScore($examID){
        $soapResult = $this->_soapClient->examSubAvgScore(
            array(
               "examId"=>$examID
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 1.1.9.	单个学生各科目作业完成程度
     * @param $stuID
     * @return array
     * {
    "data": {
    "listSize": 3,
    "list": [
    {
    "subjectId": "10010",//科目id
    "subjectName": "语文",//科目
    "allCnt": 21,//作业总数
    "completeCnt": 1//完成个数
    },
    {
    "subjectId": "10011",
    "subjectName": "数学",
    "allCnt": 29,
    "completeCnt": 4
    },
    {
    "subjectId": "10013",
    "subjectName": "生物",
    "allCnt": 2,
    "completeCnt": 0
    }
    ]
    },
    "resCode": "000000",
    "resMsg": "成功"

     */
    public function stuSubHomeworkCmp($stuID){
        $soapResult = $this->_soapClient->stuSubHomeworkCmp(
            array(
                "stuId"=>$stuID
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.39.19.	本班总体作业完成度比例（1）
     * @param $classID
     * @param $timeFrom
     * @param $timeTo
     * @return array
     * 返回的JSON：
    {"data":{
    “allCnt”: 作业*成员总数
    “completeCnt”: 完成个数
    “rate”: 完成比例
    },
    "resCode":"000000",
    "resMsg":"发送成功"
    }

     */
    public function homeworkComRate($classID,$timeFrom,$timeTo){
        $soapResult = $this->_soapClient->homeworkComRate(
            array(
                "classID"=>$classID,
                "timeFrom"=>$timeFrom,
                "timeTo"=>$timeTo
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.39.35.	考试三率变化
     * 失败	返回的JSONB：参考响应代码
    成功	{
    "data": {
    "listSize": 3,
    "list": [
    {
    "examID":”考试id”
    “examName”:”考试名称”
    “bNum”：“有成绩的人数”
    “highRate”:”高分率”
    “passRate”:”及格率”
    “lowRate”：“低分率”

    },
    {
    "examID":”考试id”
    “examName”:”考试名称”
    “bNum”：“有成绩的人数”
    “highRate”:”高分率”
    “passRate”:”及格率”
    “lowRate”：“低分率”

    },
    {
    "examID":”考试id”
    “examName”:”考试名称”
    “bNum”：“有成绩的人数”
    “highRate”:”高分率”
    “passRate”:”及格率”
    “lowRate”：“低分率”

    }
    ]
    },
    "resCode": "000000",
    "resMsg": "成功"
    }

     * @param $classID
     * @param $timeFrom
     * @param $timeTo
     * @return array
     */
    public function examThreeScoreStat($classID,$subjectID,$startTime,$endTime){
        $soapResult = $this->_soapClient->examThreeScoreStat(
            array(
                "classID"=>$classID,
                "subjectID"=>$subjectID,
                "startTime"=>$startTime,
                "endTime"=>$endTime
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }



}