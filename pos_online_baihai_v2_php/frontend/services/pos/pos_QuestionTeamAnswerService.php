<?php
namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 14-11-19
 * Time: 下午4:04
 */
class pos_QuestionTeamAnswerService extends BaseService{
    function __construct(){
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService']."schoolService/questionTeamAnswer?wsdl");
    }

    /**
     * 3.35.学生查询题目组列表
     * @param $studentId                    学生id
     * @param $createTime                   创建时间
     * @param $questionTeamName             题目组名称(可不填)
     * @param $gradeID                      年级ID
     * @param $subjectID                    科目ID
     * @param $currPage                     当前页码
     * @param $pageSize                     每页条数
     * @return ServiceJsonResult
     * {
    "data": {
    "countSize":"1",记录条数
    "pageSize":"2",每页条数
    "currPage":"1"当前页数
    "totalPages":"1"总页数
    "list"：[题目组列表
    {
    "notesID" ："推送记录ID",
    "notesTime" :"推送时间"
    "isAnswered":"是否答题 0否 1是"
    "answerTime":"答题时间"
    "questionTeamID" ："题目组ID‘
    "questionTeamName": "题目组名称"
    "Provience":"省"
    "City":"市"
    "country":"区"
    "gradeID"："年级ID"
    "gradename":""
    "subjectID":" 科目ID"
    "Subjectname":"科目名称"
    "createTime":"创建时间"
    "connetID": "知识点ID字符串多个用逗号隔开"
    "labelName":"自定义标签"
    "questionTeamMark":"题目组描述"
    "creatorID":"题目组创建人"
    }
    ]
    "resCode": "000000",
    "resMsg": "查询成功"
    }
     */
    public function searchQuestionTeam($studentId,$createTime,$questionTeamName,$gradeID, $subjectID,$currPage, $pageSize)
    {
        $soapResult = $this->_soapClient->searchQuestionTeam(array("studentId"=>$studentId,"createTime" =>$createTime, 'questionTeamName' =>$questionTeamName, 'gradeID' =>$gradeID, 'subjectID' =>$subjectID, 'currPage' =>$currPage, 'pageSize' =>$pageSize));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.36.学生查看题目组详情（答题或答题完毕）
     * @param $questionTeamID                    题目组ID
     * @param $notesID                           推送记录ID
     * @param $studentId                         学生id
     * @param $currPage                          当前页码
     * @param $pageSize                          每页条数
     * @return ServiceJsonResult
     *{
    "data": {
    "questionTeamID" ："题目组ID‘
    "questionTeamName": "题目组名称"
    “provience :”省”
    “city”市
    “country”县
    "gradeID"："年级ID"、
    “gradeName”:年级名称
    "subjectID":" 科目ID"
    “subjectName”:”科目名称”
    "createTime" ："组织时间"
    "connetID": "知识点ID字符串多个用逗号隔开"
    "labelName" :" 自定义标签名称，多个用逗号隔开"
    "questionTeamMark"："题目组描述"
    "rightCnt"："答对题数"
    "wrongCnt"："答错题数"
    "countSize": "1",
    "pageSize": "10",
    "currPage": "1",
    "totalPages": "1",
    "questionListSize": 1
    "questionList" ：//对应题目ID 列表
    [{
    "questionID" : 题目ID
    "questionName":题目名称
    "stuAnswer":""学生的答案
    "isright":""是否正确
    "tqName":""题目名称
    "content"：“题目内容”
    "answerOption":""备选项[{"id":"1","content":"备选项1","right":"1"}] 0错误 1正确
    "answerContent":""题目答案 单选题选项id 多选题选项id逗号隔开
    "analytical":"" 题目分析
    }]
    "resCode": "000000",
    "resMsg": "查询成功"
    }
     */
    public function searchQuestionTeamById($questionTeamID,$notesID,$studentId,$currPage,$pageSize)
    {
        $soapResult = $this->_soapClient->searchQuestionTeamById(array("questionTeamID"=>$questionTeamID,"notesID" =>$notesID, 'studentId' =>$studentId,"currPage" =>$currPage, 'pageSize' =>$pageSize));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.37.学生答题
     * @param $questionTeamID                    题目组ID
     * @param $notesID                           推送记录ID
     * @param $studentID                         学生id
     * @param $answerJson                        答案json[{"questionId":"","answer":""//多选题答案，用逗号隔开}]
     * @return ServiceJsonResult
     *{"data":{
    },
    "resCode":"000001",
    "resMsg":"成功"
    }
     */
    public function answerQuestionTeam($questionTeamID,$notesID,$studentID,$answerJson)
    {
        $soapResult = $this->_soapClient->answerQuestionTeam(array("questionTeamID"=>$questionTeamID,"notesID" =>$notesID, 'studentID' =>$studentID, 'answerJson' =>$answerJson));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.36.4.统计题目组答案头部
     * @param $questionTeamID                    题目组ID
     * @return ServiceJsonResult
     *{"data":{
    },
    "resCode":"000001",
    "resMsg":"成功"
    }
     */
    public function staQuesTm($questionTeamID)
    {
        $soapResult = $this->_soapClient->staQuesTm(array("questionTeamID"=>$questionTeamID));
        return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.36.5.统计题目组答案选项
     * staQuesTmAnsr
     * @param $notesID                    推送记录ID
     * @param $currPage
     * @param $pageSize
     * @return ServiceJsonResult
     *{
    "data": {
    "pageSize": "10",
    "countSize": "3",
    "currPage": "1",
    "questionList": [
    {
    "questionTeamID": "10131",题目组id
    "notesID": "10152",推送记录id
    "questionID": "20024",题目id
    "answerContent": null,正确答案
    "answer": [
    {
    "answerOption": "A",选项
    "cnt": 6选项人数
    },
    {
    "answerOption": "B",
    "cnt": 2
    }
    ]
    },
    {
    "questionTeamID": "10131",
    "notesID": "10152",
    "questionID": "20025",
    "answerContent": null,
    "answer": [
    {
    "answerOption": "A",
    "cnt": 1
    },
    {
    "answerOption": "B",
    "cnt": 6
    }
    ]
    },
    {
    "questionTeamID": "10131",
    "notesID": "10152",
    "questionID": "99",
    "answerContent": null,
    "answer": [
    {
    "answerOption": "A",
    "cnt": 7
    }
    ]
    }
    ],
    "totalPages": "1",
    "questionListSize": 3
    },
    "resCode": "000000",
    "resMsg": "查询成功"
    }
     */
    public function staQuesTmAnsr($notesID,$currPage,$pageSize)
    {
        $soapResult = $this->_soapClient->staQuesTmAnsr(array("notesID" =>$notesID,"currPage" =>$currPage,"pageSize" =>$pageSize));
        return  $this->soapResultToJsonResult($soapResult);

    }

}
