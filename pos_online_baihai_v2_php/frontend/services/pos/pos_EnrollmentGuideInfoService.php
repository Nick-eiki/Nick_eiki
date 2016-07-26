<?php

namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by 王
 * User: Administrator
 * Date: 14-9-10
 * Time: 上午10:57
 */
class pos_EnrollmentGuideInfoService extends BaseService{
    function __construct(){
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService']."schoolService/EnrollmentGuideInfo?wsdl");
    }

    /**
     * @param $briefName
     * @param $department
     * @param $year
     * @param $detailOfBrief
     * @param $creatorID
     * @return ServiceJsonResult
     * 添加招生简章
     */
    public function enrollmentGuideAdd($briefName,$department,$year,$schoolID,$detailOfBrief,$creatorID){
        $soapResult = $this->_soapClient->EnrollmentGuideAdd(array("briefName"=>$briefName,"department"=>$department,"year"=>$year,'schoolID'=>$schoolID,"detailOfBrief"=>$detailOfBrief,"creatorID"=>$creatorID));
         return  $this->soapResultToJsonResult($soapResult);
    }


    /**
     * 招生简章搜索
     * @param string $department
     * @param string $schoolID
     * @param string $year
     * @param string $currPage
     * @param string $pageSize
     * @param int $ispage
     * @return null
     */
    public function enrollmentGuideSearch($department = '', $schoolID = '', $year = '', $currPage = '', $pageSize = '', $ispage = 1){
        $soapResult = $this->_soapClient->enrollmentGuideSearch(array("department" => $department, 'schoolID' => $schoolID, "year" => $year, 'ispage' => $ispage, 'currPage' => $currPage, 'pageSize' => $pageSize));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            if (isset($result->data)) {
                return $result->data;
            }
        }
        return null;

    }

    /**
     * 详情
     * @param $briefID
     * @return null
     */
    public function egDetailSearch($briefID){
        $soapResult = $this->_soapClient->egDetailSearch(array("briefID" => $briefID));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            if (isset($result->data)) {
                return $result->data;
            }
        }
        return null;

    }

    /**
     * 编辑
     * @param $briefName
     * @param $briefID
     * @param $department
     * @param $year
     * @param $detailOfBrief
     * @return ServiceJsonResult
     */
    public function enrollmentGuideSave($briefName, $briefID, $department, $year, $detailOfBrief){
        $soapResult = $this->_soapClient->enrollmentGuideSave(array("briefName"=>$briefName, 'briefID' => $briefID, "department"=>$department,"year"=>$year, "detailOfBrief"=>$detailOfBrief));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.16.6.搜索学校最近的一条招生简章
     * 接口地址	http://主机地址:端口号/ schoolService / EnrollmentGuideInfo?wsdll
     * 方法名	enrollmentGuideLately
     * @param $schoolID     学校id
     * @param $department   学部编码（可为空）
     * "data": {
    “briefID”招生简章ID
    "createTime":"3",创建时间
    "nameOfCreator":null,创建人名称

    "department":"33",学部ID，学段ID
    “departmentName”学部名称
    "schoolID":"2",学校ID
    “schoolName”学校名称
    "year":"33",年份
    "creatorID":null,创建ID
    "detailOfBrief":"33"详细内容
    ,"briefName":"1"简章名称
    },
    "resCode": "000000",
    "resMsg": "查询成功"
    }
     * @return null
     */
    public function enrollmentGuideLately($schoolID,$department){
        $soapResult = $this->_soapClient->enrollmentGuideLately(array("schoolID" => $schoolID,'department'=>$department));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            if (isset($result->data)) {
                return $result->data;
            }
        }
        return null;
    }

    /**
     * 3.17.7.查找上一条招生简章
     * 接口地址	http://主机地址:端口号/ schoolService / EnrollmentGuideInfo?wsdl
     * 方法名	queryPreviousPageByid
     * @param $briefID      当前招生简章ID
     * @param $schoolID	    学校id
     * @param $department	学部编码（可为空）
     * 应答	         失败	返回的JSONB：参考响应代码
                            成功	返回的JSON示例：
                            {
                            "data": {
                            “briefID”招生简章ID
                            "createTime":"3",创建时间
                            "nameOfCreator":null,创建人名称

                            "department":"33",学部ID，学段ID
                            “departmentName”学部名称
                            "schoolID":"2",学校ID
                            “schoolName”学校名称
                            "year":"33",年份
                            "creatorID":null,创建ID
                            "detailOfBrief":"33"详细内容
                            ,"briefName":"1"简章名称
                            },
                            "resCode": "000000",
                            "resMsg": "查询成功"
                            }
     *
     *
     */
    public function queryPreviousPageByid($briefID, $schoolID, $department)
    {
        $soapResult = $this->_soapClient->queryPreviousPageByid(
            array(
                "briefID" => $briefID,
                'schoolID' => $schoolID,
                'department' => $department
            ));
        return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.17.8.查找下一条招生简章
     * 接口地址 http://主机地址:端口号/schoolService/EnrollmentGuideInfo?wsdl
     * 方法名	queryNextPageByid
     * @param $briefID      当前招生简章ID
     * @param $schoolID	    学校id
     * @param $department	学部编码（可为空）
     * 应答	         失败	返回的JSONB：参考响应代码
                    成功	     返回的JSON示例：
                            {
                            "data": {
                            “briefID”招生简章ID
                            "createTime":"3",创建时间
                            "nameOfCreator":null,创建人名称

                            "department":"33",学部ID，学段ID
                            “departmentName”学部名称
                            "schoolID":"2",学校ID
                            “schoolName”学校名称
                            "year":"33",年份
                            "creatorID":null,创建ID
                            "detailOfBrief":"33"详细内容
                            ,"briefName":"1"简章名称
                            },
                            "resCode": "000000",
                            "resMsg": "查询成功"
                            }
     *
     *
     */
    public function queryNextPageByid($briefID, $schoolID, $department)
    {
        $soapResult = $this->_soapClient->queryNextPageByid(
            array(
                "briefID" => $briefID,
                'schoolID' => $schoolID,
                'department' => $department
            ));
        return  $this->soapResultToJsonResult($soapResult);
    }

}