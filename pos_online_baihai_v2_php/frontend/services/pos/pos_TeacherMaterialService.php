<?php

namespace frontend\services\pos;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-15
 * Time: 下午3:21
 */

/**
 * Class SchTeacherMaterialService
 */
class pos_TeacherMaterialService extends BaseService
{

    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['pos_webService'] . "schoolService/teacherMaterial?wsdl");
    }


    /**
     * 3.19.1.公文包和资料袋查询
     * 接口地址    http://主机地址:端口号/ schoolService / teacherMaterial?wsdl
     * 方法名    queryTeacherMaterial
     * @param $materialType         类型（1资料袋 2公文包）
     * @param $detailType             资料类型（1教案，2讲义，3视频）
     * @param $ID                   公文包和资料袋id
     * @param $Name                 名称
     * @param $teacherID            教师id
     * @param $stuLimit             权限（本班学生）
     * @param $groupMemberLimit     权限（教研组同事）
     * @param $departmentMemLimit   权限（部门同事）
     * @param $token                安全保护措施
     *
     * {"data":
     * {"teachingList":
     * [{"Name":"2",
     * "departmentMemLimit":"2",
     * "createTime":"2",
     * "data":"1",
     * "ID":"2",
     * "groupMemberLimit":"2",
     * "stuLimit":"2",
     * "material":"1",
     * "pptN":"0"}]}
     * ,"resCode":"000000"
     * ,"resMsg":"成功"}
     * @return ServiceJsonResult
     */
    public function queryTeacherMaterial($materialType, $detailType, $ID, $Name, $teacherID, $stuLimit, $groupMemberLimit, $departmentMemLimit, $currPage, $pageSize, $token)
    {
        $soapResult = $this->_soapClient->queryTeacherMaterial(array("materialType" => $materialType, "detailType" => $detailType, "ID" => $ID, "Name" => $Name,
            "teacherID" => $teacherID, "stuLimit" => $stuLimit, "groupMemberLimit" => $groupMemberLimit, "departmentMemLimit" => $departmentMemLimit, "currPage" => $currPage, "pageSize" => $pageSize, "token" => $token));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /**
     * 根据Id获取详情
     * @param $Id
     * @return ServiceJsonResult
     */
    public function getTeacherMaterial($Id)
    {

        $result = $this->queryTeacherMaterial('', '', $Id, '', '', '', '', '', '', '', '');
        if (empty($result->list)) {
            return null;
        };
        return $result->list[0];


    }


    /**
     * 3.19.2.讲义/公文包/资料袋添加
     * 接口地址    http://主机地址:端口号/ schoolService / teacherMaterial?wsdl
     * 方法名    createTeacherMaterial
     * @param $Name                 讲义/公文包/资料袋名称
     * @param $materialType         类型（1资料袋 2公文包）
     * @param $teacherID            教师id
     * @param $stuLimit             权限（本班学生）
     * @param $groupMemberLimit     权限（教研组同事）
     * @param $departmentMemLimit   权限（部门同事）
     * @param $token                安全保护措施
     * 返回的JSON示例：
     * {
     * {"data":{},"resCode":"000000","resMsg":"添加了第1011个讲义"
     * }"
     * }
     * @return ServiceJsonResult
     */
    public function createTeacherMaterial($Name, $materialType, $teacherID, $stuLimit, $groupMemberLimit, $departmentMemLimit, $token)
    {
        $soapResult = $this->_soapClient->createTeacherMaterial(array("Name" => $Name, "materialType" => $materialType, "teacherID" => $teacherID, "stuLimit" => $stuLimit, "groupMemberLimit" => $groupMemberLimit, "departmentMemLimit" => $departmentMemLimit, "token" => $token));
        return $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.19.3.编辑讲义/公文包/资料袋
     * 接口地址    http://主机地址:端口号/ schoolService /  teacherMaterial?wsdll
     * 方法名    updateTeacherMaterial
     * @param $ID                   讲义/公文包/资料袋id
     * @param $teacherID            教师id
     * @param $Name                 讲义/公文包/资料袋名称
     * @param $stuLimit             权限（本班学生）
     * @param $groupMemberLimit     权限（教研组同事）
     * @param $departmentMemLimit   权限（部门同事）
     * @param $token                安全保护措施
     * 返回的JSON示例：
     * {
     * "resCode":"000000",
     * "resMsg":"成功",
     * "data":{
     * }
     * }
     * @return ServiceJsonResult
     */
    public function updateTeacherMaterial($ID, $teacherID, $Name, $stuLimit, $groupMemberLimit, $departmentMemLimit, $materialType, $token)
    {
        $soapResult = $this->_soapClient->updateTeacherMaterial(array("ID" => $ID, "teacherID" => $teacherID, "Name" => $Name, "stuLimit" => $stuLimit, "groupMemberLimit" => $groupMemberLimit, "departmentMemLimit" => $departmentMemLimit, "materialType" => $materialType, "token" => $token));
        return $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 3.19.4.删除讲义/公文包/资料袋
     * 接口地址    http://主机地址:端口号/ schoolService / teacherMaterial?wsdl
     * 方法名    delTeacherMaterial
     * @param $ID       公文包和资料袋id
     * @param $token    安全控制
     *
     * 返回的JSON示例：
     * {
     * "resCode":"000000",
     * "resMsg":"成功",
     * "data":{
     * }
     * @return ServiceJsonResult
     */
    public function delTeacherMaterial($ID, $token)
    {
        $soapResult = $this->_soapClient->delTeacherMaterial(array("ID" => $ID, "token" => $token));
        return $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 查询资料袋内容列表
     * @param $packId       资料袋或公文包id
     * @param $id           序号id可为空
     * @param $infoId       资料内容id,可为空
     * @param $detailType   资料类型（可为空）
     * @param $currPage
     * @param $pageSize
     * @param $token         安全保护措施
     * {
     * "resCode":"000000",
     * "resMsg":"查询成功",
     * "data":{
     * "currPage":"当前页码",
     * "totalPages":"总页数",
     * "countSize":"总记录数",
     * "pageSize":"每页数据的条数",
     * "list":[//列表  {
     * "ID ":"",//序号
     * "packId ":"",//资料袋id
     * "infoId ":"",//资料内容id
     * " detailType ":""//资料类型（1教案，2讲义，3视频）
     * "url ":"",//资料url
     * "name ":"",//资料名称
     * "brief ":""//资料简介
     * }]
     * }
     * @return array
     */
    public function queryTeacherMaterialDetail($packId, $id, $infoId, $detailType, $currPage, $pageSize, $token)
    {
        $soapResult = $this->_soapClient->queryTeacherMaterialDetail(array("packId" => $packId, "id" => $id, "infoId" => $infoId, "detailType" => $detailType, "currPage" => $currPage, "pageSize" => $pageSize, "token" => $token));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /**
     * 3.19.2.其他用户查询教师资料袋
     * 接口地址    http://主机地址:端口号/ schoolService / teacherMaterial?wsdl
     *  方法名    otherQueryTeacherMaterial
     * @param $ownerTeacherID   资料袋所属 教师id
     * @param $materialType     类型（1资料袋 2公文包）
     * @param $detailType       资料类型（1教案，2讲义，3视频 4 资料，5 ppt，6 素材）
     * @param $ID               公文包和资料袋id
     * @param $Name             公文包和资料袋名称
     * @param $otherUserID      其他查看用户id
     * @param $currPage         当前显示页码，可以为空,默认值1
     * @param $pageSize         每页显示的条数，可以为空，默认值10
     * "data":{
     * "currPage":"当前页码",
     * "totalPages":"总页数",
     * "countSize":"总记录数",
     * "pageSize":"每页数据的条数",
     * "list":[//列表
     * { "ID ":"",公文包和资料袋id
     * "materialType":"",类型（1资料袋 2公文包）
     * "teacherID ":"",//教师id
     * "teacherName":"",//教师名称
     * "Name ":"",//公文包和资料袋名称
     * "stuLimit ":"",//权限（本班学生）0：不可见1：可见
     * "groupMemberLimit ":"",//权限（教研组同事）0：不可见1：可见
     * "departmentMemLimit ":"",//权限（部门同事）0：不可见1：可见
     * "createTime ":"",//创建时间
     * "detail ":[{
     * "ID ":"",//序号
     * "informationPackID ":"",//资料袋id
     * "infoId ":"",//资料内容id
     * " detailType ":""//资料类型（1教案，2讲义，3视频）
     * "url ":"",//资料url
     * "name ":"",//资料名称
     * "brief ":""//资料简介
     * "uploadTime":""//上传时间
     * }
     * ]
     * "cntLst":[//资料类型统计列表
     * {"detailType":"1",//类型
     * "typeName":"1",//类型
     * "cnt":1//个数
     * }
     * ]
     * },
     * ...
     * ]}
     * @return array
     */
    public function otherQueryTeacherMaterial($ownerTeacherID, $materialType, $detailType, $ID, $Name, $otherUserID, $currPage, $pageSize)
    {
        $soapResult = $this->_soapClient->otherQueryTeacherMaterial(array("ownerTeacherID" => $ownerTeacherID, "materialType" => $materialType, "detailType" => $detailType,
            "ID" => $ID, "Name" => $Name, "otherUserID" => $otherUserID, "currPage" => $currPage, "pageSize" => $pageSize));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }


    /**
     * 3.19.3.其他用户查询资料
     * 接口地址    http://主机地址:端口号/ schoolService / teacherMaterial?wsdl
     * 方法名    otherQueryTeaMatInfo
     * @param $ownerTeacherID   资料袋所属 教师id
     * @param $detailType       资料类型（1教案，2讲义，3视频 4 资料，5 ppt，6 素材）
     * @param $otherUserID      其他查看用户id
     * @param $currPage         当前显示页码，可以为空,默认值1
     * @param $pageSize         每页显示的条数，可以为空，默认值10
     * "resCode":"000000",
     * "resMsg":"查询成功",
     * "data":{
     * "currPage":"当前页码",
     * "totalPages":"总页数",
     * "countSize":"总记录数",
     * "pageSize":"每页数据的条数",
     * "list":[//列表
     * {
     * "ID ":"",//序号
     * "informationPackID ":"",//资料袋id
     * "infoId ":"",//资料内容id
     * "detailType ":""//资料类型（1教案，2讲义，3视频）
     * "url ":"",//资料url
     * "name ":"",//资料名称
     * "brief ":""//资料简介
     * "uploadTime":""//上传时间
     * }
     * ]
     * }
     * @return array
     */
    public function otherQueryTeaMatInfo($ownerTeacherID, $detailType, $otherUserID, $currPage, $pageSize)
    {
        $soapResult = $this->_soapClient->otherQueryTeaMatInfo(array("ownerTeacherID" => $ownerTeacherID, "detailType" => $detailType, "otherUserID" => $otherUserID, "currPage" => $currPage, "pageSize" => $pageSize));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /**
     * 3.19.4.查询班级可见资料
     * 接口地址    http://主机地址:端口号/ schoolService / teacherMaterial?wsdl
     * 方法名    queryClassMatInfo
     * @param $calssID      班级id
     * @param $detailType   资料类型（1教案，2讲义，3视频 4 资料，5 ppt，6 素材）
     * @param $queryUserID  查询用户id
     * @param $currPage     当前显示页码，可以为空,默认值1
     * @param $pageSize     每页显示的条数，可以为空，默认值10
     * {
     * "resCode":"000000",
     * "resMsg":"查询成功",
     * "data":{
     * "currPage":"当前页码",
     * "totalPages":"总页数",
     * "countSize":"总记录数",
     * "pageSize":"每页数据的条数",
     * "list":[//列表
     * {
     * "ID ":"",//序号
     * "informationPackID ":"",//资料袋id
     * "infoId ":"",//资料内容id
     * "detailType ":""//资料类型（1教案，2讲义，3视频）
     * "url ":"",//资料url
     * "name ":"",//资料名称
     * "brief ":""//资料简介
     * "uploadTime":""//上传时间
     * } ] }}
     * @return array
     */
    public function queryClassMatInfo($calssID, $detailType, $queryUserID, $currPage, $pageSize)
    {
        $soapResult = $this->_soapClient->queryClassMatInfo(array("calssID" => $calssID, "detailType" => $detailType, "queryUserID" => $queryUserID, "currPage" => $currPage, "pageSize" => $pageSize));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

}
