<?php

namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: liquan
 * Date: 2015/3/23
 * Time: 18:31
 */
class pos_ClassSloganService extends BaseService{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/classSlogan?wsdl");
    }
    /*
     * 查询班级口号
     * $classID
     */
    public function searchClassSlogan($classID){
        $soapResult = $this->_soapClient->searchClassSlogan(
            array("classID" => $classID));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }
    /*
     * 修改班级口号
     * classID	班级id
     * classSlogan	学校口号
     * userID	当前用户id
     */
    public function modifyClassSlogan($classID,$classSlogan,$userID){
        $soapResult = $this->_soapClient->modifyClassSlogan(
            array("classID" => $classID,"classSlogan" => $classSlogan,"userID" => $userID));
        return  $this->soapResultToJsonResult($soapResult);
    }

}