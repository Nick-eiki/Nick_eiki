<?php

namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by editplus.
 * User: liquan
 * Date: 14-11-03
 * Time: AM 11:50
 */
class pos_ClassSectionSummaryService extends BaseService{

    function __construct(){
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService']."schoolService/classSectionSummary?wsdl");
    }
	//  3.27.2.查询总结列表
	/*  classID	班级ID
		subjectID	科目ID（可为空）
		summarizeName	总结名称（可为空）
		beginTime	开始时间（可为空）
		finishTime	结束时间（可为空）
		creatorID	创建人ID（可为空）
		currPage	当前页码
		pageSize	每页条数
	*/
	public function searchSectionSummaryList($classID,$creatorID,$searchType,$subjectID = '',$summarizeName = '',$beginTime = '',$finishTime = '',$currPage,$pageSize){
		$soapResult = $this->_soapClient->searchSectionSummaryList(
    			array(
    					"classID" => $classID,
						"creatorID" => $creatorID,
						"searchType" => $searchType,
    					"subjectID" => $subjectID,
    					"summarizeName" => $summarizeName,
    					"beginTime" => $beginTime,
    					"finishTime" => $finishTime,
    					"currPage" => $currPage,
    					"pageSize" => $pageSize,
    			));
    	$jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
    	$json = json_decode($jsonStr);
    	$result = $this->mapperJsonResult($json);
    	if ($result->resCode == self::successCode) {
    		 
    		return $result->data;
    	}
    	return array();
	}
	/*  
	 *  添加班级总结
	 *  classID	班级ID
		subjectID	科目ID（可为空）
		summarizeName	总结名称
		beginTime	开始时间
		finishTime	结束时间
		classAtmosphere	班内氛围
		studyPlan	学习计划
		knowledgepoint	知识点
		creatorID	创建人ID
	*/
	public function addSectionSummary($classID,$subjectID='',$summarizeName='',$beginTime='',$finishTime='',$classAtmosphere='',$studyPlan='',$knowledgepoint,$creatorID){
		$soapResult = $this->_soapClient->addSectionSummary(
				array(
						"classID" => $classID,
						"subjectID" => $subjectID,
						"summarizeName" => $summarizeName,
						"beginTime" => $beginTime,
						"finishTime" => $finishTime,
						"classAtmosphere" => $classAtmosphere,
						"studyPlan" => $studyPlan,
						"knowledgepoint" => $knowledgepoint,
						"creatorID" => $creatorID,
				));
		$jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
		$json = json_decode($jsonStr);
		return $this->mapperJsonResult($json);
		
	}
	/*
	 *  修改班级总结
	 *  summarizeID 总结ID
	 *  classID	班级ID
		subjectID	科目ID（可为空）
		summarizeName	总结名称
		beginTime	开始时间
		finishTime	结束时间
		classAtmosphere	班内氛围
		studyPlan	学习计划
		knowledgepoint	知识点
		
	*/
	public function modifySectionSummary($summarizeID,$classID,$subjectID='',$summarizeName='',$beginTime='',$finishTime='',$classAtmosphere='',$studyPlan='',$knowledgepoint){
		$soapResult = $this->_soapClient->modifySectionSummary(
				array(
						"summarizeID" => $summarizeID,
						"classID" => $classID,
						"subjectID" => $subjectID,
						"summarizeName" => $summarizeName,
						"beginTime" => $beginTime,
						"finishTime" => $finishTime,
						"classAtmosphere" => $classAtmosphere,
						"studyPlan" => $studyPlan,
						"knowledgepoint" => $knowledgepoint,
				
				));
		$jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
		$json = json_decode($jsonStr);
		return $this->mapperJsonResult($json);
	
	}
	/*
	 * 查询单条详情
	 * searchSectionSummaryByID
	 * summarizeID	总结ID
	 */
	public function searchSectionSummaryByID($summarizeID){
		$soapResult = $this->_soapClient->searchSectionSummaryByID(
				array(
						"summarizeID" => $summarizeID,
				));
		$jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
		$json = json_decode($jsonStr);
		return $this->mapperJsonResult($json);
		
	}
	/*
	 *删除单条详情
	* summarizeID	总结ID
	*/
	public function deleteSectionSummary($summarizeID){
		$soapResult = $this->_soapClient->deleteSectionSummary(
				array(
						"summarizeID" => $summarizeID,
				));
		$jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
		$json = json_decode($jsonStr);
		return $this->mapperJsonResult($json);
	
	}
}