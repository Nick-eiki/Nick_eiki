<?php

namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-9-15
 * Time: 下午5:37
 */
class pos_TeachingPlanInfoService extends BaseService{
    function __construct(){
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService']."schoolService/TeachingPlanInfo?wsdl");
    }

    /**
     * 高 2014.9.15
     * 4.6.1.教学计划搜索
     * 接口地址	http://主机地址:端口号/ schoolService / TeachingPlanInfo?wsdl
     * 方法名	teachingPlanSearch
     * @param $type             类型0：教研组教学计划 1：教师个人教学计划
     * @param $gradeID          年级id（可为空）
     * @param $creatorID        创建者id（可为空）
     * @param $teachingGroupID  教研组id（可为空）
     * @param $ispage           是否分页，0:不分页，1：分页，默认0
     * @param $currPage
     * @param $pageSize
     * @param $token            安全保护措施
     *
     * 返回的JSON示例：
    {
    {"data":
    {"countSize":"1",
    "pageSize":"2",
    "currPage":"1"
    ,"teachingPlanList":[{
    “teachingPlanID”:””
    "planName":"3",
    "gradeID":"3",
    "brief":"3"},
    ],
    "totalPages":"1"},
    "resCode":"000000",
    "resMsg":"查询成功"}}
     * @return ServiceJsonResult
     */
    public function teachingPlanSearch($type,$gradeID,$creatorID,$teachingGroupID,$ispage,$currPage,$pageSize){
        $soapResult = $this->_soapClient->teachingPlanSearch(array('type'=>$type,'gradeID' => $gradeID,'creatorID'=>$creatorID,'teachingGroupID'=>$teachingGroupID,'ispage'=>$ispage,'currPage'=>$currPage,'pageSize'=>$pageSize));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /**
     * 高 2014.9.16
     * 3.6.2.教学计划添加
     * 接口地址	http://主机地址:端口号/ schoolService / TeachingPlanInfo?wsdl
     * 方法名	teachingPlanAdd
     * @param $type                 教学计划类型（0：教研组教学计划，    1：教师个人教学计划）
     * @param $planName             教学计划名称
     * @param $gradeID              年级id
     * @param $brief                教学计划简介
     * @param $creatorID            创建者id
     * @param $url                  教学计划附件
     * @param $teachingGroupID      教研组id。如果教学计划所属类型是教研组教学计划，存储教研组id，否为该字段值为空
     * @param $token                安全保护措施
     * 返回的JSON示例：
    {
    {"data":{
    “teachingPlanID”:”计划id”
    },
    "resCode":"000000",
    "resMsg":"添加了第1014个教学计划"
    }
     * @return ServiceJsonResult
     */
    public function teachingPlanAdd($type,$planName,$gradeID,$brief,$creatorID,$url,$teachingGroupID,$token){
        $soapResult = $this->_soapClient->teachingPlanAdd(array("type" => $type,'planName'=>$planName,'gradeID'=>$gradeID,'brief'=>$brief,'creatorID'=>$creatorID,'url'=>$url,'teachingGroupID'=>$teachingGroupID,'token'=>$token));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 高 2014.9.16
     * 3.6.3.编辑教学计划
     * 接口地址	http://主机地址:端口号/ schoolService / TeachingPlanInfo?wsdll
     * 方法名	teachingPlanSave
     * @param $teachingPlanID   序号id
     * @param $planName         教学计划名称
     * @param $gradeID          年级id
     * @param $brief            教学计划简介
     * @param $url              教学计划附件
     * @param $token            安全保护措施
     * {
    {"data":{
    “teachingPlanID”：“”
    },
    "resCode":"000000",
    "resMsg":"保存了1个教学计划"
    }}
     * @return ServiceJsonResult
     */
    public function teachingPlanSave($teachingPlanID,$planName,$gradeID,$brief,$url,$token){
        $soapResult = $this->_soapClient->teachingPlanSave(array("teachingPlanID" => $teachingPlanID,'planName'=>$planName,'gradeID'=>$gradeID,'brief'=>$brief,'url'=>$url,'token'=>$token));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 高 2014.9.16
     * 3.6.5.查询详细教学计划
     * 接口地址	http://主机地址:端口号/ schoolService / TeachingPlanInfo?wsdl
     * 方法名	teachingPlanDetaileSearch
     * @param $teachingPlanID   序号id
     * @param $token            安全控制
     * {"data":{
    "teachingPlanID":"",
    "planName":"1",
    "gradeID":"1",
    “gradeName”:””,
    "creatorID":"1",
    "type":null,
    “teachingGroupID”：””,
    "url":"1",
    "brief":"1"},
    "resCode":"000000",
    "resMsg":"成功"
    }
     * @return ServiceJsonResult
     */
    public function teachingPlanDetaileSearch($teachingPlanID,$token){
        $soapResult = $this->_soapClient->teachingPlanDetaileSearch(array("teachingPlanID" => $teachingPlanID,'token'=>$token));
           $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return null;
    }

}