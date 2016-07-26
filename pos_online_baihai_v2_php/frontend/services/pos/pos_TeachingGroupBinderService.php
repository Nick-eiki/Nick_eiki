<?php
namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by liquan
 * User: Administrator
 * Date: 14-10-31
 * Time: ����1:20
 */
class pos_TeachingGroupBinderService extends BaseService
{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/teachingGroupBinder?wsdl");
    }
    //手拉手教研组申请列表
    public function requestTeaGroupQuery($groupID = '',$selectType = '',$currPage = '',$pageSize = ''){	
    	$soapResult = $this->_soapClient->requestTeaGroupQuery(
			array(
			"groupID" => $groupID,
    		"selectType" => $selectType, 
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
    /* 未申请列表
     * provience
	 * city
	 * country
	 * departmentID 学部
	 * schoolName 学校名称
	 * groupID
	 * currPage
	 * pageSize
     * 
     */
    public function queryNoBinderGroup($provience='',$city='',$country='',$departmentID='',$schoolName='',$groupID,$currPage,$pageSize){
    	
    	$soapResult = $this->_soapClient->queryNoBinderGroup(
    			array(
    					"provience" => $provience,
    					"city" => $city,
    					"country" => $country,
    					"departmentID" => $departmentID,
    					"schoolName" => $schoolName,
    					"groupID" => $groupID,
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
    //保存驳回或接受状态
    /*  ID	绑定请求ID
        askUserID	应答者ID
	    reason	原因
	    msgCode	应答代码
	    1：表示接受请求0：拒绝请求
    */
    public function saveGroupResponse($ID,$askUserID,$reason,$msgCode){
		//echo $ID."<br>".$askUserID."<br>".$reason."<br>".$msgCode;
    	$soapResult = $this->_soapClient->saveGroupResponse(
    			array(
    					"ID" => $ID,
    					"askUserID" => $askUserID,
    					"reason" => $reason,
    					"msgCode" => $msgCode
    			));
    	$jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
    	$json = json_decode($jsonStr);
    	return $this->mapperJsonResult($json);
    }
   /*
    * 发起申请
   	beginGroupID	发起教研组
    acceptGroupID	接收教研组
    initiator	发起人
    token	安全保护策略
   */
    public function requestTeaGroupBinder($beginGroupID = '',$acceptGroupID = '',$initiator= ''){
    	$soapResult = $this->_soapClient->requestTeaGroupBinder(
    			array(
    					"beginGroupID" => $beginGroupID,
    					"acceptGroupID" => $acceptGroupID,
    					"initiator" => $initiator,
    			));
    	$jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
    	$json = json_decode($jsonStr);
    	return $this->mapperJsonResult($json);
    }
	/*
	 3.28.5.查询绑定教研组
	 groupID	当前教研组ID
	*/
	public function queryBinderGroupByID($groupID = '',$currPage = '', $pageSize = ''){
		$soapResult = $this->_soapClient->queryBinderGroupByID(
    			array(
    					"groupID" => $groupID,
						"currPage"=> $currPage,
						"pageSize"=> $pageSize
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
	 *取消手拉手教研组
	 *ID	绑定请求ID
	 *userID	操作用户ID
	 *groupID	操作教研组
	 *reason	原因(可为空)
	 */
	 public function cancelGroupBinder($ID,$userID,$groupID,$reason = ''){
		$soapResult = $this->_soapClient->cancelGroupBinder(
    			array(
    					"ID" => $ID,
    					"userID" => $userID,
    					"groupID" => $groupID,
						"reason" => $reason
    			));
    	$jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
    	$json = json_decode($jsonStr);
    	return $this->mapperJsonResult($json);
	 }
}