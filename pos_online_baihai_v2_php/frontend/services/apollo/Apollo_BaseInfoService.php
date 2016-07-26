<?php
namespace frontend\services\apollo;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: unizk
 * Date: 14-9-9
 * Time: 下午12:58
 */
class Apollo_BaseInfoService extends BaseService
{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['apollo_webService'] . "apollo/resource/BaseInfo?wsdl");
    }


    /**
     * 基础数据查询
     * @param string $type
     *  100    科目
     * 201    班级职务
     * 202    学部
     * 203    教研组职务
     * 204    班内身份
     * @return array
     */
    public function loadData($type)
    {
        $soapResult = $this->_soapClient->loadData(array("type" => $type));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data->list;
        }
        return array();
    }

    /**
     * 获取科目
     * @return array
     */
    public function getSubject()
    {
        return $this->loadData(100);
    }

    /**
     * 获取班级职务
     * @return array
     */
    public function getClassDuty()
    {
        return $this->loadData(201);
    }

    /**
     * 获取学部
     * @return array
     */
    public function getSchoolLevel()
    {
        return $this->loadData(202);
    }

    /**
     * 获取教研组职务
     * @return array
     */
    public function getTeachingResearchDuty()
    {
        return $this->loadData(203);
    }

    /**
     * 获取班内身份
     * @return array
     */
    public function getClassIdentity()
    {
        return $this->loadData(204);
    }

    /**
     * 获取学制
     * @return array
     */
    public function getLengthSchool()
    {
        return $this->loadData(205);
    }

    /**
     * 教材版本
     * @return array
     */
    public function getMaterialVersions()
    {
        return $this->loadData(206);
    }

    /**
     * 学校身份
     * @return array
     */
    public function getSchoolIdentity()
    {
        return $this->loadData(207);
    }

    /**
     * 教试类型
     * @return array
     */
    public function getExamType()
    {
        return $this->loadData(219);
    }


    /**
     * 年级信息查询
     * @param string $department 学部
     *  20201    小学部
     * 20202    初中部
     * 20203    高中部
     * @param string $lengthOfSchooling 学制
     *  20501    六三学制
     * 20502    五四学制
     * 02503    五三学制
     * @return array
     */
    public function loadGrade($department = '', $lengthOfSchooling = '')
    {
        if (!isset($department)) {
            $department = '';
        }

        if (!isset($lengthOfSchooling)) {
            $lengthOfSchooling = '';
        }
        $soapResult = $this->_soapClient->loadGrade(array("department" => $department, "lengthOfSchooling" => $lengthOfSchooling));

        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data->gradeList;
        }
        return array();

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
    public function loadGradeByschool($schoolId, $department)
    {
        $param = array("schoolId" => $schoolId, 'department' => $department);
        $soapResult = $this->_soapClient->loadGradeBySchools($param);
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            if (isset($result->data->gradeList)) {
                return $result->data->gradeList;
            };
        }
        return array();

    }

    /** 获取名校
     * @return array
     */
    public function getElite(){
        return $this->loadData(208);
    }
    //
    /**获取题型
     * @return array
     */
    public function itemType(){
        return $this->loadData(209);
    }

    /**
     * 获取题目出处
     * @return array
     */
    public function getFrom(){
        return $this->loadData(210);
    }

    /**
     * 获取题目难度
     * @return array
     */
    public function getItemNor(){
        return $this->loadData(211);
    }
    /**
     * 获取题目等级
     * @return array
     */
    public function getQuesLevel(){
        return $this->loadData(216);
    }
    /**
     * 获取题目掌握程度
     * @return array
     */
    public function getCapacity(){
        return $this->loadData(212);
    }

    /**
     * 获取学期
     * @return array
     */
    public function getTerm(){
        return $this->loadData(213);
    }

    /**
     * 资讯类型
     * @return array
     */
    public function  getNewsType(){
        return $this->loadData(501);
    }

    /**
     * 文件类型
     * @return array
     */
    public function  getFilesType(){
        return $this->loadData(502);
    }

    /**
     * @return array
     */
    public function getGrowupType(){
        return $this->loadData(223);
    }

    /**
     * 2.1.1.根据学段/学部查科目
     * 接口地址	http://主机地址:端口号/schoolService/BaseInfo?wsdl
     * 方法名	loadSubjectBydepartment
     * @param $department   学段/学部(20201 小学部, 20202 初中部,20203	高中部,该参数允许为空，为空时查询所有学段。如果要查询多个学段，学段间使用逗号分隔。例如查询小学部和初中部：,20201,20202
     * @param $notHasComp 不包含文综理综 0或空：包含文综理综  1：不包含文综理综

     * "data": {
    "list": [
    "{"subjectId":"10010","subjectName":"语文"},
    {"subjectId":"10011","subjectName":"数学"}
    ],
    "listSize":
    },
    "resCode": "000000",
    "resMsg": "成功"
     *
     * @return array
     */
    public function loadSubjectBydepartment($department,$notHasComp){
        $soapResult = $this->_soapClient->loadSubjectBydepartment(array("department" => $department,"notHasComp"=>$notHasComp));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data->list;
        }
        return array();
    }
    /*
    * 根据年级获取科目
    * gradeId	年级id
    * $notHasComp不包含文综理综 0或空：包含文综理综  1：不包含文综理综

    */
    public function loadSubjectByGrade($gradeId,$notHasComp){
        $soapResult = $this->_soapClient->loadSubjectByGrade(
            array(
                'gradeId' => $gradeId,
                'notHasComp'=>$notHasComp
            ));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 2.1.5.根据科目查询教材版本
     * 接口地址	http://主机地址:端口号/resource/BaseInfo?wsdl
     * 方法名	loadVersionBySub

     * @param $subjectID	版本id
     * @param $grade	    年级（年级和学部，至少需要传递一个）
     * @param $department	学部（年级和学部，至少需要传递一个）

     * @return ServiceJsonResult
     *
     * "data": {
    "list": [
    "{"key":"10010","value":"版本名称"},
    {"key":"10011","value":"版本名称"}
    ],
    "listSize":
    },
    "resCode": "000000",
    "resMsg": "成功"
    }
     */
    public function loadVersionBySub($subjectID = null, $grade = null, $department = null){
        $soapResult = $this->_soapClient->loadVersionBySub(
            array(
                'subjectID' => $subjectID,
	            'grade' => $grade,
	            'department' => $department
            ));
        $result= $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data->list;
        }
        return array();
    }

}