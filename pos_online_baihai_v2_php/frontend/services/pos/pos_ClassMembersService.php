<?php

namespace frontend\services\pos;
use ArrayObject;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: unizk
 * Date: 14-9-9
 * Time: 下午12:58
 */

/**
 * Class ClassMembersService
 */
class pos_ClassMembersService extends BaseService
{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/classMembers?wsdl");
    }


    /**
     * 加载班级中未注册的成员信息
     * 返回数据  { * “classMemID”:””;//班级用户表的id      * “classID”:””//班级id     * “uesrID”:””//”用户id     * “memName”:””//姓名    * “identity”:””//班内身份
     * “job”:””//班级职务
     * “stuID”:””//学号
     * }
     * @param string $classID
     * @return array
     */
    public function loadNoRegMembers($classID, $memName = '')
    {
        $param = array("classID" => $classID, 'memName' => $memName);
        $soapResult = $this->_soapClient->loadNoRegMembers($param);
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data->classMembers;
        }
        return array();

    }

    /**
     * 加载班级中已经注册的成员信息
     * queryType    查询类型，0查询教师，1查询学生
     * {
     * "data": {
     * "classMembersSize": 1,
     * "classMembers": [
     * {
     * "identity": "20401",//班级内部身份 20401：班主任 ；20403：学生 ； 20402：任课老师
     * "userID": "10127",//用户ID
     * "subjectNumber": "10010",//科目编码
     * "subjectName": "语文",//科目名称
     * "classID": "1017",//班级id
     * "classMemID": "9101142",//班级成员id（修改时使用该ID）
     * "job": null,//班内职务
     * "stuID": "",//学号
     * "memName": "王二狗"//在班级内的名称
     * },
     * {
     * "identity": "1",
     * "userID": "1014",
     * "subjectNumber": null,
     * "subjectName": null,
     * "classID": "1017",
     * "classMemID": "10150",
     * "job": null,
     * "stuID": "1016004",
     * "memName": "马辉煌"
     * }
     * ]
     * },
     * "resCode": "000000",
     * "resMsg": "成功"
     * }
     * @param string $classID
     * @return array
     */
    public function loadRegisteredMembers($classID = null, $queryType = null, $userID = null)
    {
        $soapResult = $this->_soapClient->loadRegisteredMembers(array("classID" => $classID, "queryType" => $queryType, "userID" => $userID));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data->classMembers;
        }
        return array();

    }


    /**
     * 修改老师的身份信息
     * @param string $classID
     * @param string $classMemID
     * @param string $identityCode
     * @param string $identity
     * @param string $subjectCode
     * @return ServiceJsonResult
     */
    public function saveTeachJob($classID = '', $classMemID = '', $identity = '', $subjectCode = null)
    {
        $soapResult = $this->_soapClient->saveTeachJob(array('classID' => $classID, 'classMemID' => $classMemID, 'identity' => $identity, 'subjectCode' => $subjectCode));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);

        return $this->mapperJsonResult($json);
    }


    /**
     * 修改班级成员中学生的身份信息
     * @param string $classID
     * @param string $classMemID
     * @param string $memName
     * @param string $stuID
     * @param string $jobCode
     * @return ServiceJsonResult
     */
    public function saveStudentMembers($classID = '', $classMemID = '', $memName = '', $stuID = '', $jobCode = '')
    {
        $soapResult = $this->_soapClient->saveStudentMembers(array('classID' => $classID, 'classMemID' => $classMemID, 'memName' => $memName, 'stuID' => $stuID, 'jobCode' => $jobCode));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);

        return $this->mapperJsonResult($json);
    }

    /**
     * 删除一个成员
     * @param string $classMemID
     * @return ServiceJsonResult
     */
    public function removeMember($classMemID)
    {
        $soapResult = $this->_soapClient->removeMemnber(array('classMemID' => $classMemID));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);

        return $this->mapperJsonResult($json);
    }

    /**
     * 批量添加学生信息 (没完成)
     * @param string $classID
     * @param string $stuDate
     * @return ServiceJsonResult
     */
    public function batchAddStudent($classID = '', $stuDate = '')
    {
        $soapResult = $this->_soapClient->batchAddStudent(array('classID' => $classID, 'stuDate' => $stuDate));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);

        return $this->mapperJsonResult($json);
    }

    /**
     * 2.4.8    根据班级ID查询班主任
     * @param $classID
     * @return ServiceJsonResult
     */
    public function loadClassCharge($classID)
    {
        $soapResult = $this->_soapClient->loadClassCharge(array('classID' => $classID));
        return $this->soapResultToJsonResult($soapResult);
    }

    /**
     *  3.8.33.    只修改老师的身份信息
     * classID    班级id
     * userID    用户id
     * identity    班内身份名称
     * 20401    班主任
     * 20402    任课老师
     *
     * @param $classId
     * @param $userId
     * @param $identity
     * @return bool
     */
    public function  modifyClassTeahcer($classId, $userId, $identity)
    {

        $parameter = ["classID" => $classId,
            "userID" => $userId,
            "identity" => $identity
        ];


        $soapResult = $this->_soapClient->modifyClaTeaIde($parameter);
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return true;
        }
        return false;


    }

    /**
     * 3.8.32.    查询班级人数
     *  {
     * "data": {
     * "teaNum":"2", 教师数
     * "stuNoRegNum":"5", 学生未注册数
     * "allStuNum":"7", 总学生数
     * "stuNum":"2" 学生已注册数
     *
     * }
     * "resCode": "000000",
     * "resMsg": "删除成功"
     * }
     * @param $classId
     * @return bool
     */
    public function classMemCount($classId)
    {


        $parameter = ["classID" => $classId
        ];

        $soapResult = $this->_soapClient->classMemCount($parameter);
        $result = $this->soapResultToJsonResult($soapResult);

        $ret = [
            "teacherNum" => 0,
            "stuNoRegNum" => 0,
            "allStudentNum" => 0,
            "studentNum" => 0

        ];

        if ($result->resCode == self::successCode) {

            $ret["teacherNum"]=$result->data->teaNum;
            $ret["stuNoRegNum"]=$result->data->stuNoRegNum;
            $ret["allStudentNum"]=$result->data->allStuNum;
            $ret["studentNum"]=$result->data->stuNum;

        }
       return $ret;

    }

    /**
     *  3.8.35.	加载班级中一个学生信息
     * queryOneMemInfo
     * @param $classMemID   成员id
     * @return 失败	返回的JSON：{
            Data:{}
            "resCode":"";//应答代码
            "reMessage":""//
            }
            参考响应代码对照表

            成功
            返回的JSON示例：
            {
            "resMsg": "成功",
            "data": {
            "classID": "101474",
            "subjectNumber": null,
            "stuID": "11111111111",
            "memName": "小李",
            "classMemID": "1012362",
            "identity": "20403",
            "job": "20101",
            "userID": "101513"
            },
            "resCode": "000000"
            }
     */
    public function  queryOneMemInfo($classMemID)
    {
        $parameter = ["classMemID" => $classMemID];
        $soapResult = $this->_soapClient->queryOneMemInfo($parameter);
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return true;
        }
        return false;
    }

}