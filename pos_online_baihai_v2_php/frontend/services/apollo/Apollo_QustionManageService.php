<?php

namespace frontend\services\apollo;
use ArrayObject;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by phpstorm
 * User: liquan
 * Date: 14-11-06
 * Time: PM 15:10
 */
//试题管理
class Apollo_QustionManageService extends BaseService
{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['apollo_webService'] . "apollo/resource/questionInfo?wsdl");
    }

    //题目列表
    public function questionSearch($operater, $tags = '', $currPage, $pageSize, $id = '', $name = '', $provience = '', $city = '', $country = '', $schoolLevel = '', $gradeid = '', $subjectid = '', $versionid = '', $kid = '', $typeId = '', $provenance = '', $year = '', $school = '', $complexity = '', $capacity = '', $content = '', $analytical = '', $questionPrice = '', $chapterId = '', $userID = '')
    {

	 $param = array(
		    "operater" => $operater,
		    "currPage" => $currPage,
		    "pageSize" => $pageSize,
		    "id" => $id,
		    "name" => $name,
		    "provience" => $provience,
		    "city" => $city,
		    "country" => $country,
		    "schoolLevel" => $schoolLevel,
		    "gradeid" => $gradeid,
		    "subjectid" => $subjectid,
		    "versionid" => $versionid,
		    "kid" => $kid,
		    "typeId" => $typeId,
		    "provenance" => $provenance,
		    "year" => $year,
		    "school" => $school,
		    "complexity" => $complexity,
		    "capacity" => $capacity,
		    "tags" => $tags,
		    "content" => $content,
		    "analytical" => $analytical,
		    "questionPrice" => $questionPrice,
		    'chapterId' => $chapterId,
		    'userID' => $userID
	    );
        $soapResult = $this->_soapClient->questionSearch($param
           );
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        $result = $this->mapperJsonResult($json);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    //题目列表
    public function questionSearchByShowtype($operater, $tags = '', $currPage, $pageSize, $id = '', $name = '', $provience = '', $city = '', $country = '', $schoolLevel = '', $gradeid = '', $subjectid = '', $versionid = '', $kid = '', $typeId = '', $provenance = '', $year = '', $school = '', $complexity = '', $capacity = '', $content = '', $analytical = '', $questionPrice = '', $showTypeId = '')
    {
        $soapResult = $this->_soapClient->questionSearchByShowtype(
            array(
                "operater" => $operater,
                "currPage" => $currPage,
                "pageSize" => $pageSize,
                "id" => $id,
                "name" => $name,
                "provience" => $provience,
                "city" => $city,
                "country" => $country,
                "schoolLevel" => $schoolLevel,
                "gradeid" => $gradeid,
                "subjectid" => $subjectid,
                "versionid" => $versionid,
                "kid" => $kid,
                "typeId" => $typeId,
                "provenance" => $provenance,
                "year" => $year,
                "school" => $school,
                "complexity" => $complexity,
                "capacity" => $capacity,
                "tags" => $tags,
                "content" => $content,
                "analytical" => $analytical,
                "questionPrice" => $questionPrice,
                'showTypeId' => $showTypeId
            ));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        $result = $this->mapperJsonResult($json);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    public function questionSearchByid($user, $id)
    {
        $result = $this->questionSearch($user, '', '', '', $id);
        if (empty($result->list)) {
            return array();
        }
        return $result->list[0];
    }

    /**
     * 查询题目管理列表
     * @param $operater
     * @param $tags
     * @param $currPage
     * @param $pageSize
     * @param $typeId
     * @param $complexity
     * @return array
     */
    public function searchQuestionList($operater, $tags, $currPage, $pageSize, $schoolLevel, $subjectid, $kid, $typeId, $complexity,$chapterID,$version)
    {
        return $this->questionSearch($operater, $tags, $currPage, $pageSize, '', '', '', '', '', $schoolLevel, '', $subjectid, $version, $kid, $typeId, '', '', '', $complexity, '', '', $chapterID, '');
    }

    //添加题目

    /*
     * 创建临时题目
     * operater	录入人
     */
    public function createTempQuestion($operater = '')
    {
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
    public function saveQuestionHead($id = '', $provience = '', $city = '', $country = '', $gradeid = '', $subjectid = '', $versionid = '', $kid = '',
                                     $typeId = '', $provenance = '', $year = '', $complexity = '', $capacity = '',
                                     $tags = '', $name = '', $questionPrice = '', $quesLevel = '', $quesFrom = '', $content = '', $textContent = '')
    {
        $soapResult = $this->_soapClient->saveQuestionHead(
            array(
                'id' => $id, 'provience' => $provience, 'city' => $city, 'country' => $country, 'gradeid' => $gradeid, 'subjectid' => $subjectid,
                'versionid' => $versionid, 'kid' => $kid, 'typeId' => $typeId, 'provenance' => $provenance, 'year' => $year, 'complexity' => $complexity,
                'capacity' => $capacity, 'tags' => $tags, 'name' => $name, 'questionPrice' => $questionPrice, 'quesLevel' => $quesLevel,
                'quesFrom' => $quesFrom, 'content' => $content, 'textContent' => $textContent,
            ));
        return $this->soapResultToJsonResult($soapResult);

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
    public function saveQuestionContent($id = '', $answerOptionJson = '', $answerContent = '', $analytical = '', $childQuesJson = '', $saveType = '')
    {
        $soapResult = $this->_soapClient->saveQuestionContent(
            array(
                'id' => $id,
                'answerOptionJson' => $answerOptionJson,
                'answerContent' => $answerContent,
                'analytical' => $analytical,
                'childQuesJson' => $childQuesJson,
                'saveType' => $saveType,
            ));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        return $this->mapperJsonResult($json);
    }

    /*
     * 查询临时题目
     * id	题目id
     */
    public function queryTempQuesById($id)
    {
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

    /*
     * 修改题目难度
     */
    public function modifyQuestionSomeInfo($questionId, $userID, $complexity)
    {
        $soapResult = $this->_soapClient->modifyQuestionSomeInfo(
            array(
                'questionId' => $questionId,
                'userID' => $userID,
                'complexity' => $complexity,
            ));
        return $this->soapResultToJsonResult($soapResult);
    }

}