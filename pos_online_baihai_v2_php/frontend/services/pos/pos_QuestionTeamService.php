<?php
namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 14-11-17
 * Time: 下午4:04
 */
class pos_QuestionTeamService extends BaseService{
    function __construct(){
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService']."schoolService/questionTeam?wsdl");
    }

    /**
     * 查询题目组列表
     * @param $creatorID                题目组创建人ID （可不填）
     * @param $questionTeamName         题目组名称(可不填)
     * @param $gradeID                  年级ID
     * @param $subjectID                科目ID
     * @param $currPage                 当前页码
     * @param $pageSize                 每页条数
     * @return ServiceJsonResult
     * {
    "data": {
    "countSize":"1",记录条数
    "pageSize":"2",每页条数
    "currPage":"1"当前页数
    "totalPages":"1"总页数
    “questionTeamList”：[题目组列表
    {
    “questionTeamID” ：“题目组ID‘
    “questionTeamName”: “题目组名称”
    “gradeID”：“年级ID”
    “subjectID”:” 科目ID”
    “connetID”: “知识点ID字符串多个用逗号隔开”
    “deliverList”:[//推送记录
    {
    “notesID” ：”推送记录ID“
    “notesTime” :”推送时间”
    }]
    }
    ]
    "resCode": "000000",
    "resMsg": "查询成功"
    }
     */
    public function searchQuestionTeam($creatorID, $questionTeamName,$gradeID, $subjectID,$currPage, $pageSize)
    {
        $soapResult = $this->_soapClient->searchQuestionTeam(array("creatorID" =>$creatorID, 'questionTeamName' =>$questionTeamName, 'gradeID' =>$gradeID, 'subjectID' =>$subjectID, 'currPage' =>$currPage, 'pageSize' =>$pageSize));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 创建题目组
     * @param $questionTeamName          题目组名称
     * @param $provience                 适用地区 省
     * @param $city                      市
     * @param $country                   区县
     * @param $gradeID                   年级ID
     * @param $subjectID                 科目ID
     * @param $connetID                  知识点ID字符串多个用逗号隔开
     * @param $labelName                 自定义标签名称，多个用逗号隔开
     * @param $questionTeamMark          题目组描述
     * @param $creatorID                 创建人ID
     * @return ServiceJsonResult
     * {
    "data": {
    "resCode": "000000",
    "resMsg": "创建成功"
    }
     */
    public function createQuestionTeam($questionTeamName,$provience,$city, $country,$gradeID, $subjectID,$connetID,$labelName, $questionTeamMark,$creatorID)
    {
        $soapResult = $this->_soapClient->createQuestionTeam(array("questionTeamName" =>$questionTeamName, 'provience' =>$provience, 'city' =>$city, 'country' =>$country, 'gradeID' =>$gradeID, 'subjectID' =>$subjectID, 'connetID' =>$connetID, 'labelName' =>$labelName, 'questionTeamMark' =>$questionTeamMark, 'creatorID' =>$creatorID));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 查看题目组详情
     * @param $questionTeamID         题目组ID
     * param $currPage                当前页码
     * param $pageSize                每页条数
     * @return ServiceJsonResult
     * {
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
    " questionTeamMark"："题目组描述"
    "countSize": "1",
    "pageSize": "10",
    "currPage": "1",
    "totalPages": "1",
    " questionIDListSize": 1
    "questionIDList" ：//对应题目ID 列表
    [{
    "questionID" : 题目ID
    “questionName”题目名称
    “content”：“题目内容”
    }]
    "resCode": "000000",
    "resMsg": "查询成功"
    }
     */
    public function searchQuestionTeamById($questionTeamID,$currPage,$pageSize)
    {
        $soapResult = $this->_soapClient->searchQuestionTeamById(array('questionTeamID' =>$questionTeamID,'currPage' =>$currPage,'pageSize' =>$pageSize));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 推送题目组
     * @param $questionTeamID           题目组ID
     * @param $isMessage                是否短信通知家长默认0   0 不通知 1 通知
     * @param $message                  短信内容
     * @param $studentID                学生ID 字符串  多个学生用逗号隔开
     * @return ServiceJsonResult
     * {
    "data": {
    "resCode": "000000",
    "resMsg": "推送成功"
    }
     */
    public function deliverQuestionTeam($questionTeamID, $isMessage,$message, $studentID)
    {
        $soapResult = $this->_soapClient->deliverQuestionTeam(array("questionTeamID" =>$questionTeamID, 'isMessage' =>$isMessage, 'message' =>$message, 'studentID' =>$studentID));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 修改题目组
     * @param $questionTeamID            题目组id
     * @param $questionTeamName          题目组名称
     * @param $provience                 适用地区 省
     * @param $city                      市
     * @param $country                   区县
     * @param $gradeID                   年级ID
     * @param $subjectID                 科目ID
     * @param $connetID                  知识点ID字符串多个用逗号隔开
     * @param $labelName                 自定义标签名称，多个用逗号隔开
     * @param $questionTeamMark          题目组描述
     * @param $creatorID                 创建人ID
     * @return ServiceJsonResult
     * {
    "data": {
    "resCode": "000000",
    "resMsg": "创建成功"
    }
     */
    public function modifyQuestionTeam($questionTeamID,$questionTeamName,$provience,$city, $country,$gradeID, $subjectID,$connetID,$labelName, $questionTeamMark,$creatorID)
    {
        $soapResult = $this->_soapClient->modifyQuestionTeam(array("questionTeamID"=>$questionTeamID,"questionTeamName" =>$questionTeamName, 'provience' =>$provience, 'city' =>$city, 'country' =>$country, 'gradeID' =>$gradeID, 'subjectID' =>$subjectID, 'connetID' =>$connetID, 'labelName' =>$labelName, 'questionTeamMark' =>$questionTeamMark, 'creatorID' =>$creatorID));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 向题目组增加题目
     * @param $questionTeamID            题目组id
     * @param $questionID                关联的题目ID字符串  多个题目ID 用逗号隔开
     * @return ServiceJsonResult
     * 返回的JSON示例：
    {
    "data": {
    “questionTeamID”：“题目组ID”
    "resCode": "000000",
    "resMsg": "增加成功"
    }
     */
    public function addQuestionToTeam($questionTeamID,$questionID)
    {
        $soapResult = $this->_soapClient->addQuestionToTeam(array("questionTeamID"=>$questionTeamID,"questionID" =>$questionID));
         return  $this->soapResultToJsonResult($soapResult);
    }



}
