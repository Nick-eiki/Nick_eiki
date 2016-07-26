<?php

namespace frontend\services\pos;

use ArrayObject;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;
use Yii;

/**
 * Created by PhpStorm.
 * User: unizk
 * Date: 14-9-9
 * Time: 下午12:58
 */
class pos_ClassInfoService extends BaseService
{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/classInfo?wsdl");
    }


    /**
     *  查询申请手拉手班级
     * @param string $classID
     * @param string $currPage
     * @param string $pageSize
     * @param string $select 0：所有1：发出的申请2：收到的申请
     * @return null
     */
    public function requestClassQuery($classID = '', $currPage = '', $pageSize = '', $select = '')
    {
        $soapResult = $this->_soapClient->requestClassQuery(array("classID" => $classID, 'currPage' => $currPage, 'pageSize' => $pageSize, 'select' => $select));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();

    }

    /**
     *  发送手拉手班级申请
     * @param string $requestID
     * @param string $acceptID
     * @param string $initiator
     * @return null
     */
    public function requestClassBinder($requestID = '', $acceptID = '', $initiator = '')
    {
        $soapResult = $this->_soapClient->requestClassBinder(array('requestID' => $requestID, 'acceptID' => $acceptID, 'initiator' => $initiator));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);

        return $this->mapperJsonResult($json);
    }

    /**
     * 查询没有手拉手的班级
     * @param string $schoolID
     * @param string $currPage
     * @param string $pageSize
     * @return null
     */
    public function queryNoBinderClass($provience = '', $city = '', $country = '', $department = '', $schoolName = '', $classID = '', $currPage = '', $pageSize = '', $userID = '')
    {
        $soapResult = $this->_soapClient->queryNoBinderClass(
            array('provience' => $provience,
                'city' => $city,
                'country' => $country,
                'department' => $department,
                "schoolName" => $schoolName,
                "classID" => $classID,
                'currPage' => $currPage,
                'pageSize' => $pageSize,
                'userID' => $userID)
        );
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();

    }

    /**
     * 查询互加手拉手的班级
     * @param string $classID
     * @return null
     */
    public function queryBinderClassByID($classID, $currPage = '', $pageSize = '')
    {
        $soapResult = $this->_soapClient->queryBinderClassByID(array("classID" => $classID, "currPage" => $currPage, "pageSize" => $pageSize));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();


    }

    /*
     * 解除手拉手
     * classID	操作教研组
     * ID	绑定请求ID
     *	userID	操作用户ID
     * reason	原因(可为空)
     */
    public function cancelClassBinder($ID, $userID, $classID, $reason = '')
    {
        $soapResult = $this->_soapClient->cancelClassBinder(
            array(
                "ID" => $ID,
                "userID" => $userID,
                "classID" => $classID,
                "reason" => $reason
            ));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        return $this->mapperJsonResult($json);
    }

    /**
     * 搜索班级信息
     * schoolID    学校id(可为空)
     * department    学部
     *      20201    小学部
     *      20202    初中部
     *      20203    高中部
     * joinYear    入学年份（可为空）
     * gradeID    所在年级（可为空）
     * classNumber    第几班（传入一个数字，比如：1 表示第一班）可为空
     * className    班级名称（可为空）
     * ispage    是否分页，0：不分页，1：分页（默认为0，不分页）
     * currPage    当前显示页码，可以为空,默认值1
     * pageSize    每页显示的条数，可以为空，默认值10
     * token    安全保护措施
     * @param string $schoolID
     * @param string $department
     * @param string $joinYear
     * @param string $gradeID
     * @param string $classNumber
     * @param string $className
     * @param string $ispage
     * @param string $currPage
     * @param string $pageSize
     * @return null
     * 返回的JSON示例：
     * {
     * "data": {
     * "currPage":"当前页码",
     * "totalPages":"总页数",
     * "countSize":"总记录数",
     * "pageSize":"每页数据的条数",
     *
     * “classList”: [{
     * “classID”:””,
     * “viewClass”:””,
     * “className”:””,
     * “schoolID”:””,
     * “schoolName”:””,
     * “joinYear”:””,
     * “classNumber”:””,
     * “gradeID”:””
     * “department”：“”
     * “departmentName”：””
     * },
     * ]
     * }
     * "resCode": "000000",
     * "resMsg": "查询成功"
     * }
     */
    public function searchClassInfo($schoolID = '', $department = '', $joinYear = '', $gradeID = '', $classNumber = '', $className = '', $ispage = '', $currPage = '', $pageSize = '')
    {
        $soapResult = $this->_soapClient->searchClassInfo(array('schoolID' => $schoolID, 'department' => $department, 'joinYear' => $joinYear, 'gradeID' => $gradeID, 'classNumber' => $classNumber, 'className' => $className, 'ispage' => $ispage, 'currPage' => $currPage, 'pageSize' => $pageSize));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();

    }

    /**
     * 查询班级信息（通过id）
     * @param $classID
     * @return array
     */
    public function searchClassInfoById($classID)
    {
        $soapResult = $this->_soapClient->searchClassInfoById(array('classID' => $classID));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();

    }

    /**
     * 分页搜索
     * @param $schoolID
     * @param string $department
     * @param string $gradeID
     * @param $currPage
     * @param $pageSize
     * @param string $joinYear
     * @param string $classNumber
     * @param string $className
     * @return null
     */
    public function searchClassInfoByPage($schoolID, $department = '', $gradeID = '', $currPage, $pageSize, $joinYear = '', $classNumber = '', $className = '')
    {

        return $this->searchClassInfo($schoolID, $department, $joinYear, $gradeID, $classNumber, $className, $isPage = '', $currPage, $pageSize);
    }


    /**
     * 删除班级
     * @param string $classID
     * @return ServiceJsonResult
     */
    public function deleteClassInfo($classID = '')
    {
        $soapResult = $this->_soapClient->requestClassBinder(array('classID' => $classID));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);

        return $this->mapperJsonResult($json);
    }

    /**
     * 添加班级
     * @param string $creatorID
     * @param string $schoolID
     * @param string $department
     * @param string $joinYear
     * @param string $gradeID
     * @param string $classNumber
     * @param string $className
     * @param string $gradeID
     * @return ServiceJsonResult
     */
    public function addClassInfo($creatorID = '', $schoolID = '', $department = '', $joinYear = '', $classNumber = '', $className = '', $gradeID = '')
    {
        $param = array('creatorID' => $creatorID,
            'schoolID' => $schoolID,
            'department' => $department,
            'joinYear' => $joinYear,
            'gradeID' => $gradeID,
            'classNumber' => $classNumber,
            'className' => $className);
        $soapResult = $this->_soapClient->addClassInfo(
            $param);
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);

        return $this->mapperJsonResult($json);
    }

    /**
     * 保存驳回或接受状态
     * @param string $ID 绑定id
     * @param string $askUserID 应答者id
     * @param string $reason 原因
     * @param string $msgCode 应答代码
     * @return ServiceJsonResult
     */
    public function saveClassResponse($ID = '', $askUserID = '', $reason = '', $msgCode = '')
    {
        $soapResult = $this->_soapClient->saveClassResponse(array('ID' => $ID, 'askUserID' => $askUserID, 'reason' => $reason, 'msgCode' => $msgCode));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);

        return $this->mapperJsonResult($json);
    }

    /**
     *  更新班级信息
     * @param string $classID
     * @param string $schoolID
     * @param string $department
     * @param string $joinYear
     * @param string $gradeID
     * @param string $classNumber
     * @param string $className
     * @return ServiceJsonResult
     */
    public function updateClassInfo($classID = '', $schoolID = '', $department = '', $joinYear = '', $gradeID = '', $classNumber = '', $className = '')
    {
        $soapResult = $this->_soapClient->requestClassBinder(array('classID' => $classID, 'schoolID' => $schoolID, 'department' => $department, 'joinYear' => $joinYear, 'gradeID' => $gradeID, 'classNumber' => $classNumber, 'className' => $className));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);

        return $this->mapperJsonResult($json);
    }

    /**
     * 3.7.28.查询学校的班级列表
     * @param string $schoolID 学校id
     * @param string $department 学部 20201 小学部，20202    初中部，20203 高中部
     * @param string $joinYear 入学年份（可为空）
     * @param string $gradeID 所在年级（可为空）
     * @param string $classNumber 第几班（传入一个数字，比如：1 表示第一班）可为空
     * @param string $className 班级名称（可为空）
     * @param string $currPage
     * @param string $pageSize
     * @param string $userID 当前用户ID
     * token    安全保护策略
     * 失败         返回的JSON：{
     * Data:{}
     * "resCode":"";//应答代码
     * "reMessage":""//
     * }
     * 参考响应代码对照表
     * 成功
     * 返回的JSON示例：
     * {
     * "data": {
     * "countSize": "17",
     * "pageSize": "10",
     *
     * "classList": [
     * {
     * "teacherMap": [
     * {
     * "teacherID": "10128",//授课老师的用户ID
     * "subjectNumber": "10011",//科目ID
     * "subjectName": "数学",//教授科目名称
     * "trueName": "老师1"//老师姓名
     * },
     * {
     * "teacherID": "10128",
     * "subjectNumber": "10010",
     * "subjectName": "语文",
     * "trueName": "老师1"
     * }
     * ],
     * "schoolName": "北京市第二中学",//班级所属学校名称
     * "classID": "1017",//班级ID
     * "boutiqueCourseNum": "0",//精品课程数量
     * "provience": "北京",//省
     * "city": "北京",//市
     * "country": "朝阳",//区
     * "classStuMember": "9",//班级学生数量
     * "draftCourseNum": "0",//班级讲义数量
     * "caseCourseNum": "0",//班级教案数据
     * "schoolID": "1014",//学校ID
     * "classChargeName": "老师1",//班主任名字
     * "classTeachMember": "1",//班级授课老师数量
     * "className": "14级2班"//班级名称
     * }
     * ……
     * ],
     * "currPage": "1",
     * "totalPages": "2"
     * },
     * "resCode": "000000",
     * "resMsg": "成功"
     * }
     */
    public function querySchoolClass($schoolID, $department, $joinYear, $gradeID, $classNumber, $className, $userID, $classID, $currPage, $pageSize)
    {
        $soapResult = $this->_soapClient->querySchoolClass(
            array(
                'schoolID' => $schoolID,
                'department' => $department,
                'joinYear' => $joinYear,
                'gradeID' => $gradeID,
                'classNumber' => $classNumber,
                'className' => $className,
                'userID' => $userID,
                'classID' => $classID,
                'currPage' => $currPage,
                'pageSize' => $pageSize,

            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /**
     * 3.8.30.修改班级logo
     * 接口地址    http://主机地址:端口号/ schoolService / classInfo?wsdl
     * 方法名    modifyClassLogo
     * @param $classID  班级id
     * @param $logoUrl  Logo图片地址
     * @return ServiceJsonResult
     * {
     * "data": {
     *
     * }
     * "resCode": "000000",
     * "resMsg": "删除成功"
     * }
     */
    public function modifyClassLogo($classID, $logoUrl)
    {
        $soapResult = $this->_soapClient->modifyClassLogo(array('classID' => $classID, 'logoUrl' => $logoUrl));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);

        return $this->mapperJsonResult($json);
    }

    /**
     * 3.8.31.查询班级logo
     * 接口地址    http://主机地址:端口号/ schoolService / classInfo?wsdl
     * 方法名    searchClassLogo
     * @param $classID  班级id
     * @return ServiceJsonResult
     * {
     * "data": {
     * “classID”：班级id
     * “logoUrl”：头像地址
     *
     * }
     * "resCode": "000000",
     * "resMsg": "删除成功"
     * }
     */
    public function searchClassLogo($classID)
    {
        $soapResult = $this->_soapClient->searchClassLogo(array('classID' => $classID));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);

        return $this->mapperJsonResult($json);
    }

    /**
     * 3.8.34.统计班级作业、考试、答疑个数
     * @param $classId
     * @return ServiceJsonResult
     * @throws CHttpException
     * @throws \Camcima\Exception\InvalidParameterException
     *
     *
     */

    public function queryClassThings($classId)
    {
        $soapResult = $this->_soapClient->queryClassThings(array('classID' => $classId));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);

        return $this->mapperJsonResult($json);
    }

    /**
     * 查询互加手拉手的班级
     * @param $userID
     * @return null
     */
    public function queryBinderClassByUserID($userID)
    {



        $cache = Yii::$app->cache;
        $key = "USER_BIND_CLASS_CACHE_BY_USER" . $userID;
        $data = $cache->get($key);
        if ($data === false) {
            $data = [];
            $soapResult = $this->_soapClient->queryBinderClassByUserID(array("userID" => $userID));
            $result = $this->soapResultToJsonResult($soapResult);
            if ($result->resCode == self::successCode) {
                if (isset($result->data) && isset($result->data->BinderClassList)) {
                    $data = $result->data->BinderClassList;
                };

            }
            $cache->set($key, $data, 600);

        }
        return $data;

    }

}