<?php

namespace frontend\services\pos;
/**
 * Created by PhpStorm.
 * User: liquan
 * Date: 2014/11/17
 * Time: 10:54
 */
use ArrayObject;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

class pos_WrongTopicService extends BaseService
{


    /**
     * @return ServiceJsonResult
     */
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/wrongQuestion?wsdl");
    }
    /*
     * 错题所在科目列表
     * userId
     */
    public function stasticQuesBySub($userId='',$currPage='',$pageSize=''){
        $soapResult = $this->_soapClient->stasticQuesBySub(array("userId" => $userId, 'currPage' => $currPage, 'pageSize' => $pageSize));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }
    /*
     * 查询某科目下错题集
     * id	题目id
     * operater	录入人id
     *  gradeid	适用年级  关联年级信息表的年级ID
     *  subjectid	科目 关联科目信息表的科目ID
     * versionid	版本 管理版本信息表的版本ID
     * typeId	题型
                1	单选题
                2	填空题
                3	计算题
                4	解答题
                5	判断题
     * currPage	当前显示页码，可以为空,默认值1
     * pageSize	每页显示的条数，可以为空，默认值10
     */
   public function questionSearch($id='', $operater='', $currPage='', $pageSize='', $gradeid='', $subjectid='', $orderType='', $versionid='', $typeId=''){
       $soapResult = $this->_soapClient->questionSearch(
		   array(
			   "id" => $id,
			   'operater' => $operater,
			   'gradeid' => $gradeid,
			   'subjectid' => $subjectid,
			   'versionid' => $versionid,
			   'orderType' => $orderType,
			   'typeId' => $typeId,
			   'currPage' => $currPage,
			   'pageSize' => $pageSize));
       $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
       $json = json_decode($jsonStr);
       $result = $this->mapperJsonResult($json);
       if ($result->resCode == self::successCode) {
           return $result->data;
       }
       return array();
   }
    //添加题目

    /*
     * 创建临时题目
     * operater	录入人
     */
    public function createTempQuestion($operater=''){
        $soapResult = $this->_soapClient->createTempQuestion(
            array("operater" => $operater));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }
    /*
     * 保存题目头部
     * id	题目id
     * provience	省
     * city	市
     * country	区县
     * gradeid	适用年级
     * subjectid	科目
     * versionid	版本
     * kid	知识点
     * typeId	题型
     * provenance	考试分类
     * year	年份
     * complexity	难易程度
     * capacity	掌握程度
     * tags	自定义标签。标签之间使用逗号分隔
     * name	题目名称
     * questionPrice	题目价格
     * quesLevel	题目等级
     * quesFrom	题目来源
     * content	题目内容
     * textContent	题目内容文本
     */
    public function saveQuestionHead($id='',$provience='',$city='',$country='',$gradeid='',$subjectid='',$versionid='',$kid='',
                                     $typeId='',$provenance='',$year='',$complexity='',$capacity='',
                                     $tags='',$name='',$questionPrice='',$quesLevel='',$quesFrom='',$content='',$textContent=''){
        $soapResult = $this->_soapClient->saveQuestionHead(
            array(
                'id'=>$id, 'provience' => $provience, 'city' => $city,'country' => $country,'gradeid' => $gradeid, 'subjectid' => $subjectid,
                'versionid' => $versionid,'kid' => $kid,'typeId' => $typeId, 'provenance' => $provenance,'year' => $year,'complexity' => $complexity,
                'capacity' => $capacity, 'tags' => $tags, 'name' => $name, 'questionPrice' => $questionPrice,'quesLevel'=>$quesLevel,
                'quesFrom'=>$quesFrom,'content'=>$content,'textContent'=>$textContent,
            ));
         return  $this->soapResultToJsonResult($soapResult);

    }
    /*
     * 保存题目内容
     * id	题目id
     * answerOptionJson	选择题备选答案
     * answerContent	答案
     * analytical	解析
     * childQuesJson	小题： 没有小题时，传空值
     * saveType	保存状态 0临时 1正式
     */
    public function saveQuestionContent($id='', $answerOptionJson='', $answerContent='', $analytical='', $childQuesJson='', $saveType=''){
        $soapResult = $this->_soapClient->saveQuestionContent(
            array(
                'id' => $id,
                'answerOptionJson' => $answerOptionJson,
                'answerContent' => $answerContent,
                'analytical' => $analytical,
                'childQuesJson' => $childQuesJson,
                'saveType' => $saveType,
            ));
         return  $this->soapResultToJsonResult($soapResult);
    }
    /*
     * 查询临时题目
     * id	题目id
     */
    public function queryTempQuesById($id){
        $soapResult = $this->_soapClient->queryTempQuesById(
            array(
                'id' => $id,
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }
    //移除错题
    /*
     *  id 题目ID
     *  operater 录入人
     */
    public function removeQuestion($id='',$operater=''){
        $soapResult = $this->_soapClient->removeQuestion(array('id'=>$id,'operater'=>$operater));
         return  $this->soapResultToJsonResult($soapResult);
    }
    /*
     * 查询小题
     * parentId	大题id
     * operater	录入人
     */
    public function childQuestionSearch($parentId='',$operater=''){
        $soapResult = $this->_soapClient->childQuestionSearch(array("parentId" => $parentId, 'operater' => $operater));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }
    /*
     * 答题
     * answerJson //大题、小题、小题答案
     * masId	大题的错题记录id
     */
    public function answerQuestion($answerJson='',$masId=''){
        $soapResult = $this->_soapClient->answerQuestion(array("answerJson"=>$answerJson,"masId"=>$masId));
         return  $this->soapResultToJsonResult($soapResult);
    }
    /*
     * 批改答案（图片）
     * userId	用户id
     * picId	答案图片ID
     * checkInfoJson 批改详情
     */
    public function checkAnswerPic($userId='',$picId='',$checkInfoJson=''){
        $soapResult = $this->_soapClient->checkAnswerPic(array("userId"=>$userId,"picId"=>$picId,"checkInfoJson"=>$checkInfoJson));
         return  $this->soapResultToJsonResult($soapResult);
    }
    /*
     * 上传分数
     * userId	用户id
     * recId	答题记录id
     * score	分数
     */
    public function checkQuestionAnswerRec($userId='',$recId='',$score=''){
        $soapResult = $this->_soapClient->checkQuestionAnswerRec(array("userId"=>$userId,"recId"=>$recId,"score"=>$score));
         return  $this->soapResultToJsonResult($soapResult);
    }
    /*
     * 查询上一题/下一题
     * operater	录入人id
     * gradeid	适用年级
     * subjectid 科目
     * versionid 版本
     * typeId  题型
     * currId	当前错题记录id
     * upOrDown	上一题1  下一题2
     */
    public function questionSearchUpDown($operater='',$gradeid='',$subjectid='',$versionid='',$typeId='',$currId='',$upOrDown=''){
        $soapResult = $this->_soapClient->questionSearchUpDown(array("operater" => $operater,'gradeid' => $gradeid,'subjectid'=>$subjectid,'versionid'=>$versionid,'typeId'=>$typeId,'currId'=>$currId,'upOrDown'=>$upOrDown));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }
    /*
     * 修改题目难度
     * questionId	题目
     * userID	用户id
     * complexity	题目难度
     */
    public function modifyQuestionSomeInfo($questionId='',$userID,$complexity=''){
        $soapResult = $this->_soapClient->modifyQuestionSomeInfo(array("questionId" => $questionId,'userID' => $userID,'complexity'=>$complexity));

		return $this->soapResultToJsonResult($soapResult);
//        if ($result->resCode == self::successCode) {
//            return $result->data;
//        }
//        return array();
    }
    /*
     * 查询答题记录
     */
    public function queryAnswerRec($answerRecId){
        $soapResult = $this->_soapClient->queryAnswerRec(array("answerRecId" => $answerRecId));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

	/**
	 *3.35.6.录入图片题目（错题本）
	 *
	 * @param string operater		录入人
	 * @param string provience		省
	 * @param string city			市
	 * @param string country		区县
	 * @param string gradeid		适用年级 关联年级信息表的年级ID
	 * @param string subjectid		科目 关联科目信息表的科目ID
	 * @param string versionid		版本 管理版本信息表的版本ID
	 * @param string complexity		难易程度,复杂度 21101	简单 21102	复杂 21103	非常复杂
	 * @param string name			题目名称
	 * @param string content		题目内容（多个url用逗号隔开）
	 * @param string answerContent	答案（多个url用逗号隔开）
	 * @param string analytical		解析（多个url用逗号隔开）

	 * @return ServiceJsonResult
	 *
	 * 应答	录入失败	返回的JSON：
				{"data":{},"resCode":"000001","resMsg":"录入失败"}
			录入成功	返回的JSON：
				{"data":{
				"questionId":""
				},
				"resCode":"000001",
				"resMsg":"录入成功"
				}
	 */

	function  questionPicAdd(
		$model,
		$name,
		$answerContent,
		$operater)
	{
		$param = [
			'provience' => $model->provience,
			'city ' => $model->city,
			'country' => $model->country,
			'gradeid' => $model->gradeid,
			'subjectid' => $model->subjectid,
			'versionid' => $model->versionid,
			'complexity' => $model->complexity,
			'content' => $model->content,
			'analytical' => $model->analytical,
			'answerContent' => $answerContent,
			'name' => $name,
			'operater' => $operater
		];
		$soapResult = $this->_soapClient->questionPicAdd($param);
		$jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
		$json = json_decode($jsonStr, true);
		$result = $this->mapperJsonResult($json, true);
		return $result;

	}
}