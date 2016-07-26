<?php

namespace frontend\services\apollo;
use frontend\services\BaseService;
use frontend\services\ShanHaiSoapClient;

/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-10-31
 * Time: 下午5:19
 */
class Apollo_MaterialService extends BaseService
{
    function __construct()
    {
        $this->_soapClient = new ShanHaiSoapClient(app()->params['apollo_webService'] . "apollo/resource/material?wsdl");
    }

    /**
     * 2.3.1.上传资料
     * 接口地址    http://主机地址:端口号/resource/material?wsdl
     * 方法名    uploadMaterial
     * @param $model (name名称,matType资料类型(1教案,2讲义),provience省,city市,countr区县,gradeid适用年级关联年级信息表的年级ID,subjectid    科目关联科目信息表的科目ID,versionid    版本
     * 管理版本信息表的版本ID,contentType    资料内容类型(1知识点 2课本章节),kid    知识点关联知识点表的知识点ID。多选，知识点之间使用逗号分隔,chapterId    章节id,多个用逗号隔开,school    名(20801    北京四中
     * 20802    人大附中 20804    黄冈中学),otherSchool    其他名校,tags    自定义标签。标签之间使用逗号分隔,url附件地址,describe描述,creator录入人,matPackId    资料袋id)
     * 返回的JSON：
     * {"data":{
     * "materialId":""
     * },
     * "resCode":"000001",
     * "resMsg":"录入成功"
     * }
     * @return ServiceJsonResult
     */
    public function uploadMaterial($model, $userId, $matPackId)
    {
        $arr = array(
            'name' => $model->name,
            'matType' => $model->type,
            'provience' => $model->provience,
            'city' => $model->city,
            'country' => $model->county,
            'gradeid' => $model->grade,
            'subjectid' => $model->subjectID,
            'versionid' => $model->materials,
            'contentType' => $model->contentType,
            'chapKids' => $model->chapKids,
            'school' => $model->school,
            'otherSchool' => $model->otherSchool,
            'tags' => $model->tags,
            'url' => $model->url,
            'describe' => $model->brief,
            'creator' => $userId,
            'matPackId' => $matPackId,
            'token' => '',
        );
        $soapResult = $this->_soapClient->uploadMaterial($arr);
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);

        return $this->mapperJsonResult($json);
    }

    /**
     *2.3.2.资料查询
     * 接口地址    http://主机地址:端口号/ resource/material?wsdl
     *方法名    queryMaterial
     * @param $id           资料id
     * @param $name         名称
     * @param $matType      资料类型(1教案,2讲义)
     * @param $provience    省
     * @param $city         市
     * @param $country      区县
     * @param $gradeid      适用年级 关联年级信息表的年级ID
     * @param $subjectid    科目  关联科目信息表的科目ID
     * @param $versionid    版本 管理版本信息表的版本ID
     * @param $contentType  资料内容类型(1知识点 2课本章节)
     * @param $kid          知识点 关联知识点表的知识点ID。多选，知识点之间使用逗号分隔
     * @param $chapterId    章节id,多个用逗号隔开
     * @param $school       名校 20801    北京四中 20802    人大附中
     * @param $otherSchool  其他名校
     * @param $tags         tags    自定义标签。标签之间使用逗号分隔
     * @param $describe     描述
     * @param $creator      录入人
	 * @param $collectUserId	资料收藏人 根据收藏人，查询该资料是否收藏 isCollected 0未收藏 1收藏
     * @param $currPage     当前显示页码，可以为空,默认值1
     * @param $pageSize     每页显示的条数，可以为空，默认值10
     * "data": {
     * "currPage":"当前页码",
     * "totalPages":"总页数",
     * "countSize":"总记录数",
     * "pageSize":"每页数据的条数",
     * "list": [
     * {"id":2001,
     * "name":"",
     * "matType":"1",//资料类型(1教案,2讲义)
     * "provience":"",
     * "city":"",
     * "country":"",
     * "gradeid":"",
     * "gradename":"",
     * "subjectid":"",
     * "subjectname":"",
     * "versionid":"",
     * "versionname":"",
     * "contentType":"",//资料内容类型(1知识点 2课本章节)
     * "kid":"",
     * "chapterId":"",
     * "matDescribe":"",
     * "url":"",
     * "school":"20807",
     * "schoolName":"华师附中",
     * "tags":"",
     * "creator":"",
     * "createTime":"2014-10-31 04:33:26",
     * "updateTime":"" }
     * ],
     *
     * },
     * "resCode": "000000",
     * "resMsg": "成功"
     * @return array
     */
    public function queryMaterial($id,
                                  $name="",
                                  $matType="",
                                  $provience="",
                                  $city="",
                                  $country="",
                                  $gradeid="",
                                  $subjectid="",
                                  $versionid="",
                                  $contentType="",
                                  $chapKids="",
                                  $school="",
                                  $tags="",
                                  $describe="",
                                  $creator="",
                                  $collectUserId="",
                                  $access="",
                                  $department="",
                                  $isplatform="",
                                  $timeOrder="",
                                  $hotOrder="",
                                  $currPage="",
                                  $pageSize="")
    {

        $soapResult = $this->_soapClient->queryMaterial(
            array(
                "id" => $id,
                "name" => $name,
                "matType" => $matType,
                "provience" => $provience,
                "city" => $city,
                "country" => $country,
                "gradeid" => $gradeid,
                "subjectid" => $subjectid,
                "versionid" => $versionid,
                "contentType" => $contentType,
                "chapKids" => $chapKids,
                "school" => $school,
                "tags" => $tags,
                "describe" => $describe,
                "creator" => $creator,
                "collectUserId" => $collectUserId,
                "access"=>$access,
                "department"=>$department,
                "isplatform"=>$isplatform,
                "timeOrder"=>$timeOrder,
                "hotOrder"=>$hotOrder,
                "currPage" => $currPage,
                "pageSize" => $pageSize
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /**
     * 通过科目查询
     * @param $gradeid
     * @param $subjectid
     */
    public function getMaterialBySubjectId($arr, $currPage, $pageSize)
    {

        return $this->queryMaterial('', '', 2, '', '', '', $arr->gradeid, $arr->subjectid, '', '', '', '', '', '', '', '','','','','','', $currPage, $pageSize);
    }

    /**
     *2.4.12.查询班级资料
     * 接口地址    http://主机地址:端口号/ resource/material?wsdl
     *方法名    queryClassGroupMaterial
     * @param $id           资料id
     * @param $name         名称
     * @param $matType      资料类型(1教案,2讲义)
     * @param $department   学段
     * @param $gradeid      适用年级 关联年级信息表的年级ID
     * @param $subjectid    科目  关联科目信息表的科目ID
     * @param $versionid    版本 管理版本信息表的版本ID
     * @param $contentType  资料内容类型(1知识点 2课本章节)
     * @param $chapKids     章节id,多个用逗号隔开
     * @param $creator      录入人
     * @param $classID	    班级
     * @param $currPage     当前显示页码，可以为空,默认值1
     * @param $pageSize     每页显示的条数，可以为空，默认值10
     * "data":返回的JSON示例：
    {
    "data": {
        "allReadNum": 0,//阅读总数
        "currPage":"当前页码",
        "totalPages":"总页数",
        "countSize":"总记录数",
        "pageSize":"每页数据的条数",
        "list": [
        {"id":2001,//id
        "name":"",//名称
        "matType":"1",//资料类型(1教案,2讲义)
        "provience":"",//省
        "city":"",//市
        "country":"",//县
        "gradeid":"",//年级id
        "gradename":"",//年级名称
        "subjectid":"",//科目id
        "subjectname":"",//科目名称
        "versionid":"",//版本id
        "versionname":"",//版本名称
        "contentType":"",//资料内容类型(1知识点 2课本章节)
        " chapKids ":"",//知识点章节id,多个用逗号隔开
        "matDescribe":"",//简介
        "url":"",//url
        "school":"20807",
        "schoolName":"华师附中",
        "tags":"",//标签
        "creator":"",//创建人
        "createTime":"2014-10-31 04:33:26",//上传时间
        "updateTime":"" //更新时间
        “readNum”阅读次数
        “downNum”下载次数
        “isCollected“:"" 0未收藏 1收藏
        “collectID“:收藏id
        "collectCnt": 0,//收藏次数
        "shareClass": [//分享班
        {
        "classID": 101474,
        "className": "高考冲刺1班"
        },
        {
        "classID": 101511,
        "className": "13级1班"
        }
        ],
        "shareGroup": [//分享教研组
        {
        "groupID": 1015,
        "groupName": "1组"
        }
        ]
        }
        ],

        },
        "resCode": "000000",
        "resMsg": "成功"
        }
     */
    public function queryClassGroupMaterial($id, $name, $matType, $department,  $gradeid, $subjectid, $versionid, $contentType, $chapKids,$creator, $classID, $userID,$groupID,$currPage, $pageSize)
    {

        $soapResult = $this->_soapClient->queryClassGroupMaterial(
            array(
                "id" => $id,
                "name" => $name,
                "matType" => $matType,
                "department"=>$department,
                "gradeid" => $gradeid,
                "subjectid" => $subjectid,
                "versionid" => $versionid,
                "contentType" => $contentType,
                "chapKids" => $chapKids,
                "creator" => $creator,
                "classID"=>$classID,
                "userID"=>$userID,
                "groupID" =>$groupID,
                "currPage" => $currPage,
                "pageSize" => $pageSize
            ));
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {

            return $result->data;
        }
        return array();
    }

    /**
     * 通过科目/类型/年级查询
     * @param $gradeid
     * @param $subjectid
     * @param $matType
     */
    public function getMaterialBySubGraType($matType,$gradeid,$subjectid,$classID,$userID, $currPage, $pageSize)
    {
        return $this->queryClassGroupMaterial('', '', $matType, '',  $gradeid, $subjectid, '', '', '','', $classID,$userID,'', $currPage, $pageSize);
    }

    /**
     * 通过文件id查询文件详情
     * @param $gradeid
     * @param $subjectid
     * @param $matType
     */
    public function getClassMaterialById($id,$userID)
    {
        return $this->queryClassGroupMaterial($id, '', '', '',  '', '', '', '', '','', '', $userID,'','', '');
    }


    /**
     * 查询平台资料库
     * @param $name          名称
     * @param $matType      资料类型(1教案,2讲义)
     * @param $provience    省
     * @param $city         市
     * @param $country      区
     * @param $gradeid      年级
     * @param $subjectid    科目
     * @param $currPage
     * @param $pageSize
     * @return array
     */
    public function searchMaterial($name, $matType, $provience, $city, $country, $gradeid, $subjectid, $collectUserId, $currPage, $pageSize)
    {
        return $this->queryMaterial('', $name, $matType, $provience, $city, $country, $gradeid, $subjectid, '', '', '', '', '', '', '', $collectUserId, '','','','','',$currPage, $pageSize);
    }


    /**
     * 新备课使用映射 queryMaterial 方法
     * @param $name
     * @param $matType
     * @param $provience
     * @param $city
     * @param $country
     * @param $gradeid
     * @param $subjectid
     * @param $collectUserId
     * @param $access
     * @param $department
     * @param $currPage
     * @param $pageSize
     * @return array
     */
    public function getSearchMaterial($name, $matType, $gradeid,$subjectid,$versionid, $creator,$collectUserId, $access,$department,$isplatform,$timeOrder,$hotOrder,$currPage, $pageSize){
        return $this->queryMaterial('', $name, $matType, '', '', '', $gradeid, $subjectid, $versionid, '', '', '', '', '', $creator, $collectUserId, $access,$department,$isplatform,$timeOrder,$hotOrder,$currPage, $pageSize);

    }


    /**
     * 根据类型查询资料
     * @param $matType      资料类型 1教案,2讲义,4 资料,5 ppt,6 素材 查询多种类型用逗号隔开 4,5,6
     * @param $creator      录入人
     * @param $currPage     当前显示页码，可以为空,默认值1
     * @param $pageSize     每页显示的条数，可以为空，默认值10
     * @return array
     */
    public function getMaterialTypeById($matType, $creator, $collectUserId, $currPage, $pageSize)
    {
        return $this->queryMaterial('', '', $matType, '', '', '', '', '', '', '', '', '', '', '', $creator, $collectUserId,'','','','','', $currPage, $pageSize);
    }

    /**
     * 根据id 获取详情
     * @param $id
     * @return array
     */
    public function getMaterialById($id, $userId,$collectUserId)
    {
        $result= $this->queryMaterial($id, '', '', '', '', '', '', '', '', '', '', '', '', '', $userId, $collectUserId,'','','', '', '','','');
         if(empty($result->list)){
             return null;
         };
        return $result->list[0];
    }

    /**
     *  通过模型查询
     * @param $m Apollo_MaterialSearchModel
     * @return array
     */
    public function getMaterialBymodel($m, $currPage, $pageSize)
    {

        return $this->queryMaterial($m->id, $m->name, $m->matType, $m->provience, $m->city, $m->country, $m->gradeid, $m->subjectid, $m->versionid, $m->contentType, $m->chapKids, $m->school, $m->tags, $m->describe, $m->creator, $m->collectUserId,'','','','','', $currPage, $pageSize);
    }


    /**
     * 2.3.3.增加阅读次数
     * 接口地址    http://主机地址:端口号/material /material?wsdl
     * 方法名    increaseReadNum
     * @param $id       资料ID
     * @param $token    用于安全控制
     * "data": {
     * “readNum”阅读次数
     * },
     * "resCode": "000000",
     * "resMsg": "成功"
     * @return ServiceJsonResult
     */
    public function  increaseReadNum($id, $token)
    {
        $soapResult = $this->_soapClient->increaseReadNum(array('id' => $id, 'token' => $token));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 2.3.4.增加下载次数
     * 接口地址    http://主机地址:端口号/material /material?wsdl
     * 方法名    increaseDownNum
     * @param $id       资料ID
     * @param $token    用于安全控制
     * "data": {
     * “downNum”下载次数
     * },
     * "resCode": "000000",
     * "resMsg": "成功"
     * @return ServiceJsonResult
     */
    public function increaseDownNum($id, $token)
    {
        $soapResult = $this->_soapClient->increaseDownNum(array('id' => $id, 'token' => $token));
         return  $this->soapResultToJsonResult($soapResult);
    }

    /**
     * 2.4.3.教研组上传资料
     * 接口地址    http://主机地址:端口号/resource/material?wsdl
     * 方法名    groupUploadMaterial
     * @param $model (name名称,matType资料类型(1教案,2讲义),provience省,city市,countr区县,gradeid适用年级关联年级信息表的年级ID,subjectid    科目关联科目信息表的科目ID,versionid    版本
     * 管理版本信息表的版本ID,contentType    资料内容类型(1知识点 2课本章节),kid    知识点关联知识点表的知识点ID。多选，知识点之间使用逗号分隔,chapterId    章节id,多个用逗号隔开,school    名(20801    北京四中
     * 20802    人大附中 20804    黄冈中学),otherSchool    其他名校,tags    自定义标签。标签之间使用逗号分隔,url附件地址,describe描述,creator录入人,matPackId    资料袋id 教研组id)
     * 返回的JSON：
     * {"data":{
     * "materialId":""
     * },
     * "resCode":"000001",
     * "resMsg":"录入成功"
     * }
     * @return ServiceJsonResult
     */
    public function groupUploadMaterial($model, $userId, $matPackId, $groupId)
    {
        $arr = array(
            'name' => $model->name,
            'matType' => $model->type,
            'provience' => $model->provience,
            'city' => $model->city,
            'country' => $model->county,
            'gradeid' => $model->grade,
            'subjectid' => $model->subjectID,
            'versionid' => $model->materials,
            'contentType' => $model->contentType,
            'chapKids' => $model->chapKids,
            'school' => $model->school,
            'otherSchool' => $model->otherSchool,
            'tags' => $model->tags,
            'url' => $model->url,
            'describe' => $model->brief,
            'creator' => $userId,
            'matPackId' => $matPackId,
            'groupId' => $groupId,
            'token' => '',
        );
        $soapResult = $this->_soapClient->groupUploadMaterial($arr);
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);

        return $this->mapperJsonResult($json);
    }

    /**
     *2.4.4.教研组资料查询
     * 接口地址    http://主机地址:端口号/ resource/material?wsdl
     *方法名    groupQueryMaterial
     * @param $id           资料id
     * @param $name         名称
     * @param $matType      资料类型(1教案,2讲义)
     * @param $provience    省
     * @param $city         市
     * @param $country      区县
     * @param $gradeid      适用年级 关联年级信息表的年级ID
     * @param $subjectid    科目  关联科目信息表的科目ID
     * @param $versionid    版本 管理版本信息表的版本ID
     * @param $contentType  资料内容类型(1知识点 2课本章节)
     * @param $kid          知识点 关联知识点表的知识点ID。多选，知识点之间使用逗号分隔
     * @param $chapterId    章节id,多个用逗号隔开
     * @param $school       名校 20801    北京四中 20802    人大附中
     * @param $otherSchool  其他名校
     * @param $tags         tags    自定义标签。标签之间使用逗号分隔
     * @param $describe     描述
     * @param $creator      录入人
     * @param $collectUserId   资料收藏人根据收藏人，查询该资料是否收藏isCollected 0未收藏 1收藏
     * @param $groupId      教研组id
     * @param $currPage     当前显示页码，可以为空,默认值1
     * @param $pageSize     每页显示的条数，可以为空，默认值10
     * "data": {
     * "currPage":"当前页码",
     * "totalPages":"总页数",
     * "countSize":"总记录数",
     * "pageSize":"每页数据的条数",
     * "list": [
     * {"id":2001,
     * "name":"",
     * "matType":"1",//资料类型(1教案,2讲义)
     * "provience":"",
     * "city":"",
     * "country":"",
     * "gradeid":"",
     * "gradename":"",
     * "subjectid":"",
     * "subjectname":"",
     * "versionid":"",
     * "versionname":"",
     * "contentType":"",//资料内容类型(1知识点 2课本章节)
     * "chapterId":"",
     * "matDescribe":"",
     * "url":"",
     * "school":"20807",
     * "schoolName":"华师附中",
     * "tags":"",
     * "creator":"",
     * "createTime":"2014-10-31 04:33:26",
     * "updateTime":"" }
     * ],
     *
     * },
     * "resCode": "000000",
     * "resMsg": "成功"
     * @return array
     */
    public function groupQueryMaterial($id = '', $name = '', $matType = '', $provience = '', $city = '', $country = '', $gradeid = '', $subjectid = '', $versionid = '', $contentType = '', $chapKids = '', $school = '', $tags = '', $describe = '', $creator = '', $collectUserId = '', $groupId = '', $currPage, $pageSize)
    {

        $arr = array(
            "id" => $id,
            "name" => $name,
            "matType" => $matType,
            "provience" => $provience,
            "city" => $city,
            "country" => $country,
            "gradeid" => $gradeid,
            "subjectid" => $subjectid,
            "versionid" => $versionid,
            "contentType" => $contentType,
            "chapKids" => $chapKids,
            "school" => $school,
            "tags" => $tags,
            "describe" => $describe,
            "creator" => $creator,
            "collectUserId" => $collectUserId,
            "groupId" => $groupId,
            "currPage" => $currPage,
            "pageSize" => $pageSize
        );
        $soapResult = $this->_soapClient->groupQueryMaterial($arr);
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }
    /**
     * 通过科目/类型/教研组
     * @param $gradeid
     * @param $matType
     *  @param $groupId
     */
    public function getMaterialGroupType($mattype,$gradeid,$userId,$groupId,$currPage, $pageSize)
    {
        return $this->queryClassGroupMaterial('', '', $mattype, '',  $gradeid, '', '', '', '','', '',$userId,$groupId, $currPage, $pageSize);
    }

    /**
     * 通过文件id查询教研组文件详情
     * @param $id
     * @param $groupId
     */
    public function getMaterialGroupById($id,$type,$userId,$groupId)
    {
        return $this->queryClassGroupMaterial($id, '', $type, '',  '', '', '', '', '','', '',$userId,$groupId,'', '');
    }


    /**
     *2.4.4.2.4.5.教研组资料统一查询
     * 接口地址    http://主机地址:端口号/ resource/material?wsdl
     *方法名    groupQueryMaterialUnify
     * @param $name         名称
     * @param $matType      资料类型(1教案,2讲义,3，视频)
     * @param $provience    省
     * @param $city         市
     * @param $country      区县
     * @param $gradeid      适用年级 关联年级信息表的年级ID
     * @param $subjectid    科目  关联科目信息表的科目ID
     * @param $versionid    版本 管理版本信息表的版本ID
     * @param $school       名校 20801    北京四中 20802    人大附中
     * @param $otherSchool  其他名校
     * @param $tags         tags    自定义标签。标签之间使用逗号分隔
     * @param $describe     描述
     * @param $creator      录入人
     * @param $collectUserId   资料收藏人根据收藏人，查询该资料是否收藏isCollected 0未收藏 1收藏
     * @param $groupId      教研组id
     * @param $currPage     当前显示页码，可以为空,默认值1
     * @param $pageSize     每页显示的条数，可以为空，默认值10
     * {
    "data": {
    "currPage":"当前页码",
    "totalPages":"总页数",
    "countSize":"总记录数",
    "pageSize":"每页数据的条数",
    "list": [
    {"id":2001,//id
    "name":"",//名称
    "matType":"1",//资料类型 1教案,2讲义,3 视频，4 资料,5 ppt,6 素材

    "provience":"",//省
    "city":"",//市
    "country":"",//县
    "gradeid":"",//年级id
    "gradename":"",//年级名称
    "subjectid":"",//科目id
    "subjectname":"",//科目名称
    "versionid":"",//版本id
    "versionname":"",//版本名称
    "matDescribe":"",//描述
    "url":"",//url
    "school":"20807",
    "schoolName":"华师附中",
    "tags":"",//标签
    "creator":"",//创建人
    "createTime":"2014-10-31 04:33:26",//上传时间
    “isCollected“:"" 0未收藏 1收藏
    “collectID“:收藏id
    }
    ],

    },
    "resCode": "000000",
    "resMsg": "成功"
    }
     * @return array
     */
    public function groupQueryMaterialUnify( $name = '', $matType = '', $provience = '', $city = '', $country = '', $gradeid = '', $subjectid = '', $versionid = '', $school = '', $tags = '', $describe = '', $creator = '', $collectUserId = '', $groupId = '', $currPage, $pageSize)
    {

        $arr = array(
            "name" => $name,
            "matType" => $matType,
            "provience" => $provience,
            "city" => $city,
            "country" => $country,
            "gradeid" => $gradeid,
            "subjectid" => $subjectid,
            "versionid" => $versionid,
            "school" => $school,
            "tags" => $tags,
            "describe" => $describe,
            "creator" => $creator,
            "collectUserId" => $collectUserId,
            "groupId" => $groupId,
            "currPage" => $currPage,
            "pageSize" => $pageSize
        );
        $soapResult = $this->_soapClient->groupQueryMaterialUnify($arr);
        $result = $this->soapResultToJsonResult($soapResult);
        if ($result->resCode == self::successCode) {
            return $result->data;
        }
        return array();
    }

    /**
     * 2.4.8.修改资料
     * 接口地址	http://主机地址:端口号/resource/material?wsdl
     * 方法名	updateMaterial
     * @param $id     资料id
     * @param $name     名称
     * @param $subjectid    科目 关联科目信息表的科目ID
     * @param $tags     自定义标签。标签之间使用逗号分隔
     * @param $describe 描述
     * @param $url      附件地址
     * {"data":{
    },
    "resCode":"000001",
    "resMsg":"成功"
    }
     * @return ServiceJsonResult
     */
    public function updateMaterial($id,$name,$provience,$city,$country,$gradeid,$subjectid,$versionid,$contentType,$chapKids,$tags,$url,$describe){
        $soapResult = $this->_soapClient->updateMaterial(array('id'=>$id,'name'=>$name,'provience'=>$provience,
            'city'=>$city,'country'=>$country,'gradeid'=>$gradeid,'subjectid'=>$subjectid,'versionid'=>$versionid,
            'contentType'=>$contentType,'chapKids'=>$chapKids,'tags'=>$tags,'url'=>$url,'describe'=>$describe));
        $jsonStr = $this->_soapClient->mapSoapResult($soapResult, new ArrayObject());
        $json = json_decode($jsonStr);
        return $this->mapperJsonResult($json);

    }

    /**
     * 2.4.9.	上传文件
     * @param string $name
     * @param string $matType
     * @param string $department
     * @param string $gradeid
     * @param $subjectid
     * @param $versionid
     * @param $chapterid
     * @param $urls
     * @param $creator
     * @param $access
     * @return array
     */
    public function uploadFiles( $name = '', $matType = '',$department='',$gradeid='',$subjectid,$versionid,$contentType,$chapKids,$urls,$creator,$access)
    {

        $arr = array(
            "name" => $name,
            "matType" => $matType,
           "department"=>$department,
            "gradeid" => $gradeid,
            "subjectid" => $subjectid,
            "versionid" => $versionid,
            "contentType"=>$contentType,
            "chapKids"=>$chapKids,
            "creator" => $creator,
            "urls"=>$urls,
            "access"=>$access
        );
        $soapResult = $this->_soapClient->uploadFiles($arr);
        $result = $this->soapResultToJsonResult($soapResult);
          return $result;
    }

    /**
     * 2.4.10.	修改上传文件
     * @param $id
     * @param string $name
     * @param string $matType
     * @param string $department
     * @param string $gradeid
     * @param $subjectid
     * @param $versionid
     * @param $contentType
     * @param $chapKids
     * @param $urls
     * @param $access
     * @return ServiceJsonResult
     */
    public function modifyFiles($id, $name = '', $matType = '',$department='',$gradeid='',$subjectid,$versionid,$contentType,$chapKids,$urls,$access)
    {

        $arr = array(
            "id"=>$id,
            "name" => $name,
            "matType" => $matType,
            "department"=>$department,
            "gradeid" => $gradeid,
            "subjectid" => $subjectid,
            "versionid" => $versionid,
            "contentType"=>$contentType,
            "chapKids"=>$chapKids,
            "urls"=>$urls,
            "access"=>$access
        );
        $soapResult = $this->_soapClient->modifyFiles($arr);
        $result = $this->soapResultToJsonResult($soapResult);
        return $result;
    }

    /**
     *2.4.10.共享资料
     * 接口地址	http://主机地址:端口号/resource/material?wsdl
     * 方法名	sharedMaterial
     * @param $id           资料id
     * @param $shareUserId  共享用户id
     * @param $classId      班级id 多个班级用逗号隔开
     * @param $groupId      教研组id 多个教研组用逗号隔开
     * 返回的JSON：
    {"data":{
    "shareId":""
    },
    "resCode":"000001",
    "resMsg":"录入成功"
     *
     * @return null
     */
    public function sharedMaterial($id,$shareUserId,$classId,$groupId){
        $soapResult = $this->_soapClient->sharedMaterial(array('id' => $id, 'shareUserId' => $shareUserId,'classId'=>$classId,'groupId'=>$groupId));
         $result= $this->soapResultToJsonResult($soapResult);
        return  $result;


    }




}