<?php
namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: liquan
 * Date: 2015/3/23
 * Time: 16:05
 */
class pos_SchoolSloganService extends BaseService{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/schoolSlogan?wsdl");
    }
    /*
     * 查询学校口号
     * schoolID
     */
    public function searchSchoolSlogan($schoolID){
        $soapResult = $this->_soapClient->searchSchoolSlogan(
            array("schoolID" => $schoolID));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }
    /*
     * 修改学校口号
     * schoolID	学校id
     * schoolSlogan	学校口号
     * userID	当前用户id
     */
    public function modifySchoolSlogan($schoolID,$schoolSlogan,$userID){
        $soapResult = $this->_soapClient->modifySchoolSlogan(
            array("schoolID" => $schoolID,"schoolSlogan" => $schoolSlogan,"userID" => $userID));
        return  $this->soapResultToJsonResult($soapResult);
    }

}