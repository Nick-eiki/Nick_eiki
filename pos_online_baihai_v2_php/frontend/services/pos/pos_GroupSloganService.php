<?php

namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: liquan
 * Date: 2015/3/24
 * Time: 11:06
 */
class pos_GroupSloganService extends BaseService{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/groupSlogan?wsdl");
    }
    /*
     * 查询教研组口号
     * $groupID
     */
    public function searchGroupSlogan($groupID){
        $soapResult = $this->_soapClient->searchGroupSlogan(
            array("groupID" => $groupID));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }
    /*
     * 修改口号
     * cgroupID	班级id
     * groupSlogan	学校口号
     * userID	当前用户id
     */
    public function modifyGroupSlogan($groupID,$groupSlogan,$userID){
        $soapResult = $this->_soapClient->modifyGroupSlogan(
            array("groupID" => $groupID,"groupSlogan" => $groupSlogan,"userID" => $userID));
        return  $this->soapResultToJsonResult($soapResult);
    }

}