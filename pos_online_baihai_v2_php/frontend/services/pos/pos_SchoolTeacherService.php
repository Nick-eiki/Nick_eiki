<?php
namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-9-12
 * Time: 下午6:12
 */
class pos_SchoolTeacherService extends BaseService
{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/schoolteacher?wsdl");
    }

    /**
     * 高 2014.9.12
     * 4.3.1.查询教师列表
     * 查询条件都可为空
     * 接口地址    http://主机地址:端口号/ schoolService / schoolteacher?wsdl
     * 方法名    searchTeacherList
     * @param $schoolID         学校id
     * @param $department       学部id
     * @param $grade            年级
     * @param $classNumber      班号
     * @param $subjectID        科目id
     * @param $teacherName      教师名
     * @param $teachingGroup    教研组
     * @param $currPage         当前显示页码，可以为空,默认值1
     * @param $pageSize         每页显示的条数，可以为空，默认值10
     * @param $token            安全保护措施
     *
     * {
     * "data": {
     * "currPage":"当前页码",
     * "totalPages":"总页数",
     * "countSize":"总记录数",
     * "pageSize":"每页数据的条数",
     *
     * “teacherList”：[//教师列表
     * {
     * “teacherID”：“”，
     * “teacherName”：“”,
     * “classIdenInfo”:[{
     * “classID”：“”
     * “className”：“”
     * “viewClass”：“类似于：101，有年级和班号组成”
     * “classIdentityID”：“班级职务”
     * “classIdentityName”:”班级职务名”
     * “subjectID”：“所受科目”
     * “subjectName”：“科目名称”
     * }]
     * “groupID”：“教研组id”
     * “groupName”：“教研组名”
     * “groupIdentityID”：“教研组职务id”
     * “groupIdentityName”:”教研组职务名称”
     * “schoolidenID”:”学校职务id”
     * “schoolidenName”:”学校职务名称”
     * }]},
     * "resCode": "000000",
     *
     * "resMsg": "成功"
     * @return ServiceJsonResult
     */
    public function searchTeacherList($schoolID, $department, $grade, $classNumber, $subjectID, $teacherName, $teachingGroup, $currPage, $pageSize)
    {
        $soapResult = $this->_soapClient->searchTeacherList(array("schoolID" => $schoolID, 'department' => $department,
            'grade' => $grade, 'classNumber' => $classNumber, 'subjectID' => $subjectID, 'teacherName' => $teacherName, 'teachingGroup' => $teachingGroup,
            'currPage' => $currPage, 'pageSize' => $pageSize));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();

    }

    /**
     * 高 2014.9.22
     * 3.14.3.修改教师学校身份
     * 接口地址    http://主机地址:端口号/ schoolService / schoolteacher?wsdl
     *  方法名    updateTeaSchIden
     * @param $teacherID        教师id
     * @param $schooliden       部门职务
     * @param $token            安全保护措施
     * "data": {
     * “teacherID”:””
     * },
     * "resCode": "000000",
     * "resMsg": "成功"
     * }
     * @return ServiceJsonResult
     */
    public function updateTeaSchIden($teacherID, $schooliden)
    {
        $soapResult = $this->_soapClient->updateTeaSchIden(array('teacherID' => $teacherID, 'schooliden' => $schooliden));
         return  $this->soapResultToJsonResult($soapResult);
    }



    /**
     * 查询教师班级
     * /schoolService/schoolteacher?wsdl下的
     * 方法名    searchTeacherClass
     * @param $teacherID    教师id
     * @param $token        安全保护措施
     *
     *  "data": {
     * "classListSize": 3,
     * "classList": [
     * {
     * "joinYear": "2010",入学年级
     * "classNumber": "1",班级号
     * "department": "20203",学部
     * "classID": "100400",班级id
     * "className": "aaa",班级名
     * "departmentName": "高中部"学部名
     * },
     * {
     * "joinYear": "2014",
     * "classNumber": "1班",
     * "department": "20203",
     * "classID": "100321",
     * "className": "大时代",
     * "departmentName": "高中部"
     * },
     * {
     * "joinYear": "2014",
     * "classNumber": "1",
     * "department": "20203",
     * "classID": "100311",
     * "className": "IT班",
     * "departmentName": "高中部"
     * }
     * ]
     * },
     * "resCode": "000000",
     * "resMsg": "成功"
     * }
     * @return ServiceJsonResult
     */
    public function searchTeacherClass($teacherID)
    {
        $soapResult = $this->_soapClient->searchTeacherClass(array('teacherID' => $teacherID));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }


    /**
     * 3.14.5.根据学校ID查询学校所有教师
     * 接口地址    http://主机地址:端口号/ schoolService / schoolteacher?wsdl
     * 方法名    queryAllTeacherBySchoolID
     * @param $schoolID     学校ID
     * @param $currPage     当前显示页码，可以为空,默认值1
     * @param $pageSize     每页显示的条数，可以为空，默认值10
     * @param $token        安全保护措施
     * "data": {
     * "currPage":"当前页码",
     * "totalPages":"总页数",
     * "countSize":"总记录数",
     * "pageSize":"每页数据的条数",
     *
     * “teacherList”:””[//班级列表
     * {
     * “teacherID”:””教师ID
     * “teacherName”:””教师名称
     * “headImgUrl”:””教师头像
     *
     * }
     *
     * @return stdClass
     */
    public function queryAllTeacherBySchoolID($schoolID, $currPage, $pageSize)
    {
        $soapResult = $this->_soapClient->queryAllTeacherBySchoolID(array('schoolID' => $schoolID, 'currPage' => $currPage, 'pageSize' => $pageSize));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);

        $std = new stdClass();
        $std->currPage = 0;
        $std->totalPages = 0;
        $std->countSize = 0;
        $std->pageSize = 0;
        $std->teacherList = array();

        $result = $this->mapperJsonResult($json);
        if ($result->resCode == self::successCode) {
            $std->currPage = $result->data->currPage;
            $std->totalPages = $result->data->totalPages;
            $std->countSize = $result->data->countSize;
            $std->pageSize = $result->data->pageSize;
            $std->teacherList = $result->data->teacherList;
        }
        return $std;
    }

    /**
     * 3.14.6.教师查询所在学校ID
     * 接口地址    http://主机地址:端口号/ schoolService / schoolteacher?wsdl
     * 方法名    teacherGetSchoolID
     * @param $teacherID    教师ID
     * @param $token        安全保护措施
     * "data": {
     * “schoolID”：“”教师所在学校ID
     * }
     * ]
     * },
     * "resCode": "000000",
     * "resMsg": "成功"
     * @return ServiceJsonResult
     */
    public function teacherGetSchoolID($teacherID)
    {
        $soapResult = $this->_soapClient->teacherGetSchoolID(array('teacherID' => $teacherID));
         $result=  $this->soapResultToJsonResult($soapResult);
        if  ($result->resCode==self::successCode){
            return $result->data->schoolID;
        }
        return "";
    }

    /**
     * 3.15.7.查询教师班级信息
     * 接口地址	http://主机地址:端口号/ schoolService / schoolteacher?wsdl
     * 方法名	teacherClass
     * @param $teacherID    教师id
     * @param $classID      班级id（可为空）
     * @return string
     * "data": {
    "classList":[
    {
    "classID":""班级id
    "className":班级名称
    "joinYear":"班级入学年份"
    "classNumber"班级号
    "department":"班级学段"
    "departmentName":学段名称
    "identity": "20401",//班级内部身份 20401：班主任;20402：任课老师;20403：学生
    “gradeID”年级id
    "subList": [//所受科目列表
    {
    "ID": 1011421,
    "subjectID": "10010",科目id
    "subjectName": "语文"科目名称
    }
    ],
    }
    ]
    },
    "resCode": "000000",
    "resMsg": "成功"
    }
     */
    public function teacherClass($teacherID,$classID){
        $soapResult = $this->_soapClient->teacherClass(array('teacherID' => $teacherID,'classID'=>$classID));
        $result=  $this->soapResultToJsonResult($soapResult);
        if  ($result->resCode==self::successCode){
            return $result->data;
        }
        return array();
    }
}