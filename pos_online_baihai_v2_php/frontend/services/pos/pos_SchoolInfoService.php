<?php
namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/** * Created by 王
 * User: Administrator
 * Date: 14-9-10
 * Time: 下午7:07
 */
class pos_SchoolInfoService extends BaseService
{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/schoolInfo?wsdl");
    }

    /**
     * 搜索学校信息
     * @param string $schoolID
     * @param string $schoolName
     * @param string $currPage
     * @param string $pageSize
     * @param string $nickname
     * @param string $department
     * @param string $lengthOfSchooling
     * @param string $provience
     * @param string $city
     * @param string $country
     * @param string $ispass
     * @param string $trainingSchool
     * @param string $ispage
     * @return null
     */
    public function searchSchoolInfo($schoolID = '', $schoolName = '', $currPage = '', $pageSize = '', $nickname = '', $department = '', $lengthOfSchooling = '', $provience = '', $city = '', $country = '', $ispass = '', $trainingSchool = '', $ispage = '')
    {
        $soapResult = $this->_soapClient->searchSchoolInfo(array('schoolID' => $schoolID, 'schoolName' => $schoolName, 'nickname' => $nickname, 'department' => $department, 'lengthOfSchooling' => $lengthOfSchooling, 'provience' => $provience, 'city' => $city, 'country' => $country, 'ispass' => $ispass, 'trainingSchool' => $trainingSchool, 'ispage' => $ispage, 'currPage' => $currPage, 'pageSize' => $pageSize));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();

    }

    /**
     * @param string $schoolID
     * @param string $schoolName
     * @param string $currPage
     * @param string $pageSize
     * @param string $nickname
     * @param string $department
     * @param string $lengthOfSchooling
     * @param string $provience
     * @param string $city
     * @param string $country
     * @param string $ispass
     * @param string $trainingSchool
     * @param string $ispage
     * @return null
     */
    public function searchSchoolInfoByPage($schoolName, $currPage = '', $pageSize = '', $department = '', $nickname = '', $lengthOfSchooling = '', $provience = '', $city = '', $country = '')
    {

        return $this->searchSchoolInfo('', $schoolName, $currPage, $pageSize, $nickname, $department, $lengthOfSchooling, $provience, $city, $country, $ispass = 1, $trainingSchool = 0, $ispage = 1);

    }

    /**
     * @param $schoolModel
     * @return ServiceJsonResult
     *  修改学校信息
     */
    public function updateSchoolinfo($schoolModel){
        $array=array(
           "schoolID"=>$schoolModel->schoolID,
            "schoolName"=>$schoolModel->schoolName,
            "nickname"=>$schoolModel->nickName,
            "department"=>$schoolModel->department,
            "lengthOfSchooling"=>$schoolModel->lengthOfSchooling,
            "brief"=>$schoolModel->brief,
        );
        $soapResult = $this->_soapClient->updateSchoolinfo($array);
         return  $this->soapResultToJsonResult($soapResult);
    }


    /**
     * /**
     * 添加学校
     * schoolName    学校名称
     * nickname    学校别名（可为空）
     *department    学部分布
     * 20201    小学部
     * 20202    初中部
     * 20203    高中部
     * lengthOfSchooling    学制
     * schoolAddress    学习地址（可为空）
     * provience    省（可为空）
     * city    城市（可为空）
     * country    区县（可为空）
     * creatorID    创建人
     * brief    简介
     * token    安全保护措施
     *
     * @param $schoolName
     * @param $department
     * @param $lengthOfSchooling
     * @param string $nickName
     * @param string $provience
     * @param string $city
     * @param string $country
     * @param string $brief
     * @param string $creatorID
     * @return ServiceJsonResult
     */
    public function addSchoolInfo($schoolName, $department, $lengthOfSchooling, $nickName = '', $provience = '', $city = '', $country = '', $brief = '', $creatorID = 1)
    {
        $array = array(
            "schoolName" => $schoolName,
            "nickName" => $nickName,
            "department" => $department,
            "lengthOfSchooling" => $lengthOfSchooling,
            "provience" => $provience,
            "city" => $city,
            "country" => $country,
            "creatorID" => $creatorID,
            "brief" => $brief
        );
        $soapResult = $this->_soapClient->addSchoolInfo($array);
         return  $this->soapResultToJsonResult($soapResult);

    }

    /**
     * @param $schoolID
     * @return ServiceJsonResult
     * 通过学校ID搜索学校信息
     */
    public function searchSchoolInfoById($schoolID){
        $soapResult = $this->_soapClient->searchSchoolInfoById(array("schoolID"=>$schoolID));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * @param $schoolID
     * @param $loginUrl
     * @return ServiceJsonResult
     * 3.5.5.	修改学校logo
     */
    public function  modifySchoolLogo($schoolID,$logoUrl){
        $soapResult = $this->_soapClient->modifySchoolLogo(array("schoolID"=>$schoolID,"logoUrl"=>$logoUrl));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * @param $schoolID
     * @param $newLenOfSch
     * @param $newLenOfSchDate
     * @return ServiceJsonResult
     * 3.5.7.	添加学制
     */
    public function addNewLenOfSch($schoolID,$newLenOfSch,$newLenOfSchDate){
        $soapResult = $this->_soapClient->addNewLenOfSch(array("schoolID"=>$schoolID,"newLenOfSch"=>$newLenOfSch,"newLenOfSchDate"=>$newLenOfSchDate));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 查询学校的年级信息
     * "gradeList": [
     * {
     * "gradeId": "1001",//年级ID
     * "gradeName": "一年",//年级名称
     * "lenOfSch": "20501",//学制编码a
     * "lenOfSchName": "六三学制",//学制名称
     * "schDep": "20201",//学段编码
     * "schDepName": "小学部"//学段名称
     * },
     * {
     * "gradeId": "1002",
     * "gradeName": "二年",
     * "lenOfSch": "20501",
     * "lenOfSchName": "六三学制",
     * "schDep": "20201",
     * "schDepName": "小学部"
     * },
     * {
     * "gradeId": "1003",
     * "gradeName": "三年",
     * "lenOfSch": "20501",
     * "lenOfSchName": "六三学制",
     * "schDep": "20201",
     * "schDepName": "小学部"
     * },
     * {
     * "gradeId": "1004",
     * "gradeName": "四年",
     * "lenOfSch": "20501",
     * "lenOfSchName": "六三学制",
     * "schDep": "20201",
     * "schDepName": "小学部"
     * },
     * {
     * "gradeId": "1005",
     * "gradeName": "五年",
     * "lenOfSch": "20501",
     * "lenOfSchName": "六三学制",
     * "schDep": "20201",
     * "schDepName": "小学部"
     * },
     * @param $schoolId
     * @return array
     */
    public function loadGradeByschool($schoolId)
    {
        $param = array("schoolId" => $schoolId);
        $soapResult = $this->_soapClient->loadGradeBySchool($param);
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            if (isset($result->data->gradeList)) {
                return $result->data->gradeList;
            };
        }
        return array();

    }


}