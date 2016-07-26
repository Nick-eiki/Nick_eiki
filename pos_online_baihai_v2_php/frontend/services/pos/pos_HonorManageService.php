<?php
namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-12-2
 * Time: 下午5:14
 */
class pos_HonorManageService extends  BaseService{
    function __construct(){
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService']."schoolService/HonorManage?wsdl");
    }

    /**
     * 3.36.1.添加荣誉信息
     * 接口地址	http://主机地址:端口号/ schoolService / HonorManage?wsdl
     * 方法名	addHonor
     * @param $honorInfor       荣誉信息
     * @param $honorBelongID    荣誉所属对象id
     * @param $honorType        荣誉类型( 50301：学生荣誉,50302：教师荣誉,50303：班级荣誉)
     * {"data":{"honorID":"1014"},  荣誉id
    "resCode":"000000",
    "resMsg":"添加成功！"}
     * @return ServiceJsonResult
     */
    public function addHonor($honorInfor,$honorBelongID,$honorType){
        $soapResult = $this->_soapClient->addHonor(array("honorInfor" => $honorInfor,'honorBelongID'=>$honorBelongID,'honorType'=>$honorType));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.36.2.修改荣誉信息
     * 接口地址	http://主机地址:端口号/ schoolService / HonorManage?wsdl
     * 方法名	honorModify
     * @param $honorID      荣誉信息id
     * @param $honorInfor   荣誉信息
     * {"data":null,"resCode":"000000","resMsg":"修改成功！"}
     * @return ServiceJsonResult
     */
    public function honorModify($honorID,$honorInfor){
        $soapResult = $this->_soapClient->honorModify(array('honorID'=>$honorID,"honorInfor" => $honorInfor));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.36.3.删除荣誉信息
     * 接口地址	http://主机地址:端口号/ schoolService / HonorManage?wsdl
     * 方法名	honorDelete
     * @param $honorID      荣誉信息
     * {"data":null,"resCode":"000000",
     * @return ServiceJsonResult
     */
    public function honorDelete($honorID){
        $soapResult = $this->_soapClient->honorDelete(array('honorID'=>$honorID));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.36.4.查询荣誉信息
     * 接口地址	http://主机地址:端口号/ schoolService / HonorManage?wsdl
     * 方法名	queryHonor
     * @param $honorBelongID    荣誉所属对象ID
     * @param $honorType        荣誉类型
     * {"data":{"honorListSize":1,"honorList":[{"honorInfor":"荣誉1荣誉2",  荣誉信息
    "honorID":"1011"}
    ]},
    "resCode":"000000",
    "resMsg":"成功"}
     *
     * @return array
     */
    public function queryHonor($honorBelongID,$honorID,$honorType){
        $soapResult = $this->_soapClient->queryHonor(array('honorBelongID' => $honorBelongID,'honorType'=>$honorType,'honorID'=>$honorID));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }
}