<?php
namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: ysd
 * Date: 14-10-31
 * Time: 上午11:05
 */
class  pos_InternetParMeetingService extends BaseService{
    function __construct(){
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService']."schoolService/internetParMeeting?wsdl");
    }

    /**
     * @param $creatorID
     * @param $classID
     * @param $meetingName
     * @param $meetingDetail
     * @param $beginTime
     * @param $finishTime
     * @return ServiceJsonResult
     * 创建会议
     */
    public function createIntParMeeting($creatorID,$classID,$meetingName,$meetingDetail,$beginTime,$finishTime){
        $soapResult = $this->_soapClient->createIntParMeeting(array("creatorID"=>$creatorID,"classID"=>$classID,"meetingName"=>$meetingName,"meetingDetail"=>$meetingDetail,"beginTime"=>$beginTime,"finishTime"=>$finishTime));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * @param $meeID
     * @param $classID
     * @param $meetingName
     * @param $meetingDetail
     * @param $beginTime
     * @param $finishTime
     * @return ServiceJsonResult
     * 修改会议
     */
    public function modifyIntParMeeting($meeID,$classID,$meetingName,$meetingDetail,$beginTime,$finishTime){
        $soapResult = $this->_soapClient->modifyIntParMeeting(array("meeID"=>$meeID,"classID"=>$classID,"meetingName"=>$meetingName,"meetingDetail"=>$meetingDetail,"beginTime"=>$beginTime,"finishTime"=>$finishTime));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * @param $creatorID
     * @param $classID
     * @param $meetingName
     * @param $meetingDetail
     * @param $beginTime
     * @param $finishTime
     * @param $currPage
     * @param $pageSize
     * @return ServiceJsonResult
     * 查询会议列表
     */
    public function searchIntParMeetingList($creatorID,$classID,$meetingName,$meetingDetail,$beginTime,$finishTime,$currPage,$pageSize){
        $soapResult = $this->_soapClient->searchIntParMeetingList(array("creatorID"=>$creatorID,"classID"=>$classID,"meetingName"=>$meetingName,"meetingDetail"=>$meetingDetail,"beginTime"=>$beginTime,"finishTime"=>$finishTime,"currPage"=>$currPage,"pageSize"=>$pageSize));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * @param $meeID
     * @return ServiceJsonResult
     * 查询会议详情
     */
    public function searchIntParMeetingByID($meeID){
        $soapResult = $this->_soapClient->searchIntParMeetingByID(array("meeID"=>$meeID));
         return  $this->soapResultToJsonResult($soapResult);
    }








}

