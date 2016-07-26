<?php
namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by 王
 * User: Administrator
 * Date: 14-9-11
 * Time: 下午1:50
 */
class pos_TeachingGroupService extends BaseService
{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/teachingGroup?wsdl");
    }

    /**
     * @param $schoolID
     * @param $groupName
     * @param $brief
     * @param $subjectID
     * @param $department
     * @param $creatorID
     * @param $bookVersionID
     * @param $token
     * @return ServiceJsonResult
     * 添加教研组信息
     */
    public function addTeachingGroup($schoolID, $groupName, $brief, $subjectID, $department, $creatorID, $bookVersionID, $token)
    {
        $soapResult = $this->_soapClient->addTeachingGroup(
            array(
                "schoolID" => $schoolID,
                "groupName" => $groupName,
                "brief" => $brief,
                "subjectID" => $subjectID,
                "department" => $department,
                "creatorID" => $creatorID,
                "bookVersionID" => $bookVersionID,
                "token" => $token,
            ));
        return $this->soapResultToJsonResult($soapResult);
    }

    /**
     *  高 2014.9.12
     * 2.4.2查询教研组信息
     * 接口地址    http://主机地址:端口号/ schoolService / teachingGroup?wsdl
     * 方法名    searchTeachingGroup
     * @param $schoolID     学校id（可以为空）
     * @param $groupName    教研组名（可以为空）
     * @param $subjectID    科目（可为空）
     * @param $department   学部（可为空）    20201（小学部）20202（初中部）20203    （高中部）
     * @param $bookVersionID    教材版本id(可为空)
     * @param $ispage       是否分页，0：不分页，1：分页（默认为0，不分页）
     * @param $currPage     当前显示页码，可以为空,默认值1
     * @param $pageSize     每页显示的条数，可以为空，默认值10
     * @param $token        安全保护措施
     * 返回的JSON示例：
     * {
     * "data": {
     * "currPage":"当前页码",
     * "totalPages":"总页数",
     * "countSize":"总记录数",
     * "pageSize":"每页数据的条数",
     * “teachingGroupList”: [{
     * “groupID”：“教研组id”，
     * “groupName”：“教研组名”，
     * “subjectID”，“”，
     * “subjectName”，“”，
     * “department”，“学部”，
     * “departmentName”，“”，
     * “bookVersionID”，“”，
     * “bookVersionName”，“”，
     * “schoolID”:””,
     * “schoolName”:””,
     * },
     * ]
     * }
     * "resCode": "000000",
     * "resMsg": "添加成功"
     * }
     * @return ServiceJsonResult
     */
    public function searchTeachingGroup($schoolID, $groupName, $subjectID, $department, $bookVersionID, $ispage, $currPage, $pageSize)
    {
        $soapResult = $this->_soapClient->searchTeachingGroup(array("schoolID" => $schoolID,
            'groupName' => $groupName, 'subjectID' => $subjectID, 'department' => $department, 'bookVersionID' => $bookVersionID,
            'ispage' => $ispage, 'currPage' => $currPage, 'pageSize' => $pageSize));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /**
     * 通过id搜索教研组
     * @param string $groupID
     * @return array
     */
    public function searchTeachingGroupById($groupID = '')
    {
        $soapResult = $this->_soapClient->searchTeachingGroupById(array("groupID" => $groupID));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /**
     * 高 2014.9.22
     * 2.4.10查询教研组成员
     *接口地址    http://主机地址:端口号/ schoolService / teachingGroup?wsdl
     *方法名    searchMemberFromGroup
     * @param $groupID  教研组id（groupID和userID中选择一个参数groupID不为空时根据教研组查询，userID不为空时根据用户所在教研组查询）
     * @param $userID   当前用户id（）
     * @param $token
     * “memberList”:[{
     * “serialID”: “成员序号id”
     * “userID”:”用户id”
     * “trueName”:”姓名”
     * “headImgUrl”:”个人头像”
     * “identity”：“教研组身份”
     * “identityName”：“教研组身份名称”
     * }]
     * @return ServiceJsonResult
     */
    public function searchMemberFromGroup($groupID, $userID)
    {
        $soapResult = $this->_soapClient->searchMemberFromGroup(array("groupID" => $groupID, 'userID' => $userID));
        return $this->soapResultToJsonResult($soapResult);
    }

    /**
     * @param $schoolID
     * @param $department
     * @param string $subjectID
     * @param $currPage
     * @param $pageSize
     * @param string $groupName
     * @param string $bookVersionID
     * @return ServiceJsonResult
     */
    public function searchTeachingGroupByPage($schoolID, $department, $subjectID = '', $currPage, $pageSize, $groupName = '', $bookVersionID = '')
    {
        return $this->searchTeachingGroup($schoolID, $groupName, $subjectID, $department, $bookVersionID, 1, $currPage, $pageSize);
    }

    /**
     * 修改教研组成员 modifyMemberOfGroup
     * groupID    所在教研组id
     * userID    待修改成员用户id
     * identity    教研组身份
     * token    安全保护措施
     * 失败    返回的JSONB：参考响应代码
     *
     * 成功    返回的JSON示例：
     * {
     * "data": {
     *
     * }
     * "resCode": "000000",
     * "resMsg": "添加成功"
     * }
     * @param $groupID
     * @param $userID
     * @param $identity
     * @return bool
     */
    public function  modifyMemberOfGroup($groupID, $userID, $identity)
    {
            $parameter = ["groupID" => $groupID,
                "userID" => $userID,
                "identity" => $identity
            ];
        $soapResult = $this->_soapClient->modifyMemberOfGroup($parameter);
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return true;
        }
        return false;


    }


}