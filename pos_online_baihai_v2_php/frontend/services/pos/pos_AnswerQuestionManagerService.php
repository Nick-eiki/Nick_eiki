<?php

namespace frontend\services\pos;
use ArrayObject;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 14-10-31
 * Time: 上午11:05
 */
class pos_AnswerQuestionManagerService extends BaseService{
    function __construct(){
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService']."schoolService/answerQuestionManager?wsdl");
    }

    /**
     * @param $creatorID
     * @param $aqName
     * @param $aqDetail
     * @param $subjectID
     * @param $classID
     * @param $currPage
     * @param $pageSize
     * @param $schoolID
     * @return ServiceJsonResult
     * 查询问题列表
     */
    public function searchQuestionInfoList($creatorID = '',$aqName = '',$aqDetail = '',$subjectID = '',$classID = '',$currPage,$pageSize,$schoolID=''){
        $soapResult = $this->_soapClient->searchQuestionInfoList(
	        array(
		        "creatorID"=>$creatorID,
		        "aqName"=>$aqName,
		        "aqDetail"=>$aqDetail,
		        "subjectID"=>$subjectID,
		        "classID"=>$classID,
		        "currPage"=>$currPage,
		        "pageSize"=>$pageSize,
		        'schoolID'=>$schoolID
	        ));
         return  $this->soapResultToJsonResult($soapResult);
    }

	/**
	 * 添加问题
	 * wgl 14-10-31 14:53
	 * @param creatorID
	 * @param $aqName
	 * @param $aqDetail
     * @param $subjectID
     * @param $classID
     * @param $sendToWorld
     * @param $imgUri
	 * @return ServiceJsonResult
	 */

	public function createQuestionInfo($creatorID,$aqName,$aqDetail,$subjectID,$classID,$sendToWorld,$imgUri)
	{
		$soapResult = $this->_soapClient->createQuestionInfo(array("creatorID"=>$creatorID, "aqName"=>$aqName, "aqDetail"=>$aqDetail, "subjectID"=>$subjectID, "classID"=>$classID,'sendToWorld',$sendToWorld,'imgUri'=>$imgUri));
		$jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
		$json = json_decode($jsonStr);
		return $this->mapperJsonResult($json);
	}

    /**
     * @param $apID
     * @param $creatorID
     * @param $resultDetail
     * @return ServiceJsonResult
     * 回答问题
     */
    public function ResultQuestion($apID,$creatorID,$resultDetail){
        $soapResult = $this->_soapClient->ResultQuestion(array("apID"=>$apID,"creatorID"=>$creatorID,"resultDetail"=>$resultDetail));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * @param $aqID
     * @param $userId
     * @return ServiceJsonResult
     * 同问问题
     */
    public function SameQuestion($aqID,$userId){
        $soapResult = $this->_soapClient->SameQuestion(array("aqID"=>$aqID,"userId"=>$userId));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * @param $aqID
     * @param $qMoreDetail
     * @param $creatorID
     * @return ServiceJsonResult
     * 补充问题
     */
    public function AddMoreQuestionInfo($aqID,$qMoreDetail,$creatorID){
        $soapResult = $this->_soapClient->AddMoreQuestionInfo(array("aqID"=>$aqID,"qMoreDetail"=>$qMoreDetail,"creatorID"=>$creatorID));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * @param $resultID
     * @return ServiceJsonResult
     * 采用答案
     */
    public function UseTheAnswer($resultID){
        $soapResult = $this->_soapClient->UseTheAnswer(array("resultID"=>$resultID));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * @param $apID
     * @return ServiceJsonResult
     * 查询问题详情
     */
    public function SearchQuestionByID($apID){
        $soapResult = $this->_soapClient->searchQuestionByID(array("apID"=>$apID));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 修改问题
     * @param $aqID
     * @param $aqName
     * @param $aqDetail
     * @param $imgUri
     * @return ServiceJsonResult
     *
     */
    public function ModifyQuestionInfo($aqID,$aqName,$aqDetail,$imgUri){
        $soapResult = $this->_soapClient->modifyQuestionInfo(array("aqID"=>$aqID,"aqName"=>$aqName,"aqDetail"=>$aqDetail,'imgUri'=>$imgUri));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     *  3.27.10.用户主页查询相关问题
     * 接口地址	http://主机地址:端口号/ schoolService / answerQuestionManager?wsdll
     * 方法名	searchCurUserQuesList
     * @param $userID       用户ID（不为空）
     * @param $currPage     当前页码
     * @param $pageSize     每页条数
     * {
    "data": {
    "pageSize": "10",
    "countSize": "2",
    "list": [
    {
    "aqID": 201193,//问题ID
    "aqName": "Ffffff",//问题名称
    "aqDetail": "Vvcccccffgf",//问题详情
    "createTime": "2015-04-01 11:03:06",//创建时间
    "creatorID": "101356",//创建人ID
    "creatorName": "Q老师",//创建人名称
    "sameQueNumber": 0,//同问数
    "resutltNumber": 2,//回答数
    "usedNum": 1,//采用答案个数
    "resultDetail": "xxxxx",//采用答案
    "sendToWorld": null,//抛向宇宙
    "myQues": 1//1我提出的问题，0我回答过的问题
    },
    {
    "aqID": 101191,
    "creatorID": "100290",
    "aqName": "不明觉厉？",
    "aqDetail": "<p>形容 -<br/></p>",
    "createTime": "2015-03-02 12:06:31",
    "creatorName": "123老师",
    "sameQueNumber": 0,
    "resutltNumber": 2,
    "usedNum": 1,
    "resultDetail": "Ffsddff",
    "sendToWorld": null,
    "myQues": 0,
    "myAnswers": [//我回答过的答案列表
    {
    "resultID": 201229,
    "creatorID": "101356",
    "rel_aqID": "101191",
    "resultDetail": "Vvnnnbbjjhh",//答案内容
    "createTime": " 2015-03-31 12:40:59",
    "isUse": "0",
    "useTime": null,
    "isDelete": "0"
    }
    ]
    }
    ],
    "currPage": "1",
    "listSize": 2,
    "totalPages": "1"
    },
    "resCode": "000000",
    "resMsg": "查询成功"
    }
     * @return null
     */
    public function searchCurUserQuesList($userID,$currPage,$pageSize){
        $soapResult = $this->_soapClient->searchCurUserQuesList(array("userID" => $userID,'currPage'=>$currPage,'pageSize'=>$pageSize));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return null;
    }

    /**
     * 3.27.12.用户问题个数统计
     * 接口地址	http://主机地址:端口号/ schoolService / answerQuestionManager?wsdll
     * 方法名	stasticUserQues
     * @param $userID   用户ID（不为空）
     * {
    "data": {
    "askQuesCnt": 2,//提问个数
    "useCnt": 0,//采用个数
    "answerCnt": 12//回答个数
    },
    "resCode": "000000",
    "resMsg": "成功"
    }
     * @return null
     */
    public function stasticUserQues($userID){
        $soapResult = $this->_soapClient->stasticUserQues(array("userID" => $userID));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return null;
    }






}

