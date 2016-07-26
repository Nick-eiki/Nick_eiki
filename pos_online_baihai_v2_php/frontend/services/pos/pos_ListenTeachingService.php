<?php
namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by 王
 * User: Administrator
 * Date: 14-9-17
 * Time: 下午1:44
 */
class pos_ListenTeachingService extends BaseService
{
    function    __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/listenTeaching?wsdl");
    }

    /**
     * @param $title
     * @param $teacherID
     * @param $chapterID
     * @param $teachingGroupID
     * @param $joinTime
     * @param $joinUsers
     * @param $creatorID
     * @return ServiceJsonResult
     * 2.4.7    创建听课计划
     */
    public function createListenTeaching($title, $teacherID, $chapterID, $teachingGroupID, $joinTime, $joinUsers, $creatorID)
    {
        $soapResult = $this->_soapClient->createListenTeaching(array(
            "title" => $title,
            "teacherID" => $teacherID,
            "chapterID" => $chapterID,
            "teachingGroupID" => $teachingGroupID,
            "joinTime" => $joinTime,
            "joinUsers" => $joinUsers,
            "creatorID" => $creatorID
        ));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * @param $userID
     * @param $queryType
     * @param $ID
     * @param $currPage
     * @param $pageSize
     * @return ServiceJsonResult
     * 2.4.8    查询听课计划
     */
    public function queryListenTeaching($userID, $queryType, $ID = '',$teachingGroupID, $currPage = '', $pageSize = '')
    {
        $soapResult = $this->_soapClient->queryListenTeaching(array(
            "userID" => $userID,
            "queryType" => $queryType,
            "ID" => $ID,
            "teachingGroupID"=>$teachingGroupID,
            "currPage" => $currPage,
            "pageSize" => $pageSize
        ));
         return  $this->soapResultToJsonResult($soapResult);
    }
    /*
     * 按日期查询听课计划
     */
    public function queryListenTeachingByDatetime($teachingGroupID='',$dateTime='',$currPage='',$pageSize=''){
        $soapResult = $this->_soapClient->queryListenTeachingByDatetime(array(
            "teachingGroupID" => $teachingGroupID,
            "dateTime" => $dateTime,
            "currPage" => $currPage,
            "pageSize" => $pageSize
        ));
        $result=  $this->soapResultToJsonResult($soapResult);
        if($result->resCode==BaseService::successCode){
            return $result->data;
        }
        return array();
    }
    /**
     * @param $userID
     * @return ServiceJsonResult
     * 2.4.2    查询用户参与的听课计划（未评课）
     */
    public function  queryListenTeachNp($userID)
    {
        $soapResult = $this->_soapClient->queryListenTeachNp(array(
            "userID" => $userID,
        ));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * @param $ID
     * @param $title
     * @param $teacherID
     * @param $chapterID
     * @param $teachingGroupID
     * @param $joinTime
     * @param $joinUsers
     * @param $creatorID
     * @return ServiceJsonResult
     * 3.10.2.    修改听课计划
     */
    public function updateListenTeaching($ID, $title, $teacherID, $chapterID, $teachingGroupID, $joinTime, $joinUsers, $creatorID)
    {
        $soapResult = $this->_soapClient->updateListenTeaching(array(
            "ID" => $ID,
            "title" => $title,
            "teacherID" => $teacherID,
            "chapterID" => $chapterID,
            "teachingGroupID" => $teachingGroupID,
            "joinTime" => $joinTime,
            "joinUsers" => $joinUsers,
            "creatorID" => $creatorID
        ));
         return  $this->soapResultToJsonResult($soapResult);
    }

}