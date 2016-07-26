<?php
namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-4-3
 * Time: 下午2:01
 */

class pos_StudentScoreCountService extends BaseService{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/studentScoreConut?wsdl");
    }
    /**
     * 3.39.11.	单个学生成绩排名变动的曲线
     * @param $studentID
     * @param $startTime
     * @param $endTime
     * @return array
     * 失败	返回的JSONB：参考响应代码
    成功	{"data":{"listSize":12,"list"
    :[{"examID":"101110",                  考试id
    "examName":"1月27的第一次考试",       考试名称
    "totalScore":"199",                    总分数
    "creatTime":"1422345520817",           考试时间
    "studentName":"王春雷",                学生姓名
    "ranking":"5"},                        考试排名
    {"examID":"101122",
    "examName":"1月31的第一次考试11",
    "totalScore":"288",
    "creatTime":"1422700734043",
    "studentName":"王春雷",
    "ranking":"2"},
    {"examID":"101123",
    "examName":"2月2的第一次考试",
    "totalScore":"176",
    "creatTime":"1422843570621",
    "studentName":"王春雷",
    "ranking":"2"},
    {"examID":"101132",
    "examName":"2月3日的第一次期中考试",
    "totalScore":"310",
    "creatTime":"1422958664272",
    "studentName":"王春雷",
    "ranking":"3"}]},"resCode":"000000","resMsg":"成功"}

     */
    public function StudentScoreRanking($studentID,$startTime,$endTime){
        $soapResult = $this->_soapClient->StudentScoreRanking(
            array(
                "studentID"=>$studentID,
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



    /**
     * 3.39.12.	单科目历次考试的成绩变动
     * @param $studentID
     * @param $startTime
     * @param $endTime
     * @return array
     * {"data":{"listSize":12,"list":[
    {"examID":"101110",                    考试id
    "examName":"1月27的第一次考试",       考试名称
    "totalScore":"199",                    考试总分
    "creatTime":"1422345520817",           考试时间
    "studentName":"王春雷"},               考生姓名
    {"examID":"101122",
    "examName":"1月31的第一次考试11",
    "totalScore":"288",
    "creatTime":"1422700734043",
    "studentName":"王春雷"},
    {"examID":"101123",
    "examName":"2月2的第一次考试",
    "totalScore":"176",
    "creatTime":"1422843570621",
    "studentName":"王春雷"}
    ]},"resCode":"000000","resMsg":"成功"}

     */
    public function SubjectScoreChange($studentID,$subjectID,$startTime,$endTime){
        $soapResult = $this->_soapClient->SubjectScoreChange(
            array(
                "studentID"=>$studentID,
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
    /**
     * 3.39.17.	单科目作业完成程度
     * @param $studentID
     * @param $subjectID
     * @return array
     * {"data":{"listSize":1,"list":[
    {"subName":"数学",                 科目名称
    "checkrateId":"10011",             "checkrate1":"100.0000",          批改率
    "uncheckrate1":"350.0000",        未批改率
    "finishedrate":"450.0000"}        完成率
    ]},"resCode":"000000","resMsg":"成功"

     */
    public function SubjectFinishedPer($studentID,$subjectID){
        $soapResult = $this->_soapClient->SubjectFinishedPer(
            array(
                "studentID"=>$studentID,
                "subjectId"=>$subjectID
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);

        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }
    /**
     * 3.39.18.	所有已完成的作业中各科目占比
     * @param $studentID
     * @return array
     * {"data":{"listSize":3,"list":[
    {"subjectId":"10010",  科目id
    "subName":"语文",      科目名称
    "number":9,            完成数量
    "percent":"150.0"},    占比
    {"subjectId":"10011",
    "subName":"数学",
    "number":18,
    "percent":"300.0"},
    {"subjectId":"10013",
    "subName":"生物",
    "number":3,
    "percent":"50.0"}]},
    "resCode":"000000","resMsg":"成功"}

     */
    public function SubjectdPer($studentID){
        $soapResult = $this->_soapClient->SubjectdPer(
            array(
                "studentID"=>$studentID,
            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }
    /**
     * 3.39.16.	所有科目作业完成百分比
     * @param $studentID
     * @return array
     * {"data":{"listSize":2,"list":[
    {"su":"10010",          科目id
    "subName":"语文",       科目名称
    "rate":"14.2857"},      完成比例
    {"su":"10011",
    "subName":"数学",
    "rate":"50.0000"}
    ]},"resCode":"000000","resMsg":"成功"}

     */
    public function allSubjectFinishedPer($studentID){
        $soapResult = $this->_soapClient->allSubjectFinishedPer(
            array(
                "studentID"=>$studentID,

            )
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 3.39.13.	所有考试成绩变动曲线(每次考试的总成绩)
     * @param $studentID
     * @param $startTime
     * @param $endTime
     * @return array
     * {"data":{"listSize":12,"list":[
    {"examID":"101110",                    考试id
    "examName":"1月27的第一次考试",       考试名称
    "totalScore":"199",                    考试总分
    "creatTime":"1422345520817",           考试时间
    "studentName":"王春雷"},               考生姓名
    {"examID":"101122",
    "examName":"1月31的第一次考试11",
    "totalScore":"288",
    "creatTime":"1422700734043",
    "studentName":"王春雷"},
    {"examID":"101123",
    "examName":"2月2的第一次考试",
    "totalScore":"176",
    "creatTime":"1422843570621",
    "studentName":"王春雷"}
    ]},"resCode":"000000","resMsg":"成功"}

     */
    public function StudentTotalScore($studentID,$startTime,$endTime){
        $soapResult = $this->_soapClient->StudentTotalScore(
            array(
                "studentID"=>$studentID,
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